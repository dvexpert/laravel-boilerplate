<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Bell } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Replace with actual logic to check for notifications count
// const hasNotifications = computed(() =>  true);
const hasNotifications = ref(true);

</script>

<template>
    <div>
        <Button class="relative" variant="ghost" @click="hasNotifications = !hasNotifications">
            <span class="relative">
                <span v-if="hasNotifications" class="absolute top-0 right-0 inline-flex h-2 w-2 translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"></span>
                <Bell class="size-5" />
            </span>
        </Button>
    </div>
</template>