<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_created_from_events()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->notificationPreferences()->create([
            'new_followers' => true
        ]);
        $follower = User::factory()->create();

        $service = new NotificationService();
        $service->send($user, new NewFollowerNotification($follower), 'new_followers');

        Notification::assertSentTo(
            $user,
            NewFollowerNotification::class
        );
    }

    public function test_preferences_respected()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->notificationPreferences()->create([
            'new_followers' => false
        ]);
        $follower = User::factory()->create();

        $service = new NotificationService();
        $service->send($user, new NewFollowerNotification($follower), 'new_followers');

        Notification::assertNotSentTo($user, NewFollowerNotification::class);
    }

    public function test_read_unread_works()
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $user->notify(new NewFollowerNotification($follower));

        $this->assertEquals(1, $user->unreadNotifications()->count());

        $notification = $user->unreadNotifications()->first();

        $response = $this->actingAs($user)
            ->postJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200);
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }

    public function test_mark_all_as_read()
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $user->notify(new NewFollowerNotification($follower));
        $user->notify(new NewFollowerNotification($follower));

        $this->assertEquals(2, $user->unreadNotifications()->count());

        $response = $this->actingAs($user)
            ->postJson("/api/notifications/read-all");

        $response->assertStatus(200);
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }

    public function test_get_notifications()
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $user->notify(new NewFollowerNotification($follower));

        $response = $this->actingAs($user)
            ->getJson("/api/notifications");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => ['unread_count']
            ])
            ->assertJsonPath('meta.unread_count', 1);
    }

    public function test_update_preferences()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("/api/me/notification-preferences", [
                'new_followers' => false,
                'email_enabled' => false
            ]);

        $response->assertStatus(200);
        $this->assertFalse($user->fresh()->notificationPreferences->new_followers);
        $this->assertFalse($user->fresh()->notificationPreferences->email_enabled);
    }
}
