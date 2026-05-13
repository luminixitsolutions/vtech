<?php
session_start();
include_once 'config.php';
require_once("dbcontroller.php");
$db_handle = new DBController();

if ($_POST['action'] == 'saveCart') {
    if (!empty($_POST["quantity"])) {
        $productByCode = $db_handle->runQuery("SELECT Code,id FROM tbl_products WHERE Code='" . $_POST["code"] . "'");
        $itemArray = array($productByCode[0]["Code"] => array('code' => $productByCode[0]["Code"], 
        'id' => $productByCode[0]["id"], 'ProductName' => $_POST["pname"], 
        'ModelNo' => $_POST["modelno"], 'Qty' => $_POST["quantity"], 
        'Price' => $_POST["price"], 'SGST' => $_POST["sgst"],
        'CGST' => $_POST["cgst"], 'IGST' => $_POST["igst"],
        'TotalRate' => $_POST["total"],'Unit'=>$_POST["unit"]
    ));
        if (!empty($_SESSION["cart_item"])) {
            if (in_array($productByCode[0]["Code"], $_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($productByCode[0]["Code"] == $k)
                        $_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
                }
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }
    }
    echo 1;
    //print_r($_SESSION["cart_item"]);
}

if ($_POST['action'] == 'delete_shop_prod') {
    if (!empty($_SESSION["cart_item"])) {
        foreach ($_SESSION["cart_item"] as $k => $v) {
            if ($_POST["id"] == $k)
                unset($_SESSION["cart_item"][$k]);
            if (empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
        }
    }
    //print_r($_SESSION["cart_item"]);
}

if ($_POST['action'] == 'displayCart') {?>
<table class="table table-striped table-bordered" width="100%">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Rate</th>
            <th>SGST</th>
            <th>CGST</th>
            <th>IGST</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($_SESSION["cart_item"] as $product){?>
        <tr>
            <th><?php echo $product['ProductName'];?></th>
            <th><?php echo $product['Qty'];?></th>
            <th><?php echo $product['Unit'];?></th>
            <th><?php echo $product['Price'];?></th>
            <th><?php echo $product['SGST'];?></th>
            <th><?php echo $product['CGST'];?></th>
            <th><?php echo $product['IGST'];?></th>
            <th><?php echo $product['TotalRate'];?></th>
            <th><a href="javascript:void(0)" onclick="delete_prod('<?php echo $product['code'];?>')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a></th>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php }  

if ($_POST['action'] == 'calculate_total') {
    foreach ($_SESSION["cart_item"] as $product){
        $TotalRate += $product['TotalRate'];
    }
    echo $TotalRate;
}
?>