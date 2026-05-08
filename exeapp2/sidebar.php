<?php 
$UserId = $_SESSION['User']['id'];
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT tu.*,tut.Name As UserType FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tu.Roll=tut.id WHERE tu.id='$UserId'";
$row110 = getRecord($sql110);

$Name = $row110['Fname']." ".$row110['Lname'];
$Phone = $row110['Phone'];
$EmailId = $row110['EmailId'];
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = $row110['Member'];
$PkgDate = $row110['PkgDate'];
$Validity = $row110['Validity'];
$Latitude = $row110['Lattitude'];
$Longitude = $row110['Longitude'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row110['Validity'])));
$Options = explode(',',$row110['Options']);
$BranchId = $row110['BranchId'];
$MulBranchId = $row110['MulBranchId'];
$CurrDate = date('Y-m-d');
$diff = strtotime($Validity) - strtotime($CurrDate);
$Days = ($diff / 86400);
$RemainDays2 = $Days + 1;
if($RemainDays2 == 1){
    $RemainDays = "Today";
}
else{
$RemainDays = $RemainDays2." days";
}

$sql11x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
                        $res11x = $conn->query($sql11x);
                        $row11x = $res11x->fetch_assoc();
$mybalance = $row11x['credit'] - $row11x['debit'];

 ?>
    <!-- menu main -->
    <div class="main-menu">
        <div class="row mb-4 no-gutters">
            <div class="col-auto"><button class="btn btn-link btn-40 btn-close text-white"><span class="material-icons">chevron_left</span></button></div>
            <?php if(isset($_SESSION['User'])) {?>
            <div class="col-auto">
                <div class="avatar avatar-40 rounded-circle position-relative">
                    <figure class="background">
                       <?php 
                        if($row110['Photo'] == ''){
                     ?>
                    <img src="<?php echo $SiteUrl;?>/user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                <?php } else  {?>
                     <img src="<?php echo $Uploadurl;?>/uploads/<?php echo $row110['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                
                 <?php } ?>
                    </figure>
                </div>
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Name; ?></h6>
                
               <p class="small text-default-secondary" style="color: white;"> <?php echo $row110['UserType'];?> </p>
 
            </div>
        <?php } else{?>
             <div class="col-auto">
                
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Proj_Title; ?></h6>
                <p class="small text-default-secondary"></p>
            </div>
        <?php } ?>
        </div>
        <div class="menu-container">
              <?php
                if($WallMsg == 'NotShow'){} else{
                    if(isset($_SESSION['User'])){
                    ?>
          <!--  <div class="row">
                <div class="col">
                    <h4 class="mb-1 font-weight-normal">&#8377;<?php echo number_format($mybalance,2);?></h4>
                    <p class="text-default-secondary" style="color:#fff;">Wallet Balance</p>
                </div>
                <div class="col-auto">
                    <a href="add-money.php" class="btn btn-default btn-40 rounded-circle"><i class="material-icons">add</i></a>
                </div>
            </div> -->
        <?php } } ?>
            <ul class="nav nav-pills flex-column ">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo $SiteUrl;?>/home.php">
                        <div>
                            <span class="material-icons icon">home</span>
                            Home
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/attendance.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Attendance
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                
                 <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-attendance.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            View Attendance
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/vehical-entry.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Add Vehical Entry
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-vehical-entry.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Vehical Entries
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-expenses.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Expenses
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>

                <?php  if(in_array("56", $Options)) {?>
                 <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-quotation-products.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Quotation Products
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("2", $Options)) {?>
                 <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/branches.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Store
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("3", $Options)) {?>  
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/issues.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Issues
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("4", $Options)) {?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/scheme.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Scheme
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("5", $Options)) {?>    
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/user-type.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            User Type
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("6", $Options)) {?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=1">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Pump Head
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("7", $Options)) {?> 
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=2">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Pump Capacity
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("8", $Options)) {?> 
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=3">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Water Source
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("9", $Options)) {?> 
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=4">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Pump Surface
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("12", $Options)) {?> 
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=7">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Bore Dia
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("13", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=8">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Customer Type
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("34", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=9">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Insurance Agency
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("15", $Options)) {?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=5">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Insurance Claim Reason
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("16", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=6">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Insurance Claim Status
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("53", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=10">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Lead Source
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("54", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/common-master.php?pageid=11">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Lead Status
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("44", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/add-lead.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Lead Creation
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("45", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-leads.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            View Leads
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("46", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/assign-leads.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Lead Assign
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("47", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-leads-calling.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            To do Activity
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("63", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/lead-completed-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Prospects Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("50", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/lead-quotation.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Quotation
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("49", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/opportunity.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Opportunity
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("51", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/opportunity-convert-to-order.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Opportunity Convert To Order
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("52", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/social-media-marketing.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Social Media Marketing
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("24", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-products.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Product
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("17", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/product-specification.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Product Specification
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("18", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("19", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-manufacture.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Manufacture
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("20", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-company.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Company
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("21", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-employee.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Employee
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("22", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-dealer.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Dealer
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("23", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-agency.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Agency
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("55", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/assign-customers-to-co-ordinator.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Assign Customers To Co-ordinator
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("27", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-quotation.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Performa Invoice
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("57", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-bill-amount-status.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                           Bill Amount Status
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("58", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/assign-to-store-incharge.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Assign To Store Incharge
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("59", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/approve-store-incharge.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Approve By Store Incharge
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("25", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-purchase-order.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Purchase Order
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("60", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/assign-to-dispatch-officer.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Assign Order To Dispatch Officer
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("26", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-sells.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Delivery Challan
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("42", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/delivery-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Delivery Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/pending-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Pending Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/completed-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Completed Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("67", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/rooftop-installation.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Rooftop Installation
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("68", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/pump-installation.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Pump Installation
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("28", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-service-module.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Service Complaint
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("69", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/dealer-commission.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Dealer Commission
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } if(in_array("61", $Options)) {?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/view-warranty-registration.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Warranty Registration
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/warranty-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Warranty Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/no-warranty-customers.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            No Warranty Customers
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } ?>

           
            </ul>
            <?php if(isset($_SESSION['User'])){?>
            <div class="text-center">
                <a href="JavaScript:Void(0);" onclick="logout()" class="btn btn-outline-danger text-white rounded my-3 mx-auto">Sign out</a>
            </div>
             <?php } ?>
        </div>

    </div>
    <div class="backdrop"></div>
    
        
    <script>
        function shareApplication(msg){
            //alert(msg);
             Android.shareApplication(''+msg+'','Daily Door Services');
             //Android.shareApplication('test','Daily Door Services');
        }
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>
