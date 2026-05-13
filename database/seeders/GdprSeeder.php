<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GdprSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserPrivacyPreferenceSeeder::class,
            ConsentHistorySeeder::class,
            GdprExportSeeder::class,
            GdprDeletionSeeder::class,
            AnonymizedUserSeeder::class,
        ]);
    }
}
