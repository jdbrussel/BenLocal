<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;

class OwnerReviewResponseSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::where('role', \App\Enums\UserRole::OWNER)->get();

        foreach ($owners as $owner) {
            // Get spots owned by this user
            $spotIds = \App\Models\SpotOwnerRole::where('user_id', $owner->id)->pluck('spot_id');

            if ($spotIds->isEmpty()) continue;

            // Find reviews for these spots that don't have responses yet
            $reviews = Review::whereIn('spot_id', $spotIds)
                ->whereNull('owner_response_text')
                ->take(3)
                ->get();

            foreach ($reviews as $index => $review) {
                $responses = [
                    'en' => [
                        'Thank you for your kind words! We hope to see you again soon.',
                        'We are sorry to hear about your experience. We will work on improving our service.',
                        'Glad you enjoyed our local specialties!',
                    ],
                    'nl' => [
                        'Bedankt voor je mooie woorden! We hopen je snel weer te zien.',
                        'Het spijt ons te horen over je ervaring. We gaan werken aan het verbeteren van onze service.',
                        'Fijn dat je hebt genoten van onze lokale specialiteiten!',
                    ],
                    'es' => [
                        '¡Gracias por sus amables palabras! Esperamos verle pronto de nuevo.',
                        'Lamentamos escuchar su experiencia. Trabajaremos para mejorar nuestro servicio.',
                        '¡Nos alegra que haya disfrutado de nuestras especialidades locales!',
                    ],
                ];

                $lang = $owner->preferred_language ?: 'en';
                if (!isset($responses[$lang])) $lang = 'en';

                $responseText = $responses[$lang][$index % 3];

                $review->update([
                    'owner_response_text' => [$lang => $responseText],
                    'owner_response_at' => now(),
                    'owner_user_id' => $owner->id,
                ]);
            }
        }

        // Specific scenario: response awaiting moderation (if we had a status, but let's assume it's just filled)
        // For now we just fill the fields.
    }
}
