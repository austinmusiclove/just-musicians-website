import { expect } from '@playwright/test';
import { ThemePage } from './ThemePage.js';

export class InquiryModalPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
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

    // Get a slide by its full data-testid (e.g., 'inquiry-slide-date')
    getSlide(testId) {
        return this.page.getByTestId(testId);
    }

    // Get the navy "Next" button for a specific slide
    nextBtn(slideTestId) {
        return this.getSlide(slideTestId).locator('button.bg-navy').filter({ hasText: /^Next$/ });
    }

    // Walks every slide of the modal and submits the inquiry form.
    // sendToSimilarMusicians is only passed when the inquiry targets a specific listing;
    async createInquiry({ date, postalCodePrefix, genres, ensembleSizes = [], eventName, eventDetails, sendToSimilarMusicians }) {
        // Date slide
        await this.dateInput.fill(date);
        await this.nextBtn('inquiry-slide-date').click();
        await expect(this.getSlide('inquiry-slide-location')).toBeVisible();

        // Location slide
        await this.locationInput.click();
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/wp-html/v1/location-search-options/') && resp.status() === 200
        );
        await this.locationInput.fill(postalCodePrefix);
        await responsePromise;
        await this.locationTarget.locator('li').first().waitFor({ timeout: 10000 });
        await this.locationTarget.locator('li').first().click();
        // Wait for location to be selected (navy Next becomes visible)
        await expect(this.nextBtn('inquiry-slide-location')).toBeVisible();
        await this.nextBtn('inquiry-slide-location').click();
        await expect(this.getSlide('inquiry-slide-budget')).toBeVisible();

        // Budget slide - default selection is "I'd like to get quotes from musicians"
        await expect(this.quotesRadio).toBeChecked();
        await this.nextBtn('inquiry-slide-budget').click();
        await expect(this.getSlide('inquiry-slide-genre')).toBeVisible();

        // Genre slide
        for (const genre of genres) {
            await this.getSlide('inquiry-slide-genre').getByLabel(genre, { exact: true }).check({ force: true });
        }
        await this.nextBtn('inquiry-slide-genre').click();
        await expect(this.getSlide('inquiry-slide-performers')).toBeVisible();

        // Performers slide
        for (const size of ensembleSizes) {
            await this.getSlide('inquiry-slide-performers').getByLabel(size, { exact: true }).check({ force: true });
        }
        await this.nextBtn('inquiry-slide-performers').click();
        await expect(this.getSlide('inquiry-slide-details')).toBeVisible();

        // Details slide
        await expect(this.eventNameInput).toBeVisible();
        await this.eventNameInput.fill(eventName);
        if (eventDetails) { await this.eventDetailsInput.fill(eventDetails); }
        const submitResponsePromise = this.page.waitForResponse(
            resp => resp.url().includes('/wp-html/v1/events/') && resp.request().method() === 'POST'
        );

        if (sendToSimilarMusicians === undefined) {
            // No target listing: the details slide submits the inquiry directly
            await this.getSlide('inquiry-slide-details').locator('button[type="submit"]').click();
        } else {
            // Target listing set: the details slide leads to a "similar musicians?" slide first
            await this.nextBtn('inquiry-slide-details').click();
            await expect(this.getSlide('inquiry-slide-competing-quotes')).toBeVisible();
            await expect(this.getSlide('inquiry-slide-competing-quotes').locator('input#send-me-quotes')).toBeVisible();
            const choice = sendToSimilarMusicians ? '#send-me-quotes' : '#manual-quotes';
            // the styled <span class="radio-buttons"> overlays the input, so force the check
            await this.page.locator(choice).check({ force: true });
            await this.getSlide('inquiry-slide-competing-quotes').locator('button[type="submit"]').click();
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

    async clickThankYouLink() {
        await expect(this.thankYouLink).toBeVisible();
        await this.thankYouLink.click();
        await this.page.waitForLoadState('domcontentloaded');
    }
}
