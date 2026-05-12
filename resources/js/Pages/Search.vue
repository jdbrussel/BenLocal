<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-8 tracking-tight">Search</h1>

            <div class="relative mb-8">
                <SearchIcon class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input type="text" placeholder="Search spots, regions, or people..."
                       class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none" />
            </div>

            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Recent Searches</h2>
                <div class="flex flex-wrap gap-2">
                    <button v-for="tag in ['Paella', 'Costa Adeje', 'Live Music', 'Tacos']" :key="tag"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full text-sm font-medium">
                        {{ tag }}
                    </button>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Trending Now</h2>
                <div class="space-y-4">
                    <div v-for="i in 3" :key="i" class="flex items-center gap-4 p-2">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 font-bold">#{{ i }}</div>
                        <div>
                            <p class="font-bold">Traditional Guachinches</p>
                            <p class="text-xs text-gray-500">2.4k people searching</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { SearchIcon } from 'lucide-vue-next';
</script>
