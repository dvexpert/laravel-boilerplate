<script setup lang="ts">
import {
  SelectRootWrapper,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { twMerge } from 'tailwind-merge';

interface Props {
    placeholder: string;
    items: {
        value: string|number;
        label: string;
    }[];
    groupLabel?: string;
    class?: string;
}
const props = defineProps<Props>();
const valueModel = defineModel<string|number|string[]>();
</script>

<template>
  <SelectRootWrapper v-model="valueModel">
    <SelectTrigger v-if="props.placeholder" :class="twMerge('w-full', props.class)">
      <SelectValue :placeholder="props.placeholder" />
    </SelectTrigger>
    <SelectContent>
      <SelectGroup>
        <SelectLabel v-if="props.groupLabel">{{ props.groupLabel }}</SelectLabel>

        <SelectItem v-for="item in props.items" :key="item.value" :value="item.value">
          {{ item.label }}
        </SelectItem>
      </SelectGroup>
    </SelectContent>
  </SelectRootWrapper>
</template>