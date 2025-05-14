import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    interface String {
        // resources/js/app.ts on line 25
        toTitleCase(): string;
    }
}

export {};
