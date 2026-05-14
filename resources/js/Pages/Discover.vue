<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto">
            <!-- Region & Filter Sticky Header -->
            <div class="sticky top-0 z-30 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl px-4 py-4 border-b border-gray-100 dark:border-gray-800 space-y-4 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <button @click="showRegionSelector = true" class="flex flex-1 items-center gap-2 bg-gray-50 dark:bg-gray-800/50 px-4 py-3 rounded-2xl border border-transparent hover:border-amber-200 transition-all group">
                        <MapPinIcon class="w-5 h-5 text-amber-500 group-hover:scale-110 transition-transform" />
                        <div class="text-left flex-1 min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 leading-none mb-1">{{ $t('common.regions') }}</p>
                            <p class="text-sm font-black truncate text-gray-900 dark:text-white">{{ selectedRegion?.name || $t('common.loading') }}</p>
                        </div>
                        <ChevronDownIcon class="w-5 h-5 text-gray-300" />
                    </button>

                    <div class="flex items-center bg-gray-50 dark:bg-gray-800/50 p-1.5 rounded-2xl border border-transparent">
                        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-amber-600 scale-105' : 'text-gray-400'" class="p-2.5 rounded-xl transition-all">
                            <ListIcon class="w-5 h-5 stroke-[2.5]" />
                        </button>
                        <button @click="viewMode = 'map'" :class="viewMode === 'map' ? 'bg-white dark:bg-gray-700 shadow-sm text-amber-600 scale-105' : 'text-gray-400'" class="p-2.5 rounded-xl transition-all">
                            <MapIcon class="w-5 h-5 stroke-[2.5]" />
                        </button>
                    </div>
                </div>

                <!-- Community Filter Chips -->
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button v-for="comm in ['NL', 'BE', 'ES', 'UK']" :key="comm"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border-2 transition-all flex items-center gap-2 whitespace-nowrap"
                            :class="activeCommunity === comm ? 'bg-amber-500 border-amber-500 text-white shadow-lg shadow-amber-500/20 scale-105' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 text-gray-400 hover:border-amber-200'">
                        <span class="text-base leading-none">{{ getCommunityFlag(comm) }}</span>
                        {{ comm }}
                    </button>
                </div>

                <!-- Category Slider -->
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button v-for="cat in categories" :key="cat.id"
                            @click="selectedCategory = cat"
                            class="whitespace-nowrap px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest transition-all border-2"
                            :class="(selectedCategory?.id === cat.id || (!selectedCategory && !cat.id)) ? 'bg-gray-900 dark:bg-white text-white dark:text-gray-900 border-gray-900 dark:border-white' : 'bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 border-transparent hover:border-gray-200'">
                        {{ cat.name }}
                    </button>
                </div>
            </div>

            <!-- List View -->
            <div v-if="viewMode === 'list'" class="p-4 space-y-8">
                <!-- Hidden Gems Section -->
                <section>
                    <div class="flex items-center justify-between mb-5 px-1">
                        <div class="flex items-center gap-2">
                            <SparklesIcon class="w-5 h-5 text-amber-500" />
                            <h2 class="text-2xl font-black tracking-tighter">{{ $t('discover.hidden_gems_near_you') }}</h2>
                        </div>
                        <button class="text-xs font-black uppercase tracking-widest text-amber-600 hover:text-amber-700">{{ $t('discover.see_all') }}</button>
                    </div>
                    <div class="flex gap-5 overflow-x-auto no-scrollbar py-2 px-1">
                        <div v-for="spot in featuredSpots" :key="spot.id" class="flex-none w-[280px] group">
                            <Link :href="route('spot.show', spot.slug)">
                                <div class="relative aspect-[10/12] rounded-[32px] overflow-hidden mb-4 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 transition-transform duration-500 group-hover:scale-[1.02]">
                                    <img :src="spot.image || 'https://images.unsplash.com/photo-1515276427842-f85802d514a2?auto=format&fit=crop&w=600&q=80'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />

                                    <!-- Badges -->
                                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                                        <div class="bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-lg shadow-amber-500/30">
                                            <SparklesIcon class="w-3.5 h-3.5" /> {{ $t('spot.hidden_gem') }}
                                        </div>
                                    </div>

                                    <button @click.prevent class="absolute top-4 right-4 p-3 bg-white/20 backdrop-blur-md border border-white/30 rounded-2xl text-white hover:bg-white/40 transition-colors">
                                        <BookmarkIcon class="w-5 h-5" />
                                    </button>

                                    <!-- Overlay Info -->
                                    <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 via-black/40 to-transparent pt-20">
                                        <h3 class="font-black text-xl text-white leading-tight mb-2">{{ spot.name }}</h3>
                                        <div class="flex items-center gap-3 text-xs text-gray-200">
                                            <div class="flex items-center gap-1">
                                                <StarIcon class="w-3.5 h-3.5 text-amber-400 fill-amber-400" />
                                                <span class="font-black">{{ spot.average_rating }}</span>
                                            </div>
                                            <span class="opacity-40">•</span>
                                            <span class="font-bold uppercase tracking-widest">{{ spot.area?.name || spot.category?.name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Trusted Locals Section -->
                <section>
                    <div class="flex items-center justify-between mb-5 px-1">
                        <div class="flex items-center gap-2">
                            <ShieldCheckIcon class="w-5 h-5 text-amber-500" />
                            <h2 class="text-2xl font-black tracking-tighter">{{ $t('discover.trusted_by_locals') }}</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Link v-for="spot in localFavorites" :key="spot.id" :href="route('spot.show', spot.slug)" class="bg-white dark:bg-gray-800 rounded-[28px] p-4 border border-gray-100 dark:border-gray-800 flex gap-4 hover:shadow-xl hover:shadow-gray-200/40 dark:hover:shadow-none transition-all duration-300 group">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden flex-none shadow-sm border border-gray-50 dark:border-gray-700">
                                <img :src="spot.image || 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=300&q=80'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            </div>
                            <div class="flex flex-col justify-between py-1 flex-1">
                                <div>
                                    <h3 class="font-black text-lg leading-tight group-hover:text-amber-600 transition-colors">{{ spot.name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                         <StarIcon class="w-3 h-3 text-amber-400 fill-amber-400" />
                                         <span class="text-xs font-black">{{ spot.average_rating }}</span>
                                         <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">• {{ spot.category?.name }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex -space-x-2">
                                        <div v-for="i in 3" :key="i" class="w-7 h-7 rounded-lg border-2 border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm">
                                            <img :src="`https://i.pravatar.cc/100?u=${spot.id+i}`" />
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $t('spot.recommended_by') }}</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </section>
            </div>

        <!-- Map View -->
        <div v-else class="h-[calc(100vh-220px)] relative overflow-hidden">
            <MapView :markers="markers" @marker-click="handleMarkerClick" />

            <!-- Map Mini Preview Card -->
            <transition name="slide-up">
                <div v-if="selectedMarker" class="absolute bottom-6 inset-x-4 z-40">
                    <Link :href="route('spot.show', selectedMarker.slug)" class="bg-white dark:bg-gray-800 rounded-[32px] p-3 border border-gray-100 dark:border-gray-800 flex gap-4 shadow-2xl transition-all group">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden flex-none shadow-sm">
                            <img :src="selectedMarker.image || 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=300&q=80'" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex flex-col justify-between py-1 flex-1 min-w-0">
                            <div class="relative pr-8">
                                <h3 class="font-black text-lg leading-tight truncate">{{ selectedMarker.name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                     <StarIcon class="w-3.5 h-3.5 text-amber-400 fill-amber-400" />
                                     <span class="text-xs font-black">{{ selectedMarker.average_rating }}</span>
                                     <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest truncate">• {{ selectedMarker.area?.name || selectedMarker.category?.name }}</span>
                                </div>
                                <button @click.prevent="selectedMarker = null" class="absolute top-0 right-0 p-1 text-gray-300 hover:text-gray-500">
                                    <XIcon class="w-5 h-5" />
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-1.5">
                                    <div v-for="i in 3" :key="i" class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm">
                                        <img :src="`https://i.pravatar.cc/100?u=${selectedMarker.id+i}`" />
                                    </div>
                                </div>
                                <div class="bg-amber-50 dark:bg-amber-900/30 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $t('spot.details') }}
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </transition>

            <!-- Map Controls (Floating) -->
            <div class="absolute top-4 right-4 flex flex-col gap-2 z-40">
                <button class="p-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                    <NavigationIcon class="w-5 h-5" />
                </button>
                <button class="p-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                    <FilterIcon class="w-5 h-5" />
                </button>
            </div>
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
import { ref, onMounted, watch, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MapView from '@/Components/MapView.vue';
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
    CheckIcon,
    ShieldCheckIcon,
    NavigationIcon,
    FilterIcon,
    XIcon
} from 'lucide-vue-next';

const { t } = useI18n();
const regions = ref([]);
const categories = ref([]);
const featuredSpots = ref([]);
const localFavorites = ref([]);
const markers = ref([]);

const selectedRegion = ref(null);
const showRegionSelector = ref(false);
const viewMode = ref('list');
const selectedCategory = ref(null);
const activeCommunity = ref('NL');
const selectedMarker = ref(null);

const getCommunityFlag = (comm) => {
    const flags = {
        'NL': '🇳🇱',
        'BE': '🇧🇪',
        'ES': '🇪🇸',
        'UK': '🇬🇧'
    };
    return flags[comm] || '🌍';
};

onMounted(async () => {
    try {
        console.log('Discover: Mounting, loading initial data...');
        const [regionsRes, categoriesRes] = await Promise.all([
            LocationService.getRegions(),
            CategoryService.getCategories()
        ]);

        regions.value = regionsRes.data.data;
        console.log('Discover: Loaded regions:', regions.value.length);

        // The category name from API might be a string or object.
        // For 'All', we use the translation key.
        categories.value = [{ id: null, name: t('common.all') }, ...categoriesRes.data.data];
        console.log('Discover: Loaded categories:', categories.value.length);

        if (regions.value.length > 0) {
            selectedRegion.value = regions.value[0];
            console.log('Discover: Auto-selected region:', selectedRegion.value.name);
        }
    } catch (error) {
        console.error('Discover: Failed to load initial data', error);
    }
});

const loadSpots = async () => {
    if (!selectedRegion.value) {
        console.log('Discover: Skipping loadSpots, no region selected');
        return;
    }

    try {
        console.log('Discover: Loading spots for region:', selectedRegion.value.slug);
        const params = {
            region: selectedRegion.value.slug,
            category: selectedCategory.value?.slug,
        };

        const [discoverRes, featuredRes, markersRes] = await Promise.all([
            DiscoveryService.discover({ ...params, per_page: 10 }),
            DiscoveryService.discover({ ...params, sort: 'hidden_gems', per_page: 5 }),
            DiscoveryService.getMapSpots(params)
        ]);

        localFavorites.value = discoverRes.data.data;
        featuredSpots.value = featuredRes.data.data;
        markers.value = markersRes.data.data;
        console.log('Discover: Spots loaded successfully');
    } catch (error) {
        console.error('Discover: Failed to load spots', error);
    }
};

const handleMarkerClick = (marker) => {
    console.log('Marker clicked:', marker);
    selectedMarker.value = marker;
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
