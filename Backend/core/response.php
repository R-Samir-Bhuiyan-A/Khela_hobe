<?php
function success($message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function error($message, $code = 400) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $message
    ]);
    exit;
}
?>
