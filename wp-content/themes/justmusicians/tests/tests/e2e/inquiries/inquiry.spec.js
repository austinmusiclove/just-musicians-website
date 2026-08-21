import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Inquiry', () => {

    test('Create inquiry from a listing page, musician responds, and buyer responds with message', async ({ themePage, singleListingPage, inquiryModalPage, myGigsPage, singleEventPage, messagesPage, wpCli, mailpit }) => {
        test.slow();
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

        // Go to event page from inquiry success link
        const eventPermalink = await inquiryModalPage.getEventPermalink();
        expect(eventPermalink).toBe(wpCli.getPostUrl(eventId));
        await inquiryModalPage.clickThankYouLink();
        expect(themePage.page.url()).toContain(new URL(eventPermalink).pathname);

        // The event creator got a confirmation email
        const creatorEmail = await mailpit.findEmailBySubject( `(${mailpit.siteUrl} ${buyer.email}) Your event has been created!`);
        expect(creatorEmail).toBeTruthy();

        // The musician got an in-app notification and email about the new inquiry
        expect(wpCli.notificationExists(musicianId, 'new_inquiry', proposalId)).toBe(true);
        const musicianEmail = await mailpit.findEmailBySubject( `(${mailpit.siteUrl} ${musician.email}) New inquiry for ${listingData.meta.name} - ${eventName}`);
        expect(musicianEmail).toBeTruthy();
        const musicianEmailBody = await mailpit.getEmailBody(musicianEmail.ID);
        expect(musicianEmailBody).toContain('/my-gigs/');

        // Logout and log in as musician
        await themePage.logout();
        await themePage.navigate('/');
        await themePage.login(musician.email, musician.password);
        await themePage.expectLoggedInPage();

        // Click to the link from inquiry notification email
        const linkToGigs = mailpit.extractLinkFromEmail(musicianEmailBody);
        expect(linkToGigs).toBeTruthy();
        await myGigsPage.navigate(linkToGigs);
        await myGigsPage.expectGigCard(eventName, 'New Inquiry');

        // Respond to gig
        const responseDetails = 'We are available and would love to play your ceremony!';
        const quoteAmount = '450';
        await myGigsPage.respondToInquiry(eventName, { details: responseDetails, quote: quoteAmount });

        // Logout and log in as buyer
        await themePage.logout();
        await themePage.navigate('/');
        await themePage.login(buyer.email, buyer.password);
        await themePage.expectLoggedInPage();

        // Check email and notification is sent for buyer and click the link
        const gigResponseEmail = await mailpit.findEmailBySubject( `(${mailpit.siteUrl} ${buyer.email}) You have a new response to your inquiry from ${listingData.meta.name}`);
        expect(gigResponseEmail).toBeTruthy();
        const gigResponseEmailBody = await mailpit.getEmailBody(gigResponseEmail.ID);
        const linkToResponse = mailpit.extractLinkFromEmail(gigResponseEmailBody);
        expect(wpCli.notificationExists(buyerId, 'inquiry_response', proposalId)).toBe(true);
        await singleEventPage.navigate(linkToResponse);

        // Check the event response is there
        await singleEventPage.openMusiciansTab();
        await singleEventPage.expectApplicant(listingData.meta.name, { details: responseDetails, quote: quoteAmount });

        // Send message to the musician using send message button in the event applicant card
        const buyerMessageText = `Hi! We would like to book you for our wedding ceremony. (${Date.now()})`;
        await singleEventPage.sendMessageToApplicant(listingData.meta.name, buyerMessageText);
        await singleEventPage.closeSendMessageModal();

        // Check to make sure the musician gets new message email notification and notification
        const conversationId = wpCli.getConversationId(buyerId, listingId);
        expect(conversationId).toBeTruthy();
        const buyerMessage = wpCli.getLastMessage(conversationId);
        expect(String(buyerMessage.sender_id)).toBe(buyerId);
        expect(buyerMessage.content).toBe(buyerMessageText);
        const musicianMessageEmail = await mailpit.waitForMessageEmail(`(${mailpit.siteUrl} ${musician.email}) You have a new message!`);
        expect(musicianMessageEmail).toBeTruthy();
        const musicianMessageEmailBody = await mailpit.getEmailBody(musicianMessageEmail.ID);
        expect(musicianMessageEmailBody).toContain('/messages/');
        expect(wpCli.getUnreadConversationCount(musicianId)).toBe(1);

        // Logout and in as musician
        await themePage.logout();
        await themePage.navigate('/');
        await themePage.login(musician.email, musician.password);
        await themePage.expectLoggedInPage();

        // Follow link to messages from email
        const newMessageFromBuyerLink = mailpit.extractLinkFromEmail(musicianMessageEmailBody);
        expect(newMessageFromBuyerLink).toBeTruthy();
        await messagesPage.navigate(newMessageFromBuyerLink);

        // Read message and make sure the message is marked as read
        expect(wpCli.messageIsRead(buyerMessage.id, musicianId)).toBe(false);
        await messagesPage.openConversation(`${buyer.firstName} ${buyer.lastName}`);
        await messagesPage.expectMessage(conversationId, buyerMessage.id, buyerMessageText);
        expect(wpCli.messageIsRead(buyerMessage.id, musicianId)).toBe(true);

        // Send message back to buyer
        const musicianReplyText = `Thanks for reaching out, we are excited to play! (${Date.now()})`;
        await messagesPage.reply(musicianReplyText);
        const musicianReply = wpCli.getLastMessage(conversationId);
        expect(String(musicianReply.sender_id)).toBe(musicianId);
        expect(musicianReply.content).toBe(musicianReplyText);

        // Make sure email goes to buyer for new message and message notification
        const buyerMessageEmail = await mailpit.waitForMessageEmail(`(${mailpit.siteUrl} ${buyer.email}) You have a new message!`);
        expect(buyerMessageEmail).toBeTruthy();
        expect(await mailpit.getEmailBody(buyerMessageEmail.ID)).toContain('/messages/');
        expect(wpCli.getUnreadConversationCount(buyerId)).toBe(1);
    });

});
