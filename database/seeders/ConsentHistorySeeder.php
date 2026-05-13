<?php

namespace Database\Seeders;

use App\Models\CookieConsent;
use App\Models\PrivacyAuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ConsentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // 1. Guest sessions (null user_id)
        for ($i = 0; $i < 20; $i++) {
            CookieConsent::create([
                'user_id' => null,
                'session_id' => Str::random(40),
                'necessary' => true,
                'analytics' => (bool)rand(0, 1),
                'personalization' => (bool)rand(0, 1),
                'marketing' => (bool)rand(0, 1),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
                'consented_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);
        }

        // 2. Authenticated users first-time consent
        foreach ($users->take(30) as $user) {
            $consent = CookieConsent::create([
                'user_id' => $user->id,
                'session_id' => Str::random(40),
                'necessary' => true,
                'analytics' => true,
                'personalization' => true,
                'marketing' => true,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
                'consented_at' => Carbon::now()->subDays(rand(30, 60)),
            ]);

            PrivacyAuditLog::create([
                'user_id' => $user->id,
                'action' => 'consent_updated',
                'old_values' => null,
                'new_values' => $consent->toArray(),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
            ]);
        }

        // 3. Updated consent (withdrawn marketing)
        foreach ($users->random(10) as $user) {
            $oldConsent = $user->cookieConsents()->latest()->first();

            $consent = CookieConsent::create([
                'user_id' => $user->id,
                'session_id' => Str::random(40),
                'necessary' => true,
                'analytics' => true,
                'personalization' => true,
                'marketing' => false,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
                'consented_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);

            PrivacyAuditLog::create([
                'user_id' => $user->id,
                'action' => 'consent_updated',
                'old_values' => $oldConsent ? $oldConsent->toArray() : null,
                'new_values' => $consent->toArray(),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
            ]);
        }

        // 4. Analytics disabled
        foreach ($users->random(5) as $user) {
            $consent = CookieConsent::create([
                'user_id' => $user->id,
                'session_id' => Str::random(40),
                'necessary' => true,
                'analytics' => false,
                'personalization' => true,
                'marketing' => true,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
                'consented_at' => Carbon::now()->subDays(rand(1, 5)),
            ]);

            PrivacyAuditLog::create([
                'user_id' => $user->id,
                'action' => 'consent_updated',
                'old_values' => null,
                'new_values' => $consent->toArray(),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
            ]);
        }
    }
}
