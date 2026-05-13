<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Spot;
use App\Models\SpotVisit;
use App\Models\User;
use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VerifiedVisitReviewSeeder extends Seeder
{
    public function run(): void
    {
        $jan = User::where('email', 'jan@benlocal.test')->first();
        $carlos = User::where('email', 'carlos@benlocal.test')->first();
        $emma = User::where('email', 'emma@benlocal.test')->first();
        $markus = User::where('email', 'markus@benlocal.test')->first();

        $spots = Spot::all();

        // Jan: many verified visits in Tenerife, mostly restaurants, high credibility
        $bodega = Spot::where('slug', 'bodega-san-miguel')->first();
        if ($jan && $bodega) {
            $this->createVerifiedReview($jan, $bodega, 'gps', 0.98, 'Best tapas in Costa Adeje! Verified visit.', 5);

            $casaPepe = Spot::where('slug', 'guachinche-casa-pepe')->first();
            if ($casaPepe) {
                $this->createVerifiedReview($jan, $casaPepe, 'gps', 0.95, 'Authentic Canarian experience.', 5);
            }
        }

        // Carlos: GPS visits to authentic Canarian spots
        if ($carlos) {
            $elCamino = Spot::where('slug', 'asador-el-camino')->first();
            if ($elCamino) {
                $this->createVerifiedReview($carlos, $elCamino, 'gps', 0.92, 'The best grill in Los Cristianos.', 5);
            }
        }

        // Emma: tourist visits, mixed manual/GPS
        if ($emma) {
            $beachBar = Spot::where('slug', 'puerto-beach-bar')->first();
            if ($beachBar) {
                $this->createVerifiedReview($emma, $beachBar, 'gps', 0.88, 'Lovely views and cocktails.', 4);
            }

            $marAzul = Spot::where('slug', 'restaurante-mar-azul')->first();
            if ($marAzul) {
                // Manual visit
                $this->createReviewWithVisit($emma, $marAzul, 'manual', 0.45, 'Great seafood, but a bit pricey.', 4, false);
            }
        }

        // Markus: regular visitor, reservation-style visits
        if ($markus) {
            $marAzul = Spot::where('slug', 'restaurante-mar-azul')->first();
            if ($marAzul) {
                $this->createVerifiedReview($markus, $marAzul, 'reservation', 0.85, 'Excellent service and quality.', 5);
            }
        }

        // Create 20 more random linked reviews
        $users = User::all();
        for ($i = 0; $i < 20; $i++) {
            $u = $users->random();
            $s = $spots->random();
            $source = collect(['gps', 'qr', 'reservation', 'owner_confirmation', 'manual'])->random();
            $score = $source === 'manual' ? rand(20, 50) / 100 : rand(75, 100) / 100;
            $isVerified = $score >= 0.7;

            $this->createReviewWithVisit($u, $s, $source, $score, 'Automated review text for verification testing.', rand(3, 5), $isVerified);
        }
    }

    private function createVerifiedReview($user, $spot, $source, $score, $text, $rating)
    {
        return $this->createReviewWithVisit($user, $spot, $source, $score, $text, $rating, true);
    }

    private function createReviewWithVisit($user, $spot, $source, $score, $text, $rating, $isVerified)
    {
        $visit = SpotVisit::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'checked_in_at' => Carbon::now()->subDays(rand(1, 30)),
            'visit_source' => $source,
            'latitude' => $spot->latitude ? $spot->latitude + (rand(-50, 50) / 100000) : null,
            'longitude' => $spot->longitude ? $spot->longitude + (rand(-50, 50) / 100000) : null,
            'verification_score' => $score,
            'is_suspicious' => false,
        ]);

        $review = Review::create([
            'user_id' => $user->id,
            'spot_id' => $spot->id,
            'spot_visit_id' => $visit->id,
            'overall_rating' => $rating,
            'review_text' => ['en' => $text],
            'visited_at' => $visit->checked_in_at,
            'verified_visit' => $isVerified,
            'visibility_score' => $isVerified ? 0.9 : 0.5,
            'moderation_status' => \App\Enums\ModerationStatus::APPROVED,
        ]);

        TimelineEvent::create([
            'user_id' => $user->id,
            'type' => 'review_created_after_visit',
            'eventable_type' => Review::class,
            'eventable_id' => $review->id,
            'region_id' => $spot->region_id,
            'payload' => [
                'spot_id' => $spot->id,
                'spot_name' => $spot->getTranslation('name', 'en'),
                'visit_id' => $visit->id,
            ],
        ]);

        return $review;
    }
}
