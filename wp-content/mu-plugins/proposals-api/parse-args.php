<?php
if (!defined('ABSPATH')) { exit; }

function get_sanitized_proposal_args() {
    $sanitized_args = [];

    if (isset($_POST['post_id']))      { $sanitized_args['ID']                         = sanitize_text_field($_POST['post_id']); }
    if (isset($_POST['status']))       { $sanitized_args['meta_input']['status']       = sanitize_text_field($_POST['status']); }
    if (isset($_POST['availability'])) { $sanitized_args['meta_input']['availability'] = sanitize_text_field($_POST['availability']); }
    if (isset($_POST['details']))      { $sanitized_args['meta_input']['details']      = sanitize_textarea_field($_POST['details']); }
    if (isset($_POST['quote']))        { $sanitized_args['meta_input']['quote']        = sanitize_text_field($_POST['quote']); }
    if (isset($_POST['draw']))         { $sanitized_args['meta_input']['draw']         = sanitize_text_field($_POST['draw']); }

    return $sanitized_args;
}
