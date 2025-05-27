<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);
$tournamentId = $data['tournament_id'] ?? '';
$newStatus = $data['new_status'] ?? '';

$validStatuses = ['upcoming', 'running', 'completed'];

if (!is_non_empty_string($tournamentId) || !in_array($newStatus, $validStatuses)) {
    error("Invalid tournament ID or status.");
}

$tournaments = read_json('../../json/tournaments.json');

$found = false;
foreach ($tournaments as &$t) {
    if ($t['id'] === $tournamentId) {
        $t['status'] = $newStatus;
        $found = true;
        break;
    }
}

if (!$found) error("Tournament not found.");

write_json('../../json/tournaments.json', $tournaments);
success("Tournament status updated to '$newStatus'.");
?>
