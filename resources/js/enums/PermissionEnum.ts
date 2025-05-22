export const PermissionEnum = {
    USER_READ: 'user.read',
    USER_CREATE: 'user.create',
    USER_UPDATE: 'user.update',
    USER_DELETE: 'user.delete',
} as const

export type PermissionEnumType = (typeof PermissionEnum)[keyof typeof PermissionEnum]
