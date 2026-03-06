<script setup lang="ts">
import { Button } from '@/components/ui/button';

const props = defineProps<{
    label: string;
    currentPage: number;
    lastPage: number;
    prevLabel: string;
    nextLabel: string;
}>();

const emit = defineEmits<{
    (e: 'prev'): void;
    (e: 'next'): void;
}>();
</script>

<template>
    <div class="flex items-center justify-between text-xs text-muted-foreground">
        <span>{{ props.label }}</span>
        <div class="flex items-center gap-2">
            <Button
                variant="secondary"
                size="sm"
                :disabled="props.currentPage <= 1"
                @click="emit('prev')"
            >
                {{ props.prevLabel }}
            </Button>
            <Button
                variant="secondary"
                size="sm"
                :disabled="props.currentPage >= props.lastPage"
                @click="emit('next')"
            >
                {{ props.nextLabel }}
            </Button>
        </div>
    </div>
</template>
