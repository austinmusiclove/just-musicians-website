import { defineConfig, devices } from '@playwright/test';
//console.log(devices);

export default defineConfig({
    testDir: './tests/tests',
    timeout: 45000,
    expect: { timeout: 20000 },
    fullyParallel: false,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 2 : 0,
    workers: process.env.CI ? 1 : undefined,
    /* Include these options in ~/Library/Application Support/Local/lightning-services/php-8.3.23+0/conf/php-fpm.d/www.conf.hbs to increase local server resources
        pm = dynamic
        pm.max_children = 10
        pm.start_servers = 4
        pm.min_spare_servers = 2
        pm.max_spare_servers = 6
     */
    reporter: [['html', { outputFolder: 'playwright-report' }]],
    use: {
        baseURL: process.env.PLAYWRIGHT_TEST_BASE_URL || 'http://localhost',
        actionTimeout: 20000,
        trace: 'retain-on-failure',
        video: 'retain-on-failure',
    },
    projects: [
        {
            name: 'Desktop Chrome',
            use: { ...devices['Desktop Chrome'] },
        },
        {
            name: 'Desktop Firefox',
            use: { ...devices['Desktop Firefox'] },
        },
        {
            name: 'Desktop Safari',
            use: { ...devices['Desktop Safari'] },
        },
        {
            name: 'Mobile Chrome',
            use: { ...devices['Pixel 7'] },
        },
        {
            name: 'Mobile Safari',
            use: { ...devices['iPhone 14'] },
        },
    ],
});
