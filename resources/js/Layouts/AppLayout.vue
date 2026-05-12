<template>
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Top Header -->
        <header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center font-bold text-white">B</div>
                    <span class="font-bold text-xl tracking-tight">BenLocal</span>
                </div>

                <div class="flex items-center gap-4">
                    <button @click="toggleTheme" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <component :is="isDark ? SunIcon : MoonIcon" class="w-5 h-5" />
                    </button>
                    <Link v-if="!$page.props.auth.user" :href="route('login')" class="text-sm font-medium text-amber-600">
                        {{ $t('auth.login') }}
                    </Link>
                    <div v-else class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600">
                        <img v-if="$page.props.auth.user.avatar" :src="$page.props.auth.user.avatar" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-xs font-bold uppercase">
                            {{ $page.props.auth.user.name.charAt(0) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 pb-20">
            <slot />
        </main>

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 pb-safe">
            <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-around">
                <Link :href="route('discover')" class="flex flex-col items-center gap-1 transition-colors" :class="route().current('discover') ? 'text-amber-500' : 'text-gray-500 hover:text-amber-400'">
                    <HomeIcon class="w-6 h-6" />
                    <span class="text-[10px] font-medium uppercase tracking-wider">{{ $t('messages.nav.discover') }}</span>
                </Link>

                <Link v-if="$page.props.auth.user" :href="route('feed')" class="flex flex-col items-center gap-1 transition-colors" :class="route().current('feed') ? 'text-amber-500' : 'text-gray-500 hover:text-amber-400'">
                    <RssIcon class="w-6 h-6" />
                    <span class="text-[10px] font-medium uppercase tracking-wider">{{ $t('messages.nav.feed') }}</span>
                </Link>

                <Link :href="route('search')" class="flex flex-col items-center gap-1 transition-colors" :class="route().current('search') ? 'text-amber-500' : 'text-gray-500 hover:text-amber-400'">
                    <SearchIcon class="w-6 h-6" />
                    <span class="text-[10px] font-medium uppercase tracking-wider">{{ $t('messages.nav.search') }}</span>
                </Link>

                <Link v-if="$page.props.auth.user" :href="route('saved')" class="flex flex-col items-center gap-1 transition-colors" :class="route().current('saved') ? 'text-amber-500' : 'text-gray-500 hover:text-amber-400'">
                    <BookmarkIcon class="w-6 h-6" />
                    <span class="text-[10px] font-medium uppercase tracking-wider">{{ $t('messages.nav.saved') }}</span>
                </Link>

                <Link :href="$page.props.auth.user ? route('profile') : route('register')" class="flex flex-col items-center gap-1 transition-colors" :class="route().current('profile') || route().current('register') ? 'text-amber-500' : 'text-gray-500 hover:text-amber-400'">
                    <UserIcon class="w-6 h-6" />
                    <span class="text-[10px] font-medium uppercase tracking-wider">{{ $page.props.auth.user ? $t('messages.nav.profile') : $t('auth.register') }}</span>
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
import {
    HomeIcon,
    RssIcon,
    SearchIcon,
    BookmarkIcon,
    UserIcon,
    SunIcon,
    MoonIcon,
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
};

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
