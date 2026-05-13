# Testing Documentation

## Phase 7: Ranking & Reputation

A comprehensive test suite is available in `tests/Feature/Phase7RankingTest.php`.

### Test Scenarios
1. **Local Weighting:** Ensures recommendations from users with "local" status have a higher impact on spot scores.
2. **Hidden Gem Detection:** Verifies that spots with high local trust but low volume are correctly flagged.
3. **Tourist Saturation:** Checks that high volumes of non-local reviews correctly increase the saturation score.
4. **Personalized Ranking:** Confirms that users see different results based on who they follow and their community.
5. **Reputation Penalties:** Tests that moderation actions correctly reduce trust scores.

### Running Tests
```bash
php artisan test tests/Feature/Phase7RankingTest.php
```

### Manual Verification
Use the `RecalculateUserReputationJob` and `RecalculateSpotScoresJob` to force an update after seeding data:
```php
\App\Jobs\RecalculateUserReputationJob::dispatch($user);
\App\Jobs\RecalculateSpotScoresJob::dispatch($spot);
```
