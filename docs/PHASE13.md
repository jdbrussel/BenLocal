# BenLocal - Fase 13: Check-ins & Verified Visits

## Overview
Phase 13 introduces visit verification to increase the credibility of reviews. Users can now check in at spots using GPS or QR codes, or manually log their visits.

## Core Components

### 1. SpotVisit Model & Service
The `SpotVisit` model tracks user visits. The `SpotVisitService` handles the creation and linking of visits to reviews.

### 2. VisitVerificationService
Handles the logic for:
- **GPS Verification**: Compares user coordinates with spot coordinates (Haversine formula). Visits within 100m are fully verified. Visits further than 500m are marked as suspicious.
- **QR Verification**: Validates a unique `qr_token` assigned to each spot.

### 3. API Endpoints
- `POST /api/spots/{spot}/check-in`: GPS-based check-in.
- `POST /api/spots/{spot}/qr-check-in`: QR-code-based check-in.
- `GET /api/me/visits`: Retrieve personal visit history.

### 4. Review Credibility
Reviews linked to a verified visit (`verification_score >= 0.5`) receive a `verified_visit` badge, which increases their influence on the spot's overall score.

## Business Rules
- **GPS Check-in**: Checks distance to spot.
- **QR Check-in**: Uses a unique token stored in the `spots` table.
- **Manual Logging**: Allowed for personal history but carries lower verification trust.
- **Suspicious Activity**: Visits far from the spot are flagged for moderation.

## Filament Admin
- **Spot Visits Resource**: Manage and monitor visits, specifically flagged/suspicious ones.
- **QR Token Management**: Admins can generate or view QR tokens for spots.

## Seeders
`SpotVisitSeeder` generates:
- GPS verified visits.
- QR-based visits.
- Manual visits.
- Suspicious (far-away) visits.
- Reviews linked to verified visits.

## Testing
- `Tests\Feature\VisitVerificationTest`:
    - GPS distance validation.
    - QR check-in functionality.
    - Automatic linking of verified visits to recent reviews.
