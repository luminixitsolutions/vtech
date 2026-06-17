<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-insurance-service-complaint-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Service';
$Page = 'Done-Insurance-Process';

insuranceServiceComplaintEnsureSchema($conn);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Done Insurance Process</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once 'sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once 'top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-0">Done Insurance Process</h4>
                    <p class="text-muted small mb-3">Insurance complaints completed via Fill Insurance Details with Complaint Close = Yes.</p>

                    <div class="card" style="padding: 10px;">
                        <div class="card-datatable table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Complaint No</th>
                                        <th>Customer Name</th>
                                        <th>Contact No</th>
                                        <th>District</th>
                                        <th>Claim Amount</th>
                                        <th>Amount Received</th>
                                        <th>Process Date</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $sql = insuranceServiceComplaintDoneSql() . ' ORDER BY tp.InsuranceProcessDate DESC, tp.id DESC';
                                    $rows = getList($sql);
                                    foreach ($rows as $result) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo htmlspecialchars($result['TicketNo'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($result['CustName'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($result['CellNo'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($result['District'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($result['ClaimAmt'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($result['AmountReceived'] ?? ''); ?></td>
                                        <td><?php echo !empty($result['InsuranceProcessDate']) ? date('d.m.Y', strtotime($result['InsuranceProcessDate'])) : ''; ?></td>
                                        <td>
                                            <a href="fill-insurance-details.php?id=<?php echo (int) $result['id']; ?>&view=1" class="btn btn-sm btn-outline-secondary">View Details</a>
                                        </td>
                                    </tr>
                                    <?php $i++; } ?>
                                </tbody>
                            </table>
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
