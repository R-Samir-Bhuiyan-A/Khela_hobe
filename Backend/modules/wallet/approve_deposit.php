<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';

$data = json_decode(file_get_contents('php://input'), true);
$depositId = $data['deposit_id'] ?? '';

$deposits = read_json('../../json/deposits.json');
$users = read_json('../../json/users.json');

// Find deposit
$found = false;
foreach ($deposits as &$d) {
    if ($d['id'] === $depositId && $d['status'] === 'pending') {
        $userId = $d['user_id'];
        $amount = $d['amount'];
        $user = find_by_id($users, $userId);

        if (!$user) error("User not found.");

        $user['wallet'] += $amount;
        $d['status'] = 'approved';

        update_json_item('../../json/users.json', $userId, $user);
        $found = true;
        break;
    }
}
if (!$found) error("Pending deposit not found.");

write_json('../../json/deposits.json', $deposits);
success("Deposit approved and wallet updated.");
?>
