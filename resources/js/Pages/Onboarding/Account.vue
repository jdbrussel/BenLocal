<template>
    <OnboardingLayout :current-step="8">
        <div class="flex flex-col h-full">
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $t('ui.onboarding.account.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('ui.onboarding.account.subtitle') }}
                </p>

                <div class="space-y-4">
                    <button class="w-full flex items-center justify-center gap-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 py-4 rounded-2xl font-bold shadow-sm transition-all hover:bg-gray-50 dark:hover:bg-gray-700">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google" />
                        {{ $t('ui.auth.continue_google') }}
                    </button>
                    <button class="w-full flex items-center justify-center gap-3 bg-[#1877F2] text-white py-4 rounded-2xl font-bold shadow-sm transition-all hover:bg-[#166fe5]">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5 brightness-0 invert" alt="Facebook" />
                        {{ $t('ui.auth.continue_facebook') }}
                    </button>

                    <div class="relative flex py-5 items-center">
                        <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                        <span class="flex-shrink mx-4 text-gray-400 text-sm uppercase tracking-wider font-bold">{{ $t('ui.auth.or') }}</span>
                        <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                    </div>

                    <Link :href="route('register')" class="w-full flex items-center justify-center bg-amber-500 text-white py-4 rounded-2xl font-bold shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all">
                        {{ $t('ui.auth.register_email') }}
                    </Link>
                </div>
            </div>

            <div class="mt-auto pt-8">
                <button @click="next" class="w-full text-center py-4 text-gray-500 font-medium">
                    {{ $t('ui.onboarding.account.skip') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';

const next = () => {
    router.get(route('onboarding.step', { step: 9 }));
};
</script>
