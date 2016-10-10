<?php
session_start();
require_once(__DIR__."/../util/pdo.php");

//かごの商品消す
if(isset($_POST['goodsid'])){
    $goodsid = $_POST['goodsid'];
}
if(isset($_POST['quantity'])){
    $quantity = $_POST['quantity'];
}

// $goodsid = "abo01";
// $quantity = 1;

$isPost        = false;
$isInDb        = false;
$isNumCorrect = false;
$isAccept      = false;
$isPost = !is_null($goodsid) and !is_null($quantity);

$eroors = [];

//postされたデータが適切かをチェック
if($isPost){
  $goodsInfo = getById($goodsid);
  if(!$goodsInfo == false) $isInDb = true;

  //個数が違った場合も次に行けない
  if(ctype_digit($quantity)){
    $quantity = (int)$quantity;
  }else{
    $isInDb = false;
  }
}else $errors[] = "ポストされてないです";

if($isInDb){
  $goodsName     = $goodsInfo['name'];
  $zaiko         = $goodsInfo['quantity'];
  $isNumCorrect = (0<$quantity) && ($quantity <= $zaiko);
  if($isNumCorrect) $isAccept = true;
}else $errors[] = "dbにありませんでした";

if($isAccept){
  //データベースにあるし在庫も有る
  $_SESSION['cart'][]= ["goodsid"=>$goodsid, "quantity"=>$quantity];
  $isAccept = true;
}else $errors[] = "在庫がアレで受け付けられません";

// echo var_dump($errors);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品追加</title>
</head>
<body>
<?php if($isAccept) { ?>
    <p>買い物かごに追加しました</p>
    <table>
        <tr>
            <td>商品名</td>
            <td><?= $goodsName?></td>
        </tr>
        <tr>
            <td>個数</td>
            <td><?= $quantity?></td>
        </tr>
    </table>
<?php }else if($isInDb && !$isNumCorrect){ ?> 
<p>
  エラー：注文数が正しくありません。在庫以下の注文をお願いします。<br>
  在庫数：<?= $zaiko ?><br>
  注文数：<?= $quantity?><br>
</p>

<?php }else if($isPost && !$isInDb) { ?> 
<p>
エラー：商品IDまたは個数が不正です。<br>
商品ID:<?= $goodsid?><br>
個数:<?= $quantity?>
</p>
<?php } ?> 

<a href="cart.php">買い物かごを確認する</a><br>
<a href="index.php">TOPへ戻る</a>
</body>
</html>
