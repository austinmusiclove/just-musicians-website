import { expect } from '@playwright/test';
import { test } from '../fixtures/fixtures.js';
import { createUser } from '../data/user_factory.js';
import { createApplication } from '../data/application_factory.js';
import { createListing } from '../data/listing_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliSetUserMeta, wpCliCreatePost, wpCliDeletePost } from '../data/wp_cli.js';


test.describe('Musician Application', () => {

    let applicationAuthorUser;
    let applicationId;

    test.beforeAll(async () => {
        applicationAuthorUser = createUser();
        wpCliCreateUser(applicationAuthorUser);
        const applicationAuthorUserId = wpCliGetUserId(applicationAuthorUser.email);

        const application = createApplication();
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

    test.afterAll(async () => {
        if (applicationId) {
            wpCliDeletePost(applicationId);
        }
        if (applicationAuthorUser) {
            wpCliDeleteUser(applicationAuthorUser.email);
        }
    });

    test('not logged in user sees the listing form and no listing dropdown', async ({ musicianApplicationPage }) => {
        await musicianApplicationPage.navigateByApplicationId(applicationId);
        await expect(musicianApplicationPage.listingForm).toBeVisible();
        await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
        await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
    });

    test.describe('logged in user', () => {

        test.describe('with no listings', () => {

            let noListingsUser;

            test.beforeAll(async () => {
                noListingsUser = createUser();
                wpCliCreateUser(noListingsUser);
            });

            test.afterAll(async () => {
                if (noListingsUser) {
                    wpCliDeleteUser(noListingsUser.email);
                }
            });

            test('sees the listing form and no listing dropdown', async ({ musicianApplicationPage }) => {
                await musicianApplicationPage.navigate('/');
                await musicianApplicationPage.login(noListingsUser.email, noListingsUser.password);
                await musicianApplicationPage.navigateByApplicationId(applicationId);
                await expect(musicianApplicationPage.listingForm).toBeVisible();
                await expect(musicianApplicationPage.listingDropdown).not.toBeVisible();
                await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
            });

        });

        test.describe('with listings', () => {

            let listingsUser;
            let listingId;

            test.beforeAll(async () => {
                listingsUser = createUser();
                wpCliCreateUser(listingsUser);
                const listingsUserId = wpCliGetUserId(listingsUser.email);

                const listing = createListing();
                listingId = wpCliCreatePost({
                    postType: 'listing',
                    title: listing.name,
                    status: 'publish',
                    authorId: listingsUserId,
                    meta: {
                        name: listing.name,
                        description: listing.description,
                        city: listing.city,
                        state: listing.state,
                        zip_code: listing.zip,
                        bio: listing.bio,
                        verified: listing.verified,
                    },
                });
                wpCliSetUserMeta(listingsUser.email, 'listings', [listingId]);
            });

            test.afterAll(async () => {
                if (listingId) {
                    wpCliDeletePost(listingId);
                }
                if (listingsUser) {
                    wpCliDeleteUser(listingsUser.email);
                }
            });

            test('sees the listing dropdown and no listing form', async ({ musicianApplicationPage }) => {
                await musicianApplicationPage.navigate('/');
                await musicianApplicationPage.login(listingsUser.email, listingsUser.password);
                await musicianApplicationPage.navigateByApplicationId(applicationId);
                await expect(musicianApplicationPage.listingDropdown).toBeVisible();
                await expect(musicianApplicationPage.listingForm).not.toBeVisible();
                await expect(musicianApplicationPage.applicationSubmissionInputs).toBeVisible();
            });

        });

    });

});
