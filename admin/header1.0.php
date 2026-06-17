<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
include_once __DIR__ . '/inc-menu-option-groups.php';
$Options = adminResolveMenuOptionsFromUserRow($row77);
$MenuOptions = adminUserRawMenuOptions($row77);
$BranchId = $row77['BranchId'];
$ImmediateBoss = $row77['ImmediateBoss'];
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
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li> 
    
     <?php  if(in_array("44", $MenuOptions) || in_array("45", $MenuOptions) || in_array("46", $MenuOptions) || in_array("47", $MenuOptions) || in_array("48", $MenuOptions) || in_array("49", $MenuOptions) || in_array("50", $MenuOptions) || in_array("51", $MenuOptions) || in_array("52", $MenuOptions) || in_array("63", $MenuOptions)) { ?>
     
        <li class="sidenav-item">
            <a href="lead_management/lead-management-dashboard.php" class="sidenav-link">
               <i class="sidenav-icon feather icon-layers"></i>
                <div>Lead Management</div>
                
            </a>
        </li>
 <?php }  if(userHasAnyMenuOption($Options, getMenuOptionGroups()['Master Management'])) { ?>
        <li class="sidenav-item">
            <a href="master_management/masters-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Master Management</div>
                
            </a>
        </li>
<?php } ?>

 <?php if(in_array("24", $MenuOptions)) {?>
        <li class="sidenav-item">
            <a href="product_management/product-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-box"></i>
                <div>Product Management</div>
                
            </a>
        </li>
<?php } if(in_array("18", $MenuOptions) || in_array("19", $MenuOptions) || in_array("20", $MenuOptions) || in_array("21", $MenuOptions) || in_array("22", $MenuOptions) || in_array("23", $MenuOptions)) {?>
        <li class="sidenav-item">
            <a href="user_management/account-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-users"></i>
                <div>Employee Management</div>
                
            </a>
        </li>

<?php } if(in_array("55", $MenuOptions) || in_array("79", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-user-check"></i>
<div>Assign Customers</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("55", $MenuOptions)) {?>
<li class="sidenav-item">
<a href="assign-customers-to-co-ordinator.php?CoordinatorStatus=0" class="sidenav-link">
<div> Assign Pump Customers To Co-ordinator</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("79", $MenuOptions)) {?> 
        <li class="sidenav-item">
<a href="assign-customers-to-field-survey.php?FieldSurveyStatus=0" class="sidenav-link">
<div> Assign Pump Customers To Field Survey</div>
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
        
<?php } if(in_array("130", $MenuOptions) || in_array("131", $MenuOptions)) {?>

<li class="sidenav-item <?php if($MainPage=='Production-Plan') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-file-plus"></i>
<div>Tentative Production Plan</div>
</a>
<ul class="sidenav-menu">

 <?php if(in_array("130", $MenuOptions)) {?>
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

 <?php } if(in_array("131", $MenuOptions)) {?>
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
</li>

<?php } if(in_array("80", $MenuOptions) || in_array("81", $MenuOptions)) {?> 

    <li class="sidenav-item <?php if($MainPage=='Pump-Survey') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-grid"></i>
<div>Pump Survey</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("80", $MenuOptions)) {?>
<li class="sidenav-item">
<a href="co-ordinator-survey.php" class="sidenav-link">
<div>Pump Co-ordinator Survey</div>
<?php if($Page=='Telephonic-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("81", $MenuOptions)) {?> 
<li class="sidenav-item">
<a href="field-survey.php" class="sidenav-link">
<div> Pump Field Survey</div>
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

<?php } if(in_array("132", $MenuOptions) || in_array("133", $MenuOptions)) {?>

<li class="sidenav-item <?php if($MainPage=='Final-Production-Plan') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-file-text"></i>
<div>Final Production Plan</div>
</a>
<ul class="sidenav-menu">

 <?php if(in_array("132", $MenuOptions)) {?>
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

 <?php } if(in_array("133", $MenuOptions)) {?>
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
</li>
 <?php } if(in_array("134", $MenuOptions)) {?>
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
        <li class="sidenav-item">
            <a href="under-production-beneficiary-stock-report.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-layers"></i>
                <div>Done beneficiary — required stock</div>
                <?php if($Page=='Under-Production-Stock-Report' || $Page=='Under-Production-Required-Stock') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php } if(in_array("25", $MenuOptions)) {?>

<li class="sidenav-item <?php if($MainPage=='Purchase-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
   <i class="sidenav-icon feather icon-activity"></i>
<div>Purchase Order</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $MenuOptions)) {?>
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



<?php } if(in_array("58", $MenuOptions)) {?>
         <li class="sidenav-item">
            <a href="assign-to-store-incharge.php?StoreInchStatus=0" class="sidenav-link">
                <i class="sidenav-icon feather icon-share-2"></i>
                <div>Assign Beneficiary To Store</div>
                <?php if($Page=='Assign-Store-Incharge') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        <?php } if(in_array("59", $MenuOptions)) {?>
         <li class="sidenav-item">
            <a href="approve-store-incharge.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-check-circle"></i>
                <div>Approve By Store Incharge</div>
                <?php if($Page=='Approve-Store-Incharge') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        <?php } if(menuAccessShowStoreAssignParent($MenuOptions)) {?>

            <li class="sidenav-item <?php if($MainPage=='Assign-Order-Store') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Assign Items To Store</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowStoreAssignItemsLink($MenuOptions)) {?>
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
<?php if(menuAccessShowStoreViewAssignItemsLink($MenuOptions)) {?>
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
<?php } ?>
</ul>
</li>
<?php } if(in_array("60", $MenuOptions)) {?>
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

 <?php } if(menuAccessShowDispatchAssignParent($MenuOptions)) {?>

            <li class="sidenav-item <?php if($MainPage=='Assign-Items-Store-Executive') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Assign Items To Dispatch Officier</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowDispatchAssignItemsLink($MenuOptions)) {?>
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
<?php if(menuAccessShowDispatchViewAssignItemsLink($MenuOptions)) {?>
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
<?php } ?>
</ul>
</li>



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
<?php } if(in_array("26", $MenuOptions)) {?>

<li class="sidenav-item <?php if($MainPage=='Sell') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Delivery Challan</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $MenuOptions)) {?>
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
<li class="sidenav-item <?php if($Page=='View-Service-Challan') {?> active <?php } ?>">
<a href="view-service-challans.php" class="sidenav-link">
<div> Service Challan</div>
<?php if($Page=='View-Service-Challan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='View-Partial-Material-Challan') {?> active <?php } ?>">
<a href="view-partial-material-challans.php" class="sidenav-link">
<div> Partial Material Challan</div>
<?php if($Page=='View-Partial-Material-Challan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("82", $MenuOptions)) {?>
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
<?php } if(in_array("83", $MenuOptions)) {?>
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
        <?php } if(in_array("68", $MenuOptions)) {?>

    <li class="sidenav-item">
<a href="installation-project-dashboard.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div> Pump Installation</div>
<?php if($Page=='Pump-Installation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
        

    <?php } if(in_array("84", $MenuOptions)) {?>
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

<?php } if(in_array("28", $MenuOptions) || in_array("135", $MenuOptions) || in_array("136", $MenuOptions) || in_array("137", $MenuOptions)) {?>

<li class="sidenav-item <?php if($MainPage=='Service') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Services</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("137", $MenuOptions)) {?>
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
<?php } if(in_array("135", $MenuOptions)) {?>
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
<?php } if(in_array("136", $MenuOptions)) {?>
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
<?php } if(in_array("14", $MenuOptions)) {?>
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
<?php } ?>
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
<li class="sidenav-item <?php if($Page=='View-Service-Challan') {?> active <?php } ?>">
<a href="view-service-challans.php" class="sidenav-link">
<div> Service Challan</div>
<?php if($Page=='View-Service-Challan') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item <?php if($Page=='Insurance-Service-Complaint' || $Page=='Fill-Insurance-Details') {?> active <?php } ?>">
<a href="view-insurance-service-complaints.php" class="sidenav-link">
<div> Insurance Service Complaint</div>
</a>
</li>
<li class="sidenav-item <?php if($Page=='Done-Insurance-Process') {?> active <?php } ?>">
<a href="view-done-insurance-process.php" class="sidenav-link">
<div> Done Insurance Process</div>
</a>
</li>
</ul>
</li>
<?php } ?>

<?php if(in_array("168", $MenuOptions) || in_array("169", $MenuOptions) || in_array("170", $MenuOptions) || in_array("171", $MenuOptions) || in_array("172", $MenuOptions) || in_array("173", $MenuOptions) || in_array("121", $MenuOptions)) {?>
<li class="sidenav-item <?php if($MainPage=='Insurance') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-shield"></i>
<div>Insurance Site</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("168", $MenuOptions) || in_array("121", $MenuOptions)) {?>
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
<?php } if(in_array("169", $MenuOptions)) {?>
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
<?php } if(in_array("170", $MenuOptions)) {?>
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
<?php } if(in_array("171", $MenuOptions)) {?>
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
<?php } if(in_array("172", $MenuOptions)) {?>
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
<?php } if(in_array("173", $MenuOptions)) {?>
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

<?php if(in_array("93", $MenuOptions)) {?>
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
<?php } if(in_array("94", $MenuOptions)) {?>
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
<?php } if(in_array("95", $MenuOptions)) {?>
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
<?php } if(in_array("96", $MenuOptions)) {?>
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
        <?php } if(in_array("118", $MenuOptions)) {?>
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


<?php }  if(in_array("69", $MenuOptions)) {?>

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
<?php if(userHasAnyMenuOption($Options, getMenuOptionGroups()['Installation Workflow'])) {?>
      <li class="sidenav-item <?php if($MainPage=='Installation'){?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
    <i class="sidenav-icon feather icon-activity"></i>
    <div>Installation Workflow</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("174", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='Installation-Dashboard'){?> active <?php } ?>">
        <a href="admin-installation-dashboard.php" class="sidenav-link"><div>Installation Dashboard</div></a>
    </li>
<?php } if(in_array("175", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='Assign-Coordinator'){?> active <?php } ?>">
        <a href="pending-installations.php" class="sidenav-link"><div>Assign Coordinator</div></a>
    </li>
<?php } if(in_array("176", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='Coordinator-Sites'){?> active <?php } ?>">
        <a href="coordinator-assigned-sites.php" class="sidenav-link"><div>Coordinator Action</div></a>
    </li>
<?php } if(in_array("177", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='Manager-Pending'){?> active <?php } ?>">
        <a href="manager-pending-installations.php" class="sidenav-link"><div>Manager Action</div></a>
    </li>
<?php } if(in_array("178", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='GM-Pending'){?> active <?php } ?>">
        <a href="gm-pending-installations.php" class="sidenav-link"><div>General Manager Action</div></a>
    </li>
<?php } if(in_array("179", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='GM-Extensions'){?> active <?php } ?>">
        <a href="gm-extension-requests.php" class="sidenav-link"><div>GM Extension Requests</div></a>
    </li>
<?php } if(in_array("180", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='BH-Pending'){?> active <?php } ?>">
        <a href="business-head-pending.php" class="sidenav-link"><div>Business Head Action</div></a>
    </li>
<?php } if(in_array("181", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='BH-Extensions'){?> active <?php } ?>">
        <a href="bh-extension-requests.php" class="sidenav-link"><div>BH Extension Requests</div></a>
    </li>
<?php } if(in_array("182", $MenuOptions)) {?>
    <li class="sidenav-item <?php if($Page=='Dispute-Sites'){?> active <?php } ?>">
        <a href="dispute-sites.php" class="sidenav-link"><div>Dispute Sites</div></a>
    </li>
<?php } ?>
</ul>
</li>
<?php } ?>
<?php if(in_array("138", $MenuOptions) || in_array("139", $MenuOptions)) {?>
<li class="sidenav-item <?php if($MainPage=='Trip-Details') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Trip Details</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("138", $MenuOptions)) {?>
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
<?php } if(in_array("139", $MenuOptions)) {?>
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
<?php if(in_array("144", $MenuOptions)) {?>
 <li class="sidenav-item">
<a href="approve-attendance.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Approve Attendance</div>
<?php if($Page=='Approve-Attendance') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } ?>
<!-- <li class="sidenav-item">
            <a href="task-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Task Management</div>
                
            </a>
        </li> -->
        
<?php  if(in_array("37", $MenuOptions)) {?>
       <!--  <li class="sidenav-item">
            <a href="ecommerce-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>E-Commerce Management</div>
                
            </a>
        </li> -->
 

<?php } if(userHasAnyMenuOption($Options, getMenuOptionGroups()['Reports'])) {?>

   <li class="sidenav-item">
            <a href="report_management/report-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Reports</div>
                
            </a>
        </li>
        
     <?php } ?>

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