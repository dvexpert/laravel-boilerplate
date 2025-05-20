<script setup lang="ts">
// import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { /* SidebarTrigger, */ useSidebar } from '@/components/ui/sidebar';
import { SidebarHeaderSearch, SidebarNotification } from '@/components/ui/sidebar-header';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import UserInfo from '@/components/UserInfo.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItemType, User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { LogOut } from 'lucide-vue-next';
import { computed } from 'vue';

const { isMobile } = useSidebar();

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage<{ auth: { user: User } }>();
const auth = computed(() => page.props.auth);

const handleLogout = () => {
    router.flushAll();
};
</script>

<template>
    <header
        class="border-sidebar-border/70 h-16/ group-has-data-[collapsible=icon]/sidebar-wrapper:h-12/ sticky top-0 z-[99] flex shrink-0 items-center gap-2 border-b p-4 px-6 transition-[width,height] ease-linear md:px-4"
    >
        <div class="flex items-center gap-2">
            <!-- <SidebarTrigger class="-ml-1" /> -->
            <!-- <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template> -->

            <SidebarHeaderSearch />
        </div>

        <div class="ml-auto flex items-center gap-2">
            <SidebarNotification />

            <div v-if="isMobile">
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <Button variant="ghost" size="icon" class="relative size-10 w-auto p-1 focus-within:ring-2 focus-within:ring-transparent">
                            <Avatar class="size-8 overflow-hidden rounded-full">
                                <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                <AvatarFallback class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                                    {{ getInitials(auth.user?.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-56">
                        <UserMenuContent :user="auth.user" :only-profile="false" />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
            <div v-else class="hidden items-center space-x-2 sm:flex">
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <Button variant="ghost" size="icon" class="relative size-10 w-auto p-1 focus-within:ring-2 focus-within:ring-transparent">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <UserInfo :user="auth.user" :show-email="true" />
                            </div>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-56" :side-offset="15">
                        <UserMenuContent :user="auth.user" :only-profile="true" />
                    </DropdownMenuContent>
                </DropdownMenu>

                <TooltipProvider :delay-duration="0">
                    <Tooltip>
                        <TooltipTrigger>
                            <Button :as="Link" variant="ghost" class="block" method="post" :href="route('logout')" @click="handleLogout">
                                <LogOut class="h-4 w-4" />
                            </Button>
                        </TooltipTrigger>
                        <TooltipContent>
                            <p>Logout...</p>
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            </div>
        </div>
    </header>
</template>
