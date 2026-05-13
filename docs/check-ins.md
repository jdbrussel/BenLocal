# Check-ins & Verified Visits

## Overview
Visit verification allows users to prove they have actually been at a spot, which significantly increases the credibility and weight of their reviews.

## Verification Methods

### 1. GPS Check-in
Users can confirm their presence by sharing their GPS location. 
- **Rule:** The user must be within a predefined distance (default 200m) from the spot coordinates.
- **Score:** High verification score if within range.

### 2. QR Check-in
Spots can provide a QR code for users to scan.
- **Rule:** The scan must include a valid, spot-specific QR token.
- **Score:** Maximum verification score.

### 3. Manual Log
Users can manually log a visit without immediate verification.
- **Score:** Low/Zero verification score.
- **Status:** Unverified.

## Translations

All visit-related UI elements use the following translation keys:

### Translation Groups

| Key | Description |
|-----|-------------|
| `visit.check_in` | Button text "I'm here" |
| `visit.check_in_title` | Title for the check-in modal |
| `visit.check_in_text` | Explanation of why check-in is useful |
| `visit.location_permission_title` | Title for location permission request |
| `visit.location_permission_text` | Privacy explanation for location usage |
| `visit.allow_location` | Action button to allow location |
| `visit.manual_visit` | Option to log visit manually |
| `visit.scan_qr` | Action to scan QR code |
| `visit.gps_verified` | Label for GPS-verified visits |
| `visit.qr_verified` | Label for QR-verified visits |
| `visit.manual_logged` | Label for manually added visits |
| `visit.verified_visit` | General "Verified visit" indicator |
| `visit.unverified_visit` | Indicator for unverified visits |
| `visit.too_far_title` | Warning title when GPS distance is too large |
| `visit.too_far_text` | Warning text when GPS distance is too large |
| `visit.check_in_success` | Success message after check-in |
| `visit.check_in_failed` | Error message when check-in fails |
| `visit.write_review_after_visit` | Call to action to write a review |
| `visit.recent_visits` | Title for visit history |
| `visit.no_visits` | Empty state message for visits |
| `visit.visit_source` | Label for the source of a visit |
| `visit.source.manual` | "Manual" source |
| `visit.source.gps` | "GPS" source |
| `visit.source.qr` | "QR code" source |
| `visit.source.reservation` | "Reservation" source |
| `visit.source.owner_confirmation` | "Confirmed by owner" source |
| `visit.verification_score` | Label for the numeric verification score |
| `visit.distance_warning` | Technical warning about distance |
| `visit.privacy_note` | Note about location privacy |

### Fallback Behavior
- **Frontend (JS):** If a key is missing in the current locale (NL/ES), the PWA should fallback to the EN translation.
- **Backend (PHP):** Standard Laravel fallback to `config('app.fallback_locale')`.

## Usage in UI
- **Spot Detail Page:** "I'm here" button triggers the check-in flow.
- **Profile/Activity:** "Recent visits" list showing verification badges.
- **Review List:** Verified visits are marked with a badge, and the review weight is visually indicated.

## Testing with Demo Data

The following scenarios are seeded for testing:

### User Scenarios
- **Jan**: Many verified GPS visits to restaurants in Tenerife. High credibility reviews.
- **Carlos**: GPS visits to authentic Canarian spots (Guachinches).
- **Sofie**: QR visits to Belgian community favorites.
- **Emma**: Mixed manual and GPS visits to beach bars.
- **Markus**: Reservation-style visits to fine dining spots.

### Technical Scenarios
- **Suspicious Visits**: Seeded with coordinates in La Laguna for spots in Costa Adeje (approx. 80km distance).
- **QR Tokens**: Includes `VALID_QR_123`, `EXPIRED_TOKEN`, and `REVOKED_TOKEN` examples.
- **Verification Scores**: 
    - GPS: 0.85 - 1.00
    - QR: 0.95 - 1.00
    - Reservation: 0.75 - 0.90
    - Manual: 0.20 - 0.50
    - Suspicious: 0.00 - 0.20

## Privacy Handling
- **Exact Coordinates**: Stored in `latitude` and `longitude` fields in `spot_visits` table.
- **Public Exposure**: Coordinates are NEVER shown publicly. Only the verification status and source are displayed in the frontend.
- **Admin Access**: Admin can inspect exact coordinates and distance calculations via the Filament `SpotVisitResource`.
