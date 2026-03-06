<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import { Button } from '@/components/ui/button';
import CoverGallery from '@/components/media/CoverGallery.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MarkdownWithGallery from '@/components/markdown/MarkdownWithGallery.vue';
import { Markdown } from '@/components/ui/markdown';
import { useForm } from '@inertiajs/vue3';
import { index as publicNews } from '@/routes/news';
import { index, update, store } from '@/routes/admin/news';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from '@/i18n';

const props = defineProps<{
    item: null | {
        id: number;
        title: string;
        slug: string;
        description: string | null;
        content: string | null;
        published_at: string | null;
        cover_url?: string | null;
        cover_media_id?: number | null;
    };
    selected_media?: Array<{
        id: number;
        url: string;
        original_name: string;
        alt_text?: string | null;
    }>;
}>();

const { t } = useI18n();
const title = computed(() =>
    props.item ? t('admin.newsEdit.titleEdit') : t('admin.newsEdit.titleCreate'),
);

const breadcrumbs = computed(() => [
    {
        title: t('common.news'),
        href: index().url,
    },
    {
        title: title.value,
        href: '',
    },
]);

function toLocalDateTimeInput(utcDateTime: string | null): string {
    if (!utcDateTime) return '';

    const date = new Date(utcDateTime);
    if (Number.isNaN(date.getTime())) return '';

    const pad = (value: number) => String(value).padStart(2, '0');
    const year = date.getFullYear();
    const month = pad(date.getMonth() + 1);
    const day = pad(date.getDate());
    const hours = pad(date.getHours());
    const minutes = pad(date.getMinutes());

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function toUtcIsoString(localDateTime: string | null): string | null {
    if (!localDateTime) return null;

    const date = new Date(localDateTime);
    if (Number.isNaN(date.getTime())) return null;

    return date.toISOString();
}

const form = useForm({
    title: props.item?.title ?? '',
    slug: props.item?.slug ?? '',
    description: props.item?.description ?? '',
    content: props.item?.content ?? '',
    published_at: toLocalDateTimeInput(props.item?.published_at ?? null),
    cover: null as File | null,
    cover_media_id: props.item?.cover_media_id ?? null,
    remove_cover: false,
}).transform((data) => ({
    ...data,
    published_at: toUtcIsoString(data.published_at),
}));
const selectedMedia = computed(() => props.selected_media ?? []);
const selectedLocal = ref<(typeof props.selected_media)[number][]>([]);
const mergedSelected = computed(() => {
    const items = [...selectedMedia.value, ...selectedLocal.value];
    const unique = new Map<number, (typeof props.selected_media)[number]>();
    for (const item of items) {
        if (item && typeof item.id === 'number') {
            unique.set(item.id, item);
        }
    }
    return Array.from(unique.values());
});

type MediaItem = {
    id: number;
    url: string;
    original_name: string;
    alt_text?: string | null;
    width?: number | null;
    height?: number | null;
};

const selectedCoverUrl = ref<string | null>(props.item?.cover_url ?? null);

function submit() {
    if (props.item) {
        form.post(update(props.item.id).url, {
            forceFormData: true,
            onBefore: () => {},
            onFinish: () => {},
            preserveScroll: true,
        });
    } else {
        form.post(store().url, {
            forceFormData: true,
            onBefore: () => {},
            onFinish: () => {},
            preserveScroll: true,
        });
    }
}

const publicUrl = `${window.location.origin}${publicNews().url}`;

function removeCover() {
    form.cover = null;
    form.cover_media_id = null;
    selectedCoverUrl.value = null;
    form.remove_cover = true;
}
function selectCover(item: MediaItem) {
    form.cover_media_id = item.id;
    form.cover = null;
    form.remove_cover = false;
    selectedCoverUrl.value = item.url;
    if (!selectedLocal.value.find((existing) => existing.id === item.id)) {
        selectedLocal.value.push(item);
    }
}
</script>

<template>
    <Head :title="title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="space-y-4">
                <AdminPageHeader
                    :title="title"
                    :description="t('admin.newsEdit.description')"
                />

                <form @submit.prevent="submit" class="space-y-6">

                    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)]">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <Label>{{ t('admin.newsEdit.fields.title') }}</Label>
                                <Input v-model="form.title" />
                            </div>
                            <div class="space-y-2">
                                <Label>{{ t('admin.newsEdit.fields.url') }}</Label>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-xs text-nowrap">{{ publicUrl}}/</span>
                                    <Input
                                        v-model="form.slug"
                                        :placeholder="t('admin.newsEdit.placeholders.slug')"
                                    />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label>{{ t('admin.newsEdit.fields.description') }}</Label>
                                <Markdown v-model="form.description" :minimal="true" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <Label>{{ t('admin.newsEdit.fields.publishDate') }}</Label>
                                <Input v-model="form.published_at" type="datetime-local" />
                            </div>
                            <div class="space-y-2">
                                <Label>{{ t('admin.newsEdit.fields.cover') }}</Label>
                                <div class="mt-2">
                                    <img v-if="form.cover" :src="URL.createObjectURL(form.cover)" alt="preview" class="h-32 rounded border object-cover" />
                                    <img v-else-if="selectedCoverUrl && !form.remove_cover" :src="selectedCoverUrl" alt="cover" class="h-32 rounded border object-cover" />
                                    <p v-else class="text-sm text-muted-foreground">
                                        {{ t('admin.newsEdit.cover.empty') }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <CoverGallery @select="selectCover" :selected="mergedSelected" />
                                    <Button v-if="(form.cover || selectedCoverUrl || props.item?.cover_url) && !form.remove_cover"
                                            type="button"
                                            variant="secondary"
                                            @click="removeCover">
                                        {{ t('admin.newsEdit.cover.remove') }}
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-4">
                            <Label>{{ t('admin.newsEdit.fields.content') }}</Label>
                        </div>
                        <MarkdownWithGallery
                            v-model="form.content"
                            :selected="mergedSelected"
                            @selected="(item) => { if (!selectedLocal.find((i) => i.id === item.id)) selectedLocal.push(item); }"
                        />
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button :disabled="form.processing">
                            {{ form.processing ? t('admin.newsEdit.saving') : t('admin.newsEdit.save') }}
                        </Button>
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>

</template>
