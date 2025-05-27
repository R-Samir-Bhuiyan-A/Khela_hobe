<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';

// Optional: get status filter
$status = $_GET['status'] ?? null;

$tournaments = read_json('../../json/tournaments.json');

// Apply filter if provided
if ($status) {
    $filtered = array_filter($tournaments, function($t) use ($status) {
        return $t['status'] === $status;
    });
    success("Tournaments filtered by status.", array_values($filtered));
} else {
    success("All tournaments", $tournaments);
}
?>
