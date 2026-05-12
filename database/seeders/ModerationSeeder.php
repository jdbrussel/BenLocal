<?php

namespace Database\Seeders;

use App\Models\ContentReport;
use App\Models\ModerationAction;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModerationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('email');
        $reviews = Review::all();
        $moderator = $users['carlos@benlocal.test'] ?? User::first();

        if ($reviews->isNotEmpty() && $users->isNotEmpty()) {
            $targetReview = $reviews->first();
            $reporter = $users['emma@benlocal.test'] ?? User::first();

            // Content Report
            ContentReport::create([
                'reporter_user_id' => $reporter->id,
                'reportable_type' => Review::class,
                'reportable_id' => $targetReview->id,
                'reason' => 'spam',
                'notes' => 'Deze review lijkt op spam.',
                'status' => 'resolved',
                'moderator_id' => $moderator->id,
                'resolution_notes' => 'Review gecontroleerd, geen actie nodig.',
            ]);

            // Moderation Action
            ModerationAction::create([
                'moderator_id' => $moderator->id,
                'target_type' => Review::class,
                'target_id' => $targetReview->id,
                'action' => 'verify',
                'reason' => 'Handmatige verificatie na melding.',
            ]);

            // Fake review example
            $fakeReview = Review::create([
                'user_id' => $users['marie@benlocal.test']->id,
                'spot_id' => $targetReview->spot_id,
                'overall_rating' => 1,
                'review_text' => ['en' => 'Fake review for testing moderation.'],
                'moderation_status' => 'rejected',
            ]);

            ContentReport::create([
                'reporter_user_id' => $moderator->id,
                'reportable_type' => Review::class,
                'reportable_id' => $fakeReview->id,
                'reason' => 'fake_review',
                'status' => 'pending',
            ]);

            ModerationAction::create([
                'moderator_id' => $moderator->id,
                'target_type' => Review::class,
                'target_id' => $fakeReview->id,
                'action' => 'hide',
                'reason' => 'Duidelijk valse review.',
            ]);
        }
    }
}
