<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-insurance-site.php';
require_once __DIR__ . '/inc-insurance-service-complaint-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Service';
$Page = 'Fill-Insurance-Details';
$viewOnly = isset($_GET['view']) && $_GET['view'] === '1';
$complaintId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

insuranceServiceComplaintEnsureSchema($conn);
$row7 = insuranceServiceComplaintGetById($conn, $complaintId);

if (empty($row7)) {
    echo "<script>alert('Insurance service complaint not found.'); window.location.href='view-insurance-service-complaints.php';</script>";
    exit;
}

if (!$viewOnly && !insuranceServiceComplaintCanFillDetails($row7)) {
    if (insuranceServiceComplaintIsProcessDone($row7)) {
        echo "<script>alert('Insurance process already completed for this complaint.'); window.location.href='view-done-insurance-process.php';</script>";
    } else {
        echo "<script>alert('Only closed insurance service complaints can be processed here.'); window.location.href='view-insurance-service-complaints.php';</script>";
    }
    exit;
}

if (!$viewOnly && isset($_POST['submit'])) {
    $DocReq = $conn->real_escape_string(trim($_POST['DocReq'] ?? 'No'));
    $OrgDocReq = $conn->real_escape_string(trim($_POST['OrgDocReq'] ?? 'No'));
    $SurveyUpdate = $conn->real_escape_string(trim($_POST['SurveyUpdate'] ?? 'No'));
    $ClaimAmt = $conn->real_escape_string(trim($_POST['ClaimAmt'] ?? ''));
    $InsuranceApproved = $conn->real_escape_string(trim($_POST['InsuranceApproved'] ?? 'No'));
    $PaymentReceived = $conn->real_escape_string(trim($_POST['PaymentReceived'] ?? 'No'));
    $AmountReceived = $conn->real_escape_string(trim($_POST['AmountReceived'] ?? ''));
    $MaterialReplacement = $conn->real_escape_string(trim($_POST['MaterialReplacement'] ?? 'No'));
    $ComplaintClose = $conn->real_escape_string(trim($_POST['ComplaintClose'] ?? 'No'));
    $ModifiedDate = date('Y-m-d');
    $isProcessDone = ($ComplaintClose === 'Yes');
    $ProcessDone = $isProcessDone ? 'Yes' : 'No';
    $ProcessDate = $isProcessDone ? date('Y-m-d') : '';

    $sql = "UPDATE tbl_service_complaint SET
        DocReq='$DocReq',
        OrgDocReq='$OrgDocReq',
        SurveyUpdate='$SurveyUpdate',
        ClaimAmt='$ClaimAmt',
        InsuranceApproved='$InsuranceApproved',
        PaymentReceived='$PaymentReceived',
        AmountReceived='$AmountReceived',
        MaterialReplacement='$MaterialReplacement',
        ComplaintClose='$ComplaintClose',
        InsuranceProcessDone='$ProcessDone',
        InsuranceProcessDate=" . ($isProcessDone ? "'$ProcessDate'" : 'NULL') . ",
        ModifiedDate='$ModifiedDate',
        ModifiedBy='$user_id'
        WHERE id='$complaintId' AND ServiceType='Insurance'";
    $conn->query($sql);

    if ($isProcessDone) {
        echo "<script>alert('Insurance process completed successfully.'); window.location.href='view-done-insurance-process.php';</script>";
    } else {
        echo "<script>alert('Insurance details saved. Complaint will remain pending until Complaint Close is Yes.'); window.location.href='view-insurance-service-complaints.php';</script>";
    }
    exit;
}

$readonly = $viewOnly ? 'readonly' : '';
$disabled = $viewOnly ? 'disabled' : '';
$custId = (int) ($row7['CustId'] ?? 0);
$customerInsurance = $custId > 0 ? insuranceGetLatestCustomerInsurance($custId) : null;
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Fill Insurance Details</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <style>
        .insurance-readonly-block {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .insurance-readonly-block .form-control[readonly] {
            background: #fff;
        }
    </style>
</head>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once 'sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once 'top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-0"><?php echo $viewOnly ? 'View' : 'Fill'; ?> Insurance Details</h4>
                    <p class="text-muted small mb-3">Complaint <?php echo htmlspecialchars($row7['TicketNo'] ?? ''); ?> — update insurance process fields only.</p>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="validation-form" method="post" autocomplete="off">
                                <input type="hidden" name="id" value="<?php echo $complaintId; ?>">

                                <div class="insurance-readonly-block">
                                    <h6 class="font-weight-bold mb-3">Complaint Information (read only)</h6>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Beneficiary ID</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['BeneficiaryId'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Contact No</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['CellNo'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Customer / Farmer Name</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['CustName'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address</label>
                                            <textarea class="form-control" readonly><?php echo htmlspecialchars($row7['Address'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Taluka</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['Taluka'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Village</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['Village'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">District</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['District'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Complaint Date</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['ComplaintDate'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Type Of Insurance Complaint</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['InsuranceComplaintName'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Service Related Issue</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['RelatedIssue'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Status</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['ClainStatus'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Remark / Requirement</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['Remark'] ?? ''); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="insurance-readonly-block">
                                    <h6 class="font-weight-bold mb-2">Latest Insurance Details</h6>
                                    <p class="text-muted small mb-3"><?php
                                        if (!empty($customerInsurance)) {
                                            echo 'Source: ' . htmlspecialchars($customerInsurance['source_label'] ?? 'Insurance record');
                                        } else {
                                            echo 'No completed or renewed insurance record found for this customer.';
                                        }
                                    ?></p>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Insurance No</label>
                                            <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['insurance_no'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Insurance Company Name</label>
                                            <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['company_name'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date Of Issue</label>
                                            <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['date_of_issue_display'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date Of Expiry</label>
                                            <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['date_of_expiry_display'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="form-label">No of Year</label>
                                            <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['no_of_years'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold mb-3">Insurance Process Details</h6>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Document Required<span class="text-danger">*</span></label>
                                        <select class="form-control" name="DocReq" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['DocReq'] ?? 'No'); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label class="form-label">Original Doc Submit to insurance Company and also to Surveyor<span class="text-danger">*</span></label>
                                        <select class="form-control" name="OrgDocReq" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['OrgDocReq'] ?? 'No'); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label">Survey update report by surveyor<span class="text-danger">*</span></label>
                                        <select class="form-control" name="SurveyUpdate" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['SurveyUpdate'] ?? 'No'); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Claim Amount</label>
                                        <input type="text" name="ClaimAmt" class="form-control" value="<?php echo htmlspecialchars($row7['ClaimAmt'] ?? ''); ?>" <?php echo $readonly; ?>>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Insurance Approved or not<span class="text-danger">*</span></label>
                                        <select class="form-control" name="InsuranceApproved" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['InsuranceApproved'] ?? 'No'); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Payment received<span class="text-danger">*</span></label>
                                        <select class="form-control" name="PaymentReceived" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['PaymentReceived'] ?? 'No'); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Amount Received</label>
                                        <input type="text" name="AmountReceived" class="form-control" value="<?php echo htmlspecialchars($row7['AmountReceived'] ?? ''); ?>" <?php echo $readonly; ?>>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Material Replacement Done<span class="text-danger">*</span></label>
                                        <select class="form-control" name="MaterialReplacement" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['MaterialReplacement'] ?? 'No'); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Complaint Close<span class="text-danger">*</span></label>
                                        <select class="form-control" name="ComplaintClose" <?php echo $disabled; ?> required>
                                            <?php echo insuranceServiceComplaintYesNoOptions($row7['ComplaintClose'] ?? 'No'); ?>
                                        </select>
                                        <?php if (!$viewOnly) { ?>
                                        <small class="text-muted">Select <strong>Yes</strong> only when insurance process is fully complete — record will move to Done Insurance Process.</small>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-row mt-2">
                                    <?php if (!$viewOnly) { ?>
                                    <div class="form-group col-md-2">
                                        <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group col-md-2">
                                        <a href="<?php echo $viewOnly ? 'view-done-insurance-process.php' : 'view-insurance-service-complaints.php'; ?>" class="btn btn-secondary">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include_once 'footer.php'; ?>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once 'footer_script.php'; ?>
</body>
</html>
