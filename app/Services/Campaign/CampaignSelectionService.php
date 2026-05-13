<?php

namespace App\Services\Campaign;

use App\Models\CampaignRecommendation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class CampaignSelectionService
{
    public function shortlist(CampaignRecommendation $recommendation, bool $status = true): bool
    {
        return $recommendation->update([
            'publication_status' => $status ? 'shortlisted' : 'pending'
        ]);
    }

    public function selectForMagazine(CampaignRecommendation $recommendation, bool $status = true): bool
    {
        return $recommendation->update([
            'selected_for_publication' => $status,
            'publication_status' => $status ? 'selected_for_magazine' : 'shortlisted'
        ]);
    }

    public function exportCampaignData(int $campaignId, string $format = 'json')
    {
        $data = CampaignRecommendation::where('campaign_id', $campaignId)
            ->with(['spot', 'user', 'submission'])
            ->get()
            ->map(function ($rec) {
                return [
                    'spot_name' => $rec->spot?->name,
                    'user_name' => $rec->user?->name,
                    'motivation' => $rec->submission?->submitted_notes,
                    'status' => $rec->publication_status,
                    'selected' => $rec->selected_for_publication,
                ];
            });

        if ($format === 'csv') {
            return $this->toCsv($data);
        }

        return $data;
    }

    protected function toCsv(Collection $data): string
    {
        if ($data->isEmpty()) return '';

        $output = fopen('php://temp', 'r+');
        fputcsv($output, array_keys($data->first()));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
