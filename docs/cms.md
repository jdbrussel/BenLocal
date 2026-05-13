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

CMS content uses translatable JSON fields via `spatie/laravel-translatable`. The API returns content based on the requested locale (via `Accept-Language` header or `locale` query parameter).

### UI Translation Keys
Frontend UI elements for CMS are located in `resources/js/locales/*.json` under the `cms` key. These should be used for consistent UI rendering:

| Key | Description |
|-----|-------------|
| `cms.page_not_found` | Error message when a page doesn't exist. |
| `cms.unpublished` | Message for pages that are not yet public. |
| `cms.last_updated` | Label for the last modification date. |
| `cms.read_more` | "Read More" button label. |
| `cms.back_to_help` | Navigation back to Help Center. |
| `cms.contact_support` | Link to contact support. |
| `cms.table_of_contents` | Header for TOC section. |
| `cms.related_articles` | Header for related help articles. |
| `cms.legal_notice` | Footer/Header legal notice text. |
| `cms.accept_terms` | Label for "I accept terms" checkbox. |
| `cms.accept_privacy` | Label for "I accept privacy policy" checkbox. |
| `cms.search_help` | Placeholder for help search bar. |
| `cms.no_help_results` | Message when search returns no results. |

### Page Slug Mapping
Standard slugs used in the system for deep-linking and specific UI features:

- `how-benlocal-works`
- `what-is-a-local`
- `hidden-gems`
- `community-guidelines`
- `review-policy`
- `local-verification-policy`
- `privacy-policy`
- `cookie-policy`
- `terms-of-service`
- `ai-translation-disclaimer`
- `business-owner-guidelines`
- `safety-and-trust`
- `about-benlocal`
- `contact`

### Fallback Behavior
1. **Field Level**: If a translation is missing for a specific field in the requested locale, the system falls back to the default locale (`app.fallback_locale`, typically English).
2. **Page Visibility**: If a page exists but is not published (`published_at` is null or in the future), the API returns a 404/403 to prevent access.
3. **Locale Support**: If the requested locale is not supported, the system defaults to the primary application locale.

## Content Rendering
- **HTML Content**: The `content` and `answer` (FAQ) fields support HTML. The frontend should sanitize and render this content appropriately (e.g., using `v-html` in Vue or `dangerouslySetInnerHTML` in React).
- **SEO**: Always use `seo_title` and `seo_description` when provided for static page meta tags.
