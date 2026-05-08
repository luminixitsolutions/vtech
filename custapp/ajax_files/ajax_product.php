<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
$user_id = $_SESSION['User']['id'];
if($_POST['action']=='getProdDetails'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_rooftop_rate_calculation WHERE id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
}

if ($_POST['action'] == 'addToCart') {
    if (!empty($_POST["quantity"])) {
        $productByCode = $db_handle->runQuery("SELECT id, Code, ProductName FROM tbl_rooftop_rate_calculation WHERE id='" . $_POST["id"] . "'");

        if (!empty($productByCode)) {
            $code = $productByCode[0]["Code"];
            $itemArray = array(
                $code => array(
                    'Code' => $code,
                    'id' => $productByCode[0]["id"],
                    'ProductName' => $productByCode[0]["ProductName"],
                    'Capacity' => $_POST["Capacity"],
                    'MakeModule' => $_POST["MakeModule"],
                    'Qty' => $_POST["quantity"],
                    'Rate' => $_POST["Rate"],
                    'CostOfItem' => $_POST["CostOfItem"]
                )
            );

            if (!empty($_SESSION["cart_item"])) {
                if (array_key_exists($code, $_SESSION["cart_item"])) {
                    $_SESSION["cart_item"][$code]["Qty"] = $_POST["quantity"];
                } else {
                    $_SESSION["cart_item"] += $itemArray; // Merging correctly
                }
            } else {
                $_SESSION["cart_item"] = $itemArray;
            }
        }
    }
    echo 1;
}

if($_POST['action'] == 'displayCart'){
   ?>
   <div class="table-container" style="overflow-x: auto;">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Capacity</th>
                        <th>Make Of Module</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Cost Of Item</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION["cart_item"])) { 
                        foreach ($_SESSION["cart_item"] as $product) { ?>
                            <tr>
                                <td><?php echo $product['ProductName']; ?></td>
                                <td><?php echo $product['Capacity']; ?></td>
                                <td><?php echo $product['MakeModule']; ?></td>
                                <td><?php echo $product['Qty']; ?></td>
                                <td><?php echo $product['Rate']; ?></td>
                                <td><?php echo $product['CostOfItem']; ?></td>
                                <td>
                                    <a href="javascript:void(0)" onclick="delete_prod('<?php echo $product['Code']; ?>')" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                    <?php } } else { ?>
                        <tr><td colspan="7" class="text-center">No items in the cart</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
<?php 
}

if($_POST['action'] == 'delete_cart_prod'){
   if (!empty($_SESSION["cart_item"])) {
   foreach ($_SESSION["cart_item"] as $k => $v) {
       if ($_POST["code"] == $k)
           unset($_SESSION["cart_item"][$k]);
       if (empty($_SESSION["cart_item"]))
           unset($_SESSION["cart_item"]);
   }
}
   
}