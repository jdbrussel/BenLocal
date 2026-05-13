<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\Region;
use App\Models\Spot;
use App\Models\User;
use App\Models\UserRegionStatus;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use App\Enums\ModerationStatus;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;

class RecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $regions = Region::all();
        $spots = Spot::all();

        // Find users with local or verified_local status
        $localUserIds = UserRegionStatus::whereIn('status', [
            UserRegionStatusEnum::LOCAL,
            UserRegionStatusEnum::VERIFIED_LOCAL
        ])->pluck('user_id')->unique();

        $localUsers = User::whereIn('id', $localUserIds)->get();

        if ($localUsers->isEmpty()) {
            // Fallback: create some locals if none exist (though Phase 3 should have some)
            $localUsers = User::factory(20)->create();
            foreach ($localUsers as $user) {
                UserRegionStatus::create([
                    'user_id' => $user->id,
                    'region_id' => $regions->random()->id,
                    'status' => fake()->randomElement([UserRegionStatusEnum::LOCAL, UserRegionStatusEnum::VERIFIED_LOCAL]),
                    'confidence_score' => fake()->numberBetween(70, 99),
                ]);
            }
        }

        $count = fake()->numberBetween(150, 250);
        $recommendationsCreated = 0;

        $motivations = [
            'en' => [
                'Hidden gem with the best local wine.',
                'Perfect place for a quiet afternoon.',
                'Truly authentic experience away from tourists.',
                'The seafood here is incredibly fresh.',
                'Best view of the sunset in the region.',
                'A must-visit for anyone looking for real local flavors.',
                'Friendly staff and amazing atmosphere.',
                'They have the most delicious traditional dishes.',
                'Great value for money and very welcoming.',
                'I always bring my friends here when they visit.',
            ],
            'nl' => [
                'Verborgen juweeltje met de beste lokale wijn.',
                'Perfecte plek voor een rustige middag.',
                'Echt authentieke ervaring weg van de toeristen.',
                'De zeevruchten hier zijn ongelooflijk vers.',
                'Beste uitzicht op de zonsondergang in de regio.',
                'Een must-visit voor iedereen die op zoek is naar echte lokale smaken.',
                'Vriendelijk personeel en geweldige sfeer.',
                'Ze hebben de heerlijkste traditionele gerechten.',
                'Goede prijs-kwaliteitverhouding en zeer gastvrij.',
                'Ik breng mijn vrienden hier altijd heen als ze op bezoek komen.',
            ],
            'es' => [
                'Joya escondida con el mejor vino local.',
                'Lugar perfecto para una tarde tranquila.',
                'Experiencia verdaderamente auténtica lejos de los turistas.',
                'Los mariscos aquí son increíblemente frescos.',
                'La mejor vista del atardecer en la región.',
                'Una visita obligada para cualquiera que busque sabores locales reales.',
                'Personal amable y ambiente increíble.',
                'Tienen los platos tradicionales más deliciosos.',
                'Gran relación calidad-precio y muy acogedor.',
                'Siempre traigo a mis amigos aquí cuando vienen de visita.',
            ]
        ];

        while ($recommendationsCreated < $count) {
            $user = $localUsers->random();
            // Get user's local regions
            $userLocalRegionIds = UserRegionStatus::where('user_id', $user->id)
                ->whereIn('status', [UserRegionStatusEnum::LOCAL, UserRegionStatusEnum::VERIFIED_LOCAL])
                ->pluck('region_id');

            $spot = Spot::whereIn('region_id', $userLocalRegionIds)->inRandomOrder()->first();

            if (!$spot) continue;

            // Ensure one recommendation per user per spot
            $existing = Recommendation::where('user_id', $user->id)->where('spot_id', $spot->id)->exists();
            if ($existing) continue;

            $lang = $user->preferred_language ?: 'en';
            if (!in_array($lang, ['en', 'nl', 'es'])) $lang = 'en';

            $recommendation = Recommendation::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'region_id' => $spot->region_id,
                'community_id' => $user->community_id,
                'motivation' => [
                    $lang => fake()->randomElement($motivations[$lang]),
                    'en' => fake()->randomElement($motivations['en']), // Ensure English fallback
                ],
                'original_language' => $lang,
                'confidence_score' => fake()->randomFloat(2, 0.7, 1.0),
                'hidden_gem_candidate' => fake()->boolean(20),
                'moderation_status' => fake()->randomElement([
                    ModerationStatus::APPROVED,
                    ModerationStatus::APPROVED,
                    ModerationStatus::APPROVED,
                    ModerationStatus::PENDING,
                    ModerationStatus::REJECTED
                ]),
            ]);

            // Timeline Event
            TimelineEvent::create([
                'user_id' => $user->id,
                'type' => 'recommendation_created',
                'eventable_type' => Recommendation::class,
                'eventable_id' => $recommendation->id,
                'region_id' => $spot->region_id,
                'payload' => ['spot_name' => $spot->name],
            ]);

            $recommendationsCreated++;
        }
    }
}
