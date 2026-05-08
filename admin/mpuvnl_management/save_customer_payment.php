<?php
include '../config.php';
session_start();

$customer_id      = (int)$_POST['customer_id'];
$pump_capacity_id = (int)$_POST['pump_capacity_id'];
$total_amount     = (float)$_POST['total_amount'];
$total_paid       = (float)$_POST['total_paid'];
$payment_date     = $_POST['payment_date'];
$payment_type     = $_POST['payment_type'];
$created_by       = $_SESSION['Admin']['id']; // Admin who received payment

$conn->begin_transaction();

try {

    // 1️⃣ Get Previous Balance from Ledger
    $res = $conn->query("SELECT balance FROM tbl_customer_payment_ledger 
                         WHERE customer_id='$customer_id' 
                         ORDER BY id DESC LIMIT 1");

    $row = $res->fetch_assoc();
    $prev_balance = $row ? (float)$row['balance'] : $total_amount;

    // ❌ Prevent Overpayment
    if ($total_paid > $prev_balance) {
        throw new Exception("Payment exceeds remaining balance!");
    }

    // 2️⃣ Calculate New Balance
    $new_balance = $prev_balance - $total_paid;

    // 3️⃣ Decide Payment Status
    if ($new_balance == 0) {
        $payment_status = 'Paid';
    } elseif ($total_paid > 0) {
        $payment_status = 'Partial';
    } else {
        $payment_status = 'Pending';
    }

    // 4️⃣ Insert Payment Record
    $sql1 = "INSERT INTO tbl_customer_payments 
    (customer_id, pump_capacity_id, total_amount, total_paid, balance_amount, payment_date, payment_type, created_by, payment_status)
    VALUES 
    ('$customer_id','$pump_capacity_id','$prev_balance','$total_paid','$new_balance','$payment_date','$payment_type','$created_by','$payment_status')";

    if (!$conn->query($sql1)) {
        throw new Exception("Payment Insert Failed: " . $conn->error);
    }

    $payment_id = $conn->insert_id;

    // 5️⃣ Insert Ledger Entry (Credit = Money Received)
    $sql2 = "INSERT INTO tbl_customer_payment_ledger 
    (customer_id, payment_id, debit, credit, balance)
    VALUES 
    ('$customer_id','$payment_id',0,'$total_paid','$new_balance')";

    if (!$conn->query($sql2)) {
        throw new Exception("Ledger Insert Failed: " . $conn->error);
    }

    // 6️⃣ Update Customer Master Status (optional but useful)
    $conn->query("UPDATE tbl_users 
                  SET payment_status='$payment_status' 
                  WHERE id='$customer_id'");

    $conn->commit();

    echo "success";

} catch (Exception $e) {
    $conn->rollback();
    echo "error: " . $e->getMessage();
}
?>
