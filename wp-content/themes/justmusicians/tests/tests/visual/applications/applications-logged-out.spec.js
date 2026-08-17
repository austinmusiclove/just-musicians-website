import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplication } from '../../../data/factories/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Applications - Logged out', () => {

    test('User sees the login modal', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/applications/');
        await expect(applicationsPage.signupModalHeading).toBeVisible();
    });

});
