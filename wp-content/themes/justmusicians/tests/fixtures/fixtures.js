import { test as base } from '@playwright/test';
import { ThemePage } from '../pages/ThemePage.js';

export const test = base.extend({
    themePage: async ({ page, isMobile }, use) => {
        const themePage = new ThemePage(page, isMobile);
        await use(themePage);
    },
});
