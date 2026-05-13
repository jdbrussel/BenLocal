<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\Review;
use App\Models\Spot;
use App\Models\User;
use App\Models\UserRegionStatus;
use App\Models\TimelineEvent;
use App\Enums\ModerationStatus;
use App\Enums\UserRegionStatus as UserRegionStatusEnum;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $spots = Spot::all();
        $recommendations = Recommendation::all();

        $count = fake()->numberBetween(500, 900);
        $reviewsCreated = 0;

        $reviewTexts = [
            'en' => [
                'Great food and atmosphere. Highly recommend!',
                'The service was a bit slow, but the view made up for it.',
                'Absolutely loved the local dishes. Will come back.',
                'A bit touristy, but worth a visit for the experience.',
                'The best place in town for a quiet dinner.',
                'Very friendly staff and delicious seafood.',
                'I found this place through a local recommendation and it was spot on.',
                'Average experience, nothing special but not bad either.',
                'A bit overpriced for what you get, but okay.',
                'Truly a hidden gem! So glad I found it.',
            ],
            'nl' => [
                'Geweldig eten en sfeer. Ten zeerste aanbevolen!',
                'De bediening was een beetje traag, maar het uitzicht maakte veel goed.',
                'Absoluut genoten van de lokale gerechten. Ik kom zeker terug.',
                'Een beetje toeristisch, maar de ervaring waard.',
                'De beste plek in de stad voor een rustig diner.',
                'Zeer vriendelijk personeel en heerlijke zeevruchten.',
                'Ik vond deze plek via een lokale aanbeveling en het was precies goed.',
                'Gemiddelde ervaring, niets bijzonders maar ook niet slecht.',
                'Een beetje te duur voor wat je krijgt, maar oké.',
                'Echt een verborgen juweeltje! Zo blij dat ik het gevonden heb.',
            ],
            'es' => [
                'Gran comida y ambiente. ¡Altamente recomendado!',
                'El servicio fue un poco lento, pero la vista lo compensó.',
                'Absolutamente me encantaron los platos locales. Volveré.',
                'Un poco turístico, pero vale la pena la experiencia.',
                'El mejor lugar de la ciudad para una cena tranquila.',
                'Personal muy amable y mariscos deliciosos.',
                'Encontré este lugar a través de una recomendación local y fue un acierto.',
                'Experiencia media, nada especial pero tampoco mala.',
                'Un poco caro para lo que recibes, pero está bien.',
                '¡Verdaderamente una joya escondida! Muy feliz de haberlo encontrado.',
            ]
        ];

        while ($reviewsCreated < $count) {
            $user = $users->random();
            $spot = $spots->random();

            // One review per user per spot (simplification for seeder)
            if (Review::where('user_id', $user->id)->where('spot_id', $spot->id)->exists()) {
                continue;
            }

            $userStatus = UserRegionStatus::where('user_id', $user->id)
                ->where('region_id', $spot->region_id)
                ->first()?->status ?: UserRegionStatusEnum::TOURIST;

            $recommendation = Recommendation::where('spot_id', $spot->id)
                ->where('user_id', '!=', $user->id)
                ->inRandomOrder()
                ->first();

            $confirms = null;
            if ($recommendation) {
                // Logic for confirmation based on status
                if ($userStatus === UserRegionStatusEnum::TOURIST) {
                    $confirms = fake()->randomElement([true, true, true, false]); // Tourists usually confirm
                } else {
                    $confirms = fake()->randomElement([true, true, false, false]); // Locals more critical
                }
            }

            $lang = $user->preferred_language ?: 'en';
            if (!in_array($lang, ['en', 'nl', 'es'])) $lang = 'en';

            $review = Review::create([
                'user_id' => $user->id,
                'spot_id' => $spot->id,
                'recommendation_id' => $recommendation?->id,
                'overall_rating' => fake()->randomFloat(2, 1, 5),
                'rating_values' => [
                    'food_quality' => fake()->numberBetween(1, 5),
                    'service' => fake()->numberBetween(1, 5),
                    'atmosphere' => fake()->numberBetween(1, 5),
                    'value_for_money' => fake()->numberBetween(1, 5),
                ],
                'review_text' => [
                    $lang => fake()->randomElement($reviewTexts[$lang]),
                    'en' => fake()->randomElement($reviewTexts['en']),
                ],
                'original_language' => $lang,
                'visited_at' => now()->subDays(fake()->numberBetween(1, 365)),
                'user_region_status_at_time' => $userStatus,
                'user_community_id' => $user->community_id,
                'confirms_recommendation' => $confirms,
                'verified_visit' => fake()->boolean(30),
                'moderation_status' => ModerationStatus::APPROVED,
            ]);

            // Timeline Event
            TimelineEvent::create([
                'user_id' => $user->id,
                'type' => 'review_created',
                'eventable_type' => Review::class,
                'eventable_id' => $review->id,
                'region_id' => $spot->region_id,
                'payload' => ['spot_name' => $spot->name],
            ]);

            $reviewsCreated++;
        }
    }
}
