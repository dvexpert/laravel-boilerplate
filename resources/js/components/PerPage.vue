<script setup lang="ts">
import { Select } from '@/components/ui/select';

const itemsPerPage = defineModel({
    type: Number,
    default: 10,
});

const perPageOptions = [
    { value: 5, label: '5 Items' },
    { value: 10, label: '10 Items' },
    { value: 25, label: '25 Items' },
    { value: 50, label: '50 Items' },
    { value: 100, label: '100 Items' },
    { value: 250, label: '250 Items' },
];

const props = defineProps<{ label?: string }>();
</script>

<template>
    <div class="flex flex-col">
        <label v-if="props.label" for="perPage" class="text-sm font-medium text-gray-700">{{ props.label }}</label>
        <Select
            v-model="itemsPerPage"
            :items="perPageOptions"
            placeholder="Select items per page"
            class="rounded-md border border-gray-300 px-3 py-2 focus:ring focus:ring-blue-500 focus:outline-none"
        >
        </Select>
    </div>
</template>
