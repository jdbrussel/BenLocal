<template>
    <OnboardingLayout :current-step="6">
        <div class="flex flex-col h-full">
            <div class="flex-1 overflow-hidden flex flex-col">
                <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.interests.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('onboarding.interests.subtitle') }}
                </p>

                <div class="flex flex-wrap gap-3 overflow-y-auto pb-4 pr-1">
                    <button v-for="interest in interests" :key="interest.id"
                            @click="toggleInterest(interest.id)"
                            class="px-6 py-4 rounded-[24px] border-2 transition-all duration-300 font-black text-sm uppercase tracking-wider flex items-center gap-2 group"
                            :class="selectedInterests.includes(interest.id) ? 'border-amber-500 bg-amber-500 text-white shadow-lg shadow-amber-500/30 scale-[1.05]' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:border-amber-200'">
                        <SparklesIcon v-if="selectedInterests.includes(interest.id)" class="w-4 h-4 animate-pulse" />
                        {{ $t('onboarding.interests.items.' + interest.key) }}
                    </button>
                </div>
            </div>

            <div class="mt-auto pt-4">
                <button @click="next"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
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
import { SparklesIcon } from 'lucide-vue-next';

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
