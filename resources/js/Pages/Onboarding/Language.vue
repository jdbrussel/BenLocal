<template>
    <OnboardingLayout :current-step="2">
        <div class="flex flex-col h-full">
            <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.language.title') }}</h1>
            <p class="text-gray-500 mb-8">{{ $t('onboarding.language.subtitle') }}</p>

            <div class="space-y-3">
                <button v-for="(name, code) in $page.props.config.available_languages"
                        :key="code"
                        @click="switchLanguage(code)"
                        class="w-full p-4 rounded-2xl border flex items-center justify-between transition-all"
                        :class="selectedLanguage === code ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/10' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800'">
                    <span class="font-bold">{{ name }}</span>
                    <div v-if="selectedLanguage === code" class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center">
                        <CheckIcon class="w-4 h-4 text-white" />
                    </div>
                </button>
            </div>

            <div class="mt-auto">
                <button @click="next" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                    {{ $t('onboarding.next') }}
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
