<template>
    <OnboardingLayout :current-step="4">
        <div class="flex flex-col h-full">
            <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.region.title') }}</h1>
            <p class="text-gray-500 mb-8">{{ $t('onboarding.region.subtitle') }}</p>

            <div class="grid grid-cols-1 gap-4 overflow-y-auto pb-4">
                <button v-for="region in regions"
                        :key="region.id"
                        @click="selectedRegionId = region.id"
                        class="w-full p-5 rounded-[28px] border-2 transition-all duration-300 flex items-center justify-between group relative overflow-hidden"
                        :class="selectedRegionId === region.id ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 shadow-lg shadow-amber-500/10 scale-[1.02]' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 hover:border-amber-200 dark:hover:border-amber-900/40'">
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                            <MapIcon class="w-7 h-7" />
                        </div>
                        <div class="text-left">
                            <span class="block font-black text-xl tracking-tight">{{ region.name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">{{ $t('common.spots') }}: {{ region.spots_count || 0 }}</span>
                        </div>
                    </div>
                    <div v-if="selectedRegionId === region.id" class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/40 relative z-10">
                        <CheckIcon class="w-5 h-5 text-white stroke-[3]" />
                    </div>

                    <!-- Decorative element for selected state -->
                    <div v-if="selectedRegionId === region.id" class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl"></div>
                </button>

                <div v-if="regions.length === 0" class="text-center py-12 bg-white dark:bg-gray-800 rounded-[28px] border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">{{ $t('onboarding.region.no_regions') || 'No regions available' }}</p>
                </div>
            </div>

            <div class="mt-auto pt-4">
                <button @click="next" :disabled="!selectedRegionId"
                        class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-50 disabled:grayscale text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
                    {{ $t('onboarding.next') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { CheckIcon, MapIcon } from 'lucide-vue-next';

const props = defineProps({
    regions: {
        type: Array,
        default: () => []
    }
});

const selectedRegionId = ref(null);

const next = () => {
    router.post(route('onboarding.store', { step: 4 }), {
        region_id: selectedRegionId.value
    });
};
</script>
