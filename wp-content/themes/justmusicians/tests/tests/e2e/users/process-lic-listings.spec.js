import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';
import { createTmpCodePostData } from '../../../data/factories/tmp_code_factory.js';

test.describe('E2E - Process LIC', () => {

    let listingId;
    let listingData;
    let tmpCodeData;

    test.beforeEach(async ({ wpCli }) => {
        const testUser = createUser();
        const testUserId = wpCli.createUser(testUser);

        listingData = createListingPostData({ authorId: testUserId });
        listingId = wpCli.createListing(listingData);

        tmpCodeData = createTmpCodePostData({ authorId: testUserId, status: 'publish', overrides: { listings: [Number(listingId)] } });
        wpCli.createPost(tmpCodeData);
    });

    test('Process LIC on listings page with one listing', async ( { listingsPage, wpCli } ) => {
        await listingsPage.navigateWithLic(tmpCodeData.meta.code, true);
        await expect(listingsPage.signupModalHeading).toBeVisible();

        const newUser = createUser();
        await listingsPage.fillSignupForm(newUser);
        await listingsPage.signupSubmitBtn.click();

        await listingsPage.page.waitForURL(
            url => url.pathname.includes(`/listings/`) && url.searchParams.get('lic') === tmpCodeData.meta.code,
            { timeout: 20000 }
        );
        wpCli.trackUser(newUser);
        await listingsPage.expectLoggedInPage();

        const newUserId = wpCli.getUserId(newUser.email);
        const newUserListings = wpCli.getUserMeta(newUserId, 'listings');
        expect(newUserListings).toContain(Number(listingId));
        expect(wpCli.getPostField(listingId, 'post_status')).toBe('publish');

        const cards = await listingsPage.listingCards.all();
        expect(cards).toHaveLength(1);
        await expect(listingsPage.getCardTitle(listingsPage.listingCards.first())).toHaveText(listingData.meta.name);
    });
    test.skip('Process LIC on listings page with one pending listing', async ( {} ) => { }); // same except expect the post author to be set to the new user
    test.skip('Process LIC on listings page with two listings', async ( {} ) => { });
    test.skip('Invalid LIC on listings page', async ( {} ) => { });
    test.skip('Expired LIC on listings page', async ( {} ) => { });
    test.skip('No Listings LIC on listings page', async ( {} ) => { });

});
