# BenLocal - Fase 14: Notifications

## Overview
Phase 14 implements a comprehensive notification system including in-app (database), email, and a push-ready structure.

## Core Components

### 1. NotificationService
A central service (`App\Services\NotificationService`) to handle sending notifications while respecting user preferences.

### 2. Notification Classes
Implemented Laravel notifications for the following events:
- **NewFollowerNotification**: When a user follows another.
- **TaggedInReviewNotification**: When a user is tagged in a review.
- **ReviewValidatedNotification**: When a user's review is validated.
- **RecommendationConfirmedNotification**: When a recommendation is confirmed.
- **HiddenGemTrendingNotification**: When a spot in the user's area starts trending.
- **LocalStatusApprovedNotification**: When a user's local status is approved.
- **BusinessClaimUpdateNotification**: When there's an update on a spot claim.
- **OwnerResponseNotification**: When a business owner responds to a review.
- **CampaignSelectionNotification**: When a user is selected for a campaign.

### 3. Database Notifications
Notifications are stored in the `notifications` table and can be retrieved via the API.

### 4. Notification Preferences
Users can manage their preferences for different types of notifications and channels (Email/Push).
Stored in the `notification_preferences` table.

### 5. API Endpoints
- `GET /api/notifications`: Retrieve user notifications (paginated).
- `POST /api/notifications/{id}/read`: Mark a specific notification as read.
- `POST /api/notifications/read-all`: Mark all notifications as read.
- `GET /api/me/notification-preferences`: Get current user notification preferences.
- `PUT /api/me/notification-preferences`: Update notification preferences.

### 6. Filament Admin
- **Notification Logs**: View all sent notifications in the admin panel.
- **Failed Notifications**: (Placeholder) Logs for failed notification attempts.
- **Manual Notification**: (Placeholder) Interface for admins to send manual notifications.

## Testing
- `Tests\Feature\NotificationTest`:
    - Verified notifications are created from events.
    - Verified user preferences are respected.
    - Verified read/unread functionality.
    - Verified API endpoints for preferences.

## Seeders
`NotificationSeeder` generates:
- Unread and read notifications.
- Notifications of multiple types.
- Users with different preference scenarios.
