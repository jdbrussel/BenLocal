<template>
    <OnboardingLayout :current-step="3">
        <div class="flex flex-col h-full">
            <h1 class="text-2xl font-bold mb-2">Cookie Preferences</h1>
            <p class="text-gray-500 mb-8">We use cookies to improve your experience. Choose what you'd like to allow.</p>

            <div class="space-y-4">
                <div v-for="cat in categories" :key="cat.id" class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm capitalize">{{ cat.id }}</h3>
                        <p class="text-xs text-gray-500">{{ cat.desc }}</p>
                    </div>
                    <button @click="cat.enabled = !cat.enabled"
                            :disabled="cat.id === 'necessary'"
                            class="w-12 h-7 rounded-full transition-colors relative"
                            :class="cat.enabled ? 'bg-amber-500' : 'bg-gray-200 dark:bg-gray-700'">
                        <div class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full transition-transform"
                             :class="cat.enabled ? 'translate-x-5' : 'translate-x-0'"></div>
                    </button>
                </div>
            </div>

            <div class="mt-auto space-y-3">
                <button @click="next" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                    Accept Selection
                </button>
                <button @click="acceptAll" class="w-full text-gray-500 font-medium py-2">
                    Accept All
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';

const categories = reactive([
    { id: 'necessary', desc: 'Required for the app to function.', enabled: true },
    { id: 'analytics', desc: 'Help us understand how you use the app.', enabled: false },
    { id: 'personalization', desc: 'Allow us to show content relevant to you.', enabled: false },
    { id: 'marketing', desc: 'Used for relevant advertisements.', enabled: false },
]);

const next = () => {
    router.get(route('onboarding.step', { step: 4 }));
};

const acceptAll = () => {
    categories.forEach(c => c.enabled = true);
    next();
};
</script>
