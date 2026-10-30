<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, Users } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
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
import { index as usersIndex } from '@/routes/users';
import { index } from '@/routes/workspaces';
import type { NavItem } from '@/types';

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.type === 'admin');

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: 'Workspaces',
        href: index(),
        icon: LayoutGrid,
    },
    ...(isAdmin.value
        ? [{ title: 'Usuários', href: usersIndex(), icon: Users }]
        : []),
]);

    // const footerNavItems: NavItem[] = [
    //     {
    //         title: 'Repository',
    //         href: 'https://github.com/laravel/vue-starter-kit',
    //         icon: FolderGit2,
    //     },
    //     {
    //         title: 'Documentation',
    //         href: 'https://laravel.com/docs/starter-kits#vue',
    //         icon: BookOpen,
    //     },
    // ];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="index()">
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
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
