<?php
/**
 * One-time: php migrations/add_file_submission_reminder_tbl_option.php
 * Adds File submission reminder option (254); grants it to users who had legacy 161/162.
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncBuiltinOptionsToDb();

$updated = 0;
$rows = getList("SELECT id, Options FROM tbl_users WHERE Options IS NOT NULL AND TRIM(Options) <> '' AND Options <> '0'");
foreach ($rows as $row) {
    $parts = array_values(array_filter(array_map('trim', explode(',', (string) $row['Options']))));
    if ($parts === []) {
        continue;
    }
    $original = $parts;
    $hasLegacy = in_array('161', $parts, true) || in_array('162', $parts, true);
    if ($hasLegacy && !in_array('254', $parts, true)) {
        $parts[] = '254';
    }
    $parts = array_values(array_unique(array_filter($parts, function ($id) {
        return $id !== '161' && $id !== '162';
    })));
    if ($parts === $original) {
        continue;
    }
    $csv = $conn->real_escape_string(implode(',', $parts));
    $uid = (int) $row['id'];
    $conn->query("UPDATE tbl_users SET Options='$csv' WHERE id='$uid'");
    $updated++;
}

echo "File submission reminder option ensured (254). Users updated: $updated\n";
