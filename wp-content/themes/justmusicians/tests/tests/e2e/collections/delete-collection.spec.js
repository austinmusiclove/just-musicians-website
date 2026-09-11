import { expect } from '@playwright/test';
import { test } from '../../../fixtures/fixtures.js';
import { createUser } from '../../../data/factories/user_factory.js';

test.describe('Collections - Delete collection', () => {

    let userId, collectionId, collectionName;

    test.beforeEach(async ({ collectionsPage, wpCli }) => {
        const user = createUser();
        userId = wpCli.createUser(user);
        collectionName = `Collection ${Date.now()}`;
        collectionId = wpCli.createCollection({ title: collectionName, authorId: userId });
        wpCli.trackPost(collectionId);

        await collectionsPage.login(user.email, user.password);
        await collectionsPage.navigate('/collections/');
    });

    test('deletes a collection from the collections page', async ({ collectionsPage, wpCli }) => {
        collectionsPage.page.on('dialog', dialog => dialog.accept());

        await collectionsPage.deleteCollection(collectionId);

        await expect(collectionsPage.getCollectionCard(collectionName)).not.toBeVisible();

        expect(wpCli.getPostField(collectionId, 'post_status')).toBe('trash');
        expect(wpCli.getUserMeta(userId, 'collections').map(String)).not.toContain(String(collectionId));
    });

    test('cancels a delete collection attempt from the collections page', async ({ collectionsPage, wpCli }) => {
        collectionsPage.page.on('dialog', dialog => dialog.dismiss());

        await collectionsPage.getDeleteCollectionButton(collectionId).click();

        await expect(collectionsPage.getCollectionCard(collectionName)).toBeVisible();
        expect(wpCli.getPostField(collectionId, 'post_status')).toBe('publish');
        expect(wpCli.getUserMeta(userId, 'collections').map(String)).toContain(String(collectionId));
    });
});