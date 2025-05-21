import { AuditLog } from '@/components/ui/audit-logs/type';
import { Button } from '@/components/ui/button';
import { User } from '@/types';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

interface ColumnsProps {
    viewHandler: (audit: AuditLog) => void;
}
export default function getColumns({ viewHandler }: ColumnsProps): ColumnDef<AuditLog>[] {
    return [
        {
            accessorKey: 'id',
            header: () => h('div', { class: 'text-right' }, 'Id'),
            cell: ({ row }) => {
                return h('div', { class: 'text-right font-medium' }, row.getValue('id'));
            },
        },
        {
            accessorKey: 'created_at',
            header: () => h('div', { class: '' }, 'Data & Time'),
            cell: ({ row }) => {
                return h('div', { class: 'font-medium' }, row.getValue('created_at'));
            },
        },
        {
            accessorKey: 'event',
            header: () => h('div', { class: '' }, 'Action'),
            cell: ({ row }) => {
                return h('div', { class: '' }, row.getValue('event'));
            },
        },
        {
            accessorKey: 'auditable_type',
            header: () => h('div', { class: '' }, 'Model'),
            cell: ({ row }) => {
                const model = row.getValue('auditable_type');
                const modelName = String(model).split('\\').pop();

                return h('div', { class: 'capitalize' }, modelName);
            },
        },
        {
            accessorKey: 'details',
            header: () => h('div', { class: '' }, 'Details'),
            cell: ({ row }) => {
                return h(
                    Button,
                    {
                        variant: 'link',
                        class: 'capitalize',
                        onClick: () => {
                            viewHandler(row.original);
                        },
                    },
                    {
                        default: () => 'View',
                    },
                );
            },
        },
        {
            accessorKey: 'user',
            header: () => h('div', { class: '' }, 'Action By'),
            cell: ({ row }) => {
                return h(
                    'div',
                    {
                        class: 'capitalize',
                    },
                    (row.getValue('user') as Partial<User>).name,
                );
            },
        },
    ];
}
