import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';

test.describe('E2E - Update Application', () => {

    let applicationId;

    test.beforeEach(async ({ singleApplicationPage, wpCli }) => {
        const testUser = createUser();
        wpCli.createUser(testUser);
        const userId = wpCli.getUserId(testUser.email);

        applicationId = createApplicationPost({
            authorId: userId,
            overrides: { title: 'Original Title', description: 'Original description' },
        });
        wpCli.trackPost(applicationId);

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

});
