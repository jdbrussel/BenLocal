<?php

namespace App\Http\Controllers;

use App\Models\ClaimToken;
use App\Models\SpotClaim;
use App\Services\ClaimService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessClaimController extends Controller
{
    protected $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }

    /**
     * Show the claim landing page from a token.
     */
    public function showByToken(string $token)
    {
        $claimToken = $this->claimService->validateToken($token);

        if (!$claimToken) {
            abort(404, 'Invalid or expired claim token.');
        }

        return Inertia::render('Business/ClaimLanding', [
            'token' => $token,
            'spot' => $claimToken->spot->only(['id', 'name', 'address', 'city']),
        ]);
    }

    /**
     * Show the claim request form.
     */
    public function showForm(string $token)
    {
        $claimToken = $this->claimService->validateToken($token);

        if (!$claimToken) {
            abort(404, 'Invalid or expired claim token.');
        }

        return Inertia::render('Business/ClaimForm', [
            'token' => $token,
            'spot' => $claimToken->spot->only(['id', 'name']),
            'prefilledEmail' => $claimToken->email,
        ]);
    }

    /**
     * Submit the claim request.
     */
    public function submit(Request $request, string $token)
    {
        $claimToken = $this->claimService->validateToken($token);

        if (!$claimToken) {
            abort(404, 'Invalid or expired claim token.');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'proof_notes' => 'nullable|string',
            // 'proof_document' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $claim = SpotClaim::create([
            'spot_id' => $claimToken->spot_id,
            'user_id' => auth()->id(), // Might be null if not logged in
            'business_name' => $validated['business_name'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'website' => $validated['website'],
            'proof_notes' => $validated['proof_notes'],
            'status' => \App\Enums\ClaimStatus::PENDING,
        ]);

        // In a real scenario, we might want to link the user later if they are not logged in.
        // Or force registration/login before submitting.

        return redirect()->route('claim.success');
    }

    public function success()
    {
        return Inertia::render('Business/ClaimSuccess');
    }
}
