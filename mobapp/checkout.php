<?php session_start();
require_once 'config.php';
$PageName = "Checkout";
$user_id = $_SESSION['User']['id'];
$Page = "checkout";?>
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="checkout">
    <!-- screen loader -->
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->
    
        <div class="main-container">
            <?php if(isset($_SESSION['User'])) {?>
            <div class="container mb-4">
                <div class="row mb-3">
                    <div class="col">
                        <h6 class="subtitle">Shipping Address</h6>
                    </div>
                    <div class="col-auto"> 
                        <?php if($_GET['aid']){ ?>
                        <a href="my-address.php?aid=<?php echo $_GET['aid'];  ?>" class="float-right">Change Address</a>
                    <?php } else{?>
                        <a href="my-address.php" class="float-right">Change Address</a>
                    <?php } ?>
                    </div>
                </div>
 <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
               <div class="card mb-4">
                <?php 
                 $sql8 = "SELECT min(id) as minid FROM customer_address WHERE UserId='$user_id'";
                 $row8 = getRecord($sql8);
                 if($_GET['aid']){
                  $aid = $_GET['aid'];  
                 }
                 else{
                 $aid = $row8['minid'];
                 }

                 $sql81 = "SELECT cd.*,s.Name As State,c.Name As Country,ct.Name As City,a.Name As Area FROM customer_address cd
         LEFT JOIN tbl_country c ON c.id=cd.CountryId
         LEFT JOIN tbl_state s ON s.id = cd.StateId
         LEFT JOIN tbl_city ct ON ct.id = cd.CityId 
         LEFT JOIN tbl_area a ON a.id = cd.AreaId 
         WHERE cd.id='$aid' ORDER BY cd.id ASC";
         $rncnt7 = getRow($sql81);
         if($rncnt7 > 0){
                 $row7 = getRecord($sql81);
                 ?>
                    <div class="card-body">
                        <h6><?php echo $row7['Fname']." ".$row7['Lname']; ?></h6>
                        <address>
                            <?php echo $row7['Address']." - ".$row7['Pincode'];?><br>
                            <?php echo $row7['Area'].", ".$row7['City'].", ".$row7['State']." - ".$row7['Country'];?>
                        </address>
                        <p>Ph.: <?php echo $row7['Phone'];?></p>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="address" class="custom-control-input" id="ShippingAddress" value="<?php echo $row7['id'];?>" checked>
                            <label class="custom-control-label" for="ShippingAddress" style="    display: block;">Delivery Address</label>
                            
                        </div>
                    </div>
                <?php } ?>
                </div>   

            </div>

            
        <?php } else{?>
            <div class="container mb-4">
                
 <div class="alert alert-success" style="font-size: 15px;" align="center">
                          If Already Registered! <a href="login.php?page=checkout" >Sign In</a></div>
               <div class="card">
                  
                    <div class="card-body">
                        <?php if(!isset($_SESSION['User'])){?>
                                        <div style="display: none;">
                                    <input type="checkbox" id="shipdiff" value="1" class="form-control" checked="" disabled>
                                </div>
                                    <?php } ?>
                               
<!-- <div class="form-group float-label active">
                    <select class="form-control" id="Roll" name="Roll">
                        <option value="7" selected>Customer</option>
                        <option value="4">Doctor</option>
                        <option value="5">Optician</option>
                        <option value="6">Wholesaler</option>
                        <option value="8">Retailer</option>
                    </select>
                            <label class="form-control-label">Created As</label>
                        </div> -->
                       <!--   <div class="form-group float-label position-relative">
                            <input type="text" id="SponserId" class="form-control" oninput="checkSponserId()">
                            <label class="form-control-label">Sponser Id / Phone No</label>
                          <span id="error_msg2" style="color: red;"></span>
                        </div>
                        <div class="form-group float-label active position-relative">
                            <input type="text" id="MemberName" class="form-control" disabled>
                            <label class="form-control-label active">Sponser Name</label>
                        </div> -->

<script type="text/javascript">
    function checkSponserId(){
        var SponserId = $('#SponserId').val();
        var action = "checkSponserId";
      $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,SponserId:SponserId},
    success:function(data)
    {
        console.log(data);
      var res = JSON.parse(data);
      var status = res.status;
      var msg = res.msg;
      var name = res.name;
      var id = res.id;
      if(status == 1){
        $('#UnderUserId').val(id);
        $('#MemberName').val(name);
        $('#error_msg2').html('');
        $('#submit').attr('disabled',true);
      }
      else{
        $('#UnderUserId').val('');
        $('#MemberName').val('');
        $('#error_msg2').html(msg);
        $('#submit').attr('disabled',true);
      }
    }
    });
    }
</script>

<div class="row ">
                    <div class="col">
                        <h6 class="subtitle">Please Fill Form</h6>
                    </div>
                    
                         
                    
                </div>
                
                <div class="form-group float-label active">
                    <select class="form-control" id="Roll" name="Roll">
                        <option value="5" selected>Customer</option>
                        <option value="9">Dealer</option>
                        <option value="3">Manufacture</option>
                       
                    </select>
                            <label class="form-control-label">Created As</label>
                        </div>
                        
                        <div class="form-group float-label position-relative">
                            <input type="text" id="Fname" class="form-control">
                            <label class="form-control-label">First Name</label>
                          
                        </div>
                         <div class="form-group float-label position-relative">
                            <input type="text" id="Lname" class="form-control">
                            <label class="form-control-label">Last Name</label>
                          
                        </div>
                         <div class="form-group float-label position-relative">
                            <input type="number" id="Phone" class="form-control">
                            <label class="form-control-label">Contact No</label>
                          
                        </div>
                        <div class="form-group float-label position-relative">
                            <input id="EmailId" type="email" class="form-control">
                            <label class="form-control-label">Email Id</label>
                          
                        </div>
                         <?php if(!isset($_SESSION['User'])) {?>
                        <div class="form-group float-label position-relative">
                            <input type="password" id="Password" class="form-control">
                            <label class="form-control-label">Password</label>
                          
                        </div>
                         <?php } ?>
                       <div class="form-group float-label position-relative active">
                            <select class="form-control" name="CountryId" id="CountryId" required="">
                             <!--    <option selected="" disabled="" value="">Select Country</option>-->
                                <option value="1">India</option>
                            </select>
                     
                        </div>
<div class="form-group float-label position-relative active">
                            <select class="form-control" id="StateId" name="StateId" required="">
                                 <option selected="" disabled="" value="">Select State</option>
                                 <?php 
                                        $q = "SELECT * FROM tbl_state WHERE Status=1";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                    <option <?php if(1 == $rw['id']) {?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                            <?php } ?>
                            </select>
                           
                        </div>

                        <div class="form-group float-label position-relative active">
                            <select class="form-control" id="CityId" name="CityId" required="">
                                 <option selected="" disabled="" value="">Select City</option>
                                <?php 
                                    $StateId = 1;
                                        $q2 = "SELECT * FROM tbl_city WHERE Status=1 AND StateId='$StateId'";
                                        $r2 = $conn->query($q2);
                                        while($rw2 = $r2->fetch_assoc())
                                    {
                                ?>
                                    <option <?php if($row11['CityId'] == $rw2['id']) {?> selected <?php } ?> value="<?php echo $rw2['id']; ?>"><?php echo $rw2['Name']; ?></option>
                            <?php } ?>
                            </select>
                           
                        </div>

                       <!-- <div class="form-group float-label position-relative active">
                            <select class="form-control" id="AreaId" name="AreaId" required="">
                                 <option selected="" disabled="" value="">Select Area</option>
                               
                            </select>
                           
                        </div>-->

                        <div class="form-group float-label position-relative">
                            <textarea class="form-control" rows="3" id="Address"></textarea>
                            <label class="form-control-label">Address</label>
                        </div>

                         <div class="form-group float-label position-relative">
                            <input type="number" id="Pincode" class="form-control">
                            <label class="form-control-label">Pincode</label>
                          
                        </div>
                           
                    </div>
                </div>
            </div>
        <?php } ?>
           <input type="hidden" id="user_id" value="<?php echo $_SESSION['User']['id']; ?>">
           
            <div class="container text-center">
                
                <a href="javascript:void(0)" id="place_order" class="btn btn-primary btn-block rounded">Order Summary</a>
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
   
    function PaymentMode(id){
        $('#PaymentMethod').val(id);
    }
     function validation(){
         var Roll = $('#Roll').val();
                var Fname = $('#Fname').val();
                var Lname = $('#Lname').val();
                var Phone = $('#Phone').val();
                var EmailId = $('#EmailId').val();
                var CountryId = $('#CountryId').val();
                var StateId = $('#StateId').val();
                var CityId = $('#CityId').val();
                var AreaId = $('#AreaId').val();
                var Pincode = $('#Pincode').val();
                var Address = $('#Address').val();
                if(Fname.trim() == ''){
                    alert("Please Enter First Name");
                    $('#Fname').focus();
                }
                else if(Lname.trim() == ''){
                    alert("Please Enter Last Name");
                    $('#Lname').focus();
                }
                else if(Phone.trim() == ''){
                    alert("Please Enter Phone No");
                    $('#Phone').focus();
                }
                else if(! /^([0-9]{10})+$/.test(Phone)) {
                  alert("Mobile Number must be 10 Digit!");
                  $('#Phone').focus();
                }
                else if(EmailId.trim() == ''){
                    alert("Please Enter Email Id");
                    $('#EmailId').focus();
                }
                else if(CountryId == null){
                    alert("Please Select Country");
                    $('#CountryId').focus();
                }
                else if(StateId == null){
                    alert("Please Select State");
                    $('#StateId').focus();
                }
                else if(CityId == null){
                    alert("Please Select City");
                    $('#CityId').focus();
                }
               /* else if(AreaId == null){
                    alert("Please Select Area");
                    $('#AreaId').focus();
                }
                */
                else if(Address.trim() == ''){
                    alert("Please Enter Address");
                    $('#Address').focus();
                }
                else if(Pincode.trim() == ''){
                    alert("Please Enter Pincode");
                    $('#Pincode').focus();
                }
                else{
                    return true;
                }
    }
 
      function place_order(){
        var user_id = $('#user_id').val();
        if(user_id != 0 || user_id != ''){
            var addid = $('#ShippingAddress').val();
            //alert(addid);exit();
            if(addid == undefined){
                 $('#danger_message').css('display','block').html("Please Select Delivery Address");
                   
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
            }
            else{
            window.location.href="place-order.php?addid="+addid+"&userid="+user_id;
        }
        }
        else{
        var action = "OrderSummary";
          var Fname = $('#Fname').val();
                var Lname = $('#Lname').val();
                var Phone = $('#Phone').val();
                var EmailId = $('#EmailId').val();
                var CountryId = $('#CountryId').val();
                var StateId = $('#StateId').val();
                var CityId = $('#CityId').val();
                var AreaId = $('#AreaId').val();
                var Pincode = $('#Pincode').val();
                var Address = $('#Address').val();
                var Password = $('#Password').val();
                var ShippingAddress = $('#ShippingAddress').val();
                 var PaymentMethod = $('#PaymentMethod').val();
                 var ShippingCharge = $('#ShippingCharge').val();
                  var promo_price = $('#promo_price').val();
                 var Promocode = $('#Promocode').val();
                 var custlocation = $('#custlocation').val();
                 var latitude = $('#latitude').val();
                 var longitude = $('#longitude').val();
                 var app = $('#app').val();
                 var Roll = $('#Roll').val();
                 var UnderUserId = $('#UnderUserId').val();
                 //alert(latitude);alert(longitude);
                 if(validation()){
         $.ajax({
      url:"ajax_files/ajax_shop_cart.php",
      type:"POST",
      data:{action:action,Fname:Fname,Lname:Lname,Phone:Phone,EmailId:EmailId,CountryId:CountryId,StateId:StateId,CityId:CityId,AreaId:AreaId,Pincode:Pincode,Address:Address,ShippingAddress:ShippingAddress,PaymentMethod:PaymentMethod,ShippingCharge:ShippingCharge,Password:Password,promo_price:promo_price,Promocode:Promocode,latitude:latitude,longitude:longitude,Roll:Roll,UnderUserId:UnderUserId},
      beforeSend:function(){
     $('#place_order').attr('disabled','disabled');
     $('#place_order').text('Please Wait...');
    },
      success:function(data){
        //alert(data);exit();
        res = JSON.parse(data);
        var status = res.status;
        var userid = res.userid;
        var uid = res.userid;
        var addid = res.addid;
        var msg = res.msg;
        var Username = res.Username;
        if(status == 1){
            Android.loginUser(Username,uid);    
        window.location.href="place-order.php?addid="+addid+"&userid="+userid;
        }
        else{
            alert(msg);
        }
      },

    });
       }
     }
     
 }
      


 $(document).ready(function(){

      $(document).on("click", "#place_order", function(event){
        
            place_order();
}); 
   $(document).on("change", "#CountryId", function(event){
  var val = this.value;
   var action = "getState";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#StateId').html(data);
    }
    });

 });

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

 $(document).on("change", "#CityId", function(event){
  var val = this.value;
   var action = "getArea";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AreaId').html(data);
    }
    });
    });


});
  </script>
</body>

</html>
