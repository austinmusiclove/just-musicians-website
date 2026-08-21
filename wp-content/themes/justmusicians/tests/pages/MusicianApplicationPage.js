import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class MusicianApplicationPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.applicationTitle                     = page.getByTestId('musician-application-title');
        this.applicationDescription               = page.getByTestId('musician-application-description');
        this.musicianApplicationForm              = page.getByTestId('musician-application-form');
        this.listingForm                          = page.getByTestId('listing-form');
        this.listingDropdown                      = page.getByTestId('listing-dropdown');
        this.listingDropdownButton                = this.listingDropdown.locator('button');
        this.listingDropdownButtonLabel           = this.listingDropdownButton.locator('span');
        this.createNewListingOption               = page.getByTestId('listing-dropdown').filter({ hasText: 'Create New Musician Listing' });
        this.performerName                        = page.locator('#performer-name-input');
        this.description                          = page.locator('#description-input');
        this.postalCodeInput                      = page.locator('#listing-form-zip');
        this.postalCodeTarget                     = page.locator('#listing-form-zip-target');
        this.email                                = page.locator('#listing_email');
        this.coverImageInput                      = page.locator('input[name="cover_image_input"]');
        this.applyCropBtn                         = page.getByRole('button', { name: 'Apply' });
        this.applicationSubmissionInputs          = page.getByTestId('application-submission-inputs');
        this.messageTextarea                      = page.locator('textarea[name="applicant_message"]');
        this.submitButton                         = page.locator('#submit-button-content').locator('.htmx-indicator-component-block-replace');
        this.invalidLic                           = page.getByTestId('invalid-lic');
        this.successfulSubmission                 = page.getByTestId('successful-submission');
        this.successfulSubmissionAnon             = page.getByTestId('successful-submission-anon');
        this.successfulSubmissionAnonSignUpBtn    = page.getByTestId('successful-submission-anon-sign-up-btn');
        this.successfulSubmissionNewListing       = page.getByTestId('successful-submission-new-listing');
    }

    async navigateToApplication(applicationId, lic = '') {
        const query = lic ? `?lic=${lic}` : '';
        await super.navigate(`/musician-application/${applicationId}/${query}`);
    }

    async selectListing(listingName) {
        await this.listingDropdownButton.click();
        await this.listingDropdown.locator('li').filter({ hasText: listingName }).click();
        await expect(this.listingDropdownButtonLabel).toContainText(listingName);
    }

    async selectCreateNewListing() {
        await this.listingDropdownButton.click();
        await this.createNewListingOption.click();
    }

    async fillPostalCode(postalCodePrefix) {
        await this.postalCodeInput.click();
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('location-search-options-pc') && resp.status() === 200
        );
        await this.postalCodeInput.fill(postalCodePrefix);
        await responsePromise;
        await this.postalCodeTarget.locator('li').first().waitFor({ timeout: 10000 });
        await this.postalCodeTarget.locator('li').first().click();
    }

    async fillMinimumListingFields(name, description, postalCodePrefix, email) {
        await this.performerName.fill(name);
        await this.description.fill(description);
        await this.fillPostalCode(postalCodePrefix);
        await this.email.fill(email);
    }

    async uploadCoverImage(imagePath) {
        await this.coverImageInput.setInputFiles(imagePath);
        // wait for image to be processed before closing modal so that auto submit is not triggered when it finishes processing
        const alpineEl = await this.musicianApplicationForm.elementHandle();
        await this.page.waitForFunction(
            (el) => {
                const data = Alpine.$data(el);
                const coverImages = data.orderedImageData['cover_image'];
                return coverImages.length > 0 && !coverImages[0].loading;
            },
            alpineEl
        );
        await expect(this.applyCropBtn).toBeVisible();
        await this.applyCropBtn.click();
    }

    async fillMessage(message) {
        await this.messageTextarea.fill(message);
    }

    async submitApplication() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/wp-html/v1/applications/') && resp.url().includes('/submit/')
        );
        await this.submitButton.click();
        return responsePromise;
    }

    async expectSuccessScreen() {
        await expect(this.successfulSubmission).toBeVisible();
        await expect(this.applicationTitle).not.toBeVisible();
        await expect(this.applicationDescription).not.toBeVisible();
        await expect(this.musicianApplicationForm).not.toBeVisible();
    }
    async expectSuccessScreenNewListing() {
        await expect(this.successfulSubmissionNewListing).toBeVisible();
        await expect(this.applicationTitle).not.toBeVisible();
        await expect(this.applicationDescription).not.toBeVisible();
        await expect(this.musicianApplicationForm).not.toBeVisible();
    }
    async expectSuccessScreenAnon() {
        await expect(this.successfulSubmissionAnon).toBeVisible();
        await expect(this.applicationTitle).not.toBeVisible();
        await expect(this.applicationDescription).not.toBeVisible();
        await expect(this.musicianApplicationForm).not.toBeVisible();
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
