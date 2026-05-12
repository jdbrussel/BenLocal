import '../css/app.css';
import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'BenLocal';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        app.config.globalProperties.$t = function(key, replace = {}) {
            const keys = key.split('.');
            let translation = this.$page.props.translations;

            for (const k of keys) {
                if (translation && translation[k]) {
                    translation = translation[k];
                } else {
                    translation = key;
                    break;
                }
            }

            if (typeof translation === 'string') {
                Object.keys(replace).forEach(r => {
                    translation = translation.replace(`:${r}`, replace[r]);
                });
            }

            return translation;
        };

        app.mount(el);
    },
    progress: {
        color: '#fbbf24',
    },
});
