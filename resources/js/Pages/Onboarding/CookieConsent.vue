<template>
    <OnboardingLayout :current-step="3">
        <div class="flex flex-col h-full">
            <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.cookie.title') }}</h1>
            <p class="text-gray-500 mb-8">{{ $t('onboarding.cookie.message') }}</p>

            <div class="space-y-4 overflow-y-auto pb-4">
                <div v-for="cat in categories" :key="cat.id"
                     @click="cat.id !== 'necessary' ? cat.enabled = !cat.enabled : null"
                     class="p-5 rounded-[28px] bg-white dark:bg-gray-800 border-2 transition-all duration-300 flex items-center justify-between group cursor-pointer"
                     :class="cat.enabled ? 'border-amber-100 dark:border-amber-900/30' : 'border-gray-50 dark:border-gray-800/50 opacity-80'">
                    <div class="flex-1 pr-4">
                        <h3 class="font-black text-lg tracking-tight capitalize">{{ $t('common.' + cat.id) || cat.id }}</h3>
                        <p class="text-xs text-gray-500 leading-normal">{{ cat.desc }}</p>
                    </div>
                    <button class="w-14 h-8 rounded-full transition-all duration-300 relative shadow-inner"
                            :disabled="cat.id === 'necessary'"
                            :class="cat.enabled ? 'bg-amber-500 shadow-amber-500/20' : 'bg-gray-200 dark:bg-gray-700'">
                        <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform duration-300 flex items-center justify-center"
                             :class="cat.enabled ? 'translate-x-6' : 'translate-x-0'">
                             <CheckIcon v-if="cat.enabled" class="w-4 h-4 text-amber-500 stroke-[3]" />
                        </div>
                    </button>
                </div>
            </div>

            <div class="mt-auto space-y-4">
                <button @click="next" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
                    {{ $t('onboarding.cookie.save') }}
                </button>
                <button @click="acceptAll" class="w-full text-gray-400 font-bold py-2 text-sm uppercase tracking-widest hover:text-amber-500 transition-colors">
                    {{ $t('onboarding.cookie.accept_all') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { CheckIcon } from 'lucide-vue-next';

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
