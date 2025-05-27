<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? '';
$amount = intval($data['amount'] ?? 0);
$number = $data['number'] ?? '';
$trx_id = $data['trx_id'] ?? '';

if (!is_non_empty_string($userId) || $amount <= 0 || !is_non_empty_string($number) || !is_non_empty_string($trx_id)) {
    error("All fields are required.");
}

$deposits = read_json('../../json/deposits.json');
$newId = 'd' . (count($deposits) + 1);

$newDeposit = [
    "id" => $newId,
    "user_id" => $userId,
    "amount" => $amount,
    "number" => $number,
    "trx_id" => $trx_id,
    "status" => "pending"
];

$deposits[] = $newDeposit;
write_json('../../json/deposits.json', $deposits);

success("Deposit request submitted. Please wait for admin approval.");
?>
