# Account Lifecycle & Deletion

## Overview
Handles account deletion and data anonymization ("Right to be Forgotten").

## Deletion Process
1. User requests deletion via `DELETE /api/account`.
2. `AccountDeletionService` records the request.
3. `UserAnonymizationService` scrubs personal data:
   - Name changed to "Deleted User".
   - Email anonymized.
   - Avatar removed.
   - Social provider IDs cleared.
4. Contributions (Reviews, Recommendations) are preserved but linked to the anonymized user.
5. User record is soft-deleted.

## Seeded Scenarios
Run `php artisan db:seed --class=AnonymizedUserSeeder` to test:
- **Active user**: Normal account.
- **User requested deletion**: Pending request.
- **User anonymized**: Account already scrubbed.
- **Business owner deleted**: Owner account anonymized.
- **Trusted local deleted**: Local status preserved but name anonymized.

## Feed Integrity
Anonymized users appear as "Deleted User" in feeds and review lists, ensuring that historical content remains useful without exposing personal data.
