<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto">
            <!-- Profile Header -->
            <div class="relative h-48 bg-amber-400">
                <div class="absolute -bottom-12 left-6">
                    <div class="w-24 h-24 rounded-3xl bg-white dark:bg-gray-800 p-1 shadow-xl">
                        <div class="w-full h-full rounded-2xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-2xl">
                            {{ $page.props.auth.user?.name.charAt(0) || 'U' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 pt-16 pb-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $page.props.auth.user?.name || 'Guest User' }}</h1>
                        <p class="text-sm text-gray-500">{{ $t('ui.profile.member_since') }} May 2026</p>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="route('settings')" class="bg-gray-100 dark:bg-gray-800 p-2 rounded-xl text-gray-700 dark:text-gray-300">
                            <SettingsIcon class="w-5 h-5" />
                        </Link>
                        <button class="bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-xl text-sm font-bold">{{ $t('ui.profile.edit_profile') }}</button>
                    </div>
                </div>

                <div class="flex gap-6 mb-8">
                    <div class="text-center">
                        <p class="font-bold text-lg">12</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $t('ui.spots.reviews') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-lg">5</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $t('ui.spots.recommendations') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-lg">84</p>
                        <p class="text-xs text-gray-500 uppercase">{{ $t('ui.profile.followers') }}</p>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-gray-100 dark:border-gray-800 mb-6">
                    <button v-for="tab in ['Recommendations', 'Reviews', 'Saved']" :key="tab"
                            @click="activeTab = tab"
                            class="flex-1 py-4 text-sm font-bold transition-all border-b-2"
                            :class="activeTab === tab ? 'border-amber-500 text-amber-500' : 'border-transparent text-gray-500'">
                        {{ $t('ui.profile.' + tab.toLowerCase()) }}
                    </button>
                </div>

                <!-- Empty State -->
                <div class="py-12 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-400 mb-4">
                        <BookmarkIcon v-if="activeTab === 'Saved'" class="w-8 h-8" />
                        <SparklesIcon v-else class="w-8 h-8" />
                    </div>
                    <h3 class="font-bold text-lg">{{ $t('ui.profile.' + activeTab.toLowerCase() + '_empty') }}</h3>
                    <p class="text-sm text-gray-500 max-w-xs mt-1">{{ $t('ui.feed.feed_empty_text') }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BookmarkIcon, SparklesIcon, SettingsIcon } from 'lucide-vue-next';

const activeTab = ref('Recommendations');
</script>
