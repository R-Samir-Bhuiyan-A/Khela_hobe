<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? '';
$tournamentId = $data['tournament_id'] ?? '';

if (!is_non_empty_string($userId) || !is_non_empty_string($tournamentId)) {
    error("User ID and Tournament ID required.");
}

// Load data
$users = read_json('../../json/users.json');
$tournaments = read_json('../../json/tournaments.json');

$user = find_by_id($users, $userId);
$tournament = find_by_id($tournaments, $tournamentId);

if (!$user || !$tournament) error("Invalid user or tournament.");

date_default_timezone_set("Asia/Dhaka"); // adjust timezone
$currentTime = date("Y-m-d H:i:s");

if ($currentTime > $tournament['join_deadline']) {
    error("Joining time is over.");
}

if (count($tournament['joined_users']) >= $tournament['max_seats']) {
    error("Tournament is full.");
}

if ($user['wallet'] < $tournament['entry_fee']) {
    error("Insufficient wallet balance.");
}

// Already joined check
foreach ($tournament['joined_users'] as $entry) {
    if ($entry['user_id'] === $userId) {
        error("Already joined.");
    }
}

// Deduct wallet
$user['wallet'] -= $tournament['entry_fee'];

// Assign seat number
$seatNumber = count($tournament['joined_users']) + 1;
$tournament['joined_users'][] = [
    'user_id' => $userId,
    'seat_number' => $seatNumber
];

// Save updates
update_json_item('../../json/users.json', $userId, $user);
update_json_item('../../json/tournaments.json', $tournamentId, $tournament);

success("Joined tournament successfully.", [
    'seat_number' => $seatNumber
]);
?>
