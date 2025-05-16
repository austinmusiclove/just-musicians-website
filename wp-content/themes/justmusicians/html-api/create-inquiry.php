
<?php


//$collection_name = !empty($_POST['collection_name']) ? $_POST['collection_name'] : '';
//$listing_id      = !empty($_POST['listing_id']) ? $_POST['listing_id'] : '';


// Create Inquiry
/*
$result = create_user_inquiry();
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}
*/

// Success Response
echo '<span x-init="_handleCreateInquirySuccess()"></span>';
//echo '<span x-init="_handleCreateInquiryError()"></span>';
