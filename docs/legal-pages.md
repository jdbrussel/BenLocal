# Legal Pages

Legal pages are managed via the CMS and translated into all supported languages.

## Policy Pages
The following legal and policy pages are available as `Page` models:

| Page | Slug | Translation Key |
|------|------|-----------------|
| Privacy Policy | `privacy-policy` | `pages.privacy_policy` |
| Cookie Policy | `cookie-policy` | `pages.cookie_policy` |
| Terms of Service | `terms-of-service` | `pages.terms_of_service` |
| AI Translation Disclaimer | `ai-translation-disclaimer` | `pages.ai_translation_disclaimer` |
| Review Policy | `review-policy` | `pages.review_policy` |
| Community Guidelines | `community-guidelines` | `pages.community_guidelines` |

## Rendering & Localization
Legal pages are rendered in the frontend using the `cms.legal_notice` UI key and terms acceptance checkboxes (`cms.accept_terms`, `cms.accept_privacy`).

### Translation Files
- Backend (Page Names): `lang/{locale}/pages.php`
- Frontend UI: `resources/js/locales/{locale}.json` (under `cms` key)

### Fallback
The system uses standard Laravel/CMS fallback logic to ensure legal content is always visible, defaulting to English if the specific locale content is not available.
