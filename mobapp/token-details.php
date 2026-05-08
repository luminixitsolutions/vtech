<?php session_start();
require_once 'config.php';
require_once 'auth.php';

$UserId = $_SESSION['User']['id'];

$id = $_GET['id'];
$sql = "SELECT * FROM tbl_helps_enquiry WHERE id='$id'";
$row = getRecord($sql);
$PageName = $row['TokenNo'];
$Status = $row['Status'];
if($Status == 1){
    $OrderStatus = "<div class='alert alert-secondary'>Pending</div>";
}
else if($Status == 2){
    $OrderStatus = "<div class='alert alert-warning'>In Progress</div>";
}
else if($Status == 3){
    $OrderStatus = "<div class='alert alert-danger'>Reject</div>";
}
else if($Status == 4){
    $OrderStatus = "<div class='alert alert-success'>Complete</div>";
}
else{
    $OrderStatus = "";
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include 'back-header.php'; ?>
       
        <div class="main-container">

            <div class="container">
                 <span style="text-align: center;">
                <?php echo $OrderStatus; ?>
            </span>
                <?php  
                $sql2 = "SELECT * FROM tbl_help_support_details WHERE TokenId='$id' ORDER BY CreatedDate ASC";
                $row2 = getList($sql2);
                foreach($row2 as $result){
                    if($result['SenderName'] == 'Customer'){
                        $Color = "style='background-color: aliceblue;'";
                    }
                    else{
                        $Color = "style='background-color: antiquewhite;'";
                    }
                ?>
                <div class="card mb-4" <?php echo $Color;?>>
                    <div class="card-body">
                        <h6>Dt. <?php echo date("d-m-Y", strtotime(str_replace('-', '/',$result['CreatedDate'])));?></h6>
                        <address>
                            <?php echo $result['Message'];?>
                        </address>
                       
                    </div>
                </div>
            <?php } ?>

            <?php 
            $sql3 = "SELECT * FROM tbl_help_support_details WHERE TokenId='$id' AND SenderName='Admin'"; 
            $rncnt3 = getRow($sql3);
            if($rncnt3 > 0){
                if($Status == 3 || $Status == 4){}
                    else{
            ?>
            <form id="validation-form" method="post" autocomplete="off">
                    <div class="card-body">
                         <div id="alert_message"></div>
                       
                        <div class="form-group float-label">
                            <textarea name="Message" id="Message" class="form-control" required></textarea>
                            <label class="form-control-label">Your Message</label>
                        </div>
                        
                    </div>
                      <input type="hidden" name="TokenId" id="TokenId" value="<?php echo $_GET["id"];?>" /> 
                     <input type="hidden" name="TokenNo" id="TokenNo" value="<?php echo $row['TokenNo'];?>" />   
                     <input type="hidden" name="Status" id="Status" value="<?php echo $Status;?>" />   
                      <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="ReplyHelpSupport" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Reply</button>
                    </div>
                </form>
            <?php } } ?>
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
            var TokenId = $('#TokenId').val();
       $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                success:function(data){ 
                    
                        $('#alert_message').removeClass('alert alert-danger');
                        $('#alert_message').fadeIn().addClass('alert alert-success').html("Your Reply Sent Successfully...");
        setTimeout(function(){  
            $('#alert_message').fadeOut("Slow"); 
             window.location.href = 'token-details.php?id='+TokenId;
        }, 2000);
                     
                }  
           })  

     });

  });

</script>
</body>

</html>
