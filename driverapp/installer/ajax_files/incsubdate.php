<?php
if($product['Repeat'] == 'daily'){
    $product['Daily'] = explode(",",$product['Daily']);
    $StartDate = $product['StartDate'];
    $StartDay = date('D', strtotime($StartDate));
    //echo date('Y-m-d', strtotime('-1 day', strtotime('2015-08-10')));
     $cnt=0;
     $cart = array();
        foreach($product['Daily'] as $d){
            
            for($i=1;$i<100;$i++){
            $NextDate = date('Y-m-d', strtotime('+ '.$i.' day', strtotime($StartDate)));
            $NextDay = date('D', strtotime($NextDate));
            
            if($NextDay == $d){
               $cart[] = $NextDate;  
                //echo $NextDate."<br>";
            }
            }        
}
sort($cart);
$arrlength = count($cart);
for($x = 0; $x < $arrlength; $x++) {
    if($x <= $Recharge){
   $DeliveryDate = $cart[$x];
   $sql_22 = "INSERT INTO tbl_subscription_dates SET Oid='$oid2',OrdNo='$OrderNo2',SubDate='$DeliveryDate',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',Status=1,CreatedDate='$OrderDate'";
   $conn->query($sql_22);
  //echo "<br>";
}
}
}

   if($product['Repeat'] == 'weekdays'){
    $product['WeekDays'] = explode(",",$product['WeekDays']);
    $StartDate = $product['StartDate'];
    $StartDay = date('D', strtotime($StartDate));
     $cnt=0;
     $cart = array();
        foreach($product['WeekDays'] as $d){
            for($i=1;$i<100;$i++){
            $NextDate = date('Y-m-d', strtotime('+ '.$i.' day', strtotime($StartDate)));
            $NextDay = date('D', strtotime($NextDate));
            
            if($NextDay == $d){
               $cart[] = $NextDate;  
            }
           }
}
sort($cart);
$arrlength = count($cart);
for($x = 0; $x < $arrlength; $x++) {
    if($x <= $Recharge){
   $DeliveryDate = $cart[$x];
  //echo "<br>";
   $sql_22 = "INSERT INTO tbl_subscription_dates SET Oid='$oid2',OrdNo='$OrderNo2',SubDate='$DeliveryDate',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',Status=1,CreatedDate='$OrderDate'";
   $conn->query($sql_22);
}
}
}

   if($product['Repeat'] == 'weekends'){
    $product['Weekends'] = explode(",",$product['Weekends']);
    $StartDate = $product['StartDate'];
    $StartDay = date('D', strtotime($StartDate));
     $cnt=0;
     $cart = array();
        foreach($product['Weekends'] as $d){
            for($i=1;$i<350;$i++){
            $NextDate = date('Y-m-d', strtotime('+ '.$i.' day', strtotime($StartDate)));
            $NextDay = date('D', strtotime($NextDate));
            if($NextDay == $d){
               $cart[] = $NextDate;  
            }
        }
}
sort($cart);
$arrlength = count($cart);
for($x = 0; $x < $arrlength; $x++) {
    if($x <= $Recharge){
  $DeliveryDate = $cart[$x];
  $sql_22 = "INSERT INTO tbl_subscription_dates SET Oid='$oid2',OrdNo='$OrderNo2',SubDate='$DeliveryDate',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',Status=1,CreatedDate='$OrderDate'";
   $conn->query($sql_22);
}
}
}
?>
