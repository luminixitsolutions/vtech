<?php
/**
 * One-time: php migrations/add_item_transfer_tbl_options.php
 */
include dirname(__DIR__) . '/config.php';

$options = [
    [72, 'Item Transfer Workflow (legacy)'],
    [165, 'Transfer Item Dispatch to Store'],
    [166, 'Transfer Item Store to Store'],
    [183, 'Serial No Location Report'],
];

foreach ($options as $row) {
    list($id, $name) = $row;
    $id = (int) $id;
    $name = $conn->real_escape_string($name);
    $conn->query("INSERT IGNORE INTO tbl_options (id, Name) VALUES ($id, '$name')");
}

echo "Item transfer / serial report options ensured (72, 165, 166, 183).\n";
