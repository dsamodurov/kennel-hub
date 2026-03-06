<script setup lang="ts">
// import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { PawPrintIcon } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
const appName = usePage().props.appName as string;
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary"
    >
<!--        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />-->
        <PawPrintIcon class="size-5 text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >{{ appName }}</span
        >
    </div>
</template>
