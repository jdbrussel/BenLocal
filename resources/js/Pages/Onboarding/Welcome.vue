<template>
    <OnboardingLayout :current-step="1">
        <div class="flex flex-col h-full">
            <div class="flex-1 flex flex-col justify-center text-center">
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-amber-100 dark:bg-amber-900/30 rounded-3xl flex items-center justify-center text-amber-500">
                        <HeartIcon class="w-12 h-12" />
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-4 tracking-tight">{{ $t('messages.onboarding.welcome.title') || 'Welcome to BenLocal' }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                    {{ $t('messages.onboarding.welcome.description') || 'Discover hidden gems and local favorites in the Canary Islands.' }}
                </p>
            </div>

            <div class="mt-auto space-y-4">
                <button @click="next" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-amber-500/20 transition-all transform active:scale-[0.98]">
                    {{ $t('messages.onboarding.get_started') || 'Get Started' }}
                </button>
                <Link :href="route('login')" class="w-full block text-center py-4 text-gray-500 font-medium">
                    {{ $t('auth.login') }}
                </Link>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { HeartIcon } from 'lucide-vue-next';

const next = () => {
    router.get(route('onboarding.step', { step: 2 }));
};
</script>
