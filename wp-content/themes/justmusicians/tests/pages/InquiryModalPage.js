import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class InquiryModalPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.sidebarGetStartedBtn = isMobile
            ? page.getByTestId('sidebar-get-started-mobile')
            : page.getByTestId('sidebar-get-started-desktop');
        this.inquiryModal      = page.locator('.popup-wrapper');
        this.dateInput         = page.locator('#inquiry-start-date-input');
        this.locationInput     = page.locator('#inquiry-location-input');
        this.locationTarget    = page.locator('#inquiry-location-input-target');
        this.quotesRadio       = page.locator('#budget_option_quotes');
        this.eventNameInput    = page.locator('input[name="event_name"]');
        this.eventDetailsInput = page.locator('textarea[name="event_details"]');
        this.thankYouHeading   = page.getByText('Thanks for submitting your request!');
        this.thankYouLink      = page.getByRole('link', { name: 'responses here.' });
    }

    // Exactly one slide of the inquiry modal is visible at any time
    get visibleSlide() {
        return this.page.locator('.slide:visible');
    }

    // Slides render two "Next" buttons (grey/inactive and navy/enabled); only the navy one advances the slide
    get nextBtn() {
        return this.visibleSlide.locator('button.bg-navy').filter({ hasText: /^Next$/ });
    }

    async openFromSidebar() {
        await expect(this.sidebarGetStartedBtn).toBeVisible();
        await this.sidebarGetStartedBtn.click();
        await expect(this.dateInput).toBeVisible();
    }

    // Walks every slide of the modal and submits the inquiry form.
    // sendToSimilarMusicians is only passed when the inquiry targets a specific listing;
    async createInquiry({ date, postalCodePrefix, genres, ensembleSizes = [], eventName, eventDetails, sendToSimilarMusicians }) {
        // Date slide
        await this.dateInput.fill(date);
        await this.nextBtn.click();
        await expect(this.locationInput).toBeVisible();

        // Location slide
        await this.locationInput.click();
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/wp-html/v1/location-search-options/') && resp.status() === 200
        );
        await this.locationInput.fill(postalCodePrefix);
        await responsePromise;
        await this.locationTarget.locator('li').first().waitFor({ timeout: 10000 });
        await this.locationTarget.locator('li').first().click();
        await this.nextBtn.click();

        // Budget slide - default selection is "I'd like to get quotes from musicians"
        await expect(this.quotesRadio).toBeChecked();
        await this.nextBtn.click();

        // Genre slide
        for (const genre of genres) {
            // the styled <span class="checkmark"> overlays the input, so force the check
            await this.visibleSlide.getByLabel(genre, { exact: true }).check({ force: true });
        }
        await this.nextBtn.click();

        // Performers slide
        for (const size of ensembleSizes) {
            await this.visibleSlide.getByLabel(size, { exact: true }).check({ force: true });
        }
        await this.nextBtn.click();

        // Details slide
        await expect(this.eventNameInput).toBeVisible();
        await this.eventNameInput.fill(eventName);
        if (eventDetails) { await this.eventDetailsInput.fill(eventDetails); }
        const submitResponsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/wp-html/v1/events/') && resp.request().method() === 'POST'
        );

        if (sendToSimilarMusicians === undefined) {
            // No target listing: the details slide submits the inquiry directly
            await this.visibleSlide.locator('button[type="submit"]').click();
        } else {
            // Target listing set: the details slide leads to a "similar musicians?" slide first
            await this.nextBtn.click();
            await expect(this.visibleSlide.locator('input#send-me-quotes')).toBeVisible();
            const choice = sendToSimilarMusicians ? '#send-me-quotes' : '#manual-quotes';
            // the styled <span class="radio-buttons"> overlays the input, so force the check
            await this.page.locator(choice).check({ force: true });
            await this.visibleSlide.locator('button[type="submit"]').click();
        }
        await submitResponsePromise;

        await this.expectThankYouSlide();
    }

    async expectThankYouSlide() {
        await expect(this.thankYouHeading).toBeVisible();
    }

    async getEventPermalink() {
        return this.thankYouLink.getAttribute('href');
    }
}
