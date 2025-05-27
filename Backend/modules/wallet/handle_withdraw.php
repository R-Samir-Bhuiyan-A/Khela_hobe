<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';

$data = json_decode(file_get_contents('php://input'), true);
$withdrawId = $data['withdraw_id'] ?? '';
$action = $data['action'] ?? ''; // "approve" or "reject"

if (!$withdrawId || !in_array($action, ['approve', 'reject'])) {
    error("Invalid parameters.");
}

$withdrawals = read_json('../../json/withdrawals.json');
$users = read_json('../../json/users.json');

$found = false;
foreach ($withdrawals as &$w) {
    if ($w['id'] === $withdrawId && $w['status'] === 'pending') {
        $userId = $w['user_id'];
        $amount = $w['amount'];
        
        foreach ($users as &$u) {
            if ($u['id'] === $userId) {
                if ($action === 'reject') {
                    // Refund amount
                    $u['wallet'] += $amount;
                }
                break;
            }
        }

        $w['status'] = $action === 'approve' ? 'approved' : 'rejected';
        $found = true;
        break;
    }
}

if (!$found) {
    error("Pending withdrawal not found.");
}

write_json('../../json/withdrawals.json', $withdrawals);
write_json('../../json/users.json', $users);

success("Withdrawal request has been {$w['status']}.");
?>
