<?php 
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
$sessionid = session_id();
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getCustRow'){
$i = $_POST['id'];?>

<tr id="row<?php echo $i;?>">

 <td>


<select name="ProductId[]" id="ProductId<?php echo $i;?>" onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i;?>').value)" class="form-control">
    <option value="" selected>Select Product</option>
    <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
     <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
 <?php } ?>
</select>

</td>
       <input type="hidden" name="ProductName[]" id="ProductName<?php echo $i;?>" value="">
 <input type="hidden" name="ModelNo[]" id="ModelNo<?php echo $i;?>" value="">
 <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
<td>
<input type="number" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required>
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off">
</td>



<td>
<input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required>
</td>


<td><input type="text" name="SGST[]" id="SGST<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required></td>
       <td><input type="text" name="CGST[]" id="CGST<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required></td>
       <td><input type="text" name="IGST[]" id="IGST<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required></td>
      
  


<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
<td>
<input type="text" name="TotalRate[]" id="Total<?php echo $i;?>" class="form-control txt" placeholder="e.g.,150" value="" autocomplete="off" readonly>
</td>
<td>
  <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
</td>

</tr>

<?php } 

if($_POST['action'] == 'getProdDetails') {
    $id = addslashes(trim($_POST['id']));
    $sql = "SELECT tp.*,tc.Name As Category,tsc.Name As Brand FROM tbl_products tp 
            LEFT JOIN tbl_category tc ON tc.id=tp.CatId 
            LEFT JOIN tbl_sub_category tsc ON tsc.id=tp.BrandId WHERE tp.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
 //    $Price = $row['Price'];
	// echo json_encode(array('Price'=>$Price));
   }

   if($_POST['action'] == 'getProdDetails2') {
    $id = addslashes(trim($_POST['id']));
    $sql = "SELECT tp.*,tc.Name As Category,tsc.Name As Brand,tp2.Price,tp2.Details FROM tbl_stocks tp 
            LEFT JOIN tbl_category tc ON tc.id=tp.CatId 
            LEFT JOIN tbl_products tp2 ON tp2.id=tp.ProductId 
            LEFT JOIN tbl_sub_category tsc ON tsc.id=tp.BrandId WHERE tp.ProductNo='$id' AND tp.BuyStatus=0";
    $row = getRecord($sql);
    echo json_encode($row);
   }


   if($_POST['action'] == 'addCart'){
$ProductId = $_POST['ProductId'];
$Qty = $_POST['Qty'];
$CatId = $_POST['CatId'];
$BrandId = $_POST['BrandId'];
$Code = $_POST['Code'];
$ModelNo = $_POST['ModelNo'];
$ModelName = addslashes(trim($_POST['ModelName']));
$sql22 = "SELECT * FROM tbl_temp_stock WHERE SessionId='$sessionid'";
$rncnt22 = getRow($sql22);
if($Qty > $rncnt22){
$sql = "INSERT INTO tbl_temp_stock SET SessionId='$sessionid',ProductId='$ProductId',CatId='$CatId',BrandId='$BrandId',Code='$Code',ModelNo='$ModelNo',ModelName='$ModelName'";
$conn->query($sql);
echo 1;
}
else{
echo "You Cant add stock more than ".$Qty;
}
 // if(!empty($_POST["Qty"])) {
 //  $productByCode = $db_handle->runQuery("SELECT * FROM tbl_products WHERE Code='" . $_POST["Code"] . "'");
 //      $itemArray = array($productByCode[0]["Code"]=>array('ModelNo'=>$productByCode[0]["ModelNo"], 'Code'=>$productByCode[0]["Code"]));
 //      if(!empty($_SESSION["cart_item"])) {
 //        if(in_array($productByCode[0]["Code"],$_SESSION["cart_item"])) {
 //          foreach($_SESSION["cart_item"] as $k => $v) {
 //              if($productByCode[0]["Code"] == $k)
 //                $_SESSION["cart_item"][$k]["Qty"] = $_POST["Qty"];
 //          }
 //        } else {
 //          $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
 //        }
 //      } else {
 //        $_SESSION["cart_item"] = $itemArray;
 //      }

 //  }
 }


  if($_POST['action'] == 'showCart'){?>
   <table class="table table-striped table-bordered">
         <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
            $i=1;
            $sql2 = "SELECT * FROM tbl_temp_stock WHERE SessionId='$sessionid'";
            $row2 = getList($sql2);
            foreach($row2 as $result){
          ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $result['ModelName'] ?></td>
                <td><a onClick="deleteStock(<?php echo $result['id'];?>)" href="javascript:void(0)"><i class="lnr lnr-trash text-danger"></i></a></td>
            </tr>
          <?php $i++;} ?>
            </tbody>
    </table>
<?php } 

  if($_POST['action'] == 'deleteStock'){
    $id = $_POST['id'];
    $sql = "DELETE FROM tbl_temp_stock WHERE id='$id'";
    $conn->query($sql);
    echo 1;
  }

  if($_POST['action'] == 'availableSeries'){
    $ModelNo = $_POST['modelno'];
    ?>
   <table class="table table-striped table-bordered">
    <thead>
            <tr>
              <th>#</th>
              <th>Series</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql2 = "SELECT * FROM tbl_stocks WHERE ModelNo='".$ModelNo."' AND BuyStatus=0";
            $row2 = getList($sql2);
            foreach($row2 as $result){
          ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $result['ProductNo'] ?></td>
                
            </tr>
          <?php $i++;} ?>
        </tbody>
</table>
 
<?php } 


 if($_POST['action'] == 'checkCart'){
    $Qty = $_POST['Qty'];
   $sql22 = "SELECT * FROM tbl_temp_stock WHERE SessionId='$sessionid'";
$rncnt22 = getRow($sql22);
if($Qty == $rncnt22){  
    echo 1;
}
else{
    echo 0;
}
 }

 if($_POST['action'] == 'getAccDetails'){
     $id = addslashes(trim($_POST['id']));
    $sql = "SELECT tp.* FROM tbl_accessories tp 
            WHERE tp.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
 }
 
if ($_POST['action'] == 'getSerialProdDetails') {
     $barcodeno = addslashes(trim($_POST['barcodeno']));
    $custId    = $_POST['cust_id'];

    // Step 1: Check if item already exists in stock
    $sql33 = "SELECT * FROM tbl_stocks WHERE CrDr='dr' AND SerialNo='$barcodeno'";
    if (getRow($sql33) > 0) {
        echo json_encode(['status' => 0, 'message' => 'Already in Stock']);
        exit;
    }

    // Step 2: Check in distributed items
    $sql22 = "SELECT * FROM tbl_distibute_item_details2 WHERE SerialNo!='' AND SerialNo='$barcodeno'";
    if (getRow($sql22) > 0) {
        $row22 = getRecord($sql22);

        // Step 3: Normalize data
        $code        = $row22['code'] ?? $row22['SerialNo'];
        $id          = $row22['id'];
        $MainProdId  = $row22['ProductId'];
        $productName = $row22['ProductName'] ?? '';
        $unit        = $row22['Unit'] ?? '';
        $serial      = $row22['SerialNo'] ?? '';
        $model       = $row22['ModelNo'] ?? '';
        $ProdType    = $row22['ProdType'] ?? ($row22['prodtype'] ?? ($row22['prod_type'] ?? 0));

        // Step 4: Get allowed qty from specification
        $sqlSpec = "SELECT Qty FROM tbl_cust_product_specification 
                    WHERE CustId='$custId' AND ProdId='$MainProdId'";
        $rowSpec = getRecord($sqlSpec);
        $ReqQty  = !empty($rowSpec['Qty']) ? intval($rowSpec['Qty']) : 0;

        // Step 5: Check how many serials of same product are already in cart
        $currentProdCount = 0;
        if (!empty($_SESSION['cart_item'])) {
            foreach ($_SESSION['cart_item'] as $item) {
                // same product?
                if ($item['MainProdId'] == $MainProdId) {
                    $currentProdCount++;
                }
                // prevent duplicate serial
                if ($item['SerialNo'] == $serial) {
                    echo json_encode([
                        'status'  => 0,
                        'message' => "This serial ($serial) is already added."
                    ]);
                    exit;
                }
            }
        }

        // Step 6: Block if exceeds specification limit
        if ($ReqQty > 0 && ($currentProdCount + 1) > $ReqQty) {
            echo json_encode([
                'status'  => 0,
                'message' => "You already added $currentProdCount of $ReqQty allowed for this product."
            ]);
            exit;
        }

        // Step 7: Add to cart
        $itemArray = [
            $code => [
                'code'        => $code,
                'id'          => $id,
                'ProductName' => $productName,
                'Unit'        => $unit,
                'SerialNo'    => $serial,
                'ModelNo'     => $model,
                'ProdType'    => $ProdType,
                'quantity'    => 1,
                'MainProdId'  => $MainProdId
            ]
        ];

        if (!empty($_SESSION["cart_item"])) {
            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }

        echo json_encode([
            'status'  => 1,
            'message' => 'Product Added Successfully',
            'product' => $row22
        ]);
    } else {
        echo json_encode(['status' => 2, 'message' => 'Product Not Found']);
    }
}


if ($_POST['action'] == 'displayCart') {
    $custId = $_POST['cust_id'];
    $cartHtml = '';

    if (!empty($_SESSION['cart_item'])) {
        // --- Step 1: Group items by MainProdId ---
        $grouped = [];
        foreach ($_SESSION['cart_item'] as $item) {
            $mainId = $item['MainProdId'];
            if (!isset($grouped[$mainId])) {
                $grouped[$mainId] = [
                    'ProductName' => $item['ProductName'],
                    'ProdType'    => $item['ProdType'],
                    'MainProdId'  => $mainId,
                    'serials'     => [],
                ];
            }
            $grouped[$mainId]['serials'][] = [
                'SerialNo' => $item['SerialNo'],
                'code'     => $item['code'],
            ];
        }

        // --- Step 2: Build HTML for each grouped product ---
        $cartHtml .= '<div class="cart-items">';
        foreach ($grouped as $group) {
            $MainProdId  = $group['MainProdId'];
            $ProdType    = $group['ProdType'];
            $productName = htmlspecialchars($group['ProductName']);

            // --- Step 3: Get required qty from specification ---
            $sql = "SELECT Qty FROM tbl_cust_product_specification 
                    WHERE CustId = '$custId' AND ProdId = '$MainProdId'";
            $row = getRecord($sql);

            if ($ProdType == 2) {
                $ReqQty = 1;
            } else {
                $ReqQty = $row['Qty'] ?? 0;
            }

            // --- Step 4: Start card HTML ---
            $cartHtml .= '
            <div class="cart-card shadow-sm border rounded-3 p-3 mb-3">
                <div class="cart-info">
                    <h6 class="fw-semibold mb-2 text-dark">' . $productName . '</h6>
                    <div class="small text-muted mb-1">
                        Req. Qty: <span class="fw-bold text-dark">' . $ReqQty . '</span>
                    </div>
                    <div class="serial-list">';

            // --- Step 5: Show each serial with delete button ---
            foreach ($group['serials'] as $serialItem) {
                $SerialNo = htmlspecialchars($serialItem['SerialNo']);
                $code     = htmlspecialchars($serialItem['code']);

                $cartHtml .= '
                    <div class="d-flex justify-content-between align-items-center border rounded px-2 py-1 mb-1 bg-light">
                        <span class="text-dark small">Serial No: 
                            <span class="fw-bold text-danger">' . $SerialNo . '</span>
                        </span>
                        <button class="btn btn-link text-danger p-0 border-0 remove-item" 
                                data-code="' . $code . '">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>';
            }

            $cartHtml .= '
                    </div>
                </div>
            </div>';
        }

        $cartHtml .= '</div>';

    } else {
        $cartHtml = '
        <div class="text-center text-muted py-4">
            <i class="bi bi-cart3 fs-2 d-block mb-2"></i>
            <span>Your cart is empty</span>
        </div>';
    }

    echo $cartHtml;
    exit;?>
    <style>
.cart-card {
  background: #fff;
  transition: all 0.2s ease-in-out;
}
.cart-card:hover {
  transform: scale(1.01);
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.cart-info h6 {
  font-size: 15px;
}
.serial-list .d-flex {
  font-size: 13px;
}
.remove-item i {
  font-size: 16px;
}
</style>
<?php
} 


if ($_POST['action'] == 'removeItem') {
    session_start();
    $code = $_POST['code'] ?? '';

    if (!empty($code) && isset($_SESSION['cart_item'][$code])) {
        unset($_SESSION['cart_item'][$code]);
        
        // If cart becomes empty, clean it up
        if (empty($_SESSION['cart_item'])) {
            unset($_SESSION['cart_item']);
        }

        echo json_encode([
            'status' => 1,
            'message' => 'Item removed successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 0,
            'message' => 'Item not found in cart.'
        ]);
    }
    exit;
}
?>