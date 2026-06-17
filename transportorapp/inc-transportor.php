<?php

function transportorDriverIdsSql($transportorId)
{
    global $conn;
    $transportorId = (int) $transportorId;
    if ($transportorId <= 0) {
        return '0';
    }
    $ids = [];
    $res = $conn->query("SELECT id FROM tbl_users WHERE UnderUser='$transportorId' AND Roll=39 AND Status=1");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int) $row['id'];
        }
    }
    return $ids ? implode(',', $ids) : '0';
}

function transportorTripWhere($transportorId, $extra = '')
{
    $driverIds = transportorDriverIdsSql($transportorId);
    $where = "DriverId IN ($driverIds)";
    if ($extra !== '') {
        $where .= " AND $extra";
    }
    return $where;
}

function transportorUserLatLong($userRow)
{
    if (!is_array($userRow)) {
        return ['lat' => '', 'lng' => ''];
    }

    return [
        'lat' => trim((string) ($userRow['Lattitude'] ?? '')),
        'lng' => trim((string) ($userRow['Longitude'] ?? '')),
    ];
}

function transportorResolveTripLatLong($savedLat, $savedLng, $primaryUserRow, $fallbackUserRow)
{
    $savedLat = trim((string) $savedLat);
    $savedLng = trim((string) $savedLng);
    if ($savedLat !== '') {
        return ['lat' => $savedLat, 'lng' => $savedLng];
    }

    $coords = transportorUserLatLong($primaryUserRow);
    if ($coords['lat'] !== '' || $coords['lng'] !== '') {
        return $coords;
    }

    return transportorUserLatLong($fallbackUserRow);
}

function transportorGetDriver($transportorId, $driverId)
{
    global $conn;
    $transportorId = (int) $transportorId;
    $driverId = (int) $driverId;
    if ($transportorId <= 0 || $driverId <= 0) {
        return null;
    }
    $res = $conn->query("SELECT * FROM tbl_users WHERE id='$driverId' AND UnderUser='$transportorId' AND Roll=39 AND Status=1 LIMIT 1");
    if (!$res) {
        return null;
    }
    $row = $res->fetch_assoc();
    return $row ?: null;
}

function transportorOwnsTrip($transportorId, $tripId)
{
    global $conn;
    $transportorId = (int) $transportorId;
    $tripId = (int) $tripId;
    if ($transportorId <= 0 || $tripId <= 0) {
        return false;
    }
    $driverIds = transportorDriverIdsSql($transportorId);
    $res = $conn->query("SELECT id FROM tbl_trip_details WHERE id='$tripId' AND DriverId IN ($driverIds) LIMIT 1");
    return $res && $res->num_rows > 0;
}

function transportorDriverHasRunningTrip($driverId)
{
    global $conn;
    $driverId = (int) $driverId;
    if ($driverId <= 0) {
        return false;
    }
    $res = $conn->query("SELECT id FROM tbl_trip_details WHERE DriverId='$driverId' AND Status=0 LIMIT 1");
    return $res && $res->num_rows > 0;
}

/** @param array<int, array<string, mixed>> $rows */
function transportorAttachTripPayments(array $rows)
{
    if (empty($rows)) {
        return $rows;
    }

    $billingInc = dirname(__DIR__) . '/admin/inc-driver-trip-billing.php';
    if (!is_file($billingInc)) {
        foreach ($rows as &$row) {
            $row['IsPaid'] = 0;
            $row['PaidAmount'] = 0;
            $row['PaymentDate'] = '';
            $row['PaymentMode'] = '';
        }
        unset($row);
        return $rows;
    }

    require_once $billingInc;

    $attachRows = [];
    foreach ($rows as $row) {
        $attachRows[] = [
            'id' => (int) ($row['id'] ?? 0),
            'source' => 'trip',
        ];
    }

    $attachRows = driverTripBillingAttachPayments($attachRows);
    $paymentByTripId = [];
    foreach ($attachRows as $item) {
        $paymentByTripId[(int) $item['id']] = $item;
    }

    foreach ($rows as &$row) {
        $tripId = (int) ($row['id'] ?? 0);
        $payment = $paymentByTripId[$tripId] ?? null;
        $row['IsPaid'] = $payment ? (int) ($payment['IsPaid'] ?? 0) : 0;
        $row['PaidAmount'] = $payment ? (float) ($payment['PaidAmount'] ?? 0) : 0;
        $row['PaymentDate'] = $payment['PaymentDate'] ?? '';
        $row['PaymentMode'] = $payment['PaymentMode'] ?? '';
    }
    unset($row);

    return $rows;
}

function transportorFlash($type, $message)
{
    appFlashSet($type, $message);
}

function transportorTripPaymentStatusHtml(array $row)
{
    if (empty($row['TotalAmount']) || (float) $row['TotalAmount'] <= 0) {
        return '';
    }

    if (!empty($row['IsPaid'])) {
        $paidAmt = function_exists('driverTripBillingFormatMoney')
            ? driverTripBillingFormatMoney($row['PaidAmount'])
            : number_format((float) ($row['PaidAmount'] ?? 0), 2);
        $paidDate = function_exists('driverTripBillingFormatDate')
            ? driverTripBillingFormatDate($row['PaymentDate'])
            : htmlspecialchars((string) ($row['PaymentDate'] ?? ''));
        $mode = htmlspecialchars((string) ($row['PaymentMode'] ?? ''));

        return '<p style="margin-bottom:1px;"><strong>Paid Amount :</strong> &#8377;' . $paidAmt
            . ' | <strong>Paid Date :</strong> ' . $paidDate
            . ' | <strong>Pay Mode :</strong> ' . $mode
            . ' | <strong>Paid Status :</strong> <span class="badge badge-success">Payment Paid</span></p>';
    }

    return '<p style="margin-bottom:1px;"><strong>Paid Status :</strong> <span class="badge badge-warning">Payment Pending</span></p>';
}
