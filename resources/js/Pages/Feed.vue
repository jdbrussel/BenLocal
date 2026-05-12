<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Feed</h1>
            <div class="space-y-6">
                <div v-for="i in 3" :key="i" class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center font-bold text-amber-600">L</div>
                        <div>
                            <p class="font-bold text-sm">Local Guide</p>
                            <p class="text-xs text-gray-500">Recommended a new spot • 2h ago</p>
                        </div>
                    </div>
                    <div class="aspect-video bg-gray-100">
                         <img :src="`https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80`" class="w-full h-full object-cover" />
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold mb-1">Authentic Tapas Bar</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">This place just opened and it's already a favorite among my friends. You must try the chipirones!</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
