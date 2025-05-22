<script setup lang="ts">
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'
import { CircleHelp } from 'lucide-vue-next'
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger>
                <slot name="trigger">
                    <CircleHelp class="text-muted-foreground hover:text-primary h-4 w-4 cursor-pointer" />
                </slot>
            </TooltipTrigger>
            <TooltipContent>
                <slot name="content"></slot>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
