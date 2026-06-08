<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
require_once "../database.php";
//require_once "../../exe-database.php";
//require_once "../../admin-database.php";
$SiteUrl = "https://dailydoorservices.com/mobapp";
$AdminSiteUrl = "https://dailydoorservices.com/adminapp";
//include_once '../class.phpmailer.php';
//include_once '../class.smtp.php';
$db_handle = new DBController();
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row110 = getRecord($sql110);
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = 0;
$CustLocation = 1;
/*$sql55 = "SELECT * FROM tbl_offer_percentage WHERE AccountId='7'";
$row55 = getRecord($sql55);
$DiscPer = $row55['Percentage'];*/
$UserEmail = $_SESSION['User']['EmailId'];
if($_POST['action'] == 'shop_cart'){
 if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT * FROM products WHERE code='" . $_POST["code"] . "'");
      //$price = $productByCode[0]["MinPrice"];
      $price = $_POST["price"];
      $qty = $_POST["quantity"];
      $total_price = $price * $qty;
      
 
      $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["ProductName"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$_POST["price"],'totalprice'=>$total_price,'sizeid'=>$_POST['sizeid'],'ramid'=>$_POST['ramid'],'storageid'=>$_POST['storageid'],'colorid'=>$_POST['colorid'],'Type'=>'Cart'));
      if(!empty($_SESSION["cart_item"])) {
        if(in_array($productByCode[0]["code"],$_SESSION["cart_item"])) {
          foreach($_SESSION["cart_item"] as $k => $v) {
              if($productByCode[0]["code"] == $k)
                $_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
          }
        } else {
          $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
        }
      } else {
        $_SESSION["cart_item"] = $itemArray;
      }
    }

    echo "Product Added";
}

if($_POST['action'] == 'sub_shop_cart'){
 if(!empty($_POST["quantity"])) {
    $Repeat = $_REQUEST['Repeat'];
    if($Repeat == 'daily'){
    $Daily = implode(",",$_REQUEST['Daily']);
    $WeekDays = '';
    $Weekends = '';  
    }
    else if($Repeat == 'weekdays'){
    $Daily = '';
    $WeekDays = implode(",",$_REQUEST['WeekDays']);
    $Weekends = '';
    }
    else{
    $Daily = '';
    $WeekDays = '';
    $Weekends = implode(",",$_REQUEST['Weekends']);  
    }
    
    $Recharge = $_REQUEST['Recharge'];
    $StartDate = $_REQUEST['StartDate'];
    $pid = $_REQUEST['pid'];
    $sizeid = $_REQUEST['sizeid'];
    $ramid = $_REQUEST['ramid'];
    $storageid = $_REQUEST['storageid'];
    $code = $_REQUEST['code'];
    
    $productByCode = $db_handle->runQuery("SELECT * FROM products WHERE code='" . $_POST["code"] . "'");
      //$price = $productByCode[0]["MinPrice"];
      //$price = $_POST["price"];
     $price = $_REQUEST['prd_price'];
      $qty = $_POST["quantity"];
      $total_price = $price * $qty * $Recharge;
      
 
     $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["ProductName"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$price,'totalprice'=>$total_price,'sizeid'=>$_POST['sizeid'],'Repeat'=>$_POST['Repeat'],'Daily'=>$Daily,'WeekDays'=>$WeekDays,'Weekends'=>$Weekends,'Recharge'=>$_POST['Recharge'],'StartDate'=>$_POST['StartDate'],'Type'=>'Subscription'));
      if(!empty($_SESSION["cart_item2"])) {
        if(in_array($productByCode[0]["code"],$_SESSION["cart_item2"])) {
          foreach($_SESSION["cart_item2"] as $k => $v) {
              if($productByCode[0]["code"] == $k)
                $_SESSION["cart_item2"][$k]["quantity"] = $_POST["quantity"];
          }
        } else {
          $_SESSION["cart_item2"] = array_merge($_SESSION["cart_item2"],$itemArray);
        }
      } else {
        $_SESSION["cart_item2"] = $itemArray;
      }
    }

    echo "Product Added";
}

if($_POST['action'] == 'shop_cart2'){
  $pid = $_POST['pid'];
   $quantity = $_POST['quantity'];
  $code = $_POST['code'];
   $price = $_POST['price'];
  foreach($_SESSION["cart_item"] as &$value){
        if($value['code'] === $_POST["code"]){
            $value['quantity'] = $_POST["quantity"];
             $value['price'] = $_POST["price"];
            $value['totalprice'] = $value['quantity'] * $value['price'];
            break; // Stop the loop after we've found the product
        }
    }
    foreach ($_SESSION["cart_item"] as $product){
        $Type = $product['Type'];
        $Recharge = $product['Recharge'];
         if($Type == 'Subscription'){
            $total_price4_1 += ($product["price"]*$product["quantity"]*$Recharge);
         }
         else{
        $total_price4_2 += ($product["price"]*$product["quantity"]);
        }
        //$total_price4 = ($total_price3 - ($total_price3)*0.18);
        }

            foreach ($_SESSION["cart_item2"] as $product){
        $Type = $product['Type'];
        $Recharge = $product['Recharge'];
         if($Type == 'Subscription'){
            $total_price4_1 += ($product["price"]*$product["quantity"]*$Recharge);
         }
         else{
        $total_price4_2 += ($product["price"]*$product["quantity"]);
        }
        //$total_price4 = ($total_price3 - ($total_price3)*0.18);
        }
$total_price4 = $total_price4_1 + $total_price4_2;

$sql22 = "SELECT * FROM tbl_service_fee";
$res22 = $conn->query($sql22);
$rncnt22 = mysqli_num_rows($res22);
$row22 = $res22->fetch_assoc();
$OrderPrice = $row22['OrderPrice']; 
if($total_price4 <= $OrderPrice){   
                    if($PackageStatus==1){
                        $ShippingPrice = '0.00';
                    }
                    else{
                   $ShippingPrice = $row22['Fee']; 
                    }
                } 
                else{ 
                  $ShippingPrice = '0.00';
               } 
$netamt = $total_price4+$ShippingPrice;
//$disc = $netamt*($DiscPer/100);
//$totnetamt =  $netamt-$disc;
 if($Member == 0){
                  /*if($netamt >= 1200){
                    $totnetamt =  $total_price4+$ShippingPrice-700;
                    $disc = 700;
                    $per_disc = "";
                  }
                  else{
                    $totnetamt = $total_price4+$ShippingPrice;  
                    $per_disc = "";
                  }*/
                   $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }
                else{
                    $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }                
$sub_total =  number_format($total_price4,2);
$total_price =  number_format($netamt,2);
$disc_price = number_format($disc,2);

        //$total_price =  number_format($total_price4,2);
        $product_price = $price * $quantity;
        $product_price2 = number_format($product_price,2);
 echo json_encode(array('Price' => $product_price,'Price2' => $product_price2,'total_price'=>$total_price,'ShippingPrice'=>$ShippingPrice,'sub_total'=>$sub_total,'discount'=>$disc_price,'discountwo'=>$disc));
}


if($_POST['action'] == 'delete_shop_prod'){
    $output = "";
    if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_POST["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                        if(empty($_SESSION["cart_item"]))
                            unset($_SESSION["cart_item"]);
                           
                }
            } 

    if(!empty($_SESSION["cart_item2"])) {
                foreach($_SESSION["cart_item2"] as $k => $v) {
                    if($_POST["code"] == $k)
                        unset($_SESSION["cart_item2"][$k]);
                        if(empty($_SESSION["cart_item2"]))
                            unset($_SESSION["cart_item2"]);
                           
                }
            }         
foreach ($_SESSION["cart_item"] as $product){
    $Type = $product['Type'];
        $Recharge = $product['Recharge'];
         if($Type == 'Subscription'){
    $total_price4_1 += ($product["price"]*$product["quantity"]*$Recharge);
    }
    else{
     $total_price4_2 += ($product["price"]*$product["quantity"]);   
        }
    }

    foreach ($_SESSION["cart_item2"] as $product){
    $Type = $product['Type'];
        $Recharge = $product['Recharge'];
         if($Type == 'Subscription'){
    $total_price4_1 += ($product["price"]*$product["quantity"]*$Recharge);
    }
    else{
     $total_price4_2 += ($product["price"]*$product["quantity"]);   
        }
    }
        
$sql22 = "SELECT * FROM tbl_service_fee";
$res22 = $conn->query($sql22);
$rncnt22 = mysqli_num_rows($res22);
$row22 = $res22->fetch_assoc();
$OrderPrice = $row22['OrderPrice']; 
if($total_price4 <= $OrderPrice){   
                    if($PackageStatus==1){
                        $ShippingPrice = '0.00';
                    }
                    else{
                   $ShippingPrice = $row22['Fee']; 
                    }
                } 
                else{ 
                  $ShippingPrice = '0.00';
               } 
//$sub_total =  number_format($total_price4,2);
//$total_price =  number_format($total_price4+$ShippingPrice,2);
$netamt = $total_price4+$ShippingPrice;
//$disc = $netamt*($DiscPer/100);
//$totnetamt =  $netamt-$disc;
 if($Member == 0){
                  /*if($netamt >= 1200){
                    $totnetamt =  $total_price4+$ShippingPrice-700;
                    $disc = 700;
                    $per_disc = "";
                  }
                  else{
                    $totnetamt = $total_price4+$ShippingPrice;  
                    $per_disc = "";
                  }*/
                  $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }
                else{
                    $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }                        
$sub_total =  number_format($total_price4,2);
$total_price =  number_format($netamt,2);
$disc_price = number_format($disc,2);
  echo json_encode(array('cnt_val' => count($_SESSION["cart_item"]),'total_price'=>$total_price,'ShippingPrice'=>$ShippingPrice,'sub_total'=>$sub_total,'discount'=>$disc_price,'discountwo'=>$disc));
 }  


 if($_POST['action'] == 'show_cart'){
 if(isset($_SESSION["cart_item"])){
    $total_price = 0;
       foreach ($_SESSION["cart_item"] as $product){
        if($product['Type'] == 'Cart'){
       $Prod_code = $product["code"];
      $SizeId = $product['sizeid'];
      $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
      $res5 = $conn->query($sql5);
      $row5 = $res5->fetch_assoc();
      $total_price2 = ($product["price"]*$product["quantity"]);
      $total_price3 += ($product["price"]*$product["quantity"]);
      $total_price =  number_format($total_price3,2);
      if($row5['Name'] == ''){
          $attr_name = "";
      }
      else{
        $attr_name = " (".$row5['Name'].")";
      }
      $SubCatId = $row['SubCatId'];
      $sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
      $row11 = getRecord($sql11);
      ?> 
                <div class="media mb-4 w-100">
                    <div class="avatar avatar-60 mr-3 has-background rounded">
                     
                        <a href="product-details.php" class="background">
                             <?php if($row["Photo"] == '') {?>
                  <img src="../no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="../no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                            <img src="img/image9.jpg" class="" alt="">
                        </a>
                    </div>
                     <input type="hidden" id="price<?php echo $row["id"]; ?>" value="<?php echo $product["price"]; ?>">
                     <input type="hidden" id="code<?php echo $row["id"]; ?>" value="<?php echo $product["code"]; ?>">
                    <div class="media-body">
                        <small class="text-secondary"><?php echo $row11['Name']; ?></small>
                        <a href="product.html">
                            <p class="mb-1"><?php echo $row["ProductName"]; ?></p>
                        </a>
                        <p><span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small">Size: <?php echo $attr_name; ?></span><?php } ?></p>
                    </div>
                    <div class="align-self-center">
                        <div class="input-group cart-count">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeMinus(<?php echo $row["id"]; ?>);">-</button>
                            </div>
                            <input type="text" class="form-control" placeholder="1" value="<?php echo $product["quantity"]; ?>"id="qntno<?php echo $row["id"]; ?>" min="1" readonly="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="changePlus(<?php echo $row["id"]; ?>);">+</button>
                            </div>
                            
                        </div>
                        <a href="javascript:void(0)" id="<?php echo $product["code"]; ?>" onclick="delete_prod2(this.id)"><i class="fa fa-trash" style="color: red;padding-top: 7px;padding-left: 28px;"></i></a>
                        
                    </div>
                </div>
<?php } } } }

 if($_POST['action'] == 'sub_show_cart'){
if(isset($_SESSION["cart_item2"])){
    $total_price = 0;
       foreach ($_SESSION["cart_item2"] as $product){
        if($product['Type'] == 'Subscription'){
       $Prod_code = $product["code"];
      $SizeId = $product['sizeid'];
      $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
    $result = $conn->query($sql);
   $row = $result->fetch_assoc();
    $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
     $res5 = $conn->query($sql5);
$row5 = $res5->fetch_assoc();
  $total_price2 = ($product["price"]*$product["quantity"]*$product["Recharge"]);
  $total_price3 += ($product["price"]*$product["quantity"]*$product["Recharge"]);
 $total_price =  number_format($total_price3,2);
  if($row5['Name'] == ''){
     $attr_name = "";
   }
else{
  $attr_name = " (".$row5['Name'].")";
}
 $SubCatId = $row['SubCatId'];
$sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
$row11 = getRecord($sql11);

      ?> 
                <div class="media mb-4 w-100">
                    <div class="avatar avatar-60 mr-3 has-background rounded">
                     
                        <a href="product-details.php" class="background">
                             <?php if($row["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src=".././uploads/<?php echo $row["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                            <img src="img/image9.jpg" class="" alt="">
                        </a>
                    </div>
                     <input type="hidden" id="price<?php echo $row["id"]; ?>" value="<?php echo $product["price"]; ?>">
                     <input type="hidden" id="code<?php echo $row["id"]; ?>" value="<?php echo $product["code"]; ?>">
                    <div class="media-body">
                        <small class="text-secondary"><?php echo $row11['Name']; ?></small>
                        <a href="#">
                            <p class="mb-1"><?php echo $row["ProductName"]; ?></p>

                        </a>
                        <p><span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small">Size: <?php echo $attr_name; ?></span><?php } ?><br>
                          <span class="text-secondary small"><?php echo $product["quantity"]; ?> Pkt - <?php echo $product['Repeat']; ?> (<?php echo $product['Recharge']; ?> Days)</span>
                     </p>

                    </div>
                    <div class="align-self-center">
                       <a href="subscribe-product.php?id=<?php echo $row["id"]; ?>"><i class="fa fa-edit"></i> Edit Settings</a><br>
                        <a href="javascript:void(0)" id="<?php echo $product["code"]; ?>" onclick="delete_prod2(this.id)"><i class="fa fa-trash" style="color: red;padding-top: 7px;padding-left: 28px;"></i></a>
                        
                    </div>
                </div>
<?php } } }} 

if($_POST['action'] == 'OrderSummary'){
  //$Product_Count = count($_SESSION['cart_item']);
  $Fname = addslashes(trim($_POST['Fname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
if($_POST['Password'] == ''){
$Password = rand(1000,9999);
}
else{
  $Password = $_POST['Password'];
}
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$AreaId = addslashes($_POST['AreaId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$shipdiff = $_POST['shipdiff'];
$PaymentMethod = $_POST['PaymentMethod'];
$ShippingAddress = $_POST['ShippingAddress'];
$ShippingCharge = $_POST['ShippingCharge'];
$Promoprice = $_POST['promo_price'];
$Promocode = $_POST['Promocode'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$OrderDate = date('Y-m-d');
$OrderTime = date('h:i a');
$Roll = $_POST['Roll'];  
$UnderBy = $_POST['UnderUserId'];
if($Roll == 4){
$AccName = "Doctor";
$Code = "DOC000";
}
if($Roll == 5){
$AccName = "Opticians";
$Code = "OPT000";
}
if($Roll == 6){
$AccName = "Wholesalers";
$Code = "WHOL000";
}
if($Roll == 7){
$AccName = "Customers";
$Code = "CUST000";
}
if($Roll == 8){
$AccName = "Retailer";
$Code = "VED000";
}   

function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 12;
$ReferenceNo = RandomStringGenerator($n); 
$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone'";
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
    echo json_encode(array('msg'=>"Your Phone No Already Registered With Us",'status'=>'0'));
}
else{
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='1',Roll='$Roll',CreatedDate='$OrderDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "V000".$CustId;
$to = $EmailId;
//include("../inc_register_mail.php");
//include("../sendmailsmtp.php");
    $sql3 = "INSERT INTO customer_address SET UserId='$CustId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$OrderDate'";
$conn->query($sql3);
$addid = mysqli_insert_id($conn);
$sql31 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'";
$conn->query($sql31);

$query = "SELECT * FROM tbl_users WHERE Phone = '$Phone' AND Password = '$Password'";
    $result = $conn->query($query);
    $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $_SESSION['User'] = $row;
    $Username = $EmailId;
     echo json_encode(array('msg'=>"Registration Successfull!Redirecting...",'status'=>'1','userid'=>$CustId,'Username'=>$Username,'addid'=>$addid));  
}
}

if($_POST['action'] == 'PlaceOrder'){
$PaymentMethod = $_POST['PaymentMethod'];
$user_id = $_POST['user_id'];
$UserId = $_POST['user_id'];

/*$sql110 = "SELECT Location FROM tbl_users WHERE id='$user_id'";
$row110 = getRecord($sql110);
$CustLocation = $row110['Location'];*/

$addid = $_POST['addid'];
$ShippingCharge = $_POST['ShippingCharge'];
$GrandTotal = $_POST['GrandTotal'];
$DeliveryMethod = $_POST['DeliveryMethod'];
//$Discount = $_POST['Discount'];
$RightSph = addslashes(trim($_POST['RightSph']));
$RightCyl = addslashes(trim($_POST['RightCyl']));
$RightAxis = addslashes(trim($_POST['RightAxis']));
$LeftSph = addslashes(trim($_POST['LeftSph']));
$LeftCyl = addslashes(trim($_POST['LeftCyl']));
$LeftAxis = addslashes(trim($_POST['LeftAxis']));
$File = addslashes(trim($_POST['File']));
//$Discount = addslashes(trim($_POST['CouponAmt']));
$Discount = 0;
$Points = addslashes(trim($_POST['Points']));
$CouponCode = addslashes(trim($_POST['Coupon_Code']));
$ServiceFee = addslashes(trim($_POST['ServiceFee']));
$SevenDaysFree = addslashes(trim($_POST['SevenDaysFree']));
$OrderDate = date('Y-m-d');
$OrderTime = date('h:i a');

/*$sql = "SELECT ta.VedId FROM customer_address ca LEFT JOIN tbl_allocate_areas ta ON ta.AreaId=ca.AreaId WHERE ca.id='$addid'";
$row = getRecord($sql);
$VedId = $row['VedId'];*/

if($PaymentMethod == '1' || $PaymentMethod == '3'){
    $i=0;
    $i2=0;
 foreach ($_SESSION["cart_item"] as $product){
     if($product['Type'] == 'Cart'){$i++;}
 }
  foreach ($_SESSION["cart_item2"] as $product){
     if($product['Type'] == 'Subscription'){$i2++;}
 }
 //echo $i;
 if($i>0){   
 include 'incgeneralcart.php';
 }
 if($i2>0){   
 include 'incsubscriptioncart.php';
 }

if($Discount <= $total_price3){
    $sql2 = "UPDATE orders SET Discount='$Discount',CouponCode='$CouponCode' WHERE id='$oid'";
    $conn->query($sql2);
}
else if($Discount <= $total_price4){
    $sql2 = "UPDATE orders SET Discount='$Discount',CouponCode='$CouponCode' WHERE id='$oid2'";
    $conn->query($sql2);
}

$sql2 = "UPDATE tbl_users SET Member='1' WHERE id='$user_id'";
$conn->query($sql2);
if(isset($_SESSION['CouponCode'])){
$sql_11 = "INSERT INTO tbl_applied_code SET Oid='$oid',UserId='$user_id',Code='$CouponCode',Amount='$Discount',CreatedDate='$OrderDate'";
$conn->query($sql_11);
/*$sql_12 = "INSERT INTO tbl_points SET Oid='$oid',UserId='$user_id',Code='$CouponCode',Points='$Points',Status='Cr',CreatedDate='$OrderDate'";
$conn->query($sql_12);*/

}

//$to = $UserEmail;


$TotAmt = $total_price3 - $Discount - $Promoprice;
//include '../incomissioncal.php';

/*$sqlc_11 = "SELECT Tokens FROM tbl_users WHERE id='1'";
$data11=mysqli_query($con,$sqlc_11);
while($rowc_11=mysqli_fetch_array($data11))
{
$title = "New Order Placed By ".$Fname." ".$Lname;
$body =  "Order No :".$OrderNo;
$reg_id = $rowc_11[0];
$registrationIds = array($reg_id);
include '../../incnotification.php';
}*/

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

//include("../incordercontent.php");
//include("../sendmailsmtp.php");
if(isset($_SESSION['prime'])){
$sql = "SELECT * FROM tbl_packages WHERE id='1'";
    $row = getRecord($sql);
    $Duration = $row['Duration'];
    $PkgName = $row['Name'];
    $TxnAmount = $_SESSION['prime'];
    if($row['Period'] == '1'){
      $Period = "month";
    }
    else{
      $Period = "years";
    }
$PkgDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$valid_period = "+".$Duration." ".$Period;
$PkgExp = date('Y-m-d', strtotime($valid_period));
$sql = "UPDATE tbl_users SET PackageId='1',PkgAmount='$TxnAmount',PkgDate='$PkgDate',Validity='$PkgExp',PackageStatus='1',ModifiedDate='$PkgDate' WHERE id='$user_id'";
      $conn->query($sql);
 $Narration = "Wallet Amount Used For Prime Member";
$sql = "INSERT INTO wallet SET UserId='$user_id',Oid='$oid',Amount='$TxnAmount',Status='Dr',CreatedDate='$PkgDate',CreatedTime='$CreatedTime',Narration='$Narration'";
$conn->query($sql);     
      }

$sql11 = "SELECT Phone,Fname FROM tbl_users WHERE id='$user_id'";
$row11 = getRecord($sql11);
$Phone = $row11['Phone'];
$Fname = $row11['Fname'];
//include "incsmsfile.php";
unset($_SESSION['prime']);
unset($_SESSION['cart_item']);
unset($_SESSION['cart_item2']);
unset($_SESSION['pincode']);
unset($_SESSION['Promocode']);
unset($_SESSION['Promoprice']);

unset($_SESSION['RightSph']);
unset($_SESSION['RightCyl']);
unset($_SESSION['RightAxis']);
unset($_SESSION['LeftSph']);
unset($_SESSION['LeftCyl']);
unset($_SESSION['LeftAxis']);
unset($_SESSION['File']);
unset($_SESSION['CouponCode']);
unset($_SESSION['CouponAmt']);
echo json_encode(array('status'=>1,'email'=>$email,'msg'=>"Order Placed Successfully!"));
}
else{
 $sql22 = "SELECT * FROM customer_address WHERE id='$addid'";
  $res22 = $conn->query($sql22);
  $row22 = $res22->fetch_assoc();
  foreach ($_SESSION["cart_item"] as $product){
$total_price3 += ($product["price"]*$product["quantity"]);
  }
if($Promoprice == ''){
$netamt = $total_price3 + $ShippingCharge;
}
else{
  $netamt = $total_price3 + $ShippingCharge - $Promoprice;
}
$disc = $netamt*($DiscPer/100);
$amt =  $netamt-$disc;

  $name = $row22['Fname']." ".$row22['Lname'];
  $phone = $row22['Phone'];
  $email = $row22['EmailId'];
  
$sql = "INSERT INTO orders SET OrderNo='',Roll='$Roll',UserId='$user_id',AddressId='$addid',OrderTotal='0.00',DeliveryMethod='$DeliveryMethod',PaymentMethod='2',ShippingCharge='$ShippingCharge',ServiceFee='$ServiceFee',DiscPer='$DiscPer',Discount='$Discount',Promoprice='$Promoprice',Status='1',OrderProcess='2',OrderDate='$OrderDate',OrderTime='$OrderTime',RightSph='$RightSph',RightCyl='$RightCyl',RightAxis='$RightAxis',LeftSph='$LeftSph',LeftCyl='$LeftCyl',LeftAxis='$LeftAxis',File='$File',CouponCode='$CouponCode'";
      $conn->query($sql);
      $oid = mysqli_insert_id($conn);
      $OrderNo = "#" . rand(10000,999999)."".$oid;
      $sql2 = "UPDATE orders SET OrderNo='$OrderNo',srno='$oid' WHERE id='$oid'";
      $conn->query($sql2);  
  echo json_encode(array('status'=>2,'name'=>$name,'phone'=>$phone,'email'=>$email,'userid'=>$user_id,'addid'=>$addid,'amount'=>$amt,'ship_charge'=>$ShippingCharge,'promocode'=>$promocode,'promoprice'=>$promoprice,'oid'=>$oid,'OrderNo'=>$OrderNo));
  }
}

if($_POST['action'] == 'applyCoupon'){
$CurrDate = date('Y-m-d');    
$CouponCode = addslashes(trim($_POST['CouponCode']));
$GrandTotal = addslashes(trim($_POST['GrandTotal']));
$UserId = addslashes(trim($_POST['user_id']));
$sql = "SELECT * FROM tbl_coupon_code WHERE Code='$CouponCode'";
$rncnt = getRow($sql);
if($rncnt > 0){
    $row = getRecord($sql);
    $FromDate = $row['FromDate'];
    $ToDate = $row['ToDate'];
    $MinOrder = $row['MinOrder'];
    $Discount = $row['Discount'];
    //$CouponAmt = $GrandTotal * ($Discount/100);
     $CouponAmt = $row['Discount'];
    if($GrandTotal >= $MinOrder){
    $sql3 = "SELECT * FROM tbl_applied_code WHERE UserId='$UserId' AND Code='$CouponCode'";
    $rncnt3 = getRow($sql3);
    $sql2 = "SELECT * FROM tbl_coupon_code WHERE Code='$CouponCode' AND FromDate<='$CurrDate' AND ToDate>='$CurrDate'";
    $rncnt2 = getRow($sql2);
    if($rncnt3 > 0){
        unset($_SESSION['CouponCode']);
        unset($_SESSION['CouponAmt']);
        echo json_encode(array('Status'=>3));//Already Used
    }
    else if($rncnt2 > 0){
        foreach ($_SESSION["cart_item2"] as $product){
            $Prod_code = $product["code"];
             $sql = "SELECT id FROM products WHERE code = '$Prod_code'";
             $row = getRecord($sql);
             $ProductId = $row['id'];
             $sql2 = "SELECT * FROM tbl_dds_offers WHERE ProdId = '$ProductId'";
             $rncnt2 = getRow($sql2);
             if($rncnt2 > 0){
                $_SESSION['CouponCode'] = $CouponCode;
        $_SESSION['CouponAmt'] = $CouponAmt;
        echo json_encode(array('CouponAmt'=>$CouponAmt,'Status'=>1));//Applied
             }
             else{
                unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
    echo json_encode(array('MinOrder'=>$MinOrder,'Status'=>5));//Coupon Code Not Applied For This Products
             }

        }
        
    }
    else{
        unset($_SESSION['CouponCode']);
        unset($_SESSION['CouponAmt']);
        echo json_encode(array('Status'=>2));//Coupon Expired
    }
}
else{
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
    echo json_encode(array('MinOrder'=>$MinOrder,'Status'=>4));//Min Order
}
}
else{
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
    echo json_encode(array('Status'=>0));//Invalid Coupon
}
}

if($_POST['action'] == 'removeCoupon'){
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
}

if($_POST['action'] == 'checkWallBal'){
$user_id = $_POST['user_id'];
$sql11x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
$res11x = $conn->query($sql11x);
$row11x = $res11x->fetch_assoc();
$mybalance = $row11x['credit'] - $row11x['debit'];
echo $mybalance;
}    
?>

