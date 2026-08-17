import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplication } from '../../../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliGetLatestPostId, wpCliGetPostIdBySlug, wpCliGetPostField, wpCliGetPostMeta, wpCliDeleteUser, wpCliDeletePost } from '../../../data/wp_cli.js';

test.describe('E2E - Create Application', () => {

    let testUser;
    let userId;
    let applicationId;

    test.beforeEach(async ({ applicationFormPage }) => {
        testUser = createUser();
        wpCliCreateUser(testUser);
        userId = wpCliGetUserId(testUser.email);

        await applicationFormPage.login(testUser.email, testUser.password);
        await applicationFormPage.navigate('/application-form/');
    });

    test.afterEach(async () => {
        if (applicationId) { wpCliDeletePost(applicationId); }
        if (testUser)      { wpCliDeleteUser(testUser.email); }
    });

    test('Create application', async ({ applicationFormPage, mailpit }) => {
        const application = createApplication();

        await applicationFormPage.fillMinimumFields(application.title, application.description);
        await applicationFormPage.submitApplication();

        const urlSlug = await applicationFormPage.waitForSubmitRedirect();
        const idFromSlug = wpCliGetPostIdBySlug(urlSlug);
        applicationId = wpCliGetLatestPostId(userId, 'application');
        expect(idFromSlug).toBe(applicationId);

        expect(wpCliGetPostField(applicationId, 'post_title')).toBe(application.title);
        expect(wpCliGetPostField(applicationId, 'post_status')).toBe('publish');
        expect(wpCliGetPostField(applicationId, 'post_author')).toBe(userId);
        expect(wpCliGetPostMeta(applicationId, 'title')).toBe(application.title);
        expect(wpCliGetPostMeta(applicationId, 'description')).toBe(application.description);

        const expectedSubject = `(${mailpit.siteUrl} ${testUser.email}) Your application has been created!`;
        const email = await mailpit.findEmailBySubject(expectedSubject);
        expect(email).toBeTruthy();
    });

    test.skip('Create application with multi line description', async ({ applicationFormPage }) => {} );
});
