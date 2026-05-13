# Business Claims & Owner Dashboard

This document describes the Business Claim flow, Owner Dashboard, and how to test them using the provided demo data.

## Business Claim Statuses

| Status | Description |
|--------|-------------|
| `pending` | The owner has submitted proof and is waiting for admin approval. |
| `approved` | The claim is verified. The user gets the `OWNER` role and access to the `/owner` panel. |
| `rejected` | The claim was denied. A rejection reason is provided to the claimant. |

## Demo Owner Accounts

Use these accounts to test the Owner Dashboard (`/owner`):

| Email | Name | Spot | Role |
|-------|------|------|------|
| `owner.bodega@example.com` | Mario Rossi | Bodega San Miguel | Owner |
| `owner.cafevlaanderen@example.com` | Jan Janssens | Café Vlaanderen | Owner |
| `manager.beachbar@example.com` | John Smith | Puerto Beach Bar | Editor |

Password for all demo accounts: `password`

## Seeding & Test Scenarios

Run `php artisan db:seed` to populate the following scenarios:

### 1. Claim Token Flow
- **Valid Token:** A token exists for an unclaimed spot.
- **Expired Token:** Test that the system rejects expired tokens.
- **Used Token:** Test that tokens cannot be reused.
- **Campaign Token:** Tokens linked to "Tafelen in Tenerife".

### 2. Claim Requests
- **Pending Claim:** "Restaurante Mar Azul" has a pending claim from `owner.marazul@example.com`.
- **Rejected Claim:** "Puerto Beach Bar" has a rejected claim.
- **Duplicate Claim:** A pending claim for "Bodega San Miguel" exists even though it's already claimed (test for moderation).

### 3. Owner Dashboard Activity
- **Reviews & Responses:** Log in as `owner.cafevlaanderen@example.com` to see existing reviews and responses.
- **Spot Editing:** Owners can edit photos and contact info but not rankings or names.

## Testing Permissions

- **Approved Owner:** Can edit spot details and respond to reviews.
- **Manager:** Can respond to reviews and edit spot details.
- **Editor:** Can only edit photos and contact info (as configured in `SpotOwnerRoleSeeder`).
- **Pending/Rejected:** Should have no access to the `/owner` panel for the specific spot.
