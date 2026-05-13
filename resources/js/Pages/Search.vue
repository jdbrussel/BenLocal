<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-8 tracking-tight">{{ $t('nav.search') }}</h1>

            <div class="relative mb-8">
                <SearchIcon class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input v-model="searchQuery" type="text" :placeholder="$t('discover.search_placeholder')"
                       class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none" />
            </div>

            <div v-if="searchQuery.length >= 2" class="space-y-8">
                <div v-if="isSearching" class="text-center py-8">
                    <p class="text-gray-500">{{ $t('discover.searching') }}</p>
                </div>

                <div v-else>
                    <!-- Spot Results -->
                    <section v-if="results.spots.length > 0" class="mb-8">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">{{ $t('common.spots') }}</h2>
                        <div class="space-y-4">
                            <div v-for="spot in results.spots" :key="spot.id" class="flex items-center gap-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                                <img :src="spot.image" class="w-16 h-16 rounded-xl object-cover" />
                                <div>
                                    <p class="font-bold">{{ spot.name }}</p>
                                    <p class="text-xs text-gray-500">{{ spot.category?.name }} • {{ spot.region?.name }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Region Results -->
                    <section v-if="results.regions.length > 0" class="mb-8">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">{{ $t('common.regions') }}</h2>
                        <div class="flex flex-wrap gap-2">
                            <button v-for="region in results.regions" :key="region.id" class="px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full font-bold text-sm">
                                {{ region.name }}
                            </button>
                        </div>
                    </section>

                    <!-- User Results -->
                    <section v-if="results.users.length > 0">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">{{ $t('common.people') }}</h2>
                        <div class="space-y-3">
                            <div v-for="user in results.users" :key="user.id" class="flex items-center gap-3">
                                <img :src="user.avatar || 'https://i.pravatar.cc/100'" class="w-10 h-10 rounded-full object-cover" />
                                <span class="font-bold">{{ user.name }}</span>
                            </div>
                        </div>
                    </section>

                    <div v-if="!results.spots.length && !results.regions.length && !results.users.length" class="text-center py-8">
                        <p class="text-gray-500">{{ $t('discover.no_results_for', { query: searchQuery }) }}</p>
                    </div>
                </div>
            </div>

            <div v-else>
                <section class="mb-8">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">{{ $t('discover.recent_searches') }}</h2>
                    <div class="flex flex-wrap gap-2">
                        <button v-for="tag in ['Paella', 'Costa Adeje', 'Live Music', 'Tacos']" :key="tag"
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full text-sm font-medium">
                            {{ tag }}
                        </button>
                    </div>
                </section>

                <section>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">{{ $t('discover.trending') }}</h2>
                    <div class="space-y-4">
                        <div v-for="i in 3" :key="i" class="flex items-center gap-4 p-2">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 font-bold">#{{ i }}</div>
                            <div>
                                <p class="font-bold">Traditional Guachinches</p>
                                <p class="text-xs text-gray-500">{{ $t('discover.trending_searching', { count: '2.4k' }) }}</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { SearchService } from '@/Services/ApiService';
import { SearchIcon } from 'lucide-vue-next';

const searchQuery = ref('');
const results = ref({ spots: [], regions: [], users: [] });
const isSearching = ref(false);

let timeout = null;
const performSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        if (searchQuery.value.length < 2) {
            results.value = { spots: [], regions: [], users: [] };
            return;
        }

        isSearching.value = true;
        try {
            const response = await SearchService.search(searchQuery.value);
            results.value = response.data;
        } catch (error) {
            console.error('Search failed', error);
        } finally {
            isSearching.value = false;
        }
    }, 300);
};

watch(searchQuery, performSearch);
</script>
