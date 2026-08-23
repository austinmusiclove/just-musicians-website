import { faker } from '@faker-js/faker';
import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPostData } from '../../../data/factories/application_factory.js';
import { createAppSubmissionPostData } from '../../../data/factories/app_submission_factory.js';
import { createListingPostData } from '../../../data/factories/listing_factory.js';

test.describe('E2E - Export Applicants', () => {

    let testUser;
    let applicationId;

    test.beforeEach(async ({ singleApplicationPage, wpCli }) => {
        testUser = createUser();
        const testUserId = wpCli.createUser(testUser);

        applicationId = wpCli.createPost(createApplicationPostData({ authorId: testUserId }));
    });

    test('Export applicants to csv', async ({ singleApplicationPage, wpCli, downloads }) => {

        // Creates a listing and its app_submission link; returns the listing fields needed for assertions
        const createApplicant = ({ authorId = 0, listingStatus = 'publish', listingOverrides = {} } = {}) => {
            const listingData = createListingPostData({ authorId, status: listingStatus, overrides: listingOverrides });
            const listingId = wpCli.createListing(listingData);
            wpCli.createPost(createAppSubmissionPostData({ applicationId, listingId }));
            return { name: listingData.meta.name, email: listingData.meta.email };
        };

        // Applicant 1 has listing email and no account (listing email belongs in csv)
        let applicants = [];
        const applicant1ListingEmail = faker.internet.email();
        applicants.push(createApplicant({ listingStatus: 'pending', listingOverrides: { name: 'Applicant 1', email: applicant1ListingEmail } }));
        // Applicant 2 has no listing email and no account (no email belongs in csv)
        applicants.push(createApplicant({ listingStatus: 'pending', listingOverrides: { name: 'Applicant 2', email: '' } }));
        // Applicant 3 has an account and listing email (listing email belongs in csv)
        const applicant3 = createUser();
        const applicant3Id = wpCli.createUser(applicant3)
        const applicant3ListingEmail = faker.internet.email();
        applicants.push(createApplicant({ authorId: applicant3Id, listingOverrides: { name: 'Applicant 3', email: applicant3ListingEmail } }));
        // Applicant 4 has an account and no listing email (user email belongs in csv)
        const applicant4 = createUser();
        const applicant4Id = wpCli.createUser(applicant4)
        applicants.push(createApplicant({ authorId: applicant4Id, listingOverrides: { name: 'Applicant 4', email: '' } }));

        await singleApplicationPage.login(testUser.email, testUser.password);
        const slug = wpCli.getPostField(applicationId, 'post_name');
        await singleApplicationPage.navigateToApplication(slug, 'applicants');

        const download = await singleApplicationPage.exportApplicantsToCsv();

        expect(download.suggestedFilename()).toMatch(/^applicants-.+-\d{4}-\d{2}-\d{2}\.csv$/);

        const contents = await downloads.downloadText(download);
        const rows = downloads.parseCsv(contents);

        expect(rows[0]).toEqual(['Name', 'Email', 'Phone', 'City', 'State', 'Genres', 'Description', 'Ensemble Size', 'Website', 'Spotify', 'Apple Music', 'Instagram', 'Facebook', 'Youtube', 'Bandcamp', 'Soundcloud', 'Hire Musicians', 'Applied Date', 'Message']);
        expect(rows).toHaveLength(5); // header + 3 applicants

        // Check applicant 1 has

        const rowsByName = Object.fromEntries(rows.slice(1).map(row => [row[0], row]));
        expect(Object.keys(rowsByName)).toHaveLength(4);

        expect(rowsByName['Applicant 1'][1]).toBe(applicant1ListingEmail);
        expect(rowsByName['Applicant 2'][1]).toBe('');
        expect(rowsByName['Applicant 3'][1]).toBe(applicant3ListingEmail);
        expect(rowsByName['Applicant 4'][1]).toBe(applicant4.email);
    });
});
