<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "New Registration";
$UserId = $_SESSION['User']['id']; ?>
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
        <?php include_once 'back-header.php'; ?> 
        
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off">
                   
                    <div class="card-body">
                         <div class="row">
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Fname" id="Fname" value="<?php echo $row11['Fname']; ?>" autofocus required>
                            <label class="form-control-label">First Name</label>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Lname" id="Lname" value="<?php echo $row11['Lname']; ?>" required>
                            <label class="form-control-label">Last Name</label>
                        </div>
                    </div>
                </div>

                 <div class="row">
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                         <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone" id="Phone" value="<?php echo $row11['Phone']; ?>" required>
                            <label class="form-control-label">Phone Number</label>                            
                        </div>
                    </div>
                     <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                         <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone2" id="Phone2" value="<?php echo $row11['Phone2']; ?>">
                            <label class="form-control-label">Whatsapp Number</label>                            
                        </div>
                    </div></div>
                         <div class="form-group float-label active">
                            <input type="email" class="form-control" name="EmailId" id="EmailId" value="<?php echo $row11['EmailId']; ?>">
                            <label class="form-control-label">Email Id</label>                            
                        </div>

                        <div class="form-group float-label active">
                            <input type="password" class="form-control" name="Password" id="Password" value="<?php echo $row11['Password']; ?>">
                            <label class="form-control-label">Password</label>                            
                        </div>
                       
                        
                          <div class="form-group float-label active">
                               <select class="form-control" id="CountryId" name="CountryId">
                                
                                     <option  value="1" selected="">India</option>
                                  
                                </select>
                                  <label class="form-control-label">Country</label>
                            </div>


                        <div class="form-group float-label active">
                           <select class="form-control" id="StateId" name="StateId">
                               <option value="" selected="" disabled="">Select State</option>
                               <?php 
                                        $q = "SELECT * FROM state WHERE Status=1";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                    <option <?php if($row11['StateId'] == $rw['id']) {?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">State</label>
                        </div>
                        <div class="form-group float-label active">
                           <select class="form-control" id="CityId" name="CityId">
                               <option value="" selected="" disabled="">Select City</option>
                                <?php 
                                    $StateId = $row11['StateId'];
                                        $q2 = "SELECT * FROM city WHERE Status=1 AND StateId='$StateId'";
                                        $r2 = $conn->query($q2);
                                        while($rw2 = $r2->fetch_assoc())
                                    {
                                ?>
                                    <option <?php if($row11['CityId'] == $rw2['id']) {?> selected <?php } ?> value="<?php echo $rw2['id']; ?>"><?php echo $rw2['Name']; ?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">City</label>
                        </div>

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Address" name="Address" value="<?php echo $row11['Address']; ?>">
                            <label class="form-control-label">Address</label>                            
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Pincode" name="Pincode" value="<?php echo $row11['Pincode']; ?>">
                            <label class="form-control-label">Pincode</label>                            
                        </div>

                        <div class="form-group float-label active">
                           <select class="form-control" id="Roll" name="Roll" required>
                               <option value="" selected="" disabled="">Select Account Type</option>
                               
                               <option value="4">Doctor</option>
                                <option value="5">Optician</option>
                                <option value="6">Wholesaler</option>
                                <option value="7">Customer</option>
                                <option value="8">Retailer</option>
                          
                            </select>
                            <label class="form-control-label">Account Type</label>
                        </div>

                      
                     
                  
                
                    </div>
                        
                          <input type="hidden" name="CreatedBy" value="<?php echo $_SESSION['User']['id']; ?>" id="CreatedBy">  
                      <input type="hidden" name="action" value="NewReg" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Submit</button>
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
                  console.log(data);
                  var res = JSON.parse(data);
                  var msg = res.msg;
                  var status = res.status;
                     if(status == 0){
                        toastr.error(msg, 'Error', {timeOut: 2000}); 
                     
                     }
                     else{
                    toastr.success(msg, 'Success', {timeOut: 3000});
                    setTimeout(function(){  
                      window.location.href="view-customers.php";
                    }, 3000);  
                    
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Submit');
                }  
           })  



    

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
});
</script>
</body>

</html>
