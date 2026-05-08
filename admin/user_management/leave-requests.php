<?php
/**
 * Moved to admin/leave-requests.php — redirect keeps old bookmarks working.
 */
$qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== ''
    ? ('?' . $_SERVER['QUERY_STRING'])
    : '';
header('Location: ../leave-requests.php' . $qs, true, 302);
exit;
