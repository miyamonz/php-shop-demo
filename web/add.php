<?php
session_start();

echo count($_SESSION['cart']), "<br>";
echo var_dump($_SESSION['cart']), "<br>";
$_SESSION['cart'][] = ["goodsid"=>"abo01", "quantity"=> 3];
echo count($_SESSION['cart']), "<br>";
echo var_dump($_SESSION['cart']), "<br>";
