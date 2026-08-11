import { ThemePage } from './ThemePage.js';

export class MusicianApplicationPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.listingForm     = page.getByTestId('listing-form');
        this.listingDropdown = page.getByTestId('listing-dropdown');
        this.applicationSubmissionInputs = page.getByTestId('application-submission-inputs');
    }

    async navigateByApplicationId(applicationId) {
        await super.navigate(`/musician-application/${applicationId}`);
    }

    async login(username, password) {
        await super.login(username, password);
        await super.expectLoggedInPage();
    }
}
