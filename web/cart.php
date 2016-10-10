<?php
session_start();
require_once(__DIR__."/../util/pdo.php");

//かごの商品消す
if(isset($_POST['delete'])){
    $delNum = $_POST['delete'];
    array_splice($_SESSION['cart'],$delNum, 1 );
}
if(isset($_GET['deleteall'])){
    $_SESSION['cart'] = [];
}


if(!is_null($_SESSION['cart'])){
    $cart = $_SESSION['cart'];
}else {
    $cart = [];
    echo "仮のデータを入れています",PHP_EOL;
    $cart = getDemoCart();
}
// echo "cart ",var_dump($cart), PHP_EOL;

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
// echo "detail ", var_dump($details), PHP_EOL;

// だぶりチェック
if(isDuplicate($cart)){
  $cart = joinCart($cart);
  $_SESSION['cart'] = $cart;
}

$zaikos = [];
for($i=0; $i<count($cart); $i++){
  $key = $cart[$i]["goodsid"]; 
  $zaikos[] = [
    "goodsid" => $key,
    "zaiko"   => getById($key)['quantity']
  ];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>買い物かご</title>
<script id="php" cart=<?= json_safe_encode($cart)?> zaiko=<?= json_safe_encode($zaikos)?>></script>
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
    <td><?= $details[$i]["name"]?> <a href="goods.php?goodsid=<?= $cart[$i]['goodsid']?>">商品ページへ</a></td>
        <td><?= $details[$i]["price"]?></td>
        <td><?= $cart[$i]["quantity"]?>
          <div class="henkouContainer">
            <button class="henkou" name="" value='<?= $i?>'>変更する</button>
        <form class='cartChange' value='<?=$i?>' method="post" action="cartChange.php" style="display:none">
            <input type="hidden" name="goodsid" value='<?= $cart[$i]['goodsid']?>'>
            <input type="number" name="quantity" value='<?= $cart[$i]['quantity']?>'>
            <button type="submit" name="cartNum" value='<?= $i?>'>変更</button>
          </form>
        </div>
            
        </td>
        <td>
          <?=  $details[$i]["price"]* $cart[$i]["quantity"] ?>
          <form method="post" action="<?= $_SERVER['PHP_SELF']?>">
            <button type="submit" name="delete" value='<?= $i?>'>削除する</button>
          </form>
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
<form method="get" action="<?= $_SERVER['PHP_SELF'] ?>">
    <button type="submit" name="deleteall" value=''>買い物かごを空にする</button>
</form>
<hr>

<form action="confirm.php" method="get">
    <button type="submit" name="confirm">注文確認画面へ進む</button>
</form>
</body>
<script src="./jquery-3.1.1.min.js"></script>
<script src="../util/util.js"></script>
<script src="./cart.js"></script>
</html>
