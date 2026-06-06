<?php

/**
 * Employee menu access (tbl_users.Options) — sanitize against tbl_options and
 * apply roll-based defaults from peers who already have access configured.
 */
require_once __DIR__ . '/inc-menu-option-groups.php';

/** @return int[] */
function employeeMenuAllValidOptionIds()
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $rows = getList('SELECT id FROM tbl_options ORDER BY id');
    $cache = [];
    foreach ($rows as $row) {
        $cache[(int) $row['id']] = true;
    }
    foreach (getMenuOptionIdsFromGroupsOnly() as $id) {
        $cache[(int) $id] = true;
    }
    foreach (array_keys(menuAccessBuiltinOptionLabels()) as $id) {
        $cache[(int) $id] = true;
    }
    $cache = array_keys($cache);
    sort($cache, SORT_NUMERIC);
    return $cache;
}

/**
 * @param string|array|null $raw Comma-separated string or Options[] from POST
 * @return string Comma-separated option ids or '0'
 */
function employeeMenuSanitizeOptionsString($raw)
{
    if ($raw === null || $raw === '' || $raw === '0') {
        return '0';
    }
    if (is_array($raw)) {
        $parts = $raw;
    } else {
        $parts = explode(',', (string) $raw);
    }
    $valid = array_flip(employeeMenuAllValidOptionIds());
    $ids = [];
    foreach ($parts as $part) {
        $id = (int) trim((string) $part);
        if ($id > 0 && isset($valid[$id])) {
            $ids[$id] = $id;
        }
    }
    if (empty($ids)) {
        return '0';
    }
    ksort($ids, SORT_NUMERIC);

    return implode(',', $ids);
}

/**
 * Default Options for a roll: copy from an active user with the same roll who
 * already has the fullest access configured (longest Options list).
 *
 * @return string Comma-separated ids or '0'
 */
function employeeMenuDefaultOptionsForRoll($roll)
{
    global $conn;
    $roll = (int) $roll;
    if ($roll <= 0) {
        return '0';
    }
    $rollEsc = $conn->real_escape_string((string) $roll);
    $sql = "SELECT Options FROM tbl_users
        WHERE Roll='$rollEsc' AND Status='1'
          AND Options IS NOT NULL AND TRIM(Options) != '' AND Options != '0'
        ORDER BY LENGTH(Options) DESC, id ASC
        LIMIT 1";
    $row = getRecord($sql);
    if (empty($row['Options'])) {
        return employeeMenuFallbackOptionsForRoll($roll);
    }

    return employeeMenuSanitizeOptionsString($row['Options']);
}

/**
 * Minimal access when no peer template exists for the roll.
 *
 * @return string
 */
function employeeMenuFallbackOptionsForRoll($roll)
{
    $roll = (int) $roll;
    $map = [
        6 => [55, 79, 10, 14],
        12 => [55, 10, 14],
        26 => [60, 71, 82, 83, 86, 165, 183, 26, 10, 14],
        27 => [58, 70, 166, 165, 183, 10, 14],
        28 => [25, 26, 28, 135, 137, 10, 14],
        30 => [28, 135, 137, 136, 10, 14],
        31 => [168, 169, 170, 171, 172, 173, 10, 14],
        43 => [18, 10, 14],
        44 => [44, 45, 46, 10, 14],
        45 => [44, 45, 46, 10, 14],
    ];
    if (!isset($map[$roll])) {
        return '0';
    }

    return employeeMenuSanitizeOptionsString($map[$roll]);
}

/**
 * Selected options for add/edit account forms (saved access or roll defaults only).
 *
 * @param array $selectedOptions
 * @return string[]
 */
function employeeMenuOptionsForAccountForm(array $selectedOptions, $roll)
{
    $roll = (int) $roll;
    if (adminUserHasFullMenuAccess($roll)) {
        return array_map('strval', getMenuOptionIdsFlat());
    }

    $selectedOptions = array_values(array_filter($selectedOptions, function ($v) {
        return $v !== '' && $v !== null && $v !== '0';
    }));

    if (empty($selectedOptions) && $roll > 0) {
        $csv = employeeMenuDefaultOptionsForRoll($roll);
        if ($csv === '0') {
            return [];
        }

        return array_map('strval', explode(',', $csv));
    }

    $csv = employeeMenuSanitizeOptionsString($selectedOptions);
    if ($csv === '0') {
        return [];
    }

    return array_map('strval', explode(',', $csv));
}

/**
 * Resolve Options for save: use POST when provided; else keep existing on edit;
 * else apply roll template.
 *
 * @param string|array|null $postedOptions
 * @return string
 */
function employeeMenuResolveOptionsForSave($postedOptions, $roll, $existingUserId = 0, $menuAccessPosted = false)
{
    $roll = (int) $roll;
    if (adminUserHasFullMenuAccess($roll)) {
        return employeeMenuSanitizeOptionsString(array_map('strval', getMenuOptionIdsFlat()));
    }

    if ($menuAccessPosted) {
        return employeeMenuSanitizeOptionsString($postedOptions);
    }

    $posted = employeeMenuSanitizeOptionsString($postedOptions);
    if ($posted !== '0') {
        return $posted;
    }

    $existingUserId = (int) $existingUserId;
    if ($existingUserId > 0) {
        $row = getRecord("SELECT Options, Roll FROM tbl_users WHERE id='$existingUserId'");
        if (!empty($row)) {
            $existing = employeeMenuSanitizeOptionsString($row['Options'] ?? '0');
            if ($existing !== '0') {
                return $existing;
            }
            if ($roll <= 0 && !empty($row['Roll'])) {
                $roll = (int) $row['Roll'];
            }
        }
    }

    $roll = (int) $roll;
    if ($roll > 0) {
        $defaults = employeeMenuDefaultOptionsForRoll($roll);
        if ($defaults !== '0') {
            return $defaults;
        }
    }

    return '0';
}

/** @return int[] */
function employeeMenuDefaultOptionIdsForRoll($roll)
{
    $roll = (int) $roll;
    if (adminUserHasFullMenuAccess($roll)) {
        return array_map('intval', getMenuOptionIdsFlat());
    }
    $csv = employeeMenuDefaultOptionsForRoll($roll);
    if ($csv === '0') {
        return [];
    }

    return array_map('intval', explode(',', $csv));
}

/**
 * Backfill tbl_users.Options for employees missing access (same roll template).
 *
 * @return array{updated:int, skipped:int, errors:string[]}
 */
function employeeMenuSyncMissingEmployeeOptions()
{
    global $conn;
    $excludeRolls = '1,3,4,5,9,10,8,11,34,35,36,37,39,40,26,27,42';
    $sql = "SELECT id, Roll, Options FROM tbl_users
        WHERE Roll NOT IN($excludeRolls)
          AND (Options IS NULL OR TRIM(Options) = '' OR Options = '0')
        ORDER BY id";
    $rows = getList($sql);
    $updated = 0;
    $skipped = 0;
    $errors = [];

    foreach ($rows as $row) {
        $uid = (int) $row['id'];
        $roll = (int) ($row['Roll'] ?? 0);
        $resolved = employeeMenuResolveOptionsForSave('0', $roll, 0);
        if ($resolved === '0') {
            $skipped++;
            continue;
        }
        $resolvedEsc = $conn->real_escape_string($resolved);
        if (!$conn->query("UPDATE tbl_users SET Options='$resolvedEsc' WHERE id='$uid'")) {
            $errors[] = "id $uid: " . $conn->error;
            continue;
        }
        $updated++;
    }

    return ['updated' => $updated, 'skipped' => $skipped, 'errors' => $errors];
}
