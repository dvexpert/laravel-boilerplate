<script setup lang="ts">
// This Component is to render the generic Audit Logs table with search filter etc.
import { getColumns } from '@/components/ui/audit-logs';
import DataTable from '@/components/ui/DataTable.vue';
import DialogWrapper from '@/components/ui/dialog/DialogWrapper.vue';
import PaginationWrapper from '@/components/ui/pagination/PaginationWrapper.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Paginated, User } from '@/types';
import axios, { AxiosResponse } from 'axios';
import { onMounted, ref } from 'vue';
import { AuditLog } from './type';

const props = defineProps<{ user: User }>();
const data = ref<Paginated<AuditLog>>();
const loading = ref(false);
const viewData = ref<{ audit: AuditLog; uniqueKeys: string[] }>();

async function getData(page: number | string = 1) {
    loading.value = true;
    axios
        .get(
            route('admin.user.audits', {
                user: props.user.id,
                user_audits: page,
            }),
            {},
        )
        .catch((error) => {
            //
            loading.value = false;
        })
        .then((response: void | AxiosResponse<{ audits: Paginated<AuditLog> }>) => {
            if (response && response.status === 200 && response.data.audits) {
                data.value = response.data.audits;
            }
            loading.value = false;
        });
}

onMounted(async () => {
    await getData();
});

const viewHandler = (audit: AuditLog) => {
    viewData.value = {
        audit: audit,
        uniqueKeys: Array.from(new Set([...Object.keys(audit.old_values || {}), ...Object.keys(audit.new_values || {})])),
    };
};
</script>

<template>
    <div>
        <div class="relative">
            <div v-if="!data || loading" class="absolute inset-0 z-10 flex items-center justify-center bg-gray-50/50 p-8">
                <div class="border-primary h-8 w-8 animate-spin rounded-full border-4 border-t-transparent"></div>
            </div>

            <DataTable v-if="data && data.data" :columns="getColumns({ viewHandler })" :data="data.data" />

            <PaginationWrapper
                v-if="data?.data && data?.data.length > 0"
                :data="data"
                :with-link-navigation="false"
                @page-change="(page) => getData(page)"
            />

            <DialogWrapper
                :open="Boolean(viewData)"
                :title="`Audit Logs (${viewData?.audit.event})`"
                :with-footer-close="true"
                content-class="sm:max-w-[50vw]"
                @close="viewData = undefined"
            >
                <template #content>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Column</TableHead>
                                <TableHead>Original</TableHead>
                                <TableHead>Updated</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody v-if="viewData">
                            <TableRow v-for="key in viewData.uniqueKeys" :key="key">
                                <TableCell>{{ key }}</TableCell>
                                <TableCell>{{ viewData.audit.old_values?.[key] ?? '-' }}</TableCell>
                                <TableCell>{{ viewData.audit.new_values?.[key] ?? '-' }}</TableCell>
                            </TableRow>
                        </TableBody>
                        <TableBody v-else>
                            <TableRow>
                                <TableCell colspan="3">No changes recorded.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </template>

                <template #footer-close></template>
            </DialogWrapper>
        </div>
    </div>
</template>
