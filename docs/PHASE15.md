# BenLocal - Fase 15: GDPR, Privacy & Account Data Management

## Overview
Phase 15 implements full GDPR compliance and advanced privacy management tools, allowing users to control their data and privacy footprint.

## Core Services

### 1. DataExportService
Handles the extraction of all personal data associated with a user into a portable JSON format.
- Gathers profile info, recommendations, reviews, and visit history.
- Generates a timestamped JSON file stored securely in the `exports` directory.
- Managed via `GdprExport` model.

### 2. UserAnonymizationService
Implements the "right to be forgotten" while maintaining platform integrity.
- Replaces personal identifiers (name, email) with anonymized placeholders (e.g., "Deleted User").
- Scrubs sensitive fields like avatars, social providers, and IP addresses.
- Retains contributions (reviews, recommendations) in an anonymized state.

### 3. AccountDeletionService
Coordinates the account deletion lifecycle.
- Records the deletion request.
- Logs the final audit event.
- Triggers anonymization and soft deletes the user record.

### 4. ConsentAuditService
Provides a tamper-evident log of all privacy-related actions.
- Logs cookie consent updates.
- Logs visibility and privacy setting changes.
- Records data export and deletion events.
- Stores IP address and User Agent for compliance verification.

## API Endpoints

### 1. GDPR Management
- `POST /api/gdpr/export`: Request a full data export.
- `PUT /api/gdpr/privacy`: Update profile visibility and marketing consent.
- `DELETE /api/account`: Trigger account deletion and anonymization.
- `POST /api/consent`: Update cookie consent (logged in audit trail).

### 2. Privacy Settings
- **Profile Visibility**: `public`, `private`, or `friends`.
- **Location Privacy**: Option to hide residence location.
- **Contribution Privacy**: Option to hide reviews from public profile.

## Filament Admin (GDPR Dashboard)
A dedicated **GDPR** navigation group includes:
- **Export Requests**: View and process user data export requests with download capability.
- **Deletion Requests**: Monitor and execute account deletion/anonymization.
- **Privacy Audit Logs**: Searchable database of all privacy-related actions taken by users.
- **Cookie Consents**: View current user consent states.

## Database Models & Migrations
- `GdprExport`: Tracks export requests and file paths.
- `GdprDeletion`: Tracks account deletion requests and completion status.
- `PrivacyAuditLog`: Stores the history of consent and privacy changes.
- Added `profile_visibility`, `show_location`, and `show_reviews` to the `users` table.

## Seeders
`GdprSeeder` generates:
- Pending and completed export requests.
- Pending deletion requests.
- Sample anonymized "Deleted User".
- Privacy audit trail entries for testing.

## Testing
`Tests\Feature\Gdpr\GdprTest` covers:
- Data export request flow.
- Privacy settings updates and persistence.
- Account deletion and anonymization verification.
- Audit log generation for all privacy actions.
