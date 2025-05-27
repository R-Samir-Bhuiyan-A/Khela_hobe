<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);

// Basic validation
$title = $data['title'] ?? '';
$entry_fee = intval($data['entry_fee'] ?? 0);
$max_seats = intval($data['max_seats'] ?? 0);
$join_deadline = $data['join_deadline'] ?? '';
$start_time = $data['start_time'] ?? '';
$room_id = $data['room_id'] ?? '';
$room_password = $data['room_password'] ?? '';

if (
    !is_non_empty_string($title) || $entry_fee <= 0 || $max_seats <= 0 ||
    !is_non_empty_string($join_deadline) || !is_non_empty_string($start_time) ||
    !is_non_empty_string($room_id) || !is_non_empty_string($room_password)
) {
    error("All fields are required and must be valid.");
}

// Read and create ID
$tournaments = read_json('../../json/tournaments.json');
$newId = 't' . (count($tournaments) + 1);

// Create tournament object
$newTournament = [
    "id" => $newId,
    "title" => $title,
    "entry_fee" => $entry_fee,
    "max_seats" => $max_seats,
    "joined_users" => [],
    "join_deadline" => $join_deadline,
    "start_time" => $start_time,
    "room_id" => $room_id,
    "room_password" => $room_password,
    "status" => "upcoming"
];

// Save
$tournaments[] = $newTournament;
write_json('../../json/tournaments.json', $tournaments);

success("Tournament created successfully.", ["tournament_id" => $newId]);
?>
