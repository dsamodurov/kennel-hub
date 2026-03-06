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
    <div :class="cn('rounded-lg border bg-muted/30 p-4 text-sm text-muted-foreground', props.class)">
        <p class="font-medium text-foreground">{{ props.title }}</p>
        <p v-if="props.description" class="mt-1 text-xs text-muted-foreground">
            {{ props.description }}
        </p>
        <slot />
    </div>
</template>
