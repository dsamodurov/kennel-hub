<script setup lang="ts">
const props = defineProps<{
    message: string;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
}>();

function handleClick(event: MouseEvent) {
    if (props.disabled) return;
    event.preventDefault();
    if (confirm(props.message)) {
        emit('confirm');
    }
}
</script>

<template>
    <span class="inline-flex" @click="handleClick">
        <slot />
    </span>
</template>
