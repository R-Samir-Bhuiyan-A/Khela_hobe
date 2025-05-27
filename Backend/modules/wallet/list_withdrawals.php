<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';

$status = $_GET['status'] ?? null; // e.g. pending, approved, rejected
$userId = $_GET['user_id'] ?? null; // optional: to filter withdrawals by user

$withdrawals = read_json('../../json/withdrawals.json');

if ($status) {
    $withdrawals = array_filter($withdrawals, function($w) use ($status) {
        return $w['status'] === $status;
    });
}

if ($userId) {
    $withdrawals = array_filter($withdrawals, function($w) use ($userId) {
        return $w['user_id'] === $userId;
    });
}

withdrawals = array_values($withdrawals); // reindex

success("Withdrawals list", $withdrawals);
?>
