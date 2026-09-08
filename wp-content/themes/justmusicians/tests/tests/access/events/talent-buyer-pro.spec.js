import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('Access - Talent Buyer Pro - Event Limits', () => {

    const pad = n => String(n).padStart(2, '0');

    function monthDates() {
        const now = new Date();
        const currentMonthLastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 15);
        return {
            currentMonthDate: `${currentMonthLastDay.getFullYear()}-${pad(currentMonthLastDay.getMonth() + 1)}-${pad(currentMonthLastDay.getDate())}`,
            nextMonthDate: `${nextMonth.getFullYear()}-${pad(nextMonth.getMonth() + 1)}-${pad(nextMonth.getDate())}`,
        };
    }

    test('free users limited to 4 events per month; adding Pro unlocks unlimited', async ({ themePage, eventFormPage, myEventsPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        const { currentMonthDate, nextMonthDate } = monthDates();

        // 3 events in the current month + 1 event in a different month via CLI
        wpCli.createPost({ postType: 'event', title: 'CLI Event A', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event A', start_date: currentMonthDate } });
        wpCli.createPost({ postType: 'event', title: 'CLI Event B', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event B', start_date: currentMonthDate } });
        wpCli.createPost({ postType: 'event', title: 'CLI Event C', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event C', start_date: currentMonthDate } });
        wpCli.createPost({ postType: 'event', title: 'CLI Event Other Month', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event Other Month', start_date: nextMonthDate } });

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();

        // 4th event in the current month is allowed for a free user
        await eventFormPage.navigate('/event-form/');
        await eventFormPage.fillForm('Free Event 4', currentMonthDate);
        await eventFormPage.submitEvent();
        await eventFormPage.waitForSubmitRedirect();

        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(5);

        // 5th event in the current month is rejected for a free user
        await eventFormPage.navigate('/event-form/');
        await eventFormPage.fillForm('Free Event 5', currentMonthDate);
        await eventFormPage.submitEvent();
        await expect(eventFormPage.page).toHaveURL(/\/event-form\/$/);

        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(5);

        // Adding Pro allows the 5th event in the current month
        wpCli.addCap(userId, 'hm_buyer_pro');
        await eventFormPage.navigate('/event-form/');
        await eventFormPage.fillForm('Pro Event 5', currentMonthDate);
        await eventFormPage.submitEvent();
        await eventFormPage.waitForSubmitRedirect();

        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(6);
    });

    test('deleting one event lets a free user create another in the same month', async ({ themePage, eventFormPage, myEventsPage, singleEventPage, wpCli }) => {
        const user = createUser();
        const userId = wpCli.createUser(user);
        const { currentMonthDate } = monthDates();

        // 4 events in the current month via CLI
        wpCli.createPost({ postType: 'event', title: 'CLI Event 1', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event 1', start_date: currentMonthDate } });
        wpCli.createPost({ postType: 'event', title: 'CLI Event 2', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event 2', start_date: currentMonthDate } });
        wpCli.createPost({ postType: 'event', title: 'CLI Event 3', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event 3', start_date: currentMonthDate } });
        wpCli.createPost({ postType: 'event', title: 'CLI Event 4', status: 'publish', authorId: userId, meta: { event_name: 'CLI Event 4', start_date: currentMonthDate } });

        await themePage.navigate('/');
        await themePage.login(user.email, user.password);
        await themePage.expectLoggedInPage();

        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(4);

        // 5th event in the current month is rejected for a free user
        await eventFormPage.navigate('/event-form/');
        await eventFormPage.fillForm('Free Event 5', currentMonthDate);
        await eventFormPage.submitEvent();
        await expect(eventFormPage.page).toHaveURL(/\/event-form\/$/);

        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(4);

        // Delete one event from the front end
        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        await myEventsPage.getManageEventBtn(myEventsPage.eventCard('CLI Event 1')).click();
        await singleEventPage.deleteEvent();

        // Only 3 published events remain
        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(3);

        // The free user can now create another event in the same month
        await eventFormPage.navigate('/event-form/');
        await eventFormPage.fillForm('Replacement Event', currentMonthDate);
        await eventFormPage.submitEvent();
        await eventFormPage.waitForSubmitRedirect();

        await myEventsPage.navigate('/my-events/');
        await myEventsPage.waitForResults();
        expect(await myEventsPage.countEvents()).toBe(4);
    });

});
