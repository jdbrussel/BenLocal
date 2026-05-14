<template>
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Top Header -->
        <header class="sticky top-0 z-40 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl border-b border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center font-black text-white text-xl shadow-lg shadow-amber-500/20 transform -rotate-3 group cursor-pointer hover:rotate-0 transition-transform">B</div>
                    <span class="font-black text-2xl tracking-tighter hidden xs:block">BenLocal</span>
                </div>

                <div class="flex items-center gap-2">
                    <button class="p-2.5 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                        <BellIcon class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                        <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 bg-rose-500 border-2 border-white dark:border-gray-900 rounded-full"></span>
                    </button>
                    <button @click="toggleTheme" class="p-2.5 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <component :is="isDark ? SunIcon : MoonIcon" class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                    </button>
                    <div class="h-8 w-px bg-gray-100 dark:bg-gray-800 mx-1"></div>
                    <Link v-if="!$page.props.auth.user" :href="route('login')" class="pl-2">
                        <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl">
                             <UserIcon class="w-6 h-6 text-gray-400" />
                        </div>
                    </Link>
                    <div v-else class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-900/30 overflow-hidden border-2 border-white dark:border-gray-800 shadow-sm">
                        <img v-if="$page.props.auth.user.avatar" :src="$page.props.auth.user.avatar" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-sm font-black uppercase text-amber-600">
                            {{ $page.props.auth.user.name.charAt(0) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 pb-24">
            <slot />
        </main>

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl border-t border-gray-100 dark:border-gray-800 pb-safe shadow-[0_-8px_30px_rgb(0,0,0,0.04)]">
            <div class="max-w-xl mx-auto px-6 h-20 flex items-center justify-between">
                <Link :href="route('discover')" class="flex flex-col items-center gap-1.5 transition-all relative group" :class="route().current('discover') ? 'text-amber-500 scale-110' : 'text-gray-400 hover:text-amber-400'">
                    <div class="p-1 rounded-xl transition-colors" :class="route().current('discover') ? 'bg-amber-50 dark:bg-amber-900/20' : ''">
                        <CompassIcon class="w-6 h-6 stroke-[2.5]" />
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.1em]">{{ $t('nav.discover') }}</span>
                    <div v-if="route().current('discover')" class="absolute -top-1 w-1 h-1 bg-amber-500 rounded-full"></div>
                </Link>

                <Link :href="route('map')" class="flex flex-col items-center gap-1.5 transition-all relative group" :class="route().current('map') ? 'text-amber-500 scale-110' : 'text-gray-400 hover:text-amber-400'">
                    <div class="p-1 rounded-xl transition-colors" :class="route().current('map') ? 'bg-amber-50 dark:bg-amber-900/20' : ''">
                        <MapIcon class="w-6 h-6 stroke-[2.5]" />
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.1em]">{{ $t('discover.map_view') }}</span>
                    <div v-if="route().current('map')" class="absolute -top-1 w-1 h-1 bg-amber-500 rounded-full"></div>
                </Link>

                <Link v-if="$page.props.auth.user" :href="route('feed')" class="flex flex-col items-center gap-1.5 transition-all relative group" :class="route().current('feed') ? 'text-amber-500 scale-110' : 'text-gray-400 hover:text-amber-400'">
                    <div class="p-1 rounded-xl transition-colors" :class="route().current('feed') ? 'bg-amber-50 dark:bg-amber-900/20' : ''">
                        <RssIcon class="w-6 h-6 stroke-[2.5]" />
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.1em]">{{ $t('nav.feed') }}</span>
                    <div v-if="route().current('feed')" class="absolute -top-1 w-1 h-1 bg-amber-500 rounded-full"></div>
                </Link>

                <Link :href="$page.props.auth.user ? route('saved') : route('register')" class="flex flex-col items-center gap-1.5 transition-all relative group" :class="route().current('saved') ? 'text-amber-500 scale-110' : 'text-gray-400 hover:text-amber-400'">
                    <div class="p-1 rounded-xl transition-colors" :class="route().current('saved') ? 'bg-amber-50 dark:bg-amber-900/20' : ''">
                        <BookmarkIcon class="w-6 h-6 stroke-[2.5]" />
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.1em]">{{ $t('nav.saved') }}</span>
                    <div v-if="route().current('saved')" class="absolute -top-1 w-1 h-1 bg-amber-500 rounded-full"></div>
                </Link>

                <Link :href="$page.props.auth.user ? route('profile') : route('login')" class="flex flex-col items-center gap-1.5 transition-all relative group" :class="route().current('profile') || route().current('login') ? 'text-amber-500 scale-110' : 'text-gray-400 hover:text-amber-400'">
                    <div class="p-1 rounded-xl transition-colors" :class="route().current('profile') || route().current('login') ? 'bg-amber-50 dark:bg-amber-900/20' : ''">
                        <UserIcon class="w-6 h-6 stroke-[2.5]" />
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.1em]">{{ $page.props.auth.user ? $t('nav.profile') : $t('auth.login') }}</span>
                    <div v-if="route().current('profile')" class="absolute -top-1 w-1 h-1 bg-amber-500 rounded-full"></div>
                </Link>
            </div>
        </nav>

        <!-- Global Modals/Notifications -->
        <transition name="fade">
            <div v-if="$page.props.flash.message" class="fixed top-20 left-4 right-4 z-50 bg-amber-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between">
                <span class="text-sm font-medium">{{ $page.props.flash.message }}</span>
                <button @click="$page.props.flash.message = null" class="p-1">
                    <XIcon class="w-4 h-4" />
                </button>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import i18n from '@/i18n';
import {
    CompassIcon,
    RssIcon,
    SearchIcon,
    BookmarkIcon,
    UserIcon,
    MapIcon,
    SunIcon,
    MoonIcon,
    BellIcon,
    XIcon
} from 'lucide-vue-next';

const isDark = ref(false);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    updateTheme();
};

const updateTheme = () => {
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }

    // Update user preference if logged in
    if (usePage().props.auth.user) {
        // We could call an API here to persist the theme, but for now we'll stick to local storage and sync on login
    }
};

watch(() => usePage().props.locale, (newLocale) => {
    if (newLocale) {
        i18n.global.locale.value = newLocale;
    }
}, { immediate: true });

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
        isDark.value = true;
    } else {
        isDark.value = false;
    }
    updateTheme();
});
</script>

<style scoped>
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
