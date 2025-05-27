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

$tournaments = read_json('../../json/tournaments.json');
$tournament = find_by_id($tournaments, $tournamentId);

if (!$tournament) error("Tournament not found.");

date_default_timezone_set("Asia/Dhaka");
$currentTime = date("Y-m-d H:i:s");

if ($currentTime < $tournament['start_time']) {
    error("Tournament has not started yet.");
}

// Check if user joined
$isJoined = false;
foreach ($tournament['joined_users'] as $entry) {
    if ($entry['user_id'] === $userId) {
        $isJoined = true;
        break;
    }
}

if (!$isJoined) {
    error("You are not a participant in this tournament.");
}

// Return room details
success("Room info", [
    'room_id' => $tournament['room_id'],
    'room_password' => $tournament['room_password']
]);
?>
