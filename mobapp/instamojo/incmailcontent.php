<?php
$from = 'From: Multifix';
$subject = 'Order Placed Successfully From Multifix';
$body = 'Your order has been placed successfully and is being processed.<br><br>';
$body .= '<div align="center"><strong>Order Summary</strong></div> <br>';
$body .= 'Order No: '.$OrderNo.'<br>';
$body .= 'Order Placed on: '.$OrderDate.'<br>';
$body .= 'Net Payment:  &#8377;'.number_format($total_price3+$ShippingCharge,2).'<br>';
$body .= 'Order Status:  Confirmed <br><br>';
$body .= '<h3>Order Details :</h3><br><table style="border: 1px solid #ddd;text-align: left;border-collapse: collapse;width: 100%;">
  	<tr>
  	<th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Photo</th>
    <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Product</th>
    <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Quantity</th>
    <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Price</th>
  </tr>';
  $OrderId = $oid;
  $sql2 = "SELECT od.*,p.ProductName,p.Photo,p.CatId,p.id As pid FROM order_details od
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
 	$body .= '<tr>
 	<td style="border: 1px solid #ddd;text-align: left;padding: 15px;">
 	<img src="http://jigurumart.com/uploads/'.$row['Photo'].'" alt="" style="width: 50px;"></td>
       <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">
       <span>'.$row['ProductName'].'<span>';
		if($SizeId=='0' && $RamId=='0' && $StorageId=='0' && $ColorId=='0'){} else{
		$body .= '<br><br>
        <table style="border: 1px solid #ddd;text-align: left;border-collapse: collapse;width: 100%;">';
          if($ColorId!='0'){
        $body .= '<tr>
            <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Color</th>    
            <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">'.getAttrDetails($ColorId).'</td>
            </tr>';
             } 
		if($SizeId!='0'){
          $body .= '<tr>
            <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Size</th>    
            <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">'.
            getAttrDetails($SizeId).'</td>
            </tr>';
        }
		if($StorageId!='0'){
   			$body .= '<tr>
            <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Storage</th>    
            <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">'.getAttrDetails($StorageId).'</td>
            </tr>';
         } 
		if($RamId!='0'){
            $body .= '<tr>
            <th style="border: 1px solid #ddd;text-align: left;padding: 15px;">Ram</th>    
            <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">'.
            getAttrDetails($RamId).'</td>
            </tr>';
       }
       $body .= '</table>';
       }
       $body .= '</td>
        <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">'.$row['Quantity'].'</td>
        <td style="border: 1px solid #ddd;text-align: left;padding: 15px;">&#8377;'.number_format($row["Price"],2).'</td>
       </tr>';
    }} 
$body .= '<tr>
    <td colspan="2" style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>Sub Total</strong></td>
    <td style="border: 1px solid #ddd;text-align: left;padding: 15px;"></td>
    <td style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>&#8377;'.number_format($GrandTotal,2).'</strong></td>
    </tr>
    <tr>
    <td colspan="2" style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>Shipping Charges</strong></td>
    <td style="border: 1px solid #ddd;text-align: left;padding: 15px;"></td>
    <td style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>&#8377;'.number_format($ShippingCharge,2).'</strong></td>
    </tr>
    <tr>
   <td colspan="2" style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>Grand Total</strong></td>
    <td style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>'.$TotalQty.'</strong></td>
    <td style="border: 1px solid #ddd;text-align: left;padding: 15px;"><strong>&#8377;'.number_format($GrandTotal+$ShippingCharge,2).'</strong></td>
    </tr>';
$body .= '</table>';
$body .= 'Share some information to improve your shopping Experience.<br>';
$body .= '<strong>Note:</strong> Please Do Not Reply on this mail .Its auto generated mail for further discussion.<br><br>
Thank You.<br>
Regards,<br>
<strong>Multifix</strong><br>';
?>