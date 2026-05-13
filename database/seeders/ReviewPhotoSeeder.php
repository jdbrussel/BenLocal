<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewPhoto;
use Illuminate\Database\Seeder;

class ReviewPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::all();

        // 40-60% of reviews get photos
        $reviewsWithPhotos = $reviews->random(intval($reviews->count() * fake()->randomFloat(2, 0.4, 0.6)));

        foreach ($reviewsWithPhotos as $review) {
            $photoCount = fake()->numberBetween(1, 4);

            for ($i = 1; $i <= $photoCount; $i++) {
                ReviewPhoto::create([
                    'review_id' => $review->id,
                    'path' => "storage/demo/reviews/review-" . str_pad($review->id, 3, '0', STR_PAD_LEFT) . "-{$i}.jpg",
                    'caption' => [
                        'en' => fake()->sentence(),
                        'es' => fake()->sentence(),
                        'nl' => fake()->sentence(),
                    ],
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
