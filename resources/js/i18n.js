import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import nl from './locales/nl.json';
import es from './locales/es.json';

const i18n = createI18n({
    legacy: false,
    locale: 'en', // Default locale
    fallbackLocale: 'en',
    globalInjection: true,
    messages: {
        en,
        nl,
        es
    },
});

export default i18n;
