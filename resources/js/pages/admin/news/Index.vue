<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import ConfirmAction from '@/components/admin/ConfirmAction.vue';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { index, create, edit, destroy } from '@/routes/admin/news';
import { type BreadcrumbItem, type ActionsItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { index as publicNews } from '@/routes/news';
import { FilePlus2Icon, FilePenIcon, FileX2Icon, LinkIcon, CalendarIcon, ArchiveRestoreIcon, TrashIcon } from 'lucide-vue-next';
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue';
import { useI18n } from '@/i18n';

const { t } = useI18n();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    { title: t('common.news'), href: index().url },
]);
const actions = computed<ActionsItem[]>(() => [
    {
        icon: FilePlus2Icon,
        label: t('admin.news.create'),
        onClick: () => {
            router.get(create());
        },
    },
]);

const props = defineProps<{
    items: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            published_at: string | null;
            published_at_human: string | null;
            is_trashed: boolean;
        }>;
        links: Array<any>;
    };
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
    };
    filters: {
        current: string;
    };
}>();

function formatLocalDateTime(utcDateTime: string | null): string | null {
    if (!utcDateTime) return null;

    const date = new Date(utcDateTime);
    if (Number.isNaN(date.getTime())) return null;

    return new Intl.DateTimeFormat(undefined, {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
}

const list = ref(props.items.data.slice());
const currentPage = ref(props.meta.current_page);
const lastPage = ref(props.meta.last_page);
const currentFilter = ref(props.filters.current);

const hasMore = computed(() => currentPage.value < lastPage.value);

const filterTabs = computed(() => [
    { key: 'all', label: t('admin.news.filters.all') },
    { key: 'published', label: t('admin.news.filters.published') },
    { key: 'scheduled', label: t('admin.news.filters.scheduled') },
    { key: 'no_date', label: t('admin.news.filters.noDate') },
    { key: 'trashed', label: t('admin.news.filters.trashed') },
]);

function setFilter(filterKey: string) {
    currentFilter.value = filterKey;
    currentPage.value = 1;
    lastPage.value = 1;
    list.value = [];

    router.visit(`${index().url}?filter=${filterKey}`, {
        preserveState: false,
        preserveScroll: false,
        only: ['items', 'meta', 'filters'],
        replace: false,
        onSuccess: (page) => {
            const pageItems = page.props.items as typeof props.items;
            const pageMeta = page.props.meta as typeof props.meta;
            list.value = pageItems.data;
            currentPage.value = pageMeta.current_page;
            lastPage.value = pageMeta.last_page;
        }
    });
}

function destroyItem(id: number) {
    router.delete(destroy(id), {
        preserveScroll: true,
        preserveState: true,
    });
}

function restoreItem(id: number) {
    router.post(`/admin/news/${id}/restore`, {}, {
        preserveScroll: true,
        preserveState: true,
    });
}

function forceDestroyItem(id: number) {
    router.delete(`/admin/news/${id}/force`, {
        preserveScroll: true,
        preserveState: true,
    });
}

const publicUrl = `${window.location.origin}${publicNews().url}`;

let loading = false;

async function loadMore() {
    if (!hasMore.value || loading) return;
    loading = true;

    const nextPage = currentPage.value + 1;

    router.visit(`${index().url}?page=${nextPage}&filter=${currentFilter.value}`, {
        preserveState: true,
        preserveScroll: true,
        preserveUrl: true,
        only: ['items', 'meta'],
        replace: true,
        onSuccess: async (page) => {
            const pageItems = page.props.items as typeof props.items;
            const pageMeta = page.props.meta as typeof props.meta;

            if (pageMeta.current_page === currentPage.value) return;

            list.value.push(...pageItems.data);
            currentPage.value = pageMeta.current_page;
            lastPage.value = pageMeta.last_page;

            await nextTick();
            attachObserver();
        },
        onFinish: () => {
            loading = false;
        }
    });
}

const sentinel = ref<HTMLElement | null>(null);
let observer: IntersectionObserver | null = null;

function attachObserver() {
    if (!sentinel.value) return;
    if (observer) {
        observer.disconnect();
    }
    observer = new IntersectionObserver(
        (entries) => {
            const [entry] = entries;
            if (entry.isIntersecting) {
                setTimeout(() => loadMore(), 0);
            }
        },
        { root: null, rootMargin: '200px', threshold: 0 }
    );
    observer.observe(sentinel.value);
}

onMounted(() => {
    attachObserver();
});

onBeforeUnmount(() => {
    if (observer) observer.disconnect();
    observer = null;
});

watch(() => props.items.data, (newData) => {
    if (currentPage.value === 1) {
        list.value = newData.slice();
    }
});

watch(
    () => props.filters.current,
    (next) => {
        currentFilter.value = next;
        list.value = props.items.data.slice();
        currentPage.value = props.meta.current_page;
        lastPage.value = props.meta.last_page;
    },
);
</script>

<template>
    <Head :title="t('common.news')" />
    <AppLayout :breadcrumbs="breadcrumbs" :actions="actions">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl">
            <div class="px-4 pt-4">
                <AdminPageHeader
                    :title="t('admin.news.title')"
                    :description="t('admin.news.description')"
                />
            </div>
            <!-- Фильтры табами -->
            <div class="flex gap-2 border-b p-2">
                <Button
                    v-for="tab in filterTabs"
                    :key="tab.key"
                    @click="setFilter(tab.key)"
                    :variant="currentFilter === tab.key ? 'default' : 'ghost'"
                    size="sm"
                >
                    {{ tab.label }}
                </Button>
            </div>

            <!-- Grid -->
            <div class="grid gap-4 p-4 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="n in list"
                    :key="n.id"
                    :class="[
                        'flex h-full flex-col rounded-lg border p-4 shadow-sm transition hover:shadow',
                        n.is_trashed ? 'bg-red-50 dark:bg-red-950' : 'bg-white dark:bg-gray-900'
                    ]"
                >
                    <div class="mb-2 flex items-baseline justify-between gap-2">
                        <h3 class="line-clamp-2 text-base font-semibold">{{ n.title }}</h3>
                        <span class="text-xs text-gray-500">#{{ n.id }}</span>
                    </div>

                    <a
                        v-if="!n.is_trashed"
                        class="mb-3 inline-flex items-center gap-1 text-sm text-blue-700 dark:text-blue-500 hover:underline"
                        :href="`${publicUrl}/${n.slug}`"
                        target="_blank"
                    >
                        <LinkIcon class="size-4" />
                        {{ `${publicUrl}/${n.slug}` }}
                    </a>

                    <div class="mt-auto flex items-baseline justify-between gap-2 pt-3">
                        <p :title="formatLocalDateTime(n.published_at) ?? ''"
                           class="flex items-center gap-2 text-sm text-gray-400 dark:text-gray-200">
                            <CalendarIcon class="size-4" />
                            <span>{{ n.published_at ? n.published_at_human : t('admin.news.emptyDate') }}</span>
                        </p>

                        <div class="flex items-center justify-end gap-2">
                            <template v-if="!n.is_trashed">
                                <Button :title="t('admin.news.actions.edit')" @click="router.get(edit(n.id))"
                                        class="bg-green-700 hover:bg-green-600 text-green-50">
                                    <FilePenIcon class="size-4" />
                                    <span class="sr-only">{{ t('admin.news.actions.edit') }}</span>
                                </Button>
                                <ConfirmAction
                                    :message="t('admin.news.confirms.trash', { title: n.title })"
                                    @confirm="destroyItem(n.id)"
                                >
                                    <Button :title="t('admin.news.actions.trash')"
                                            class="bg-red-700 hover:bg-red-600 text-red-50">
                                        <FileX2Icon class="size-4" />
                                        <span class="sr-only">{{ t('admin.news.actions.trash') }}</span>
                                    </Button>
                                </ConfirmAction>
                            </template>
                            <template v-else>
                                <ConfirmAction
                                    :message="t('admin.news.confirms.restore', { title: n.title })"
                                    @confirm="restoreItem(n.id)"
                                >
                                    <Button :title="t('admin.news.actions.restore')"
                                            class="bg-blue-700 hover:bg-blue-600 text-blue-50">
                                        <ArchiveRestoreIcon class="size-4" />
                                        <span class="sr-only">{{ t('admin.news.actions.restore') }}</span>
                                    </Button>
                                </ConfirmAction>
                                <ConfirmAction
                                    :message="t('admin.news.confirms.deleteForever', { title: n.title })"
                                    @confirm="forceDestroyItem(n.id)"
                                >
                                    <Button :title="t('admin.news.actions.deleteForever')"
                                            class="bg-red-900 hover:bg-red-800 text-red-50">
                                        <TrashIcon class="size-4" />
                                        <span class="sr-only">{{ t('admin.news.actions.deleteForever') }}</span>
                                    </Button>
                                </ConfirmAction>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- sentinel всегда внизу -->
            <div ref="sentinel" class="h-10 flex items-center justify-center text-sm text-gray-500">
                <span v-if="hasMore">{{ t('common.loadingMore') }}</span>
                <span v-else>{{ t('common.noMoreItems') }}</span>
            </div>
        </div>
    </AppLayout>
</template>
