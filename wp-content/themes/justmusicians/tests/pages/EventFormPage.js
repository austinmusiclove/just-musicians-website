import { ThemePage } from './ThemePage.js';

export class EventFormPage extends ThemePage {
    constructor(page, isMobile = false) {
        super(page, isMobile);
        this.form              = page.locator('form').filter({ has: page.getByRole('button', { name: 'Create Event' }) });
        this.titleInput        = this.form.locator('input[name="event_name"]');
        this.startDateInput    = this.form.locator('input[name="event_start_date"]');
        this.genreCheckbox     = this.form.locator('input[name="event_genres[]"][type="checkbox"]').first();
        this.cityInput         = this.form.locator('#edit-event-city');
        this.citySearchResults = this.form.locator('#edit-event-city-target li');
        this.submitBtn         = this.form.getByRole('button', { name: 'Create Event' });
    }

    async navigate(url = '/event-form/') {
        await super.navigate(url);
    }

    async login(username, password) {
        await this.navigate('/');
        await super.login(username, password);
        await super.expectLoggedInPage();
    }

    async selectLocation(city = 'Austin') {
        await this.cityInput.click();
        await this.cityInput.type(city);
        const result = this.citySearchResults.first();
        await result.waitFor({ state: 'visible', timeout: 15000 });
        await result.locator('span').click();
    }

    async fillForm(name, startDate) {
        await this.titleInput.fill(name);
        await this.startDateInput.fill(startDate);
        await this.genreCheckbox.check({ force: true });
        await this.selectLocation();
    }

    async submitEvent() {
        const responsePromise = this.page.waitForResponse(
            resp => resp.url().includes('wp-html/v1/events') && resp.request().method() === 'POST' && resp.status() === 200
        );
        await this.submitBtn.click();
        await responsePromise;
    }

    async waitForSubmitRedirect() {
        await this.page.waitForURL(url => url.searchParams.get('toast') === 'create', { timeout: 15000 });
        const pathParts = this.page.url().split('?')[0].replace(/\/$/, '').split('/').filter(Boolean);
        return pathParts[pathParts.length - 1];
    }
}
