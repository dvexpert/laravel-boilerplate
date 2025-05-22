<script setup lang="ts">
// This Component is to render the generic Audit Logs table with search filter etc.
import { getColumns } from '@/components/ui/audit-logs'
import DataTable from '@/components/ui/DataTable.vue'
import DialogWrapper from '@/components/ui/dialog/DialogWrapper.vue'
import PaginationWrapper from '@/components/ui/pagination/PaginationWrapper.vue'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Paginated, User } from '@/types'
import { SortingState } from '@tanstack/vue-table'
import axios, { AxiosResponse } from 'axios'
import { onMounted, ref, watch } from 'vue'
import { AuditLog } from './type'

const props = defineProps<{ user: User }>()
const data = ref<Paginated<AuditLog>>()
const loading = ref(false)
const viewData = ref<{ audit: AuditLog; uniqueKeys: string[] }>()

interface TableState {
    sorting: SortingState
    search: string
    page: number
    pageSize: number
}
const tableState = ref<TableState>({
    sorting: [] as SortingState,
    search: '',
    page: 1,
    pageSize: 5,
})

watch(
    () => tableState.value,
    (newState) => {
        getData(newState)
    },
    {
        deep: true,
    },
)

async function getData(withTableState?: TableState) {
    const params = withTableState ?? tableState.value

    loading.value = true
    axios
        .get(
            route('admin.user.audits', {
                user: props.user.id,
                user_audits: params.page,
                search: params.search,
                per_page: params.pageSize,
                ...(params.sorting.length > 0 ? { sort: params.sorting[0].id, order: params.sorting[0].desc ? 'desc' : 'asc' } : {}),
            }),
            {},
        )
        .catch((error) => {
            //
            loading.value = false
        })
        .then((response: void | AxiosResponse<{ audits: Paginated<AuditLog> }>) => {
            if (response && response.status === 200 && response.data.audits) {
                data.value = response.data.audits
            }
            loading.value = false
        })
}

onMounted(async () => {
    await getData()
})

const viewHandler = (audit: AuditLog) => {
    viewData.value = {
        audit: audit,
        uniqueKeys: Array.from(new Set([...Object.keys(audit.old_values || {}), ...Object.keys(audit.new_values || {})])),
    }
}
</script>

<template>
    <div>
        <div class="relative">
            <div v-if="!data || loading" class="absolute inset-0 z-10 flex items-center justify-center bg-gray-50/50 p-8">
                <div class="border-primary h-8 w-8 animate-spin rounded-full border-4 border-t-transparent"></div>
            </div>

            <!-- @vue-generic {AuditLog, unknown} -->
            <DataTable
                v-if="data && data.data"
                v-model:table-state="tableState"
                :columns="getColumns({ viewHandler })"
                :data="data.data"
                :with-search="true"
                :with-page-size="true"
            />

            <PaginationWrapper
                v-if="data?.data && data?.data.length > 0"
                :data="data"
                :with-link-navigation="false"
                @page-change="
                    (page) => {
                        tableState.page = Number(page)
                    }
                "
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
