<?php
require_once(__DIR__."/pdo.php");
function isCartArray($array){
  if(!is_array($array)) return false;

  $correnctNum = 0;
  for($i=0; $i<count($array); $i++){
    if(!array_key_exists("goodsid",$array[$i])) return false;
    if(!array_key_exists("quantity",$array[$i])) return false;
    $correnctNum++;
  }
  if($correnctNum >= 0) return true;
  return $correnctNum;
}
function getIdsOfCart($cart){
  $ids = [];
  for($i=0; $i<count($cart); $i++){
    $ids[] = $cart[$i]["goodsid"];
  }
  return $ids;
}
function isDuplicate($cart){
  if(!isCartArray($cart)){
    echo "配列が不正";
    echo var_dump($cart);
    throw new Exception("配列が不正");
  }
  $ids = getIdsOfCart($cart);
  $isDup = !($ids === array_unique($ids));
  return $isDup;
}

function joinCart($cart){
  if(!isDuplicate($cart)) return $cart;
  $ids = getIdsOfCart($cart);
  $uniqueIds = array_unique($ids);

  $newCart = [];
  foreach ($uniqueIds as $id) {
    $sum = 0;
    foreach ($cart as $row) {
      if($row['goodsid'] == $id)
        $sum += $row['quantity'];
    }
    $newCart[] = ['goodsid'=>$id, 'quantity'=>$sum];

  }

  return $newCart;
}

function checkCartIsOk($cart){
  if(!isCartArray($cart)) return false;
  for($i=0; $i<count($cart); $i++){
    $goodsid = $cart[$i]['goodsid'];
    $quantity = $cart[$i]['quantity'];
    $zaiko = getById($goodsid)['quantity'];
    if($quantity > $zaiko) return false;
    if($quantity <= 0) return false;
  }
  return true;
}

function json_safe_encode($data){
  return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}
function echoTagWithArray($array){

  echo "<script json =";
  echo "'", json_safe_encode($array), "'";
  echo "></script>";
}
// $a = getDemoCart();
// $a[] = getDemoCart()[0];
// echo print_r($a),PHP_EOL;
// echo print_r(joinCart($a));
