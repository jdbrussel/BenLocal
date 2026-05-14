<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col p-6 overflow-hidden">
        <div class="flex-1 flex flex-col max-w-md mx-auto w-full">
            <header class="flex justify-between items-center mb-12">
                <div class="w-10 h-10 bg-amber-400 rounded-xl flex items-center justify-center font-bold text-white text-xl">B</div>
                <div v-if="stepCount > 1" class="flex gap-1">
                    <div v-for="i in stepCount" :key="i"
                         class="h-1.5 rounded-full transition-all duration-300"
                         :class="i <= currentStep ? 'w-6 bg-amber-500' : 'w-2 bg-gray-200 dark:bg-gray-700'">
                    </div>
                </div>
            </header>

            <main class="flex-1 relative z-10">
                <slot />
            </main>

            <!-- Background Elements -->
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-200/20 dark:bg-amber-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-300/20 dark:bg-amber-600/10 rounded-full blur-3xl"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1200&q=80')] opacity-[0.03] dark:opacity-[0.05] grayscale"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    currentStep: {
        type: Number,
        default: 1
    },
    stepCount: {
        type: Number,
        default: 9
    }
});
</script>
