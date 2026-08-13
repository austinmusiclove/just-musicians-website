import { execSync } from 'child_process';

const WP_PATH = '/Users/johnfilippone/Local\\ Sites/just-musicians/app/public';

export function wpCliCreateUser(userData) {
    execSync(
        `wp user create "${userData.email}" "${userData.email}" --role=subscriber --user_pass="${userData.password}" --first_name="${userData.firstName}" --last_name="${userData.lastName}" --path=${WP_PATH}`,
        { stdio: 'ignore' }
    );
}

export function wpCliGetUserId(userEmail) {
    const output = execSync(
        `wp user get "${userEmail}" --field=ID --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    return output.trim();
}

export function wpCliDeleteUser(userEmail) {
    execSync(
        `wp user delete "${userEmail}" --yes --path=${WP_PATH}`,
        { stdio: 'ignore' }
    );
}

export function wpCliSetUserMeta(userEmail, key, value) {
    execSync(
        `wp user meta update "${userEmail}" "${key}" '${JSON.stringify(value)}' --format=json --path=${WP_PATH}`,
        { stdio: 'ignore' }
    );
}

export function wpCliDeleteUsers(userEmails) {
    if (userEmails.length) {
        execSync(
            `wp user delete ${userEmails.join(' ')} --yes --path=${WP_PATH}`,
            { stdio: 'ignore' }
        );
    }
}

export function wpCliCreatePost({ postType, title, status = 'publish', authorId, meta = {} }) {
    const metaJson = JSON.stringify(meta).replace(/'/g, `'\\''`);
    const output = execSync(
        `wp post create --post_type=${postType} --post_title="${title}" --post_status=${status} --post_author=${authorId} --meta_input='${metaJson}' --path=${WP_PATH} --porcelain`,
        { encoding: 'utf-8' }
    );
    return output.trim();
}

export function wpCliDeletePost(postId) {
    execSync(
        `wp post delete ${postId} --force --path=${WP_PATH}`,
        { stdio: 'ignore' }
    );
}
