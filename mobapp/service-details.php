<?php session_start();
require_once 'config.php';
include_once 'class.phpmailer.php';
include_once 'class.smtp.php';
$user_id = $_SESSION['User']['id'];
$VedId = $_GET['id'];
$CatId = $_GET['cat_id'];
$SubCatId = $_GET['subid'];
$sql11 = "SELECT * FROM customers WHERE id='$VedId' ORDER BY id DESC";
$row11 = getRecord($sql11);
$VedCatId = $row11['PrdCatId'];
$VedSubCatId = $row11['PrdSubCatId'];
$PageName = $row11['ShopName'];
$Page = "Shop";
    //print_r($_SESSION["cart_item"]);
 ?>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
         <link href="css/toastr.min.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
  
   
<?php
if(isset($_POST['submit'])){
    $Title = $row7["Title"];
    $ServiceId = $_POST['ServiceId'];
    $StateId = $_POST['StateId'];
    $CityId = $_POST['CityId'];
    $Name = addslashes(trim($_POST['Name']));
    $EmailId = addslashes(trim($_POST['EmailId']));
    $Phone = addslashes(trim($_POST['Phone']));
    $Comments = addslashes(trim($_POST['Comments']));
    $CreatedDate = date('Y-m-d');
    $sql = "INSERT INTO tbl_service_enquiry SET ServiceId='$ServiceId',StateId='$StateId',CityId='$CityId',Name='$Name',EmailId='$EmailId',Phone='$Phone',Comments='$Comments',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    $EnqId = mysqli_insert_id($conn);
    $sql11 = "SELECT Phone,EmailId,id FROM customers WHERE id='$ServiceId'";
    $row11 = getRecord($sql11);
    $to = $row11['EmailId'];
    include 'incenquirymail.php';
    include 'sendmailsmtp.php';
    
    ?>
    <script type="text/javascript">
        alert("Enquiry Sent Successfully!");
        window.location.href="service-details.php?id=<?php echo $ServiceId;?>";
    </script>
  <?php } ?>
    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->
        <div class="container-fluid px-0">
            <div class="card overflow-hidden">
                <div class="card-body p-0 h-150">
                    <div class="background">
                        <?php if($row11['ServicePhoto'] == ''){?>
                        <img src="img/image10.jpg" alt="" style="width: 392px;height: 150px;">
                        <?php } else if(file_exists("../uploads/".$row11['ServicePhoto'])) {?>
                        <img src="../uploads/<?php echo $row11['ServicePhoto']; ?>" alt="" style="width: 392px;height: 150px;">
                        <?php } else {?>
                        <img src="img/image10.jpg" alt="" style="width: 392px;height: 150px;">
                        <?php } ?>    
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid top-70 text-center">
            <div class="avatar avatar-140 rounded-circle mx-auto shadow">
                <div class="background">
                     <?php if($row11['Photo'] == '') {?>
                        <img src="no_image.jpg" alt="" style="width: 140px;height: 140px;">
                     <?php } else{?>
                        <img src="../uploads/<?php echo $row11['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                     <?php } ?>
                </div>
            </div>
        </div>

        <div class="container mb-4 text-center text-white">
              <p class="small mb-0">
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                               <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star-half-empty" style="color: #ffa700;"></i>
                                <span class="text-mute">4.85</span>
                            </p>
            <h6 class="mb-1"><?php echo $row11['ShopName']; ?></h6>
            <span><i class="fa fa-map-marker"></i> <?php echo $row11['Address']; ?></span>
            <p class="mb-1"><i class="fa fa-envelope"></i> <?php echo $row11['EmailId']; ?></p>
            <p><i class="fa fa-phone"></i> <?php echo $row11['Phone']; ?></p>
        </div>

        <div class="container mb-4" style="padding-right: 1px;padding-left: 1px;">
                <!-- Swiper -->
                <div class="swiper-container offerslidetab1">
                    <div class="swiper-wrapper">
                      <?php 
                      $VedId = $_GET['id'];
                      $sql22 = "SELECT * FROM tbl_users_images WHERE UserId='$VedId' AND Roll=2 ORDER BY id ASC";
                      $row22 = getList($sql22);
                      foreach($row22 as $result){
                       ?>
                        <div class="swiper-slide">
                            <div class="card overflow-hidden">
                                <div class="background opacity-30">
                                    <img src="../uploads/<?php echo $result['Files']; ?>" alt="">
                                </div>
                                <div class="card-body text-white" style="height: 175px;">
                                   
                                </div>
                            </div>
                        </div>
                      <?php } ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination white-pagination text-left pl-2 mb-3"></div>
                </div>
            </div>
        <div class="main-container" style="background-color: #fff;">
             
            <h6 class="page-title" style="text-align: center;">Service Details</h6>
                 <div class="card-body">
                <p class="text-mute" style="text-align: justify;"><?php echo $row11['ServiceDetails'];?></p>
              
            </div></div><br>

            <div class="main-container" style="background-color: #fff;">
            <h6 class="page-title" style="text-align: center;">Send Enquiry</h6>
                
                    <div class="card-body">
                 <form class="ps-form--post-comment" action="" method="post">
                 <input type="hidden" name="ServiceId" value="<?php echo $_GET['id']; ?>">
                        <div class="form-group float-label">
                            <input type="text" class="form-control" name="Name" id="Name" autofocus required>
                            <label class="form-control-label">Full Name</label>
                        </div>
                    
                         <div class="form-group float-label">
                            <input type="number" class="form-control" name="Phone" id="Phone" required>
                            <label class="form-control-label">Phone Number</label>                            
                        </div>
                    
                         <div class="form-group float-label">
                            <input type="email" class="form-control" name="EmailId" id="EmailId">
                            <label class="form-control-label">Email Id</label>                            
                        </div>
                       
                        
                          

                        <div class="form-group float-label active">
                           <select class="form-control" id="StateId" name="StateId">
                               <option selected="" disabled="">Select State</option>
                                        <?php 
                                        $q = "select * from state Where CountryId='1'";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if('1'==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                              <?php } ?>
                            </select>
                            <label class="form-control-label">State</label>
                        </div>
                        <div class="form-group float-label active">
                           <select class="form-control" id="CityId" name="CityId">
                              <option selected="" disabled="">Select City</option>
                                              <?php 
                                        $q = "select * from city Where StateId='1'";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if('819'==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                              <?php } ?>
                            
                            </select>
                            <label class="form-control-label">City</label>
                        </div>

                        <div class="form-group float-label">
                                <textarea class="form-control" placeholder="Your Comments" name="Comments"></textarea>
                            </div>
 <button class="btn btn-block btn-default rounded" type="submit" id="submit" name="submit">Send</button>
              </form>
            </div>
        </div>
    </main>

    <!-- footer-->
    <?php include_once 'footer.php'; ?>
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
        <script src="js/toastr.min.js"></script>
    <script type="text/javascript">
        function addCart(id){
var action = "shop_cart";
var quantity = $('#qntno'+id).val();
var code = $('#code'+id).val();
var pid = $('#pid'+id).val();
var sizeid = $('#sizeid'+id).val();
var ramid = $('#ramid'+id).val();
var storageid = $('#storageid'+id).val();
var price = $('#prd_price'+id).val();
$.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:pid,quantity:quantity,code:code,sizeid:sizeid,ramid:ramid,storageid:storageid,price:price},
   beforeSend:function(){
     $('#add-cart'+id).attr('disabled','disabled');
     $('#add-cart'+id).text('Adding To Cart...');
    },

  success: function(data){
       toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart'+id).attr('disabled',false);
                       $('#add-cart'+id).text('Added..');
      }
});

        }


$(document).ready(function() {
        $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId').html(data);
    }
    });

 });
        });
    </script>
</body>

</html>
