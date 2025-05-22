import type { Updater } from '@tanstack/vue-table';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import type { Ref } from 'vue';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function valueUpdater<T extends Updater<any>, TVal>(updaterOrValue: T, ref: Ref<TVal>, onlyReturn: boolean = false): TVal {
    const value = typeof updaterOrValue === 'function' ? updaterOrValue(ref.value) : updaterOrValue;

    if (!onlyReturn) {
        ref.value = value;
    }

    return value;
}
