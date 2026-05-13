<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-4">
            <header class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">{{ $t('nav.feed') }}</h1>
                <button @click="refreshFeed" :disabled="loading" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <RefreshCwIcon :class="{'animate-spin': loading}" class="w-5 h-5 text-gray-500" />
                </button>
            </header>

            <!-- Loading State -->
            <div v-if="loading && events.length === 0" class="space-y-6">
                <div v-for="i in 3" :key="i" class="bg-white dark:bg-gray-800 rounded-3xl h-64 animate-pulse"></div>
            </div>

            <!-- Empty State -->
            <div v-else-if="events.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <RssIcon class="w-10 h-10 text-gray-400" />
                </div>
                <h3 class="text-lg font-bold">{{ $t('feed.empty_title') }}</h3>
                <p class="text-gray-500 max-w-xs mt-2">{{ $t('feed.empty_desc') }}</p>
                <Link :href="route('discover')" class="mt-6 bg-amber-500 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-amber-500/30">
                    {{ $t('feed.discover_locals') }}
                </Link>
            </div>

            <!-- Feed List -->
            <div v-else class="space-y-6">
                <FeedItem v-for="event in events" :key="event.id" :event="event" />

                <!-- Loading More / Intersection Observer Target -->
                <div ref="loadMoreTrigger" class="py-10 flex justify-center">
                    <div v-if="hasMore" class="flex flex-col items-center gap-2">
                        <div class="w-6 h-6 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs text-gray-500">{{ $t('common.loading_more') }}</span>
                    </div>
                    <p v-else class="text-sm text-gray-400 italic">
                        {{ $t('feed.end_of_feed') }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FeedItem from '@/Components/Feed/FeedItem.vue';
import { FeedService } from '@/Services/ApiService';
import { RefreshCwIcon, RssIcon } from 'lucide-vue-next';

const events = ref([]);
const loading = ref(false);
const page = ref(1);
const hasMore = ref(true);
const loadMoreTrigger = ref(null);

let observer = null;

const fetchFeed = async (reset = false) => {
    if (loading.value) return;

    if (reset) {
        page.value = 1;
        events.value = [];
        hasMore.value = true;
    }

    loading.value = true;
    try {
        const response = await FeedService.getFeed({ page: page.value });
        const newEvents = response.data.data;

        if (newEvents.length === 0) {
            hasMore.value = false;
        } else {
            events.value = [...events.value, ...newEvents];
            page.value++;
            if (newEvents.length < 20) { // Assuming 20 is per_page
                hasMore.value = false;
            }
        }
    } catch (error) {
        console.error('Failed to fetch feed:', error);
    } finally {
        loading.value = false;
    }
};

const refreshFeed = () => fetchFeed(true);

onMounted(() => {
    fetchFeed();

    observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && hasMore.value && !loading.value) {
            fetchFeed();
        }
    }, { threshold: 0.1 });

    if (loadMoreTrigger.value) {
        observer.observe(loadMoreTrigger.value);
    }
});

onUnmounted(() => {
    if (observer) observer.disconnect();
});
</script>
