<template>
    <div class="p-8">
        <h1>Claim Landing</h1>
        <p>Spot: {{ spot.name }}</p>
    </div>
</template>
<script setup>
defineProps({
    token: String,
    spot: Object
})
</script>
