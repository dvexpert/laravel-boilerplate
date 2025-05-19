<script setup lang="ts">
import {
  Button,
} from '@/components/ui/button'

import {
  Pagination,
  PaginationEllipsis,
  // PaginationFirst,
  // PaginationLast,
  // PaginationList,
  // PaginationListItem,
  PaginationNext,
  // PaginationPrev,
} from '@/components/ui/pagination'
import { Paginated } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-vue-next';
import { PaginationList, PaginationListItem } from 'reka-ui';
import { cn } from '@/lib/utils'
import { buttonVariants } from '@/components/ui/button'
import { ref, watch } from 'vue';

const props = defineProps<{data: Paginated}>()

const currentPage = ref(props.data.current_page)
watch(
  () => props.data.current_page,
  (newPage) => {
    currentPage.value = newPage
  },
  { immediate: true }
)

</script>

<template>
  <Pagination v-slot="{ page }" v-model:page="currentPage" :items-per-page="props.data.per_page" :total="props.data.total" :sibling-count="0" show-edges :default-page="props.data.current_page" class="py-4">
    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
      <!-- <PaginationFirst preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: 1 })" /> -->
        <Button preserve-scroll prefetch :as="props.data.current_page !== 1 ? Link : undefined" :href="props.data.first_page_url" :class="cn(buttonVariants({ variant: 'outline', size:'default' }), 'gap-1 px-2.5 sm:pr-2.5')" variant="ghost" :disabled="props.data.current_page === 1">
          <ChevronsLeft class="size-4" />
          <span class="sr-only">First</span>
        </Button>
        <Button preserve-scroll prefetch :as="props.data.prev_page_url ? Link : undefined" :href="props.data.prev_page_url ?? '#'" :class="cn(buttonVariants({ variant: 'outline', size:'default' }), 'gap-1 px-2.5 sm:pr-2.5')" variant="ghost" :disabled="!props.data.prev_page_url">
          <ChevronLeft class="size-4" />
          <span class="sr-only">Prev</span>
        </Button>

      <template v-for="(item, index) in items">
        <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
          <Button preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: item.value })" class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'">
            {{ item.value }}
          </Button>
        </PaginationListItem>
        <PaginationEllipsis v-else :key="item.type" :index="index" />
      </template>

      <!-- <PaginationNext /> -->
      <!-- <PaginationLast preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: props.data.last_page })" /> -->
       <Button preserve-scroll prefetch :as="props.data.current_page !== props.data.last_page ? Link : undefined" :href="props.data.last_page_url" :class="cn(buttonVariants({ variant: 'outline', size:'default' }), 'gap-1 px-2.5 sm:pr-2.5')" variant="ghost" :disabled="props.data.current_page === props.data.last_page">
         <span class="sr-only">Last</span>
         <ChevronsRight class="size-4" />
        </Button>
        <Button preserve-scroll prefetch :as="props.data.next_page_url ? Link : undefined" :href="props.data.next_page_url ?? '#'" :class="cn(buttonVariants({ variant: 'outline', size:'default' }), 'gap-1 px-2.5 sm:pr-2.5')" variant="ghost" :disabled="!props.data.next_page_url">
          <ChevronRight class="size-4" />
          <span class="sr-only">Next</span>
        </Button>
    </PaginationList>
  </Pagination>
</template>