<script setup lang="ts">
// This component is only to provide toggle button for Audit Logs in Admin Layout pages.
import { AuditLogs } from '@/components/ui/audit-logs';
import Button from '@/components/ui/button/Button.vue';
import { User } from '@/types';
import { FileClock } from 'lucide-vue-next';

const props = defineProps<{ user: User }>();
const emit = defineEmits(['toggle-audit-logs']);
const toggleAuditLogs = () => {
    emit('toggle-audit-logs');
};
</script>

<template>
    <div>
        <Teleport to="#user-details-action-container">
            <div class="flex items-center gap-2">
                <Button variant="secondary" size="sm" @click="toggleAuditLogs">
                    <FileClock class="size-4" />
                    Audit Logs
                </Button>
            </div>
        </Teleport>

        <AuditLogs :user="props.user"></AuditLogs>
    </div>
</template>
