<?php
session_start();
include_once '../config.php';
include_once '../inc-driver-trip-billing.php';
$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);

function driverTripBillingFail($message)
{
    echo "<script>alert(" . json_encode($message) . ");history.back();</script>";
    exit;
}

if (($_POST['action'] ?? '') === 'Save') {
    $id = trim((string) ($_POST['id'] ?? ''));
    $tripDetails = addslashes(trim($_POST['TripDetails'] ?? ''));
    $estimatedDistanceKm = driverTripBillingFloat($_POST['EstimatedDistanceKm'] ?? 0);
    $transportorId = (int) ($_POST['TransportorId'] ?? 0);
    $transportName = addslashes(trim($_POST['TransportName'] ?? ''));
    $driverId = (int) ($_POST['DriverId'] ?? 0);
    $driverName = addslashes(trim($_POST['DriverName'] ?? ''));
    $gadiNo = addslashes(trim($_POST['GadiNo'] ?? ''));
    $outDate = driverTripBillingDateYmd($_POST['OutDate'] ?? '');
    $inDate = driverTripBillingDateYmd($_POST['InDate'] ?? '');
    $openingReading = driverTripBillingFloat($_POST['OpeningReading'] ?? 0);
    $closingReading = driverTripBillingFloat($_POST['ClosingReading'] ?? 0);
    $fastag = driverTripBillingFloat($_POST['Fastag'] ?? 0);
    $challan = driverTripBillingFloat($_POST['Challan'] ?? 0);
    $dieselPayment = driverTripBillingFloat($_POST['DieselPayment'] ?? 0);
    $food = driverTripBillingFloat($_POST['Food'] ?? 0);
    $perDayRate = driverTripBillingFloat($_POST['PerDayRate'] ?? 0);
    $dieselRate = driverTripBillingFloat($_POST['DieselRate'] ?? 93, 93);
    $status = (int) ($_POST['Status'] ?? 1);
    $now = date('Y-m-d H:i:s');

    if ($tripDetails === '' || $driverId <= 0 || $outDate === '' || $inDate === '') {
        driverTripBillingFail('Trip details, driver, out date and in date are required.');
    }

    if ($transportorId > 0 && $transportName === '') {
        $tRow = getRecord("SELECT Fname FROM tbl_users WHERE id='$transportorId' AND Roll=46");
        $transportName = addslashes($tRow['Fname'] ?? '');
    }
    if ($driverName === '') {
        $dRow = getRecord("SELECT Fname, VehicalNo, UnderUser FROM tbl_users WHERE id='$driverId' AND Roll=39");
        $driverName = addslashes($dRow['Fname'] ?? '');
        if ($gadiNo === '') {
            $gadiNo = addslashes($dRow['VehicalNo'] ?? '');
        }
        if ($transportorId <= 0 && !empty($dRow['UnderUser'])) {
            $transportorId = (int) $dRow['UnderUser'];
            $tRow = getRecord("SELECT Fname FROM tbl_users WHERE id='$transportorId'");
            $transportName = addslashes($tRow['Fname'] ?? '');
        }
    }

    $calc = driverTripBillingCalculate([
        'opening_reading' => $openingReading,
        'closing_reading' => $closingReading,
        'fastag' => $fastag,
        'diesel_payment' => $dieselPayment,
        'food' => $food,
        'per_day_rate' => $perDayRate,
        'diesel_rate' => $dieselRate,
        'out_date' => $outDate,
        'in_date' => $inDate,
    ]);

    $totalRunningKm = $calc['total_running_km'];
    $avgVehicle = $calc['avg_vehicle'];
    $totalDieselUsed = $calc['total_diesel_used'];
    $days = $calc['days'];
    $totalAmount = $calc['total_amount'];
    $finalBillingAmount = $calc['final_billing_amount'];

    if ($id === '') {
        $sql = "INSERT INTO driver_trip_billings SET TripDetails='$tripDetails',EstimatedDistanceKm='$estimatedDistanceKm',TransportorId='$transportorId',TransportName='$transportName',DriverId='$driverId',DriverName='$driverName',GadiNo='$gadiNo',OutDate='$outDate',InDate='$inDate',OpeningReading='$openingReading',ClosingReading='$closingReading',Fastag='$fastag',Challan='$challan',DieselPayment='$dieselPayment',Food='$food',PerDayRate='$perDayRate',DieselRate='$dieselRate',TotalRunningKm='$totalRunningKm',AvgVehicle='$avgVehicle',TotalDieselUsed='$totalDieselUsed',Days='$days',TotalAmount='$totalAmount',FinalBillingAmount='$finalBillingAmount',Status='$status',CreatedBy='$user_id',CreatedDate='$now'";
        if (!$conn->query($sql)) {
            driverTripBillingFail('Error: ' . $conn->error);
        }
        $newId = mysqli_insert_id($conn);
        echo "<script>alert('Trip billing created successfully!');window.location.href='view-driver-trip-billing.php?id=$newId';</script>";
        exit;
    }

    $idEsc = $conn->real_escape_string($id);
    $sql = "UPDATE driver_trip_billings SET TripDetails='$tripDetails',EstimatedDistanceKm='$estimatedDistanceKm',TransportorId='$transportorId',TransportName='$transportName',DriverId='$driverId',DriverName='$driverName',GadiNo='$gadiNo',OutDate='$outDate',InDate='$inDate',OpeningReading='$openingReading',ClosingReading='$closingReading',Fastag='$fastag',Challan='$challan',DieselPayment='$dieselPayment',Food='$food',PerDayRate='$perDayRate',DieselRate='$dieselRate',TotalRunningKm='$totalRunningKm',AvgVehicle='$avgVehicle',TotalDieselUsed='$totalDieselUsed',Days='$days',TotalAmount='$totalAmount',FinalBillingAmount='$finalBillingAmount',Status='$status',ModifiedBy='$user_id',ModifiedDate='$now' WHERE id='$idEsc'";
    if (!$conn->query($sql)) {
        driverTripBillingFail('Error: ' . $conn->error);
    }
    echo "<script>alert('Trip billing updated successfully!');window.location.href='view-driver-trip-billing.php?id=$idEsc';</script>";
    exit;
}

if (($_POST['action'] ?? '') === 'delete') {
    $id = (int) ($_POST['id'] ?? 0);
    $conn->query("DELETE FROM driver_trip_billings WHERE id=$id");
    echo '1';
    exit;
}

if (($_POST['action'] ?? '') === 'getDriverInfo') {
    $driverId = (int) ($_POST['driver_id'] ?? 0);
    $row = getRecord("SELECT tu.id,tu.Fname,tu.VehicalNo,tu.PerDayVehicle,tu.UnderUser,t2.Fname AS TransportName FROM tbl_users tu LEFT JOIN tbl_users t2 ON t2.id=tu.UnderUser WHERE tu.id='$driverId' AND tu.Roll=39");
    header('Content-Type: application/json');
    echo json_encode($row ?: []);
    exit;
}

if (($_POST['action'] ?? '') === 'saveTripPayment') {
    header('Content-Type: application/json');
    $result = driverTripBillingSavePayment($_POST, $user_id);
    echo json_encode($result);
    exit;
}
