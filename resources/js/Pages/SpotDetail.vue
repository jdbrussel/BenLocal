<template>
    <AppLayout>
        <div v-if="isLoading" class="flex items-center justify-center min-h-screen">
            <p class="text-gray-500">{{ $t('ui.common.loading') }}</p>
        </div>
        <div v-else-if="spot" class="pb-24">
            <!-- Hero Header Section -->
            <div class="relative h-[450px] md:h-[600px] overflow-hidden">
                <img :src="spot.photos?.[0]?.url || 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=1200&q=80'" class="w-full h-full object-cover transform scale-105" />
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent"></div>

                <!-- Floating Back Button -->
                <button @click="goBack" class="absolute top-6 left-6 p-3 bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl text-white hover:bg-white/40 transition-colors z-20">
                    <ArrowLeftIcon class="w-6 h-6 stroke-[3]" />
                </button>

                <!-- Floating Save Button -->
                <button @click="toggleSave" class="absolute top-6 right-6 p-3 bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl text-white hover:bg-white/40 transition-colors z-20">
                    <BookmarkIcon :class="spot.is_saved ? 'fill-amber-500 text-amber-500' : ''" class="w-6 h-6 stroke-[3]" />
                </button>

                <!-- Hero Content -->
                <div class="absolute bottom-10 left-6 right-6 z-10">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <div class="bg-amber-500 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-xl shadow-lg shadow-amber-500/30 flex items-center gap-1.5">
                            <SparklesIcon class="w-3.5 h-3.5" /> {{ spot.category?.name }}
                        </div>
                        <div v-if="spot.is_hidden_gem" class="bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <SparklesIcon class="w-3.5 h-3.5" /> {{ $t('ui.spots.hidden_gem') }}
                        </div>
                    </div>
                    <h1 class="text-5xl font-black text-white mb-4 tracking-tighter leading-none">{{ spot.name }}</h1>

                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-2 text-white/90">
                            <StarIcon class="w-5 h-5 text-amber-400 fill-amber-400" />
                            <span class="text-lg font-black">{{ spot.average_rating }}</span>
                            <span class="text-sm font-bold opacity-60">({{ spot.review_count }} {{ $t('ui.spots.reviews') }})</span>
                        </div>
                        <div class="flex items-center gap-2 text-white/90">
                            <MapPinIcon class="w-5 h-5 text-rose-500" />
                            <span class="text-sm font-black uppercase tracking-widest">{{ spot.location?.area?.name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto px-6 -mt-8 relative z-20">
                <!-- Quick Actions / Community Pulse -->
                <div class="bg-white dark:bg-gray-900 rounded-[40px] p-8 shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <!-- Community Profile Placeholder -->
                        <div class="flex-1">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-4">{{ $t('ui.spots.community_profile') }}</h3>
                            <div class="flex items-end gap-1 h-12">
                                <div class="w-4 bg-amber-500 rounded-t-lg transition-all duration-500" style="height: 70%"></div>
                                <div class="w-4 bg-amber-400 rounded-t-lg transition-all duration-500" style="height: 15%"></div>
                                <div class="w-4 bg-amber-300 rounded-t-lg transition-all duration-500" style="height: 10%"></div>
                                <div class="w-4 bg-amber-200 rounded-t-lg transition-all duration-500" style="height: 5%"></div>
                                <div class="ml-4 flex flex-col justify-center">
                                    <span class="text-2xl font-black tracking-tighter leading-none">70%</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ activeCommunity }} Locals</span>
                                </div>
                            </div>
                        </div>

                        <!-- Main Action -->
                        <div class="flex-none">
                            <button class="w-full md:w-auto bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-8 py-5 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-gray-900/20 transition-all hover:scale-[1.02] active:scale-95">
                                {{ $t('ui.spots.recommend_spot') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <section>
                        <h2 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-6 px-1">{{ $t('ui.spots.about') }}</h2>
                        <p class="text-lg font-medium text-gray-700 dark:text-gray-300 leading-relaxed px-1">
                            {{ spot.description.value }}
                        </p>
                        <div v-if="spot.description.is_translated" class="mt-4 inline-flex items-center gap-2 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-gray-700">
                             <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">
                                {{ $t('ui.common.translated_from', { lang: spot.description.original_language }) }}
                            </span>
                        </div>
                    </section>

                    <section class="space-y-8">
                        <!-- Details Card -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-[32px] border border-gray-100 dark:border-gray-800">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-6 flex items-center gap-2">
                                <InfoIcon class="w-4 h-4" />
                                {{ $t('ui.spots.details') }}
                            </h3>
                            <div class="space-y-4">
                                <div v-for="(val, key) in spot.specs" :key="key" class="flex items-center justify-between pb-3 border-b border-gray-200/50 dark:border-gray-700 last:border-0 last:pb-0">
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500">{{ key }}</span>
                                    <span class="text-sm font-black">{{ val }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div v-if="spot.location?.latitude && spot.location?.longitude" class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-[32px] border border-gray-100 dark:border-gray-800">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-6 flex items-center gap-2">
                                <MapIcon class="w-4 h-4" />
                                {{ $t('ui.spots.location') }}
                            </h3>
                            <div class="h-40 rounded-2xl overflow-hidden mb-4 shadow-inner">
                                <MapView :center="[spot.location.latitude, spot.location.longitude]" :zoom="15" :markers="[{latitude: spot.location.latitude, longitude: spot.location.longitude, rating: spot.average_rating}]" />
                            </div>
                            <p class="text-xs font-black tracking-tight mb-2">{{ spot.contact_info.address }}</p>
                            <div class="flex gap-2">
                                <a v-if="spot.contact_info.website" :href="spot.contact_info.website" target="_blank" class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-amber-600">
                                    <GlobeIcon class="w-5 h-5" />
                                </a>
                                <a v-if="spot.contact_info.phone" :href="`tel:${spot.contact_info.phone}`" class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-amber-600">
                                    <PhoneIcon class="w-5 h-5" />
                                </a>
                                <button class="flex-1 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 font-black text-[10px] uppercase tracking-widest">
                                    {{ $t('ui.spots.directions') }}
                                </button>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Recommended by Locals Section -->
                <section class="mb-12">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                                <ShieldCheckIcon class="w-6 h-6" />
                            </div>
                            <h2 class="text-2xl font-black tracking-tighter">{{ $t('ui.spots.recommended_by_locals') }}</h2>
                        </div>
                        <span class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ spot.recommendation_count }} {{ $t('ui.spots.recommendations') }}</span>
                    </div>

                    <div class="space-y-6">
                        <div v-for="rec in spot.recommendations_preview" :key="rec.id" class="bg-white dark:bg-gray-800 p-8 rounded-[40px] border border-gray-100 dark:border-gray-800 shadow-xl shadow-gray-200/40 dark:shadow-none group transition-all hover:scale-[1.01]">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="relative">
                                    <img :src="rec.user?.avatar || 'https://i.pravatar.cc/100'" class="w-14 h-14 rounded-2xl object-cover shadow-md" />
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-500 rounded-lg flex items-center justify-center border-2 border-white dark:border-gray-800 shadow-sm">
                                        <CheckIcon class="w-3.5 h-3.5 text-white stroke-[3]" />
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-lg tracking-tight">{{ rec.user?.name }}</h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $t('ui.spots.local_in_region', { region: spot.location?.region?.name }) }}</p>
                                </div>
                                <button class="p-3 rounded-2xl border border-gray-100 dark:border-gray-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors group">
                                     <UserPlusIcon class="w-5 h-5 text-gray-400 group-hover:text-amber-500" />
                                </button>
                            </div>
                            <p class="text-xl font-black italic tracking-tight text-gray-800 dark:text-gray-200 leading-tight">
                                "{{ rec.motivation }}"
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Reviews Section -->
                <section v-if="spot.reviews_preview?.length" class="mb-12">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-8 px-1">{{ $t('ui.spots.reviews') }}</h2>
                    <div class="space-y-8">
                        <div v-for="review in spot.reviews_preview" :key="review.id" class="px-1 border-b border-gray-100 dark:border-gray-800 pb-10 last:border-0 last:pb-0">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <img :src="review.user?.avatar || 'https://i.pravatar.cc/100'" class="w-10 h-10 rounded-xl object-cover shadow-sm" />
                                    <div>
                                        <p class="font-black text-base tracking-tight">{{ review.user?.name }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="flex">
                                                <StarIcon v-for="i in 5" :key="i" class="w-3 h-3" :class="i <= review.rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200'" />
                                            </div>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ review.created_at }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 text-green-600 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                    <ShieldCheckIcon class="w-3.5 h-3.5" /> {{ $t('ui.reviews.verified_visit') }}
                                </div>
                            </div>
                            <p class="text-lg font-medium text-gray-600 dark:text-gray-400 leading-relaxed mb-6">{{ review.content.value }}</p>

                            <!-- Review Actions / AI Validation -->
                            <div class="flex items-center gap-2">
                                <button class="px-5 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 text-[10px] font-black uppercase tracking-widest hover:border-amber-200 transition-colors">
                                    {{ $t('ui.reviews.agree') }}
                                </button>
                                <button class="px-5 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 text-[10px] font-black uppercase tracking-widest hover:border-amber-200 transition-colors">
                                    {{ $t('ui.reviews.partly_agree') }}
                                </button>
                                <button class="px-5 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 text-[10px] font-black uppercase tracking-widest hover:border-rose-200 transition-colors">
                                    {{ $t('ui.reviews.disagree') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MapView from '@/Components/MapView.vue';
import { DiscoveryService, SavedSpotService } from '@/Services/ApiService';
import {
    ArrowLeftIcon,
    MapPinIcon,
    BookmarkIcon,
    ShareIcon,
    StarIcon,
    PhoneIcon,
    GlobeIcon,
    ClockIcon,
    InfoIcon,
    MapIcon,
    ShieldCheckIcon,
    CheckIcon,
    SparklesIcon,
    UserPlusIcon,
    XIcon
} from 'lucide-vue-next';

const props = defineProps({
    slug: String
});

const spot = ref(null);
const isLoading = ref(true);
const activeCommunity = ref('NL'); // Should be dynamic based on user context

onMounted(async () => {
    try {
        const response = await DiscoveryService.getSpot(props.slug);
        spot.value = response.data.data;
    } catch (error) {
        console.error('Failed to load spot', error);
    } finally {
        isLoading.value = false;
    }
});

const goBack = () => {
    window.history.back();
};

const toggleSave = async () => {
    try {
        if (spot.value.is_saved) {
            await SavedSpotService.unsaveSpot(spot.value.slug);
            spot.value.is_saved = false;
        } else {
            await SavedSpotService.saveSpot(spot.value.slug);
            spot.value.is_saved = true;
        }
    } catch (error) {
        console.error('Toggle save failed', error);
    }
};
</script>
