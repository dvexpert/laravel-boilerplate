<script setup lang="ts">
import PerPage from '@/components/PerPage.vue';
import SearchInput from '@/components/SearchInput.vue';
import UserAddEdit from '@/components/ui/admin/user/UserAddEdit.vue';
import UserListSkeltonLoader from '@/components/ui/admin/user/UserListSkeltonLoader.vue';
import { Button } from '@/components/ui/button';
import { PaginationWrapper } from '@/components/ui/pagination';
import { UserStatusEnum } from '@/enums/UserStatusEnum';
import AdminLayout from '@/layouts/admin/Layout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Paginated, SharedData, User } from '@/types';
import { Deferred, Head, router, usePage } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { FilePen, UserPlus, Users } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

interface Props extends SharedData {
    users: Paginated<User[]>;
}
interface fetchUsersParams {
    search?: string;
    per_page?: number;
    page?: number;
}

const page = usePage<Props>();

const searchField = ref<string>('');
const perPage = ref<number>(page.props.users.per_page);
const action = ref<{ label?: 'create' | 'edit'; user?: User }>({
    label: undefined,
    user: undefined,
});

onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.has('search')) {
        searchField.value = params.get('search') ?? '';
    }

    watchDebounced([searchField, perPage], fetchUsers, { debounce: 300 });
});

const fetchUsers = () => {
    const params: fetchUsersParams = {};
    if (searchField.value !== '') params.search = searchField.value;
    if (perPage.value !== 5) {
        params.per_page = perPage.value;
        params.page = 1;
    }

    router.get(route('admin.user.index'), { ...params }, { preserveState: true, preserveScroll: true, only: ['users'] });
};

const editUser = (user: User) => {
    action.value.label = 'edit';
    action.value.user = user;
};
</script>

<template>
    <AppLayout>
        <Head title="User Management" />

        <AdminLayout>
            <div class="split-container min-h-[70vh]">
                <div class="split-child split-left">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4 text-lg">
                        <h2>Users</h2>
                        <Button varian="primary" @click="action = { label: 'create', user: undefined }">
                            <UserPlus class="size-4" />
                        </Button>
                    </div>

                    <div class="flex flex-col">
                        <div class="flex items-center justify-between gap-2 p-2">
                            <SearchInput class="w-full" />
                            <PerPage v-model="perPage" class="max-w-32 min-w-32" />
                        </div>

                        <Deferred data="users">
                            <template #fallback>
                                <UserListSkeltonLoader />
                            </template>
                            <div v-if="page.props?.users?.data.length === 0" class="flex h-64 flex-col items-center justify-center text-gray-500">
                                <Users class="size-12" />
                                <p class="text-lg">No users found</p>
                            </div>

                            <div
                                v-for="(user, index) in page.props?.users?.data"
                                :key="user.id"
                                class="flex cursor-pointer items-center justify-between p-4 hover:bg-gray-50"
                                :class="{
                                    'border-b border-gray-200': index < page.props?.users?.data.length - 1,
                                }"
                                role="button"
                                aria-label="Edit user"
                                @click="editUser(user)"
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

                            <PaginationWrapper v-if="page.props?.users?.data.length > 0" :data="page.props.users" />
                        </Deferred>
                    </div>
                </div>
                <div id="user-details" class="split-child split-right">
                    <div class="flex items-center justify-between border-b border-gray-200 p-4 text-lg">
                        <h2>Users Details</h2>
                        <div id="user-details-action-container">
                            <!--  -->
                        </div>
                        <!-- <Button class="" varian="primary" disabled title="Coming Soon...">
                            <Save class="size-4" />
                            Save
                        </Button> -->
                    </div>

                    <div class="p-4">
                        <div v-if="!action.label" class="flex h-64 flex-col items-center justify-center text-gray-500">
                            <Users class="size-12" />
                            <p class="text-lg">Select a user to view or edit details</p>
                        </div>

                        <UserAddEdit v-else :user="action.user" @close="action = { label: undefined, user: undefined }" />
                    </div>
                </div>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
