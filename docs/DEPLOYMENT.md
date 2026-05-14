# Deployment & Launch Guide (Phase 18)

## 1. Environment Setup (.env)
Ensure your production `.env` contains the following critical keys:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://benlocal.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=benlocal_prod
DB_USERNAME=benlocal
DB_PASSWORD=secret

# Performance
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

# OAuth (Socialite)
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

FACEBOOK_CLIENT_ID=...
FACEBOOK_CLIENT_SECRET=...
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"

# AI Provider
OPENAI_API_KEY=...

# Storage (S3)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=eu-central-1
AWS_BUCKET=benlocal-assets
```

## 2. Queue Setup
BenLocal uses Redis for high-performance background jobs (AI enrichment, Reputation updates).

- **Requirement:** Redis 6.0+
- **Process Manager:** Use `Supervisor` to keep queue workers running.
- **Command:** `php artisan queue:work --tries=3 --timeout=90`
- **Recommended:** Install `laravel/horizon` for production monitoring.

## 3. Scheduler Setup
Add the following entry to your server's crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**Key Scheduled Tasks (defined in routes/console.php):**
- `01:00` - `benlocal:recalculate-reputation`: Daily trust score updates.
- `02:00` - `benlocal:recalculate-rankings`: Global spot ranking refresh.
- `03:00` - `benlocal:refresh-community-profiles`: Language/community alignment.
- `04:00` - `benlocal:detect-hidden-gems`: Sentiment analysis for local spots.
- `05:00` - `benlocal:clear-stale-cache`: Cache housekeeping.

## 4. Storage Setup
- Public assets (Spot photos, User avatars) should be stored on S3.
- Configure `FILESYSTEM_DISK=s3`.
- Set up a CloudFront distribution for optimal delivery in Tenerife.

## 5. AI Provider Setup
- Ensure `OPENAI_API_KEY` is valid.
- The `AIEnrichmentService` handles rate limits and failures gracefully.
- Monitor logs for `AIEnrichmentService` entries to verify provider health.

## 6. Backup Strategy
- **Database:** Nightly snapshots using `mysqldump` or RDS automated backups.
- **Storage:** S3 Cross-Region Replication for media.
- **Config:** Store `.env.production` in a secure vault (e.g., AWS Secrets Manager).

## 7. Rollback Strategy
- **Deployment:** Use a symlink-based deployment (e.g., Laravel Envoyer or Deployer).
- **Rollback:** Point the `current` symlink back to the previous release folder.
- **Database:** Use `php artisan migrate:rollback` only if schemas are backward compatible. Preferably use "Expand and Contract" migration patterns.

## 8. Launch Checklist (MVP)
1. [ ] Generate `APP_KEY`
2. [ ] Run `composer install --optimize-autoloader --no-dev`
3. [ ] Run `php artisan migrate --force`
4. [ ] Seed essential data: `php artisan db:seed --class=ProductionSeeder`
5. [ ] Compile assets: `npm run build`
6. [ ] Clear and cache config: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
7. [ ] Verify SSL (Let's Encrypt)
8. [ ] Test OAuth login flow (Google/Facebook)
9. [ ] Verify Mailer (SES/Resend)
10. [ ] Start Queue Workers (Supervisor)
11. [ ] Verify Health Check: `https://api.benlocal.com/api/health`
12. [ ] Audit `public/` directory for accidental sensitive files
13. [ ] Check `robots.txt` and `sitemap.xml`
14. [ ] Verify PWA manifest load
15. [ ] Perform manual mobile UX walkthrough
16. [ ] Disable `APP_DEBUG`
17. [ ] Configure `LOG_LEVEL=info` or `warning`
18. [ ] Set up Uptime monitoring (e.g., Oh Dear, Better Stack)
19. [ ] Verify Stripe/Payment Webhooks (if applicable)
20. [ ] **FINAL GO:** Switch DNS to Production IP
