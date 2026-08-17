import { test as base } from '@playwright/test';
import { ThemePage } from '../pages/ThemePage.js';
import { ApplicationsPage } from '../pages/ApplicationsPage.js';
import { ApplicationFormPage } from '../pages/ApplicationFormPage.js';
import { MusicianApplicationPage } from '../pages/MusicianApplicationPage.js';
import { ListingFormPage } from '../pages/ListingFormPage.js';

export const test = base.extend({
    themePage: async ({ page, isMobile }, use) => {
        const themePage = new ThemePage(page, isMobile);
        await use(themePage);
    },
    applicationsPage: async ({ page, isMobile }, use) => {
        const applicationsPage = new ApplicationsPage(page, isMobile);
        await use(applicationsPage);
    },
    applicationFormPage: async ({ page, isMobile }, use) => {
        const applicationFormPage = new ApplicationFormPage(page, isMobile);
        await use(applicationFormPage);
    },
    musicianApplicationPage: async ({ page, isMobile }, use) => {
        const musicianApplicationPage = new MusicianApplicationPage(page, isMobile);
        await use(musicianApplicationPage);
    },
    listingFormPage: async ({ page, isMobile }, use) => {
        const listingFormPage = new ListingFormPage(page, isMobile);
        await use(listingFormPage);
    },
});
