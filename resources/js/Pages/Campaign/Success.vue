<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CheckCircle2, Share2, PlusCircle, ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
    campaign: Object,
    submission: Object
});
</script>

<template>
    <Head title="Thank You!" />

    <AppLayout>
        <div class="max-w-2xl mx-auto py-12 px-4 text-center">
            <div class="mb-8 flex justify-center">
                <div class="bg-green-100 p-4 rounded-full">
                    <CheckCircle2 class="h-16 w-16 text-green-600" />
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                {{ campaign.success_message?.en || 'Thank you for your recommendation!' }}
            </h1>

            <p class="text-lg text-gray-600 mb-12">
                Your submission for <strong>{{ submission.submitted_name }}</strong> has been received.
                We will review it shortly.
            </p>

            <div class="grid gap-4 sm:grid-cols-2">
                <Link
                    :href="route('campaign.show', campaign.slug)"
                    class="flex items-center justify-center p-4 bg-white border-2 border-blue-600 text-blue-600 rounded-xl font-bold hover:bg-blue-50 transition-colors"
                >
                    <PlusCircle class="h-5 w-5 mr-2" />
                    Recommend Another
                </Link>

                <button
                    class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200"
                >
                    <Share2 class="h-5 w-5 mr-2" />
                    Share Campaign
                </button>
            </div>

            <div class="mt-12 pt-8 border-t">
                <Link
                    :href="route('discover')"
                    class="text-gray-500 hover:text-gray-700 flex items-center justify-center text-sm font-medium"
                >
                    <ArrowLeft class="h-4 w-4 mr-1" />
                    Back to Discover
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
