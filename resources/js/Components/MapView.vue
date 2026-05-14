<template>
    <div ref="mapContainer" class="w-full h-full"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    markers: {
        type: Array,
        default: () => []
    },
    center: {
        type: Array,
        default: () => [28.2916, -16.6291] // Tenerife
    },
    zoom: {
        type: Number,
        default: 11
    }
});

const emit = defineEmits(['markerClick']);

const mapContainer = ref(null);
let map = null;
let markerLayer = null;

const initMap = () => {
    if (!mapContainer.value) return;

    map = L.map(mapContainer.value, {
        zoomControl: false
    }).setView(props.center, props.zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    markerLayer = L.layerGroup().addTo(map);
    updateMarkers();
};

const updateMarkers = () => {
    if (!markerLayer) return;
    markerLayer.clearLayers();

    props.markers.forEach(marker => {
        if (!marker.latitude || !marker.longitude) return;

        const customIcon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <div class="absolute inset-0 bg-amber-500 rounded-full shadow-lg border-2 border-white"></div>
                    <div class="relative text-white font-bold text-xs">${marker.rating || ''}</div>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        const lMarker = L.marker([marker.latitude, marker.longitude], { icon: customIcon });

        lMarker.on('click', () => {
            emit('markerClick', marker);
        });

        lMarker.addTo(markerLayer);
    });
};

watch(() => props.markers, updateMarkers, { deep: true });

watch(() => props.center, (newCenter) => {
    if (map) map.setView(newCenter, props.zoom);
});

onMounted(() => {
    initMap();

    // Fix leaflet default icon issue
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    });
});

onUnmounted(() => {
    if (map) {
        map.remove();
    }
});
</script>

<style>
.custom-marker {
    background: transparent;
    border: none;
}
</style>
