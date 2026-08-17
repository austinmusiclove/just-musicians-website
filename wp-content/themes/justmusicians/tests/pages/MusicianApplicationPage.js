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
        this.successfulSubmissionAnon = page.getByTestId('successful-submission-anon');
        this.successfulSubmissionNewListing = page.getByTestId('successful-submission-new-listing');
    }

    async navigateToApplication(applicationId, lic = '') {
        const query = lic ? `?lic=${lic}` : '';
        await super.navigate(`/musician-application/${applicationId}${query}`);
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
