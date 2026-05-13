<?php

namespace Tests\Feature;

use App\Enums\ClaimStatus;
use App\Enums\UserRole;
use App\Models\ClaimToken;
use App\Models\Spot;
use App\Models\User;
use App\Services\ClaimService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessClaimTest extends TestCase
{
    use RefreshDatabase;

    protected $claimService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->claimService = app(ClaimService::class);
    }

    public function test_claim_token_generation_and_validation()
    {
        $spot = Spot::factory()->create();
        $token = $this->claimService->generateToken($spot, 'owner@example.com');

        $this->assertDatabaseHas('claim_tokens', [
            'spot_id' => $spot->id,
            'email' => 'owner@example.com',
            'token' => $token->token,
        ]);

        $validatedToken = $this->claimService->validateToken($token->token);
        $this->assertNotNull($validatedToken);
        $this->assertEquals($token->id, $validatedToken->id);
    }

    public function test_expired_claim_token_is_invalid()
    {
        $spot = Spot::factory()->create();
        $token = ClaimToken::create([
            'spot_id' => $spot->id,
            'token' => 'expired-token',
            'email' => 'test@example.com',
            'expires_at' => now()->subDay(),
        ]);

        $validatedToken = $this->claimService->validateToken('expired-token');
        $this->assertNull($validatedToken);
    }

    public function test_used_claim_token_is_invalid()
    {
        $spot = Spot::factory()->create();
        $token = ClaimToken::create([
            'spot_id' => $spot->id,
            'token' => 'used-token',
            'email' => 'test@example.com',
            'used_at' => now()->subHour(),
            'expires_at' => now()->addDay(),
        ]);

        $validatedToken = $this->claimService->validateToken('used-token');
        $this->assertNull($validatedToken);
    }

    public function test_owner_access_to_dashboard()
    {
        $owner = User::factory()->create(['role' => UserRole::OWNER]);
        $user = User::factory()->create(['role' => UserRole::USER]);

        $this->actingAs($owner)->get('/owner')->assertOk();
        $this->actingAs($user)->get('/owner')->assertForbidden();
    }
}
