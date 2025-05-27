<?php
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function is_non_empty_string($str) {
    return isset($str) && is_string($str) && trim($str) !== '';
}
?>
