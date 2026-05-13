<template>
    <OnboardingLayout :current-step="5">
        <div class="flex flex-col h-full">
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.communities.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('onboarding.communities.subtitle') }}
                </p>

                <div class="grid grid-cols-1 gap-4">
                    <div v-for="community in communities" :key="community.id"
                         @click="toggleCommunity(community.id)"
                         class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-center justify-between"
                         :class="selectedCommunities.includes(community.id) ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800'">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-2xl">
                                {{ community.flag }}
                            </div>
                            <div>
                                <h3 class="font-bold">{{ community.name }}</h3>
                                <p class="text-xs text-gray-500">{{ $t('onboarding.communities.members', { count: community.members }) }}</p>
                            </div>
                        </div>
                        <div v-if="selectedCommunities.includes(community.id)" class="text-amber-500">
                            <CheckCircleIcon class="w-6 h-6" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-8">
                <button @click="next"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
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

const selectedCommunities = ref([1]);

const communities = [
    { id: 1, name: 'Netherlands', flag: '🇳🇱', members: '12.4k' },
    { id: 2, name: 'Belgium', flag: '🇧🇪', members: '8.2k' },
    { id: 3, name: 'Germany', flag: '🇩🇪', members: '15.1k' },
    { id: 4, name: 'United Kingdom', flag: '🇬🇧', members: '18.5k' },
    { id: 5, name: 'Spain / Canaries', flag: '🇪🇸', members: '22.3k' },
];

const toggleCommunity = (id) => {
    if (selectedCommunities.value.includes(id)) {
        selectedCommunities.value = selectedCommunities.value.filter(c => c !== id);
    } else {
        selectedCommunities.value.push(id);
    }
};

const next = () => {
    router.get(route('onboarding.step', { step: 6 }));
};
</script>
