# Privacy Management

## Overview
BenLocal provides granular privacy controls to users, allowing them to manage their digital footprint.

## Privacy Settings
Users can configure the following settings via `PUT /api/gdpr/privacy`:

- **Profile Visibility**:
  - `public`: Visible to everyone.
  - `private`: Visible only to the user.
  - `friends`: Visible only to followers.
- **Show Location**: Toggle visibility of the residence region/area on the profile.
- **Show Reviews**: Toggle visibility of the reviews list on the public profile.

## Seeding & Testing
To seed privacy scenarios:
`php artisan db:seed --class=UserPrivacyPreferenceSeeder`

Testing scenarios include:
- Fully public profile
- Private profile
- Reviews hidden
- Followers hidden
- Public local profile
- Business owner visibility

# GDPR Compliance

## Overview
Phase 15 implements full GDPR compliance, focusing on data portability and the right to erasure.

## Data Export
- Service: `DataExportService`
- Endpoint: `POST /api/gdpr/export`
- Admin: **GDPR > Export Requests** in Filament.

To seed export requests:
`php artisan db:seed --class=GdprExportSeeder`

## Consent Auditing
- Service: `ConsentAuditService`
- Table: `privacy_audit_logs`
- Admin: **GDPR > Privacy Audit Logs** in Filament.

To seed consent history:
`php artisan db:seed --class=ConsentHistorySeeder`

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
