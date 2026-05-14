<script setup>
import { ref, watch } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    Search,
    CheckCircle,
    MapPin,
    ArrowRight,
    Loader2
} from 'lucide-vue-next';

const props = defineProps({
    campaign: Object
});

const searchQuery = ref('');
const matches = ref([]);
const isSearching = ref(false);
const step = ref('search'); // search, confirm, details

const form = useForm({
    submitted_name: '',
    submitted_notes: '',
    submitted_place_hint: '',
    matched_spot_id: null,
    user_confirmed_spot: false,
    consent_to_terms: false,
});

watch(searchQuery, async (val) => {
    if (val.length < 2) {
        matches.value = [];
        return;
    }

    isSearching.value = true;
    try {
        const response = await axios.post(route('campaign.search', props.campaign.slug), {
            query: val,
            region_id: props.campaign.region_id
        });
        matches.value = response.data;
    } catch (error) {
        console.error('Search failed', error);
    } finally {
        isSearching.value = false;
    }
});

const selectSpot = (spot) => {
    form.matched_spot_id = spot.id;
    form.submitted_name = spot.name.en || spot.name; // Use translation if available
    form.user_confirmed_spot = true;
    step.value = 'details';
};

const proceedWithUnknown = () => {
    form.submitted_name = searchQuery.value;
    form.matched_spot_id = null;
    form.user_confirmed_spot = false;
    step.value = 'details';
};

const submit = () => {
    form.post(route('campaign.submit', props.campaign.slug), {
        onSuccess: () => {
            // Inertia will handle the redirect from the controller
        },
    });
};

// Alternative submit for API-like behavior
const submitForm = async () => {
    try {
        const response = await axios.post(route('campaign.submit', props.campaign.slug), form.data());
        window.location.href = route('campaign.success', {
            slug: props.campaign.slug,
            submission: response.data.submission_id
        });
    } catch (error) {
        if (error.response?.data?.errors) {
            form.setError(error.response.data.errors);
        }
    }
};

</script>

<template>
    <Head :title="campaign.landing_title?.en || campaign.name?.en || campaign.name" />

    <AppLayout>
        <div class="max-w-2xl mx-auto py-8 px-4">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ campaign.landing_title?.en || campaign.name?.en || campaign.name }}
                </h1>
                <p class="text-gray-600">
                    {{ campaign.landing_intro?.en || campaign.description?.en || campaign.description }}
                </p>
            </div>

            <!-- Step 1: Search -->
            <div v-if="step === 'search'" class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4">Find your favorite restaurant</h2>
                <div class="relative mb-6">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 h-5 w-5" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by name..."
                        class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <div v-if="isSearching" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <Loader2 class="h-5 w-5 animate-spin text-blue-500" />
                    </div>
                </div>

                <div v-if="matches.length > 0" class="space-y-3 mb-6">
                    <p class="text-sm font-medium text-gray-500 uppercase">Existing Spots</p>
                    <button
                        v-for="spot in matches"
                        :key="spot.id"
                        @click="selectSpot(spot)"
                        class="w-full flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors text-left"
                    >
                        <div>
                            <div class="font-bold text-gray-900">{{ spot.name.en || spot.name }}</div>
                            <div class="text-sm text-gray-500 flex items-center mt-1">
                                <MapPin class="h-3 w-3 mr-1" />
                                {{ spot.region?.name }}
                            </div>
                        </div>
                        <ArrowRight class="h-5 w-5 text-gray-400" />
                    </button>
                </div>

                <div v-if="searchQuery.length >= 3" class="mt-6 border-t pt-6">
                    <button
                        @click="proceedWithUnknown"
                        class="w-full p-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-500 transition-all flex flex-col items-center justify-center"
                    >
                        <span class="font-medium">Not found?</span>
                        <span class="text-sm">Submit "{{ searchQuery }}" as a new spot</span>
                    </button>
                </div>
            </div>

            <!-- Step 2: Details -->
            <div v-if="step === 'details'" class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center mb-6">
                    <button @click="step = 'search'" class="text-blue-600 text-sm font-medium hover:underline mr-4">
                        &larr; Back
                    </button>
                    <h2 class="text-xl font-semibold">Your Recommendation</h2>
                </div>

                <div class="mb-6 p-4 bg-blue-50 rounded-lg flex items-start">
                    <CheckCircle class="h-5 w-5 text-blue-500 mr-3 mt-0.5" />
                    <div>
                        <div class="font-bold text-blue-900">{{ form.submitted_name }}</div>
                        <div v-if="form.user_confirmed_spot" class="text-sm text-blue-700">Existing spot matched</div>
                        <div v-else class="text-sm text-blue-700">New spot submission</div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Why do you recommend this place?</label>
                        <textarea
                            v-model="form.submitted_notes"
                            rows="4"
                            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Share your motivation..."
                        ></textarea>
                    </div>

                    <div v-if="!form.user_confirmed_spot">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Where is it located? (Optional)</label>
                        <input
                            v-model="form.submitted_place_hint"
                            type="text"
                            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Address, area or nearby landmarks"
                        >
                    </div>

                    <div class="flex items-start">
                        <input
                            v-model="form.consent_to_terms"
                            id="consent"
                            type="checkbox"
                            class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded"
                        >
                        <label for="consent" class="ml-3 text-sm text-gray-600">
                            I agree to the terms and conditions of this campaign.
                        </label>
                    </div>

                    <button
                        @click="submit"
                        :disabled="!form.consent_to_terms || form.processing"
                        class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-lg hover:bg-blue-700 disabled:opacity-50 transition-colors shadow-lg shadow-blue-200"
                    >
                        {{ campaign.cta_text?.en || 'Submit Recommendation' }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
