<script setup lang="ts" generic="TData, TValue">
import HelpTooltip from '@/components/HelpTooltip.vue'
import PerPage from '@/components/PerPage.vue'
import { Input } from '@/components/ui/input'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { valueUpdater } from '@/lib/utils'
import type { ColumnDef, PaginationState, SortingState } from '@tanstack/vue-table'
import { FlexRender, getCoreRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table'
import { Lightbulb } from 'lucide-vue-next'
import { ref } from 'vue'

const props = withDefaults(
    defineProps<{
        columns: ColumnDef<TData, TValue>[]
        data: TData[]
        tableState: {
            sorting: SortingState
            search: string
            pageSize: number
        }
        withSearch?: boolean
        withPageSize?: boolean
    }>(),
    {
        withSearch: false,
        withPageSize: false,
    },
)

const sorting = ref<SortingState>([])
const globalSearch = ref<string>('')
const pageSize = ref<number>(5)
const emit = defineEmits<{
    (e: 'update:tableState', value: typeof props.tableState): void
}>()

function updateState<K extends keyof typeof props.tableState>(key: K, value: (typeof props.tableState)[K]) {
    emit('update:tableState', { ...props.tableState, [key]: value })
}

const table = useVueTable({
    get data() {
        return props.data
    },
    get columns() {
        return props.columns
    },
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    manualSorting: true,
    manualFiltering: true,
    getSortedRowModel: getSortedRowModel(),
    onSortingChange: (updaterOrValue) => {
        const value = valueUpdater(updaterOrValue, sorting)

        updateState('sorting', value)
    },
    onGlobalFilterChange: (updaterOrValue) => {
        const value = valueUpdater(updaterOrValue, globalSearch)

        if (value === '' || value.length > 1) {
            updateState('search', value)
        }
    },
    onPaginationChange: (updaterOrValue) => {
        const value = valueUpdater(updaterOrValue, pageSize, true) as unknown as PaginationState

        pageSize.value = value.pageSize
        updateState('pageSize', value.pageSize)
    },
    state: {
        get sorting() {
            return sorting.value
        },
        get globalFilter() {
            return globalSearch.value
        },
        get pagination() {
            return {
                pageIndex: pageSize.value - 1,
                pageSize: pageSize.value,
            }
        },
    },
})
</script>

<template>
    <div>
        <div class="flex items-center justify-between pb-4">
            <div v-if="props.withSearch" class="flex items-center gap-4">
                <Input
                    class="max-w-sm"
                    placeholder="Search..."
                    :model-value="table.getState().globalFilter ?? ''"
                    @update:model-value="table.setGlobalFilter"
                />
                <HelpTooltip>
                    <template #content>
                        <div class="flex items-center gap-1">
                            <span><Lightbulb class="size-4 text-yellow-200"></Lightbulb></span>
                            <span class="font-bold">Pro Tip:</span>
                            <span class="">Start your search with <code>#</code> to look up a specific audit by its ID.</span>
                        </div>
                    </template>
                </HelpTooltip>
            </div>
            <div v-if="props.withPageSize" class="flex items-center">
                <PerPage :model-value="pageSize" class="max-w-32 min-w-32" @update:model-value="(value) => table.setPageSize(value)" />
            </div>
        </div>
        <div class="rounded-md border">
            <Table manual>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
