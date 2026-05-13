# Queues & Background Processing

## Queue Setup
BenLocal uses Laravel Queues to handle time-consuming tasks outside the request-response cycle.

- **Driver:** Redis (recommended for production).
- **Default Queue:** `default`.
- **Priority Queues:** `high`, `low`.

## Background Tasks (Phase 17)
Several background jobs and scheduled commands have been implemented:
- `benlocal:recalculate-rankings`: Updates spot rankings based on recent activity.
- `benlocal:recalculate-reputation`: Updates user trust scores.
- `benlocal:refresh-community-profiles`: Aggregates community data.
- `benlocal:detect-hidden-gems`: Identifies spots with high local trust but low tourist saturation.
- `benlocal:clear-stale-cache`: Maintenance task for cache cleanup.

## UI Integration
The frontend provides visibility into background task status:
- `system.queue_processing`: General indicator that background work is happening.
- `system.job_pending`: Task is in the queue.
- `system.job_processing`: Task is currently being handled.
- `system.job_completed`: Task finished successfully.
- `system.job_failed`: Task encountered an error.

## Testing Queues

Use `QueueJobDemoSeeder` to prepare data for job processing tests:

1. Run `php artisan db:seed --class=QueueJobDemoSeeder`.
2. Run the processing commands:
   - `php artisan benlocal:recalculate-rankings`
   - `php artisan benlocal:recalculate-reputation`
3. Verify that the jobs are dispatched to Redis and processed by a worker:
   ```bash
   php artisan queue:work
   ```
