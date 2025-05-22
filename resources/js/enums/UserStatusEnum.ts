export const UserStatusEnum = {
    ACTIVE: 'active',
    INACTIVE: 'inactive',
    SUSPENDED: 'suspended',
} as const
export type UserStatusEnumType = (typeof UserStatusEnum)[keyof typeof UserStatusEnum]
