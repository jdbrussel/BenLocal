<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the application's production data.
     */
    public function run(): void
    {
        $this->call([
            ProductionUserSeeder::class,
            CommunitySeeder::class,
            LocationSeeder::class,
            SectorCategorySeeder::class,
            FaqSeeder::class,
            TenerifeSpotSeeder::class,
        ]);
    }
}
