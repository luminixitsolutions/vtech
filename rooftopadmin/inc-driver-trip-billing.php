<?php

function driverTripBillingTableExists($table)
{
    global $conn;
    if (!$conn) {
        return false;
    }
    $table = $conn->real_escape_string($table);
    $res = @$conn->query("SHOW TABLES LIKE '$table'");
    return $res && $res->num_rows > 0;
}

function driverTripBillingSafeQuery($sql)
{
    global $conn;
    if (!$conn) {
        return false;
    }
    try {
        $res = $conn->query($sql);
        return $res ?: false;
    } catch (Throwable $e) {
        return false;
    }
}

function driverTripBillingPaymentModes()
{
    return [
        'Cash' => 'Cash',
        'Bank Transfer' => 'Bank Transfer',
        'UPI' => 'UPI',
        'Cheque' => 'Cheque',
        'NEFT' => 'NEFT',
        'RTGS' => 'RTGS',
        'Other' => 'Other',
    ];
}

function driverTripBillingEnsurePaymentTable()
{
    global $conn;
    if (!$conn || driverTripBillingTableExists('driver_trip_billing_payments')) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS driver_trip_billing_payments (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      SourceType VARCHAR(20) NOT NULL DEFAULT '',
      SourceId INT UNSIGNED NOT NULL DEFAULT 0,
      TotalAmount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      PaidAmount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      PaymentDate DATE NOT NULL,
      PaymentMode VARCHAR(50) NOT NULL DEFAULT '',
      PaymentStatus TINYINT NOT NULL DEFAULT 1,
      CreatedBy INT DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      ModifiedBy INT DEFAULT NULL,
      ModifiedDate DATETIME DEFAULT NULL,
      PRIMARY KEY (id),
      UNIQUE KEY uk_source (SourceType, SourceId),
      KEY idx_payment_date (PaymentDate),
      KEY idx_payment_status (PaymentStatus)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    driverTripBillingSafeQuery($sql);
}

function driverTripBillingEnsureSchema()
{
    global $conn;
    if (!$conn) {
        return;
    }

    driverTripBillingEnsurePaymentTable();

    if (driverTripBillingTableExists('driver_trip_billings')) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS driver_trip_billings (
      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
      TripDetails VARCHAR(255) NOT NULL DEFAULT '',
      EstimatedDistanceKm DECIMAL(12,2) DEFAULT NULL,
      TransportorId INT NOT NULL DEFAULT 0,
      TransportName VARCHAR(150) NOT NULL DEFAULT '',
      DriverId INT NOT NULL DEFAULT 0,
      DriverName VARCHAR(150) NOT NULL DEFAULT '',
      GadiNo VARCHAR(50) NOT NULL DEFAULT '',
      OutDate DATE NOT NULL,
      InDate DATE NOT NULL,
      OpeningReading DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      ClosingReading DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      Fastag DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      Challan DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      DieselPayment DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      Food DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      PerDayRate DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      DieselRate DECIMAL(12,2) NOT NULL DEFAULT 93.00,
      TotalRunningKm DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      AvgVehicle DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      TotalDieselUsed DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      Days DECIMAL(8,2) NOT NULL DEFAULT 0.00,
      TotalAmount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      FinalBillingAmount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      Status TINYINT NOT NULL DEFAULT 1,
      CreatedBy INT DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      ModifiedBy INT DEFAULT NULL,
      ModifiedDate DATETIME DEFAULT NULL,
      PRIMARY KEY (id),
      KEY idx_driver (DriverId),
      KEY idx_transportor (TransportorId),
      KEY idx_out_date (OutDate),
      KEY idx_in_date (InDate),
      KEY idx_status (Status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    driverTripBillingSafeQuery($sql);
}

function driverTripBillingFloat($value, $default = 0.0)
{
    if ($value === null || $value === '') {
        return (float) $default;
    }
    return (float) $value;
}

function driverTripBillingDateYmd($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }
    $ts = strtotime($value);
    return $ts ? date('Y-m-d', $ts) : '';
}

/** @return array<string, float|int|string> */
function driverTripBillingCalculate(array $input)
{
    $opening = driverTripBillingFloat($input['opening_reading'] ?? 0);
    $closing = driverTripBillingFloat($input['closing_reading'] ?? 0);
    $fastag = driverTripBillingFloat($input['fastag'] ?? 0);
    $dieselPayment = driverTripBillingFloat($input['diesel_payment'] ?? 0);
    $food = driverTripBillingFloat($input['food'] ?? 0);
    $perDayRate = driverTripBillingFloat($input['per_day_rate'] ?? 0);
    $dieselRate = driverTripBillingFloat($input['diesel_rate'] ?? 93, 93);

    $totalRunningKm = max(0, $closing - $opening);
    $avgVehicle = $totalRunningKm / 12;
    $totalDieselUsed = $avgVehicle * $dieselRate;

    $outDate = driverTripBillingDateYmd($input['out_date'] ?? '');
    $inDate = driverTripBillingDateYmd($input['in_date'] ?? '');
    $days = 0;
    if ($outDate !== '' && $inDate !== '') {
        $outTs = strtotime($outDate);
        $inTs = strtotime($inDate);
        if ($outTs && $inTs) {
            $days = (int) floor(($inTs - $outTs) / 86400) + 1;
            if ($days < 0) {
                $days = 0;
            }
        }
    }

    $totalAmount = $perDayRate * $days;
    $finalBillingAmount = $perDayRate - ($dieselPayment - $totalDieselUsed) + $fastag + $food;

    return [
        'total_running_km' => round($totalRunningKm, 2),
        'avg_vehicle' => round($avgVehicle, 2),
        'total_diesel_used' => round($totalDieselUsed, 2),
        'days' => $days,
        'total_amount' => round($totalAmount, 2),
        'final_billing_amount' => round($finalBillingAmount, 2),
        'diesel_rate' => $dieselRate,
    ];
}

function driverTripBillingFormatMoney($value)
{
    return number_format((float) $value, 2, '.', ',');
}

function driverTripBillingFormatDate($ymd)
{
    $ymd = driverTripBillingDateYmd($ymd);
    if ($ymd === '') {
        return '';
    }
    return date('d/m/Y', strtotime($ymd));
}

function driverTripBillingFormatChallanPaidBy($value)
{
    $value = strtolower(trim((string) $value));
    if ($value === 'vtech') {
        return 'Paid BY VTECH';
    }
    if ($value === 'transportor') {
        return 'Paid By transportor';
    }
    return '';
}

/** @return array{driver_id:string,transportor_id:string,from_date:string,to_date:string} */
function driverTripBillingTripFilters(array $input)
{
    return [
        'driver_id' => (string) ($input['DriverId'] ?? $input['driver_id'] ?? 'all'),
        'transportor_id' => (string) ($input['TransportorId'] ?? $input['transportor_id'] ?? 'all'),
        'from_date' => driverTripBillingDateYmd($input['FromDate'] ?? $input['from_date'] ?? ''),
        'to_date' => driverTripBillingDateYmd($input['ToDate'] ?? $input['to_date'] ?? ''),
    ];
}

function driverTripBillingCompletedTripsSql(array $filters)
{
    global $conn;

    $sql = "SELECT ts.*, tu.UnderUser AS TransportorId, t2.Fname AS TransportName
            FROM tbl_trip_details ts
            INNER JOIN tbl_users tu ON tu.id = ts.DriverId
            LEFT JOIN tbl_users t2 ON t2.id = tu.UnderUser
            WHERE ts.Status = 1";

    if ($filters['driver_id'] !== '' && $filters['driver_id'] !== 'all') {
        $sql .= " AND ts.DriverId='" . $conn->real_escape_string($filters['driver_id']) . "'";
    }
    if ($filters['transportor_id'] !== '' && $filters['transportor_id'] !== 'all') {
        $sql .= " AND tu.UnderUser='" . $conn->real_escape_string($filters['transportor_id']) . "'";
    }
    if ($filters['from_date'] !== '') {
        $sql .= " AND ts.InDate>='" . $conn->real_escape_string($filters['from_date']) . "'";
    }
    if ($filters['to_date'] !== '') {
        $sql .= " AND ts.OutDate<='" . $conn->real_escape_string($filters['to_date']) . "'";
    }

    $sql .= " ORDER BY ts.OutDate ASC, ts.id ASC";
    return $sql;
}

function driverTripBillingManualBillingsSql(array $filters)
{
    global $conn;

    $sql = "SELECT * FROM driver_trip_billings WHERE Status = 1";

    if ($filters['driver_id'] !== '' && $filters['driver_id'] !== 'all') {
        $sql .= " AND DriverId='" . $conn->real_escape_string($filters['driver_id']) . "'";
    }
    if ($filters['transportor_id'] !== '' && $filters['transportor_id'] !== 'all') {
        $sql .= " AND TransportorId='" . $conn->real_escape_string($filters['transportor_id']) . "'";
    }
    if ($filters['from_date'] !== '') {
        $sql .= " AND OutDate>='" . $conn->real_escape_string($filters['from_date']) . "'";
    }
    if ($filters['to_date'] !== '') {
        $sql .= " AND InDate<='" . $conn->real_escape_string($filters['to_date']) . "'";
    }

    $sql .= " ORDER BY OutDate ASC, id ASC";
    return $sql;
}

/** @return array<string, mixed> */
function driverTripBillingNormalizeTripRow(array $row)
{
    $avgVehicle = driverTripBillingFloat($row['TotalAvgVehicle'] ?? 0);
    if ($avgVehicle <= 0) {
        $avgVehicle = driverTripBillingFloat($row['VehAverage'] ?? 0);
    }

    return [
        'id' => (int) ($row['id'] ?? 0),
        'source' => 'trip',
        'TripDetails' => $row['TripDetails'] ?? '',
        'EstimatedDistanceKm' => $row['EstimateKm'] ?? '',
        'TransportName' => $row['TransportName'] ?? '',
        'GadiNo' => $row['VehicalNo'] ?? '',
        'DriverName' => $row['DriverName'] ?? '',
        'OutDate' => $row['OutDate'] ?? '',
        'InDate' => $row['InDate'] ?? '',
        'OpeningReading' => $row['OpeningReading'] ?? '',
        'ClosingReading' => $row['ClosingReading'] ?? '',
        'Fastag' => $row['Fastag'] ?? 0,
        'Challan' => $row['Challan'] ?? 0,
        'ChallanPaidBy' => $row['ChallanPaidBy'] ?? '',
        'DieselPayment' => $row['DieselPayment'] ?? 0,
        'TotalRunningKm' => $row['TotalRunningKm'] ?? 0,
        'AvgVehicle' => $avgVehicle,
        'TotalDieselUsed' => $row['TotalDieselUsed'] ?? 0,
        'Food' => $row['Food'] ?? 0,
        'PerDayRate' => $row['VehicleRate'] ?? 0,
        'Days' => $row['Days'] ?? 0,
        'TotalAmount' => $row['TotalAmount'] ?? 0,
        'FinalBillingAmount' => $row['TotalAmount'] ?? 0,
        '_sort_date' => driverTripBillingDateYmd($row['OutDate'] ?? '') ?: driverTripBillingDateYmd($row['InDate'] ?? ''),
    ];
}

/** @return array<string, mixed> */
function driverTripBillingNormalizeBillingRow(array $row)
{
    return [
        'id' => (int) ($row['id'] ?? 0),
        'source' => 'billing',
        'TripDetails' => $row['TripDetails'] ?? '',
        'EstimatedDistanceKm' => $row['EstimatedDistanceKm'] ?? '',
        'TransportName' => $row['TransportName'] ?? '',
        'GadiNo' => $row['GadiNo'] ?? '',
        'DriverName' => $row['DriverName'] ?? '',
        'OutDate' => $row['OutDate'] ?? '',
        'InDate' => $row['InDate'] ?? '',
        'OpeningReading' => $row['OpeningReading'] ?? '',
        'ClosingReading' => $row['ClosingReading'] ?? '',
        'Fastag' => $row['Fastag'] ?? 0,
        'Challan' => $row['Challan'] ?? 0,
        'ChallanPaidBy' => $row['ChallanPaidBy'] ?? '',
        'DieselPayment' => $row['DieselPayment'] ?? 0,
        'TotalRunningKm' => $row['TotalRunningKm'] ?? 0,
        'AvgVehicle' => $row['AvgVehicle'] ?? 0,
        'TotalDieselUsed' => $row['TotalDieselUsed'] ?? 0,
        'Food' => $row['Food'] ?? 0,
        'PerDayRate' => $row['PerDayRate'] ?? 0,
        'Days' => $row['Days'] ?? 0,
        'TotalAmount' => $row['TotalAmount'] ?? 0,
        'FinalBillingAmount' => $row['FinalBillingAmount'] ?? 0,
        '_sort_date' => driverTripBillingDateYmd($row['OutDate'] ?? '') ?: driverTripBillingDateYmd($row['InDate'] ?? ''),
    ];
}

/** @return array<int, array<string, mixed>> */
function driverTripBillingGetReportRows(array $filters)
{
    driverTripBillingEnsureSchema();

    $rows = [];

    $tripRes = driverTripBillingSafeQuery(driverTripBillingCompletedTripsSql($filters));
    if ($tripRes) {
        while ($row = $tripRes->fetch_assoc()) {
            $rows[] = driverTripBillingNormalizeTripRow($row);
        }
    }

    if (driverTripBillingTableExists('driver_trip_billings')) {
        $billingRes = driverTripBillingSafeQuery(driverTripBillingManualBillingsSql($filters));
        if ($billingRes) {
            while ($row = $billingRes->fetch_assoc()) {
                $rows[] = driverTripBillingNormalizeBillingRow($row);
            }
        }
    }

    usort($rows, function ($a, $b) {
        return strcmp((string) ($a['_sort_date'] ?? ''), (string) ($b['_sort_date'] ?? ''));
    });

    return driverTripBillingAttachPayments($rows);
}

/** @return array<string, array<string, mixed>> */
function driverTripBillingGetPaymentMap(array $rows)
{
    global $conn;

    driverTripBillingEnsurePaymentTable();
    if (!$conn || !driverTripBillingTableExists('driver_trip_billing_payments') || empty($rows)) {
        return [];
    }

    $tripIds = [];
    $billingIds = [];
    foreach ($rows as $row) {
        $source = (string) ($row['source'] ?? '');
        $id = (int) ($row['id'] ?? 0);
        if ($id <= 0) {
            continue;
        }
        if ($source === 'trip') {
            $tripIds[] = $id;
        } elseif ($source === 'billing') {
            $billingIds[] = $id;
        }
    }

    $map = [];
    if (!empty($tripIds)) {
        $ids = implode(',', array_map('intval', array_unique($tripIds)));
        $res = driverTripBillingSafeQuery("SELECT * FROM driver_trip_billing_payments WHERE SourceType='trip' AND SourceId IN ($ids) AND PaymentStatus=1");
        if ($res) {
            while ($payment = $res->fetch_assoc()) {
                $map['trip:' . (int) $payment['SourceId']] = $payment;
            }
        }
    }
    if (!empty($billingIds)) {
        $ids = implode(',', array_map('intval', array_unique($billingIds)));
        $res = driverTripBillingSafeQuery("SELECT * FROM driver_trip_billing_payments WHERE SourceType='billing' AND SourceId IN ($ids) AND PaymentStatus=1");
        if ($res) {
            while ($payment = $res->fetch_assoc()) {
                $map['billing:' . (int) $payment['SourceId']] = $payment;
            }
        }
    }

    return $map;
}

/** @param array<int, array<string, mixed>> $rows */
function driverTripBillingAttachPayments(array $rows)
{
    $paymentMap = driverTripBillingGetPaymentMap($rows);

    foreach ($rows as &$row) {
        $source = (string) ($row['source'] ?? '');
        $id = (int) ($row['id'] ?? 0);
        $key = $source . ':' . $id;
        $payment = $paymentMap[$key] ?? null;

        $row['IsPaid'] = $payment ? 1 : 0;
        $row['PaidAmount'] = $payment ? driverTripBillingFloat($payment['PaidAmount'] ?? 0) : 0;
        $row['PaymentDate'] = $payment['PaymentDate'] ?? '';
        $row['PaymentMode'] = $payment['PaymentMode'] ?? '';
        $row['PaymentTotalAmount'] = $payment ? driverTripBillingFloat($payment['TotalAmount'] ?? 0) : 0;
    }
    unset($row);

    return $rows;
}

/** @return array{ok:bool,message:string,payment?:array<string,mixed>} */
function driverTripBillingSavePayment(array $input, $userId)
{
    global $conn;

    driverTripBillingEnsurePaymentTable();
    if (!$conn) {
        return ['ok' => false, 'message' => 'Database connection failed.'];
    }

    $sourceType = strtolower(trim((string) ($input['source_type'] ?? '')));
    $sourceId = (int) ($input['source_id'] ?? 0);
    $totalAmount = driverTripBillingFloat($input['total_amount'] ?? 0);
    $paidAmount = driverTripBillingFloat($input['paid_amount'] ?? 0);
    $paymentDate = driverTripBillingDateYmd($input['payment_date'] ?? '');
    $paymentMode = trim((string) ($input['payment_mode'] ?? ''));

    if (!in_array($sourceType, ['trip', 'billing'], true)) {
        return ['ok' => false, 'message' => 'Invalid trip source.'];
    }
    if ($sourceId <= 0) {
        return ['ok' => false, 'message' => 'Invalid trip record.'];
    }
    if ($totalAmount <= 0) {
        return ['ok' => false, 'message' => 'Total amount must be greater than zero.'];
    }
    if ($paidAmount <= 0) {
        return ['ok' => false, 'message' => 'Paid amount must be greater than zero.'];
    }
    if ($paymentDate === '') {
        return ['ok' => false, 'message' => 'Payment date is required.'];
    }
    if ($paymentMode === '') {
        return ['ok' => false, 'message' => 'Payment mode is required.'];
    }

    if ($sourceType === 'trip') {
        $exists = getRecord("SELECT id FROM tbl_trip_details WHERE id='$sourceId' AND Status=1 LIMIT 1");
    } else {
        $exists = getRecord("SELECT id FROM driver_trip_billings WHERE id='$sourceId' AND Status=1 LIMIT 1");
    }
    if (!$exists) {
        return ['ok' => false, 'message' => 'Trip record not found.'];
    }

    $existing = getRecord("SELECT id FROM driver_trip_billing_payments WHERE SourceType='" . $conn->real_escape_string($sourceType) . "' AND SourceId='$sourceId' AND PaymentStatus=1 LIMIT 1");
    if ($existing) {
        return ['ok' => false, 'message' => 'Payment already recorded for this trip.'];
    }

    $sourceTypeEsc = $conn->real_escape_string($sourceType);
    $paymentModeEsc = $conn->real_escape_string($paymentMode);
    $paymentDateEsc = $conn->real_escape_string($paymentDate);
    $now = date('Y-m-d H:i:s');
    $userId = (int) $userId;

    $sql = "INSERT INTO driver_trip_billing_payments SET
        SourceType='$sourceTypeEsc',
        SourceId='$sourceId',
        TotalAmount='$totalAmount',
        PaidAmount='$paidAmount',
        PaymentDate='$paymentDateEsc',
        PaymentMode='$paymentModeEsc',
        PaymentStatus=1,
        CreatedBy='$userId',
        CreatedDate='$now'";

    if (!$conn->query($sql)) {
        return ['ok' => false, 'message' => 'Error: ' . $conn->error];
    }

    return [
        'ok' => true,
        'message' => 'Payment saved successfully.',
        'payment' => [
            'paid_amount' => $paidAmount,
            'paid_amount_formatted' => driverTripBillingFormatMoney($paidAmount),
            'payment_date' => $paymentDate,
            'payment_date_formatted' => driverTripBillingFormatDate($paymentDate),
            'payment_mode' => $paymentMode,
        ],
    ];
}

function driverTripBillingCompletedTripsSummarySql(array $filters)
{
    global $conn;

    $sql = "SELECT ts.DriverId, ts.DriverName, t2.Fname AS TransportName, ts.VehicalNo AS GadiNo,
            COUNT(*) AS TripCount,
            SUM(COALESCE(ts.TotalRunningKm, 0)) AS TotalKm,
            SUM(COALESCE(ts.DieselPayment, 0)) AS TotalDieselPayment,
            SUM(COALESCE(ts.Fastag, 0)) AS TotalFastag,
            SUM(COALESCE(ts.Food, 0)) AS TotalFood,
            SUM(COALESCE(ts.TotalAmount, 0)) AS TotalAmount,
            SUM(COALESCE(ts.TotalAmount, 0)) AS FinalBillingTotal
            FROM tbl_trip_details ts
            INNER JOIN tbl_users tu ON tu.id = ts.DriverId
            LEFT JOIN tbl_users t2 ON t2.id = tu.UnderUser
            WHERE ts.Status = 1";

    if ($filters['from_date'] !== '') {
        $sql .= " AND ts.InDate>='" . $conn->real_escape_string($filters['from_date']) . "'";
    }
    if ($filters['to_date'] !== '') {
        $sql .= " AND ts.OutDate<='" . $conn->real_escape_string($filters['to_date']) . "'";
    }

    $sql .= " GROUP BY ts.DriverId, ts.DriverName, t2.Fname, ts.VehicalNo
              ORDER BY ts.DriverName ASC";
    return $sql;
}
