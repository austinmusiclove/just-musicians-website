import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/user_factory.js';
import { createApplication } from '../../../data/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Applications - One applicaiton', () => {

    let applicationAuthorUser;
    let applicationId;
    let applicationTitle;

    test.afterAll(async () => {
        if (applicationId) {
            wpCliDeletePost(applicationId);
        }
        if (applicationAuthorUser) {
            wpCliDeleteUser(applicationAuthorUser.email);
        }
    });

    test('User\'s application is displayed', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/');
        await applicationsPage.login(applicationAuthorUser.email, applicationAuthorUser.password);
        await applicationsPage.navigate('/applications/');
        await applicationsPage.waitForResults();
        const cards = await applicationsPage.applicationCards.all();
        expect(cards).toHaveLength(1);
        await expect(applicationsPage.getCardTitle(applicationsPage.applicationCards.first())).toHaveText(applicationTitle);
    });

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);

        const application = createApplication();
        applicationTitle = application.title;
        applicationId = wpCliCreatePost({
            postType: 'application',
            title: application.title,
            authorId: applicationAuthorUserId,
            meta: {
                title: application.title,
                description: application.description,
            },
        });
    });

});
