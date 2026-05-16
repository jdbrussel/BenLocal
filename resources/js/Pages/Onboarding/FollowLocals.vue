<template>
    <OnboardingLayout :current-step="7">
        <div class="flex flex-col h-full">
            <div class="flex-1 overflow-hidden flex flex-col">
                <h1 class="text-2xl font-bold mb-2">{{ $t('ui.onboarding.follow.title') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $t('ui.onboarding.follow.subtitle') }}
                </p>

                <div class="space-y-4 overflow-y-auto pb-4 pr-1">
                    <div v-for="local in locals" :key="local.id"
                         class="flex items-center justify-between p-5 rounded-[28px] bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 shadow-sm transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-inner group-hover:scale-105 transition-transform">
                                    <img :src="local.avatar" alt="Avatar" class="w-full h-full object-cover" />
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-500 rounded-lg flex items-center justify-center border-2 border-white dark:border-gray-800 shadow-sm">
                                    <ShieldCheckIcon class="w-3.5 h-3.5 text-white" />
                                </div>
                            </div>
                            <div class="text-left">
                                <h3 class="font-black text-lg tracking-tight">{{ local.name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">{{ local.community }} • {{ local.status }}</p>
                            </div>
                        </div>
                        <button @click="toggleFollow(local.id)"
                                class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300"
                                :class="followed.includes(local.id) ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600' : 'bg-gray-50 dark:bg-gray-700 text-gray-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-500'">
                            <UserPlusIcon v-if="!followed.includes(local.id)" class="w-6 h-6" />
                            <CheckIcon v-else class="w-6 h-6 stroke-[3]" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-4">
                <button @click="next"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform active:scale-[0.98] text-lg uppercase tracking-wider">
                    {{ followed.length > 0 ? $t('ui.onboarding.continue') : $t('ui.onboarding.follow.skip_for_now') }}
                </button>
            </div>
        </div>
    </OnboardingLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import OnboardingLayout from '@/Layouts/OnboardingLayout.vue';
import { UserPlusIcon, CheckIcon, ShieldCheckIcon } from 'lucide-vue-next';

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
