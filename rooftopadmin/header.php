<?php
$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
if (!is_array($row77) || empty($row77['id'])) {
    header('Location: logout.php');
    exit;
}
$Roll = (int) ($row77['Roll'] ?? 0);
$UserCat = $row77['CatId'] ?? '';
$BranchId = $row77['BranchId'] ?? '';
$RooftopBranchId = $row77['RooftopBranchId'] ?? '';
$MulRooftopBranchId = $row77['MulRooftopBranchId'] ?? '';
if (!function_exists('adminResolveMenuOptionsFromUserRow')) {
    require_once __DIR__ . '/inc-menu-option-groups.php';
}
$Options = adminResolveMenuOptionsFromUserRow($row77);
?>
<div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="dashboard.php"><img src="logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 150px;"></a>
                    </span>
                    
                   <!--<a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $row77['Fname']." ".$row77['Lname']; ?></a>-->
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-user"></i>
                <div><?php echo $row77['Fname']." ".$row77['Lname']; ?></div>
                
            </a>
        </li> 
        <li class="sidenav-item">
            <a href="dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>

        <li class="sidenav-item<?php if (!empty($MainPage) && $MainPage === 'Whatsapp-SMS') { ?> active<?php } ?>">
            <a href="send-sms.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-message-circle"></i>
                <div>Whatsapp SMS</div>
            </a>
        </li>
    
     <?php  if(in_array("44", $Options) || in_array("45", $Options) || in_array("46", $Options) || in_array("47", $Options) || in_array("48", $Options) || in_array("49", $Options) || in_array("50", $Options) || in_array("51", $Options) || in_array("52", $Options) || in_array("63", $Options)) { ?>
     
        <li class="sidenav-item">
            <a href="lead_management/lead-management-dashboard.php" class="sidenav-link">
               <i class="sidenav-icon feather icon-layers"></i>
                <div>Lead Management</div>
                
            </a>
        </li>
 <?php }  if(in_array("1", $Options) || in_array("2", $Options) || in_array("3", $Options) || in_array("4", $Options) || in_array("5", $Options) || in_array("6", $Options) || in_array("7", $Options) || in_array("8", $Options) || in_array("9", $Options) || in_array("12", $Options) || in_array("13", $Options) || in_array("15", $Options) || in_array("16", $Options) || in_array("53", $Options) || in_array("54", $Options)) { ?>
        <li class="sidenav-item">
            <a href="master_management/masters-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Master Management</div>
                
            </a>
        </li>
<?php } ?>

 <?php if(in_array("24", $Options)) {?>
        <li class="sidenav-item">
            <a href="product_management/product-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-box"></i>
                <div>Product Management</div>
                
            </a>
        </li>
<?php } if(in_array("18", $Options) || in_array("19", $Options) || in_array("20", $Options) || in_array("21", $Options) || in_array("22", $Options) || in_array("23", $Options)) {?>
        <li class="sidenav-item">
            <a href="user_management/account-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-users"></i>
                <div>User Account Management</div>
                
            </a>
        </li>

<?php } if(in_array("55", $Options) || in_array("79", $Options)) {?>
    <li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-user-check"></i>
<div>Assign Customers</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("55", $Options)) {?>
<li class="sidenav-item">
<a href="assign-customers-to-co-ordinator.php?CoordinatorStatus=0" class="sidenav-link">
<div> Assign Customers To Co-ordinator</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("79", $Options)) {?> 
        <li class="sidenav-item">
<a href="assign-customers-to-field-survey.php?FieldSurveyStatus=0" class="sidenav-link">
<div> Assign Customers To Field Survey</div>
<?php if($Page=='Assign-Customers-Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  
<?php } ?>
</ul>
</li>
        
<?php } if(in_array("130", $Options) || in_array("131", $Options)) {?>

<!-- <li class="sidenav-item <?php if($MainPage=='Production-Plan') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-file-plus"></i>
<div>Tentative Production Plan</div>
</a>
<ul class="sidenav-menu">

 <?php if(in_array("130", $Options)) {?>
<li class="sidenav-item">
<a href="bos-tentative-production-plan.php?MinLimit=0" class="sidenav-link">
<div> BOS Production Plan</div>
<?php if($Page=='BOS-Production-Plan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <?php } if(in_array("131", $Options)) {?>
<li class="sidenav-item">
<a href="stucture-tentative-production-plan.php?MinLimit=0" class="sidenav-link">
<div> Stucture Production Plan</div>
<?php if($Page=='Stucture-Production-Plan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

</ul>
</li> -->

<?php } if(in_array("147", $Options) || in_array("148", $Options)) {?> 

    <li class="sidenav-item <?php if($MainPage=='Pump-Survey') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-grid"></i>
<div>Rooftop Survey</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("147", $Options)) {?>
<li class="sidenav-item">
<a href="co-ordinator-survey.php" class="sidenav-link">
<div>Rooftop Co-ordinator Survey</div>
<?php if($Page=='Telephonic-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("148", $Options)) {?> 
<li class="sidenav-item">
<a href="field-survey.php" class="sidenav-link">
<div> Rooftop Field Survey</div>
<?php if($Page=='Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } ?>
</ul>
</li>

<?php } if(in_array("132", $Options) || in_array("133", $Options)) {?>

<!-- <li class="sidenav-item <?php if($MainPage=='Final-Production-Plan') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-file-text"></i>
<div>Final Production Plan</div>
</a>
<ul class="sidenav-menu">

 <?php if(in_array("132", $Options)) {?>
<li class="sidenav-item">
<a href="bos-final-production-plan.php?MinLimit=0" class="sidenav-link">
<div> BOS Production Plan</div>
<?php if($Page=='BOS-Final-Production-Plan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <?php } if(in_array("133", $Options)) {?>
<li class="sidenav-item">
<a href="stucture-final-production-plan.php?MinLimit=0" class="sidenav-link">
<div> Stucture Production Plan</div>
<?php if($Page=='Stucture-Final-Production-Plan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li> -->
 <?php } if(in_array("134", $Options)) {?>
<li class="sidenav-item">
            <a href="under-production-beneficiary.php?UnderProdStatus=0" class="sidenav-link">
                <i class="sidenav-icon feather icon-check-circle"></i>
                <div>Under Production Beneficiary</div>
                <?php if($Page=='Under-Production-Beneficiary') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("25", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Purchase-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
   <i class="sidenav-icon feather icon-activity"></i>
<div>Purchase Order</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-purchase-order.php" class="sidenav-link">
<div> Add Purchase Order</div>
<?php if($Page=='Add-Purchase-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-purchase-order.php" class="sidenav-link">
<div> View Purchase Order</div>
<?php if($Page=='View-Purchase-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="delete-bill-no-stock.php" class="sidenav-link">
<div> Delete Bill No Stock</div>
<?php if($Page=='Delete-Bill-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("27", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Quotation') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Performa Invoice (PI)</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="view-quotation-products.php" class="sidenav-link">
<div> PI Products</div>
<?php if($Page=='Quotation-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="add-quotation.php" class="sidenav-link">
<div> Add PI</div>
<?php if($Page=='Add-Quotation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-quotation.php" class="sidenav-link">
<div> View PI</div>
<?php if($Page=='View-Quotation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("58", $Options)) {?>
         <li class="sidenav-item">
            <a href="assign-to-store-incharge.php?StoreInchStatus=0" class="sidenav-link">
                <i class="sidenav-icon feather icon-share-2"></i>
                <div>Assign Beneficiary To Store Incharge</div>
                <?php if($Page=='Assign-Store-Incharge') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        <?php } if(in_array("70", $Options)) {?>

            <li class="sidenav-item <?php if($MainPage=='Assign-Order-Store') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Assign Items To Store</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="distribute-item-store.php" class="sidenav-link">
<div> Assign Items</div>
<?php if($Page=='Assign-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<li class="sidenav-item">
<a href="distribute-item-store-2.php" class="sidenav-link">
<div> Assign Items</div>
<?php if($Page=='Assign-Order-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-distribute-item-store.php" class="sidenav-link">
<div> View Assign Items</div>
<?php if($Page=='View-Assign-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("60", $Options)) {?>
 <li class="sidenav-item">
            <a href="assign-to-dispatch-officer.php?DispatchOfficerStatus=0" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Assign Beneficiary To Dispatch Officer</div>
                <?php if($Page=='Assign-Dispatch-Officer') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

 <?php } if(in_array("71", $Options)) {?>

            <li class="sidenav-item <?php if($MainPage=='Assign-Items-Store-Executive') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Assign Items To Dispatch Officier</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="distribute-item-store-executive.php" class="sidenav-link">
<div> Assign Items</div>
<?php if($Page=='Assign-Store-Executive') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<li class="sidenav-item">
<a href="distribute-item-store-executive-2.php" class="sidenav-link">
<div> Assign Items</div>
<?php if($Page=='Assign-Store-Executive-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-distribute-item-store-executive.php" class="sidenav-link">
<div> View Assign Items</div>
<?php if($Page=='View-Assign-Store-Executive') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if($Roll == 1 || $Roll == 7 || $Roll == 26 || in_array("165", $Options) || in_array("72", $Options)) { ?>
<li class="sidenav-item <?php if($MainPage=='Item-Transfer-Workflow' && ($Page=='Dispatch-To-Store-Transfer' || $Page=='View-Dispatch-To-Store-Transfers' || $Page=='Stock-Location-Report' || $Page=='Serial-Location-Report')) {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-activity"></i>
<div>Transfer Item Dispatch to Store</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item <?php if($Page=='Dispatch-To-Store-Transfer') {?> active <?php } ?>">
<a href="item_transfer_workflow/dispatch-to-store-transfer.php" class="sidenav-link">
<div>Transfer to Store</div>
<?php if($Page=='Dispatch-To-Store-Transfer') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='View-Dispatch-To-Store-Transfers') {?> active <?php } ?>">
<a href="item_transfer_workflow/view-dispatch-to-store-transfers.php" class="sidenav-link">
<div>View Dispatch to Store Transfers</div>
<?php if($Page=='View-Dispatch-To-Store-Transfers') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='Stock-Location-Report') {?> active <?php } ?>">
<a href="item_transfer_workflow/stock-location-report.php" class="sidenav-link">
<div>Stock Location Report</div>
<?php if($Page=='Stock-Location-Report') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='Serial-Location-Report') {?> active <?php } ?>">
<a href="report_management/serial-location-report.php" class="sidenav-link">
<div>Serial No — Location Report</div>
<?php if($Page=='Serial-Location-Report') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if($Roll == 1 || $Roll == 7 || $Roll == 27 || in_array("166", $Options) || in_array("72", $Options)) { ?>
<li class="sidenav-item <?php if($MainPage=='Item-Transfer-Workflow' && ($Page=='Store-To-Store-Transfer' || $Page=='View-Store-To-Store-Transfers' || $Page=='Stock-Location-Report' || $Page=='Serial-Location-Report')) {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-activity"></i>
<div>Transfer Item Store to Store</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item <?php if($Page=='Store-To-Store-Transfer') {?> active <?php } ?>">
<a href="item_transfer_workflow/store-to-store-transfer.php" class="sidenav-link">
<div>Transfer to Another Store</div>
<?php if($Page=='Store-To-Store-Transfer') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='View-Store-To-Store-Transfers') {?> active <?php } ?>">
<a href="item_transfer_workflow/view-store-to-store-transfers.php" class="sidenav-link">
<div>View Store to Store Transfers</div>
<?php if($Page=='View-Store-To-Store-Transfers') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='Stock-Location-Report') {?> active <?php } ?>">
<a href="item_transfer_workflow/stock-location-report.php" class="sidenav-link">
<div>Stock Location Report</div>
<?php if($Page=='Stock-Location-Report') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='Serial-Location-Report') {?> active <?php } ?>">
<a href="report_management/serial-location-report.php" class="sidenav-link">
<div>Serial No — Location Report</div>
<?php if($Page=='Serial-Location-Report') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } ?>

 <!-- <li class="sidenav-item">
            <a href="assign-to-dispatch-officer.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Assign Order To Dispatch Officer</div>
                <?php if($Page=='Assign-Dispatch-Officer') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
 -->
<?php if(in_array("26", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Sell') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Delivery Challan</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="add-sell.php" class="sidenav-link">
<div> Add Delivery Challan</div>
<?php if($Page=='Add-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<li class="sidenav-item">
<a href="add-sell.php" class="sidenav-link">
<div> Create Delivery Challan</div>
<?php if($Page=='Add-Sell-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-sells.php" class="sidenav-link">
<div> View Delivery Challan</div>
<?php if($Page=='View-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="return-challans.php" class="sidenav-link">
<div> Return Challans</div>
<?php if($Page=='Return-Challans' || $Page=='Edit-Challan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("82", $Options)) {?>
         <li class="sidenav-item">
            <a href="assign-challan-to-dispatcher.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Assign Challan For Dispatching To Contractor</div>
                <?php if($Page=='Assign-Challan-Dispatcher') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("83", $Options)) {?>
         <li class="sidenav-item">
            <a href="assign-site-to-installation.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Assign Site For Installation To Contractor</div>
                <?php if($Page=='Assign-Site-Installation') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        <?php } if(in_array("167", $Options)) {?>

    <li class="sidenav-item">
<a href="installation-project-dashboard.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div> Rooftop Installation</div>
<?php if($Page=='Pump-Installation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
        

    <?php } if(in_array("84", $Options)) {?>
         <li class="sidenav-item">
            <a href="assign-site-to-inspection.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Assign Site For Inspection To Contractor</div>
                <?php if($Page=='Assign-Site-Inspection') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

<?php } if($Roll == 1 || $Roll == 7 || in_array("168", $Options) || in_array("169", $Options) || in_array("170", $Options) || in_array("171", $Options) || in_array("172", $Options) || in_array("173", $Options) || in_array("121", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Insurance') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-shield"></i>
<div>Insurance Site</div>
</a>
<ul class="sidenav-menu">
<?php if($Roll == 1 || $Roll == 7 || in_array("168", $Options) || in_array("121", $Options)) {?>
<li class="sidenav-item <?php if($Page=='Insurance-Dashboard') {?> active <?php } ?>">
<a href="insurance-dashboard.php" class="sidenav-link">
<div>Dashboard</div>
<?php if($Page=='Insurance-Dashboard') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if($Roll == 1 || $Roll == 7 || in_array("169", $Options)) {?>
<li class="sidenav-item <?php if($Page=='Pending-Insurance') {?> active <?php } ?>">
<a href="pending-insurance.php" class="sidenav-link">
<div>Pending Insurance</div>
<?php if($Page=='Pending-Insurance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if($Roll == 1 || $Roll == 7 || in_array("170", $Options)) {?>
<li class="sidenav-item <?php if($Page=='Completed-Insurance') {?> active <?php } ?>">
<a href="completed-insurance.php" class="sidenav-link">
<div>Completed Insurance</div>
<?php if($Page=='Completed-Insurance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if($Roll == 1 || $Roll == 7 || in_array("171", $Options)) {?>
<li class="sidenav-item <?php if($Page=='Renewal-Insurance') {?> active <?php } ?>">
<a href="renewal-insurance.php" class="sidenav-link">
<div>Upcoming Renewal Insurance</div>
<?php if($Page=='Renewal-Insurance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if($Roll == 1 || $Roll == 7 || in_array("172", $Options)) {?>
<li class="sidenav-item <?php if($Page=='Expired-Insurance') {?> active <?php } ?>">
<a href="expired-insurance.php" class="sidenav-link">
<div>Expired Insurance</div>
<?php if($Page=='Expired-Insurance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if($Roll == 1 || $Roll == 7 || in_array("173", $Options)) {?>
<li class="sidenav-item <?php if($Page=='Renewed-Insurance') {?> active <?php } ?>">
<a href="renewed-insurance.php" class="sidenav-link">
<div>Renewed Insurance</div>
<?php if($Page=='Renewed-Insurance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } ?>

<?php if($Roll == 1 || $Roll == 7 || in_array("188", $Options) || in_array("189", $Options) || in_array("190", $Options) || in_array("191", $Options) || in_array("192", $Options) || in_array("193", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='MSEDCL-Smart') {?> active <?php } ?>">
<a href="msedcl_smart/" class="sidenav-link">
<i class="sidenav-icon feather icon-zap"></i>
<div>MSEDCL SMART PROJECT</div>
</a>
</li>
<?php } ?>

<?php if(in_array("28", $Options) || in_array("135", $Options) || in_array("136", $Options) || in_array("137", $Options) || in_array("164", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Service') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Services</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("164", $Options)) {?>
    <li class="sidenav-item">
            <a href="service-dashboard.php" class="sidenav-link">
               
                <div>Service Dashboard</div>
                <?php if($Page=='Service-Dashboard') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
    <?php } if(in_array("137", $Options)) {?>
<li class="sidenav-item">
            <a href="beneficiary-service-lists.php" class="sidenav-link">
               
                <div>Service Beneficiary List</div>
                <?php if($Page=='Service-Beneficiary-List') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("135", $Options)) {?>
    <li class="sidenav-item">
            <a href="allocate-complaints-to-engineer.php?EnggAssignStatus=0" class="sidenav-link">
               
                <div>Allocate Complaints To Engineer</div>
                <?php if($Page=='Allocate-Complaints-Engineer') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("136", $Options)) {?>
         <li class="sidenav-item">
            <a href="allocate-not-solved-complaints-to-engineer.php?EnggAssignStatus=0" class="sidenav-link">
               
                <div>Allocate Not Solved Complaints To Engineer</div>
                <?php if($Page=='Allocate-Not-Solved-Complaints-Engineer') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="choose-service-type2.php" class="sidenav-link">
<div> Add Service Complaint</div>
<?php if($Page=='Add-Service-Complaint') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("28", $Options)) {?>
<li class="sidenav-item">
<a href="view-service-module.php" class="sidenav-link">
<div> View Service Complaint</div>
<?php if($Page=='View-Service-Complaint') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item <?php if($Page=='Service-Abstract') {?> active <?php } ?>">
<a href="service-abstract.php" class="sidenav-link">
<div> Service Abstract</div>
<?php if($Page=='Service-Abstract') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("93", $Options)) {?>
<li class="sidenav-item">
            <a href="update-dispatch-calling-status.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Dispatched Calling Confirmation</div>
                <?php if($Page=='Dispatched-Calling-Confirmation') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("94", $Options)) {?>
<li class="sidenav-item">
            <a href="before-installation.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Before Installation</div>
                <?php if($Page=='Before-Installation') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("95", $Options)) {?>
        <li class="sidenav-item">
            <a href="after-installation.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>After Installation</div>
                <?php if($Page=='After-Installation') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("96", $Options)) {?>
         <li class="sidenav-item">
            <a href="before-inspection.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Before Inspection</div>
                <?php if($Page=='Before-Inspection') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        <?php } if(in_array("118", $Options)) {?>
         <li class="sidenav-item">
            <a href="beneficiary-selection.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Beneficiary Selection</div>
                <?php if($Page=='Beneficiary-Selection') {?>
                <div class="pl-1 ml-auto">
                <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>


<?php }  if(in_array("69", $Options)) {?>

<!-- <li class="sidenav-item">
            <a href="dealer-commission.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Dealer Commission</div>
                <?php if($Page=='Dealer-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
            </a>
        </li> -->
        
       <!--  <li class="sidenav-item">
            <a href="dealer-show-balance-amount.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Dealer Show Commission</div>
                <?php if($Page=='Dealer-Show-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
            </a>
        </li> -->
<?php } ?>
<!-- <li class="sidenav-item <?php if($MainPage=='Notifications') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Notifications</div>
</a>
<ul class="sidenav-menu">
  
 <li class="sidenav-item">
<a href="customer-notifications.php" class="sidenav-link">

<div>Customer Notifications</div>
<?php if($Page=='Customer-Notification') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="employee-notifications.php" class="sidenav-link">

<div>Employee Notifications</div>
<?php if($Page=='Employee-Notification') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 

</ul>
</li> -->
<?php if(in_array("138", $Options) || in_array("139", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Trip-Details') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Trip Details</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("138", $Options)) {?>
<li class="sidenav-item">
<a href="running-trips.php" class="sidenav-link">
<div> Running Trips</div>
<?php if($Page=='Running-Trips') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("139", $Options)) {?>
<li class="sidenav-item">
<a href="completed-trips.php" class="sidenav-link">
<div> Completed Trips</div>
<?php if($Page=='Completed-Trips') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>
<?php } ?>
 <!-- <li class="sidenav-item">
<a href="expense-request.php" class="sidenav-link">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Expense Request</div>
<?php if($Page=='Expense-Request') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php if($Roll == 1 || $Roll == 41){?>
<!--  <li class="sidenav-item">
<a href="approve-attendance.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Approve Attendance</div>
<?php if($Page=='Approve-Attendance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<!-- <li class="sidenav-item">
            <a href="task-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Task Management</div>
                
            </a>
        </li> -->
        
<?php  if(in_array("37", $Options)) {?>
       <!--  <li class="sidenav-item">
            <a href="ecommerce-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>E-Commerce Management</div>
                
            </a>
        </li> -->
 

<?php } if(in_array("29", $Options) || in_array("30", $Options) || in_array("31", $Options) || in_array("38", $Options) || in_array("40", $Options) || in_array("65", $Options) || in_array("99", $Options) || in_array("100", $Options) || in_array("101", $Options) || in_array("102", $Options) || in_array("103", $Options) || in_array("104", $Options) || in_array("105", $Options) || in_array("106", $Options) || in_array("107", $Options) || in_array("108", $Options) || in_array("109", $Options) || in_array("110", $Options) || in_array("111", $Options) || in_array("112", $Options)) {?>

   <li class="sidenav-item">
            <a href="report_management/report-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Reports</div>
                
            </a>
        </li>
        
     <?php } ?>
     
     <li class="sidenav-item <?php if($MainPage=='Account') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Account</div>
</a>
<ul class="sidenav-menu">

<li class="sidenav-item">
<a href="receive-amount.php" class="sidenav-link">
<div> Receive Amount</div>
<?php if($Page=='Receive-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php if(in_array("14", $Options)) {?> 
<li class="sidenav-item">
<a href="add-receive-amount.php" class="sidenav-link">
<div> Add Receive Amount</div> 
<?php if($Page=='Add-Receive-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>

<li class="sidenav-item <?php if($MainPage=='Dealer-Commission') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Dealer Commission</div>
</a>
<ul class="sidenav-menu">

<li class="sidenav-item">
<a href="view-dealer-commission.php" class="sidenav-link">
<div> View Dealer Commission</div>
<?php if($Page=='View-Dealer-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php if(in_array("14", $Options)) {?> 
<li class="sidenav-item">
<a href="add-dealer-commission.php" class="sidenav-link">
<div> Pay Dealer Commission</div> 
<?php if($Page=='Add-Dealer-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>

      <li class="sidenav-item">
            <a href="logout.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Logout</div>
                
            </a>
        </li>
        
     <li class="sidenav-item">
            <a href="backup.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>DB Backup</div>
                
            </a>
        </li>
        
      
    </ul>
</div>