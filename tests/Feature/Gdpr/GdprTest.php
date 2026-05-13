<?php

namespace Tests\Feature\Gdpr;

use App\Models\GdprDeletion;
use App\Models\GdprExport;
use App\Models\PrivacyAuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GdprTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_data_export()
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/gdpr/export');

        $response->assertStatus(200);
        $this->assertDatabaseHas('gdpr_exports', [
            'user_id' => $user->id,
            'completed_at' => null,
        ]);

        $this->assertDatabaseHas('privacy_audit_logs', [
            'user_id' => $user->id,
            'action' => 'data_export_requested',
        ]);
    }

    public function test_user_can_update_privacy_settings()
    {
        $user = User::factory()->create([
            'profile_visibility' => 'public',
        ]);

        $response = $this->actingAs($user)->putJson('/api/gdpr/privacy', [
            'profile_visibility' => 'private',
            'show_location' => false,
            'marketing_consent' => true,
        ]);

        $response->assertStatus(200);
        $user->refresh();

        $this->assertEquals('private', $user->profile_visibility);
        $this->assertFalse($user->show_location);
        $this->assertTrue($user->notificationPreferences->marketing);

        $this->assertDatabaseHas('privacy_audit_logs', [
            'user_id' => $user->id,
            'action' => 'visibility_changed',
        ]);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/account');

        $response->assertStatus(200);

        $user->refresh(); // Fresh since it's soft deleted

        $this->assertEquals('Deleted User', $user->name);
        $this->assertNotEquals('original@example.com', $user->email);
        $this->assertNotNull($user->deleted_at);

        $this->assertDatabaseHas('gdpr_deletions', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('privacy_audit_logs', [
            'user_id' => $user->id,
            'action' => 'account_deleted',
        ]);
    }
}
