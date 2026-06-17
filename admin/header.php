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
$MulBranchId = $row77['MulBranchId'];
$fileSubmissionReminderCount = function_exists('getFileSubmissionReminderCount')
    ? getFileSubmissionReminderCount()
    : 0;
$fileSubmissionReminderAlert = $fileSubmissionReminderCount > 0;
?>
<style>
@keyframes fileSubmissionReminderBlink {
    0%, 100% { color: #dc3545; opacity: 1; }
    50% { color: #ff1744; opacity: 0.7; }
}
.sidenav-item.file-submission-reminder-alert > .sidenav-link,
.sidenav-item.file-submission-reminder-alert > .sidenav-link .sidenav-icon,
.sidenav-item.file-submission-reminder-alert > .sidenav-link div {
    color: #dc3545 !important;
    animation: fileSubmissionReminderBlink 1s ease-in-out infinite;
}
</style>
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
        
        <li class="sidenav-item">
            <a href="send-sms.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Whatsapp SMS</div>
                
            </a>
        </li> 

        <?php if (userHasAnyMenuOption($Options, menuAccessFileSubmissionOptionIds())) { ?>
        <li class="sidenav-item<?php if (!empty($MainPage) && $MainPage === 'File-Submission-Reminder') { ?> active<?php } ?><?php if (!empty($fileSubmissionReminderAlert)) { ?> file-submission-reminder-alert<?php } ?>">
            <a href="file-submission-reminder.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-bell"></i>
                <div>File submission reminder</div>
            </a>
        </li>
        <?php } ?>

        <li class="sidenav-item <?php if (!empty($MainPage) && $MainPage === 'Leave-Requests') { ?>active<?php } ?>">
            <a href="leave-requests.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-calendar"></i>
                <div>Leave request</div>
            </a>
        </li>
        
         <?php if (userHasAnyMenuOption($Options, menuAccessMpuvnlOptionIds())) { ?>
         <li class="sidenav-item">
            <a href="mpuvnl_management/mpuvnl-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>MPUVNL Management</div>
                
            </a>
        </li>
        <?php } ?>

    
     <?php if (userHasAnyMenuOption($Options, getMenuOptionGroups()['Lead Management'])) { ?>
     
        <li class="sidenav-item">
            <a href="<?php echo htmlspecialchars(menuAccessLeadModuleEntryUrl($Options), ENT_QUOTES, 'UTF-8'); ?>" class="sidenav-link">
               <i class="sidenav-icon feather icon-layers"></i>
                <div>Lead Management</div>
                
            </a>
        </li>
        <?php } if (userHasAnyMenuOption($Options, getMenuOptionGroups()['Pump Application Management'])) { ?>
        <li class="sidenav-item">
            <a href="<?php echo htmlspecialchars(menuAccessPumpApplicationEntryUrl($Options), ENT_QUOTES, 'UTF-8'); ?>" class="sidenav-link">
               <i class="sidenav-icon feather icon-layers"></i>
                <div>Pump Application Management</div>
            </a>
        </li>
        <?php } if(userHasAnyMenuOption($Options, menuAccessDealerLeadOptionIds())) { ?>
     
        <li class="sidenav-item">
            <a href="<?php echo htmlspecialchars(menuAccessDealerLeadEntryUrl($Options), ENT_QUOTES, 'UTF-8'); ?>" class="sidenav-link">
               <i class="sidenav-icon feather icon-layers"></i>
                <div>Dealer Lead Management</div>
                
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

 <?php if(in_array("24", $Options)) {?>
        <li class="sidenav-item">
            <a href="product_management/product-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-box"></i>
                <div>Product Management</div>
                
            </a>
        </li>
<?php } if(userHasAnyMenuOption($Options, getMenuOptionGroups()['User Accounts'])) {?>
        <li class="sidenav-item">
            <a href="user_management/account-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-users"></i>
                <div>Employee/User Management</div>
                
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
 <?php } if(menuAccessShowLegacyModuleScreen($MenuOptions, 134) || menuAccessShowGranularLink($MenuOptions, 224)) {?>
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
        <?php } if(menuAccessShowGranularLink($MenuOptions, 224)) {?>
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
    
    <?php } if(menuAccessShowGranularGroup($MenuOptions, 145)) {?>    
         <li class="sidenav-item <?php if($MainPage=='PDI-Verification') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
   <i class="sidenav-icon feather icon-activity"></i>
<div>PDI Verification</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowGranularLink($MenuOptions, 225)) {?>
<li class="sidenav-item">
<a href="upload-pdi-excel.php" class="sidenav-link">
<div> Upload PDI Excel</div>
<?php if($Page=='Upload-PDI-Verification') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<?php if(menuAccessShowGranularLink($MenuOptions, 226)) {?>
<li class="sidenav-item">
<a href="view-uploaded-pdi.php" class="sidenav-link">
<div> View Uploaded PDI</div>
<?php if($Page=='View-PDI-Verification') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>

<?php } if(menuAccessShowGranularGroup($MenuOptions, 25)) {?>

<li class="sidenav-item <?php if($MainPage=='Purchase-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
   <i class="sidenav-icon feather icon-activity"></i>
<div>Purchase Order</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowGranularLink($MenuOptions, 228)) {?>
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
<?php if(menuAccessShowGranularLink($MenuOptions, 229)) {?>
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
<?php } ?>
<?php if(menuAccessShowGranularLink($MenuOptions, 230)) {?>
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
<?php } ?>
</ul>
</li>


<!--<li class="sidenav-item <?php if($MainPage=='Bag-Purchase-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
   <i class="sidenav-icon feather icon-activity"></i>
<div>Bag Purchase Order</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-bag-purchase-order.php" class="sidenav-link">
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
<a href="view-bag-purchase-order.php" class="sidenav-link">
<div> View Purchase Order</div>
<?php if($Page=='View-Purchase-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>-->

 <?php } if(menuAccessShowGranularLink($MenuOptions, 227)) {?>  
<li class="sidenav-item">
            <a href="match-pdi-verification.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-share-2"></i>
                <div>Match PDI</div>
                <?php if($Page=='Match-PDI-Verification') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

 <?php } if(menuAccessShowGranularGroup($MenuOptions, 146)) {?>  
<li class="sidenav-item <?php if($MainPage=='DCR-Verification') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
   <i class="sidenav-icon feather icon-activity"></i>
<div>DCR Verification</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowGranularLink($MenuOptions, 231)) {?>
<li class="sidenav-item">
<a href="upload-dcr-excel.php" class="sidenav-link">
<div> Upload DCR Excel</div>
<?php if($Page=='Upload-DCR-Verification') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<?php if(menuAccessShowGranularLink($MenuOptions, 232)) {?>
<li class="sidenav-item">
<a href="view-uploaded-dcr.php" class="sidenav-link">
<div> View Uploaded DCR</div>
<?php if($Page=='View-DCR-Verification') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
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
        <?php } if(in_array("59", $Options)) {?>
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
 
 <?php } if(menuAccessShowGranularGroup($MenuOptions, 165)) { ?>
<li class="sidenav-item <?php if($MainPage=='Item-Transfer-Workflow' && ($Page=='Dispatch-To-Store-Transfer' || $Page=='View-Dispatch-To-Store-Transfers' || $Page=='Stock-Location-Report')) {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Transfer Item Dispatch to Store</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowGranularLink($MenuOptions, 237)) { ?>
<li class="sidenav-item">
<a href="<?php echo isset($SiteUrl) ? $SiteUrl : ''; ?>/item_transfer_workflow/dispatch-to-store-transfer.php" class="sidenav-link">
<div>Transfer to Store</div>
<?php if($Page=='Dispatch-To-Store-Transfer') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<?php } if(menuAccessShowGranularLink($MenuOptions, 238)) { ?>
<li class="sidenav-item">
<a href="<?php echo isset($SiteUrl) ? $SiteUrl : ''; ?>/item_transfer_workflow/view-dispatch-to-store-transfers.php" class="sidenav-link">
<div>View Dispatch to Store Transfers</div>
<?php if($Page=='View-Dispatch-To-Store-Transfers') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<?php } if(menuAccessShowGranularLink($MenuOptions, 239)) { ?>
<li class="sidenav-item">
<a href="<?php echo isset($SiteUrl) ? $SiteUrl : ''; ?>/item_transfer_workflow/stock-location-report.php" class="sidenav-link">
<div>Stock Location Report</div>
<?php if($Page=='Stock-Location-Report') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>
 <?php } if(menuAccessShowGranularGroup($MenuOptions, 166)) { ?>
<li class="sidenav-item <?php if($MainPage=='Item-Transfer-Workflow' && ($Page=='Store-To-Store-Transfer' || $Page=='View-Store-To-Store-Transfers' || $Page=='Stock-Location-Report')) {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Transfer Item Store to Store</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowGranularLink($MenuOptions, 240)) { ?>
<li class="sidenav-item">
<a href="<?php echo isset($SiteUrl) ? $SiteUrl : ''; ?>/item_transfer_workflow/store-to-store-transfer.php" class="sidenav-link">
<div>Transfer to Another Store</div>
<?php if($Page=='Store-To-Store-Transfer') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<?php } if(menuAccessShowGranularLink($MenuOptions, 241)) { ?>
<li class="sidenav-item">
<a href="<?php echo isset($SiteUrl) ? $SiteUrl : ''; ?>/item_transfer_workflow/view-store-to-store-transfers.php" class="sidenav-link">
<div>View Store to Store Transfers</div>
<?php if($Page=='View-Store-To-Store-Transfers') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<?php } if(menuAccessShowGranularLink($MenuOptions, 242)) { ?>
<li class="sidenav-item">
<a href="<?php echo isset($SiteUrl) ? $SiteUrl : ''; ?>/item_transfer_workflow/stock-location-report.php" class="sidenav-link">
<div>Stock Location Report</div>
<?php if($Page=='Stock-Location-Report') {?>
<div class="pl-1 ml-auto"><span class="badge badge-dot badge-primary"></span></div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>

<?php } if(menuAccessShowGranularGroup($MenuOptions, 26)) {?>

<li class="sidenav-item <?php if($MainPage=='Sell') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Delivery Challan</div>
</a>
<ul class="sidenav-menu">
<?php if(menuAccessShowGranularLink($MenuOptions, 243)) {?>
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
<?php if(menuAccessShowGranularLink($MenuOptions, 244)) {?>
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
<?php } if(menuAccessShowGranularLink($MenuOptions, 260)) {?>
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
<?php } if(menuAccessShowGranularLink($MenuOptions, 262)) {?>
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
<?php } ?>
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

<?php } if(in_array("28", $Options) || in_array("135", $Options) || in_array("136", $Options) || in_array("137", $Options) || in_array("164", $Options) || menuAccessShowGranularGroup($MenuOptions, 28)) {?>

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
                <?php if($Page=='Service-Beneficiary-List') {?>
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
<?php } if(menuAccessShowGranularLink($MenuOptions, 245)) {?>
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
<?php } if(menuAccessShowGranularLink($MenuOptions, 246)) {?>
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
<?php } if(menuAccessShowGranularLink($MenuOptions, 247)) {?>
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
<?php } if(menuAccessShowGranularLink($MenuOptions, 260)) {?>
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
<?php } ?>
</ul>
</li>
<?php } ?>

<?php if(in_array("168", $Options) || in_array("169", $Options) || in_array("170", $Options) || in_array("171", $Options) || in_array("172", $Options) || in_array("173", $Options) || in_array("121", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Insurance') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-shield"></i>
<div>Insurance Site</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("168", $Options) || in_array("121", $Options)) {?>
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
<?php } if(in_array("169", $Options)) {?>
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
<?php } if(in_array("170", $Options)) {?>
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
<?php } if(in_array("171", $Options)) {?>
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
<?php } if(in_array("172", $Options)) {?>
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
<?php } if(in_array("173", $Options)) {?>
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

<?php if(in_array("93", $Options)) {?>
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
<?php if(userHasAnyMenuOption($Options, getMenuOptionGroups()['Installation Workflow'])) {?>
      <li class="sidenav-item <?php if($MainPage=='Installation'){?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
    <i class="sidenav-icon feather icon-activity"></i>
    <div>Installation Workflow</div>
</a>

<ul class="sidenav-menu">
<?php if(in_array("174", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Installation-Dashboard'){?> active <?php } ?>">
        <a href="admin-installation-dashboard.php" class="sidenav-link">
            <div>Installation Dashboard</div>
        </a>
    </li>
<?php } if(in_array("175", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Assign-Coordinator'){?> active <?php } ?>">
        <a href="pending-installations.php" class="sidenav-link">
            <div>Assign Coordinator</div>
        </a>
    </li>
<?php } if(in_array("176", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Coordinator-Sites'){?> active <?php } ?>">
        <a href="coordinator-assigned-sites.php" class="sidenav-link">
            <div>Coordinator Action</div>
        </a>
    </li>
<?php } if(in_array("177", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Manager-Pending'){?> active <?php } ?>">
        <a href="manager-pending-installations.php" class="sidenav-link">
            <div>Manager Action</div>
        </a>
    </li>
<?php } if(in_array("178", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='GM-Pending'){?> active <?php } ?>">
        <a href="gm-pending-installations.php" class="sidenav-link">
            <div>General Manager Action</div>
        </a>
    </li>
<?php } if(in_array("179", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='GM-Extensions'){?> active <?php } ?>">
        <a href="gm-extension-requests.php" class="sidenav-link">
            <div>GM Extension Requests</div>
        </a>
    </li>
<?php } if(in_array("180", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='BH-Pending'){?> active <?php } ?>">
        <a href="business-head-pending.php" class="sidenav-link">
            <div>Business Head Action</div>
        </a>
    </li>
<?php } if(in_array("181", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='BH-Extensions'){?> active <?php } ?>">
        <a href="bh-extension-requests.php" class="sidenav-link">
            <div>BH Extension Requests</div>
        </a>
    </li>
<?php } if(in_array("182", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Dispute-Sites'){?> active <?php } ?>">
        <a href="dispute-sites.php" class="sidenav-link">
            <div>Dispute Sites</div>
        </a>
    </li>
<?php } ?>
</ul>
</li>
<?php } ?>
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
 <li class="sidenav-item">
<a href="expense-request.php" class="sidenav-link">
 <i class="sidenav-icon feather icon-activity"></i>
<div>Expense Request</div>
<?php if($Page=='Expense-Request') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
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


 <?php if (userHasAnyMenuOption($Options, menuAccessTaskOptionIds())) { ?>
 <li class="sidenav-item">
            <a href="task_management/task-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Task Management</div>
                
            </a>
        </li>
 <?php } ?>
        
<?php  if(in_array("37", $Options)) {?>
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