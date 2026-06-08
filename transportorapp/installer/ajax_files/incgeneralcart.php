<?php
   //general order
 $sql = "INSERT INTO orders SET OrderNo='',Roll='$Roll',UserId='$user_id',AddressId='$addid',OrderTotal='0.00',DeliveryMethod='$DeliveryMethod',PaymentMethod='$PaymentMethod',ShippingCharge='$ShippingCharge',DiscPer='$DiscPer',Promoprice='$Promoprice',Status='1',OrderProcess='2',OrderDate='$OrderDate',OrderTime='$OrderTime',RightSph='$RightSph',RightCyl='$RightCyl',RightAxis='$RightAxis',LeftSph='$LeftSph',LeftCyl='$LeftCyl',LeftAxis='$LeftAxis',File='$File',Type='Cart',VedId='$VedId',ConfirmDate='$OrderDate',ConfirmTime='$OrderTime',ServiceFee='$ServiceFee',CityId='$CustLocation'";
      $conn->query($sql);
      $oid = mysqli_insert_id($conn);
      $OrderNo = "#" . rand(10000,999999)."".$oid;
      $sql2 = "UPDATE orders SET OrderNo='$OrderNo',srno='$oid' WHERE id='$oid'";
      $conn->query($sql2);
    
foreach ($_SESSION["cart_item"] as $product){
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
        //include 'incsubdate.php';
       $total_price3_1 += ($product["price"]*$product["quantity"]*$Recharge);        
      }
      else{
      $total_price3_2 += ($product["price"]*$product["quantity"]);
      }
      
        
    $sql = "INSERT INTO order_details SET OrderId='$oid',OrderNo='$OrderNo',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',SizeId='$SizeId',RamId='$RamId',StorageId='$StorageId',ColorId='$ColorId',Quantity='$Quantity',Price='$Price',OrderDate='$OrderDate',Type='$Type',Repeats='$Repeats',Daily='$Daily',WeekDays='$WeekDays',Weekends='$Weekends',Recharge='$Recharge',StartDate='$StartDate',VedId='$VedId',CityId='$CustLocation'";
      $conn->query($sql);

   
}
$total_price3 = $total_price3_1 + $total_price3_2;
$sql2 = "UPDATE orders SET OrderTotal='$total_price3' WHERE id='$oid'";
$conn->query($sql2);

if($PaymentMethod == '3'){
$Narration = "Wallet Amount Used For Order No : ".$OrderNo;
$sql = "INSERT INTO wallet SET UserId='$user_id',Oid='$oid',Amount='$total_price3',Status='Dr',CreatedDate='$OrderDate',CreatedTime='$OrderTime',Narration='$Narration'";
$conn->query($sql);
}

?>