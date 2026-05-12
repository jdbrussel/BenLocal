<template>
    <AppLayout>
        <div v-if="isLoading" class="flex items-center justify-center min-h-screen">
            <p class="text-gray-500">Loading spot details...</p>
        </div>
        <div v-else-if="spot" class="pb-20">
            <!-- Header with Image -->
            <div class="relative h-64 md:h-96">
                <img :src="spot.photos?.[0]?.url || 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=1200&q=80'" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <button @click="goBack" class="absolute top-4 left-4 p-2 bg-white/20 backdrop-blur-md rounded-full text-white">
                    <ArrowLeftIcon class="w-6 h-6" />
                </button>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-500 text-white text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full">
                            {{ spot.category?.name }}
                        </span>
                        <div v-for="badge in spot.badges" :key="badge.id" class="bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full">
                            {{ badge.name }}
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ spot.name }}</h1>
                    <p class="text-white/80 text-sm flex items-center gap-1">
                        <MapPinIcon class="w-4 h-4" />
                        {{ spot.location?.region?.name }}, {{ spot.location?.area?.name }}
                    </p>
                </div>
            </div>

            <div class="max-w-3xl mx-auto px-6 py-8">
                <!-- Actions -->
                <div class="flex gap-4 mb-8">
                    <button @click="toggleSave" :class="spot.saved_state ? 'bg-amber-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white'" class="flex-1 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 transition-all">
                        <BookmarkIcon :class="spot.saved_state ? 'fill-white' : ''" class="w-5 h-5" />
                        {{ spot.saved_state ? 'Saved' : 'Save' }}
                    </button>
                    <button class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white flex-1 py-3 rounded-2xl font-bold flex items-center justify-center gap-2">
                        <ShareIcon class="w-5 h-5" />
                        Share
                    </button>
                </div>

                <!-- Info -->
                <section class="mb-8">
                    <h2 class="text-xl font-bold mb-4">About</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ spot.description }}</p>
                </section>

                <!-- Recommendations -->
                <section class="mb-8">
                    <h2 class="text-xl font-bold mb-4">Recommended by locals</h2>
                    <div class="space-y-4">
                        <div v-for="rec in spot.recommendations_preview" :key="rec.id" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3 mb-3">
                                <img :src="rec.user?.avatar || 'https://i.pravatar.cc/100'" class="w-10 h-10 rounded-full object-cover" />
                                <div>
                                    <p class="font-bold text-sm">{{ rec.user?.name }}</p>
                                    <p class="text-[10px] text-gray-500">Local in Tenerife</p>
                                </div>
                            </div>
                            <p class="text-sm italic">"{{ rec.motivation }}"</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { DiscoveryService, SavedSpotService } from '@/Services/ApiService';
import { ArrowLeftIcon, MapPinIcon, BookmarkIcon, ShareIcon } from 'lucide-vue-next';

const props = defineProps({
    slug: String
});

const spot = ref(null);
const isLoading = ref(true);

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
        if (spot.value.saved_state) {
            await SavedSpotService.unsaveSpot(spot.value.slug);
            spot.value.saved_state = false;
        } else {
            await SavedSpotService.saveSpot(spot.value.slug);
            spot.value.saved_state = true;
        }
    } catch (error) {
        console.error('Toggle save failed', error);
    }
};
</script>
