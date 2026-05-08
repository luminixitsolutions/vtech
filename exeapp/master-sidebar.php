<?php 
	$user_id = $_SESSION['Admin']['id'];
	 $sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
	$row77 = getRecord($sql77);
	$Roll = $row77['Roll'];
	$UserCat = $row77['CatId'];
	$Options = explode(',',$row77['Options']);
	$BranchId = $row77['BranchId'];
 ?>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>
 <?php if($Roll == 1 || $Roll == 7){?>
 <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="dashboard.php"><img src="logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                   <!-- <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $Proj_Title; ?></a>-->
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
        <li class="sidenav-item">
            <a href="masters-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Master Dashboard</div>
                
            </a>
        </li> 
        
        <?php if(in_array("1", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Location') {?> open active <?php } ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon feather icon-home"></i>
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
    <i class="sidenav-icon feather icon-home"></i>
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
<i class="sidenav-icon feather icon-home"></i>
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
    <i class="sidenav-icon feather icon-home"></i>
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
    <i class="sidenav-icon feather icon-home"></i>
<div> Scheme</div>
<?php if($Page=='Scheme') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("5", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="user-type.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>User Type </div> 
<?php if($Page=='UserType') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("6", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=1" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Pump Head </div> 
<?php if($Page=='PumpHead') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("7", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=2" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Pump Capacity </div> 
<?php if($Page=='PumpCapacity') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("8", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=3" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Water Source </div> 
<?php if($Page=='WaterSource') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("9", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=4" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Surface </div> 
<?php if($Page=='Surface') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("12", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=7" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Bore Dia </div> 
<?php if($Page=='Bore-Dia') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("13", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=8" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Customer Type </div> 
<?php if($Page=='Customer-Type') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("34", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=9" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Insurance Agency</div> 
<?php if($Page=='Insurance-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("15", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=5" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Insurance Claim Reason</div> 
<?php if($Page=='Insurance-Reason') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("16", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=6" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Insurance Claim Status</div> 
<?php if($Page=='Insurance-Status') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("53", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=10" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Lead Source</div> 
<?php if($Page=='By-Lead') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("54", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
<a href="common-master.php?pageid=11" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Lead Status</div> 
<?php if($Page=='Lead-Status') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
        
        
    </ul>
</div>
<?php } else { 
    include 'employee-sidebar.php';
} ?>