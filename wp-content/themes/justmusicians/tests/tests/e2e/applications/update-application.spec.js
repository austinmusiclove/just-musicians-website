import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';

test.describe('E2E - Update Application', () => {

    let applicationId;

    test.beforeEach(async ({ singleApplicationPage, wpCli }) => {
        const testUser = createUser();
        const userId = wpCli.createUser(testUser);

        applicationId = wpCli.createPost(createApplicationPostData({ authorId: userId }));

        await singleApplicationPage.login(testUser.email, testUser.password);
    });

    test('Update application title and description', async ({ singleApplicationPage, wpCli }) => {
        const slug = wpCli.getPostField(applicationId, 'post_name');
        await singleApplicationPage.navigateToApplication(slug);

        await singleApplicationPage.clickEdit();

        const newTitle = 'Updated Title';
        const newDescription = 'Updated description text';
        await singleApplicationPage.fillTitle(newTitle);
        await singleApplicationPage.fillDescription(newDescription);
        await singleApplicationPage.updateApplication();

        expect(wpCli.getPostField(applicationId, 'post_title')).toBe(newTitle);
        expect(wpCli.getPostMeta(applicationId, 'title')).toBe(newTitle);
        expect(wpCli.getPostMeta(applicationId, 'description')).toBe(newDescription);
    });

    test('Update application request quote and request draw', async ({ singleApplicationPage, wpCli }) => {
        const slug = wpCli.getPostField(applicationId, 'post_name');
        await singleApplicationPage.navigateToApplication(slug);

        // Check request quote and request draw
        await singleApplicationPage.clickEdit();
        await singleApplicationPage.checkRequestQuote();
        await singleApplicationPage.checkRequestDraw();
        await singleApplicationPage.updateApplication();
        expect(wpCli.getPostMeta(applicationId, 'request_quote')).toBe('1');
        expect(wpCli.getPostMeta(applicationId, 'request_draw')).toBe('1');

        // Uncheck request quote and request draw
        await singleApplicationPage.clickEdit();
        await singleApplicationPage.checkRequestQuote(false);
        await singleApplicationPage.checkRequestDraw(false);
        await singleApplicationPage.updateApplication();
        expect(wpCli.getPostMeta(applicationId, 'request_quote')).toBeNull();
        expect(wpCli.getPostMeta(applicationId, 'request_draw')).toBeNull();
    });

});
