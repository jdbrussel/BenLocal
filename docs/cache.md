# Cache Management

## Strategy
BenLocal implements an aggressive caching strategy using Redis to ensure sub-100ms response times for the most frequent API calls.

## Cache Tags
We use Laravel's cache tagging feature to organize and invalidate cache entries efficiently:
- `discovery`: Global discovery data.
- `spots`: Spot details and listings.
- `feeds`: User-specific activity feeds.
- `categories`: Category specifications and options.
- `system`: System-wide settings and health status.

## UI & Offline Support
The PWA leverages the cache for offline capabilities and resilience:
- **Offline State:** When the network is unavailable, the PWA switches to `system.offline_mode`.
- **Cache Banner:** If content is served from the local cache instead of the network, `system.cached_content` is displayed.
- **Manual Refresh:** Users can trigger a cache refresh via `system.refresh`.

## Maintenance
The `benlocal:clear-stale-cache` command is scheduled to run daily to remove old entries that are no longer referenced.

## Testing Cache Behavior

Use `CacheScenarioSeeder` to create predictable data for testing invalidation:

1. Run `php artisan db:seed --class=CacheScenarioSeeder`.
2. Request `GET /api/spots/{id}` for the created spot to prime the cache.
3. Update the spot via API or Database.
4. Verify that `Cache::tags(['spots'])->flush()` or the maintenance command clears the stale data.
