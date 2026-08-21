import { test as base } from '@playwright/test';
import { ThemePage } from '../pages/ThemePage.js';
import { ApplicationsPage } from '../pages/ApplicationsPage.js';
import { ApplicationFormPage } from '../pages/ApplicationFormPage.js';
import { MusicianApplicationPage } from '../pages/MusicianApplicationPage.js';
import { ListingsPage } from '../pages/ListingsPage.js';
import { ListingFormPage } from '../pages/ListingFormPage.js';
import { SingleListingPage } from '../pages/SingleListingPage.js';
import { SingleApplicationPage } from '../pages/SingleApplicationPage.js';
import { PasswordResetPage } from '../pages/PasswordResetPage.js';
import { InquiryModalPage } from '../pages/InquiryModalPage.js';
import { findEmailBySubject as findEmail, getEmailBody as getEmail, extractLinkFromEmail as extractLink } from '../data/mailpit.js';
import {
    wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliDeleteUsers,
    wpCliCreatePost, wpCliGetUserMeta, wpCliSetUserMeta,
    wpCliGetLatestPostId, wpCliGetLatestPostIdByType, wpCliGetPostField, wpCliGetPostUrl, wpCliGetPostMeta,
    wpCliGetPostIdBySlug, wpCliGetPostThumbnailId,
    wpCliSetPostThumbnail, wpCliDeletePost,
    wpCliAddListingToUser, wpCliNotificationExists,
    wpCliSetPostTerms, wpCliIndexListing, wpCliCreateListing,
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
            createUser: (user) => { const id = wpCliCreateUser(user); createdUsers.push(user); return id; },
            getUserId: wpCliGetUserId,
            deleteUser: wpCliDeleteUser,
            deleteUsers: wpCliDeleteUsers,
            createPost: (args) => { const id = wpCliCreatePost(args); createdPosts.push(id); return id; },
            createListing: (listingData) => { const id = wpCliCreateListing(listingData); createdPosts.push(id); return id; },
            getUserMeta: wpCliGetUserMeta,
            setUserMeta: wpCliSetUserMeta,
            getLatestPostId: wpCliGetLatestPostId,
            getLatestPostIdByType: wpCliGetLatestPostIdByType,
            getPostField: wpCliGetPostField,
            getPostUrl: wpCliGetPostUrl,
            getPostMeta: wpCliGetPostMeta,
            getPostIdBySlug: wpCliGetPostIdBySlug,
            getPostThumbnailId: wpCliGetPostThumbnailId,
            setPostThumbnail: wpCliSetPostThumbnail,
            deletePost: wpCliDeletePost,
            addListingToUser: wpCliAddListingToUser,
            notificationExists: wpCliNotificationExists,
            setPostTerms: wpCliSetPostTerms,
            indexListing: wpCliIndexListing,
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
    listingsPage: async ({ page, isMobile }, use) => {
        const listingsPage = new ListingsPage(page, isMobile);
        await use(listingsPage);
    },
    listingFormPage: async ({ page, isMobile }, use) => {
        const listingFormPage = new ListingFormPage(page, isMobile);
        await use(listingFormPage);
    },
    singleListingPage: async ({ page, isMobile }, use) => {
        const singleListingPage = new SingleListingPage(page, isMobile);
        await use(singleListingPage);
    },
    singleApplicationPage: async ({ page, isMobile }, use) => {
        const singleApplicationPage = new SingleApplicationPage(page, isMobile);
        await use(singleApplicationPage);
    },
    passwordResetPage: async ({ page, isMobile }, use) => {
        const passwordResetPage = new PasswordResetPage(page, isMobile);
        await use(passwordResetPage);
    },
    inquiryModalPage: async ({ page, isMobile }, use) => {
        const inquiryModalPage = new InquiryModalPage(page, isMobile);
        await use(inquiryModalPage);
    },
});
