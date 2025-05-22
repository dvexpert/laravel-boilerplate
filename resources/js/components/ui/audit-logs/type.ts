import { User } from '@/types'

export interface AuditLog {
    id: number
    user_type: string
    user_id: string // UUID
    event: string
    auditable_type: string
    auditable_id: string // UUID
    old_values: {
        [key: string]: string | number | boolean | null
    }
    new_values: {
        [key: string]: string | number | boolean | null
    }
    url: string
    ip_address: string
    user_agent: string
    tags: string | null
    created_at: string // ISO date string
    updated_at: string // ISO date string
    user: Partial<User>
}
