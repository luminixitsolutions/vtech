<?php 
	$user_id = $_SESSION['Admin']['id'];
	 $sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
	$row77 = getRecord($sql77);
	$Roll = $row77['Roll'];
	$UserCat = $row77['CatId'];
	$Options = adminResolveMenuOptionsFromUserRow($row77);
	$BranchId = $row77['BranchId'];
	$MulBranchId = $row77['MulBranchId'];

if (!function_exists('reportSidebarOptionAllowed')) {
function reportSidebarOptionAllowed($Options, $optionId)
{
	return in_array((string) $optionId, $Options, true) || in_array($optionId, $Options, true);
}
}
if (!function_exists('reportSidebarAnyOption')) {
function reportSidebarAnyOption($Options, array $optionIds)
{
	foreach ($optionIds as $optionId) {
		if (reportSidebarOptionAllowed($Options, $optionId)) {
			return true;
		}
	}
	return false;
}
}
if (!function_exists('reportSidebarPageActive')) {
function reportSidebarPageActive($Page, array $pages)
{
	return in_array($Page, $pages, true);
}
}
if (!function_exists('reportSidebarOpenClass')) {
function reportSidebarOpenClass($Page, array $pages)
{
	return reportSidebarPageActive($Page, $pages) ? ' open active' : '';
}
}
if (!function_exists('reportSidebarActiveDot')) {
function reportSidebarActiveDot($Page, array $pages)
{
	if (!reportSidebarPageActive($Page, $pages)) {
		return '';
	}
	return '<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>';
}
}

$contractBillingPages = array(
	'Contractor-Commision-Report', 'Contractor-Commision-Details',
	'Contractor-Payment-Dashboard', 'Contractor-Payment-Add', 'Contractor-Payment-History',
	'Contractor-Advance-Payment-Add', 'Contractor-Advance-Payment-History',
);
$dispatchReportPages = array(
	'View-Customers', 'Sell-Reports', 'Material-Dispatch-Reports', 'Trip-Reports',
	'Dispatch-Report', 'Dispatch-Calling-Report',
);
$stockReportPages = array(
	'Stock-Report2', 'Stock-Report', 'Stock-Report2-Inward-Detail', 'Stock-Report2-Outward-Detail',
	'Stock-Report-Sell-Detail', 'Store-Stock-Report', 'Store-Stock-Report-2',
	'Store-Stock-Report-2-Credit-Detail', 'Store-Stock-Report-2-Debit-Detail',
	'Serial-Location-Report', 'Store-Incharge-Stock-Report', 'Dispatch-Stock-Report',
	'Dispatch-Stock-Report-Tab',
);
$attendanceReportPages = array('Attendance-Report', 'Attendance-Report-2');
$vehicleReportPages = array(
	'Vehical-Report', 'Driver-Trip-Billing-List', 'Driver-Trip-Billing-Add',
	'Driver-Trip-Billing-View', 'Driver-Trip-Billing-Report', 'Driver-Trip-Billing-Summary',
);
$siteReportPages = array(
	'Field-Survey-Report', 'Installation-Report', 'Inspection-Report', 'Site-Engineer-Reports',
);
$callingReportPages = array(
	'Before-Installation-Calling-Report', 'After-Installation-Calling-Report',
	'Before-Inspection-Calling-Report', 'Beneficiary-Selection-Calling-Report',
);
$delayReportPages = array('Delay-Calculation-Report', 'Delay-Calculation-Report-2');
$employeeTrackingPages = array('Employee-Tracking-Dashboard', 'Employee-Tracking');
 ?>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>

 <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="../dashboard.php"><img src="../logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="../dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-user"></i>
                <div><?php echo $row77['Fname']." ".$row77['Lname']; ?></div>
            </a>
        </li> 
        <li class="sidenav-item">
            <a href="../dashboard.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
            </a>
        </li>

          <?php if (userHasAnyMenuOption($Options, menuAccessReportsOptionIds())) { ?>
          <li class="sidenav-item">
            <a href="report-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Report Dashboard</div>
            </a>
        </li> 
          <?php } ?>

          <?php if (reportSidebarOptionAllowed($Options, 142)) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $contractBillingPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-file-text"></i>
<div>Contract Billing</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="contractor-commision-report.php" class="sidenav-link">
<div>Contractor Billing Report</div>
<?php echo reportSidebarActiveDot($Page, array('Contractor-Commision-Report', 'Contractor-Commision-Details')); ?>
</a>
</li>
<li class="sidenav-item">
<a href="contractor-payment-dashboard.php" class="sidenav-link">
<div>Contractor Payment Dashboard</div>
<?php echo reportSidebarActiveDot($Page, array('Contractor-Payment-Dashboard', 'Contractor-Payment-Add', 'Contractor-Payment-History')); ?>
</a>
</li>
<li class="sidenav-item">
<a href="contractor-advance-payment-add.php" class="sidenav-link">
<div>Contractor Advance Payment</div>
<?php echo reportSidebarActiveDot($Page, array('Contractor-Advance-Payment-Add', 'Contractor-Advance-Payment-History')); ?>
</a>
</li>
</ul>
</li>
          <?php } ?>

          <?php if (reportSidebarAnyOption($Options, array(160, 29, 120, 115, 105, 109))) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $dispatchReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-share-2"></i>
<div>Dispatch Reports</div>
</a>
<ul class="sidenav-menu">
<?php if (reportSidebarOptionAllowed($Options, 160)) { ?>
<li class="sidenav-item">
<a href="dispatch-customer-csv-report.php" class="sidenav-link">
<div>Dispatch Customer CSV Report</div>
<?php echo reportSidebarActiveDot($Page, array('Dispatch-Customer-CSV-Report', 'View-Customers')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 29)) { ?>
<li class="sidenav-item">
<a href="sell-report.php" class="sidenav-link">
<div>Delivery Challan Report</div>
<?php echo reportSidebarActiveDot($Page, array('Sell-Reports')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 120)) { ?>
<li class="sidenav-item">
<a href="dispatch-material-report.php" class="sidenav-link">
<div>Material Dispatch Report</div>
<?php echo reportSidebarActiveDot($Page, array('Material-Dispatch-Reports')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 115)) { ?>
<li class="sidenav-item">
<a href="trip-report.php" class="sidenav-link">
<div>Trip Report</div>
<?php echo reportSidebarActiveDot($Page, array('Trip-Reports')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 105)) { ?>
<li class="sidenav-item">
<a href="dispatch-report.php" class="sidenav-link">
<div>Dispatch Report</div>
<?php echo reportSidebarActiveDot($Page, array('Dispatch-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 109)) { ?>
<li class="sidenav-item">
<a href="dispatch-calling-report.php" class="sidenav-link">
<div>Dispatch Calling Report</div>
<?php echo reportSidebarActiveDot($Page, array('Dispatch-Calling-Report')); ?>
</a>
</li>
<?php } ?>
</ul>
</li>
          <?php } ?>

          <?php
          $hasStockMenu = reportSidebarAnyOption($Options, array(30, 31, 101, 184, 183, 102, 103));
          if ($hasStockMenu) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $stockReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-package"></i>
<div>Stock Reports</div>
</a>
<ul class="sidenav-menu">
<?php if (reportSidebarOptionAllowed($Options, 30)) { ?>
<li class="sidenav-item">
<a href="stock-report2.php" class="sidenav-link">
<div>Store Inward &amp; Outward Report</div>
<?php echo reportSidebarActiveDot($Page, array('Stock-Report2', 'Stock-Report2-Inward-Detail', 'Stock-Report2-Outward-Detail')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 31)) { ?>
<li class="sidenav-item">
<a href="stock-report.php" class="sidenav-link">
<div>Outstanding Stock Report</div>
<?php echo reportSidebarActiveDot($Page, array('Stock-Report', 'Stock-Report-Sell-Detail')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 101)) { ?>
<li class="sidenav-item">
<a href="store-stock-report.php" class="sidenav-link">
<div>Store Stock Movement Report</div>
<?php echo reportSidebarActiveDot($Page, array('Store-Stock-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 184) || reportSidebarOptionAllowed($Options, 101)) { ?>
<li class="sidenav-item">
<a href="store-stock-report-2.php" class="sidenav-link">
<div>Store Stock Summary Report</div>
<?php echo reportSidebarActiveDot($Page, array('Store-Stock-Report-2', 'Store-Stock-Report-2-Credit-Detail', 'Store-Stock-Report-2-Debit-Detail')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 183) || reportSidebarOptionAllowed($Options, 101) || reportSidebarOptionAllowed($Options, 103)) { ?>
<li class="sidenav-item">
<a href="serial-location-report.php" class="sidenav-link">
<div>Serial No Location Report</div>
<?php echo reportSidebarActiveDot($Page, array('Serial-Location-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 102)) { ?>
<li class="sidenav-item">
<a href="store-item-report.php" class="sidenav-link">
<div>Store Incharge Stock Report</div>
<?php echo reportSidebarActiveDot($Page, array('Store-Incharge-Stock-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 103)) { ?>
<li class="sidenav-item">
<a href="dispatch-officer-stock-report.php" class="sidenav-link">
<div>Dispatch Officer Stock Report</div>
<?php echo reportSidebarActiveDot($Page, array('Dispatch-Stock-Report', 'Dispatch-Stock-Report-Tab')); ?>
</a>
</li>
<?php } ?>
</ul>
</li>
          <?php } ?>

          <?php if (reportSidebarOptionAllowed($Options, 38)) { ?>
<li class="sidenav-item">
<a href="all-customer-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-user-check"></i>
<div>Customer Report</div>
<?php echo reportSidebarActiveDot($Page, array('Customer-Report', 'View-Customers')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 39)) { ?>
<li class="sidenav-item">
<a href="daily-record-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-book"></i>
<div>Daily Record Report</div>
<?php echo reportSidebarActiveDot($Page, array('Daily-Record-Report')); ?>
</a>
</li>
<?php } if (reportSidebarAnyOption($Options, array(99, 185))) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $attendanceReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-calendar"></i>
<div>Attendance Reports</div>
</a>
<ul class="sidenav-menu">
<?php if (reportSidebarOptionAllowed($Options, 99)) { ?>
<li class="sidenav-item">
<a href="attendance-report.php" class="sidenav-link">
<div>Attendance Report</div>
<?php echo reportSidebarActiveDot($Page, array('Attendance-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 185) || reportSidebarOptionAllowed($Options, 99)) { ?>
<li class="sidenav-item">
<a href="attendance-report-2.php" class="sidenav-link">
<div>Attendance Summary Report</div>
<?php echo reportSidebarActiveDot($Page, array('Attendance-Report-2')); ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 100)) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $vehicleReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-navigation"></i>
<div>Vehicle Report</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="vehical-report.php" class="sidenav-link">
<div>Vehicle Entry Report</div>
<?php echo reportSidebarActiveDot($Page, array('Vehical-Report')); ?>
</a>
</li>
<li class="sidenav-item">
<a href="driver-trip-billing-report.php" class="sidenav-link">
<div>Driver Trip Billing Report</div>
<?php echo reportSidebarActiveDot($Page, array('Driver-Trip-Billing-Report')); ?>
</a>
</li>
<li class="sidenav-item">
<a href="driver-trip-billing-summary.php" class="sidenav-link">
<div>Driver Trip Billing Summary</div>
<?php echo reportSidebarActiveDot($Page, array('Driver-Trip-Billing-Summary')); ?>
</a>
</li>
<li class="sidenav-item">
<a href="driver-trip-billings.php" class="sidenav-link">
<div>Driver Trip Billing Manage</div>
<?php echo reportSidebarActiveDot($Page, array('Driver-Trip-Billing-List', 'Driver-Trip-Billing-Add', 'Driver-Trip-Billing-View')); ?>
</a>
</li>
</ul>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 65)) { ?>
<li class="sidenav-item">
<a href="dealer-report.php" class="sidenav-link">
      <i class="sidenav-icon feather icon-users"></i>
<div>Dealer Report</div>
<?php echo reportSidebarActiveDot($Page, array('Dealer-Report', 'View-Customers')); ?>
</a>
</li>
<?php } if (reportSidebarAnyOption($Options, array(104, 106, 107, 108))) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $siteReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-map-pin"></i>
<div>Site Reports</div>
</a>
<ul class="sidenav-menu">
<?php if (reportSidebarOptionAllowed($Options, 104)) { ?>
<li class="sidenav-item">
<a href="field-survey-report.php" class="sidenav-link">
<div>Field Survey Report</div>
<?php echo reportSidebarActiveDot($Page, array('Field-Survey-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 106)) { ?>
<li class="sidenav-item">
<a href="installation-report.php" class="sidenav-link">
<div>Installation Report</div>
<?php echo reportSidebarActiveDot($Page, array('Installation-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 107)) { ?>
<li class="sidenav-item">
<a href="inspection-report.php" class="sidenav-link">
<div>Inspection Report</div>
<?php echo reportSidebarActiveDot($Page, array('Inspection-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 108)) { ?>
<li class="sidenav-item">
<a href="site-engineer-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div>Site Engineer Report</div>
<?php echo reportSidebarActiveDot($Page, array('Site-Engineer-Reports')); ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } if (reportSidebarAnyOption($Options, array(110, 111, 112, 119))) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $callingReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-phone"></i>
<div>Calling Reports</div>
</a>
<ul class="sidenav-menu">
<?php if (reportSidebarOptionAllowed($Options, 110)) { ?>
<li class="sidenav-item">
<a href="before-installation-calling-report.php" class="sidenav-link">
<div>Before Installation Calling Report</div>
<?php echo reportSidebarActiveDot($Page, array('Before-Installation-Calling-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 111)) { ?>
<li class="sidenav-item">
<a href="after-installation-calling-report.php" class="sidenav-link">
<div>After Installation Calling Report</div>
<?php echo reportSidebarActiveDot($Page, array('After-Installation-Calling-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 112)) { ?>
<li class="sidenav-item">
<a href="before-inspection-calling-report.php" class="sidenav-link">
<div>Before Inspection Calling Report</div>
<?php echo reportSidebarActiveDot($Page, array('Before-Inspection-Calling-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 119)) { ?>
<li class="sidenav-item">
<a href="beneficiary-selection-calling-report.php" class="sidenav-link">
<div>Beneficiary Selection Calling Report</div>
<?php echo reportSidebarActiveDot($Page, array('Beneficiary-Selection-Calling-Report')); ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } if (reportSidebarAnyOption($Options, array(143, 186))) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $delayReportPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-clock"></i>
<div>Delay Calculation Reports</div>
</a>
<ul class="sidenav-menu">
<?php if (reportSidebarOptionAllowed($Options, 143)) { ?>
<li class="sidenav-item">
<a href="delay-calculation-report.php" class="sidenav-link">
<div>Delay Calculation Report</div>
<?php echo reportSidebarActiveDot($Page, array('Delay-Calculation-Report')); ?>
</a>
</li>
<?php } if (reportSidebarOptionAllowed($Options, 186) || reportSidebarOptionAllowed($Options, 143)) { ?>
<li class="sidenav-item">
<a href="delay-calculation-report-2.php" class="sidenav-link">
<div>Delay Calculation Summary Report</div>
<?php echo reportSidebarActiveDot($Page, array('Delay-Calculation-Report-2')); ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } if ((function_exists('adminUserHasFullMenuAccess') && adminUserHasFullMenuAccess($Roll)) || reportSidebarOptionAllowed($Options, 187)) { ?>
        <li class="sidenav-item<?php echo reportSidebarOpenClass($Page, $employeeTrackingPages); ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
      <i class="sidenav-icon feather icon-pie-chart"></i>
<div>Employee Tracking</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="employee-tracking-dashboard.php" class="sidenav-link">
<div>Employee Tracking Dashboard</div>
<?php echo reportSidebarActiveDot($Page, array('Employee-Tracking-Dashboard')); ?>
</a>
</li>
<li class="sidenav-item">
<a href="employee-tracking-report.php" class="sidenav-link">
<div>Employee Tracking Report</div>
<?php echo reportSidebarActiveDot($Page, array('Employee-Tracking')); ?>
</a>
</li>
</ul>
</li>
<?php } ?>
        
    </ul>
</div>
