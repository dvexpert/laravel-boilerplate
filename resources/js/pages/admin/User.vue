<script setup lang="ts">
import UserListSkeltonLoader from '@/components/ui/admin/user/UserListSkeltonLoader.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { PaginationWrapper } from '@/components/ui/pagination';
import { UserStatusEnum } from '@/enums/UserStatusEnum';
import AdminLayout from '@/layouts/admin/Layout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Paginated, SharedData, User } from '@/types';
import { Deferred, Head, usePage } from '@inertiajs/vue3';
import { FilePen, Save, UserPlus, Users } from 'lucide-vue-next';

interface Props extends SharedData {
    users: Paginated<User[]>;
}
const page = usePage<Props>();
</script>

<template>
    <AppLayout>
        <Head title="User Management" />

        <AdminLayout>
            <div class="split-container min-h-[70vh]">
                <div class="split-child split-left">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4 text-lg">
                        <h2>Users</h2>
                        <Button varian="primary">
                            <UserPlus class="size-4" />
                        </Button>
                    </div>

                    <div class="flex flex-col">
                        <div class="p-2">
                            <Input placeholder="Search users..." />
                        </div>

                        <Deferred data="users">
                            <template #fallback>
                                <UserListSkeltonLoader />
                            </template>
                            <div
                                v-for="(user, index) in page.props?.users?.data"
                                :key="user.id"
                                class="flex items-center justify-between p-4 hover:bg-gray-50"
                                :class="{
                                    'border-b border-gray-200': index < page.props?.users?.data.length - 1,
                                }"
                            >
                                <div class="flex flex-col">
                                    <p class="font-medium text-gray-800">{{ user.name }}</p>
                                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                                    <div class="mt-1 flex items-center">
                                        <span
                                            class="rounded px-2 py-1 text-xs capitalize"
                                            :class="{
                                                'bg-green-100 text-green-800': user.status === UserStatusEnum.ACTIVE,
                                                'bg-gray-100 text-gray-800': user.status === UserStatusEnum.INACTIVE,
                                                'bg-yellow-100 text-yellow-800': user.status === UserStatusEnum.SUSPENDED,
                                            }"
                                            >{{ user.status }}</span
                                        >
                                        <span class="ml-2 text-xs text-gray-500">{{ user.roles[0].name_label }}</span>
                                    </div>
                                </div>
                                <div>
                                    <FilePen class="size-4" />
                                </div>
                            </div>

                            <PaginationWrapper :data="page.props.users" />
                        </Deferred>
                    </div>
                </div>
                <div class="split-child split-right">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4 text-lg">
                        <h2>Users Details</h2>
                        <Button class="hidden" varian="primary" disabled title="Coming Soon...">
                            <Save class="size-4" />
                            Save
                        </Button>
                    </div>

                    <div class="p-4">
                        <div class="flex h-64 flex-col items-center justify-center text-gray-500">
                            <Users class="size-12" />
                            <p class="text-lg">Select a user to view or edit details</p>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
