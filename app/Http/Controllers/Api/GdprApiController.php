<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use App\Services\Gdpr\ConsentAuditService;
use App\Services\Gdpr\DataExportService;
use Illuminate\Http\Request;

class GdprApiController extends Controller
{
    public function __construct(
        protected DataExportService $exportService,
        protected ConsentAuditService $auditService
    ) {}

    public function requestExport(Request $request)
    {
        $export = $this->exportService->createRequest($request->user());

        $this->auditService->logAction($request->user(), 'data_export_requested');

        return response()->json([
            'message' => 'Data export requested. It will be processed soon.',
            'export' => $export
        ]);
    }

    public function updatePrivacySettings(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'profile_visibility' => ['sometimes', 'string', 'in:public,private,friends'],
            'show_location' => ['sometimes', 'boolean'],
            'show_reviews' => ['sometimes', 'boolean'],
            'marketing_consent' => ['sometimes', 'boolean'],
        ]);

        $oldValues = [
            'profile_visibility' => $user->profile_visibility,
            'show_location' => $user->show_location,
            'show_reviews' => $user->show_reviews,
            'marketing_consent' => $user->notificationPreferences?->marketing ?? false,
        ];

        $user->update($request->only(['profile_visibility', 'show_location', 'show_reviews']));

        if (isset($validated['marketing_consent'])) {
            $user->notificationPreferences()->updateOrCreate(
                ['user_id' => $user->id],
                ['marketing' => $validated['marketing_consent']]
            );
        }

        $this->auditService->logVisibilityChange($user, $oldValues, $validated);

        return response()->json([
            'message' => 'Privacy settings updated successfully',
            'user' => $user->fresh(['notificationPreferences'])
        ]);
    }
}
