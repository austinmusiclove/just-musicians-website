import { test as base } from '@playwright/test';
import { ThemePage } from '../pages/ThemePage.js';
import { ApplicationsPage } from '../pages/ApplicationsPage.js';
import { ApplicationFormPage } from '../pages/ApplicationFormPage.js';
import { MusicianApplicationPage } from '../pages/MusicianApplicationPage.js';
import { ListingFormPage } from '../pages/ListingFormPage.js';
import { SingleApplicationPage } from '../pages/SingleApplicationPage.js';
import { findEmailBySubject as findEmail, getEmailBody as getEmail, extractLinkFromEmail as extractLink } from '../data/mailpit.js';
import {
    wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeleteUsers,
    wpCliCreatePost, wpCliGetUserMeta, wpCliSetUserMeta,
    wpCliGetLatestPostId, wpCliGetLatestPostIdByType, wpCliGetPostField, wpCliGetPostMeta,
    wpCliGetPostIdBySlug, wpCliGetPostThumbnailId,
    wpCliSetPostThumbnail, wpCliDeletePost,
    wpCliAddListingToUser, wpCliNotificationExists,
} from '../data/wp_cli.js';

export const test = base.extend({
    mailpit: async ({ baseURL }, use) => {
        const mailpitApiUrl = process.env.MAILPIT_API_URL || 'http://localhost:10000/api/v1';
        await use({
            apiUrl: mailpitApiUrl,
            siteUrl: baseURL,
            findEmailBySubject: (subject) => findEmail(subject, mailpitApiUrl),
            getEmailBody: (messageId) => getEmail(messageId, mailpitApiUrl),
            extractLinkFromEmail: extractLink,
        });
    },
    wpCli: async ({}, use) => {
        const createdUsers = [];
        const createdPosts = [];
        await use({
            createUser: (user) => { wpCliCreateUser(user); createdUsers.push(user); },
            getUserId: wpCliGetUserId,
            deleteUser: wpCliDeleteUser,
            deleteUsers: wpCliDeleteUsers,
            createPost: (args) => { const id = wpCliCreatePost(args); createdPosts.push(id); return id; },
            getUserMeta: wpCliGetUserMeta,
            setUserMeta: wpCliSetUserMeta,
            getLatestPostId: wpCliGetLatestPostId,
            getLatestPostIdByType: wpCliGetLatestPostIdByType,
            getPostField: wpCliGetPostField,
            getPostMeta: wpCliGetPostMeta,
            getPostIdBySlug: wpCliGetPostIdBySlug,
            getPostThumbnailId: wpCliGetPostThumbnailId,
            setPostThumbnail: wpCliSetPostThumbnail,
            deletePost: wpCliDeletePost,
            addListingToUser: wpCliAddListingToUser,
            notificationExists: wpCliNotificationExists,
            trackUser: (user) => { if (user) createdUsers.push(user); },
            trackPost: (postId) => { if (postId) createdPosts.push(postId); },
        });
        for (const id of createdPosts) wpCliDeletePost(id);
        for (const u of createdUsers) wpCliDeleteUser(u.email);
    },
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
    singleApplicationPage: async ({ page, isMobile }, use) => {
        const singleApplicationPage = new SingleApplicationPage(page, isMobile);
        await use(singleApplicationPage);
    },
});
