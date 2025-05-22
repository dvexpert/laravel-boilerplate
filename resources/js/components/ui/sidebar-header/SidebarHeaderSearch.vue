<script setup lang="ts">
import { Input } from '@/components/ui/input'
import { Search } from 'lucide-vue-next'
</script>

<template>
    <div class="relative flex items-center gap-2">
        <Input class="w-64 rounded border-0 bg-gray-100 py-5 pl-10 shadow-none" placeholder="Search..." />
        <span class="absolute top-0 bottom-0 left-3 flex items-center justify-center">
            <Search class="size-[18px] text-gray-500" />
        </span>
    </div>
</template>
