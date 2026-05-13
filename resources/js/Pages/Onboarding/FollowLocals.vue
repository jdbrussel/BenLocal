<template>
    <OnboardingLayout :current-step="7">
        <div class="flex flex-col h-full">
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $t('onboarding.follow.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('onboarding.follow.subtitle') }}
                </p>

                <div class="space-y-4">
                    <div v-for="local in locals" :key="local.id"
                         class="flex items-center justify-between p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <img :src="local.avatar" alt="Avatar" class="w-full h-full object-cover" />
                            </div>
                            <div>
                                <h3 class="font-bold text-sm">{{ local.name }}</h3>
                                <p class="text-xs text-gray-500">{{ local.community }} • {{ local.status }}</p>
                            </div>
                        </div>
                        <button @click="toggleFollow(local.id)"
                                class="px-4 py-2 rounded-full text-xs font-bold transition-all"
                                :class="followed.includes(local.id) ? 'bg-gray-100 text-gray-700' : 'bg-amber-500 text-white'">
                            {{ followed.includes(local.id) ? $t('onboarding.follow.following') : $t('onboarding.follow.follow') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-8">
                <button @click="next"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all">
                    {{ followed.length > 0 ? $t('onboarding.continue') : $t('onboarding.follow.skip_for_now') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';

const followed = ref([]);

const locals = [
    { id: 1, name: 'Jan de Vries', community: 'Dutch', status: 'Verified Local', avatar: 'https://i.pravatar.cc/150?u=jan' },
    { id: 2, name: 'Sofie Peeters', community: 'Belgian', status: 'Local', avatar: 'https://i.pravatar.cc/150?u=sofie' },
    { id: 3, name: 'Carlos Rodriguez', community: 'Spanish', status: 'Expert', avatar: 'https://i.pravatar.cc/150?u=carlos' },
    { id: 4, name: 'Emma Wilson', community: 'UK', status: 'Regular Visitor', avatar: 'https://i.pravatar.cc/150?u=emma' },
];

const toggleFollow = (id) => {
    if (followed.value.includes(id)) {
        followed.value = followed.value.filter(i => i !== id);
    } else {
        followed.value.push(id);
    }
};

const next = () => {
    router.get(route('onboarding.step', { step: 8 }));
};
</script>
