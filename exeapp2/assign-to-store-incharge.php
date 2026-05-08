<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Store-Incharge";
$Page = "Assign-Store-Incharge";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Sell List</title>
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
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">








<?php

                if (isset($_POST['submit'])) {

 $rncnt = $_POST['rncnt'];
    $rncnt2 = $_POST['rncnt2'];
    if($rncnt > 0){
                    $number = count($_POST['CheckId']);

                    $StoreInchId = $_POST['StoreInchId'];
                    $CreatedDate = date('Y-m-d H:i:s');
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["CheckId"][$i] != '')) {
                                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                                if ($CheckId == 1) {
                                    $QtnId = addslashes(trim($_POST['QtnId'][$i]));
                                    $sql = "UPDATE tbl_quotation SET StoreInchStatus='1',StoreInchId='$StoreInchId',StoreInchDate='$CreatedDate' WHERE id='$QtnId'";
                                    $conn->query($sql);
                                }
                            }
                        }
                    }

}

if($rncnt2 > 0){
                     $number2 = count($_POST['CheckId2']);

                    $StoreInchId = $_POST['StoreInchId'];
                    $CreatedDate = date('Y-m-d H:i:s');
                    if ($number2 > 0) {
                        for ($i2 = 0; $i2 < $number2; $i2++) {
                            if (trim($_POST["CheckId2"][$i2] != '')) {
                                $CheckId2 = addslashes(trim($_POST['CheckId2'][$i2]));
                                if ($CheckId2 == 1) {
                                    $QtnId = addslashes(trim($_POST['QtnId2'][$i2]));
                                    echo $sql = "UPDATE tbl_users SET StoreInchStatus='1',StoreInchId='$StoreInchId',StoreInchDate='$CreatedDate' WHERE id='$QtnId'";
                                    $conn->query($sql);
                                }
                            }
                        }
                    }
}

                    $Title = "Customer Assign";
                    $Message = "Customer Assign To you for Further Process";
                    $sql73 = "SELECT Tokens,id FROM tbl_users WHERE Status='1' AND Tokens!=''";
                    $sql73 .= " AND id='$StoreInchId'";


                    //echo $sql73;exit();
                    $data = mysqli_query($con, $sql73);

                    while ($row = mysqli_fetch_array($data)) {

                        $ReceiverId = $row['id'];
                        $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id',ReceiverId='$ReceiverId',Title='$Title',Message='$Message',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
                        $conn->query($sql);

                        $title = $Title;
                        $body =  $Message;
                        $reg_id = $row[0];
                        $registrationIds = array($reg_id);
                        //$url = "$SiteUrl/profile.php?id=$UserId";
                        include '../incnotification.php';
                    }
                    echo "<script>alert('Assign To Store Incharge');window.location.href='assign-to-store-incharge.php';</script>";
                }
                ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign To Store Incharge
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<div class="card" style="padding: 10px;">
    <form id="validation-form" method="post" enctype="multipart/form-data" action="">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                
<div class="form-row">
 <div class="form-group col-lg-4">
<label class="form-label"> Store Incharge<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="StoreInchId" id="StoreInchId" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>



</div>

       

 


                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Assign To</th>
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Address</th>
                <th>QTN NO</th>
                <th>QTN Date</th>
                <th>Paid Status</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Bal Amount</th>
                <th>Paid Date</th>
                <th>Paid By</th>
              
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.PaidStatus=1
            
            ORDER BY tp.CreatedDate DESC";
             $rncnt = getRow($sql);?>
        <input type="hidden" name="rncnt" value="<?php echo $rncnt;?>">
        <?php 
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
               $sql22 = "SELECT * FROM tbl_quotation WHERE StoreInchStatus=1 AND id='".$row['id']."'";
                $rncnt22 = getRow($sql22);
                if($rncnt22 > 0){
                     $bcolor = "background-color: #b9efb9;";
                }
                else{
                    $bcolor = "";
                }

             ?>
            <tr style="<?php echo $bcolor;?>">
               <td><?php if($rncnt22 > 0){} else{?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId[]">
               <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">

<td><?php echo $row['InchargeName']; ?></td> 
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                <td><?php echo $row['Address']; ?></td>
                <td><?php echo $row['InvoiceNo']; ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
                <td><?php if($row['PaidStatus']=='1'){echo "<span style='color:green;'>Paid</span>";} else { echo "<span style='color:red;'>Not Paid</span>";} ?></td>
                <td>&#8377;<?php echo number_format($row["TotalAmt"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["PaidAmt"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["TotalAmt"]-$row["PaidAmt"],2); ?></td>
                <td><?php if($row['PaidStatus']=='1'){ echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaidDate']))); } ?></td>
                <td><?php echo $row['PayMode']; ?></td>
             
              
            </tr>
           <?php $i++;} ?>
           
            <?php
                                            $i = 1;
                                            $sql = "SELECT tu.*,tu2.Fname As InchargeName FROM tbl_users tu 
                                                    LEFT JOIN tbl_users tu2 ON tu2.id=tu.StoreInchId 
                                                    WHERE tu.ProjectType=1 ORDER BY tu.CreatedDate DESC";
                                                    $rncnt2 = getRow($sql);?>
        <input type="hidden" name="rncnt2" value="<?php echo $rncnt2;?>">
        <?php
                                            $res = $conn->query($sql);
                                            while ($row = $res->fetch_assoc()) {

                                                $sql22 = "SELECT * FROM tbl_users WHERE StoreInchStatus=1 AND id='" . $row['id'] . "'";
                                                $rncnt22 = getRow($sql22);
                                                if ($rncnt22 > 0) {
                                                    $bcolor = "background-color: #b9efb9;";
                                                } else {
                                                    $bcolor = "";
                                                }

                                            ?>
                                                <tr style="<?php echo $bcolor; ?>">
                                                    <td><?php if ($rncnt22 > 0) {
                                                        } else { ?>
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" id="Check_Id2<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured2(<?php echo $row['id']; ?>)">
                                                                <span class="custom-control-label">&nbsp;</span>
                                                            </label><?php } ?>
                                                    </td>
                                                    <input type="hidden" value="0" name="CheckId2[]" id="CheckId2<?php echo $row['id']; ?>">
                                                    <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId2[]">
                                                    <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
                                                    <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
                                                    <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">

                                                    <td><?php echo $row['InchargeName']; ?></td>
                                                    <td><?php echo $row['Fname']; ?></td>

                                                    <td><?php echo $row['Phone']; ?></td>
                                                    <td><?php echo $row['Address']; ?></td>
                                                    <td><?php echo $row['InvoiceNo']; ?></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>


                                                </tr>
                                            <?php $i++;
                                            } ?>
        </tbody>
    </table>
</div>

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
</div>
</form>
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
<script type="text/javascript">
     function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }

    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    });
});
</script>
</body>
</html>
