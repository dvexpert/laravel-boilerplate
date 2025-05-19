<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';
import { toast } from 'vue3-toastify';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
watch(
    () => usePage<SharedData>().props.flash,
    (flash) => {
        if (flash.message && flash.message.message) {
            toast(flash.message.message, {
                type: flash.message.success ? 'success' : 'error',
            });
        }
    },
    { deep: true },
);

onMounted(() => {
    const flash = usePage<SharedData>().props.flash;
    if (flash.message.message) {
        toast(flash.message.message, {
            type: flash.message.success ? 'success' : 'error',
        });
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
