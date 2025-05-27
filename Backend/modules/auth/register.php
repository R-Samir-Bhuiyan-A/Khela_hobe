<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!is_non_empty_string($username) || !is_valid_email($email) || !is_non_empty_string($password)) {
    error("Invalid input. Username, email and password are required.");
}

$users = read_json('../../json/users.json');

// Check if email already exists
foreach ($users as $u) {
    if ($u['email'] === $email) {
        error("Email already registered.");
    }
}

$newId = 'u' . (count($users) + 1);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$newUser = [
    "id" => $newId,
    "username" => $username,
    "email" => $email,
    "password" => $hashedPassword,
    "wallet" => 0
];

$users[] = $newUser;
write_json('../../json/users.json', $users);

success("Registered successfully.", [
    "user_id" => $newId
]);
?>
