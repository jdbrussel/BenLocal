# Deployment Notes

## Phase 7: Trust Engine Requirements

### Background Processing (Queues)
Phase 7 introduces heavy calculation jobs that **must** be processed asynchronously.
- Ensure `QUEUE_CONNECTION` is set to a production-ready driver (e.g., `redis`, `database`).
- Start workers to process the `default` queue:
  ```bash
  php artisan queue:work
  ```

### Scheduled Tasks
Score recalculation is scheduled to run daily.
- Ensure the CRON entry is configured for the Laravel Scheduler:
  ```bash
  * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
  ```
- **Scheduled Jobs:**
    - `RecalculateUserReputationJob`: Daily (00:00).
    - `RecalculateSpotScoresJob`: Daily (02:00).

### Database Migrations
Run the migrations to add scoring fields and reputation tables:
```bash
php artisan migrate --force
```

### Caching
Rankings and scores are heavily cached. Ensure `CACHE_STORE` is configured (Redis recommended).
To clear trust-related caches:
```bash
php artisan cache:forget trust_graph_*
```

## Phase 17: Performance & Production Scale

### Environment Configuration
Ensure the following variables are set for production:
- `CACHE_STORE=redis`
- `QUEUE_CONNECTION=redis`

### Benchmark Data (Optional)
To test performance in a staging environment with realistic volumes:
1. Set `BENLOCAL_SEED_BENCHMARKS=true` in `.env`.
2. Configure desired volumes (e.g., `BENLOCAL_BENCHMARK_SPOTS=1000`).
3. Run `php artisan db:seed --class=PerformanceBenchmarkSeeder`.
4. **Important**: Do not run benchmark seeders in production.

### Post-Seeding Maintenance
After seeding large datasets, run the recalculation commands to populate scores:
```bash
php artisan benlocal:recalculate-rankings
php artisan benlocal:recalculate-reputation
```
