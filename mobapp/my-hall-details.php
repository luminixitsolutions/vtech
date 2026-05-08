<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$OrderId = $_GET['oid'];
$sql21 = "SELECT ord.*,ca.Fname,ca.Lname,ca.Address,ca.Pincode,ca.Phone,ca.EmailId,c.Name As Country,s.Name As State,ct.Name As City FROM orders ord 
LEFT JOIN customer_address ca ON ca.id=ord.AddressId
LEFT JOIN country c ON c.id=ca.CountryId
LEFT JOIN state s ON s.id=ca.StateId
LEFT JOIN city ct ON ct.id=ca.CityId
WHERE ord.id='$OrderId'";
$res21 = $conn->query($sql21);
$row21 = $res21->fetch_assoc();
if($row21['OrderProcess'] == 1){
    $OrderStatus = "<div class='alert alert-success'>Hall Booked</div>";
}
else if($row21['OrderProcess'] == 2){
    $OrderStatus = "<div class='alert alert-warning'>In Progress</div>";
}
else if($row21['OrderProcess'] == 3){
    $OrderStatus = "<div class='alert alert-danger'>Order Cancel</div>";
}
else if($row21['OrderProcess'] == 4){
    $OrderStatus = "<div class='alert alert-warning'>In Progress</div>";
}
else if($row21['OrderProcess'] == 5){
    $OrderStatus = "<div class='alert alert-info'>Order Dispatch</div>";
}
else{
    $OrderStatus = "";
}
$ShippingCharge = $row21['ShippingCharge'];
$Promoprice = $row21['Promoprice'];
$Discount = $row21["Discount"];
$Disc_Percentage = $row21["DiscPer"];
                             
$sql87 = "SELECT EmpId FROM confirm_orders WHERE Ordid='$OrderId'";
$row87 = getRecord($sql87);
$empid = $row87['EmpId'];
/*$sql88 = "SELECT latitude,longitude FROM employee WHERE id='$empid'";
$row88 = getRecord($sql88);*/
$PageName = $row21['OrderNo']; ?>
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->

        <div class="main-container" style="background-color: #fbfbfb;">
            <div class="container" id="show_prod">
                <span style="text-align: center;">
                 <?php echo $OrderStatus; ?>
                <?php 
                                    function getAttrDetails($id){
                                         global $conn;
                                         $sql3 = "SELECT * FROM attribute_value WHERE id = '$id'";
                                         $res3 = $conn->query($sql3);
                                         $row_cnt3 = mysqli_num_rows($res3);
                                         $row3 = $res3->fetch_assoc();
                                         if($row_cnt3 > 0){
                                              $Size = $row3['Name'];
                                         }
                                          return $Size;
                                    }

                                    $OrderId = $_GET['oid'];
                                     $sql2 = "SELECT od.*,p.ProductName,p.Photo,p.CatId,p.id As pid,p.SubCatId FROM order_details od
                                            LEFT JOIN products p ON p.id=od.ProductId 
                                            WHERE od.OrderId='$OrderId' ORDER BY od.id ASC";
                                        $res2 = $conn->query($sql2);
                                        $row_cnt = mysqli_num_rows($res2);
                                        if($row_cnt > 0){
                                        while($row = $res2->fetch_assoc()){
                                            $GrandTotal += $row['Price'];
                                            $TotalQty += $row['Quantity'];
                                            $SizeId = $row['SizeId'];
                                            $RamId = $row['RamId'];
                                            $StorageId = $row['StorageId'];
                                            $ColorId = $row['ColorId'];
                                            $ProdId = $row["pid"];
                                            $SubCatId = $row['SubCatId'];
                                         $sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
                                        $row11 = getRecord($sql11);
                                            
                                     ?>
                <div class="media mb-4 w-100">
                    <div class="avatar avatar-60 mr-3 has-background rounded">
                     
                        <a href="product-details.php?id=<?php echo $row["id"]; ?>" class="background">
                             <?php if($row["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                            <img src="img/image9.jpg" class="" alt="">
                        </a>
                    </div>
                    
                    <div class="media-body">
                        <small class="text-secondary"><?php echo $row11['Name']; ?></small>
                        <a href="product-details.php?id=<?php echo $row["id"]; ?>">
                            <p class="mb-1"><?php echo $row["ProductName"]; ?></p>
                        </a>
                        <p><span class="text-success">&#8377;<?php echo number_format($row["Price"],2); ?></span>
                      <?php  if($SizeId=='0' && $RamId=='0' && $StorageId=='0' && $ColorId=='0'){} else{
                         if($ColorId!='0'){?>
                           <span class="text-secondary small">Color: <?php echo getAttrDetails($ColorId); ?></span>
                         <?php } if($SizeId!='0'){?>
                         <span class="text-secondary small">Hrs: <?php echo getAttrDetails($SizeId); ?></span>
                          <?php } if($StorageId!='0'){?>
                          <span class="text-secondary small">Storage: <?php echo getAttrDetails($StorageId); ?></span>
                           <?php } if($RamId!='0'){?>
                            <span class="text-secondary small">Ram: <?php echo getAttrDetails($RamId); ?></span>
                          <?php } } ?>
                       </p>
                    </div>
                    <div class="align-self-center">
                       <!-- <div class="input-group cart-count">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeMinus(<?php echo $row["id"]; ?>);">-</button>
                            </div>
                            <input type="text" class="form-control" placeholder="1" value="<?php echo $row['Quantity']; ?>"id="qntno<?php echo $row["id"]; ?>" min="1" readonly="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="changePlus(<?php echo $row["id"]; ?>);">+</button>
                            </div>
                            
                        </div>-->
                        
                        
                    </div>
                </div>
            <?php }} ?>
                
            </div>
           
            <div class="container mb-4">
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Sub total</div>
                    <div class="col text-right text-mute" id="subtotal">&#8377;<?php echo number_format($GrandTotal,2); ?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Shipping Charges</div>
                    <div class="col text-right text-mute" id="shipping_price">&#8377;<?php echo number_format($ShippingCharge,2); ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal" style="color: red;">
                    <div class="col"><?php echo $AccName;?> Discount (<?php echo $Disc_Percentage."%";?>)</div>
                    <div class="col text-right text-mute" id="discount">&#8377;<?php echo number_format($Discount,2); ?></div>
                </div>
                <hr>
                <div class="row h6 font-weight-bold">
                    <div class="col">Net Payable</div>
                    <div class="col text-right text-mute" id="grand_total">&#8377;<?php echo number_format($GrandTotal+$ShippingCharge-$Promoprice-$Discount,2); ?></div>
                </div>
                 </div>


          
        </div><br>
       
        <div class="card mb-3">
                    <div class="card-body position-relative">
                       <h6 class="mb-1 text-default" style="text-align: center;">Shipping Details</h6><br>
                       <div class="table-responsive">
                                        <table class="table table-bordered ps-table ps-table--specification">
                                            <tbody>
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <td><?php echo $row21['Fname']." ".$row21['Lname']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Contact No</th>
                                                    <td><?php echo $row21['Phone']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Email Id</th>
                                                    <td><?php echo $row21['EmailId']; ?></td>
                                                </tr>
                                              <tr>
                                                    <th>Country</th>
                                                    <td><?php echo $row21['Country']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>State</th>
                                                    <td><?php echo $row21['State']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>City</th>
                                                    <td><?php echo $row21['City']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td><?php echo $row21['Address']." - ".$row21['Pincode']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                    </div>
                </div>


  <!--  <div class="card mb-3">
       <div class="card-body position-relative">
           <h6 class="mb-1 text-default" style="text-align: center;">Contact Lense Details</h6><br>
              <div class="table-responsive">
                   <table class="table table-bordered ps-table ps-table--specification">
                    <tbody>
                    <?php if($row21['RightSph']!='') {?>
                      <tr>
                        <th>Right SPH.</th>
                        <td><?php echo $row21['RightSph']; ?></td>
                     </tr>
                 <?php } if($row21['RightCyl']!='') {?>
                     <tr>
                        <th>Right CYL.</th>
                        <td><?php echo $row21['RightCyl']; ?></td>
                    </tr>
                   <?php } if($row21['RightAxis']!='') {?>  
                    <tr>
                        <th>Right Axis</th>
                        <td><?php echo $row21['RightAxis']; ?></td>
                    </tr>
                    <?php } if($row21['LeftSph']!='') {?>  
                     <tr>
                        <th>Left SPH.</th>
                        <td><?php echo $row21['LeftSph']; ?></td>
                     </tr>
                    <?php } if($row21['LeftCyl']!='') {?> 
                     <tr>
                        <th>Left CYL.</th>
                        <td><?php echo $row21['LeftCyl']; ?></td>
                    </tr>
                    <?php } if($row21['LeftAxis']!='') {?> 
                    <tr>
                        <th>Left Axis</th>
                        <td><?php echo $row21['LeftAxis']; ?></td>
                    </tr>
                    <?php } if($row21['File']=='') {}
                    else if(file_exists("../uploads/".$row21['File'])){?> 
                     <tr>
                        <th>Attach File</th>
                        <td><a href="../uploads/<?php echo $row21['File']; ?>" target="_new">View</a></td>
                    </tr>
                <?php } ?>
                                         
                                            </tbody>
                                        </table>
                                    </div>
                    </div>
                </div>    -->          
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

   
</body>

</html>
