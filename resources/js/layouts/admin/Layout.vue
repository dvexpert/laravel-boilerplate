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
        <Heading
            title="Admin Panel"
            description="Manage system settings, users, and templates"
            class="!mb-6 [&_h2]:text-2xl [&_p]:text-base [&_p]:text-gray-600"
        />

        <div class="">
            <aside class="w-full">
                <nav class="flex space-y-1 space-x-0 border-b">
                    <Button
                        v-for="item in navItems"
                        :key="item.href"
                        variant="ghost"
                        :data-state="`${currentPath === item.href ? 'active' : ''}`"
                        :class="`md h-auto gap-2 rounded-none px-4 py-4 text-sm md:px-6 md:py-4 ${currentPath === item.href ? 'border-b-2 border-b-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'}`"
                        as-child
                    >
                        <Link :href="item.href" prefetch class="mb-0">
                            <span v-if="item.icon">
                                <component :is="item.icon" class="size-5" />
                            </span>
                            <span v-show="!isMobile || currentPath === item.href" class="text-sm md:text-[inherit]">
                                {{ item.title }}
                            </span>
                        </Link>
                    </Button>
                </nav>
            </aside>

            <div id="admin-layout" class="mt-6 w-full">
                <section>
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference 'tailwindcss';

/* @ref https://vuejs.org/api/sfc-css-features#deep-selectors */
#admin-layout :deep(.split-container) {
    @apply grid h-full w-full grid-cols-6 gap-x-6;
}
#admin-layout :deep(.split-container > .split-child) {
    @apply rounded-lg border border-gray-200 bg-white;
}
#admin-layout :deep(.split-container > .split-child.split-left) {
    @apply col-span-2;
}
#admin-layout :deep(.split-container > .split-child.split-right) {
    @apply col-span-4;
}
</style>
