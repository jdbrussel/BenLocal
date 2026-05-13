<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Gdpr\AccountDeletionService;
use App\Services\Gdpr\ConsentAuditService;
use App\Services\UserSettingsService;
use App\Services\ThemePreferenceService;
use App\Services\LocaleService;
use App\Services\CookieConsentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthApiController extends Controller
{
    public function __construct(
        protected UserSettingsService $settingsService,
        protected ThemePreferenceService $themeService,
        protected LocaleService $localeService,
        protected CookieConsentService $consentService,
        protected AccountDeletionService $deletionService,
        protected ConsentAuditService $auditService
    ) {}

    /**
     * Get the authenticated user.
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'onboarding' => $this->settingsService->getOnboardingState($user),
            'preferences' => [
                'language' => $user->preferred_language,
                'theme' => $user->preferred_theme,
            ],
            'consent' => $this->consentService->getConsent(),
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'preferred_language' => ['sometimes', 'string', 'in:' . implode(',', array_keys($this->localeService->getAvailableLanguages()))],
            'preferred_theme' => ['sometimes', 'string', 'in:light,dark,system'],
            'residence_region_id' => ['sometimes', 'nullable', 'exists:regions,id'],
            'community_id' => ['sometimes', 'nullable', 'exists:communities,id'],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'preferred_language' => ['sometimes', 'string', 'in:' . implode(',', array_keys($this->localeService->getAvailableLanguages()))],
            'preferred_theme' => ['sometimes', 'string', 'in:light,dark,system'],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Preferences updated successfully',
            'preferences' => [
                'language' => $user->preferred_language,
                'theme' => $user->preferred_theme,
            ]
        ]);
    }

    /**
     * Update cookie consent.
     */
    public function updateConsent(Request $request)
    {
        $validated = $request->validate([
            'consent' => ['required', 'array'],
        ]);

        $oldConsent = $this->consentService->getConsent();
        $this->consentService->updateConsent($validated['consent']);
        $newConsent = $this->consentService->getConsent();

        if ($request->user()) {
            $this->auditService->logConsentUpdate($request->user(), $oldConsent, $newConsent);
        }

        return response()->json([
            'message' => 'Cookie consent updated successfully',
            'consent' => $newConsent
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Delete account.
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        $deletionRequest = $this->deletionService->requestDeletion($user);
        $this->deletionService->processDeletion($deletionRequest);

        return response()->json([
            'message' => 'Account deleted successfully'
        ]);
    }
}
