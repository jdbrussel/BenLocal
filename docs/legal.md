# Legal Pages

BenLocal maintains several legal pages required for compliance and user trust.

## Required Pages

The following pages are seeded as system pages:
- **Privacy Policy**: GDPR compliance and data handling.
- **Cookie Policy**: Cookie usage and consent information.
- **Terms of Service**: General usage terms.
- **AI Translation Disclaimer**: Notice about automated translations.
- **Review Policy**: Rules for submitting content.
- **Local Verification Policy**: Standards for trusted locals.
- **Community Guidelines**: Expected behavior on the platform.

## Management

These pages are marked as `is_system_page = true` to prevent accidental deletion in the admin panel. They can still be edited to update content.

## Rendering

The frontend fetches these pages by their fixed slugs via the `/api/pages/{slug}` endpoint. Fallback logic is handled by the `Spatie\Translatable` package and the `CmsController`.
