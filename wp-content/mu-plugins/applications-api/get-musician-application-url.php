<?php

function get_musician_application_url($application_id) {
    return site_url('/musician-application/' . $application_id . '/');
}

function get_musician_application_embed_url($application_id) {
    return site_url('/musician-application/' . $application_id . '/embed/');
}

function get_musician_application_embed_code($application_id) {
    $url = get_musician_application_embed_url($application_id);
    return '<iframe src="' . esc_url($url) . '" width="100%" height="1100" style="border:none;" loading="lazy" allowfullscreen></iframe>';
}
