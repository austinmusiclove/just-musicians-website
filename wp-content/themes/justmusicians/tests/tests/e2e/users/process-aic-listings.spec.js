import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createArtistPostData } from '../../../data/factories/artist_factory.js';
import { createTmpCodePostData } from '../../../data/factories/tmp_code_factory.js';

test.describe('E2E - Process AIC', () => {

    let artistData;
    let tmpCodeData;

    test.beforeEach(async ({ wpCli }) => {
        const testUser = createUser();
        const testUserId = wpCli.createUser(testUser);

        artistData = createArtistPostData({author: testUserId});
        const artistId = wpCli.createPost(artistData);

        tmpCodeData = createTmpCodePostData({ authorId: testUserId, status: 'publish', overrides: { artists: [Number(artistId)] } });
        wpCli.createPost(tmpCodeData);
    });

    test('Process AIC on listings page with one listing', async ( { listingsPage, wpCli } ) => {
        await listingsPage.navigateWithAic(tmpCodeData.meta.code, true);
        await expect(listingsPage.signupModalHeading).toBeVisible();

        const newUser = createUser();
        await listingsPage.fillSignupForm(newUser);
        await listingsPage.signupSubmitBtn.click();

        await listingsPage.page.waitForURL(
            url => url.pathname.includes(`/listings/`) && url.searchParams.get('aic') === tmpCodeData.meta.code,
            { timeout: 20000 }
        );
        wpCli.trackUser(newUser);
        await listingsPage.expectLoggedInPage();

        const listingPostId = wpCli.getLatestPostIdByType('listing', 'draft', { metaKey: 'name', metaValue: artistData.meta.name });
        expect(listingPostId).toBeTruthy();
        wpCli.trackPost(listingPostId);
        const newUserId = wpCli.getUserId(newUser.email);
        const newUserListings = wpCli.getUserMeta(newUserId, 'listings');
        expect(wpCli.getPostField(listingPostId, 'post_author')).toBe(newUserId);
        expect(wpCli.getPostField(listingPostId, 'post_status')).toBe('draft');
        expect(wpCli.getPostMeta(listingPostId, 'name')).toBe(artistData.meta.name);
        expect(newUserListings).toContain(Number(listingPostId));

        const cards = await listingsPage.listingCards.all();
        expect(cards).toHaveLength(1);
        await expect(listingsPage.getCardTitle(listingsPage.listingCards.first())).toHaveText(artistData.meta.name + ' (Draft)');
    });
    test.skip('Process AIC on listings page with two listings', async ( {} ) => { });
    test.skip('Invalid AIC on listings page', async ( {} ) => { });
    test.skip('Expired AIC on listings page', async ( {} ) => { });
    test.skip('No Artists AIC on listings page', async ( {} ) => { });

});
