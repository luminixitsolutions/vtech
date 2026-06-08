<?php
/**
 * One-time: php migrations/add_transportor_account.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-menu-option-groups.php';
require_once __DIR__ . '/../inc-menu-access-granular-options.php';

menuAccessSyncGranularOptionsToDb();

$labels = menuAccessBuiltinOptionLabels();
$nameEsc = $conn->real_escape_string($labels[255]);
$conn->query("INSERT INTO tbl_options (id, Name) VALUES (255, '$nameEsc')
    ON DUPLICATE KEY UPDATE Name = '$nameEsc'");

$conn->query("INSERT INTO tbl_user_type (id, Name, Status) VALUES (46, 'Transportor', '1')
    ON DUPLICATE KEY UPDATE Name = 'Transportor', Status = '1'");

$res = $conn->query("SELECT id, Options FROM tbl_users WHERE Roll IN (1, 7) OR Options LIKE '%116%' OR Options LIKE '%255%'");
while ($row = $res->fetch_assoc()) {
    $opts = array_filter(array_map('trim', explode(',', (string) $row['Options'])));
    foreach (['255', '256', '257'] as $opt) {
        if (!in_array($opt, $opts, true)) {
            $opts[] = $opt;
        }
    }
    $optStr = $conn->real_escape_string(implode(',', $opts));
    $uid = (int) $row['id'];
    $conn->query("UPDATE tbl_users SET Options='$optStr' WHERE id=$uid");
}

echo "Transportor account options synced.\n";
