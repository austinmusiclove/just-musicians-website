
/**
 * Wraps an async function so that it runs exclusively — only one call can execute at a time.
 *
 * How it works:
 * - Maintains a `lock`, which is a promise chain that all function calls queue into.
 * - Each new call waits for the previous one to finish before executing.
 * - Calls are executed in the order they are made (FIFO).
 * - If a function call throws an error, it's caught to ensure the lock chain remains intact.
 *
 * Use case:
 * Useful when a function should not run concurrently — e.g. updating shared state or UI.
 *
 * Example:
 * const lockedFn = createLockedFunction(asyncTask);
 * lockedFn(); // runs immediately
 * lockedFn(); // runs after the first one finishes
 */
function createLockedFunction(fn) {
    let lock = Promise.resolve();

    return async function (...args) {
        const run = async () => {
            //console.log('running:', fn.name);
            await fn.apply(this, args);
        };
        const next = lock.then(() => run());
        lock = next.catch(() => {}); // prevent lock from breaking on error
        return next;
    };
}
