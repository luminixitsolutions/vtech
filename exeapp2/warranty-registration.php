<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "View-Customer-Payment";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Customer Payment
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
</head>

<body>
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_warranty_registration WHERE id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];
    if($_GET['id'] == ''){
    $sql = "SELECT * FROM tbl_warranty_registration WHERE CustId='$CustId'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        echo "<script>alert('Warranty Registration Already Saved');window.location.href='warranty-registration.php';</script>";
    }
    else{
        $sql2 = "INSERT INTO tbl_warranty_registration SET CustId='$CustId',StartDate='$StartDate',EndDate='$EndDate'";
        $conn->query($sql2);
        echo "<script>alert('Warranty Registration Saved Successfully');window.location.href='view-warranty-registration.php';</script>";
    }
}
else{

    $sql = "SELECT * FROM tbl_warranty_registration WHERE CustId='$CustId' AND id!='$id'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        echo "<script>alert('Warranty Registration Already Saved');window.location.href='warranty-registration.php?id=$id';</script>";
    }
    else{
        $sql2 = "UPDATE tbl_warranty_registration SET CustId='$CustId',StartDate='$StartDate',EndDate='$EndDate' WHERE id='$id'";
        $conn->query($sql2);
        echo "<script>alert('Warranty Registration Update Successfully');window.location.href='view-warranty-registration.php';</script>";
    }

}
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Warranty Registration</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="SaveVed" id="action">
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
<label class="form-label">Customer <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="CustId" name="CustId"  required>
   <option value="" selected>...</option>
    <?php 
     $sql1 = "SELECT tu.*,td.InvNo,td.InvoiceDate FROM tbl_delivered_invoice td 
                    LEFT JOIN tbl_users tu ON td.CustId=tu.id WHERE tu.Roll=5";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option <?php if($result['id'] == $row7['CustId']) {?> selected <?php } ?>  value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." ".$result['Lname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                      

                                         <div class="form-group col-md-3">
                                            <label class="form-label">Start Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="StartDate" id="StartDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["StartDate"]; ?>"
                                                autocomplete="off" required>
                                        </div>

                                         <div class="form-group col-md-3">
                                            <label class="form-label">End Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="EndDate" id="EndDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["EndDate"]; ?>"
                                                autocomplete="off" required>
                                        </div>

                                        
                                        
                                        
                                       
                                       


                                       

                                    </div><br>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit" name="submit">Save</button>
                                </form>
                            </div>
                        </div>






                    </div>


                    <?php include_once 'footer.php'; ?>
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
       <?php include_once 'footer_script.php'; ?>

  
</body>

</html>