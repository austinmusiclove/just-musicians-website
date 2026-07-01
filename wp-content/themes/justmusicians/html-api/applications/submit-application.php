<?php
$result = submit_application();
if ($result) {
    echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'Application Submitted Successfully\'})"></span>';
} else {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Application Submission Failed\'})"></span>';
}
