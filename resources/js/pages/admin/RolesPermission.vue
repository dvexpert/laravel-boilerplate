<script setup lang="ts">
import RolePermissionEdit from '@/components/ui/admin/RolePermission/RolePermissionEdit.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { GroupedPermission, Permission, SharedData, UserRole } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { FilePen, ShieldUser } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props extends SharedData {
    roles: UserRole[];
    permissions: Permission[];
}

const page = usePage<Props>();

const editRole = ref<UserRole | null>(null);

const groupedPermissions = computed(() => {
    const permissions = page.props.permissions;
    const grouped: GroupedPermission = {};

    permissions.forEach((permission) => {
        const parts = permission.name.split('.');
        if (parts.length > 1) {
            const group = parts[0];
            if (!grouped[group]) {
                grouped[group] = [];
            }
            grouped[group].push(permission);
        }
    });

    return grouped;
});

const handleEditRole = (role: UserRole | null) => {
    editRole.value = role ? role : null;
};
</script>

<template>
    <AppLayout>
        <Head title="Roles and Permission" />
        <AdminLayout>
            <div class="split-container min-h-[70vh]">
                <div class="split-child split-left">
                    <div class="flex min-h-[69px] items-center justify-between border-b border-gray-200 p-4 text-lg">
                        <h2>Roles</h2>
                    </div>

                    <div class="flex flex-col">
                        <div v-if="page.props?.roles.length === 0" class="flex h-64 flex-col items-center justify-center text-gray-500">
                            <ShieldUser class="size-12" />
                            <p class="text-lg">No roles found</p>
                        </div>

                        <div
                            v-for="(role, index) in page.props?.roles"
                            :key="role.id"
                            class="flex cursor-pointer items-center justify-between p-4 hover:bg-gray-50"
                            :class="{
                                'border-b border-gray-200': index < page.props?.roles?.length - 1,
                            }"
                            role="button"
                            aria-label="Edit user"
                            @click="() => handleEditRole(role)"
                        >
                            <div class="flex flex-col">
                                <p class="font-medium text-gray-800">{{ role.name_label }}</p>
                            </div>
                            <div>
                                <FilePen class="size-4" />
                            </div>
                        </div>
                    </div>
                </div>
                <div id="user-details" class="split-child split-right">
                    <div class="flex min-h-[69px] items-center justify-between border-b border-gray-200 p-4 text-lg">
                        <h2>
                            Role Permissions
                            <span v-if="editRole" class="text-sm text-gray-500">({{ editRole.name_label }})</span>
                        </h2>
                        <div id="user-details-action-container">
                            <!--  -->
                        </div>
                    </div>

                    <div class="p-4">
                        <div v-if="!editRole" class="flex h-64 flex-col items-center justify-center text-gray-500">
                            <ShieldUser class="size-12" />
                            <p class="text-lg">Select a role to view or edit permission</p>
                        </div>

                        <RolePermissionEdit
                            v-else
                            :key="editRole.id"
                            :role="editRole"
                            :permissions="groupedPermissions"
                            @close="() => handleEditRole(null)"
                        />
                    </div>
                </div>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
