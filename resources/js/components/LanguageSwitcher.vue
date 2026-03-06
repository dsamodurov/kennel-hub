<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useI18n } from '@/i18n';
import { computed } from 'vue';

const { locale, setLocale, availableLocales } = useI18n();

const labels = computed(() => ({
    ru: 'RU',
    en: 'EN',
}));
</script>

<template>
    <div class="inline-flex items-center gap-1 rounded-md border px-1 py-1">
        <Button
            v-for="lang in availableLocales"
            :key="lang"
            size="sm"
            variant="ghost"
            class="h-7 px-2 text-xs"
            :class="locale === lang ? 'bg-muted' : ''"
            @click="setLocale(lang)"
        >
            {{ labels[lang] }}
        </Button>
    </div>
</template>
