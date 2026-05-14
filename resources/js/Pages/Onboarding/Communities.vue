<template>
    <OnboardingLayout :current-step="5">
        <div class="flex flex-col h-full">
            <div class="flex-1 overflow-hidden flex flex-col">
                <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.communities.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('onboarding.communities.subtitle') }}
                </p>

                <div class="grid grid-cols-1 gap-4 overflow-y-auto pb-4 pr-1">
                    <div v-for="community in communities" :key="community.id"
                         @click="toggleCommunity(community.id)"
                         class="p-5 rounded-[28px] border-2 transition-all duration-300 cursor-pointer flex items-center justify-between group relative overflow-hidden"
                         :class="selectedCommunities.includes(community.id) ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 shadow-lg shadow-amber-500/10 scale-[1.02]' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 hover:border-amber-200 dark:hover:border-amber-900/40'">
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                                {{ community.flag || '🌍' }}
                            </div>
                            <div class="text-left">
                                <h3 class="font-black text-lg tracking-tight">{{ community.name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">{{ $t('onboarding.communities.members', { count: community.members_count || 0 }) }}</p>
                            </div>
                        </div>
                        <div v-if="selectedCommunities.includes(community.id)" class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/40 relative z-10">
                            <CheckCircleIcon class="w-5 h-5 text-white stroke-[3]" />
                        </div>

                        <!-- Decorative element for selected state -->
                        <div v-if="selectedCommunities.includes(community.id)" class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl"></div>
                    </div>

                    <div v-if="communities.length === 0" class="text-center py-12 bg-white dark:bg-gray-800 rounded-[28px] border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">{{ $t('onboarding.communities.no_communities') || 'Loading communities...' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-4">
                <button @click="next" :disabled="selectedCommunities.length === 0"
                        class="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-50 disabled:grayscale text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
                    {{ $t('onboarding.continue') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { CheckCircleIcon } from 'lucide-vue-next';

const props = defineProps({
    communities: {
        type: Array,
        default: () => []
    }
});

const selectedCommunities = ref([]);

const toggleCommunity = (id) => {
    if (selectedCommunities.value.includes(id)) {
        selectedCommunities.value = selectedCommunities.value.filter(c => c !== id);
    } else {
        selectedCommunities.value.push(id);
    }
};

const next = () => {
    router.post(route('onboarding.store', { step: 5 }), {
        community_ids: selectedCommunities.value
    });
};
</script>
