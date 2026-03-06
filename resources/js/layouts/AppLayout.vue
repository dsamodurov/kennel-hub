<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, ActionsItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    actions?: ActionsItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    actions: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" :actions="actions">
        <slot />
    </AppLayout>
</template>
