<?php
session_start();
include_once 'config.php';
require_once 'exe-database.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Under-Production-Beneficiary';
$Page = 'Under-Production-Stock-Report';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo htmlspecialchars($Proj_Title); ?> | Done beneficiary — required stock</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <style>
        .upb-select-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        table#example th.upb-chk-col,
        table#example td.upb-chk-col {
            width: 42px;
            text-align: center;
            vertical-align: middle;
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
    <h4 class="font-weight-bold py-3 mb-0">Done beneficiaries — required stock report</h4>

    <div class="card" style="padding: 10px;">
        <div class="upb-select-toolbar">
            <span id="upbSelectedCount" class="text-muted">0 customer(s) selected</span>
            <button type="button" id="btnShowCombinedStock" class="btn btn-primary btn-sm" disabled>
                Show combined required stock
            </button>
        </div>

        <div class="card-datatable table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th class="upb-chk-col" data-orderable="false">
                            <label class="custom-control custom-checkbox m-0 d-inline-block">
                                <input type="checkbox" id="upbSelectAll" class="custom-control-input">
                                <span class="custom-control-label">&nbsp;</span>
                            </label>
                        </th>
                        <th>#</th>
                        <th>Beneficiary Id</th>
                        <th>Customer name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th data-orderable="false">View required stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $sql = "SELECT tp.id, tp.BeneficiaryId, tp.Fname, tp.Phone, tp.Address
                            FROM tbl_users tp
                            WHERE tp.SurveyMatch = 1 AND tp.ProjectType = 1 AND tp.UnderProdStatus = '1'
                            AND NOT EXISTS (
                                SELECT 1 FROM tbl_sell ts
                                WHERE ts.CustId = tp.id AND ts.SellType = 'Challan' AND ts.Status = 1
                            )
                            ORDER BY tp.UnderProdDate DESC, tp.CreatedDate DESC";
                    $res = $conn->query($sql);
                    if ($res) {
                        while ($row = $res->fetch_assoc()) {
                            $uid = (int) $row['id'];
                            $detailUrl = 'under-production-beneficiary-required-stock.php?uid=' . $uid;
                            ?>
                            <tr>
                                <td class="upb-chk-col">
                                    <label class="custom-control custom-checkbox m-0 d-inline-block">
                                        <input type="checkbox" class="custom-control-input upb-cust-select"
                                            value="<?php echo $uid; ?>"
                                            data-name="<?php echo htmlspecialchars((string) $row['Fname'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="custom-control-label">&nbsp;</span>
                                    </label>
                                </td>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars((string) $row['BeneficiaryId']); ?></td>
                                <td><?php echo htmlspecialchars((string) $row['Fname']); ?></td>
                                <td><?php echo htmlspecialchars((string) $row['Phone']); ?></td>
                                <td><?php echo htmlspecialchars((string) $row['Address']); ?></td>
                                <td><a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars($detailUrl); ?>">View required stock</a></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>
<script type="text/javascript">
$(document).ready(function() {
    var dt = $('#example').DataTable({
        scrollX: true,
        columnDefs: [
            { targets: 0, orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });

    function getSelectedIds() {
        var ids = [];
        $('#example tbody .upb-cust-select:checked').each(function() {
            ids.push($(this).val());
        });
        return ids;
    }

    function updateSelectionUi() {
        var ids = getSelectedIds();
        var n = ids.length;
        $('#upbSelectedCount').text(n + ' customer(s) selected');
        $('#btnShowCombinedStock').prop('disabled', n === 0);
        $('#upbSelectAll').prop('checked', n > 0 && n === $('#example tbody .upb-cust-select').length);
    }

    $('#example').on('change', '.upb-cust-select', updateSelectionUi);

    $('#upbSelectAll').on('change', function() {
        var checked = $(this).prop('checked');
        $('#example tbody .upb-cust-select').prop('checked', checked);
        updateSelectionUi();
    });

    dt.on('draw', function() {
        updateSelectionUi();
    });

    $('#btnShowCombinedStock').on('click', function() {
        var ids = getSelectedIds();
        if (!ids.length) {
            return;
        }
        window.location.href = 'under-production-beneficiary-required-stock.php?uids=' + ids.join(',');
    });

    updateSelectionUi();
});
</script>
</body>
</html>
