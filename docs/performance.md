# Performance & Optimization

## API Query Optimization
To ensure production-scale performance, all major API endpoints have been optimized to avoid N+1 query problems and utilize efficient database indexing.

## Caching Strategy (Phase 17)
BenLocal uses a multi-layered caching strategy to reduce database load and improve response times.

### Cache Implementation
- **Driver:** Redis is the recommended cache driver for production.
- **Tags:** Cache tags are used to allow targeted invalidation (e.g., `spots`, `feeds`, `discovery`).
- **TTL:** Time-to-live varies by content type (e.g., 60 minutes for discovery, 24 hours for categories).

### System Translation Keys
The following keys in `system.php` and `locales/*.json` are used for performance-related UI states:
- `system.loading`: Generic loading indicator.
- `system.cached_content`: Notifies the user that content is served from cache.
- `system.slow_connection`: Warning when the connection speed is below threshold.
- `system.last_updated`: Shows when the data was last successfully fetched.

## Media Optimization
Image placeholders and lazy loading are used to optimize initial page weight. Future implementations will include an image processing pipeline for on-the-fly resizing and optimization.

### UI Indicators
- `system.image_optimizing`: Shown when a high-resolution image is being processed in the background.

## Benchmark Testing

To test performance at scale, use the `PerformanceBenchmarkSeeder`.

1. Configure volumes in `.env` (e.g., `BENLOCAL_BENCHMARK_SPOTS=1000`).
2. Run `php artisan db:seed --class=PerformanceBenchmarkSeeder`.
3. Use the following endpoints for benchmarking:
   - `GET /api/discovery`: Test map marker clustering and bounds queries.
   - `GET /api/feed`: Test pagination with large timeline datasets.
   - `GET /api/spots/{id}`: Test caching of spot details with many reviews.

## Performance Risks
- **Map Markers**: Loading >500 markers in a single viewport without clustering can degrade frontend performance.
- **Feed Pagination**: Offset-based pagination becomes slow with >100k events; prefer cursor-based if possible.
