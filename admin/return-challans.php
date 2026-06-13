<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once 'inc-challan-return.php';
challanReturnEnsureTables($conn);

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Return-Challans";
$filterFromDate = $_POST['FromDate'] ?? $_GET['FromDate'] ?? '';
$filterToDate = $_POST['ToDate'] ?? $_GET['ToDate'] ?? '';
$filterSearchActive = isset($_POST['Search']) || isset($_GET['Search']);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/feather.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/datatables/datatables.css">
</head>
<body>
    <div class="page-loader"><div class="bg-primary"></div></div>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <?php include_once 'header.php'; ?>
            <div class="layout-container">
                <?php include_once 'top_header.php'; ?>
                <div class="layout-content">
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Return Challans</h5>
                        <br>
                        <div class="card mb-4" style="padding: 10px;">
                            <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                <div class="" style="padding:5px;">
                                    <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label">From Date</label>
                                                <input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate, ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label">To Date</label>
                                                <input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate, ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                            <input type="hidden" name="Search" value="Search">
                                            <div class="form-group col-md-2" style="padding-top:20px;">
                                                <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
                                            </div>
                                            <?php if ($filterSearchActive) { ?>
                                                <div class="col-md-1">
                                                    <label class="form-label d-none d-md-block">&nbsp;</label>
                                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block">X</a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-datatable table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Challan No</th>
                                            <th>Customer</th>
                                            <th>Return Date</th>
                                            <th>Remarks</th>
                                            <th>Total Items</th>
                                            <th>Returned By</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $sql = "SELECT cr.*, ts.InvoiceNo, ts.CustName, ts.ReturnStatus, tu.Fname AS ReturnedByName
                                            FROM challan_returns cr
                                            LEFT JOIN tbl_sell ts ON ts.id = cr.sell_id
                                            LEFT JOIN tbl_users tu ON tu.id = cr.created_by
                                            WHERE 1=1";
                                        if ($Roll == 26) {
                                            $sql .= " AND ts.BranchId='" . (int) $_SESSION['storeid'] . "' AND ts.CreatedBy='$user_id'";
                                        } else if ($Roll == 27) {
                                            $sql .= " AND ts.BranchId='$BranchId'";
                                        } else if (!($Roll == 1 || $Roll == 7)) {
                                            $sql .= " AND ts.CreatedBy='$user_id'";
                                        }
                                        if ($filterFromDate) {
                                            $sql .= " AND cr.return_date>='" . mysqli_real_escape_string($conn, $filterFromDate) . "'";
                                        }
                                        if ($filterToDate) {
                                            $sql .= " AND cr.return_date<='" . mysqli_real_escape_string($conn, $filterToDate) . "'";
                                        }
                                        $sql .= " ORDER BY cr.id DESC";
                                        $res = $conn->query($sql);
                                        while ($row = $res->fetch_assoc()) {
                                            $sqlCnt = "SELECT COUNT(*) AS cnt FROM challan_return_items WHERE return_id='" . (int) $row['id'] . "'";
                                            $rowCnt = getRecord($sqlCnt);
                                            $totItems = (int) ($rowCnt['cnt'] ?? 0);
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo htmlspecialchars($row['InvoiceNo'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($row['CustName'] ?? ''); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($row['return_date'])); ?></td>
                                                <td><?php echo htmlspecialchars($row['remarks'] ?? ''); ?></td>
                                                <td><?php echo $totItems; ?></td>
                                                <td><?php echo htmlspecialchars($row['ReturnedByName'] ?? ''); ?></td>
                                                <td>
                                                    <?php if ((int) ($row['ReturnStatus'] ?? 0) === 1) { ?>
                                                        <span class="badge badge-warning">Returned</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-success">Edited</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a href="view-return-challan.php?id=<?php echo (int) $row['sell_id']; ?>" class="btn btn-sm btn-info">View</a>
                                                    <?php if ((int) ($row['sell_id'] ?? 0) > 0) { ?>
                                                        <a href="edit-challan.php?id=<?php echo (int) $row['sell_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/demo.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                scrollX: true,
                pageLength: 100
            });
        });
    </script>
</body>
</html>
