import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';

test.describe('E2E - Process LIC', () => {

    test.skip('Process LIC on listings page with one listing', async ( {} ) => {
        // Go to listings page with lic logged out
        // Sign up
        // Redirect to same url
        // Expect logged in page
        // Listing is added to user's listings
        // Listing is published and author is logged in user
        // Listing is displayed on listings page
    });
    test.skip('Process LIC on listings page with one pending listing', async ( {} ) => {
        // Same but with pending listing
    });
    test.skip('Process LIC on listings page with two listings', async ( {} ) => { });
    test.skip('Invalid LIC on listings page', async ( {} ) => { });
    test.skip('Expired LIC on listings page', async ( {} ) => { });
    test.skip('No Listings LIC on listings page', async ( {} ) => { });

});
