<template>
    <OnboardingLayout :current-step="9">
        <div class="flex flex-col h-full items-center text-center">
            <div class="flex-1 flex flex-col justify-center">
                <div class="mb-10 flex justify-center relative">
                    <div class="w-32 h-32 bg-green-500 rounded-[40px] flex items-center justify-center text-white shadow-2xl shadow-green-500/40 relative z-10 animate-bounce-subtle">
                        <PartyPopperIcon class="w-16 h-16" />
                    </div>
                    <div class="absolute inset-0 bg-green-400/20 rounded-full blur-3xl animate-pulse"></div>
                </div>
                <h1 class="text-4xl font-black mb-4 tracking-tighter text-gray-900 dark:text-white">{{ $t('onboarding.completion.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-xl font-medium leading-tight px-4">
                    {{ $t('onboarding.completion.subtitle') }}
                </p>
            </div>

            <div class="mt-auto w-full pb-6">
                <button @click="finish" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
                    {{ $t('onboarding.completion.finish') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { PartyPopperIcon } from 'lucide-vue-next';

const finish = () => {
    router.post(route('onboarding.complete'), {});
};
</script>

<style scoped>
@keyframes bounce-subtle {
    0%, 100% { transform: translateY(0) rotate(0); }
    50% { transform: translateY(-15px) rotate(5deg); }
}
.animate-bounce-subtle {
    animation: bounce-subtle 4s ease-in-out infinite;
}
</style>
