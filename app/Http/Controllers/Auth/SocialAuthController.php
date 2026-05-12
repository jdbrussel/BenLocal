<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, config('benlocal.social_providers', []))) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, config('benlocal.social_providers', []))) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update provider info if missing or different
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $user->avatar ?: $socialUser->getAvatar(),
            ]);
        } else {
            // Create a new user
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'password' => null, // Social users might not have a password
                'email_verified_at' => now(), // Assume provider verified email
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
