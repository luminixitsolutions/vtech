<?php
session_start();
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/auth.php';
include_once __DIR__ . '/inc-rooftop-insurance-site.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Insurance';
$Page = 'Renewed-Insurance';

$filterCustId = isset($_REQUEST['CustId']) ? trim((string) $_REQUEST['CustId']) : 'all';
$filterFromDate = isset($_REQUEST['FromDate']) ? trim((string) $_REQUEST['FromDate']) : '';
$filterToDate = isset($_REQUEST['ToDate']) ? trim((string) $_REQUEST['ToDate']) : '';
$filterDistrict = isset($_REQUEST['District']) ? trim((string) $_REQUEST['District']) : '';
$filterTaluka = isset($_REQUEST['Taluka']) ? trim((string) $_REQUEST['Taluka']) : '';
$filterVillage = isset($_REQUEST['Village']) ? trim((string) $_REQUEST['Village']) : '';
$isSearch = isset($_REQUEST['Search']);
$importedCount = isset($_GET['imported']) ? (int) $_GET['imported'] : 0;
$listFilters = insuranceSiteListFiltersFromRequest();

$renewedInsuranceCustomerSql = insuranceSiteCustomerDropdownSql(insuranceSiteRenewedSqlCondition());
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Renewed Insurance</title>
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
<h4 class="font-weight-bold py-3 mb-0">Renewed Insurance</h4>
<p class="text-muted mb-3">Customers renewed from expired insurance via Excel import.</p>

<?php if ($importedCount > 0) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $importedCount; ?> record(s) renewed successfully and moved to renewed insurance.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>

<div class="card" style="padding: 10px;">
    <div id="accordion2">
        <div class="card mb-2">
            <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                <div class="" style="padding:5px;">
                    <form id="validation-form" method="get" action="">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="form-label">Customer</label>
                                <select class="select2-demo form-control" name="CustId" id="CustId">
                                    <option selected="" value="all">All</option>
                                    <?php
                                    $customers = getList($renewedInsuranceCustomerSql);
                                    foreach ($customers as $customer) {
                                        ?>
                                        <option <?php if ($filterCustId == $customer['id']) { ?> selected <?php } ?> value="<?php echo $customer['id']; ?>">
                                            <?php echo $customer['Fname'] . ' (' . $customer['BeneficiaryId'] . ')'; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">District</label>
                                <select class="form-control" name="District" id="District">
                                    <option selected="" value="">All District</option>
                                    <?php
                                    $districtRows = getList("SELECT DISTINCT(District) AS District FROM tbl_users WHERE District!='' AND Roll=5 AND ProjectType=2 ORDER BY District ASC");
                                    foreach ($districtRows as $districtRow) {
                                        ?>
                                        <option <?php if ($filterDistrict == $districtRow['District']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($districtRow['District']); ?>">
                                            <?php echo htmlspecialchars($districtRow['District']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">Taluka</label>
                                <select class="form-control" name="Taluka" id="Taluka">
                                    <option selected="" value="">All Taluka</option>
                                    <?php
                                    $talukaRows = getList("SELECT DISTINCT(Taluka) AS Taluka FROM tbl_users WHERE Taluka!='' ORDER BY Taluka ASC");
                                    foreach ($talukaRows as $talukaRow) {
                                        ?>
                                        <option <?php if ($filterTaluka == $talukaRow['Taluka']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($talukaRow['Taluka']); ?>">
                                            <?php echo htmlspecialchars($talukaRow['Taluka']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">Village</label>
                                <select class="form-control" name="Village" id="Village">
                                    <option selected="" value="">All Village</option>
                                    <?php
                                    $villageRows = getList("SELECT DISTINCT(Village) AS Village FROM tbl_users WHERE Village!='' ORDER BY Village ASC");
                                    foreach ($villageRows as $villageRow) {
                                        ?>
                                        <option <?php if ($filterVillage == $villageRow['Village']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($villageRow['Village']); ?>">
                                            <?php echo htmlspecialchars($villageRow['Village']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">Dispatch From Date</label>
                                <input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate); ?>" autocomplete="off">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">Dispatch To Date</label>
                                <input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate); ?>" autocomplete="off">
                            </div>

                            <input type="hidden" name="Search" value="Search">
                            <div class="form-group col-md-1" style="padding-top:30px;">
                                <button type="submit" class="btn btn-primary btn-finish">Search</button>
                            </div>
                            <?php if ($isSearch) { ?>
                            <div class="col-md-1" style="padding-top:30px;">
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                            </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card-datatable table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Contact No</th>
                    <th>Project Type</th>
                    <th>Taluka</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Address</th>
                    <th>Insurance Company Name</th>
                    <th>Policy No</th>
                    <th>Date Of Issue</th>
                    <th>Date Of Expiry</th>
                    <th>No of Year</th>
                    <th>Insurance Status</th>
                    <th>Site Dispatch Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $renewedOrder = insuranceSiteExpiryDateSql() . ' DESC, tdo.Inst_Dispatcher_Date DESC, tdo.id DESC';
                $sql = insuranceSiteListSelectSql(insuranceSiteRenewedSqlCondition(), $listFilters, $renewedOrder);
                $res = $conn->query($sql);
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $dispatchDate = '';
                        if (!empty($row['Inst_Dispatcher_Date']) && $row['Inst_Dispatcher_Date'] !== '0000-00-00') {
                            $dispatchDate = date('d/m/Y', strtotime(str_replace('-', '/', $row['Inst_Dispatcher_Date'])));
                        }

                        $profileUrl = ($row['ProjectType'] == '2')
                            ? 'user_management/rooftop-customer-profile.php?id=' . $row['CustId']
                            : 'user_management/customer-profile.php?id=' . $row['CustId'];
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo htmlspecialchars($row['BeneficiaryId']); ?></td>
                            <td><?php echo htmlspecialchars($row['CustName']); ?></td>
                            <td><?php echo htmlspecialchars($row['CellNo']); ?></td>
                            <td><?php echo ($row['ProjectType'] == '2') ? 'Rooftop' : 'Pump'; ?></td>
                            <td><?php echo htmlspecialchars($row['Taluka']); ?></td>
                            <td><?php echo htmlspecialchars($row['Village']); ?></td>
                            <td><?php echo htmlspecialchars($row['District']); ?></td>
                            <td><?php echo htmlspecialchars($row['Address']); ?></td>
                            <td><?php echo htmlspecialchars($row['InsuranceAgency']); ?></td>
                            <td><?php echo htmlspecialchars($row['InsuranceNumber']); ?></td>
                            <td><?php echo htmlspecialchars(formatInsuranceDate($row['InsuranceIssueDate'])); ?></td>
                            <td><?php echo htmlspecialchars(formatInsuranceDate($row['InsuranceValidity'])); ?></td>
                            <td><?php echo htmlspecialchars(getInsuranceYears($row['InsuranceIssueDate'], $row['InsuranceValidity'], $row['InsuranceYears'])); ?></td>
                            <td>Renewed</td>
                            <td><?php echo $dispatchDate; ?></td>
                            <td>
                                <a href="<?php echo $profileUrl; ?>" class="btn btn-sm btn-primary" target="_blank">View</a>
                                <button type="button" class="btn btn-sm btn-info btnInsuranceHistory" data-custid="<?php echo (int) $row['UserId']; ?>" data-custname="<?php echo htmlspecialchars($row['CustName'], ENT_QUOTES); ?>">History</button>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
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

<div class="modal fade" id="insuranceHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insurance Process History - <span id="insuranceHistoryCustomerName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="insuranceHistoryContent">
                <div class="text-center text-muted py-4">Loading...</div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable({
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            title: 'Renewed Insurance',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        }]
    });

    $(document).on('click', '.btnInsuranceHistory', function() {
        var custId = $(this).data('custid');
        var custName = $(this).data('custname') || '';
        $('#insuranceHistoryCustomerName').text(custName);
        $('#insuranceHistoryContent').html('<div class="text-center text-muted py-4">Loading...</div>');
        $('#insuranceHistoryModal').modal('show');

        $.get('ajax-insurance-site-history.php', { CustId: custId }, function(html) {
            $('#insuranceHistoryContent').html(html);
        }).fail(function() {
            $('#insuranceHistoryContent').html('<div class="alert alert-danger mb-0">Could not load insurance history.</div>');
        });
    });
});
</script>
</body>
</html>
