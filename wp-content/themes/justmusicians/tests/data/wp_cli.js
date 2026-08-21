import { execSync } from 'child_process';

const WP_PATH = '/Users/johnfilippone/Local\\ Sites/just-musicians/app/public';
const LISTING_THUMBNAIL_PATH = 'tests/data/files/test-image.png';

export function wpCliCreateUser(userData) {
    const output = execSync(
        `wp user create "${userData.email}" "${userData.email}" --role=subscriber --user_pass="${userData.password}" --first_name="${userData.firstName}" --last_name="${userData.lastName}" --path=${WP_PATH} --porcelain`,
        { encoding: 'utf-8' }
    );
    return output.trim();
}

export function wpCliGetUserId(userEmail) {
    const output = execSync(
        `wp user get "${userEmail}" --field=ID --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    return output.trim();
}

export function wpCliDeleteUser(userEmail) {
    try {
        execSync(
            `wp user delete "${userEmail}" --yes --path=${WP_PATH}`,
            { stdio: 'ignore' }
        );
    } catch (e) {}
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

export function wpCliGetUserMeta(userId, key) {
    const output = execSync(
        `wp user meta get ${userId} ${key} --format=json --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    return JSON.parse(output.trim());
}

export function wpCliGetLatestPostId(authorId, postType = 'listing', postStatus = 'publish') {
    const output = execSync(
        `wp post list --author=${authorId} --post_type=${postType} --post_status=${postStatus} --fields=ID --format=csv --orderby=date --order=desc --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    const lines = output.trim().split('\n');
    return lines.length > 1 ? lines[1].trim() : null;
}

export function wpCliGetLatestPostIdByType(postType = 'listing', postStatus = 'publish', { metaKey, metaValue } = {}) {
    let cmd = `wp post list --post_type=${postType} --post_status=${postStatus}`;
    if (metaKey && metaValue) {
        cmd += ` --meta_key=${metaKey} --meta_value="${metaValue}"`;
    }
    cmd += ` --fields=ID --format=csv --orderby=date --order=desc --path=${WP_PATH}`;
    const output = execSync(cmd, { encoding: 'utf-8' });
    const lines = output.trim().split('\n');
    return lines.length > 1 ? lines[1].trim() : null;
}

export function wpCliGetPostField(postId, field) {
    const output = execSync(
        `wp post get ${postId} --field=${field} --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    return output.trim();
}

export function wpCliGetPostUrl(postId) {
    const output = execSync(
        `wp post url ${postId} --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    return output.trim();
}

export function wpCliGetPostIdBySlug(slug, postType = 'application') {
    const output = execSync(
        `wp post list --name=${slug} --post_type=${postType} --field=ID --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    return output.trim() || null;
}

export function wpCliGetPostMeta(postId, key) {
    try {
        const output = execSync(
            `wp post meta get ${postId} ${key} --path=${WP_PATH}`,
            { encoding: 'utf-8' }
        );
        return output.trim();
    } catch (e) {
        return null;
    }
}

export function wpCliGetPostThumbnailId(postId) {
    return wpCliGetPostMeta(postId, '_thumbnail_id');
}

export function wpCliDeletePost(postId) {
    try {
        execSync(
            `wp post delete ${postId} --force --path=${WP_PATH}`,
            { stdio: 'ignore' }
        );
    } catch (e) {}
}

export function wpCliSetPostThumbnail(postId, imagePath) {
    const output = execSync(
        `wp media import ${imagePath} --post_id=${postId} --title="cover" --porcelain --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
    const attachmentId = output.trim();
    execSync(
        `wp post meta update ${postId} _thumbnail_id ${attachmentId} --path=${WP_PATH}`,
        { stdio: 'ignore' }
    );
    return attachmentId;
}

export function wpCliSetPostTerms(postId, taxonomy, terms) {
    const encodedTerms = Buffer.from(JSON.stringify(terms)).toString('base64');
    execSync(
        `wp eval "wp_set_object_terms(${postId}, json_decode(base64_decode('${encodedTerms}')), '${taxonomy}', false);" --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
}

// The listing location index is built on save_post before thumbnails/terms can be attached via cli; re-run it manually
export function wpCliIndexListing(postId) {
    execSync(
        `wp eval "hm_index_upsert_listing(${postId});" --path=${WP_PATH}`,
        { encoding: 'utf-8' }
    );
}

export function wpCliAddListingToUser(userId, listingId) {
    let listings = [];
    try {
        const current = execSync(
            `wp user meta get ${userId} listings --format=json --path=${WP_PATH}`,
            { encoding: 'utf-8' }
        ).trim();
        listings = JSON.parse(current);
    } catch {
        // User has no listings meta yet
    }
    if (!Array.isArray(listings)) listings = [];
    if (!listings.includes(String(listingId))) {
        listings.push(String(listingId));
    }
    execSync(
        `wp user meta update ${userId} listings '${JSON.stringify(listings)}' --format=json --path=${WP_PATH}`,
        { stdio: 'ignore' }
    );
}

// Creates a fully usable listing post from createListingPostData output: post, thumbnail,
// taxonomies, author's listings user meta, and the location/search index entry
export function wpCliCreateListing(listingData) {
    const listingId = wpCliCreatePost(listingData);
    wpCliSetPostThumbnail(listingId, LISTING_THUMBNAIL_PATH);
    const taxonomies = [
        ['genre', listingData.genres],
        ['ensemble_size', listingData.ensembleSizes],
    ];
    for (const [taxonomy, terms] of taxonomies) {
        if (Array.isArray(terms) && terms.length > 0) {
            wpCliSetPostTerms(listingId, taxonomy, terms);
        }
    }
    if (listingData.authorId) {
        wpCliAddListingToUser(listingData.authorId, listingId);
    }
    wpCliIndexListing(listingId);
    return listingId;
}

export function wpCliNotificationExists(userId, notificationType, subjectId) {
    try {
        const output = execSync(
            `wp db query "SELECT COUNT(*) FROM wp_notifications WHERE user_id = ${userId} AND notification_type = '${notificationType}' AND subject_id = ${subjectId}" --skip-column-names --path=${WP_PATH}`,
            { encoding: 'utf-8' }
        );
        return parseInt(output.trim(), 10) > 0;
    } catch {
        return false;
    }
}
