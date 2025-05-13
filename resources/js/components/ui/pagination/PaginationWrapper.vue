<script setup lang="ts">
import {
  Button,
} from '@/components/ui/button'

import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  // PaginationList,
  // PaginationListItem,
  PaginationNext,
  // PaginationPrev,
} from '@/components/ui/pagination'
import { Paginated } from '@/types';
import { Link } from '@inertiajs/vue3';
import { PaginationList, PaginationListItem } from 'reka-ui';

const props = defineProps<{data: Paginated}>()

</script>

<template>
  <Pagination v-slot="{ page }" :items-per-page="props.data.per_page" :total="props.data.total" :sibling-count="1" show-edges :default-page="props.data.current_page" class="py-4">
    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
      <PaginationFirst preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: 1 })" />

      <template v-for="(item, index) in items">
        <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
          <Button preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: item.value })" class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'">
            {{ item.value }}
          </Button>
        </PaginationListItem>
        <PaginationEllipsis v-else :key="item.type" :index="index" />
      </template>

      <!-- <PaginationNext /> -->
      <PaginationLast preserve-scroll prefetch :as="Link" :href="route('admin.user.index', { page: props.data.last_page })" />
    </PaginationList>
  </Pagination>
</template>