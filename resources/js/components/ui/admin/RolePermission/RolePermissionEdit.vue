<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { GroupedPermission, UserRole } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { Save, X } from 'lucide-vue-next';
import { onMounted, watch } from 'vue';
import { toast } from 'vue3-toastify';

const props = defineProps<{
    role: UserRole;
    permissions: GroupedPermission;
}>();

const form = useForm('RolePermissionForm', {
    permissions: props.role.permissions.map((permission) => permission.id),
});

onMounted(() => {
    if (props.role) {
        if (form.hasErrors) form.clearErrors();
        initRolePermissionEdit(props.role);
    }
});

watch(
    () => props.role,
    () => {
        if (props.role) {
            form.clearErrors();
            initRolePermissionEdit(props.role);
        } else {
            form.reset();
        }
    },
);

function initRolePermissionEdit(role: UserRole) {
    form.reset();
    form.defaults(
        'permissions',
        role.permissions.map((permission) => permission.id),
    );
}

const submit = () => {
    if (!props.role) {
        toast('Invalid Role selected! Please try again.', {
            type: 'error',
            autoClose: 2000,
            position: 'top-right',
        });
        return;
    }

    const url = route('admin.role-permission.update', props.role.id);

    form.submit('put', url, {
        onSuccess: () => {
            close();
        },
    });
};

const emit = defineEmits(['close']);
const close = () => {
    form.reset();
    emit('close');
};
</script>

<template>
    <div>
        <form @submit.prevent="submit">
            <div class="">
                <div v-for="(group, groupName) in props.permissions" :key="groupName" class="flex flex-col">
                    <h3 class="py-3 text-lg font-bold capitalize">{{ groupName }}</h3>
                    <div class="flex flex-col gap-4 pl-4">
                        <div v-for="permission in group" :key="permission.id" class="flex items-center gap-2">
                            <Switch
                                :id="permission.name"
                                :model-value="form.permissions.includes(permission.id)"
                                size="xl"
                                @update:model-value="
                                    (checked) => {
                                        checked
                                            ? form.permissions.push(permission.id)
                                            : form.permissions.splice(form.permissions.indexOf(permission.id), 1);
                                    }
                                "
                            >
                                <span class="sr-only">Toggle {{ permission.name_label }}</span>
                            </Switch>
                            <Label :for="permission.name">{{ permission.name_label }}</Label>
                        </div>
                    </div>
                </div>

                <InputError class="py-3" :message="Object.values(form.errors)[0]" />

                <div class="flex items-center gap-4">
                    <Teleport to="#user-details-action-container">
                        <div class="flex items-center gap-2">
                            <Button variant="secondary" size="sm" :disabled="form.processing" @click="close">
                                <X class="size-4" />
                                Cancel
                            </Button>
                            <Button :disabled="form.processing || !form.isDirty" size="sm" @click="submit">
                                <Save class="size-4" />
                                {{ 'Update' }}
                            </Button>
                        </div>
                    </Teleport>
                    <!-- This is a hidden button to submit the form on enter -->
                    <button type="submit" class="invisible hidden" aria-label="Submit">submit</button>
                </div>
            </div>
        </form>
    </div>
</template>
