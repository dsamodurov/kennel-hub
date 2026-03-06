<script lang="ts" setup>
import { computed, onMounted, onBeforeUnmount, ref, watch } from 'vue';
import Vditor from 'vditor';
import 'vditor/dist/index.css';
import { useI18n } from '@/i18n';

const props = withDefaults(defineProps<{
    modelValue: string;
    placeholder?: string;
    minimal?: boolean; // true для "описания", false для "контента"
    uploadUrl?: string;
    onGallery?: () => void;
}>(), {
    modelValue: '',
    placeholder: '',
    minimal: false,
    uploadUrl: '',
    onGallery: undefined,
});

const emit = defineEmits<{
    (e: 'update:modelValue', v: string): void
}>();

const el = ref<HTMLDivElement | null>(null);
let vditor: Vditor | null = null;
let isDestroyed = false;
let isReady = false;
const { locale, t } = useI18n();
const wrapperClass = computed(() =>
    props.minimal ? 'markdown-minimal' : 'markdown-full',
);

// Набор тулбаров
const fullToolbar = [
    'headings', 'bold', 'italic', 'strike', 'underline', '|',
    'quote', 'line', 'list', 'ordered-list', 'check', 'table', '|',
    'code', 'inline-code', 'link', 'emoji', 'graph', '|',
    'undo', 'redo', 'outline', 'both', 'preview'
];

const minimalToolbar = [
    'bold', 'italic', 'underline', '|', 'undo', 'redo', '|', 'preview'
];

const galleryIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
  <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
  <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z"/>
</svg>`;

const galleryButton = {
    name: 'gallery',
    tip: t('admin.newsEdit.gallery.open'),
    icon: galleryIcon,
    click: () => props.onGallery?.(),
};

function buildToolbar() {
    if (props.minimal) {
        return minimalToolbar;
    }

    if (!props.onGallery) {
        return fullToolbar;
    }

    return [
        'headings', 'bold', 'italic', 'strike', 'underline', '|',
        'quote', 'line', 'list', 'ordered-list', 'check', 'table', '|',
        'code', 'inline-code', 'link',
        galleryButton,
        'emoji', '|',
        'undo', 'redo', 'outline', 'both', 'preview',
    ];
}

function applyTheme() {
    if (!vditor || isDestroyed || !isReady) return;
    const options = (vditor as unknown as { options?: unknown }).options;
    if (!options) return;
    try {
        const isDark = document.documentElement.classList.contains('dark');
        if (isDark) {
            vditor.setTheme('dark', 'dark', 'dark');
        } else {
            vditor.setTheme('classic', 'light', 'light');
        }
    } catch (error) {
        // Avoid crashing if editor is mid-destroy.
    }
}

onMounted(() => {
    isDestroyed = false;
    isReady = false;
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    const uploadConfig = props.uploadUrl
        ? {
            url: props.uploadUrl,
            fieldName: 'file',
            accept: 'image/*',
            headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : undefined,
        }
        : { accept: '', url: '' };

    vditor = new Vditor(el.value as HTMLDivElement, {
        value: props.modelValue ?? '',
        placeholder: props.placeholder,
        toolbar: buildToolbar(),
        cache: { enable: false },
        mode: 'ir', // более дружелюбный режим ввода
        lang: locale.value === 'ru' ? 'ru_RU' : 'en_US',
        cdn: '/vendor/vditor',
        upload: uploadConfig,
        after: () => {
            // Синхронизация начального значения, если отложилась инициализация
            if (props.modelValue) vditor?.setValue(props.modelValue);
            isReady = true;
            applyTheme();
        },
        input: (value: string) => {
            emit('update:modelValue', value);
        },
    });

    const observer = new MutationObserver(applyTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    (vditor as any).__themeObserver = observer;
});

onBeforeUnmount(() => {
    isDestroyed = true;
    const observer = (vditor as any)?.__themeObserver as MutationObserver | undefined;
    observer?.disconnect();
    vditor?.destroy?.();
    vditor = null;
});

// Если внешний modelValue изменился — обновим редактор (без зацикливания)
watch(() => props.modelValue, (val) => {
    if (!vditor) return;
    if (val !== vditor.getValue()) {
        vditor.setValue(val || '');
    }
});


function insertImage(url: string, alt: string = '') {
    if (!vditor) return;
    vditor.insertMD(`![${alt}](${url})`);
    vditor.focus();
}

defineExpose({
    insertImage,
});
</script>

<template>
    <div ref="el" class="w-full" :class="wrapperClass" />
</template>

<style scoped>
/* Можно настроить высоту */
:deep(.markdown-minimal .vditor) {
    min-height: 220px;
}

:deep(.markdown-full .vditor) {
    min-height: 50vh;
}

:deep(.vditor-content) {
    font-size: 15px;
}

/* Темная тема настраивается глобально в app.css */
</style>
