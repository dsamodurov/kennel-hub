<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
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
import { library as mediaLibrary } from '@/routes/admin/media';

type MediaItem = {
    id: number;
    url: string;
    original_name: string;
    alt_text?: string | null;
};

const props = withDefaults(defineProps<{
    buttonLabel?: string;
    selected?: MediaItem[];
}>(), {
    buttonLabel: '',
    selected: () => [],
});

const emit = defineEmits<{
    (e: 'select', item: MediaItem): void;
}>();

const { t } = useI18n();

const galleryOpen = ref(false);
const galleryItems = ref<MediaItem[]>([]);
const galleryLoading = ref(false);
const galleryPage = ref(1);
const galleryLastPage = ref(1);
const gallerySearch = ref('');
const galleryInUse = ref('all');
const galleryError = ref<string | null>(null);

const mediaLibraryUrl = mediaLibrary().url;
const selectedItems = ref<MediaItem[]>(props.selected ?? []);
const selectedIds = ref(new Set<number>((props.selected ?? []).map((item) => item.id)));

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

function selectItem(item: MediaItem) {
    if (!selectedIds.value.has(item.id)) {
        selectedIds.value.add(item.id);
        selectedItems.value = [...selectedItems.value, item];
    }
    emit('select', item);
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
    <Button type="button" variant="secondary" @click="galleryOpen = true">
        {{ buttonLabel || t('admin.newsEdit.cover.choose') }}
    </Button>

    <Dialog v-model:open="galleryOpen">
        <DialogContent class="w-[96vw] max-w-none sm:max-w-none">
            <DialogHeader>
                <DialogTitle>{{ t('admin.newsEdit.gallery.title') }}</DialogTitle>
                <DialogDescription>
                    {{ t('admin.newsEdit.gallery.descriptionCover') }}
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
                            @click="selectItem(item)"
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
