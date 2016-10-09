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
$isZaikoTariru = false;
$isAccept      = false;
$isPost = !is_null($goodsid) and !is_null($quantity);

$eroors = [];

//postされたデータが適切かをチェック
if($isPost){
  $goodsInfo = getById($goodsid);
  // echo var_dump($goodsInfo);
  if(!$goodsInfo == false) $isInDb = true;
}else $errors[] = "ポストされてないか、変です";

if($isInDb){
  $goodsName     = $goodsInfo['name'];
  $zaiko         = $goodsInfo['quantity'];
  echo $zaiko;
  $isZaikoTariru = ($zaiko >= $quantity);
  if($isZaikoTariru) $isAccept = true;
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
<?php }else if($isInDb && !$isZaikoTariru){ ?> 
<p>
  エラー：申し訳ありませんが、現在在庫数不足ですので、<?= $quantity?>個の注文はできません。<br>
  <?= $zaiko ?>個以下での注文をお願いします。
</p>

<?php }else if($isPost && !$isInDb) { ?> 
<p>
エラー：商品IDが不正です。<?= $goodsid?>
</p>
<?php }else { ?> 
   <p>エラー：商品が追加されませんでした。</p>
<?php } ?> 

<a href="cart.php">買い物かごを確認する</a>
</body>
</html>
