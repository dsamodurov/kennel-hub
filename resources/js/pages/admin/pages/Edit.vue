<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MarkdownWithGallery from '@/components/markdown/MarkdownWithGallery.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from '@/i18n';
import { computed, ref } from 'vue';

const props = defineProps<{
    item: null | {
        id: number;
        title: string;
        slug: string;
        content: string | null;
        is_published: boolean;
    };
    selected_media?: Array<{
        id: number;
        url: string;
        original_name: string;
        alt_text?: string | null;
    }>;
}>();

type MediaItem = {
    id: number;
    url: string;
    original_name: string;
    alt_text?: string | null;
};

const { t } = useI18n();

const title = computed(() =>
    props.item ? t('admin.pages.editTitle') : t('admin.pages.createTitle'),
);

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    { title: t('admin.pages.title'), href: '/admin/pages' },
    { title: title.value, href: '' },
]);

const form = useForm({
    title: props.item?.title ?? '',
    slug: props.item?.slug ?? '',
    content: props.item?.content ?? '',
    is_published: props.item?.is_published ?? true,
});
const selectedLocal = ref<MediaItem[]>(
    props.selected_media ? [...props.selected_media] : [],
);

function handleSelected(item: MediaItem) {
    if (!selectedLocal.value.find((existing) => existing.id === item.id)) {
        selectedLocal.value.push(item);
    }
}

function submit() {
    if (props.item) {
        form.post(`/admin/pages/${props.item.id}`, {
            preserveScroll: true,
        });
    } else {
        form.post('/admin/pages', {
            preserveScroll: true,
        });
    }
}

</script>

<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <AdminPageHeader
                :title="title"
                :description="t('admin.pages.editorDescription')"
            />

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label>{{ t('admin.pages.fields.title') }}</Label>
                        <Input v-model="form.title" />
                        <p v-if="form.errors.title" class="text-xs text-red-600">
                            {{ form.errors.title }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <Label>{{ t('admin.pages.fields.slug') }}</Label>
                        <Input v-model="form.slug" :placeholder="t('admin.pages.placeholders.slug')" />
                        <p v-if="form.errors.slug" class="text-xs text-red-600">
                            {{ form.errors.slug }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label>{{ t('admin.pages.fields.content') }}</Label>
                    <MarkdownWithGallery
                        v-model="form.content"
                        v-model:selected="selectedLocal"
                        @selected="handleSelected"
                    />
                    <p v-if="form.errors.content" class="text-xs text-red-600">
                        {{ form.errors.content }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <input id="page-published" type="checkbox" v-model="form.is_published" />
                    <Label for="page-published">{{ t('admin.pages.fields.published') }}</Label>
                </div>

                <div class="flex justify-end">
                    <Button :disabled="form.processing">
                        {{ form.processing ? t('admin.pages.saving') : t('admin.pages.save') }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
