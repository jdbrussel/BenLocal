<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('email');
        $spots = Spot::all()->keyBy('slug');

        $reviewsData = [
            [
                'email' => 'emma@benlocal.test',
                'spot_slug' => 'bodega-san-miguel',
                'overall_rating' => 5,
                'rating_values' => ['food_quality' => 5, 'service' => 4, 'atmosphere' => 5, 'value_for_money' => 5],
                'review_text' => ['en' => 'Amazing experience! Jan was right, this place is a must-visit.'],
                'confirms_recommendation' => true,
                'verified_visit' => true,
                'reactions' => [
                    ['email' => 'jan@benlocal.test', 'reaction' => 'agree'],
                    ['email' => 'sofie@benlocal.test', 'reaction' => 'agree'],
                ]
            ],
            [
                'email' => 'markus@benlocal.test',
                'spot_slug' => 'restaurante-mar-azul',
                'overall_rating' => 4,
                'rating_values' => ['food_quality' => 4, 'service' => 4, 'atmosphere' => 5, 'value_for_money' => 3],
                'review_text' => ['de' => 'Gutes Essen, aber etwas teuer wegen der Lage am Hafen.', 'en' => 'Good food, but a bit expensive due to the harbor location.'],
                'confirms_recommendation' => true,
                'perceived_community_profile' => ['tourists' => 70, 'locals' => 30],
            ],
            [
                'email' => 'carlos@benlocal.test',
                'spot_slug' => 'puerto-beach-bar',
                'overall_rating' => 2,
                'rating_values' => ['ambiance' => 3, 'drink_selection' => 2, 'music' => 2, 'service' => 2, 'price_level' => 1],
                'review_text' => ['es' => 'Demasiado turístico y caro para lo que ofrecen.', 'en' => 'Too touristy and expensive for what they offer.'],
                'reactions' => [
                    ['email' => 'jan@benlocal.test', 'reaction' => 'agree'],
                    ['email' => 'emma@benlocal.test', 'reaction' => 'disagree'],
                ]
            ],
            [
                'email' => 'sofie@benlocal.test',
                'spot_slug' => 'cafe-vlaanderen',
                'overall_rating' => 5,
                'rating_values' => ['ambiance' => 5, 'drink_selection' => 5, 'music' => 4, 'service' => 5, 'price_level' => 4],
                'review_text' => ['nl' => 'Mijn favoriete plek voor een Belgisch biertje!'],
                'confirms_recommendation' => true,
                'verified_visit' => true,
            ],
        ];

        foreach ($reviewsData as $data) {
            $user = $users[$data['email']] ?? null;
            $spot = $spots[$data['spot_slug']] ?? null;

            if ($user && $spot) {
                $recommendation = Recommendation::where('spot_id', $spot->id)
                    ->where('user_id', '!=', $user->id) // Someone else's recommendation
                    ->first();

                $review = Review::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'spot_id' => $spot->id,
                    ],
                    [
                        'recommendation_id' => $recommendation?->id,
                        'overall_rating' => $data['overall_rating'],
                        'rating_values' => $data['rating_values'],
                        'review_text' => $data['review_text'],
                        'confirms_recommendation' => $data['confirms_recommendation'] ?? null,
                        'perceived_community_profile' => $data['perceived_community_profile'] ?? null,
                        'verified_visit' => $data['verified_visit'] ?? false,
                        'user_community_id' => $user->community_id,
                        'moderation_status' => 'approved',
                    ]
                );

                if (isset($data['reactions'])) {
                    foreach ($data['reactions'] as $reactionData) {
                        $reactionUser = $users[$reactionData['email']] ?? null;
                        if ($reactionUser) {
                            ReviewReaction::updateOrCreate(
                                [
                                    'user_id' => $reactionUser->id,
                                    'review_id' => $review->id,
                                ],
                                [
                                    'reaction' => $reactionData['reaction'],
                                    'weight' => 1.0,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
