<template>
    <OnboardingLayout :current-step="4">
        <div class="flex flex-col h-full">
            <h1 class="text-2xl font-bold mb-2">Select Region</h1>
            <p class="text-gray-500 mb-8">Where are you planning to discover spots?</p>

            <div class="space-y-3">
                <button v-for="region in regions"
                        :key="region"
                        @click="selectedRegion = region"
                        class="w-full p-4 rounded-2xl border flex items-center justify-between transition-all"
                        :class="selectedRegion === region ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/10' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800'">
                    <span class="font-bold">{{ region }}</span>
                    <div v-if="selectedRegion === region" class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center">
                        <CheckIcon class="w-4 h-4 text-white" />
                    </div>
                </button>
            </div>

            <div class="mt-auto">
                <button @click="next" :disabled="!selectedRegion"
                        class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                    Next
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { CheckIcon } from 'lucide-vue-next';

const selectedRegion = ref('Tenerife');
const regions = ['Tenerife', 'Gran Canaria', 'Lanzarote', 'Fuerteventura', 'La Palma'];

const next = () => {
    router.get(route('onboarding.step', { step: 5 }));
};
</script>
