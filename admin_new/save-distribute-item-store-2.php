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
$Rncnt3 = $_POST['Rncnt3'];

try {
    // 🔹 Begin Transaction
    $conn->begin_transaction();

    // 1️⃣ Insert main distribute record
    $sql = "INSERT INTO tbl_distibute_items 
            SET VehicalNo='$VehicalNo',
                VehicalDate='$VehicalDate',
                BranchId='$BranchId',
                StoreInchId='$StoreInchId',
                CreatedDate='$CreatedDate',
                Narration='$Narration'";
    if (!$conn->query($sql)) {
        throw new Exception("Error inserting main distribution record: " . $conn->error);
    }

    $SellId = $conn->insert_id;

    // 2️⃣ Handle serial-numbered products (ProdType = 1)
    if ($Rncnt2 > 0 && !empty($_POST["CheckId"])) {
        foreach ($_POST["CheckId"] as $i => $CheckId) {
            if ($CheckId != 1) continue;

            $StockId = addslashes(trim($_POST['SerialProd'][$i]));
            $sql     = "SELECT * FROM tbl_stocks WHERE id='$StockId'";
            $row     = getRecord($sql);
            if (!$row) continue;

            $ProductId   = $row['ProductId'];
            $ProductName = $row['ProductName'];
            $Purity      = $row['Unit'];
            $SerialNo    = $row['SerialNo'];
            $ModelNo     = $row['ModelNo'];

            $sql22 = "INSERT INTO tbl_distibute_item_details 
                      SET VehicalNo='$VehicalNo',
                          VehicalDate='$VehicalDate',
                          BranchId='$BranchId',
                          DistId='$SellId',
                          StoreInchId='$StoreInchId',
                          ProductName='$ProductName',
                          Purity='$Purity',
                          Qty='1',
                          ProductId='$ProductId',
                          ModelNo='$ModelNo',
                          CreatedDate='$CreatedDate',
                          SerialNo='$SerialNo',
                          ProdType=1";
            if (!$conn->query($sql22)) {
                throw new Exception("Error inserting ProdType=1 item: " . $conn->error);
            }
        }
    }

    // 3️⃣ Handle bag products (ProdType = 2)
    if ($Rncnt3 > 0 && !empty($_POST["CheckId2"])) {
        foreach ($_POST["CheckId2"] as $i => $CheckId2) {
            if ($CheckId2 != 1) continue;

            $StockId = addslashes(trim($_POST['SerialProd2'][$i]));
            $sql     = "SELECT * FROM tbl_stocks WHERE id='$StockId'";
            $row     = getRecord($sql);
            if (!$row) continue;

            $ProductId   = $row['ProductId'];
            $ProductName = $row['ProductName'];
            $Purity      = $row['Unit'];
            $SerialNo    = $row['SerialNo'];
            $ModelNo     = $row['ModelNo'];

            // Insert main bag record
            $sql22 = "INSERT INTO tbl_distibute_item_details 
                      SET VehicalNo='$VehicalNo',
                          VehicalDate='$VehicalDate',
                          BranchId='$BranchId',
                          DistId='$SellId',
                          StoreInchId='$StoreInchId',
                          ProductName='$ProductName',
                          Purity='$Purity',
                          Qty='1',
                          ProductId='$ProductId',
                          ModelNo='$ModelNo',
                          CreatedDate='$CreatedDate',
                          SerialNo='$SerialNo',
                          ProdType=2";
            if (!$conn->query($sql22)) {
                throw new Exception("Error inserting main Bag item: " . $conn->error);
            }

            // Insert all bag sub-items
            $bagItems = getList("SELECT * FROM tbl_bag_items WHERE BagId='$ProductId'");
            foreach ($bagItems as $result) {
                $prodid        = $result['ProdId'];
                $Product_Name  = addslashes($result['ProductName']);
                $Qty2          = floatval($result['Qty']);

                $sql33 = "INSERT INTO tbl_distibute_item_details 
                          SET VehicalNo='$VehicalNo',
                              VehicalDate='$VehicalDate',
                              BranchId='$BranchId',
                              DistId='$SellId',
                              StoreInchId='$StoreInchId',
                              ProductName='$Product_Name',
                              Purity='$Purity',
                              Qty='$Qty2',
                              ProductId='$prodid',
                              ModelNo='$ModelNo',
                              CreatedDate='$CreatedDate',
                              SerialNo='$SerialNo',
                              ProdType=0";
                if (!$conn->query($sql33)) {
                    throw new Exception("Error inserting Bag sub-item: " . $conn->error);
                }
            }
        }
    }

    // ✅ Commit transaction
    $conn->commit();

    echo "<script>alert('Item allocation created successfully!');window.location.href='view-distribute-item-store.php';</script>";

} catch (Exception $e) {
    // ❌ Rollback on error
    $conn->rollback();
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');window.history.back();</script>";
}

/*$sql = "INSERT INTO tbl_distibute_items SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',StoreInchId='$StoreInchId',CreatedDate='$CreatedDate',Narration='$Narration'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);*/

/*if($Rncnt > 0){
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
                $sql22 = "INSERT INTO tbl_distibute_item_details SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo'";
                $conn->query($sql22);

            }
              }  

          }
      }
    }*/

   /* if($Rncnt2 > 0){
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
                 $sql = "SELECT * FROM tbl_stocks WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                
                $sql22 = "INSERT INTO tbl_distibute_item_details SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='1',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=1";
                $conn->query($sql22);
            }
             

            }
            }
        }
    }
    
    
    if($Rncnt3 > 0){
$number3 = count($_POST["CheckId2"]);
if($number3 > 0)  
      {  
        for($i=0; $i<$number3; $i++)  
          {  
            if(trim($_POST["CheckId2"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId2'][$i]));
                if($CheckId == 1){
                $StockId = addslashes(trim($_POST['SerialProd2'][$i]));
                 $sql = "SELECT * FROM tbl_stocks WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                
                $sql22 = "INSERT INTO tbl_distibute_item_details SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='1',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=2";
                $conn->query($sql22);
                
                
                $sql = "SELECT * FROM tbl_bag_items WHERE BagId='$ProductId'";
                    $row = getList($sql);
                    foreach($row as $result){
                        $prodid = $result['ProdId'];
                        $Product_Name = $result['ProductName'];
                        $Qty2 = $result['Qty'];
                        $sql22 = "INSERT INTO tbl_distibute_item_details SET VehicalNo='$VehicalNo',VehicalDate='$VehicalDate',BranchId='$BranchId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$Product_Name',Purity='$Purity',Qty='$Qty2',ProductId='$prodid',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=0";
                $conn->query($sql22);
                    }
                    
            }
             

            }
            }
        }
    }

echo "<script>alert('Item allocated Created Successfully!');window.location.href='view-distribute-item-store.php';</script>";*/

?>