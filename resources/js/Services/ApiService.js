import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

export const UserContextService = {
    getContext: () => api.get('/me/context'),
};

export const DiscoveryService = {
    discover: (params) => api.get('/discover', { params }),
    getMapSpots: (params) => api.get('/map/spots', { params }),
    getSpot: (slug) => api.get(`/spots/${slug}`),
};

export const LocationService = {
    getRegions: () => api.get('/regions'),
    getRegionAreas: (slug) => api.get(`/regions/${slug}/areas`),
    getAreaPlaces: (slug) => api.get(`/areas/${slug}/places`),
};

export const CategoryService = {
    getSectors: () => api.get('/sectors'),
    getCategories: () => api.get('/categories'),
    getCategorySpecs: (slug) => api.get(`/categories/${slug}/specs`),
};

export const SearchService = {
    search: (q) => api.get('/search', { params: { q } }),
};

export const SavedSpotService = {
    getSavedSpots: () => api.get('/saved-spots'),
    saveSpot: (slug) => api.post(`/spots/${slug}/save`),
    unsaveSpot: (slug) => api.delete(`/spots/${slug}/save`),
};

export const FeedService = {
    getFeed: (params) => api.get('/feed', { params }),
    getUserActivity: (userId, params) => api.get(`/users/${userId}/activity`, { params }),
};

export default api;
