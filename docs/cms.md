# CMS Content Management

BenLocal uses a custom CMS for managing public pages and FAQs.

## Models

### Page
- `slug`: Unique identifier for the page.
- `title`: Translatable title.
- `intro`: Translatable introduction text.
- `content`: Translatable rich text content.
- `seo_title`: Translatable SEO title.
- `seo_description`: Translatable SEO description.
- `is_system_page`: Boolean to protect core pages from deletion.
- `published_at`: Timestamp for scheduled publishing.

### FAQ
- `question`: Translatable question.
- `answer`: Translatable rich text answer.
- `category`: Category for grouping FAQs.
- `sort_order`: Integer for ordering.
- `is_active`: Boolean to toggle visibility.

## API Endpoints

- `GET /api/pages/{slug}`: Returns localized page content.
- `GET /api/faqs`: Returns list of all active FAQs grouped/sorted.

## Filament Management

Admin can manage all CMS content via the **CMS & Legal** navigation group in the Filament panel.
- Rich text editing for content.
- Multilingual support via tabs for each available language.
- Scheduled publishing via `published_at`.
- SEO metadata configuration.

## Translations & Slugs

CMS content uses translatable JSON fields. The API returns content based on the requested locale.

### UI Translation Keys
Frontend UI elements for CMS are located in `resources/js/locales/*.json` under the `cms` key:
- `cms.page_not_found`
- `cms.last_updated`
- `cms.read_more`
- `cms.back_to_help`

### Page Slug Mapping
Standard slugs used in the system:
- `how-benlocal-works`
- `what-is-a-local`
- `hidden-gems`
- `community-guidelines`
- `privacy-policy`
- `terms-of-service`

### Fallback Behavior
- If a translation is missing for a specific field in the requested locale, the system falls back to the default locale (English).
- If an unpublished page is requested, the API returns a 403 or 404 status.
