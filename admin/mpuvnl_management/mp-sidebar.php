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
            <a href="../dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="mpuvnl-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Dashboard</div>
                
            </a>
        </li>
       
     


<li class="sidenav-item <?php if($MainPage=='MPUVNL-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Customers Selection</div>
</a>
<ul class="sidenav-menu">
  
<li class="sidenav-item">
<a href="pending-selection.php" class="sidenav-link">
<div> Pending Selection</div>
<?php if($Page=='Add-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="approve-selection.php" class="sidenav-link">
<div> Approve Selections</div>
<?php if($Page=='Pump-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="reject-selection.php" class="sidenav-link">
<div> Reject Selections</div>
<?php if($Page=='Pump-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
       
         <li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-user-check"></i>
<div>Customer Payments</div>
</a>
<ul class="sidenav-menu">
   
<li class="sidenav-item">
<a href="pending-mp-payments.php" class="sidenav-link">
<div> Pending Payments</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="partial-mp-payments.php" class="sidenav-link">
<div> Partial Payments Done</div>
<?php if($Page=='Assign-Customers-Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

        <li class="sidenav-item">
<a href="full-mp-payments.php" class="sidenav-link">
<div> Full Payments Done</div>
<?php if($Page=='Assign-Customers-Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

</ul>
</li>


<li class="sidenav-item <?php if($MainPage=='Assign-Pump-Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-user-check"></i>
<div>LOA</div>
</a>
<ul class="sidenav-menu">
   
<li class="sidenav-item">
<a href="pending-loa.php" class="sidenav-link">
<div> Pending LOA</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="received-loa.php" class="sidenav-link">
<div> Received LOA</div>
<?php if($Page=='Assign-Customers-Field-Survey') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

</ul>
</li>

<li class="sidenav-item <?php if($MainPage=='NTP-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-file-text"></i>
<div>NTP Order</div>
</a>
<ul class="sidenav-menu">
   
<li class="sidenav-item">
<a href="pending-ntp-order.php" class="sidenav-link">
<div> Pending NTP Order</div>
<?php if($Page=='Pending-NTP-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="complete-ntp-order.php" class="sidenav-link">
<div> Complete NTP Order</div>
<?php if($Page=='Complete-NTP-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

</ul>
</li>

<li class="sidenav-item <?php if($MainPage=='AIF') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-clipboard"></i>
<div>AIF</div>
</a>
<ul class="sidenav-menu">
   
<li class="sidenav-item">
<a href="pending-aif.php" class="sidenav-link">
<div> Pending AIF</div>
<?php if($Page=='Pending-AIF') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="complete-aif.php" class="sidenav-link">
<div> Complete AIF</div>
<?php if($Page=='Complete-AIF') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

</ul>
</li>

<li class="sidenav-item <?php if($MainPage=='CIF') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
 <i class="sidenav-icon feather icon-check-circle"></i>
<div>CIF</div>
</a>
<ul class="sidenav-menu">
   
<li class="sidenav-item">
<a href="pending-cif.php" class="sidenav-link">
<div> Pending CIF</div>
<?php if($Page=='Pending-CIF') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="complete-cif.php" class="sidenav-link">
<div> Complete CIF</div>
<?php if($Page=='Complete-CIF') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  

</ul>
</li>

    </ul>
</div>