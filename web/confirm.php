<?php
session_start();
require_once(__DIR__."/../util/pdo.php");

if(!is_null($_SESSION['cart'])){
    $cart = $_SESSION['cart'];
}else {
    $cart = [];
    echo "仮のデータを入れています",PHP_EOL;
    $cart = getDemoCart();
}
//カゴの商品のデータを取ってきます
$pdo = getPdo();
$details = [];
for($i=0; $i<count($cart); $i++){
  $tmp = $cart[$i];
  $goodsid = $cart[$i]['goodsid'];
  $query = "select * from inventory.goods where id = '{$goodsid}'";
  $st = $pdo->query($query);
  $row = $st->fetch(PDO::FETCH_ASSOC);
  $details[] = $row;
}

// だぶりチェック
if(isDuplicate($cart)){
  $cart = joinCart($cart);
  $_SESSION['cart'] = $cart;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>確認</title>
</head>
<body>
  
</body>
</html>
<style>
table {
border-collapse: collapse;
}
table td {
padding-right: 50px;
}
table tr:last-child ,table tr:first-child{
  border: solid 1px;
}
table td form {
display: inline-flex;
float: right;
}
.henkouContainer{
}
table a, .henkouContainer {
display:inline-block;
}
.henkouContainer form input {
width: 40px;
display:inline;
}
.henkouContainer button + * {
display:inline;
}
</style>
</head>
<body>
<h1>買い物かご</h1>
<a href="index.php">topへ戻る</a>
<table style="border: 1px solid;">
<?php if(count($cart) == 0) {?>

<?php } ?>    
    <tr>
        <td>商品名</td>
        <td>単価</td>
        <td>注文個数</td>
        <td>金額</td>
    </tr>
<?php for($i=0;$i< count($cart); $i++) {?>
    <tr>
      <td><?= $details[$i]["name"]?> </td>
      <td><?= $details[$i]["price"]?></td>
      <td><?= $cart[$i]["quantity"]?></td>
      <td>
        <?=  $details[$i]["price"]* $cart[$i]["quantity"] ?>
      </td>
    </tr>
<?php } ?>    
    <tr>
        <td>合計金額</td>
        <td></td>
        <td></td>
<?php
$sum =0;
for($i=0; $i<count($cart); $i++){
    $sum += $details[$i]['price']*$cart[$i]['quantity'];

}
?>
        <td><?=  $sum?></td>
    </tr>
</table>
<hr>

<form action="" method="post">
    <button type="submit" name="confirm">注文を確定する</button>
</form>
