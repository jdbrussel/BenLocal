<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $communities = [
            [
                'slug' => 'spain-canaries',
                'name' => [
                    'nl' => 'Spanje / Canarische Eilanden',
                    'en' => 'Spain / Canary Islands',
                    'es' => 'España / Canarias',
                    'de' => 'Spanien / Kanaren',
                    'fr' => 'Espagne / Canaries',
                ],
                'sort_order' => 1,
            ],
            [
                'slug' => 'netherlands',
                'name' => [
                    'nl' => 'Nederland',
                    'en' => 'Netherlands',
                    'es' => 'Países Bajos',
                    'de' => 'Niederlande',
                    'fr' => 'Pays-Bas',
                ],
                'sort_order' => 2,
            ],
            [
                'slug' => 'belgium',
                'name' => [
                    'nl' => 'België',
                    'en' => 'Belgium',
                    'es' => 'Bélgica',
                    'de' => 'Belgien',
                    'fr' => 'Belgique',
                ],
                'sort_order' => 3,
            ],
            [
                'slug' => 'germany',
                'name' => [
                    'nl' => 'Duitsland',
                    'en' => 'Germany',
                    'es' => 'Alemania',
                    'de' => 'Deutschland',
                    'fr' => 'Allemagne',
                ],
                'sort_order' => 4,
            ],
            [
                'slug' => 'united-kingdom',
                'name' => [
                    'nl' => 'Verenigd Koninkrijk',
                    'en' => 'United Kingdom',
                    'es' => 'Reino Unido',
                    'de' => 'Vereinigtes Königreich',
                    'fr' => 'Royaume-Uni',
                ],
                'sort_order' => 5,
            ],
            [
                'slug' => 'other',
                'name' => [
                    'nl' => 'Overig',
                    'en' => 'Other',
                    'es' => 'Otro',
                    'de' => 'Andere',
                    'fr' => 'Autre',
                ],
                'sort_order' => 6,
            ],
        ];

        foreach ($communities as $data) {
            Community::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'sort_order' => $data['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
