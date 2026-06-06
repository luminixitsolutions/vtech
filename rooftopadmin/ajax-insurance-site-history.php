<?php
session_start();
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/auth.php';
include_once __DIR__ . '/inc-rooftop-insurance-site.php';

header('Content-Type: text/html; charset=utf-8');

$custId = isset($_GET['CustId']) ? (int) $_GET['CustId'] : 0;
if ($custId <= 0) {
    echo '<div class="alert alert-warning mb-0">Invalid customer.</div>';
    exit;
}

$customer = getRecord("SELECT id, Fname, BeneficiaryId FROM tbl_users WHERE id = '$custId' LIMIT 1");
$historyRows = insuranceGetSiteHistoryByCustomer($custId);
?>
<div class="mb-2">
    <strong><?php echo htmlspecialchars($customer['Fname']); ?></strong>
    <?php if (!empty($customer['BeneficiaryId'])) { ?>
        <span class="text-muted">(<?php echo htmlspecialchars($customer['BeneficiaryId']); ?>)</span>
    <?php } ?>
</div>

<?php if (empty($historyRows)) { ?>
<div class="alert alert-info mb-0">No insurance process history found for this customer.</div>
<?php } else { ?>
<div class="table-responsive">
    <table class="table table-bordered table-sm mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Completed Date</th>
                <th>Insurance Company</th>
                <th>Policy No</th>
                <th>Date Of Issue</th>
                <th>Date Of Expiry</th>
                <th>No of Year</th>
                <th>Process Type</th>
                <th>Processed By</th>
                <th>Source File</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n = 1;
            foreach ($historyRows as $history) {
                $processorName = trim($history['ProcessedByName']);
                if ($processorName === '') {
                    $processorName = trim($history['ProcessorFname'] . ' ' . $history['ProcessorLname']);
                }
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo htmlspecialchars(formatInsuranceDate($history['CompletedDate'])); ?><br>
                        <small class="text-muted"><?php echo date('h:i A', strtotime($history['CompletedDateTime'])); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($history['InsuranceCompany']); ?></td>
                    <td><?php echo htmlspecialchars($history['PolicyNo']); ?></td>
                    <td><?php echo htmlspecialchars(formatInsuranceDate($history['DateOfIssue'])); ?></td>
                    <td><?php echo htmlspecialchars(formatInsuranceDate($history['DateOfExpiry'])); ?></td>
                    <td><?php echo htmlspecialchars($history['NoOfYear']); ?></td>
                    <td><?php echo htmlspecialchars($history['ProcessType']); ?></td>
                    <td><?php echo htmlspecialchars($processorName); ?></td>
                    <td><?php echo htmlspecialchars($history['SourceFile']); ?></td>
                </tr>
                <?php
                $n++;
            }
            ?>
        </tbody>
    </table>
</div>
<?php } ?>
