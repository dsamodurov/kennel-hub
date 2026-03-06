<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import AdminSectionCard from '@/components/admin/AdminSectionCard.vue';
import ConfirmAction from '@/components/admin/ConfirmAction.vue';
import PaginationFooter from '@/components/admin/PaginationFooter.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, router, useForm } from '@inertiajs/vue3';
import { index, destroy, store } from '@/routes/admin/media';
import { type BreadcrumbItem, type ActionsItem } from '@/types';
import { ImagePlusIcon, TrashIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useI18n } from '@/i18n';

const { t } = useI18n();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    { title: t('common.media'), href: index().url },
]);
const actions: ActionsItem[] = [];

const props = defineProps<{
    items: {
        data: Array<{
            id: number;
            url: string;
            original_name: string;
            size: number;
            width?: number | null;
            height?: number | null;
            alt_text?: string | null;
            usage_count?: number;
            usages?: Array<{
                id: number;
                field: string;
                label: string;
            }>;
        }>;
    };
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
    };
    filters: {
        search: string;
        in_use?: string | null;
    };
}>();

const search = ref(props.filters.search ?? '');
const inUseFilter = ref(props.filters.in_use ?? 'all');
const fileInput = ref<HTMLInputElement | null>(null);
const altEdits = ref<Record<number, string>>({});
const altSaving = ref<Record<number, boolean>>({});
const altErrors = ref<Record<number, string>>({});
const detailsOpen = ref(false);
const selectedItem = ref<(typeof props.items.data)[number] | null>(null);

const csrfToken =
    typeof document !== 'undefined'
        ? document
              .querySelector('meta[name=\"csrf-token\"]')
              ?.getAttribute('content') ?? ''
        : '';

const uploadForm = useForm({
    file: null as File | null,
    alt: '',
});

watch(
    () => props.items.data,
    (items) => {
        for (const item of items) {
            altEdits.value[item.id] = item.alt_text ?? '';
        }
    },
    { immediate: true },
);

function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    uploadForm.file = target.files?.[0] ?? null;
}

function upload() {
    if (!uploadForm.file) return;
    uploadForm.post(store().url, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            uploadForm.reset();
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
}

function remove(item: (typeof props.items.data)[number]) {
    router.delete(destroy(item.id), {
        preserveScroll: true,
    });
}

function confirmMessage(item: (typeof props.items.data)[number]) {
    if (item.usage_count && item.usage_count > 0) {
        const usageLabels = (item.usages ?? [])
            .map((usage) => `${usage.label} (${usage.field})`)
            .slice(0, 5)
            .join('\\n');

        return usageLabels
            ? t('admin.media.usageConfirm', { count: item.usage_count, usages: usageLabels })
            : t('admin.media.usageConfirmShort', { count: item.usage_count });
    }

    return t('admin.media.deleteConfirm');
}

function openDetails(item: (typeof props.items.data)[number]) {
    selectedItem.value = item;
    detailsOpen.value = true;
}

async function updateAlt(id: number) {
    altSaving.value[id] = true;
    altErrors.value[id] = '';

    try {
        const response = await fetch(`/admin/media/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
            body: JSON.stringify({ alt: altEdits.value[id] || null }),
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error('Failed to update alt');
        }
    } catch {
        altErrors.value[id] = t('admin.media.altSaveError');
    } finally {
        altSaving.value[id] = false;
    }
}

function applySearch() {
    router.get(index().url, { search: search.value || undefined, in_use: inUseFilter.value === 'all' ? undefined : inUseFilter.value }, {
        preserveScroll: true,
        preserveState: true,
    });
}

watch(search, (value, oldValue) => {
    if (value === '' && oldValue !== '') {
        applySearch();
    }
});
</script>

<template>
    <Head :title="t('common.media')" />
    <AppLayout :breadcrumbs="breadcrumbs" :actions="actions">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <AdminPageHeader
                :title="t('admin.media.title')"
                :description="t('admin.media.description')"
            />
            <AdminSectionCard class="space-y-2">
                <Label>{{ t('admin.media.addImage') }}</Label>
                <div class="flex flex-wrap items-center gap-2">
                    <Input ref="fileInput" type="file" accept="image/*" @change="onFileChange" />
                    <Input v-model="uploadForm.alt" :placeholder="t('admin.newsEdit.placeholders.alt')" />
                    <Button :disabled="uploadForm.processing || !uploadForm.file" @click="upload">
                        <ImagePlusIcon class="mr-2 size-4" />
                        {{ t('admin.media.upload') }}
                    </Button>
                </div>
            </AdminSectionCard>

            <div class="flex flex-wrap items-center gap-2">
                <Input
                    v-model="search"
                    :placeholder="t('admin.newsEdit.placeholders.search')"
                    @keydown.enter.prevent="applySearch"
                />
                <select
                    v-model="inUseFilter"
                    class="h-9 rounded-md border border-input bg-background px-3 text-sm"
                    @change="applySearch"
                >
                    <option value="all">{{ t('admin.media.filters.all') }}</option>
                    <option value="1">{{ t('admin.media.filters.used') }}</option>
                    <option value="0">{{ t('admin.media.filters.unused') }}</option>
                </select>
                <Button variant="secondary" @click="applySearch">{{ t('common.search') }}</Button>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div
                    v-for="item in props.items.data"
                    :key="item.id"
                    class="group overflow-hidden rounded-lg border"
                >
                    <button type="button" class="w-full" @click="openDetails(item)">
                        <img :src="item.url" :alt="item.original_name" class="h-40 w-full object-cover" />
                    </button>
                    <div class="space-y-2 p-2 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="truncate" :title="item.original_name">
                                {{ item.original_name }}
                            </span>
                            <ConfirmAction
                                :message="confirmMessage(item)"
                                @confirm="remove(item)"
                            >
                                <Button
                                    size="sm"
                                    variant="destructive"
                                    class="h-7 px-2"
                                >
                                    <TrashIcon class="size-3" />
                                </Button>
                            </ConfirmAction>
                        </div>
                        <div class="flex items-center gap-2">
                            <Input
                                v-model="altEdits[item.id]"
                                :placeholder="t('admin.media.altPlaceholder')"
                                class="h-7 text-xs"
                            />
                            <Button
                                size="sm"
                                variant="secondary"
                                class="h-7 px-2"
                                :disabled="altSaving[item.id]"
                                @click="updateAlt(item.id)"
                            >
                                {{ t('admin.media.altSave') }}
                            </Button>
                        </div>
                        <p v-if="altErrors[item.id]" class="text-xs text-red-600">
                            {{ altErrors[item.id] }}
                        </p>
                        <p v-if="item.usage_count" class="text-xs text-muted-foreground">
                            {{ t('admin.media.inUse', { count: item.usage_count }) }}
                        </p>
                    </div>
                </div>
            </div>

            <PaginationFooter
                :label="t('admin.media.page', { current: props.meta.current_page, total: props.meta.last_page })"
                :current-page="props.meta.current_page"
                :last-page="props.meta.last_page"
                :prev-label="t('admin.media.prev')"
                :next-label="t('admin.media.next')"
                @prev="router.get(index().url, { page: props.meta.current_page - 1, search })"
                @next="router.get(index().url, { page: props.meta.current_page + 1, search })"
            />
        </div>
    </AppLayout>

    <Dialog v-model:open="detailsOpen">
        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>{{ t('admin.media.detailsTitle') }}</DialogTitle>
                <DialogDescription>
                    {{ selectedItem?.original_name }}
                </DialogDescription>
            </DialogHeader>

            <div v-if="selectedItem" class="space-y-4">
                <img :src="selectedItem.url" :alt="selectedItem.original_name" class="h-64 w-full rounded-lg object-cover" />

                <div class="space-y-2">
                    <Label>{{ t('admin.media.altPlaceholder') }}</Label>
                    <div class="flex items-center gap-2">
                        <Input v-model="altEdits[selectedItem.id]" />
                        <Button
                            size="sm"
                            variant="secondary"
                            :disabled="altSaving[selectedItem.id]"
                            @click="updateAlt(selectedItem.id)"
                        >
                            {{ t('admin.media.altSave') }}
                        </Button>
                    </div>
                    <p v-if="altErrors[selectedItem.id]" class="text-xs text-red-600">
                        {{ altErrors[selectedItem.id] }}
                    </p>
                </div>

                <div class="space-y-2">
                    <p class="text-sm font-medium">
                        {{ t('admin.media.inUse', { count: selectedItem.usage_count ?? 0 }) }}
                    </p>
                    <ul v-if="selectedItem.usages?.length" class="space-y-1 text-sm text-muted-foreground">
                        <li v-for="usage in selectedItem.usages" :key="usage.id">
                            {{ usage.label }} ({{ usage.field }})
                        </li>
                    </ul>
                    <p v-else class="text-sm text-muted-foreground">
                        {{ t('admin.media.noUsages') }}
                    </p>
                </div>
            </div>

            <DialogFooter>
                <Button variant="secondary" @click="detailsOpen = false">
                    {{ t('common.close') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
