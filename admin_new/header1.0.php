<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
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
<div> Assign Pump Customers To Co-ordinator</div>
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
        
<?php } if(in_array("130", $Options) || in_array("131", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Production-Plan') {?> open active <?php } ?>">
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
</li>

<?php } if(in_array("80", $Options) || in_array("81", $Options)) {?> 

    <li class="sidenav-item <?php if($MainPage=='Pump-Survey') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-grid"></i>
<div>Pump Survey</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("80", $Options)) {?>
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
<?php } if(in_array("81", $Options)) {?> 
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

<?php } if(in_array("132", $Options) || in_array("133", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Final-Production-Plan') {?> open active <?php } ?>">
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
</li>
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



<?php } if(in_array("58", $Options)) {?>
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
<?php } if(in_array("26", $Options)) {?>

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
        <?php } if(in_array("68", $Options)) {?>

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

<?php } if(in_array("28", $Options) || in_array("135", $Options) || in_array("136", $Options) || in_array("137", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Service') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Services</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("137", $Options)) {?>
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
<?php if(in_array("144", $Options)) {?>
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