<?php
   //general order
 $sql = "INSERT INTO orders SET OrderNo='',Roll='$Roll',UserId='$user_id',AddressId='$addid',OrderTotal='0.00',DeliveryMethod='$DeliveryMethod',PaymentMethod='$PaymentMethod',ShippingCharge='$ShippingCharge',DiscPer='$DiscPer',Promoprice='$Promoprice',Status='1',OrderProcess='2',OrderDate='$OrderDate',OrderTime='$OrderTime',RightSph='$RightSph',RightCyl='$RightCyl',RightAxis='$RightAxis',LeftSph='$LeftSph',LeftCyl='$LeftCyl',LeftAxis='$LeftAxis',File='$File',Type='Subscription',VedId='$VedId',ConfirmDate='$OrderDate',ConfirmTime='$OrderTime',ServiceFee='$ServiceFee',SevenDaysFree='$SevenDaysFree',CityId='$CustLocation'";
      $conn->query($sql);
      $oid2 = mysqli_insert_id($conn);
      $OrderNo2 = "#" . rand(10000,999999)."".$oid2;
      $sql2 = "UPDATE orders SET OrderNo='$OrderNo2',srno='$oid2' WHERE id='$oid2'";
      $conn->query($sql2);
    
foreach ($_SESSION["cart_item2"] as $product){
      $Prod_code = $product["code"];
      $SizeId = $product["sizeid"];
      $RamId = $product["ramid"];
      $StorageId = $product["storageid"];
      $ColorId = $product["colorid"];
      $Price = $product['totalprice'];
      $Quantity = $product['quantity'];

      $Repeats = $product['Repeat'];
      $Daily = $product['Daily'];
      $WeekDays = $product['WeekDays'];
      $Weekends = $product['Weekends'];
      $Recharge = $product['Recharge'];
      $StartDate = $product['StartDate'];
      $Type = $product['Type'];

     

      $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $ProductId = $row['id'];
      //$VedId = $row['VedId'];
      if($Type == 'Subscription'){
        include 'incsubdate.php';
       $total_price3_11 += ($product["price"]*$product["quantity"]*$Recharge);        
      }
      else{
      $total_price3_22 += ($product["price"]*$product["quantity"]);
      }
      
        
    $sql = "INSERT INTO order_details SET OrderId='$oid2',OrderNo='$OrderNo2',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',SizeId='$SizeId',RamId='$RamId',StorageId='$StorageId',ColorId='$ColorId',Quantity='$Quantity',Price='$Price',OrderDate='$OrderDate',Type='$Type',Repeats='$Repeats',Daily='$Daily',WeekDays='$WeekDays',Weekends='$Weekends',Recharge='$Recharge',StartDate='$StartDate',VedId='$VedId',CityId='$CustLocation'";
      $conn->query($sql);

   
}

$total_price4 = $total_price3_11 + $total_price3_22;
$sql2 = "UPDATE orders SET OrderTotal='$total_price4' WHERE id='$oid2'";
$conn->query($sql2);

$sql_22 = "SELECT * FROM tbl_free_subscribe WHERE UserId='$user_id'";
$rncnt_22 = getRow($sql_22);
if($rncnt_22 > 0){}
else{
$sql33 = "INSERT INTO tbl_free_subscribe SET UserId='$user_id',OrderId='$oid2',OrderNo='$OrderNo2',CreatedDate='$OrderDate',CreatedTime='$OrderTime'";
$conn->query($sql33);
}

if($PaymentMethod == '3'){
$Narration2 = "Wallet Amount Used For Order No : ".$OrderNo2;
$sql = "INSERT INTO wallet SET UserId='$user_id',Oid='$oid2',Amount='$total_price4',Status='Dr',CreatedDate='$OrderDate',CreatedTime='$OrderTime',Narration='$Narration2'";
$conn->query($sql);
}
//customer notification
$sqlc11 = "SELECT Tokens,Fname,Lname,EmailId FROM customers WHERE id='$user_id'";
$data=mysqli_query($con,$sqlc11);
while($rowc11=mysqli_fetch_array($data))
{
$Fname = $rowc11['Fname'];
$Lname = $rowc11['Lname'];
$email = $rowc11['EmailId'];
$to = $rowc11['EmailId'];
$title = "New Order Placed.";
$body =  "Your Order No :".$OrderNo2;
$reg_id = $rowc11[0];
$registrationIds = array($reg_id);
$url = "$SiteUrl/my-sub-orders-details.php?oid=$oid";
include '../../incnotification.php';
}

//admin notification
$sqlc11_1 = "SELECT Tokens FROM customers WHERE Roll='1'";
$data_1=mysqli_query($con3,$sqlc11_1);
while($rowc11_1=mysqli_fetch_array($data_1))
{
$title = "New Order Placed.";
$body =  "Order No :".$OrderNo2;
$reg_id = $rowc11_1[0];
$registrationIds = array($reg_id);
$url = "$AdminSiteUrl/my-sub-orders-details.php?oid=$oid";
include '../../incnotification.php';
}
?>