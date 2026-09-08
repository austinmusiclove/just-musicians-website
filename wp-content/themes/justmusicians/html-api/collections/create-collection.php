<?php

// Ensure the user is logged in
if (!is_user_logged_in()) {
    $message = 'Unauthorized: Must be logged in to create a collection';
    ?>
    <span x-init="$dispatch('error-toast', { 'message': '<?php echo $message; ?>'})"></span>
    <?php
    exit;
}

$collection_name = !empty($_POST['collection_name']) ? $_POST['collection_name'] : '';
$listing_id      = !empty($_POST['listing_id']) ? $_POST['listing_id'] : '';
$error_event     = !empty($_POST['error_event']) ? $_POST['error_event'] : 'error-toast';
$success_event   = !empty($_POST['success_event']) ? $_POST['success_event'] : 'success-toast';


// Create user collection
$result = create_user_collection($collection_name, $listing_id);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    ?>
    <span x-init="$dispatch('<?php echo $error_event; ?>', { 'message': '<?php echo $message; ?>'})"></span>
    <?php
    exit;
}

// Success Response
?>
<span x-init="$dispatch('<?php echo $success_event; ?>', { 'message': 'Collection Created Successfully' })"></span>
<span x-init="$dispatch('add-collection', {
    'post_id': '<?php echo $result['post_id']; ?>',
    'name': '<?php echo $result['name']; ?>',
    'access_level': 'owner',
    'listings': <?php echo clean_arr_for_doublequotes($result['listings']); ?>,
    'permalink': '<?php echo $result['permalink']; ?>'
})"></span>
<span x-init="$refs.newCollectionInput<?php echo $listing_id; ?>.value = '';"></span>
