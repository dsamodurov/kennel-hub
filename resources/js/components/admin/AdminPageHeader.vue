<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    title: string;
    description?: string;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div :class="cn('space-y-1', props.class)">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold">{{ props.title }}</h1>
                <p v-if="props.description" class="text-sm text-muted-foreground">
                    {{ props.description }}
                </p>
            </div>
            <div v-if="$slots.actions" class="flex items-center gap-2">
                <slot name="actions" />
            </div>
        </div>
        <slot />
    </div>
</template>
