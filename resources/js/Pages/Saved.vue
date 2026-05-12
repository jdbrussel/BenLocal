<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-6">
            <h1 class="text-3xl font-bold mb-8 tracking-tight">Saved Spots</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="i in 2" :key="i" class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm group">
                    <div class="relative aspect-video">
                        <img :src="`https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?auto=format&fit=crop&w=600&q=80`" class="w-full h-full object-cover" />
                        <button class="absolute top-3 right-3 p-2 bg-amber-500 rounded-full text-white shadow-lg">
                            <BookmarkIcon class="w-4 h-4 fill-white" />
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-1">Sunset Beach Bar</h3>
                        <p class="text-sm text-gray-500">Costa Adeje • Drinks & Views</p>
                    </div>
                </div>
            </div>

            <div v-if="false" class="py-20 flex flex-col items-center justify-center text-center">
                <BookmarkIcon class="w-16 h-16 text-gray-200 mb-4" />
                <h2 class="text-xl font-bold">No saved spots yet</h2>
                <p class="text-gray-500 max-w-xs mt-2">Tap the heart icon on any spot to save it for later.</p>
                <Link :href="route('discover')" class="mt-8 bg-amber-500 text-white px-8 py-3 rounded-2xl font-bold">Start Exploring</Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BookmarkIcon } from 'lucide-vue-next';
</script>
