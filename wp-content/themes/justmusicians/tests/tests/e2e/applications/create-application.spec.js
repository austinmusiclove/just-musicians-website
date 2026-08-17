import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplication } from '../../../data/factories/application_factory.js';

test.describe('E2E - Create Application', () => {

    let testUser;
    let userId;

    test.beforeEach(async ({ applicationFormPage, wpCli }) => {
        testUser = createUser();
        wpCli.createUser(testUser);
        userId = wpCli.getUserId(testUser.email);

        await applicationFormPage.login(testUser.email, testUser.password);
        await applicationFormPage.navigate('/application-form/');
    });

    test('Create application', async ({ applicationFormPage, mailpit, wpCli }) => {
        const application = createApplication();

        await applicationFormPage.fillMinimumFields(application.title, application.description);
        await applicationFormPage.submitApplication();

        const urlSlug = await applicationFormPage.waitForSubmitRedirect();
        const idFromSlug = wpCli.getPostIdBySlug(urlSlug);
        const applicationId = wpCli.getLatestPostId(userId, 'application');
        wpCli.trackPost(applicationId);
        expect(idFromSlug).toBe(applicationId);

        expect(wpCli.getPostField(applicationId, 'post_title')).toBe(application.title);
        expect(wpCli.getPostField(applicationId, 'post_status')).toBe('publish');
        expect(wpCli.getPostField(applicationId, 'post_author')).toBe(userId);
        expect(wpCli.getPostMeta(applicationId, 'title')).toBe(application.title);
        expect(wpCli.getPostMeta(applicationId, 'description')).toBe(application.description);

        const expectedSubject = `(${mailpit.siteUrl} ${testUser.email}) Your application has been created!`;
        const email = await mailpit.findEmailBySubject(expectedSubject);
        expect(email).toBeTruthy();
    });

    test.skip('Create application with multi line description', async ({ applicationFormPage }) => {} );
});
