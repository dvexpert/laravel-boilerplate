<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { useSidebar } from '@/components/ui/sidebar';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { FilePen, Settings, Users } from 'lucide-vue-next';

const navItems: NavItem[] = [
    {
        title: 'Manage Templates',
        href: '/admin/template',
        icon: FilePen,
    },
    {
        title: 'User Management',
        href: '/admin/user',
        icon: Users,
    },
    {
        title: 'System Configuration',
        href: '/admin/system-configuration',
        icon: Settings,
    },
];

const page = usePage<SharedData>();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
const { isMobile } = useSidebar();
</script>

<template>
    <div class="p-6">
        <Heading title="Admin Panel" description="Manage system settings, users, and templates" class="!mb-6 text-2xl [&_h2]:text-3xl" />

        <div class="">
            <aside class="w-full">
                <nav class="flex space-y-1 space-x-0">
                    <Button
                        v-for="item in navItems"
                        :key="item.href"
                        variant="ghost"
                        :class="`h-auto gap-2 rounded-none border-indigo-600 px-4 py-4 text-xl md:px-6 md:py-4 ${currentPath === item.href ? 'border-b-2 text-indigo-600' : ''}`"
                        as-child
                    >
                        <Link :href="item.href" prefetch>
                            <span v-if="item.icon">
                                <component :is="item.icon" class="size-5" />
                            </span>
                            <span v-show="!isMobile || currentPath === item.href" class="text-lg md:text-[inherit]">
                                {{ item.title }}
                            </span>
                        </Link>
                    </Button>
                </nav>
            </aside>

            <div class="mt-6 w-full">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
