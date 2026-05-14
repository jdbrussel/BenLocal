<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignSubmission;
use App\Services\Campaign\CampaignSpotMatchingService;
use App\Services\Campaign\CampaignSubmissionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignLandingController extends Controller
{
    public function show(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Campaign/Landing', [
            'campaign' => $campaign
        ]);
    }

    public function searchSpots(Request $request, CampaignSpotMatchingService $matchingService)
    {
        $query = $request->input('query');
        $regionId = $request->input('region_id');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $matches = $matchingService->findMatches($query, $regionId);

        return response()->json($matches);
    }

    public function submit(Request $request, string $slug, CampaignSubmissionService $submissionService)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'submitted_name' => 'required|string|max:255',
            'submitted_notes' => 'nullable|string',
            'submitted_place_hint' => 'nullable|string',
            'matched_spot_id' => 'nullable|exists:spots,id',
            'user_confirmed_spot' => 'boolean',
            'consent_to_terms' => 'accepted',
            'guest_token' => 'nullable|string',
        ]);

        $submission = $submissionService->createSubmission($campaign, $validated, $request->user());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Submission received',
                'submission_id' => $submission->id,
                'guest_token' => $submission->guest_token,
            ]);
        }

        return redirect()->route('campaign.success', [
            'slug' => $campaign->slug,
            'submission' => $submission->id
        ]);
    }

    public function success(string $slug, int $submissionId)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        $submission = CampaignSubmission::findOrFail($submissionId);

        return Inertia::render('Campaign/Success', [
            'campaign' => $campaign,
            'submission' => $submission,
        ]);
    }
}
