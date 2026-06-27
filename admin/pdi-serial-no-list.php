<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$pdiId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pdiHeader = null;
if ($pdiId > 0) {
    $pdiHeader = getRecord("SELECT tpv.*, tcm.Name AS ProjName, tps.Name AS ProjSubHeadName
        FROM tbl_pdi_verification tpv
        INNER JOIN tbl_common_master tcm ON tpv.project_id = tcm.id
        INNER JOIN tbl_project_sub_head tps ON tpv.project_sub_head_id = tps.id
        WHERE tpv.id = '$pdiId' LIMIT 1");
}
$MainPage = "PDI-Verification";
$Page = "View-PDI-Verification";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
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
                        <h4 class="font-weight-bold py-3 mb-0">PDI Verification Serial No List</h4>
                        <?php if ($pdiHeader) {
                            $pdiDateDisp = !empty($pdiHeader['pdidate'])
                                ? date('d/m/Y', strtotime(str_replace('-', '/', $pdiHeader['pdidate'])))
                                : '—';
                            ?>
                        <p class="text-muted mb-3">
                            <strong><?php echo htmlspecialchars($pdiHeader['ProjName']); ?></strong>
                            &mdash; <?php echo htmlspecialchars($pdiHeader['ProjSubHeadName']); ?>
                            &nbsp;|&nbsp; PDI Date: <?php echo $pdiDateDisp; ?>
                            &nbsp;|&nbsp; Report No: <?php echo htmlspecialchars($pdiHeader['report_no'] ?? '—'); ?>
                            &nbsp;|&nbsp; PDI Qty: <?php echo (int) ($pdiHeader['pdi_qty'] ?? 0); ?>
                        </p>
                        <a href="view-uploaded-pdi.php" class="btn btn-sm btn-outline-secondary mb-3">Back to PDI Verification</a>
                        <?php } elseif ($pdiId <= 0) { ?>
                        <div class="alert alert-warning">Invalid PDI record. <a href="view-uploaded-pdi.php">Go back</a></div>
                        <?php } else { ?>
                        <div class="alert alert-warning">PDI record not found. <a href="view-uploaded-pdi.php">Go back</a></div>
                        <?php } ?>

                        <?php if ($pdiHeader) { ?>
                        <div class="card" style="padding: 10px;">
                            <div class="card-datatable table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PDI Date</th>
                                            <th>PDI Report No</th>
                                            <th>Serial No</th>
                                            <th class="text-center">Match</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        if ($pdiId > 0) {
                                        $sql = "SELECT * FROM tbl_pdi_verification_serialno WHERE pdi_id='$pdiId'";
                                        $listVal = isset($_GET['val']) ? trim((string) $_GET['val']) : '';
                                        if ($listVal === 'match') {
                                            $sql .= " AND match_status=1";
                                        } elseif ($listVal === 'unmatch') {
                                            $sql .= " AND match_status=0";
                                        }
                                        $res = $conn->query($sql);
                                        if ($res) {
                                        while ($row = $res->fetch_assoc()) {
                                            $isMatched = ((int) ($row['match_status'] ?? 0) === 1);
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo !empty($row['pdidate']) ? date("d/m/Y", strtotime(str_replace('-', '/', $row['pdidate']))) : '—'; ?></td>
                                                <td><?php echo htmlspecialchars($row['report_no'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['serialno'] ?? ''); ?></td>
                                                <td class="text-center">
                                                    <?php if ($isMatched) { ?>
                                                    <i class="lnr lnr-checkmark-circle text-success" style="font-size: 20px;" title="Matched"></i>
                                                    <?php } else { ?>
                                                    <i class="lnr lnr-cross-circle text-danger" style="font-size: 20px;" title="Not matched"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                        }
                                        }
                                        }
                                        if ($i === 1) {
                                            echo '<tr><td colspan="5" class="text-center text-muted">No serial numbers found for this PDI.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php include_once 'footer.php'; ?>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <?php include_once 'footer_script.php'; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#example').length) {
                $('#example').DataTable({
                    "scrollX": true,
                    dom: 'Bfrtip',
                    buttons: [
                        'excelHtml5'
                    ]
                });
            }
        });
    </script>
</body>

</html>