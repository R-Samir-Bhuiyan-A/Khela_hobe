<?php
function read_json($filepath) {
    if (!file_exists($filepath)) return [];
    $json = file_get_contents($filepath);
    return json_decode($json, true) ?: [];
}

function write_json($filepath, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filepath, $json);
}

function find_by_id($array, $id) {
    foreach ($array as $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            return $item;
        }
    }
    return null;
}

function update_json_item($filepath, $id, $newData) {
    $data = read_json($filepath);
    $updated = false;
    foreach ($data as &$item) {
        if (isset($item['id']) && $item['id'] === $id) {
            $item = $newData;
            $updated = true;
            break;
        }
    }
    if ($updated) {
        write_json($filepath, $data);
    }
    return $updated;
}
?>
