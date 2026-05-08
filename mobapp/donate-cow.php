<?php session_start();
require_once 'config.php';
$PageName = "Donate Cow";
$UserId = $_SESSION['User']['id'];
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include 'back-header.php'; ?>
       
        <div class="main-container">
            <div class="container">
                <div class="card mb-4">
                  
                  
                   
                       <form id="validation-form" method="post" autocomplete="off">
                    <div class="card-body">
                         <div id="alert_message"></div>
                         <div class="form-group float-label">
                            <input type="text" name="Name" id="Name" class="form-control" autofocus required value="<?php echo $row110['Fname']." ".$row110['Lname']; ?>">
                            <label class="form-control-label">Your Name</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" name="Phone" id="Phone" class="form-control" required value="<?php echo $row110['Phone'];?>">
                            <label class="form-control-label">Phone No</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" name="EmailId" id="EmailId" class="form-control" required value="<?php echo $row110['EmailId'];?>">
                            <label class="form-control-label">Email Id</label>
                        </div>
                         <div class="form-group float-label active">
                            <input type="file" name="Photo" id="Photo" class="form-control" required>
                            <label class="form-control-label active">Upload Cow Photo</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="number" name="Price" id="Price" class="form-control" required>
                            <label class="form-control-label">Cow Price</label>
                        </div>
                        <div class="form-group float-label">
                            <textarea name="Message" id="Message" class="form-control" required></textarea>
                            <label class="form-control-label">Your Message</label>
                        </div>
                        
                    </div>
                    
                      <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="DonateCow" id="action">  
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
                success:function(data){ 
                    
                        $('#alert_message').removeClass('alert alert-danger');
                        $('#alert_message').fadeIn().addClass('alert alert-success').html("Your Enquiry Sent Successfully...");
        setTimeout(function(){  
            $('#alert_message').fadeOut("Slow"); 
             window.location.href = 'index.php';
        }, 2000);
                     
                }  
           })  

     });

  });

</script>
</body>

</html>
