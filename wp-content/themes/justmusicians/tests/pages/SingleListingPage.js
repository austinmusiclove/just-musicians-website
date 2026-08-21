import { ThemePage } from './ThemePage.js';

export class SingleListingPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.sendInquiryBtn = page.getByRole('button', { name: 'Send Inquiry' });
    }

    async navigate(url) {
        await super.navigate(url);
    }

    async sendInquiry() {
        await this.sendInquiryBtn.click();
    }
}
