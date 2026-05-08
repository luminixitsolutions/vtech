<?php
/**
 * Calendar days of [from,to] that fall in the given Y-m.
 */
function leave_request_days_in_month($fromYmd, $toYmd, $year, $month) {
    if (!$fromYmd || !$toYmd) {
        return 0;
    }
    $m = str_pad((string) (int) $month, 2, '0', STR_PAD_LEFT);
    $y = (int) $year;
    $monthStart = sprintf('%04d-%s-01', $y, $m);
    if (!strtotime($monthStart)) {
        return 0;
    }
    $monthEnd = date('Y-m-t', strtotime($monthStart));
    if (strcmp($toYmd, $fromYmd) < 0) {
        return 0;
    }
    if (strcmp($toYmd, $monthStart) < 0 || strcmp($fromYmd, $monthEnd) > 0) {
        return 0;
    }
    $a = strcmp($fromYmd, $monthStart) > 0 ? $fromYmd : $monthStart;
    $b = strcmp($toYmd, $monthEnd) < 0 ? $toYmd : $monthEnd;
    $t1 = strtotime($a);
    $t2 = strtotime($b);
    if ($t1 === false || $t2 === false || $t1 > $t2) {
        return 0;
    }
    return (int) floor(($t2 - $t1) / 86400) + 1;
}

function leave_request_inclusive_span_days($fromYmd, $toYmd) {
    if (!$fromYmd || !$toYmd) {
        return 0;
    }
    if (strcmp($toYmd, $fromYmd) < 0) {
        return 0;
    }
    $t1 = strtotime($fromYmd);
    $t2 = strtotime($toYmd);
    if ($t1 === false || $t2 === false) {
        return 0;
    }
    return (int) floor(($t2 - $t1) / 86400) + 1;
}
