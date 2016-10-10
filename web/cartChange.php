<?php
session_start();
require_once(__DIR__."/../util/pdo.php");

if(isset($_POST['cartNum'])){
    $cartNum = $_POST['cartNum'];
}
if(isset($_POST['goodsid'])){
    $goodsid= $_POST['goodsid'];
}
if(isset($_POST['quantity'])){
    $quantity= $_POST['quantity'];
}

if(is_null($cartNum) || is_null($goodsid) || is_null($quantity)  ) exit("post変");

$_SESSION['cart'][$cartNum]['quantity'] = $quantity;
$url = "cart.php";
header("Location: {$url}");
exit;
