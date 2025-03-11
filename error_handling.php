<?php
function handleError($error) {
    // Log detailed error
    error_log($error);
    // Return generic error message
    echo 'An error occurred. Please try again later.';
}
?>
