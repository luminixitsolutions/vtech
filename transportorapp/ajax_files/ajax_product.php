<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'getDiffPrice2'){
$ProdId = $_POST['pid'];
	$SizeId = $_POST['sizeid'];
$sql = "SELECT * FROM related_products WHERE ProdId = '$ProdId' AND AttrValueSize='$SizeId'";
	$res = $conn->query($sql);
	$rncnt = mysqli_num_rows($res);
	if($rncnt > 0){
	$row = $res->fetch_assoc();
	$MinPrice = $row['MinPrice'];
	$MaxPrice = $row['MaxPrice'];
	$OfferPrice = $row['OfferPrice'];
	$Stock = $row['Stock'];
	$MinPrice2 = number_format($row['MinPrice'],2);
	$MaxPrice2 = number_format($row['MaxPrice'],2);
	$OfferPrice2 = number_format($row['OfferPrice'],2);
	$OfferPer = $row['OfferPer'];
	echo json_encode(array('MinPrice'=>$MinPrice,'MaxPrice'=>$MaxPrice,'OfferPrice'=>$OfferPrice,'OfferPer'=>$OfferPer,'MinPrice2'=>$MinPrice2,'MaxPrice2'=>$MaxPrice2,'OfferPrice2'=>$OfferPrice2,'Stock'=>$Stock,'status'=>'1'));
	}
	else{
	$sql2 = "SELECT * FROM products WHERE id = '$ProdId' AND Size='$SizeId'";
	$res2 = $conn->query($sql2);
	$row2 = $res2->fetch_assoc();
	$rncnt2 = mysqli_num_rows($res2);
	if($rncnt2 > 0){
	$MinPrice = $row2['MinPrice'];
	$MaxPrice = $row2['MaxPrice'];
	$OfferPrice = $row2['OfferPrice'];
	$Stock = $row2['Stock'];
	$MinPrice2 = number_format($row2['MinPrice'],2);
	$MaxPrice2 = number_format($row2['MaxPrice'],2);
	$OfferPrice2 = number_format($row2['OfferPrice'],2);
	$OfferPer = $row2['OfferPer'];
	echo json_encode(array('MinPrice'=>$MinPrice,'MaxPrice'=>$MaxPrice,'OfferPrice'=>$OfferPrice,'OfferPer'=>$OfferPer,'MinPrice2'=>$MinPrice2,'MaxPrice2'=>$MaxPrice2,'OfferPrice2'=>$OfferPrice2,'Stock'=>$Stock,'status'=>'1'));
	}
	else{
	$sql21 = "SELECT * FROM products WHERE id = '$ProdId'";
	$res21 = $conn->query($sql21);
	$row21 = $res21->fetch_assoc();
	$MinPrice = $row21['MinPrice'];
	$MaxPrice = $row21['MaxPrice'];
	$OfferPrice = $row21['OfferPrice'];
	$StorageId = $_POST['storageid'];
	$SizeId = $_POST['sizeid'];
	$RamId = $_POST['ramid'];
	$Stock = $row21['Stock'];
	$MinPrice2 = number_format($row21['MinPrice'],2);
	$MaxPrice2 = number_format($row21['MaxPrice'],2);
	$OfferPrice2 = number_format($row21['OfferPrice'],2);
	$OfferPer = $row21['OfferPer'];
	echo json_encode(array('ErrorMsg'=>'Currently, This Size Of Product is Not Available!','MinPrice'=>$MinPrice,'MaxPrice'=>$MaxPrice,'OfferPrice'=>$OfferPrice,'OfferPer'=>$OfferPer,'MinPrice2'=>$MinPrice2,'MaxPrice2'=>$MaxPrice2,'OfferPrice2'=>$OfferPrice2,'SizeId'=>$SizeId,'RamId'=>$RamId,'StorageId'=>$StorageId,'status'=>'0','Stock'=>$Stock));
	}
}
}
if($_POST['action'] == 'getDiffPrice'){
	$ProdId = $_POST['pid'];
	$SizeId = $_POST['sizeid'];
	$RamId = $_POST['ramid'];
	$StorageId = $_POST['storageid'];
	
	$sql = "SELECT * FROM related_products WHERE ProdId = '$ProdId' AND AttrValueSize='$SizeId' AND AttrValueRam='$RamId' AND AttrValueStorage='$StorageId'";
	$res = $conn->query($sql);
	$rncnt = mysqli_num_rows($res);
	if($rncnt > 0){
	$row = $res->fetch_assoc();
	$MinPrice = $row['MinPrice'];
	$MaxPrice = $row['MaxPrice'];
	$OfferPrice = $row['OfferPrice'];
	$Stock = $row['Stock'];
	$MinPrice2 = number_format($row['MinPrice'],2);
	$MaxPrice2 = number_format($row['MaxPrice'],2);
	$OfferPrice2 = number_format($row['OfferPrice'],2);
	$OfferPer = $row['OfferPer'];
	echo json_encode(array('MinPrice'=>$MinPrice,'MaxPrice'=>$MaxPrice,'OfferPrice'=>$OfferPrice,'OfferPer'=>$OfferPer,'MinPrice2'=>$MinPrice2,'MaxPrice2'=>$MaxPrice2,'OfferPrice2'=>$OfferPrice2,'Stock'=>$Stock,'status'=>'1'));
	}
	else{
	$sql2 = "SELECT * FROM products WHERE id = '$ProdId' AND Size='$SizeId' AND Ram='$RamId' AND Storage='$StorageId'";
	$res2 = $conn->query($sql2);
	$row2 = $res2->fetch_assoc();
	$rncnt2 = mysqli_num_rows($res2);
	if($rncnt2 > 0){
	$MinPrice = $row2['MinPrice'];
	$MaxPrice = $row2['MaxPrice'];
	$OfferPrice = $row2['OfferPrice'];
	$Stock = $row2['Stock'];
	$MinPrice2 = number_format($row2['MinPrice'],2);
	$MaxPrice2 = number_format($row2['MaxPrice'],2);
	$OfferPrice2 = number_format($row2['OfferPrice'],2);
	$OfferPer = $row2['OfferPer'];
	echo json_encode(array('MinPrice'=>$MinPrice,'MaxPrice'=>$MaxPrice,'OfferPrice'=>$OfferPrice,'OfferPer'=>$OfferPer,'MinPrice2'=>$MinPrice2,'MaxPrice2'=>$MaxPrice2,'OfferPrice2'=>$OfferPrice2,'Stock'=>$Stock,'status'=>'1'));
	}
	else{
	$sql21 = "SELECT * FROM products WHERE id = '$ProdId'";
	$res21 = $conn->query($sql21);
	$row21 = $res21->fetch_assoc();
	$MinPrice = $row21['MinPrice'];
	$MaxPrice = $row21['MaxPrice'];
	$OfferPrice = $row21['OfferPrice'];
	$StorageId = $_POST['storageid'];
	$SizeId = $_POST['sizeid'];
	$RamId = $_POST['ramid'];
	$Stock = $row21['Stock'];
	$MinPrice2 = number_format($row21['MinPrice'],2);
	$MaxPrice2 = number_format($row21['MaxPrice'],2);
	$OfferPrice2 = number_format($row21['OfferPrice'],2);
	$OfferPer = $row21['OfferPer'];
	echo json_encode(array('ErrorMsg'=>'Currently, This Combination is Not Available!','MinPrice'=>$MinPrice,'MaxPrice'=>$MaxPrice,'OfferPrice'=>$OfferPrice,'OfferPer'=>$OfferPer,'MinPrice2'=>$MinPrice2,'MaxPrice2'=>$MaxPrice2,'OfferPrice2'=>$OfferPrice2,'SizeId'=>$SizeId,'RamId'=>$RamId,'StorageId'=>$StorageId,'status'=>'0','Stock'=>$Stock));
	}
	}
}

if($_POST['action']=='wishlist'){
	$output="";
	$wishid = $_POST['wishid'];
	$productid = $_POST['productid'];
	$user_id = $_POST['user_id'];
	if($_POST['wishid']=="0"){
                 $q2 = "DELETE FROM wishlist WHERE UserId = '$user_id' AND ProductId = '$productid'";
                 $conn->query($q2);
               
             }
             else{
                
                 $querys25 = "INSERT INTO wishlist SET UserId = '$user_id',ProductId = '$productid',Value = '$wishid'";
                 $conn->query($querys25);
             }
     $w = "SELECT * FROM wishlist WHERE UserId = '$user_id' AND ProductId = '$productid'";
        $wr = $conn->query($w);
        $ws = $wr->fetch_assoc();
        $sqlw1 = "SELECT * FROM wishlist WHERE UserId='$user_id'";
        $resw1 = $conn->query($sqlw1);
        $wish_cnt = mysqli_num_rows($resw1);
     ?>
       <?php if($ws['Value'] == 1){
            $val = 0;
        $output.='<i class="fa fa-heart" style="color:red"></i>';
                    } else {
                        $val = 1;
        $output.='<i class="fa fa-heart-o" style="color:red"></i>';
         } 
         echo json_encode(array('output' => $output,'val'=>$val,'prod_id'=>$productid,'wish_cnt'=>$wish_cnt));         
}
?>	