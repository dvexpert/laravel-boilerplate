<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';


</script>

<template>
    <div class="relative flex items-center gap-2">
        <Input class="pl-10 py-5 w-64 bg-gray-100 rounded border-0 shadow-none" placeholder="Search..." />
        <span class="absolute top-0 bottom-0 left-3 flex justify-center items-center">
            <Search class=" text-gray-500 size-[18px] " />
        </span>
    </div>
</template>