<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto">
            <!-- Region & Filter Sticky Header -->
            <div class="sticky top-16 z-30 bg-gray-50/90 dark:bg-gray-900/90 backdrop-blur-md px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between gap-4">
                    <button @click="showRegionSelector = true" class="flex items-center gap-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm">
                        <MapPinIcon class="w-4 h-4 text-amber-500" />
                        <span class="text-sm font-semibold truncate max-w-[120px]">{{ selectedRegion?.name || $t('common.loading') }}</span>
                        <ChevronDownIcon class="w-4 h-4 text-gray-400" />
                    </button>

                    <div class="flex items-center bg-gray-200 dark:bg-gray-800 p-1 rounded-full">
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-amber-500' : 'text-gray-500'" class="p-1.5 rounded-full transition-all">
                            <ListIcon class="w-4 h-4" />
                        </button>
                        <button @click="viewMode = 'map'" :class="viewMode === 'map' ? 'bg-white dark:bg-gray-700 shadow-sm text-amber-500' : 'text-gray-500'" class="p-1.5 rounded-full transition-all">
                            <MapIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Category Slider -->
                <div class="flex gap-2 mt-4 overflow-x-auto no-scrollbar pb-1">
                    <button v-for="cat in categories" :key="cat.id"
                            @click="selectedCategory = cat"
                            class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors"
                            :class="(selectedCategory?.id === cat.id || (!selectedCategory && !cat.id)) ? 'bg-amber-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700'">
                        {{ cat.name }}
                    </button>
                </div>
            </div>

            <!-- List View -->
            <div v-if="viewMode === 'list'" class="p-4 space-y-6">
                <!-- Hidden Gems Section -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold tracking-tight">{{ $t('discover.hidden_gems_near_you') }}</h2>
                        <button class="text-sm font-medium text-amber-600">{{ $t('discover.see_all') }}</button>
                    </div>
                    <div class="flex gap-4 overflow-x-auto no-scrollbar py-2">
                        <div v-for="spot in featuredSpots" :key="spot.id" class="flex-none w-72 group">
                            <Link :href="route('spot.show', spot.slug)">
                                <div class="relative aspect-[4/3] rounded-2xl overflow-hidden mb-3">
                                    <img :src="spot.image || 'https://images.unsplash.com/photo-1515276427842-f85802d514a2?auto=format&fit=crop&w=600&q=80'" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    <div class="absolute top-3 left-3 bg-amber-500 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full flex items-center gap-1">
                                        <SparklesIcon class="w-3 h-3" /> {{ $t('spot.hidden_gem') }}
                                    </div>
                                    <button class="absolute top-3 right-3 p-2 bg-white/20 backdrop-blur-md rounded-full text-white">
                                        <BookmarkIcon class="w-4 h-4" />
                                    </button>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg leading-tight mb-1">{{ spot.name }}</h3>
                                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ spot.category?.name }}</span>
                                        <span>•</span>
                                        <div class="flex items-center gap-0.5">
                                            <StarIcon class="w-3.5 h-3.5 text-amber-400 fill-amber-400" />
                                            <span class="font-bold text-gray-900 dark:text-gray-100">{{ spot.average_rating }}</span>
                                            <span>({{ spot.review_count }})</span>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold tracking-tight mb-4">{{ $t('discover.trusted_by_locals') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Link v-for="spot in localFavorites" :key="spot.id" :href="route('spot.show', spot.slug)" class="bg-white dark:bg-gray-800 rounded-2xl p-3 border border-gray-100 dark:border-gray-700 flex gap-4 hover:shadow-md transition-shadow">
                            <div class="w-24 h-24 rounded-xl overflow-hidden flex-none">
                                <img :src="spot.image || 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=300&q=80'" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex flex-col justify-between py-1">
                                <div>
                                    <h3 class="font-bold text-base leading-tight">{{ spot.name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ spot.category?.name }} • {{ spot.area?.name }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex -space-x-2">
                                        <div v-for="i in 3" :key="i" class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-800 bg-gray-200 overflow-hidden">
                                            <img :src="`https://i.pravatar.cc/100?u=${spot.id+i}`" />
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-medium text-gray-500">{{ $t('spot.recommended_by') }}</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </section>
            </div>

            <!-- Map View Placeholder -->
            <div v-else class="h-[calc(100vh-144px)] bg-gray-200 dark:bg-gray-800 flex flex-col items-center justify-center text-center p-8">
                <MapIcon class="w-16 h-16 text-gray-400 mb-4" />
                <h3 class="text-lg font-bold">{{ $t('discover.map_coming_soon') }}</h3>
                <p class="text-gray-500 max-w-xs">{{ $t('discover.map_coming_soon_desc') }}</p>
                <button @click="viewMode = 'list'" class="mt-6 bg-amber-500 text-white px-6 py-2 rounded-full font-bold">{{ $t('discover.list_view') }}</button>
            </div>
        </div>

        <!-- Region Selector Modal -->
        <transition name="slide-up">
            <div v-if="showRegionSelector" class="fixed inset-0 z-[60] flex items-end">
                <div @click="showRegionSelector = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                <div class="relative w-full bg-white dark:bg-gray-900 rounded-t-[32px] p-6 max-h-[80vh] overflow-y-auto">
                    <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-8"></div>
                    <h2 class="text-2xl font-bold mb-6">{{ $t('discover.choose_region') }}</h2>
                    <div class="space-y-4">
                        <button v-for="region in regions"
                                :key="region.id"
                                @click="selectedRegion = region; showRegionSelector = false"
                                class="w-full text-left p-4 rounded-2xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center justify-between transition-colors">
                            <span class="font-bold">{{ region.name }}</span>
                            <div v-if="selectedRegion?.id === region.id" class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center">
                                <CheckIcon class="w-4 h-4 text-white" />
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { LocationService, CategoryService, DiscoveryService } from '@/Services/ApiService';
import { useI18n } from 'vue-i18n';
import {
    MapPinIcon,
    ChevronDownIcon,
    ListIcon,
    MapIcon,
    SparklesIcon,
    StarIcon,
    BookmarkIcon,
    CheckIcon
} from 'lucide-vue-next';

const { t } = useI18n();
const regions = ref([]);
const categories = ref([]);
const featuredSpots = ref([]);
const localFavorites = ref([]);

const selectedRegion = ref(null);
const showRegionSelector = ref(false);
const viewMode = ref('list');
const selectedCategory = ref(null);

onMounted(async () => {
    try {
        const [regionsRes, categoriesRes] = await Promise.all([
            LocationService.getRegions(),
            CategoryService.getCategories()
        ]);

        regions.value = regionsRes.data.data;
        // The category name from API might be a string or object.
        // For 'All', we use the translation key.
        categories.value = [{ id: null, name: t('common.all') }, ...categoriesRes.data.data];

        if (regions.value.length > 0) {
            selectedRegion.value = regions.value[0];
        }
    } catch (error) {
        console.error('Failed to load initial data', error);
    }
});

const loadSpots = async () => {
    if (!selectedRegion.value) return;

    try {
        const params = {
            region: selectedRegion.value.slug,
            category: selectedCategory.value?.slug,
        };

        const [discoverRes, featuredRes] = await Promise.all([
            DiscoveryService.discover({ ...params, per_page: 10 }),
            DiscoveryService.discover({ ...params, sort: 'hidden_gems', per_page: 5 })
        ]);

        localFavorites.value = discoverRes.data.data;
        featuredSpots.value = featuredRes.data.data;
    } catch (error) {
        console.error('Failed to load spots', error);
    }
};

watch([selectedRegion, selectedCategory], () => {
    loadSpots();
});
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.slide-up-enter-active, .slide-up-leave-active {
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-up-enter-from, .slide-up-leave-to {
    transform: translateY(100%);
}
</style>
