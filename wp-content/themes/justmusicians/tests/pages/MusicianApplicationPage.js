import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class MusicianApplicationPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.listingForm     = page.getByTestId('listing-form');
        this.listingDropdown = page.getByTestId('listing-dropdown');
        this.applicationSubmissionInputs = page.getByTestId('application-submission-inputs');
        this.invalidLic      = page.getByTestId('invalid-lic');
        this.applicationTitle = page.getByTestId('musician-application-title');
        this.applicationDescription = page.getByTestId('musician-application-description');
        this.successfulSubmission = page.getByTestId('successful-submission');
        this.successfulSubmissionAnon = page.getByTestId('successful-submission-anon');
        this.successfulSubmissionNewListing = page.getByTestId('successful-submission-new-listing');
        this.submitButton = page.locator('#submit-button-content').locator('.htmx-indicator-component-block-replace');
        this.messageTextarea = page.locator('textarea[name="applicant_message"]');
        this.listingDropdownButton = this.listingDropdown.locator('button');
        this.listingDropdownButtonLabel = this.listingDropdownButton.locator('span');
    }

    async navigateToApplication(applicationId, lic = '') {
        const query = lic ? `?lic=${lic}` : '';
        await super.navigate(`/musician-application/${applicationId}${query}`);
    }

    async selectListing(listingName) {
        await this.listingDropdownButton.click();
        await this.listingDropdown.locator('li').filter({ hasText: listingName }).click();
        await expect(this.listingDropdownButtonLabel).toContainText(listingName);
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

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
