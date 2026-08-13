import { ThemePage } from './ThemePage.js';

export class MusicianApplicationPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.listingForm     = page.getByTestId('listing-form');
        this.listingDropdown = page.getByTestId('listing-dropdown');
        this.applicationSubmissionInputs = page.getByTestId('application-submission-inputs');
        this.invalidLpc      = page.getByTestId('invalid-lpc');
        this.applicationTitle = page.getByTestId('musician-application-title');
        this.applicationDescription = page.getByTestId('musician-application-description');
        this.successfulSubmissionAnon = page.getByTestId('successful-submission-anon');
        this.successfulSubmissionNewListing = page.getByTestId('successful-submission-new-listing');
    }

    async navigateByApplicationId(applicationId, lpc = '') {
        const query = lpc ? `?lpc=${lpc}` : '';
        await super.navigate(`/musician-application/${applicationId}${query}`);
    }

    async login(username, password) {
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
