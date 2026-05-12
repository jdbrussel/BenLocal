<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\Region;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $tenerife = Region::where('slug', 'tenerife')->first();

        $jan = User::where('email', 'jan@benlocal.test')->first();
        $carlos = User::where('email', 'carlos@benlocal.test')->first();
        $sofie = User::where('email', 'sofie@benlocal.test')->first();

        $recommendations = [
            [
                'user' => $jan,
                'spot_slug' => 'bodega-san-miguel',
                'motivation' => [
                    'nl' => 'Authentieke sfeer en fantastische lokale wijn. Een echte aanrader voor wie het ware Tenerife wil proeven.',
                    'en' => 'Authentic atmosphere and fantastic local wine. Highly recommended for those who want to taste the real Tenerife.',
                ],
                'confidence_score' => 0.95,
            ],
            [
                'user' => $carlos,
                'spot_slug' => 'guachinche-casa-pepe',
                'motivation' => [
                    'nl' => 'Beste guachinche in de regio. Super vers eten voor een eerlijke prijs. Typisch Canarisch.',
                    'en' => 'Best guachinche in the region. Super fresh food for a fair price. Typically Canarian.',
                    'es' => 'El mejor guachinche de la zona. Comida súper fresca a un precio justo. Típico canario.',
                ],
                'confidence_score' => 0.98,
                'hidden_gem_candidate' => true,
            ],
            [
                'user' => $sofie,
                'spot_slug' => 'cafe-vlaanderen',
                'motivation' => [
                    'nl' => 'Heerlijk Belgisch bier en een gezellig terras. De ideale plek om even te ontsnappen aan de drukte.',
                    'en' => 'Delicious Belgian beer and a cozy terrace. The ideal place to escape the hustle and bustle.',
                ],
                'confidence_score' => 0.90,
            ],
            [
                'user' => $carlos,
                'spot_slug' => 'asador-el-camino',
                'motivation' => [
                    'nl' => 'Geweldig vlees van de grill. De bediening is vlot en vriendelijk.',
                    'en' => 'Great meat from the grill. The service is fast and friendly.',
                    'es' => 'Excelente carne a la brasa. El servicio es rápido y amable.',
                ],
                'confidence_score' => 0.85,
            ],
            [
                'user' => $jan,
                'spot_slug' => 'restaurante-mar-azul',
                'motivation' => [
                    'nl' => 'Prachtig uitzicht op de haven en de beste zeevruchten in Puerto Colón.',
                    'en' => 'Beautiful view of the harbor and the best seafood in Puerto Colón.',
                ],
                'confidence_score' => 0.88,
            ],
            [
                'user' => $sofie,
                'spot_slug' => 'belgian-bistro-tenerife',
                'motivation' => [
                    'nl' => 'Echte Belgische klassiekers met een prachtig uitzicht. De stoofvlees is een aanrader.',
                    'en' => 'Real Belgian classics with a beautiful view. The stew is highly recommended.',
                ],
                'confidence_score' => 0.82,
            ],
        ];

        foreach ($recommendations as $data) {
            $spot = Spot::where('slug', $data['spot_slug'])->first();
            if ($data['user'] && $spot) {
                Recommendation::updateOrCreate(
                    [
                        'user_id' => $data['user']->id,
                        'spot_id' => $spot->id,
                    ],
                    [
                        'region_id' => $tenerife->id,
                        'community_id' => $data['user']->community_id,
                        'motivation' => $data['motivation'],
                        'confidence_score' => $data['confidence_score'],
                        'hidden_gem_candidate' => $data['hidden_gem_candidate'] ?? false,
                    ]
                );
            }
        }
    }
}
