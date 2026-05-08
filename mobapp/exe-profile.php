<?php session_start();
require_once 'config.php';
require_once 'auth.php';
echo "<script>window.location.href='profile.php';</script>";
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM customers WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row11['Validity']))); ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
   <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once 'sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once 'top_header.php'; ?>
    <br><br><br>
        <div class="main-container">
            <div class="container-fluid top-70 text-center mb-4">
            <div class="avatar avatar-140 rounded-circle mx-auto shadow">
                 <div class="background">
                    <?php 
                        if($row11['Photo'] == ''){
                     ?>
                    <img src="user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                <?php } else if(file_exists("../uploads/".$row11['Photo'])) {?>
                     <img src="../uploads/<?php echo $row11['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                 <?php } else{?>
                     <img src="user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                 <?php } ?>
                </div>
            </div>
        </div>

       <div class="container mb-4 text-center text-black">
            <h6 class="mb-1"><?php echo $Name; ?></h6>
           <!--  <span><i class="fa fa-map-marker"></i> <?php echo $row11['Address']; ?></span>
            <p class="mb-1"><i class="fa fa-envelope"></i> <?php echo $row11['EmailId']; ?></p>
            <p><i class="fa fa-phone"></i> <?php echo $row11['Phone']; ?></p> -->
        </div>
         
            <div class="container mb-4">
              <div class="row mb-4">
                    <div class="col-6">
                        <a href="edit-profile.php"><button class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;"><span class="material-icons">account_circle</span> Edit Profile</button></a>
                    </div>
                    <div class="col-6">
                        <a href="change-password.php"><button class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;"> <span class="material-icons">lock_open</span> Change Password</button></a>
                    </div>
                </div>
                
            </div>

            <div class="container mb-4">

                   
                <div class="card mb-3">
                     <div class="card-header border-bottom">
                        <h6 class="mb-0">Latest Registered Customers</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <ul class="list-group list-group-flush">
                             <?php 
                                $sql2 = "SELECT * FROM customers WHERE CreatedBy='$UserId' ORDER BY id DESC LIMIT 5"; 
                                $res2 = $conn->query($sql2);
                                $row_cnt = mysqli_num_rows($res2);
                                    if($row_cnt > 0){
                                    while($row = $res2->fetch_assoc()){
                                        $Roll = $row['Roll'];
                                       if($Roll == 4){
                                        $AccName = "Doctor";
                                        }
                                        if($Roll == 5){
                                        $AccName = "Optician";
                                        }
                                        if($Roll == 6){
                                        $AccName = "Wholesaler";
                                        }
                                        if($Roll == 7){
                                        $AccName = "Customer";
                                        }
                                        if($Roll == 8){
                                        $AccName = "Retailer";
                                        }

                                        if($row['Status'] == 1){
                                        $Status = '<h6 class="text-success">Active</h6>';
                                        }   
                                        else{
                                        $Status = '<h6 class="text-danger">Pending</h6>';
                                        }
                                     ?>
                            <li class="list-group-item">
                                <a href="view-cust-details.php?id=<?php echo $row['id'];?>"><div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background">
                                     <?php if($row['Photo'] == ''){?>
                    <img src="no_profile.jpg" alt="" style="width: 40px;height: 40px;">
                <?php } else if(file_exists("../uploads/".$row['Photo'])) {?>
                     <img src="../uploads/<?php echo $row['Photo']; ?>" alt="" style="width: 40px;height: 40px;">
                 <?php } else{?>
                     <img src="no_profile.jpg" alt="" style="width: 40px;height: 40px;">
                 <?php } ?>           
                                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1" style="color: #212529"><?php echo $row['Fname']." ".$row['Lname']; ?><br> (<?php echo $AccName; ?>)</h6>
                                       
                                        <p class="small text-secondary"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate'])))?></p>
                                    </div>
                                    <div class="col-auto">
                                        <?php echo $Status; ?>
                                    </div>
                                </div></a>
                            </li>
                        <?php }} else{ ?><br>
                           <div class="col-auto">
                                        <h6 class="text-danger">Oops! No Customer Found..</h6>
                                    </div>
                           <?php } ?>
                        </ul>
                        
                    </div>
                </div>
             
                
            </div>
           
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">My Account</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="list-group list-group-flush border-top border-color">
                             <a href="add-customer.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">language</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Add Customers</h6>
                                        <p class="text-secondary">Register New Customer</p>
                                    </div>
                                </div>
                            </a>
                            <a href="view-customers.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">language</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Customers</h6>
                                        <p class="text-secondary">Registered  Customers Lists</p>
                                    </div>
                                </div>
                            </a>

                            <a href="edit-profile.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Account Details</h6>
                                       <p class="text-secondary">Edit Profile Details</p>
                                    </div>
                                </div>
                            </a>

                             <a href="my-address.php?page=profile" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">location_city</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Address</h6>
                                        <p class="text-secondary">Add, update, delete address</p>
                                    </div>
                                </div>
                            </a>

                          <!-- <a href="#" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Wishlist</h6>
                                       
                                    </div>
                                </div>
                            </a>

                             

                             <a href="#" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Wallet</h6>
                                       
                                    </div>
                                </div>
                            </a>-->

                             

                             
                             <a href="change-password.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">lock_open</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Change Password</h6>
                                        <p class="text-secondary">App lock, Password</p>
                                    </div>
                                </div>
                            </a>

                            <a href="logout.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">power_settings_new</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Logout</h6>
                                        <p class="text-secondary">Logout from the application</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once 'footer.php';?>


    <!-- color settings style switcher -->
    



    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
    
</body>

</html>
