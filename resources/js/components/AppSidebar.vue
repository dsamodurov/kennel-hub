<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes/admin';
import { index } from '@/routes/admin/news';
import { index as mediaIndex } from '@/routes/admin/media';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { FileText, Folder, Images, LayoutGrid, Newspaper } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from '@/i18n';
import AppLogo from './AppLogo.vue';

const { t } = useI18n();

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: t('common.dashboard'),
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: t('common.news'),
        href: index(),
        icon: Newspaper,
    },
    {
        title: t('admin.pages.title'),
        href: '/admin/pages',
        icon: FileText,
    },
    {
        title: t('common.media'),
        href: mediaIndex(),
        icon: Images,
    },
]);

const footerNavItems = computed<NavItem[]>(() => [
    {
        title: t('nav.githubRepo'),
        href: 'https://github.com/dsamodurov/kennel-hub',
        icon: Folder,
    },
    // {
    //     title: t('common.documentation'),
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
]);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
