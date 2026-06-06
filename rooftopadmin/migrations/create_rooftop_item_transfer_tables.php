<?php
/**
 * One-time: php migrations/create_rooftop_item_transfer_tables.php
 */
include dirname(__DIR__) . '/config.php';

$sqlFile = dirname(__DIR__) . '/sql/rooftop_item_transfer_workflow_tables.sql';
if (!is_readable($sqlFile)) {
    fwrite(STDERR, "SQL file not found: $sqlFile\n");
    exit(1);
}

$sql = file_get_contents($sqlFile);
$parts = preg_split('/;\s*\n/', $sql);
foreach ($parts as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '' || strpos($stmt, '--') === 0) {
        continue;
    }
    if (!$conn->query($stmt)) {
        echo 'Error: ' . $conn->error . "\n";
        echo substr($stmt, 0, 120) . "...\n";
        exit(1);
    }
}

echo "Rooftop item transfer tables ensured.\n";
