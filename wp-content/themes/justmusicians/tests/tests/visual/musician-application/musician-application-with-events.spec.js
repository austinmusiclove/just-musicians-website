import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplicationPost } from '../../../data/factories/application_factory.js';


test.describe('Visual - Musician Application - With Events', () => {

    test.skip('Displays events defined in the application events', async ({}) => {});
    test.skip('Does not display deleted event in the application events', async ({}) => {});
    test.skip('Does not display trashed event in the application events', async ({}) => {});
    test.skip('Does not display pending event in the application events', async ({}) => {});

});
