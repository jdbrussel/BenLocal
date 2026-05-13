<template>
    <OnboardingLayout :current-step="9">
        <div class="flex flex-col h-full items-center text-center">
            <div class="flex-1 flex flex-col justify-center">
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-500">
                        <PartyPopperIcon class="w-12 h-12" />
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-4 tracking-tight">{{ $t('onboarding.completion.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    {{ $t('onboarding.completion.subtitle') }}
                </p>
            </div>

            <div class="mt-auto w-full">
                <button @click="finish" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all transform active:scale-[0.98]">
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
    // We would typically hit an API endpoint here to mark onboarding as completed
    router.post(route('onboarding.complete'), {}, {
        onSuccess: () => {
            router.get(route('discover'));
        }
    });
};
</script>
