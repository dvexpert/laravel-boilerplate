<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select } from '@/components/ui/select';
import { useUserCan } from '@/composables/useUserCan';
import { PermissionEnum } from '@/enums/PermissionEnum';
import { RoleEnum } from '@/enums/RoleEnum';
import { UserStatusEnum } from '@/enums/UserStatusEnum';
import { SharedData, User } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { Eye, EyeOff, FileClock, Save, Trash, X } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

interface Props {
    user?: User;
}

const { can } = useUserCan();
const page = usePage<SharedData>();
const props = defineProps<Props>();

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: undefined,
    password_confirmation: undefined,
    role: [] as string[],
    status: String(UserStatusEnum.ACTIVE),
});

const roles = Object.values(RoleEnum).map((role) => ({
    value: role,
    label: role.toTitleCase(),
}));
const statuses = Object.values(UserStatusEnum).map((statuses) => ({
    value: statuses,
    label: statuses.toTitleCase(),
}));

const showPassword = ref(false);

onMounted(() => {
    if (props.user) {
        form.clearErrors();
        setUser(props.user);
    }
});

watch(
    () => props.user,
    () => {
        if (props.user) {
            form.clearErrors();
            setUser(props.user);
        } else {
            form.reset();
        }
    },
);

function setUser(user: User) {
    form.first_name = user.first_name;
    form.last_name = user.last_name;
    form.email = user.email;
    form.password = undefined;
    form.password_confirmation = undefined;
    form.role = user.roles.map(role => role.name);
    form.status = String(user.status);
}

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    const method = props.user ? 'put' : 'post';
    const url = props.user ? route('admin.user.update', props.user.id) : route('admin.user.store');

    form.submit(method, url, {
        onSuccess: () => {
            close();
        },
    });
};
const emit = defineEmits(['close', 'toggle-audit-logs']);
const close = () => {
    form.reset();
    emit('close');
};
const deleteUser = () => {
    if (props.user && page.props.auth.user.id === props.user.id) {
        return;
    }

    if (confirm('Are you sure you want to delete this user?')) {
        form.delete(route('admin.user.destroy', props.user?.id), {
            onSuccess: () => {
                close();
            },
        });
    }
};

const showAuditLogs = ref(false);
</script>

<template>
    <div>
        <form @submit.prevent="submit">
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <Label for="first_name">First Name</Label>
                    <Input
                        id="first_name"
                        v-model="form.first_name"
                        class="mt-1 block w-full"
                        :required="false"
                        autocomplete="off"
                        placeholder="First Name"
                    />
                    <InputError :message="form.errors.first_name" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="last_name">Last Name</Label>
                    <Input
                        id="last_name"
                        v-model="form.last_name"
                        class="mt-1 block w-full"
                        :required="false"
                        autocomplete="off"
                        placeholder="Last Name"
                    />
                    <InputError :message="form.errors.last_name" />
                </div>

                <div class="col-span-full flex flex-col gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        :required="false"
                        autocomplete="off"
                        placeholder="Email address"
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="password">Password</Label>
                    <div class="relative mt-1 w-full">
                        <Input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="block w-full pr-10"
                            :required="false"
                            autocomplete="off"
                            :placeholder="`Password ${props.user ? '(Optional)' : ''}`"
                        />
                        <span class="absolute inset-y-0 end-0 flex items-center justify-center">
                            <Button type="button" variant="ghost" @click.prevent="togglePassword">
                                <Eye v-if="showPassword" class="size-4" />
                                <EyeOff v-else class="size-4" />
                            </Button>
                        </span>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="password">Confirm Password</Label>
                    <div class="relative mt-1 w-full">
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            :type="showPassword ? 'text' : 'password'"
                            class="block w-full pr-10"
                            :required="false"
                            autocomplete="off"
                            :placeholder="`Confirm Password ${props.user ? '(Optional)' : ''}`"
                        />
                        <span class="absolute inset-y-0 end-0 flex items-center justify-center">
                            <Button type="button" variant="ghost" @click.prevent="togglePassword">
                                <Eye v-if="showPassword" class="size-4" />
                                <EyeOff v-else class="size-4" />
                            </Button>
                        </span>
                    </div>
                    <InputError :message="form.errors.password_confirmation" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="role">Role</Label>
                    <Select v-model="form.role" :items="roles" placeholder="Role" class="w-full" multiple></Select>
                    <InputError :message="form.errors.role" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="status">Status</Label>
                    <Select v-model="form.status" :items="statuses" placeholder="Status" class=""></Select>
                    <InputError :message="form.errors.status" />
                </div>

                <div class="flex items-center gap-4">
                    <Teleport to="#user-details-action-container">
                        <div class="flex items-center gap-2">
                            <Button variant="secondary" size="sm" class="mr-6" :disabled="form.processing" @click="$emit('toggle-audit-logs')">
                                <FileClock class="size-4" />
                                Audit Logs
                            </Button>
                            <Button
                                v-if="user && page.props.auth.user.id !== user.id && can(PermissionEnum.USER_DELETE)"
                                variant="destructive"
                                size="sm"
                                :disabled="form.processing"
                                @click="deleteUser"
                            >
                                <Trash class="size-4" />
                                Delete
                            </Button>
                            <Button variant="secondary" size="sm" :disabled="form.processing" @click="close">
                                <X class="size-4" />
                                Cancel
                            </Button>
                            <Button
                                v-if="can(PermissionEnum.USER_CREATE) || can(PermissionEnum.USER_UPDATE)"
                                :disabled="form.processing"
                                size="sm"
                                @click="submit"
                            >
                                <Save class="size-4" />
                                {{ user ? 'Update' : 'Create' }}
                            </Button>
                        </div>
                    </Teleport>
                    <!-- This is a hidden button to submit the form on enter -->
                    <button type="submit" class="invisible hidden" aria-label="Submit">submit</button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="form.recentlySuccessful" class="text-sm text-green-600">Saved.</p>
                    </Transition>
                </div>
            </div>
        </form>
    </div>
</template>
