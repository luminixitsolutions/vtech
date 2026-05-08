<?php
include dirname(__DIR__) . '/config.php';
$conn->query("SET SESSION sql_mode = ''");
$r = $conn->query("SHOW COLUMNS FROM tbl_installations LIKE 'RmsIntegration7Days'");
if ($r && $r->num_rows) {
    echo "Columns already exist.\n";
    exit(0);
}
if (!$conn->query("ALTER TABLE tbl_installations ADD COLUMN RmsIntegration7Days VARCHAR(10) NOT NULL DEFAULT 'No' AFTER RmsIntegrationDate")) {
    die($conn->error);
}
if (!$conn->query("ALTER TABLE tbl_installations ADD COLUMN RmsIntegration90Days VARCHAR(10) NOT NULL DEFAULT 'No' AFTER RmsIntegration7Days")) {
    die($conn->error);
}
echo "OK\n";
