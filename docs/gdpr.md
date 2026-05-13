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
