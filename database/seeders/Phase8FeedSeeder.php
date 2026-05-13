<?php

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\Recommendation;
use App\Models\Region;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\Spot;
use App\Models\TimelineEvent;
use App\Models\User;
use App\Services\TimelineEventService;
use Illuminate\Database\Seeder;

class Phase8FeedSeeder extends Seeder
{
    protected $timelineEventService;

    public function __construct(TimelineEventService $timelineEventService)
    {
        $this->timelineEventService = $timelineEventService;
    }

    public function run(): void
    {
        $this->command->info("Starting Phase8FeedSeeder...");

        $users = User::all();
        $spots = Spot::all();
        $regions = Region::all();

        if ($users->count() < 10 || $spots->count() < 10) {
            $this->command->warn("Not enough users or spots to seed feed reliably.");
            return;
        }

        // 1. Create Follows to enable personalization testing
        $this->command->info("Seeding Follows...");
        foreach ($users->take(20) as $follower) {
            $toFollow = $users->random(rand(2, 5));
            foreach ($toFollow as $followed) {
                if ($follower->id !== $followed->id) {
                    Follow::firstOrCreate([
                        'follower_id' => $follower->id,
                        'followed_id' => $followed->id,
                    ]);

                    // Note: Usually we don't have a timeline event for every follow,
                    // but the requirement mentions 'follows' in the feed.
                    $this->timelineEventService->createEvent(
                        $follower->id,
                        'follow_created',
                        $follower->follows()->where('followed_id', $followed->id)->first(),
                        null,
                        ['followed_name' => $followed->name]
                    );
                }
            }
        }

        // 2. Create diverse Timeline Events
        $this->command->info("Seeding diverse Timeline Events...");

        // New Recommendations
        $this->command->info("- Recommendations");
        $recs = Recommendation::all()->random(min(50, Recommendation::count()));
        foreach ($recs as $rec) {
            $this->timelineEventService->createEvent(
                $rec->user_id,
                'recommendation_created',
                $rec,
                $rec->region_id,
                ['spot_name' => $rec->spot->name]
            );
        }

        // New Reviews
        $this->command->info("- Reviews");
        $reviews = Review::all()->random(min(50, Review::count()));
        foreach ($reviews as $review) {
            $this->timelineEventService->createEvent(
                $review->user_id,
                'review_created',
                $review,
                $review->spot->region_id,
                [
                    'spot_name' => $review->spot->name,
                    'rating' => $review->overall_rating
                ]
            );
        }

        // Review Reactions (Review Validations)
        $this->command->info("- Review Reactions");
        $reactions = ReviewReaction::all()->random(min(30, ReviewReaction::count()));
        foreach ($reactions as $reaction) {
            $this->timelineEventService->createEvent(
                $reaction->user_id,
                'review_reaction_created',
                $reaction,
                $reaction->review->spot->region_id,
                [
                    'reaction' => $reaction->reaction,
                    'spot_name' => $reaction->review->spot->name
                ]
            );
        }

        // 3. Region-aware feed examples
        $this->command->info("Seeding region-specific events...");
        foreach ($regions as $region) {
            $regionSpots = Spot::where('region_id', $region->id)->take(3)->get();
            $regionUsers = User::where('residence_region_id', $region->id)->take(3)->get();

            if ($regionSpots->isNotEmpty() && $regionUsers->isNotEmpty()) {
                foreach ($regionUsers as $user) {
                    foreach ($regionSpots as $spot) {
                        // Create a "Hidden Gem Update" placeholder event
                        TimelineEvent::create([
                            'user_id' => $user->id,
                            'type' => 'hidden_gem_update',
                            'eventable_type' => Spot::class,
                            'eventable_id' => $spot->id,
                            'region_id' => $region->id,
                            'payload' => [
                                'spot_name' => $spot->name,
                                'new_status' => 'official_hidden_gem'
                            ],
                            'created_at' => now()->subHours(rand(1, 48))
                        ]);
                    }
                }
            }
        }

        $this->command->info("Phase8FeedSeeder completed.");
    }
}
