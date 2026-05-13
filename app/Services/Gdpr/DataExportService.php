<?php

namespace App\Services\Gdpr;

use App\Models\GdprExport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataExportService
{
    public function createRequest(User $user): GdprExport
    {
        return GdprExport::create([
            'user_id' => $user->id,
            'requested_at' => now(),
        ]);
    }

    public function generateExport(GdprExport $export): string
    {
        $user = $export->user;
        $data = $this->gatherUserData($user);

        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'exports/' . Str::uuid() . '_data_export_' . $user->id . '.json';

        Storage::disk('local')->put($filename, $json);

        $export->update([
            'export_path' => $filename,
            'completed_at' => now(),
        ]);

        return $filename;
    }

    protected function gatherUserData(User $user): array
    {
        return [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'country' => $user->country,
                'city' => $user->city,
                'created_at' => $user->created_at?->toIso8601String(),
            ],
            'recommendations' => $user->recommendations()->with('spot')->get()->map(fn($r) => [
                'spot' => $r->spot?->name,
                'title' => $r->getTranslations('title'),
                'motivation' => $r->getTranslations('motivation'),
                'created_at' => $r->created_at?->toIso8601String(),
            ])->toArray(),
            'reviews' => $user->reviews()->with('spot')->get()->map(fn($r) => [
                'spot' => $r->spot?->name,
                'rating' => $r->overall_rating,
                'text' => $r->getTranslations('review_text'),
                'created_at' => $r->created_at?->toIso8601String(),
            ])->toArray(),
            'visits' => $user->spotVisits()->with('spot')->get()->map(fn($v) => [
                'spot' => $v->spot?->name,
                'visited_at' => $v->visited_at?->toIso8601String(),
            ])->toArray(),
        ];
    }
}
