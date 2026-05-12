<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    public function welcome()
    {
        return Inertia::render('Onboarding/Welcome');
    }

    public function step($step)
    {
        $view = match ((int)$step) {
            1 => 'Onboarding/Welcome',
            2 => 'Onboarding/Language',
            3 => 'Onboarding/CookieConsent',
            4 => 'Onboarding/Region',
            5 => 'Onboarding/Communities',
            6 => 'Onboarding/Interests',
            7 => 'Onboarding/FollowLocals',
            8 => 'Onboarding/Account',
            9 => 'Onboarding/Completion',
            default => 'Onboarding/Welcome',
        };

        return Inertia::render($view, [
            'currentStep' => (int)$step,
        ]);
    }
}
