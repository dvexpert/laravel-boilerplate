<script setup lang="ts">
import { Button } from '@/components/ui/button'

import { buttonVariants } from '@/components/ui/button'
import { Pagination, PaginationEllipsis } from '@/components/ui/pagination'
import { cn } from '@/lib/utils'
import { Paginated } from '@/types'
import { Link } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-vue-next'
import { PaginationList, PaginationListItem } from 'reka-ui'
import { ref, watch } from 'vue'

const props = withDefaults(
    defineProps<{
        data: Paginated
        withLinkNavigation?: boolean
    }>(),
    {
        withLinkNavigation: true,
    },
)

const currentPage = ref(props.data.current_page)

const emits = defineEmits<{ 'page-change': [value: number | string] }>()
watch(
    () => props.data.current_page,
    (newPage) => {
        currentPage.value = newPage
    },
    { immediate: true },
)
</script>

<template>
    <Pagination
        v-slot="{ page }"
        v-model:page="currentPage"
        :items-per-page="props.data.per_page"
        :total="props.data.total"
        :sibling-count="0"
        show-edges
        :default-page="props.data.current_page"
        class="py-4"
    >
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <!-- <PaginationFirst preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: 1 })" /> -->
            <Button
                preserve-scroll
                prefetch
                :as="props.withLinkNavigation && props.data.current_page !== 1 ? Link : undefined"
                :href="props.data.first_page_url"
                :class="cn(buttonVariants({ variant: 'outline', size: 'default' }), 'gap-1 px-2.5 sm:pr-2.5')"
                variant="ghost"
                :disabled="props.data.current_page === 1"
                @click.prevent="
                    () => {
                        !props.withLinkNavigation && emits('page-change', 1)
                    }
                "
            >
                <ChevronsLeft class="size-4" />
                <span class="sr-only">First</span>
            </Button>
            <Button
                preserve-scroll
                prefetch
                :as="props.withLinkNavigation && props.data.prev_page_url ? Link : undefined"
                :href="props.data.prev_page_url ?? '#'"
                :class="cn(buttonVariants({ variant: 'outline', size: 'default' }), 'gap-1 px-2.5 sm:pr-2.5')"
                variant="ghost"
                :disabled="!props.data.prev_page_url"
                @click.prevent="
                    () => {
                        if (props.data.prev_page_url && !props.withLinkNavigation) {
                            emits('page-change', props.data.per_page)
                        }
                    }
                "
            >
                <ChevronLeft class="size-4" />
                <span class="sr-only">Prev</span>
            </Button>

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button
                        preserve-scroll
                        prefetch
                        :as="props.withLinkNavigation ? Link : undefined"
                        :href="route('admin.user.index', { page: item.value })"
                        class="h-10 w-10 p-0"
                        :variant="item.value === page ? 'default' : 'outline'"
                        @click.prevent="
                            () => {
                                if (item.value !== page && !props.withLinkNavigation) {
                                    emits('page-change', item.value)
                                }
                            }
                        "
                    >
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <!-- <PaginationNext /> -->
            <!-- <PaginationLast preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: props.data.last_page })" /> -->
            <Button
                preserve-scroll
                prefetch
                :as="props.withLinkNavigation && props.data.current_page !== props.data.last_page ? Link : undefined"
                :href="props.data.last_page_url"
                :class="cn(buttonVariants({ variant: 'outline', size: 'default' }), 'gap-1 px-2.5 sm:pr-2.5')"
                variant="ghost"
                :disabled="props.data.current_page === props.data.last_page"
                @click.prevent="
                    () => {
                        if (props.data.last_page_url && !props.withLinkNavigation) {
                            emits('page-change', props.data.last_page)
                        }
                    }
                "
            >
                <span class="sr-only">Last</span>
                <ChevronsRight class="size-4" />
            </Button>
            <Button
                preserve-scroll
                prefetch
                :as="props.withLinkNavigation && props.data.next_page_url ? Link : undefined"
                :href="props.data.next_page_url ?? '#'"
                :class="cn(buttonVariants({ variant: 'outline', size: 'default' }), 'gap-1 px-2.5 sm:pr-2.5')"
                variant="ghost"
                :disabled="!props.data.next_page_url"
                @click.prevent="
                    () => {
                        if (props.data.next_page_url && !props.withLinkNavigation) {
                            emits('page-change', props.data.current_page + 1)
                        }
                    }
                "
            >
                <ChevronRight class="size-4" />
                <span class="sr-only">Next</span>
            </Button>
        </PaginationList>
    </Pagination>
</template>
