export const RoleEnum = {
    APPLICATION_ADMIN: 'application_admin',
    INVESTIGATION_ADMIN: 'investigation_admin',
    USER_MANAGER: 'user_manager',
    APPLICATION_MANAGER: 'application_manager',
    INVESTIGATION_MANAGER: 'investigation_manager',
    SYSTEM_ADMINISTRATOR: 'system_administrator',
    SUPERVISOR: 'supervisor',
} as const

export type RoleEnumType = (typeof RoleEnum)[keyof typeof RoleEnum]
