<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';

$tournamentId = $_GET['tournament_id'] ?? '';
if (!$tournamentId) {
    error("Tournament ID is required.");
}

$tournaments = read_json('../../json/tournaments.json');
$users = read_json('../../json/users.json');

$tournament = null;
foreach ($tournaments as $t) {
    if ($t['id'] === $tournamentId) {
        $tournament = $t;
        break;
    }
}

if (!$tournament) error("Tournament not found.");

$joined = $tournament['joined_users'] ?? [];

$result = [];
foreach ($joined as $join) {
    $user = null;
    foreach ($users as $u) {
        if ($u['id'] === $join['user_id']) {
            $user = $u;
            break;
        }
    }
    if ($user) {
        $result[] = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'seat_no' => $join['seat_no']
        ];
    }
}

success("Joined users list", $result);
?>
