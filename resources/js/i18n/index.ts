import { computed, inject, ref } from 'vue';
import { messages, type Locale } from './messages';

export type I18nInstance = {
    locale: ReturnType<typeof ref<Locale>>;
    t: (key: string, params?: Record<string, string | number>) => string;
    setLocale: (next: Locale) => void;
    availableLocales: Locale[];
};

const I18N_KEY = Symbol('i18n');

function getInitialLocale(): Locale {
    if (typeof window === 'undefined') return 'ru';
    const stored = window.localStorage.getItem('locale');
    if (stored === 'ru' || stored === 'en') return stored;
    const htmlLang = document.documentElement.lang?.toLowerCase();
    if (htmlLang?.startsWith('en')) return 'en';
    if (htmlLang?.startsWith('ru')) return 'ru';
    const browser = navigator.language?.toLowerCase() ?? '';
    return browser.startsWith('en') ? 'en' : 'ru';
}

function getMessage(locale: Locale, key: string): string | null {
    const parts = key.split('.');
    let current: any = messages[locale];

    for (const part of parts) {
        if (current && typeof current === 'object' && part in current) {
            current = current[part];
        } else {
            return null;
        }
    }

    return typeof current === 'string' ? current : null;
}

function interpolate(text: string, params?: Record<string, string | number>) {
    if (!params) return text;
    return Object.entries(params).reduce(
        (acc, [key, value]) =>
            acc.replace(new RegExp(`\\{${key}\\}`, 'g'), String(value)),
        text,
    );
}

export function createI18n() {
    const locale = ref<Locale>(getInitialLocale());
    const availableLocales = ['ru', 'en'] as Locale[];

    const t = (key: string, params?: Record<string, string | number>) => {
        const message =
            getMessage(locale.value, key) ?? getMessage('ru', key) ?? key;
        return interpolate(message, params);
    };

    const setLocale = (next: Locale) => {
        if (next === locale.value) return;
        locale.value = next;
        if (typeof window !== 'undefined') {
            window.localStorage.setItem('locale', next);
        }
        if (typeof document !== 'undefined') {
            document.documentElement.lang = next;
        }
    };

    const instance: I18nInstance = {
        locale,
        t,
        setLocale,
        availableLocales,
    };

    return {
        install(app: { provide: (key: symbol, value: I18nInstance) => void }) {
            app.provide(I18N_KEY, instance);
        },
        ...instance,
    };
}

export function useI18n() {
    const i18n = inject<I18nInstance>(I18N_KEY);
    if (!i18n) {
        throw new Error('I18n instance is not provided');
    }
    return i18n;
}

export const useLocaleLabel = () =>
    computed(() => ({
        ru: 'RU',
        en: 'EN',
    }));
