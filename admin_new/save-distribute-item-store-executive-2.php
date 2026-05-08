<?php 
session_start();
include_once 'config.php';
$user_id = $_SESSION['Admin']['id'];
$StoreInchId = $_POST['StoreInchId'];
$CreatedDate = $_POST['CreatedDate'];
$BranchId = $_POST['BranchId'];
$StoreExeId = $_POST['StoreExeId'];
$Narration = addslashes(trim($_POST['Narration']));
$Rncnt = $_POST['Rncnt'];
$Rncnt2 = $_POST['Rncnt2'];
$Rncnt3 = $_POST['Rncnt3'];


try {
    // 🔹 Start Transaction
    $conn->begin_transaction();

    // 1️⃣ Insert main distribute record
    $sql = "INSERT INTO tbl_distibute_items2 
            SET StoreExeId='$StoreExeId',
                BranchId='$BranchId',
                StoreInchId='$StoreInchId',
                CreatedDate='$CreatedDate',
                Narration='$Narration'";
    if (!$conn->query($sql)) {
        throw new Exception("Error inserting main record: " . $conn->error);
    }

    $SellId = $conn->insert_id;

    // 2️⃣ Insert session cart items
    if (!empty($_SESSION["cart_item"])) {
        foreach ($_SESSION["cart_item"] as $product) {
            $StockId = $product['id'];

            // Fetch base details
            $sql = "SELECT * FROM tbl_distibute_item_details WHERE id='$StockId'";
            $row = getRecord($sql);
            if (!$row) continue;

            $ProductId   = $row['ProductId'];
            $ProductName = $row['ProductName'];
            $Purity      = $row['Unit'];
            $SerialNo    = $row['SerialNo'];
            $ModelNo     = $row['ModelNo'];
            $ProdType    = $product['ProdType'];

            // Insert main cart item
            $sql22 = "INSERT INTO tbl_distibute_item_details2 
                      SET BranchId='$BranchId',
                          StoreExeId='$StoreExeId',
                          DistId='$SellId',
                          StoreInchId='$StoreInchId',
                          ProductName='$ProductName',
                          Purity='$Purity',
                          Qty='1',
                          ProductId='$ProductId',
                          ModelNo='$ModelNo',
                          CreatedDate='$CreatedDate',
                          SerialNo='$SerialNo',
                          ProdType='$ProdType'";
            if (!$conn->query($sql22)) {
                throw new Exception("Error inserting session item: " . $conn->error);
            }

            // If product is a Bag → insert sub-items
            if ($ProdType == 2) {
                $bagItems = getList("SELECT * FROM tbl_bag_items WHERE BagId='$ProductId'");
                foreach ($bagItems as $result) {
                    $prodid        = $result['ProdId'];
                    $Product_Name  = addslashes($result['ProductName']);
                    $Qty2          = floatval($result['Qty']);

                    $sql33 = "INSERT INTO tbl_distibute_item_details2 
                              SET BranchId='$BranchId',
                                  StoreExeId='$StoreExeId',
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
                        throw new Exception("Error inserting bag sub-item: " . $conn->error);
                    }
                }
            }
        }
    }

    // ✅ Commit all queries
    $conn->commit();

    // Clear session cart
    unset($_SESSION["cart_item"]);

    echo "<script>alert('Item allocation created successfully!');window.location.href='view-distribute-item-store-executive.php';</script>";

} catch (Exception $e) {
    // ❌ Rollback on any failure
    $conn->rollback();
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');window.history.back();</script>";
}


/*$sql = "INSERT INTO tbl_distibute_items2 SET StoreExeId='$StoreExeId',BranchId='$BranchId',StoreInchId='$StoreInchId',CreatedDate='$CreatedDate',Narration='$Narration'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);
*/
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
                $sql22 = "INSERT INTO tbl_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo'";
                $conn->query($sql22);

            }
              }  

          }
      }
    }*/

    /*if($Rncnt2 > 0){
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
                $sql = "SELECT * FROM tbl_distibute_item_details WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                
                 $sql22 = "INSERT INTO tbl_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='1',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=1";
                $conn->query($sql22);
            }
             

            }
            }
        }
        
    }*/
    //print_r($_SESSION["cart_item"]);
  /*  foreach ($_SESSION["cart_item"] as $product){
        $StockId = $product['id'];
                $sql = "SELECT * FROM tbl_distibute_item_details WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                $ProdType = $product['ProdType'];
                if ( (($ProductId != '' && $ProductId != 0) || $SerialNo != '') ) {
                  $sql22 = "INSERT INTO tbl_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='1',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType='$ProdType'";
                $conn->query($sql22);
                    
                    if($ProdType == 2){
                        $sql = "SELECT * FROM tbl_bag_items WHERE BagId='$ProductId'";
                    $row = getList($sql);
                    foreach($row as $result){
                        $prodid = $result['ProdId'];
                        $Product_Name = $result['ProductName'];
                        $Qty2 = $result['Qty'];
                        $sql22 = "INSERT INTO tbl_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$Product_Name',Purity='$Purity',Qty='$Qty',ProductId='$prodid',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=0";
                $conn->query($sql22);
                    }
                    }
                
                }
    }
  
    unset($_SESSION["cart_item"]);

echo "<script>alert('Item allocated Created Successfully!');window.location.href='view-distribute-item-store-executive.php';</script>";*/
?>