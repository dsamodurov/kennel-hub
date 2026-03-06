<script setup lang="ts">
import { ref, watch } from 'vue';
import { Markdown } from '@/components/ui/markdown';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useI18n } from '@/i18n';
import {
    library as mediaLibrary,
    store as mediaStore,
} from '@/routes/admin/media';

type MediaItem = {
    id: number;
    url: string;
    original_name: string;
    alt_text?: string | null;
};

const props = withDefaults(defineProps<{
    modelValue: string;
    placeholder?: string;
    selected?: MediaItem[];
}>(), {
    modelValue: '',
    placeholder: '',
    selected: () => [],
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'selected', item: MediaItem): void;
    (e: 'update:selected', value: MediaItem[]): void;
}>();

const { t } = useI18n();
const contentEditor = ref<InstanceType<typeof Markdown> | null>(null);
const selectedItems = ref<MediaItem[]>(props.selected ?? []);
const selectedIds = ref(new Set<number>((props.selected ?? []).map((item) => item.id)));

const galleryOpen = ref(false);
const galleryItems = ref<MediaItem[]>([]);
const galleryLoading = ref(false);
const galleryPage = ref(1);
const galleryLastPage = ref(1);
const gallerySearch = ref('');
const galleryInUse = ref('all');
const galleryUpload = ref<File | null>(null);
const galleryAlt = ref('');
const galleryError = ref<string | null>(null);
const galleryFileInput = ref<HTMLInputElement | null>(null);

const mediaLibraryUrl = mediaLibrary().url;
const mediaStoreUrl = mediaStore().url;

const csrfToken =
    typeof document !== 'undefined'
        ? document
              .querySelector('meta[name="csrf-token"]')
              ?.getAttribute('content') ?? ''
        : '';

function openGallery() {
    galleryOpen.value = true;
}

async function fetchGallery(page = 1) {
    galleryLoading.value = true;
    galleryError.value = null;
    galleryPage.value = page;

    const params = new URLSearchParams({
        page: String(page),
        per_page: '24',
    });

    if (gallerySearch.value.trim()) {
        params.set('search', gallerySearch.value.trim());
    }
    if (galleryInUse.value !== 'all') {
        params.set('in_use', galleryInUse.value);
    }

    try {
        const response = await fetch(
            `${mediaLibraryUrl}?${params.toString()}`,
            {
                headers: {
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            },
        );

        if (!response.ok) {
            throw new Error('Failed to load gallery');
        }

        const data = await response.json();
        galleryItems.value = data.items?.data ?? [];
        galleryLastPage.value = data.meta?.last_page ?? 1;
    } catch {
        galleryError.value = t('admin.newsEdit.gallery.loadError');
    } finally {
        galleryLoading.value = false;
    }
}

function onGalleryFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    galleryUpload.value = target.files?.[0] ?? null;
}

async function uploadToGallery() {
    if (!galleryUpload.value) return;
    galleryError.value = null;

    const formData = new FormData();
    formData.append('file', galleryUpload.value);
    if (galleryAlt.value.trim()) {
        formData.append('alt', galleryAlt.value.trim());
    }

    try {
        const response = await fetch(mediaStoreUrl, {
            method: 'POST',
            headers: csrfToken
                ? { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' }
                : { Accept: 'application/json' },
            body: formData,
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error('Failed to upload image');
        }

        galleryUpload.value = null;
        galleryAlt.value = '';
        if (galleryFileInput.value) {
            galleryFileInput.value.value = '';
        }
        await fetchGallery(1);
    } catch {
        galleryError.value = t('admin.newsEdit.gallery.uploadError');
    }
}

function insertFromGallery(item: MediaItem) {
    contentEditor.value?.insertImage(
        item.url,
        item.alt_text ?? item.original_name,
    );
    if (!selectedIds.value.has(item.id)) {
        selectedIds.value.add(item.id);
        selectedItems.value = [...selectedItems.value, item];
    }
    emit('selected', item);
    emit('update:selected', [...selectedItems.value]);
    galleryOpen.value = false;
}

watch(galleryOpen, (open) => {
    if (open) {
        fetchGallery(1);
    }
});

watch(
    () => props.selected,
    (next) => {
        selectedItems.value = next ? [...next] : [];
        selectedIds.value = new Set((next ?? []).map((item) => item.id));
    },
    { immediate: true },
);
</script>

<template>
    <Markdown
        ref="contentEditor"
        :model-value="modelValue"
        :placeholder="placeholder"
        :on-gallery="openGallery"
        @update:modelValue="(value) => emit('update:modelValue', value)"
    />

    <Dialog v-model:open="galleryOpen">
        <DialogContent class="w-[96vw] max-w-none sm:max-w-none">
            <DialogHeader>
                <DialogTitle>{{ t('admin.newsEdit.gallery.title') }}</DialogTitle>
                <DialogDescription>
                    {{ t('admin.newsEdit.gallery.descriptionContent') }}
                </DialogDescription>
            </DialogHeader>

            <div class="grid gap-6 md:grid-cols-[minmax(0,1fr)_280px]">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <Input
                            v-model="gallerySearch"
                            :placeholder="t('admin.newsEdit.placeholders.search')"
                            class="min-w-[220px] flex-1"
                        />
                    <select
                        v-model="galleryInUse"
                        class="h-9 rounded-md border border-input bg-background px-3 text-sm"
                    >
                        <option value="all">{{ t('admin.media.filters.all') }}</option>
                        <option value="1">{{ t('admin.media.filters.used') }}</option>
                        <option value="0">{{ t('admin.media.filters.unused') }}</option>
                    </select>
                    <Button type="button" variant="secondary" @click="fetchGallery(1)">
                        {{ t('common.search') }}
                    </Button>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <Input
                            ref="galleryFileInput"
                            type="file"
                            accept="image/*"
                            class="min-w-[220px] flex-1"
                            @change="onGalleryFileChange"
                        />
                        <Input
                            v-model="galleryAlt"
                            :placeholder="t('admin.newsEdit.placeholders.alt')"
                            class="min-w-[180px] flex-1"
                        />
                        <Button
                            type="button"
                            :disabled="!galleryUpload"
                            @click="uploadToGallery"
                        >
                            {{ t('admin.newsEdit.gallery.upload') }}
                        </Button>
                    </div>

                    <p v-if="galleryError" class="text-sm text-red-600">
                        {{ galleryError }}
                    </p>

                    <div v-if="galleryLoading" class="text-sm text-muted-foreground">
                        {{ t('common.loading') }}
                    </div>

                    <div
                        v-else
                        class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                    >
                        <button
                            v-for="item in galleryItems"
                            :key="item.id"
                            type="button"
                            class="group relative overflow-hidden rounded-lg border text-left"
                            @click="insertFromGallery(item)"
                        >
                            <span
                                v-if="selectedIds.has(item.id)"
                                class="absolute right-2 top-2 rounded-full bg-emerald-500 px-2 py-1 text-[10px] text-white"
                            >
                                ✓
                            </span>
                            <img
                                :src="item.url"
                                :alt="item.alt_text ?? item.original_name"
                                class="h-40 w-full object-cover transition group-hover:scale-[1.02]"
                            />
                            <div class="p-2 text-xs text-muted-foreground">
                                {{ item.original_name }}
                            </div>
                        </button>
                    </div>

                    <div class="flex items-center justify-between">
                        <Button
                            type="button"
                            variant="secondary"
                            :disabled="galleryPage <= 1"
                            @click="fetchGallery(galleryPage - 1)"
                        >
                            {{ t('admin.newsEdit.gallery.back') }}
                        </Button>
                        <span class="text-xs text-muted-foreground">
                            {{ t('admin.newsEdit.gallery.page', { current: galleryPage, total: galleryLastPage }) }}
                        </span>
                        <Button
                            type="button"
                            variant="secondary"
                            :disabled="galleryPage >= galleryLastPage"
                            @click="fetchGallery(galleryPage + 1)"
                        >
                            {{ t('admin.newsEdit.gallery.next') }}
                        </Button>
                    </div>
                </div>

                <div class="hidden md:flex md:flex-col">
                    <div class="text-sm font-medium">
                        {{ t('admin.media.selectedTitle') }}
                    </div>
                    <div class="mt-2 space-y-2 overflow-auto rounded-lg border p-2 text-xs text-muted-foreground">
                        <div v-if="selectedItems.length === 0">
                            {{ t('admin.media.noSelected') }}
                        </div>
                        <div v-for="item in selectedItems" :key="item.id" class="flex items-center gap-2">
                            <img :src="item.url" :alt="item.original_name" class="h-12 w-12 rounded object-cover" />
                            <span class="line-clamp-2">{{ item.original_name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <DialogFooter class="gap-2">
                <DialogClose as-child>
                    <Button type="button" variant="secondary">
                        {{ t('common.close') }}
                    </Button>
                </DialogClose>
            </DialogFooter>

            <div class="mt-4 md:hidden">
                <div class="text-sm font-medium">
                    {{ t('admin.media.selectedTitle') }}
                </div>
                <div class="mt-2 space-y-2 rounded-lg border p-2 text-xs text-muted-foreground">
                    <div v-if="selectedItems.length === 0">
                        {{ t('admin.media.noSelected') }}
                    </div>
                    <div v-for="item in selectedItems" :key="item.id" class="flex items-center gap-2">
                        <img :src="item.url" :alt="item.original_name" class="h-12 w-12 rounded object-cover" />
                        <span class="line-clamp-2">{{ item.original_name }}</span>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
