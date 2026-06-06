<?php
include dirname(__DIR__) . '/config.php';
$conn->query("SET SESSION sql_mode = ''");
$r = $conn->query("SHOW COLUMNS FROM tbl_sell LIKE 'PumpCapacity'");
if ($r && $r->num_rows) {
    echo "PumpCapacity column already exists.\n";
    exit(0);
}
if (!$conn->query("ALTER TABLE tbl_sell ADD COLUMN PumpCapacity INT(11) NOT NULL DEFAULT 0 AFTER CustId")) {
    die($conn->error);
}
echo "OK\n";
