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
<div> Add Customer</div>
<?php if($Page=='Add-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-customers.php" class="sidenav-link">
<div> View Customers</div>
<?php if($Page=='View-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>



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
<?php } ?>
        
    </ul>
</div>
<?php } else { 
    include 'employee-sidebar.php';
} ?>