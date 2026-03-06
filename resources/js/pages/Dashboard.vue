<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AdminPageHeader from '@/components/admin/AdminPageHeader.vue';
import EmptyState from '@/components/admin/EmptyState.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes/admin';
import { create as createNews, index as newsIndex, edit as editNews } from '@/routes/admin/news';
import { create as createPage, index as pagesIndex, edit as editPage } from '@/routes/admin/pages';
import { index as mediaIndex } from '@/routes/admin/media';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useI18n } from '@/i18n';
import { computed } from 'vue';
import {
    FilePlus2Icon,
    ImageIcon,
    NewspaperIcon,
    FileTextIcon,
    HistoryIcon,
} from 'lucide-vue-next';

const { t } = useI18n();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: t('common.dashboard'),
        href: dashboard().url,
    },
]);

const props = defineProps<{
    stats: {
        news: {
            total: number;
            published: number;
            scheduled: number;
            no_date: number;
            trashed: number;
        };
        pages: {
            total: number;
            published: number;
            draft: number;
        };
        media: {
            total: number;
            used: number;
            unused: number;
        };
    };
    recentNews: Array<{
        id: number;
        title: string;
        slug: string;
        published_at: string | null;
        updated_at: string | null;
        is_trashed: boolean;
    }>;
    recentPages: Array<{
        id: number;
        title: string;
        slug: string;
        is_published: boolean;
        updated_at: string | null;
    }>;
    recentMedia: Array<{
        id: number;
        url: string;
        original_name: string;
        usage_count: number;
        created_at: string | null;
    }>;
}>();
</script>

<template>
    <Head :title="t('common.dashboard')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <AdminPageHeader
                :title="t('admin.dashboard.title')"
                :description="t('admin.dashboard.description')"
            />

            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)]">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between gap-3">
                        <div>
                            <CardTitle>{{ t('admin.dashboard.stats.news') }}</CardTitle>
                            <p class="text-xs text-muted-foreground">
                                {{ t('admin.dashboard.stats.total') }}: {{ props.stats.news.total }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-amber-100 p-2 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                            <NewspaperIcon class="size-5" />
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.published') }}</span>
                            <span class="font-medium">{{ props.stats.news.published }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.scheduled') }}</span>
                            <span class="font-medium">{{ props.stats.news.scheduled }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.noDate') }}</span>
                            <span class="font-medium">{{ props.stats.news.no_date }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.trashed') }}</span>
                            <span class="font-medium">{{ props.stats.news.trashed }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between gap-3">
                        <div>
                            <CardTitle>{{ t('admin.dashboard.stats.pages') }}</CardTitle>
                            <p class="text-xs text-muted-foreground">
                                {{ t('admin.dashboard.stats.total') }}: {{ props.stats.pages.total }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-sky-100 p-2 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300">
                            <FileTextIcon class="size-5" />
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.published') }}</span>
                            <span class="font-medium">{{ props.stats.pages.published }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.draft') }}</span>
                            <span class="font-medium">{{ props.stats.pages.draft }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between gap-3">
                        <div>
                            <CardTitle>{{ t('admin.dashboard.stats.media') }}</CardTitle>
                            <p class="text-xs text-muted-foreground">
                                {{ t('admin.dashboard.stats.total') }}: {{ props.stats.media.total }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                            <ImageIcon class="size-5" />
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.used') }}</span>
                            <span class="font-medium">{{ props.stats.media.used }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">{{ t('admin.dashboard.stats.unused') }}</span>
                            <span class="font-medium">{{ props.stats.media.unused }}</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <CardTitle>{{ t('admin.dashboard.quickActions') }}</CardTitle>
                        <p class="text-sm text-muted-foreground">{{ t('admin.dashboard.quickDescription') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <Button as-child>
                            <a :href="createNews().url" class="inline-flex items-center gap-2">
                                <FilePlus2Icon class="size-4" />
                                {{ t('admin.dashboard.createNews') }}
                            </a>
                        </Button>
                        <Button as-child variant="secondary">
                            <a :href="createPage().url" class="inline-flex items-center gap-2">
                                <FilePlus2Icon class="size-4" />
                                {{ t('admin.dashboard.createPage') }}
                            </a>
                        </Button>
                        <Button as-child variant="outline">
                            <a :href="mediaIndex().url" class="inline-flex items-center gap-2">
                                <ImageIcon class="size-4" />
                                {{ t('admin.dashboard.openMedia') }}
                            </a>
                        </Button>
                    </div>
                </CardHeader>
            </Card>

            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)]">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between gap-2">
                        <CardTitle>{{ t('admin.dashboard.recent.news') }}</CardTitle>
                        <a :href="newsIndex().url" class="text-xs text-muted-foreground hover:text-foreground">
                            {{ t('admin.dashboard.viewAll') }}
                        </a>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm">
                        <EmptyState
                            v-if="props.recentNews.length === 0"
                            :title="t('admin.dashboard.recent.empty')"
                        />
                        <div v-for="item in props.recentNews" :key="item.id" class="flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <a :href="editNews(item.id).url" class="font-medium hover:underline">
                                    {{ item.title }}
                                </a>
                                <div class="text-xs text-muted-foreground">
                                    <span v-if="item.published_at">{{ t('admin.dashboard.labels.published') }}: {{ item.published_at }}</span>
                                    <span v-else>{{ t('admin.dashboard.stats.noDate') }}</span>
                                </div>
                            </div>
                            <div
                                v-if="item.is_trashed"
                                class="rounded-full bg-red-100 px-2 py-1 text-[10px] font-semibold text-red-700 dark:bg-red-500/10 dark:text-red-300"
                            >
                                {{ t('admin.dashboard.labels.trashed') }}
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between gap-2">
                        <CardTitle>{{ t('admin.dashboard.recent.pages') }}</CardTitle>
                        <a :href="pagesIndex().url" class="text-xs text-muted-foreground hover:text-foreground">
                            {{ t('admin.dashboard.viewAll') }}
                        </a>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm">
                        <EmptyState
                            v-if="props.recentPages.length === 0"
                            :title="t('admin.dashboard.recent.empty')"
                        />
                        <div v-for="item in props.recentPages" :key="item.id" class="flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <a :href="editPage(item.id).url" class="font-medium hover:underline">
                                    {{ item.title }}
                                </a>
                                <div class="text-xs text-muted-foreground">
                                    {{ t('admin.dashboard.labels.updated') }}: {{ item.updated_at ?? '—' }}
                                </div>
                            </div>
                            <div
                                class="rounded-full px-2 py-1 text-[10px] font-semibold"
                                :class="item.is_published
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                                    : 'bg-slate-100 text-slate-600 dark:bg-slate-500/10 dark:text-slate-300'"
                            >
                                {{ item.is_published ? t('admin.pages.published') : t('admin.pages.draft') }}
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between gap-2">
                        <CardTitle>{{ t('admin.dashboard.recent.media') }}</CardTitle>
                        <a :href="mediaIndex().url" class="text-xs text-muted-foreground hover:text-foreground">
                            {{ t('admin.dashboard.viewAll') }}
                        </a>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <EmptyState
                            v-if="props.recentMedia.length === 0"
                            :title="t('admin.dashboard.recent.empty')"
                        />
                        <div v-else class="grid grid-cols-2 gap-3 text-xs sm:grid-cols-3">
                            <div v-for="item in props.recentMedia" :key="item.id" class="space-y-2">
                                <div class="relative overflow-hidden rounded-lg border">
                                    <img :src="item.url" :alt="item.original_name" class="h-20 w-full object-cover" />
                                </div>
                                <div class="space-y-1">
                                    <div class="line-clamp-2 font-medium">{{ item.original_name }}</div>
                                    <div class="flex items-center justify-between text-muted-foreground">
                                        <span>{{ t('admin.dashboard.labels.used') }}: {{ item.usage_count }}</span>
                                        <span class="inline-flex items-center gap-1">
                                            <HistoryIcon class="size-3" />
                                            {{ item.created_at ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
