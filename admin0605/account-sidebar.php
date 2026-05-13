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
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="account-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>User Account Dashboard</div>
                
            </a>
        </li>
         
           <?php if(in_array("18", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Customers</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>   
<li class="sidenav-item">
<a href="add-customer.php" class="sidenav-link">
<div> Add Pump Customer</div>
<?php if($Page=='Add-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="pump-customers.php" class="sidenav-link">
<div> Pump Customers</div>
<?php if($Page=='Pump-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

   <?php if(in_array("14", $Options)) {?>   
<!-- <li class="sidenav-item">
<a href="add-rooftop-customer.php" class="sidenav-link">
<div> Add Rooftop Customer</div>
<?php if($Page=='Add-Rooftop-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<!-- <li class="sidenav-item">
<a href="rooftop-customers.php" class="sidenav-link">
<div> Rooftop Customers</div>
<?php if($Page=='Rooftop-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->

<!-- <li class="sidenav-item">
<a href="view-customers.php" class="sidenav-link">
<div> All Customers</div>
<?php if($Page=='View-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php if(in_array("122", $Options)) {?>
<li class="sidenav-item">
<a href="upload-customer-excel.php" class="sidenav-link">
<div> Upload Customers Excel</div>
<?php if($Page=='Upload-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

</ul>
</li>




<?php } if(in_array("19", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Manufacture') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Manufacture</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>   
<li class="sidenav-item">
<a href="add-manufacture.php" class="sidenav-link">
<div> Add Manufacture</div>
<?php if($Page=='Add-Manufacture') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-manufacture.php" class="sidenav-link">
<div> View Manufactures</div>
<?php if($Page=='View-Manufacture') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>

<?php } if(in_array("20", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Company') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Company</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>   
<li class="sidenav-item">
<a href="add-company.php" class="sidenav-link">
<div> Add Company</div>
<?php if($Page=='Add-Company') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-company.php" class="sidenav-link">
<div> View Company</div>
<?php if($Page=='View-Company') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("21", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Employee') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Employee</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-employee.php" class="sidenav-link">
<div> Add Employee</div>
<?php if($Page=='Add-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-employee.php" class="sidenav-link">
<div> View Employee</div>
<?php if($Page=='View-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("125", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Store-Incharge') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Store Incharge Account</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-store-incharge.php" class="sidenav-link">
<div> Create Store Incharge</div>
<?php if($Page=='Add-Store-Incharge') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-store-incharge.php" class="sidenav-link">
<div> View Store Incharge</div>
<?php if($Page=='View-Store-Incharge') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("126", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Dispatch-Officer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Dispatch Officer Account</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-dispatch-officer.php" class="sidenav-link">
<div> Add Dispatch Officer</div>
<?php if($Page=='Add-Dispatch-Officer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-dispatch-officer.php" class="sidenav-link">
<div> View Dispatch Officer</div>
<?php if($Page=='View-Dispatch-Officer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("127", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Installer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Contractor</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-installer.php" class="sidenav-link">
<div> Add Contractor</div>
<?php if($Page=='Add-Installer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-installer.php" class="sidenav-link">
<div> View Contractor</div>
<?php if($Page=='View-Installer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-contractor-commision.php" class="sidenav-link">
<div> Set Contractor Commission</div>
<?php if($Page=='View-Contractor-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="../report_management/contractor-commision-report.php" class="sidenav-link">
<div> Contractor Commision Report</div>
<?php if($Page=='Contractor-Commision-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("128", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Installer-Employee') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Installer</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-installer-employee.php" class="sidenav-link">
<div> Add Installer</div>
<?php if($Page=='Add-Installer-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-installer-employee.php" class="sidenav-link">
<div> View Installer</div>
<?php if($Page=='View-Installer-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("116", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Drivers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Driver Account</div>
</a>
<ul class="sidenav-menu">
   <?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-driver.php" class="sidenav-link">
<div> Add Driver Account</div>
<?php if($Page=='Add-Drivers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-drivers.php" class="sidenav-link">
<div> View Driver Account</div>
<?php if($Page=='View-Drivers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("22", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Dealer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Dealer</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-dealer.php" class="sidenav-link">
<div> Add Dealer</div>
<?php if($Page=='Add-Dealer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-dealer.php" class="sidenav-link">
<div> View Dealer</div>
<?php if($Page=='View-Dealer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("23", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Agency') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Agency</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-agency.php" class="sidenav-link">
<div> Add Agency</div>
<?php if($Page=='Add-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-agency.php" class="sidenav-link">
<div> View Agency</div>
<?php if($Page=='View-Agency') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } if(in_array("129", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Maintaince') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Maintaince Engineer</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>  
<li class="sidenav-item">
<a href="add-maintaince-engineer.php" class="sidenav-link">
<div> Add Maintaince Engineer</div>
<?php if($Page=='Add-Maintaince') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-maintaince-engineer.php" class="sidenav-link">
<div> View Maintaince Engineer</div>
<?php if($Page=='View-Maintaince') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
       <?php } ?> 
    </ul>
</div>
