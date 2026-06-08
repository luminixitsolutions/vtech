<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
include_once '../class.phpmailer.php';
include_once '../class.smtp.php';
$db_handle = new DBController();
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM customers WHERE id='$user_id'";
$row110 = getRecord($sql110);
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = $row110['Member'];
$sql55 = "SELECT * FROM tbl_offer_percentage WHERE AccountId='7'";
$row55 = getRecord($sql55);
$DiscPer = $row55['Percentage'];
$UserEmail = $_SESSION['User']['EmailId'];
if($_POST['action'] == 'shop_cart'){
 if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT * FROM products WHERE code='" . $_POST["code"] . "'");
      //$price = $productByCode[0]["MinPrice"];
      $price = $_POST["price"];
      $qty = $_POST["quantity"];
      $total_price = $price * $qty;
      
 
      $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["ProductName"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$_POST["price"],'totalprice'=>$total_price,'sizeid'=>$_POST['sizeid'],'ramid'=>$_POST['ramid'],'storageid'=>$_POST['storageid'],'colorid'=>$_POST['colorid']));
      if(!empty($_SESSION["hall_item"])) {
        if(in_array($productByCode[0]["code"],$_SESSION["hall_item"])) {
          foreach($_SESSION["hall_item"] as $k => $v) {
              if($productByCode[0]["code"] == $k)
                $_SESSION["hall_item"][$k]["quantity"] = $_POST["quantity"];
          }
        } else {
          $_SESSION["hall_item"] = array_merge($_SESSION["hall_item"],$itemArray);
        }
      } else {
        $_SESSION["hall_item"] = $itemArray;
      }
    }

    echo "Product Added";
}



if($_POST['action'] == 'PlaceOrder'){
  //$Product_Count = count($_SESSION['hall_item']);
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
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$shipdiff = $_POST['shipdiff'];
$PaymentMethod = $_POST['PaymentMethod'];
$ShippingAddress = $_POST['ShippingAddress'];
$ShippingCharge = $_POST['ShippingCharge'];
$Discount = $_POST['Discount'];
$GrandTotal = $_POST['GrandTotal'];
$UnderBy = $_POST['UnderBy'];
  $OrderDate = date('Y-m-d');
  $OrderTime = date('h:i a');

if($user_id == ''){}
else{
if($shipdiff == 'true'){
$sql3 = "INSERT INTO customer_address SET UserId='$user_id',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$OrderDate'";
$conn->query($sql3);
$addid = mysqli_insert_id($conn);
}
else{
  $addid = $_POST['addid'];
 
}
 $sql22 = "SELECT * FROM customer_address WHERE id='$addid'";
  $res22 = $conn->query($sql22);
  $row22 = $res22->fetch_assoc();
}
if($PaymentMethod == '3'){
  if($user_id == ''){
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
$sql2 = "SELECT * FROM customers WHERE Phone='$Phone' AND EmailId!='$EmailId'";
$sql21 = "SELECT * FROM customers WHERE EmailId='$EmailId' AND Phone!='$Phone'";
$sql3 = "SELECT * FROM customers WHERE Phone='$Phone' AND EmailId='$EmailId'";

$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);

$res21 = $conn->query($sql21);
$row21 = mysqli_num_rows($res21);

$res3 = $conn->query($sql3);
$row3 = mysqli_num_rows($res3);

if($row3 > 0){
  echo json_encode(array('msg'=>"Your Email Id & Phone No Already Registered With Us",'status'=>'3'));
}
else if($row21 > 0){
    echo json_encode(array('msg'=>"Your Email Id Already Registered With Us",'status'=>'4'));

} 
else if($row2 > 0){
    echo json_encode(array('msg'=>"Your Phone No Already Registered With Us",'status'=>'5'));
}
else{
$sql = "INSERT INTO customers SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',otp_verify='1',ReferenceNo='$ReferenceNo',Status='1',Roll='7',AccName='Customer',CreatedDate='$OrderDate',UnderBy='$UnderBy'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$to = $EmailId;
//include("../inc_register_mail.php");
//include("../sendmailsmtp.php");
$user_id = $CustId;
$UserId = $CustId;
$CustomerId = "CUST000".$CustId;
    $sql3 = "INSERT INTO customer_address SET UserId='$CustId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$OrderDate'";
$conn->query($sql3);
$addid = mysqli_insert_id($conn);
$sql31 = "UPDATE customers SET CustomerId='$CustomerId' WHERE id='$CustId'";
$conn->query($sql31);

$query = "SELECT * FROM customers WHERE Phone = '$Phone' AND Password = '$Password'";
    $result = $conn->query($query);
    $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $_SESSION['User'] = $row;
}
  }

if($user_id !=''){
  
  
  $sql = "INSERT INTO orders SET OrderNo='',Roll='$Roll',UserId='$user_id',AddressId='$addid',OrderTotal='0.00',DeliveryMethod='$DeliveryMethod',PaymentMethod='$PaymentMethod',ShippingCharge='$ShippingCharge',DiscPer='25',Discount='$Discount',Promoprice='$Promoprice',Status='1',OrderProcess='2',OrderDate='$OrderDate',OrderTime='$OrderTime',RightSph='$RightSph',RightCyl='$RightCyl',RightAxis='$RightAxis',LeftSph='$LeftSph',LeftCyl='$LeftCyl',LeftAxis='$LeftAxis',File='$File',Type=2,DelieverDate='$OrderDate',DelieverTime='$OrderTime'";
      $conn->query($sql);
      $oid = mysqli_insert_id($conn);

      $sql32 = "SELECT (case when max(srno) is null then 1 else max((srno)+1) end ) as mxsrno FROM orders";
      $row32 = getRecord($sql32);
      $OrderNo = "#00".$oid;
      //$OrderNo = "#" . rand(10000,999999)."".$oid;
      $sql2 = "UPDATE orders SET OrderNo='$OrderNo',srno='$oid' WHERE id='$oid'";
      $conn->query($sql2);

foreach ($_SESSION["hall_item"] as $product){
      $Prod_code = $product["code"];
      $SizeId = $product["sizeid"];
      $RamId = $product["ramid"];
      $StorageId = $product["storageid"];
      $ColorId = $product["colorid"];
      $Price = $product['totalprice'];
      $Quantity = $product['quantity'];
      $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $ProductId = $row['id'];
      $total_price3 += ($product["price"]*$product["quantity"]);

      $sql = "INSERT INTO order_details SET OrderId='$oid',OrderNo='$OrderNo',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',SizeId='$SizeId',RamId='$RamId',StorageId='$StorageId',ColorId='$ColorId',Quantity='$Quantity',Price='$Price',OrderDate='$OrderDate'";
      $conn->query($sql);

}
$sql2 = "UPDATE orders SET OrderTotal='$total_price3' WHERE id='$oid'";
$conn->query($sql2);
$sql2 = "UPDATE customers SET Member='1' WHERE id='$user_id'";
$conn->query($sql2);
//$to = $UserEmail;
$sqlc11 = "SELECT Tokens,Fname,Lname,EmailId FROM customers WHERE id='$user_id'";
$data=mysqli_query($con,$sqlc11);
while($rowc11=mysqli_fetch_array($data))
{
//$rowc11 = getRecord($sqlc11);
$Fname = $rowc11['Fname'];
$Lname = $rowc11['Lname'];
$email = $rowc11['EmailId'];
$to = $rowc11['EmailId'];
$title = "New Order Placed.";
$body =  "Your Order No :".$OrderNo;
$reg_id = $rowc11[0];
$registrationIds = array($reg_id);
$url = "$SiteUrl/my-orders-details.php?oid=$oid";
include '../incnotification.php';
}
$UserId = $user_id;
$TotAmt = $total_price3 - $Discount - $Promoprice;
$Active = 0;
include '../inchallcomissioncal.php';

$sqlc_11 = "SELECT Tokens FROM customers WHERE id='1'";
$data11=mysqli_query($con,$sqlc_11);
while($rowc_11=mysqli_fetch_array($data11))
{
$title = "New Hall Booked By ".$Fname." ".$Lname;
$body =  "Order No :".$OrderNo;
$reg_id = $rowc_11[0];
$registrationIds = array($reg_id);
//$url = "$SiteUrl/my-orders-details.php?oid=$oid";
include '../incnotification.php';
}
$to = $UserEmail;
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
unset($_SESSION['hall_item']);
unset($_SESSION['pincode']);
echo json_encode(array('status'=>1,'msg'=>"Hall Booked Successfully!"));
}
}
else{
  if($user_id == ''){
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
$sql2 = "SELECT * FROM customers WHERE Phone='$Phone' AND EmailId!='$EmailId'";
$sql21 = "SELECT * FROM customers WHERE EmailId='$EmailId' AND Phone!='$Phone'";
$sql3 = "SELECT * FROM customers WHERE Phone='$Phone' AND EmailId='$EmailId'";

$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);

$res21 = $conn->query($sql21);
$row21 = mysqli_num_rows($res21);

$res3 = $conn->query($sql3);
$row3 = mysqli_num_rows($res3);

if($row3 > 0){
  echo json_encode(array('msg'=>"Your Email Id & Phone No Already Registered With Us",'status'=>'3'));
}
else if($row21 > 0){
    echo json_encode(array('msg'=>"Your Email Id Already Registered With Us",'status'=>'4'));

} 
else if($row2 > 0){
    echo json_encode(array('msg'=>"Your Phone No Already Registered With Us",'status'=>'5'));
}
else{
$sql = "INSERT INTO customers SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',otp_verify='1',ReferenceNo='$ReferenceNo',Status='1',Roll='7',AccName='Customer',CreatedDate='$OrderDate',UnderBy='$UnderBy'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "CUST000".$CustId;
$to = $EmailId;
include("../inc_register_mail.php");
include("../sendmailsmtp.php");
$user_id = $CustId;
    $sql3 = "INSERT INTO customer_address SET UserId='$CustId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$OrderDate'";
$conn->query($sql3);
$addid = mysqli_insert_id($conn);
$sql31 = "UPDATE customers SET CustomerId='$CustomerId' WHERE id='$CustId'";
$conn->query($sql31);

$query = "SELECT * FROM customers WHERE Phone = '$Phone' AND Password = '$Password'";
    $result = $conn->query($query);
    $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $_SESSION['User'] = $row;
}
  }
  if($user_id!=''){
    $sql22 = "SELECT * FROM customer_address WHERE id='$addid'";
  $res22 = $conn->query($sql22);
  $row22 = $res22->fetch_assoc();
  foreach ($_SESSION["hall_item"] as $product){
$total_price3 += ($product["price"]*$product["quantity"]);
  }
  $DiscPer = 25;
  $netamt = $total_price3 + $ShippingCharge;
 $disc = $netamt*($DiscPer/100);
$amt =  $netamt-$disc;

  $name = $row22['Fname']." ".$row22['Lname'];
  $phone = $row22['Phone'];
  $email = $row22['EmailId'];
  echo json_encode(array('status'=>2,'name'=>$name,'phone'=>$phone,'email'=>$email,'userid'=>$user_id,'addid'=>$addid,'amount'=>$amt,'ship_charge'=>$ShippingCharge,'discount'=>$disc));
}
}
}
?>