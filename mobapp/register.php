<?php session_start();
require_once 'config.php';
$PageName = "New Registration";
$uid = $_REQUEST['uid'];
$city_id = $_REQUEST['city_id'];
//$_SESSION['Location'] = $city_id;
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$rncnt11 = getRow($sql11);
if($rncnt11 > 0){
    $row = getRecord($sql11);
    $_SESSION['User'] = $row;
    $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$uid'";
    $conn->query($sql);
} 

if(isset($_SESSION['User'])){
    echo "<script>window.location.href='home.php?city_id=$city_id';</script>";
}
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
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php //include_once 'back-header.php'; ?> 
   
<style type="text/css">
        .imgcenter {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
    </style>     
        <div class="main-container">

            <div class="container">
                <img src="logo2.png" class="imgcenter"><br>
                <div class="alert alert-success" style="font-size: 15px;" align="center">
                          If Already Registered! <a href="login.php?city_id=<?php echo $city_id;?>" >Sign In</a></div>
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off">
                   
                    <div class="card-body">

                   <div class="form-group float-label active">
                    <select class="form-control" id="Roll" name="Roll">
                        <option value="5" selected>Customer</option>
                        <option value="9">Dealer</option>
                        <option value="3">Manufacture</option>
                       
                    </select>
                            <label class="form-control-label">Created As</label>
                        </div>

      <!-- <div class="form-group float-label position-relative">
                            <input type="text" id="SponserId" class="form-control" oninput="checkSponserId()">
                            <label class="form-control-label">Sponser Id / Phone No</label>
                          <span id="error_msg2" style="color: red;"></span>
                        </div>
                        <div class="form-group float-label active position-relative">
                            <input type="text" id="MemberName" class="form-control" disabled>
                            <label class="form-control-label active">Sponser Name</label>
                        </div> -->
<input type="hidden" name="UnderBy" id="UnderUserId"> 
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
                         <div class="row">
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Fname" id="Fname" value="" autofocus>
                            <label class="form-control-label">Full Name</label>
                        </div>
                    </div>
                    <!-- <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Lname" id="Lname" value="">
                            <label class="form-control-label">Last Name</label>
                        </div>
                    </div> -->
                </div>

                 <div class="row">
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                         <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone" id="Phone" value="">
                            <label class="form-control-label">Phone Number</label>                            
                        </div>
                    </div>
                  
                   <input type="hidden" name="Password" id="Password" value="12345">
                  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                   <div class="form-group float-label active">
                            <input type="email" class="form-control" name="EmailId" id="EmailId" value="">
                            <label class="form-control-label">Email Id</label>                            
                        </div>
                        </div>
                     <!-- <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                         <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone2" id="Phone2" value="">
                            <label class="form-control-label">Whatsapp Number</label>                            
                        </div>
                    </div></div>
                         
                        
                        <div class="form-group float-label active">
                            <input type="password" class="form-control" name="Password" id="Password" value="">
                            <label class="form-control-label">Password</label>                            
                        </div>-->
                       
                         <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                          <div class="form-group float-label active">
                               <select class="form-control" id="CountryId" name="CountryId">
                                
                                     <option  value="1" selected="">India</option>
                                  
                                </select>
                                  <label class="form-control-label">Country</label>
                            </div>
</div>
 <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                           <select class="form-control" id="StateId" name="StateId">
                               <option value="" selected="" disabled="">Select State</option>
                               <?php 
                                        $q = "SELECT * FROM tbl_state WHERE Status=1";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">State</label>
                        </div>
                        </div>
 <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                           <select class="form-control" id="CityId" name="CityId">
                               <option value="" selected="" disabled="">Select City</option>
                                <?php 
                                    $StateId = $row11['StateId'];
                                        $q2 = "SELECT * FROM tbl_city WHERE Status=1 AND StateId='$StateId'";
                                        $r2 = $conn->query($q2);
                                        while($rw2 = $r2->fetch_assoc())
                                    {
                                ?>
                                    <option value="<?php echo $rw2['id']; ?>"><?php echo $rw2['Name']; ?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">City</label>
                        </div>
</div>
 <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                       

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Address" name="Address" value="">
                            <label class="form-control-label">Address</label>                            
                        </div>
                        </div>
 <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Pincode" name="Pincode" value="">
                            <label class="form-control-label">Pincode</label>                            
                        </div> 
  </div> 
                       <!--   <div class="form-group float-label active">
                            <input type="file" class="form-control" id="Photo" name="Photo">
                            <label class="form-control-label">Profile Photo</label>                            
                        </div>
                        -->
                     <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="DealerCode" name="DealerCode" value="">
                            <label class="form-control-label">Dealer Code</label>                            
                        </div> 
  </div> 
                  
                
                    </div>
     
                      <input type="hidden" name="action" value="Register" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Register</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>

    <!-- footer-->
    


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
    $(document).ready(function() {

             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var Fname = $('#Fname').val();
       var Mname = $('#Mname').val();
                var Lname = $('#Lname').val();
                var Phone = $('#Phone').val();

                var EmailId = $('#EmailId').val();
                var Password = $('#Password').val();
                var CountryId = $('#CountryId').val();
                var StateId = $('#StateId').val();
                var CityId = $('#CityId').val();
                var AreaId = $('#AreaId').val();
                var Pincode = $('#Pincode').val();
                var Address = $('#Address').val();
                if(Fname.trim() == ''){
                    toastr.error('Please Enter First Name', 'Error', {timeOut: 2000}); 
                    //alert("Please Enter First Name");
                    $('#Fname').focus();
                }
               /* else if(Lname.trim() == ''){
                    alert("Please Enter Last Name");
                    $('#Lname').focus();
                }*/
                else if(Phone.trim() == ''){
                    //alert("Please Enter Phone No");
                    toastr.error('Please Enter Phone No', 'Error', {timeOut: 2000}); 
                    $('#Phone').focus();
                }
                else if(! /^([0-9]{10})+$/.test(Phone)) {
                  //alert("Mobile Number must be 10 Digit!");
                    toastr.error('Mobile Number must be 10 Digit!', 'Error', {timeOut: 2000});
                  $('#Phone').focus();
                }
                 else if(Password.trim() == ''){
                    //alert("Please Enter a Password");
                    toastr.error('Please Enter a Password', 'Error', {timeOut: 2000});
                    $('#Password').focus();
                }
                else if(CountryId == null){
                    //alert("Please Select Country");
                    toastr.error('Please Select Country', 'Error', {timeOut: 2000});
                    $('#CountryId').focus();
                }
                else if(StateId == null){
                    //alert("Please Select State");
                    toastr.error('Please Select State', 'Error', {timeOut: 2000});
                    $('#StateId').focus();
                }
                else if(CityId == null){
                    //alert("Please Select City");
                    toastr.error('Please Select City', 'Error', {timeOut: 2000});
                    $('#CityId').focus();
                }
                /*else if(AreaId == null){
                    alert("Please Select Area");
                    $('#AreaId').focus();
                }*/
                else if(Pincode.trim() == ''){
                    //alert("Please Enter Pincode");
                    toastr.error('Please Enter Pincode', 'Error', {timeOut: 2000});
                    $('#Pincode').focus();
                }
                else if(Address.trim() == ''){
                    //alert("Please Enter Address");
                    toastr.error('Please Enter Address', 'Error', {timeOut: 2000});
                    $('#Address').focus();
                }
               
    else { 
      
         $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  //console.log(data);exit();
                   var res = JSON.parse(data);
                    var status = res.status;
                    var roll = res.roll;
                    var Username = res.Username;
                    var uid = res.uid;
                     if(status == 0){
                        toastr.error('Phone Already Exist', 'Error', {timeOut: 1000}); 
                     
                     }
                     else{
                    //Android.loginUser(Username,uid);    
                    toastr.success('OTP Sent On Your Registered Mobile No.! Please Verify.', 'Success', {timeOut: 5000}); 
                    //window.location.href="home.php?city_id="+CityId;
                    window.location.href="login-otp-verify.php?roll="+roll+"&uid="+uid+"&Username="+Username;
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Register');
                }  
           })  



    }

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
