<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-8 tracking-tight">{{ $t('nav.saved') }}</h1>

            <div v-if="savedSpots.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="spot in savedSpots" :key="spot.id" class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm group">
                    <div class="relative aspect-video">
                        <img :src="spot.image" class="w-full h-full object-cover" />
                        <button @click="unsave(spot.slug)" class="absolute top-3 right-3 p-2 bg-amber-500 rounded-full text-white shadow-lg hover:scale-110 transition-transform">
                            <BookmarkIcon class="w-4 h-4 fill-white" />
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-1">{{ spot.name }}</h3>
                        <p class="text-sm text-gray-500">{{ spot.region?.name }} • {{ spot.category?.name }}</p>
                    </div>
                </div>
            </div>

            <div v-else-if="!isLoading" class="py-20 flex flex-col items-center justify-center text-center">
                <BookmarkIcon class="w-16 h-16 text-gray-200 mb-4" />
                <h2 class="text-xl font-bold">{{ $t('discover.no_saved_spots') }}</h2>
                <p class="text-gray-500 max-w-xs mt-2">{{ $t('discover.no_saved_spots_desc') }}</p>
                <Link :href="route('discover')" class="mt-8 bg-amber-500 text-white px-8 py-3 rounded-2xl font-bold">{{ $t('onboarding.completion.finish') }}</Link>
            </div>

            <div v-else class="py-20 text-center">
                <p class="text-gray-500">{{ $t('common.loading') }}</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { SavedSpotService } from '@/Services/ApiService';
import { BookmarkIcon } from 'lucide-vue-next';

const savedSpots = ref([]);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const response = await SavedSpotService.getSavedSpots();
        savedSpots.value = response.data.data;
    } catch (error) {
        console.error('Failed to load saved spots', error);
    } finally {
        isLoading.value = false;
    }
});

const unsave = async (slug) => {
    try {
        await SavedSpotService.unsaveSpot(slug);
        savedSpots.value = savedSpots.value.filter(s => s.slug !== slug);
    } catch (error) {
        console.error('Failed to unsave spot', error);
    }
};
</script>
