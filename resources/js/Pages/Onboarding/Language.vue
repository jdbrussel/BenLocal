<template>
    <OnboardingLayout :current-step="2">
        <div class="flex flex-col h-full">
            <h1 class="text-2xl font-bold mb-2">{{ $t('ui.onboarding.language.title') }}</h1>
            <p class="text-gray-500 mb-8">{{ $t('ui.onboarding.language.subtitle') }}</p>

            <div class="grid grid-cols-1 gap-4 overflow-y-auto pb-4">
                <button v-for="(name, code) in $page.props.config.available_languages"
                        :key="code"
                        @click="switchLanguage(code)"
                        class="w-full p-6 rounded-[28px] border-2 flex items-center justify-between transition-all duration-300 relative overflow-hidden group"
                        :class="selectedLanguage === code ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 shadow-lg shadow-amber-500/10 scale-[1.02]' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 hover:border-amber-200 dark:hover:border-amber-900/40'">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            {{ getFlag(code) }}
                        </div>
                        <div class="text-left">
                            <span class="block font-black text-lg tracking-tight">{{ name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-widest">{{ code }}</span>
                        </div>
                    </div>
                    <div v-if="selectedLanguage === code" class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/40">
                        <CheckIcon class="w-5 h-5 text-white stroke-[3]" />
                    </div>
                </button>
            </div>

            <div class="mt-auto">
                <button @click="next" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                    {{ $t('ui.onboarding.next') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { CheckIcon } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();
const page = usePage();
const selectedLanguage = ref(page.props.locale);

const getFlag = (code) => {
    const flags = {
        nl: '🇳🇱',
        en: '🇬🇧',
        es: '🇪🇸',
        de: '🇩🇪',
        fr: '🇫🇷'
    };
    return flags[code] || '🌍';
};

const switchLanguage = (code) => {
    selectedLanguage.value = code;
    router.post(route('locale.switch'), { locale: code }, {
        onSuccess: () => {
            locale.value = code;
        },
        preserveScroll: true
    });
};

const next = () => {
    router.get(route('onboarding.step', { step: 3 }));
};
</script>
