<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarTrigger,
    useSidebar,
} from '@/components/ui/sidebar';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Settings } from 'lucide-vue-next';
// import AppLogo from './AppLogo.vue';

const page = usePage<SharedData>();
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/',
        icon: LayoutGrid,
    },
    {
        title: 'Admin',
        href: '/admin/template',
        icon: Settings,
        isActive: page.url.startsWith('/admin'),
    },
];

const { open: isSidebarOpen } = useSidebar();
// const footerNavItems: NavItem[] = [
//     {
//         title: 'Github Repo',
//         href: 'https://github.com/laravel/vue-starter-kit',
//         icon: Folder, // import { Folder } from 'lucide-vue-next';
//     },
//     {
//         title: 'Documentation',
//         href: 'https://laravel.com/docs/starter-kits#vue',
//         icon: BookOpen, // import { BookOpen } from 'lucide-vue-next';
//     },
// ];
</script>

<template>
    <Sidebar collapsible="icon" variant="sidebar">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        :as="Link"
                        class="hover:bg-transparent hover:text-white focus:bg-transparent focus:text-white"
                        size="lg"
                        as-child
                    >
                        <div class="flex items-center justify-between">
                            <Transition>
                                <Link v-if="isSidebarOpen" :href="route('dashboard')" class="flex-1" prefetch>
                                    <div class="text-left text-xl font-bold">
                                        <span class="mb-0.5 truncate leading-none font-semibold">{{ page.props.app.name }}</span>
                                    </div>
                                </Link>
                            </Transition>
                            <SidebarTrigger class="!mr-auto !ml-auto" />
                        </div>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter> -->
    </Sidebar>
    <slot />
</template>
