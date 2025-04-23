<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function _delete_listing($args) {

    if (empty($args['post_id'])) {
        return new WP_Error(400, 'Cannot delete listing without post id');
    }

    // Delete post
    return wp_trash_post($args['post_id']);

}
