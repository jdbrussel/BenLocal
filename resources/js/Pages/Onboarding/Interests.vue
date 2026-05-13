<template>
    <OnboardingLayout :current-step="6">
        <div class="flex flex-col h-full">
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.interests.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('onboarding.interests.subtitle') }}
                </p>

                <div class="flex flex-wrap gap-3">
                    <button v-for="interest in interests" :key="interest.id"
                            @click="toggleInterest(interest.id)"
                            class="px-6 py-3 rounded-full border-2 transition-all font-medium"
                            :class="selectedInterests.includes(interest.id) ? 'border-amber-500 bg-amber-500 text-white' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'">
                        {{ $t('onboarding.interests.items.' + interest.key) }}
                    </button>
                </div>
            </div>

            <div class="mt-auto pt-8">
                <button @click="next"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                    {{ $t('onboarding.continue') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';

const selectedInterests = ref([]);

const interests = [
    { id: 1, key: 'hidden_gems' },
    { id: 2, key: 'beach_bars' },
    { id: 3, key: 'local_food' },
    { id: 4, key: 'fine_dining' },
    { id: 5, key: 'nightlife' },
    { id: 6, key: 'cocktails' },
    { id: 7, key: 'live_music' },
    { id: 8, key: 'seafood' },
    { id: 9, key: 'wine' },
    { id: 10, key: 'tapas' },
];

const toggleInterest = (id) => {
    if (selectedInterests.value.includes(id)) {
        selectedInterests.value = selectedInterests.value.filter(i => i !== id);
    } else {
        selectedInterests.value.push(id);
    }
};

const next = () => {
    router.get(route('onboarding.step', { step: 7 }));
};
</script>
