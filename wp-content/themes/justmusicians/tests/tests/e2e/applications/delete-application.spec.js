import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';

test.describe('E2E - Delete Application', () => {

    let applicationId;

    test.beforeEach(async ({ wpCli, singleApplicationPage }) => {
        const applicationAuthor = createUser();
        wpCli.createUser(applicationAuthor);
        const applicationAuthorId = wpCli.getUserId(applicationAuthor.email);

        applicationId = createApplicationPost({ authorId: applicationAuthorId });
        const slug = wpCli.getPostField(applicationId, 'post_name');

        await singleApplicationPage.login(applicationAuthor.email, applicationAuthor.password);
        await singleApplicationPage.navigateToApplication(slug);
    });

    test('Delete application from single application page', async ({ singleApplicationPage, wpCli }) => {
        singleApplicationPage.page.on('dialog', dialog => dialog.accept());
        await singleApplicationPage.deleteBtn.click();

        await singleApplicationPage.page.waitForURL(
            url => url.pathname === '/applications/' && url.searchParams.get('toast') === 'delete',
            { timeout: 10000 }
        );

        expect(wpCli.getPostField(applicationId, 'post_status')).toBe('trash');
    });

    test('Cancel delete application attempt from single application page', async ({ singleApplicationPage, wpCli }) => {
        singleApplicationPage.page.on('dialog', dialog => dialog.dismiss());
        await singleApplicationPage.deleteBtn.click();

        await singleApplicationPage.page.waitForTimeout(2000);
        expect(wpCli.getPostField(applicationId, 'post_status')).toBe('publish');
    });
});
