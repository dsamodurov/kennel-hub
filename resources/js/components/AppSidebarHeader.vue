<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItemType, ActionsItemType } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
        actions?: ActionsItemType[];
    }>(),
    {
        breadcrumbs: () => [],
        actions: () => [],
    },
);
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div class="flex items-center gap-2">
            <LanguageSwitcher />
            <slot name="actions">
                <template v-for="(a, i) in actions" :key="i">
                    <Button
                        @click="a.onClick?.()"
                    >
                        <component :is="a.icon" />
                        <span>{{ a.label }}</span>
                    </Button>
                </template>
            </slot>
        </div>
    </header>
</template>
