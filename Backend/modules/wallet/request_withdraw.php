<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['user_id'] ?? '';
$amount = intval($data['amount'] ?? 0);
$paymentNumber = $data['payment_number'] ?? '';

if (!is_non_empty_string($userId) || $amount <= 0 || !is_non_empty_string($paymentNumber)) {
    error("All fields are required.");
}

if ($amount < 100) {
    error("Minimum withdrawal amount is 100.");
}

$users = read_json('../../json/users.json');
$withdrawals = read_json('../../json/withdrawals.json');

$user = null;
foreach ($users as &$u) {
    if ($u['id'] === $userId) {
        $user = &$u;
        break;
    }
}

if (!$user) {
    error("User not found.");
}

if ($user['wallet'] < $amount) {
    error("Insufficient wallet balance.");
}

// Deduct from wallet immediately (or you can choose to deduct on approval)
$user['wallet'] -= $amount;

// Add withdraw request
$newId = 'w' . (count($withdrawals) + 1);
$newWithdraw = [
    "id" => $newId,
    "user_id" => $userId,
    "amount" => $amount,
    "payment_number" => $paymentNumber,
    "status" => "pending"
];
$withdrawals[] = $newWithdraw;

// Save all changes
write_json('../../json/users.json', $users);
write_json('../../json/withdrawals.json', $withdrawals);

success("Withdrawal request submitted and is pending admin approval.");
?>
