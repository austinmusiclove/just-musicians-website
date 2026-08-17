import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';


test.describe('Visual - Applications - Logged out', () => {

    test('User sees the login modal', async ({ applicationsPage }) => {
        await applicationsPage.navigate('/applications/');
        await expect(applicationsPage.signupModalHeading).toBeVisible();
    });

});
