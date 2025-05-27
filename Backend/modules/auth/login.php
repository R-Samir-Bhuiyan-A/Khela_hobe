<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!is_valid_email($email) || !is_non_empty_string($password)) {
    error("Email and password are required.");
}

$users = read_json('../../json/users.json');

$matchedUser = null;
foreach ($users as $u) {
    if ($u['email'] === $email && password_verify($password, $u['password'])) {
        $matchedUser = $u;
        break;
    }
}

if (!$matchedUser) {
    error("Invalid credentials.");
}

// Remove password before sending
unset($matchedUser['password']);

success("Login successful.", $matchedUser);
?>
