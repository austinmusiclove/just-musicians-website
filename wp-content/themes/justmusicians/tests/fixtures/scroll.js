export async function waitForScrollToSettle(page) {
    await page.evaluate(() => new Promise(resolve => {
        let lastX = window.scrollX, lastY = window.scrollY;
        const tick = () => {
            const x = window.scrollX, y = window.scrollY;
            if (x !== lastX || y !== lastY) { lastX = x; lastY = y; requestAnimationFrame(tick); }
            else resolve();
        };
        requestAnimationFrame(tick);
    }));
}