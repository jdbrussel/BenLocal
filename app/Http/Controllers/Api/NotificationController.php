<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get user notifications.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'unread_count' => $user->unreadNotifications()->count(),
            ],
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'new_followers' => 'boolean',
            'review_replies' => 'boolean',
            'recommendation_validation' => 'boolean',
            'tagged_in_review' => 'boolean',
            'hidden_gem_updates' => 'boolean',
            'local_status_updates' => 'boolean',
            'spot_updates' => 'boolean',
            'business_claim_updates' => 'boolean',
            'owner_responses' => 'boolean',
            'campaign_selections' => 'boolean',
            'marketing' => 'boolean',
            'email_enabled' => 'boolean',
            'push_enabled' => 'boolean',
        ]);

        $preferences = $user->notificationPreferences()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json([
            'message' => 'Preferences updated successfully',
            'data' => $preferences
        ]);
    }

    /**
     * Get notification preferences.
     */
    public function getPreferences(Request $request)
    {
        $preferences = $request->user()->notificationPreferences()->firstOrCreate([
            'user_id' => $request->user()->id
        ]);

        return response()->json(['data' => $preferences]);
    }
}
