<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Models\CampaignSubmission;
use App\Models\User;
use Illuminate\Support\Str;

class CampaignSubmissionService
{
    public function createSubmission(Campaign $campaign, array $data, ?User $user = null): CampaignSubmission
    {
        $guestToken = $user ? null : ($data['guest_token'] ?? Str::random(40));

        $submission = CampaignSubmission::create([
            'campaign_id' => $campaign->id,
            'user_id' => $user?->id,
            'guest_token' => $guestToken,
            'submitted_name' => $data['submitted_name'],
            'submitted_notes' => $data['submitted_notes'] ?? null,
            'submitted_place_hint' => $data['submitted_place_hint'] ?? null,
            'matched_spot_id' => $data['matched_spot_id'] ?? null,
            'user_confirmed_spot' => $data['user_confirmed_spot'] ?? false,
            'wants_to_recommend' => $data['wants_to_recommend'] ?? true,
            'consent_to_contact' => $data['consent_to_contact'] ?? false,
            'consent_to_publish_quote' => $data['consent_to_publish_quote'] ?? false,
            'consent_to_terms' => $data['consent_to_terms'] ?? false,
            'status' => 'pending',
            'ai_result' => $this->getPlaceholderAiResult($data['submitted_name']),
        ]);

        return $submission;
    }

    protected function getPlaceholderAiResult(string $name): array
    {
        return [
            'confidence' => 0.85,
            'suggested_category' => 'Restaurant',
            'suggested_tags' => ['local', 'food'],
            'search_query' => $name . ' Tenerife',
            'processed_at' => now()->toDateTimeString(),
        ];
    }

    public function linkUserToSubmissions(User $user, string $guestToken): int
    {
        return CampaignSubmission::where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id, 'guest_token' => null]);
    }
}
