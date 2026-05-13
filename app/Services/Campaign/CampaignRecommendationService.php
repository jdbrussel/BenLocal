<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Models\CampaignRecommendation;
use App\Models\CampaignSubmission;
use App\Models\Recommendation;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CampaignRecommendationService
{
    public function createFromSubmission(CampaignSubmission $submission): ?CampaignRecommendation
    {
        if (!$submission->user_id || (!$submission->matched_spot_id && !$submission->created_spot_id)) {
            return null;
        }

        return DB::transaction(function () use ($submission) {
            $spotId = $submission->matched_spot_id ?: $submission->created_spot_id;
            $spot = Spot::find($spotId);

            $recommendation = Recommendation::updateOrCreate(
                [
                    'user_id' => $submission->user_id,
                    'spot_id' => $spotId,
                ],
                [
                    'region_id' => $spot->region_id,
                    'title' => [
                        'en' => "Recommendation from {$submission->campaign->name}",
                        'nl' => "Aanbeveling via {$submission->campaign->getTranslation('name', 'nl')}",
                    ],
                    'motivation' => [
                        'en' => $submission->submitted_notes,
                        'nl' => $submission->submitted_notes,
                    ],
                    'original_language' => 'en', // Default or detect
                    'confidence_score' => 0.7,
                    'moderation_status' => 'pending',
                ]
            );

            return CampaignRecommendation::create([
                'campaign_id' => $submission->campaign_id,
                'submission_id' => $submission->id,
                'recommendation_id' => $recommendation->id,
                'user_id' => $submission->user_id,
                'spot_id' => $spotId,
                'publication_status' => 'pending',
            ]);
        });
    }
}
