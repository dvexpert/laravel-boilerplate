<script setup lang="ts">
// This component is only to provide toggle button for Audit Logs in Admin Layout pages.
import { AuditLogs } from '@/components/ui/audit-logs';
import Button from '@/components/ui/button/Button.vue';
import { FileClock } from 'lucide-vue-next';

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

        <AuditLogs></AuditLogs>
    </div>
</template>
