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
                <div>Dashboard</div>
                
            </a>
        </li>
        
       

<?php if(in_array("1", $Options) || in_array("2", $Options) || in_array("3", $Options) || in_array("4", $Options) || in_array("5", $Options) || in_array("6", $Options) || in_array("7", $Options) || in_array("8", $Options) || in_array("9", $Options) || in_array("12", $Options) || in_array("13", $Options) || in_array("15", $Options) || in_array("16", $Options) || in_array("53", $Options) || in_array("54", $Options)) { ?>
         <li class="sidenav-item <?php if($MainPage=='Masters') {?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-aperture"></i>
                <div>Masters</div>
            </a>
            <ul class="sidenav-menu">


    <?php if(in_array("1", $Options)) {?>
    <li class="sidenav-item <?php if($Page=='Location') {?> open active <?php } ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle">
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
<!--    <li class="sidenav-item">
    <a href="area.php" class="sidenav-link">
    <div>Area</div>
    <?php if($Page2=='Area') {?>
    <div class="pl-1 ml-auto">
    <span class="badge badge-dot badge-primary"></span>
    </div>
    <?php } ?>  
    </a>
    </li>-->
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
        </li>
    <?php } ?>

 <?php if(in_array("44", $Options)) {?>
        <li class="sidenav-item">
            <a href="add-lead.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Lead Creation</div>
                
            </a>
        </li>
        <?php } if(in_array("45", $Options)) {?>
        <li class="sidenav-item">
            <a href="view-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>View Leads</div>
                
            </a>
        </li>
        <?php } if(in_array("46", $Options)) {?>
        <li class="sidenav-item">
            <a href="assign-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Lead Assign</div>
                
            </a>
        </li>
        <?php } if(in_array("47", $Options)) {?>
         <li class="sidenav-item">
            <a href="view-leads-calling.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>To do Activity</div>
               
            </a>
        </li>
        <?php } if(in_array("48", $Options)) {?>
        <!-- <li class="sidenav-item">
            <a href="appointment-scheduling.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Appointment Scheduling</div>
                
            </a>
        </li> -->
        <?php } if(in_array("63", $Options)) {?>
       
        <li class="sidenav-item">
            <a href="lead-completed-customers.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Prospects Customers</div>
               
            </a>
        </li>
        <?php } if(in_array("50", $Options)) {?>
        <li class="sidenav-item">
            <a href="lead-quotation.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Quotation</div>
               
            </a>
        </li>
<?php } if(in_array("49", $Options)) {?>
        <li class="sidenav-item">
            <a href="opportunity.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Opportunity</div>
               
            </a>
        </li>
<?php } if(in_array("51", $Options)) {?>
        <li class="sidenav-item">
            <a href="opportunity-convert-to-order.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Opportunity Convert to Order</div>
               
            </a>
        </li>
<?php } if(in_array("64", $Options)) {?>
    <li class="sidenav-item">
            <a href="upload-excel.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Upload Excel</div>
               
            </a>
        </li>
       
       <?php } if(in_array("52", $Options)) {?>
 <li class="sidenav-item">
            <a href="social-media-marketing.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Social Media Marketing</div>
               
            </a>
        </li>
        
         <?php } ?>


         <?php if(in_array("24", $Options)) {?>
        <li class="sidenav-item">
            <a href="add-product.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Add Product</div>
                
            </a>
        </li>
       
        <li class="sidenav-item">
            <a href="view-products.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>View Product</div>
                
            </a>
        </li>
        <?php } if(in_array("17", $Options)) {?>
            <li class="sidenav-item">
                    <a href="product-specification.php" class="sidenav-link">
                        <i class="sidenav-icon feather icon-home"></i>
                        <div>Product Specification</div>
                        <?php if($Page=='Product-Specification') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
        <?php } ?>
        

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
<?php } if(in_array("55", $Options)) {?>
        <li class="sidenav-item">
<a href="assign-customers-to-co-ordinator.php" class="sidenav-link">
     <i class="sidenav-icon feather icon-home"></i>
<div> Assign Customers To Co-ordinator</div>
<?php if($Page=='Assign-Customers-Co-ordinator') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>      

<?php if(in_array("27", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Quotation') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Performa Invoice (PI)</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("56", $Options)) {?>
<li class="sidenav-item">
<a href="view-quotation-products.php" class="sidenav-link">
<div> PI/Quotation Products</div>
<?php if($Page=='Quotation-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-quotation.php" class="sidenav-link">
<div> Create PI</div>
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
<?php } ?>

<?php if(in_array("57", $Options)) {?>
 <li class="sidenav-item">
            <a href="view-bill-amount-status.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Bill Amount Status</div>
                <?php if($Page=='Bill-Amount-Status') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>


<?php } if(in_array("58", $Options)) {?>
         <li class="sidenav-item">
            <a href="assign-to-store-incharge.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Assign To Store Incharge</div>
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
                <i class="sidenav-icon feather icon-home"></i>
                <div>Approve By Store Incharge</div>
                <?php if($Page=='Approve-Store-Incharge') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

       


<?php } if(in_array("25", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Purchase-Order') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
    <i class="sidenav-icon feather icon-save"></i>
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
</ul>
</li>

<?php } if(in_array("60", $Options)) {?>

 <li class="sidenav-item">
            <a href="assign-to-dispatch-officer.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Assign Order To Dispatch Officer</div>
                <?php if($Page=='Assign-Dispatch-Officer') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

<?php } if(in_array("26", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='Sell') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-printer"></i>
<div>Delivery Challan</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="add-sell.php" class="sidenav-link">
<div> Add Delivery Challan</div>
<?php if($Page=='Add-Sell') {?>
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
<?php } ?>

<?php if(in_array("42", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Delivery-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Delivery Products</div>
</a>
<ul class="sidenav-menu">
  
<li class="sidenav-item">
<a href="delivery-customers.php" class="sidenav-link">
<div> Delivery Customers</div>
<?php if($Page=='Delivery-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="pending-customers.php" class="sidenav-link">
<div> Pending Customers</div>
<?php if($Page=='Pending-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="completed-customers.php" class="sidenav-link">
<div> Completed Customers</div>
<?php if($Page=='Completed-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php } if(in_array("67", $Options) || in_array("68", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Installation') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-user"></i>
<div>Installation</div>
</a>
<ul class="sidenav-menu">
  <?php if(in_array("67", $Options)) {?>
<li class="sidenav-item">
<a href="rooftop-installation.php" class="sidenav-link">
<div> Rooftop Installation</div>
<?php if($Page=='Rooftop-Installation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("68", $Options)) {?>
<li class="sidenav-item">
<a href="pump-installation.php" class="sidenav-link">
<div> Pump Installation</div>
<?php if($Page=='Pump-Installation') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li>

<?php } if(in_array("28", $Options)) {?>

<li class="sidenav-item <?php if($MainPage=='service') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-headphones"></i>
<div>Service Complaint</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("14", $Options)) {?>
<li class="sidenav-item">
<a href="service-module.php" class="sidenav-link">
<div> Add Service Complaint</div>
<?php if($Page=='Add-Sell') {?>
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
<?php if($Page=='View-Sell') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php }  if(in_array("69", $Options)) {?>

<li class="sidenav-item">
            <a href="dealer-commission.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Dealer Commission</div>
                <?php if($Page=='Dealer-Commission') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
            </a>
        </li>


<?php } if(in_array("37", $Options)) {?>
        <li class="sidenav-item">
            <a href="ecommerce-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>E-Commerce Management</div>
                
            </a>
        </li>
<?php }  if(in_array("61", $Options)) {?>

<li class="sidenav-item">
            <a href="warranty-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Warranty Management</div>
                
            </a>
        </li>
     <?php } ?>   

<?php if(in_array("29", $Options) || in_array("30", $Options) || in_array("31", $Options) || in_array("38", $Options) || in_array("40", $Options) || in_array("65", $Options)) {?>
     <li class="sidenav-item <?php if($MainPage=='Report') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-bar-chart"></i>
<div>Report</div>
</a>
<ul class="sidenav-menu"> 
 <?php  if(in_array("29", $Options)) {?>
 <li class="sidenav-item">
<a href="sell-report.php" class="sidenav-link">
<div> Delivery Challan Report</div>
<?php if($Page=='Sell-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 


<?php } if(in_array("30", $Options)) {?>
 <li class="sidenav-item">
<a href="stock-report2.php" class="sidenav-link">
<div> Stock Report</div>
<?php if($Page=='Stock-Report2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("31", $Options)) {?>
 <li class="sidenav-item">
<a href="stock-report.php" class="sidenav-link">
<div> Outstanding Stock Report</div>
<?php if($Page=='Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("38", $Options)) {?>

<li class="sidenav-item">
<a href="all-customer-report.php" class="sidenav-link">
<div> Customer Report</div>
<?php if($Page=='Customer-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("39", $Options)) {?>
 <li class="sidenav-item">
<a href="daily-record-report.php" class="sidenav-link">
<div> Daily Record Report</div>
<?php if($Page=='Daily-Record-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("40", $Options)) {?>
 <li class="sidenav-item">
<a href="attendance-report.php" class="sidenav-link">
<div> Attendance Report</div>
<?php if($Page=='Attendance-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 

<?php } if(in_array("65", $Options)) {?>
<li class="sidenav-item">
<a href="dealer-report.php" class="sidenav-link">
<div> Dealer Report</div>
<?php if($Page=='Dealer-Report') {?>
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



    </ul>
</div>