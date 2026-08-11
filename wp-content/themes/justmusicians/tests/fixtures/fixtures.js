import { test as base } from '@playwright/test';
import { ThemePage } from '../pages/ThemePage.js';
import { ApplicationsPage } from '../pages/ApplicationsPage.js';
import { MusicianApplicationPage } from '../pages/MusicianApplicationPage.js';

export const test = base.extend({
    themePage: async ({ page, isMobile }, use) => {
        const themePage = new ThemePage(page, isMobile);
        await use(themePage);
    },
    applicationsPage: async ({ page, isMobile }, use) => {
        const applicationsPage = new ApplicationsPage(page, isMobile);
        await use(applicationsPage);
    },
    musicianApplicationPage: async ({ page, isMobile }, use) => {
        const musicianApplicationPage = new MusicianApplicationPage(page, isMobile);
        await use(musicianApplicationPage);
    },
});
