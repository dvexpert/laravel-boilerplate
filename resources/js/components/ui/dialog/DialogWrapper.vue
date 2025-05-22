<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import { type DialogRootProps, useForwardProps } from 'reka-ui'
import { computed, HTMLAttributes } from 'vue'

const props = withDefaults(
    defineProps<
        DialogRootProps & {
            title?: string
            description?: string
            contentClass?: HTMLAttributes['class']
            withTrigger?: boolean
            withFooterClose?: boolean
            withFooterSave?: boolean
        }
    >(),
    {
        withTrigger: false,
        withFooterClose: false,
        withFooterSave: false,
    },
)

const delegatedProps = computed(() => {
    const { /* class: _, */ ...delegated } = props

    return delegated
})
const forwardedProps = useForwardProps(delegatedProps)

const emits = defineEmits(['close'])
</script>

<template>
    <Dialog v-bind="forwardedProps">
        <DialogTrigger v-if="withTrigger" as-child>
            <slot name="trigger">
                <Button variant="outline">Open Dialog</Button>
            </slot>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[75vw]" :class="contentClass" @close="() => emits('close')">
            <DialogHeader>
                <DialogTitle>{{ props.title }}</DialogTitle>
                <DialogDescription v-if="props.description">{{ props.description }}</DialogDescription>
            </DialogHeader>

            <slot name="content">Dialog Content </slot>

            <DialogFooter>
                <slot name="footer">
                    <slot v-if="withFooterClose" name="footer-close">
                        <DialogClose as-child @click="() => emits('close')">
                            <slot name="footer-close-child">
                                <Button variant="ghost" type="button" @click="() => emits('close')">Close</Button>
                            </slot>
                        </DialogClose>
                    </slot>
                    <slot v-if="withFooterSave" name="footer-save">
                        <Button type="submit"> Save changes </Button>
                    </slot>
                </slot>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
