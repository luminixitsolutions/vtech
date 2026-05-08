<div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
    <div class="app-brand demo">
        <span class="app-brand-logo demo">
            <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2">
                <span style="font-size:24px;">SAI SOYA</span><br>
                Food Products
            </a>
        </span>
        <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2">
        </a>
        <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
        </a>
    </div>
    <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner py-1">
        <li class="sidenav-item open active">
            <a href="dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-check-circle"></i>
                <div>Dashboard</div>
                <?php if($Page=='Dashboard') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

<?php if(in_array("1", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Customers') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Customers</div>
</a>
<ul class="sidenav-menu">
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
<?php } if(in_array("2", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Users') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>User Account</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="add-user.php" class="sidenav-link">
<div> Add User</div>
<?php if($Page=='Add-Users') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-users.php" class="sidenav-link">
<div> View Users</div>
<?php if($Page=='View-Users') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("3", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Vendors') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Vendors</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="add-vendor.php" class="sidenav-link">
<div> Add Vendor</div>
<?php if($Page=='Add-Vendors') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-vendors.php" class="sidenav-link">
<div> View Vendors</div>
<?php if($Page=='View-Vendors') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("4", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Products') {?> open active <?php } ?>">
<a href="products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-users"></i>
<div>Products </div> 
<?php if($Page=='Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("5", $Options)) {?>


<li class="sidenav-item <?php if($MainPage=='Vendor-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Vendor Orders</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="sell-vendor-product.php" class="sidenav-link">
<div>New Vendor Order</div>
<?php if($Page=='Add-Vendor-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-sell-vendor-products.php" class="sidenav-link">
<div> View Vendor Orders</div>
<?php if($Page=='View-Vendor-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-vendor-payments.php" class="sidenav-link">
<div> Vendor Payment</div>
<?php if($Page=='View-Vendor-Payment') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("6", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Customer-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Customer Orders</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="sell-customer-product.php" class="sidenav-link">
<div>New Customer Order</div>
<?php if($Page=='Add-Customer-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-sell-customer-products.php" class="sidenav-link">
<div> View Customer Orders</div>
<?php if($Page=='View-Customer-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("7", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Raw-Materials') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Raw Materials</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="add-raw-materials.php" class="sidenav-link">
<div>New Raw Materials</div>
<?php if($Page=='Add-Raw-Materials') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-raw-materials.php" class="sidenav-link">
<div> View Raw Materials</div>
<?php if($Page=='View-Raw-Materials') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("8", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Raw-Stock') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Raw Stock</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="add-raw-stock.php" class="sidenav-link">
<div> Add Stock</div>
<?php if($Page=='Add-Raw-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-raw-stock.php" class="sidenav-link">
<div> View Stock</div>
<?php if($Page=='View-Raw-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } if(in_array("9", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Reports') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-users"></i>
<div>Reports</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="customer-sell-reports.php" class="sidenav-link">
<div>Customer Sell Reports</div>
<?php if($Page=='Customer-Sell-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="vendor-sell-reports.php" class="sidenav-link">
<div> Vendor Sell Reports</div>
<?php if($Page=='Vendor-Sell-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="sell-reports.php" class="sidenav-link">
<div> Sell Reports</div>
<?php if($Page=='Sell-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="product-sell-reports.php" class="sidenav-link">
<div> Product Sell Reports</div>
<?php if($Page=='Product-Sell-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="raw-stock-reports.php" class="sidenav-link">
<div>Raw Stock Reports</div>
<?php if($Page=='Raw-Stock-Reports') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>
<?php } ?>



        <li class="sidenav-item <?php if($MainPage=='Account') {?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-lock"></i>
                <div>Account Settings</div>
            </a>
            <ul class="sidenav-menu">

                <li class="sidenav-item ">
                    <a href="change-password.php" class="sidenav-link">
                        <div><i class="feather icon-unlock text-muted"></i> Change Password</div>
                        <?php if($Page=='Change-Password') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="logout.php" class="sidenav-link">
                        <div><i class="feather icon-power text-danger"></i> Log Out</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</div>