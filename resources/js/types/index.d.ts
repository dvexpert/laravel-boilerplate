import { PermissionEnumType } from '@/enums/PermissionEnum';
import { RoleEnumType } from '@/enums/RoleEnum';
import { UserStatusEnumType } from '@/enums/UserStatusEnum';
import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    can: Partial<Record<PermissionEnumType, boolean>>;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    isAllowed: PermissionEnumType | boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    flash: {
        success: string;
        message: string;
    };
}

export interface UserRole {
    id: number;
    name: RoleEnumType;
    name_label: string;
    guard_name: 'web';
    created_at: string;
    updated_at: string;
    permissions: Permission[];
}

export interface Permission {
    id: number;
    name: RoleEnumType;
    name_label: string;
    guard_name: 'web';
    created_at: string;
    updated_at: string;
}
export interface GroupedPermission extends Record<string, Permission[]> {}

export interface User {
    id: string;
    name: string;
    first_name: string;
    last_name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    roles: UserRole[];
    status: UserStatusEnumType;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Paginated<T = {}> {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}
