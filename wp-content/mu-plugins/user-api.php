<?php
    // is current user email verified
    add_action('rest_api_init', function () {
        register_rest_route('user/v1', 'email-verified', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'is_email_verified'
        ));
    });
    function is_email_verified() {
        $email_verified = get_user_meta(wp_get_current_user()->ID, "email_verified");
        return $email_verified[0];
        if (!isset($email_verified) || is_null($email_verified) || !is_array($email_verified)) {
            return false;
        } else {
            return $email_verified[0];
        }
    }

    // get current user profiles
    add_action('rest_api_init', function () {
        register_rest_route('user/v1', 'profiles', array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'getUserProfiles'
        ));
    });
    function getUserProfiles() {
        $results = array();
        $user_profiles = get_user_meta(wp_get_current_user()->ID, "profiles");
        if (!isset($user_profiles) || is_null($user_profiles) || !is_array($user_profiles)) {
            return $results;
        } else {
            // for each profile get name
            for ($index = 0; $index < count($user_profiles[0]); $index++) {
                $profile_post_id = $user_profiles[0][$index];

                // if status not publish, skip
                $post_status = get_post_status($profile_post_id);
                if ($post_status != "publish") { continue; }

                $name = get_post_meta($profile_post_id, "name")[0];
                array_push($results, array(
                    "post_id" => $profile_post_id,
                    "name" => $name
                ));
            }
            return $results;
        }
    }
?>

