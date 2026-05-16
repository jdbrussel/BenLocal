<template>
    <OnboardingLayout :current-step="1">
        <div class="flex flex-col h-full">
            <div class="flex-1 flex flex-col justify-center text-center">
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-amber-400 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-amber-500/40 relative z-10 animate-bounce-subtle">
                        <HeartIcon class="w-12 h-12 fill-current" />
                    </div>
                </div>
                <h1 class="text-4xl font-black mb-4 tracking-tighter text-gray-900 dark:text-white">{{ $t('ui.onboarding.welcome.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-xl font-medium leading-tight px-4">
                    {{ $t('ui.onboarding.welcome.subtitle') }}
                </p>
            </div>

            <div class="mt-auto space-y-4 relative z-10">
                <button @click="next" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
                    {{ $t('ui.onboarding.welcome.get_started') }}
                </button>
                <div class="flex items-center gap-4 py-2">
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-800"></div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $t('ui.auth.or') }}</span>
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-800"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-2 py-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl font-bold text-sm shadow-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" />
                        Google
                    </button>
                    <button class="flex items-center justify-center gap-2 py-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl font-bold text-sm shadow-sm">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" />
                        Facebook
                    </button>
                </div>
                <Link :href="route('login')" class="w-full block text-center py-4 text-gray-500 font-bold text-sm uppercase tracking-widest">
                    {{ $t('ui.auth.already_account') }} <span class="text-amber-500">{{ $t('ui.auth.login') }}</span>
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

<style scoped>
@keyframes bounce-subtle {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-bounce-subtle {
    animation: bounce-subtle 3s ease-in-out infinite;
}
</style>
