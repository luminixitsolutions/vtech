<?php 
session_start();
include_once 'config.php';
$user_id = $_SESSION['Admin']['id'];
$StoreInchId = $_POST['StoreInchId'];
$CreatedDate = $_POST['CreatedDate'];
$Narration = addslashes(trim($_POST['Narration']));
$BranchId = $_POST['BranchId'];
$VehicalDate = $_POST['VehicalDate'];
$VehicalNo = $_POST['VehicalNo'];
$Rncnt = $_POST['Rncnt'];
$Rncnt2 = $_POST['Rncnt2'];

$sql = "INSERT INTO tbl_rooftop_distibute_items SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',StoreInchId='$StoreInchId',CreatedDate='$CreatedDate',Narration='$Narration'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);

if($Rncnt > 0){
$number = count($_POST["ProductId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProductId"][$i] != ''))  
              {
                $ProductName = addslashes(trim($_POST['ProductName'][$i]));
                $Purity = addslashes(trim($_POST['Purity'][$i]));
                $Weight = addslashes(trim($_POST['Weight'][$i]));
                $Price = addslashes(trim($_POST['Price'][$i]));
                $Making = addslashes(trim($_POST['Making'][$i]));
                $HmCharge = addslashes(trim($_POST['HmCharge'][$i]));
                $Qty = addslashes(trim($_POST['Qty'][$i]));
                $TotalRate = addslashes(trim($_POST['TotalRate'][$i]));
                $ProductId = addslashes(trim($_POST['ProductId'][$i]));
                $ModelNo = addslashes(trim($_POST['ModelNo'][$i]));
                $SerialNo = addslashes(trim($_POST['SerialNo'][$i]));
                $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                if($Qty > 0){
                $sql22 = "INSERT INTO tbl_rooftop_distibute_item_details SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo'";
                $conn->query($sql22);

            }
              }  

          }
      }
    }

    if($Rncnt2 > 0){
$number2 = count($_POST["CheckId"]);
if($number2 > 0)  
      {  
        for($i=0; $i<$number2; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $StockId = addslashes(trim($_POST['SerialProd'][$i]));
                 $sql = "SELECT * FROM tbl_rooftop_stocks WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                
                $sql22 = "INSERT INTO tbl_rooftop_distibute_item_details SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='1',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=1";
                $conn->query($sql22);
            }
             

            }
            }
        }
    }

echo "<script>alert('Item allocated Created Successfully!');window.location.href='view-distribute-item-store.php';</script>";

?>