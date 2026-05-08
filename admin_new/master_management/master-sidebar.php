<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
?>

            <!-- [ Layout navbar ( Header ) ] End -->
            <!-- [ Layout sidenav ] Start -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="../dashboard.php"><img src="../logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                   <!-- <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $Proj_Title; ?></a>-->
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
            <a href="<?php echo $SiteUrl;?>/dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="masters-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Master Dashboard</div>
                
            </a>
        </li> 
        
        <?php if(in_array("1", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Location') {?> open active <?php } ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon feather icon-activity"></i>
    <div>Locations</div>
    </a>
    <ul class="sidenav-menu">
    <li class="sidenav-item">
    <a href="country.php" class="sidenav-link">
    <div>Country</div>
    <?php if($Page2=='Country') {?>
    <div class="pl-1 ml-auto">
    <span class="badge badge-dot badge-primary"></span>
    </div>
    <?php } ?>  
    </a>
    </li>
    <li class="sidenav-item">
    <a href="state.php" class="sidenav-link">
    <div>State</div>
    <?php if($Page2=='State') {?>
    <div class="pl-1 ml-auto">
    <span class="badge badge-dot badge-primary"></span>
    </div>
    <?php } ?>  
    </a>
    </li>
    <li class="sidenav-item">
    <a href="city.php" class="sidenav-link">
    <div>City</div>
    <?php if($Page2=='City') {?>
    <div class="pl-1 ml-auto">
    <span class="badge badge-dot badge-primary"></span>
    </div>
    <?php } ?>  
    </a>
    </li>

    </ul>
    </li> 
 <?php } if(in_array("56", $Options)) {?>
   <li class="sidenav-item">
<a href="view-quotation-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div> PI/Quotation Products</div>
<?php if($Page=='Quotation-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    <?php } if(in_array("2", $Options)) {?>
    
<li class="sidenav-item">
<a href="branches.php" class="sidenav-link">
 <i class="sidenav-icon feather icon-activity"></i>
<div> Store</div>
<?php if($Page=='Branch') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("3", $Options)) {?>
<li class="sidenav-item">
<a href="issues.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div> Issues</div>
<?php if($Page=='Issues') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("4", $Options)) {?>
<li class="sidenav-item">
<a href="scheme.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div> Scheme/Yojna</div>
<?php if($Page=='Scheme') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("5", $Options)) {?>
<li class="sidenav-item ">
<a href="user-type.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>User Type </div> 
<?php if($Page=='UserType') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("140", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=24" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Project Head </div> 
<?php if($Page=='ProjectHead') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("141", $Options)) {?>
<li class="sidenav-item ">
<a href="project-sub-head.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Project Sub Head </div> 
<?php if($Page=='Project-Sub-Head') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("6", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=1" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Pump Head </div> 
<?php if($Page=='PumpHead') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("7", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=2" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Pump Capacity </div> 
<?php if($Page=='PumpCapacity') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("72", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=12" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Pump Outlet Size </div> 
<?php if($Page=='PumpOutletSize') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("73", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=13" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Standard Depth </div> 
<?php if($Page=='StandardDepth') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("74", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=14" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Pump Head Range </div> 
<?php if($Page=='Pump-Head-Range') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("75", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=15" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Module Watt </div> 
<?php if($Page=='Module-Watt') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("76", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=16" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Module Qty </div> 
<?php if($Page=='Module-Qty') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("77", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=17" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Structure</div> 
<?php if($Page=='Structure') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("97", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=22" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Module Make </div> 
<?php if($Page=='Module-Make') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("98", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=23" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Structure Make</div> 
<?php if($Page=='Structure-Make') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("8", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=3" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Water Source </div> 
<?php if($Page=='WaterSource') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("9", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=4" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Type Of Pump </div> 
<?php if($Page=='Surface') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("12", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=7" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Bore Dia </div> 
<?php if($Page=='Bore-Dia') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("13", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=8" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Customer Type </div> 
<?php if($Page=='Customer-Type') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("34", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=9" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Insurance Agency</div> 
<?php if($Page=='Insurance-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("15", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=5" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Insurance Claim Reason</div> 
<?php if($Page=='Insurance-Reason') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("16", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=6" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Insurance Claim Status</div> 
<?php if($Page=='Insurance-Status') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("53", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=10" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Lead Source</div> 
<?php if($Page=='By-Lead') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("54", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=11" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Lead Status</div> 
<?php if($Page=='Lead-Status') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("89", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=18" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Dispatched Calling Confirmation Ques</div> 
<?php if($Page=='Dispatched-Question') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("90", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=19" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Before Installation Ques</div> 
<?php if($Page=='Before-Installation-Question') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("91", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=20" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>After Installation Ques</div> 
<?php if($Page=='After-Installation-Question') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("92", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=21" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Before Inspection Ques</div> 
<?php if($Page=='Before-Inspection-Question') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("117", $Options)) {?>
<li class="sidenav-item ">
<a href="common-master.php?pageid=25" class="sidenav-link">
    <i class="sidenav-icon feather icon-activity"></i>
<div>Beneficiary Selection Ques</div> 
<?php if($Page=='Beneficiary-Selection-Question') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>        
        
    </ul>
</div>