<?php 
session_start();
include_once 'config.php';
require_once("dbcontroller.php");
$db_handle = new DBController();
if($_POST['action'] == 'saveCart'){
 if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT * FROM tbl_distibute_item_details2 WHERE id='" . $_POST["id"] . "'");
     $itemArray = array($productByCode[0]["code"]=>array('code'=>$productByCode[0]["code"],'id'=>$_POST["id"],'ProductName'=>$productByCode[0]["ProductName"],'Unit'=>$productByCode[0]["Unit"],'SerialNo'=>$productByCode[0]["SerialNo"],'ModelNo'=>$productByCode[0]["ModelNo"]));
      if(!empty($_SESSION["cart_item"])) {
        if(array_key_exists($productByCode[0]["code"], $_SESSION["cart_item"])) {
          foreach($_SESSION["cart_item"] as $k => $v) {
              if($productByCode[0]["code"] == $k)
                $_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
          }
        } else {
          $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
        }
      } else {
        $_SESSION["cart_item"] = $itemArray;
      }
    }
    echo "Product Added";
}

if($_POST['action'] == 'delete_shop_prod'){
    $removeId = isset($_POST['id']) ? (string) $_POST['id'] : '';
    if ($removeId !== '' && !empty($_SESSION["cart_item"])) {
        foreach ($_SESSION["cart_item"] as $k => $v) {
            $itemId = isset($v['id']) ? (string) $v['id'] : '';
            if ($removeId === (string) $k || ($itemId !== '' && $removeId === $itemId)) {
                unset($_SESSION["cart_item"][$k]);
            }
        }
        if (empty($_SESSION["cart_item"])) {
            unset($_SESSION["cart_item"]);
        }
    }
}
?>