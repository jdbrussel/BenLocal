<template>
    <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
        <!-- Header -->
        <div class="p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 overflow-hidden flex items-center justify-center font-bold text-amber-600 dark:text-amber-400">
                    <img v-if="event.user.avatar" :src="event.user.avatar" class="w-full h-full object-cover" />
                    <span v-else>{{ event.user.initials }}</span>
                </div>
                <div>
                    <p class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ event.user.name }}</p>
                    <p class="text-xs text-gray-500">{{ timeAgo }} • {{ event.region?.name || 'BenLocal' }}</p>
                </div>
            </div>
            <div v-if="event.rank_score > 100" class="bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-1">
                <UsersIcon class="w-3 h-3" /> {{ $t('ui.feed.followed') }}
            </div>
        </div>

        <!-- Content -->
        <div class="px-4 pb-4">
            <!-- Recommendation -->
            <div v-if="(event.type === 'recommendation_created' || event.type === 'recommendation') && event.eventable?.spot" class="space-y-3">
                <p class="text-sm">
                    <span class="font-medium text-amber-600">{{ $t('activity.recommendation_created') }}</span>
                    <span class="font-bold"> {{ event.eventable.spot.name }}</span>
                </p>
                <div v-if="event.payload?.spot_image" class="aspect-video rounded-2xl overflow-hidden bg-gray-100">
                    <img :src="event.payload.spot_image" class="w-full h-full object-cover" />
                </div>
            </div>

            <!-- Review -->
            <div v-else-if="(event.type === 'review_created' || event.type === 'review') && event.eventable?.spot" class="space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium">
                        {{ $t('activity.review_created') }} <span class="font-bold">{{ event.eventable.spot.name }}</span>
                    </p>
                    <div v-if="event.eventable.rating" class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">
                        <StarIcon class="w-3 h-3 text-amber-400 fill-amber-400" />
                        <span class="text-xs font-bold">{{ event.eventable.rating }}</span>
                    </div>
                </div>
                <p v-if="event.eventable.text" class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 italic">
                    "{{ event.eventable.text }}"
                </p>
            </div>

            <!-- Reaction -->
            <div v-else-if="event.type === 'review_reaction_created' && event.eventable?.review?.spot" class="flex items-center gap-2">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                    <ThumbsUpIcon class="w-4 h-4 text-blue-500" />
                </div>
                <p class="text-sm">
                    {{ $t('activity.review_helpful') }} {{ $t('ui.feed.at') }} <span class="font-bold">{{ event.eventable.review.spot.name }}</span>
                </p>
            </div>

            <!-- Follow -->
            <div v-else-if="(event.type === 'follow_created' || event.type === 'user_followed' || event.type === 'follow') && event.eventable?.followed_user" class="flex items-center gap-2">
                <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-full">
                    <UserPlusIcon class="w-4 h-4 text-purple-500" />
                </div>
                <p class="text-sm">
                    {{ $t('activity.started_following') }} <span class="font-bold">{{ event.eventable.followed_user.name }}</span>
                </p>
            </div>

            <!-- Saved Spot -->
            <div v-else-if="(event.type === 'spot_saved' || event.type === 'saved_spot') && event.eventable?.spot" class="flex items-center gap-2">
                <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-full">
                    <HeartIcon class="w-4 h-4 text-red-500 fill-red-500" />
                </div>
                <p class="text-sm">
                    {{ $t('activity.saved_spot') }} <span class="font-bold">{{ event.eventable.spot.name }}</span>
                </p>
            </div>

            <!-- Hidden Gem Update -->
            <div v-else-if="(event.type === 'hidden_gem_update' || event.type === 'hidden_gem_detected') && event.payload?.spot_name" class="bg-amber-50 dark:bg-amber-900/10 p-4 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                <div class="flex items-center gap-2 mb-2">
                    <SparklesIcon class="w-4 h-4 text-amber-500" />
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600">{{ $t('ui.feed.hidden_gem_alert') }}</span>
                </div>
                <p class="text-sm font-bold">{{ event.payload.spot_name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $t('ui.feed.new_hidden_gem_desc') }}</p>
            </div>

            <!-- Default -->
            <div v-else>
                <p class="text-sm text-gray-500">{{ event.type }} activity</p>
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="px-4 py-3 border-t border-gray-50 dark:border-gray-700/50 flex items-center justify-between">
            <div class="flex gap-4">
                <button class="flex items-center gap-1.5 text-gray-400 hover:text-amber-500 transition-colors">
                    <HeartIcon class="w-4 h-4" />
                    <span class="text-xs">{{ $t('ui.common.helpful') }}</span>
                </button>
                <button class="flex items-center gap-1.5 text-gray-400 hover:text-amber-500 transition-colors">
                    <MessageSquareIcon class="w-4 h-4" />
                    <span class="text-xs">{{ $t('ui.common.comment') }}</span>
                </button>
            </div>
            <button class="text-gray-400">
                <ShareIcon class="w-4 h-4" />
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import {
    StarIcon,
    ThumbsUpIcon,
    UserPlusIcon,
    SparklesIcon,
    HeartIcon,
    MessageSquareIcon,
    ShareIcon,
    UsersIcon
} from 'lucide-vue-next';
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const timeAgo = computed(() => {
    try {
        return formatDistanceToNow(new Date(props.event.created_at), { addSuffix: true });
    } catch (e) {
        return 'recently';
    }
});
</script>
