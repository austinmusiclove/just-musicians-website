import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Inquiry', () => {

    test('Create inquiry from a listing page and the musician gets notified via email', async ({ themePage, singleListingPage, inquiryModalPage, wpCli, mailpit }) => {
        const buyer = createUser();
        const buyerId = wpCli.createUser(buyer);

        // Create a musician with a listing that matches the inquiry we are about to send
        const musician = createUser();
        const musicianId = wpCli.createUser(musician);
        const listingData = createListingPostData({
            authorId: musicianId,
            overrides: { city: 'Austin', state: 'Texas', zip: '78701', genres: ['Rock'], ensembleSizes: ['Solo'] },
        });
        const listingId = wpCli.createListing(listingData);

        const eventDate = new Date(Date.now() + 24 * 60 * 60 * 1000);
        const eventDateStr = `${eventDate.getFullYear()}-${String(eventDate.getMonth() + 1).padStart(2, '0')}-${String(eventDate.getDate()).padStart(2, '0')}`;
        const eventName = `${buyer.firstName}'s Wedding Ceremony ${Date.now()}`;

        await themePage.navigate('/');
        await themePage.login(buyer.email, buyer.password);
        await themePage.expectLoggedInPage();

        // Start the inquiry from the public page of the created listing
        const listingPermalink = wpCli.getPostUrl(listingId);
        await singleListingPage.navigate(listingPermalink);
        await singleListingPage.sendInquiry();
        await inquiryModalPage.createInquiry({
            date: eventDateStr,
            postalCodePrefix: '78701',
            genres: ['Rock'],
            ensembleSizes: ['Solo'],
            eventName,
            eventDetails: 'Outdoor ceremony, need sound equipment for 100 guests.',
            sendToSimilarMusicians: false,
        });

        // Event was created for the logged in user
        const eventId = wpCli.getLatestPostId(buyerId, 'event', 'publish');
        expect(eventId).toBeTruthy();
        expect(wpCli.getPostField(eventId, 'post_title')).toBe(eventName);
        expect(wpCli.getPostField(eventId, 'post_status')).toBe('publish');
        expect(wpCli.getPostField(eventId, 'post_author')).toBe(buyerId);
        expect(wpCli.getPostMeta(eventId, 'start_date')).toBe(eventDateStr);
        expect(wpCli.getPostMeta(eventId, 'city')).toBe('Austin');
        expect(wpCli.getPostMeta(eventId, 'state')).toBe('Texas');
        expect(wpCli.getPostMeta(eventId, 'zip_code')).toBe('78701');
        expect(wpCli.getPostMeta(eventId, 'auto_rfp')).not.toBe('1'); // The inquiry was declined for similar musicians, so no auto-RFP was scheduled
        wpCli.trackPost(eventId);

        // The listing was the only musician invited to respond
        const proposalId = wpCli.getLatestPostIdByType('proposal', 'publish', { metaKey: 'event', metaValue: eventId });
        expect(proposalId).toBeTruthy();
        expect(wpCli.getPostMeta(proposalId, 'listing')).toBe(String(listingId));
        expect(wpCli.getPostMeta(proposalId, 'status')).toBe('inquiry');
        wpCli.trackPost(proposalId);

        // The musician got an in-app notification about the new inquiry
        expect(wpCli.notificationExists(musicianId, 'new_inquiry', proposalId)).toBe(true);

        // The musician got an email about the new inquiry
        const musicianEmail = await mailpit.findEmailBySubject(
            `(${mailpit.siteUrl} ${musician.email}) New inquiry for ${listingData.meta.name} - ${eventName}`
        );
        expect(musicianEmail).toBeTruthy();
        const musicianEmailBody = await mailpit.getEmailBody(musicianEmail.ID);
        expect(musicianEmailBody).toContain('/my-gigs/');

        // The event creator got a confirmation email
        const creatorEmail = await mailpit.findEmailBySubject(
            `(${mailpit.siteUrl} ${buyer.email}) Your event has been created!`
        );
        expect(creatorEmail).toBeTruthy();
    });

});
