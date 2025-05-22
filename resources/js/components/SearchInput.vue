<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { X } from 'lucide-vue-next'

const searchField = defineModel('searchField', {
    type: String,
})

const props = defineProps({
    clearable: {
        type: Boolean,
        default: true,
    },
})
</script>

<template>
    <div class="relative">
        <Input v-model="searchField" placeholder="Search users..." />
        <span v-if="props.clearable && String(searchField ?? '').length > 0" class="absolute inset-y-0 end-0 flex items-center justify-center">
            <Button type="button" variant="ghost" @click.prevent="searchField = ''">
                <X class="size-4" />
            </Button>
        </span>
    </div>
</template>
