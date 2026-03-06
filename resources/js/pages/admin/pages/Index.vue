<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import ConfirmAction from '@/components/admin/ConfirmAction.vue';
import { Button } from '@/components/ui/button';
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem, type ActionsItem } from '@/types';
import { FilePlus2Icon, FilePenIcon, TrashIcon } from 'lucide-vue-next';
import { useI18n } from '@/i18n';
import { computed } from 'vue';

const props = defineProps<{
    items: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            is_published: boolean;
            updated_at: string | null;
        }>;
    };
}>();

const { t } = useI18n();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    { title: t('admin.pages.title'), href: '/admin/pages' },
]);

const actions = computed<ActionsItem[]>(() => [
    {
        icon: FilePlus2Icon,
        label: t('admin.pages.create'),
        onClick: () => router.get('/admin/pages/create'),
    },
]);

function editItem(id: number) {
    router.get(`/admin/pages/${id}/edit`);
}

function removeItem(id: number) {
    router.delete(`/admin/pages/${id}`);
}
</script>

<template>
    <Head :title="t('admin.pages.title')" />
    <AppLayout :breadcrumbs="breadcrumbs" :actions="actions">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <AdminPageHeader
                :title="t('admin.pages.title')"
                :description="t('admin.pages.description')"
            />

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="item in props.items.data"
                    :key="item.id"
                    class="rounded-lg border bg-white dark:bg-gray-900 p-4 shadow-sm"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="text-base font-semibold">{{ item.title }}</h3>
                            <p class="text-xs text-muted-foreground">/{{ item.slug }}</p>
                            <p class="text-xs text-muted-foreground">
                                {{ item.is_published ? t('admin.pages.published') : t('admin.pages.draft') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <Button
                                size="sm"
                                variant="secondary"
                                @click="editItem(item.id)"
                            >
                                <FilePenIcon class="size-4" />
                            </Button>
                            <ConfirmAction
                                :message="t('admin.pages.confirms.delete', { title: item.title })"
                                @confirm="removeItem(item.id)"
                            >
                                <Button
                                    size="sm"
                                    variant="destructive"
                                >
                                    <TrashIcon class="size-4" />
                                </Button>
                            </ConfirmAction>
                        </div>
                    </div>
                    <p v-if="item.updated_at" class="mt-2 text-xs text-muted-foreground">
                        {{ t('admin.pages.updatedAt', { date: item.updated_at }) }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
