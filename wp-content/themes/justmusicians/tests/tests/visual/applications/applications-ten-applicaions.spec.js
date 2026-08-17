import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';
import { createApplication } from '../../../data/factories/application_factory.js';
import { wpCliCreateUser, wpCliGetUserId, wpCliDeleteUser, wpCliCreatePost, wpCliDeletePost } from '../../../data/wp_cli.js';


test.describe('Visual - Applications - Ten applicaitons', () => {

    let applicationAuthorUser;
    let applicationId;
    let applicationTitle;

    test.skip('Page one of user\'s applications are displayed', async ( {} ) => {});
    test.skip('Page two of user\'s applications are displayed after scrolling to the bottom', async ( {} ) => {});

});
