<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';

$deposits = read_json('../../json/deposits.json');

success("All deposit requests", $deposits);
?>
