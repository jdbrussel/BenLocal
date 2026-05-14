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
        $stepInt = (int)$step;
        $view = match ($stepInt) {
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

        $data = [
            'currentStep' => $stepInt,
        ];

        // Add step specific data
        if ($stepInt === 4) {
            $data['regions'] = \App\Models\Region::active()->get(['id', 'name', 'slug']);
        }

        if ($stepInt === 5) {
            $data['communities'] = \App\Models\Community::active()->get(['id', 'name', 'slug']);
        }

        return Inertia::render($view, $data);
    }

    public function store(Request $request, $step)
    {
        $stepInt = (int)$step;

        // Logic to store choices in session or user record
        switch ($stepInt) {
            case 2: // Language
                $locale = $request->input('locale', app()->getLocale());
                session(['locale' => $locale]);
                if (auth()->check()) {
                    auth()->user()->update(['preferred_language' => $locale]);
                }
                break;
            case 4: // Region
                session(['onboarding_region_id' => $request->input('region_id')]);
                break;
            case 5: // Communities
                session(['onboarding_community_ids' => $request->input('community_ids')]);
                break;
        }

        return redirect()->route('onboarding.step', $stepInt + 1);
    }

    public function complete(Request $request)
    {
        if (auth()->check()) {
            auth()->user()->update(['onboarding_completed' => true]);
        } else {
            session(['onboarding_completed' => true]);
        }

        return redirect()->route('discover');
    }
}
