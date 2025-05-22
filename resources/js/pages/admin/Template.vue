<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue'
import AdminLayout from '@/layouts/admin/Layout.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
    <AppLayout>
        <Head title="Manage Templates" />

        <AdminLayout>
            <div class="space-y-6">
                <HeadingSmall title="Manage Templates" class="bg-transparent" description="Coming Soon..." />
            </div>
        </AdminLayout>
    </AppLayout>
</template>
