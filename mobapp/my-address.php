<?php session_start();
require_once 'config.php';
$PageName = "My Address";
$user_id = $_SESSION['User']['id'];?>
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   

  
    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
         <?php include_once 'back-header.php'; ?> 
        

        <!-- page content start -->
<?php 
if($_GET['page']){
    $page = "&page=".$_GET['page'];
    $page2 = "?page=".$_GET['page'];
}
else{
    $page = "";
    $page2 = "";
}
    if($_GET['action']=='delete'){
    $id = $_GET['aid'];
    if($_GET['page']){
        $page2 = "?page=".$_GET['page'];
    }
    else{
        $page2 = "";
   }
    $q2 = "DELETE FROM customer_address WHERE id = '$id'";
    $conn->query($q2);?>
    <script type="text/javascript">
       alert("Address Deleted Successfully!");
       window.location.href="my-address.php<?php echo $page2;?>";
    </script>
<?php } ?>
        <div class="main-container">
            <div class="container">
                 <div class="alert alert-success" style="font-size: 15px;">

                       <a href="checkout.php"> <span class="material-icons">keyboard_arrow_left</span> <span>Back</span></a>   <span style="float:right;"> <a href="add-address.php" >Add New Address!</a></span></div>
                <?php 
 $i=1;
 $sql8 = "SELECT min(id) as minid FROM customer_address WHERE UserId='$user_id'";
 $row8 = getRecord($sql8);
 if($_GET['aid']){
    $minid = $_GET['aid'];  
 }
  else{
    $minid = $row8['minid'];
 }
$sql7 = "SELECT cd.*,s.Name As State,c.Name As Country,ct.Name As City,a.Name As Area FROM customer_address cd
         LEFT JOIN tbl_country c ON c.id=cd.CountryId
         LEFT JOIN tbl_state s ON s.id = cd.StateId
         LEFT JOIN tbl_city ct ON ct.id = cd.CityId 
         LEFT JOIN tbl_area a ON a.id = cd.AreaId 
         WHERE cd.UserId='$user_id' ORDER BY cd.id ASC";
$res7 = $conn->query($sql7);
while($row7 = $res7->fetch_assoc()){

?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h6><?php echo $row7['Fname']." ".$row7['Lname']; ?></h6>
                        <address>
                            <?php echo $row7['Address']." - ".$row7['Pincode'];?><br>
                            <?php echo $row7['Area'].", ".$row7['City'].", ".$row7['State']." - ".$row7['Country'];?>
                        </address>
                        <p>Ph.: <?php echo $row7['Phone'];?></p>
                       
                        <div class="custom-control custom-switch">
                            <input type="radio" name="address" class="custom-control-input" id="address<?php echo $row7['id'];?>" <?php if($minid == $row7['id']) {?> checked <?php } ?> onclick="getAddress(<?php echo $row7['id'];?>);">
                            <label class="custom-control-label" for="address<?php echo $row7['id'];?>">Use this address</label>
                            <span style="float: right;"><a href="add-address.php?aid=<?php echo $row7['id'];?><?php echo $page;?>"><span class="material-icons vm">edit</span></a> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?aid=<?php echo $row7['id'];?>&action=delete<?php echo $page;?>"><span class="material-icons vm" style="color: red;">delete</span></a></span>
                        </div>
                    </div>
                </div>
                <?php $i++;} ?>
                
                <input type="hidden" id="page" value="<?php echo $_GET['page']; ?>">
                
            </div>
        </div>
    </main>


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
    <script type="text/javascript">
        function getAddress(id){
            var page = $('#page').val();
            if(page == 'profile'){
                 window.location.href="profile.php";
            }
            else if(page == 'hall'){
                 window.location.href="checkout-hall.php?aid="+id;
            }
            else{
            window.location.href="checkout.php?aid="+id;
            }
        }
    </script>
</body>

</html>
