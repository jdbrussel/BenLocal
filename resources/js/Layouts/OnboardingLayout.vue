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

            <main class="flex-1 relative">
                <slot />
            </main>
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
