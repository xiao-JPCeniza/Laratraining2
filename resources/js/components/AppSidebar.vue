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
import { dashboard } from '@/routes';

import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Route } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { MapPin } from 'lucide-vue-next';
import { Factory } from 'lucide-vue-next';
import { Voicemail } from 'lucide-vue-next';
import { ChartBarStacked } from 'lucide-vue-next';
import { computed } from 'vue';


const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
        roles: ['super_admin', 'inventory_user', 'inventory_manager'],
    },
     {
        title: 'Location',
        href: '/locations',
        icon: MapPin,
        roles: ['super_admin', 'inventory_manager'],
    },
     {
        title: 'Manufacturer',
        href: '/manufacturers',
        icon: Factory,
        roles: ['super_admin','inventory_manager'],
    },
     {
        title: 'Asset',
        href: '/assets',
        icon: Voicemail,
        roles: ['super_admin', 'inventory_user'],
    },
         {
        title: 'Category',
        href: '/categories',
        icon: ChartBarStacked,
        roles: ['super_admin'],
    },

];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

const page = usePage();
const userRole = computed(() => page.props.auth.user.role|| null);


const filterNavItems = computed(() => {
    return mainNavItems.filter((item) => {
        if (!item.roles) return true;
        
        return item.roles.includes(userRole.value);
    });
});

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
            <NavMain :items="filterNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
