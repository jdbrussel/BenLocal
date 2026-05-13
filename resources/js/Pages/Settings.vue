<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-6 pb-32">
            <h1 class="text-3xl font-bold mb-8 tracking-tight">{{ $t('settings.title') }}</h1>

            <!-- Language Section -->
            <section class="mb-8">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <GlobeIcon class="w-5 h-5 text-amber-500" />
                    {{ $t('settings.language') }}
                </h2>
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                    <button v-for="(name, code) in $page.props.config.available_languages"
                            :key="code"
                            @click="switchLanguage(code)"
                            class="w-full px-6 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <span :class="currentLocale === code ? 'font-bold text-amber-600' : 'font-medium'">{{ name }}</span>
                        <div v-if="currentLocale === code" class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center">
                            <CheckIcon class="w-4 h-4 text-white" />
                        </div>
                    </button>
                </div>
            </section>

            <!-- Theme Section -->
            <section class="mb-8">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <component :is="currentTheme === 'dark' ? MoonIcon : SunIcon" class="w-5 h-5 text-amber-500" />
                    {{ $t('settings.theme') }}
                </h2>
                <div class="grid grid-cols-3 gap-4">
                    <button v-for="mode in ['light', 'dark', 'system']"
                            :key="mode"
                            @click="switchTheme(mode)"
                            class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all"
                            :class="currentTheme === mode ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800'">
                        <component :is="getThemeIcon(mode)" class="w-6 h-6" :class="currentTheme === mode ? 'text-amber-500' : 'text-gray-400'" />
                        <span class="text-xs font-bold capitalize">{{ $t('settings.themes.' + mode) }}</span>
                    </button>
                </div>
            </section>

            <!-- Logout -->
            <div class="mt-12">
                <Link :href="route('logout')" method="post" as="button" class="w-full py-4 rounded-2xl bg-red-50 text-red-600 font-bold border border-red-100 hover:bg-red-100 transition-colors">
                    {{ $t('auth.logout') }}
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useI18n } from 'vue-i18n';
import {
    GlobeIcon,
    SunIcon,
    MoonIcon,
    LaptopIcon,
    CheckIcon,
    SettingsIcon
} from 'lucide-vue-next';

const { locale } = useI18n();
const page = usePage();

const currentLocale = computed(() => page.props.locale);
const currentTheme = ref(localStorage.getItem('theme') || 'system');

const switchLanguage = (code) => {
    router.post(route('locale.switch'), { locale: code }, {
        onSuccess: () => {
            locale.value = code;
        },
        preserveScroll: true
    });
};

const switchTheme = (mode) => {
    currentTheme.value = mode;
    localStorage.setItem('theme', mode);

    if (mode === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (mode === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', systemDark);
    }
};

const getThemeIcon = (mode) => {
    if (mode === 'light') return SunIcon;
    if (mode === 'dark') return MoonIcon;
    return LaptopIcon;
};
</script>
