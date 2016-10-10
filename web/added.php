<?php
session_start();
require_once(__DIR__."/../util/pdo.php");

$goodsid = "";
$quantity = "";
if(isset($_POST['goodsid'])){
  $goodsid = $_POST['goodsid'];
}
if(isset($_POST['quantity'])){
  $quantity = $_POST['quantity'];
}

// $goodsid = "abo01";
// $quantity = 1;

//postが正しいか（idとquantityが受け取れてる
//idがdbにあるか
//量が正しいか　１以上、在庫以下
//かごと合わせて、やっぱ正しいか
//正常、かごに追加
$isPost       = !is_null($goodsid) and !is_null($quantity);
$isQuantNum   = ctype_digit($quantity);
$isAccept     = false;

$errors = [];
do {
  if(!$isPost){
    $errors[] = "postが不正です。";
    break;
  }

  if($isQuantNum){
    $quantity = (int)$quantity;
  }else{
    $errors[] = "個数が不正です。";
  }

  $goodsInfo = getById($goodsid);
  if(($goodsInfo == false)) {
    $errors[] = "商品が見つかりませんでした。";
  }

  if(!$isQuantNum || !$goodsInfo){
    break;
  }

  $row = ["goodsid" => $goodsid, "quantity" => $quantity ];
  $goodsName    = $goodsInfo['name'];
  $zaiko        = $goodsInfo['quantity'];
  if($quantity <= 0) {
    $errors[] = "個数が０以下です。";
    break;
  }
  if($quantity > $zaiko) {
    $errors[] = "在庫を超えた注文はできません。";
    break;
  }

  //cartに既に同じ商品があると、足した結果在庫を超える可能性がある。そのチェック
  $oldCart = $_SESSION['cart'];
  $addedCart = $oldCart;
  $addedCart[] = $row;
  $addedCart = joinCart($addedCart);
  if(checkCartIsOk($addedCart)){
    $_SESSION['cart'] = $addedCart;
  } else{
    $_SESSION['cart'] = $oldCart;
    $errors[] = "カゴに既に同じ商品があり、今の注文を合わせると在庫を超えてしまいます。";
    break;
  }

  //データベースにあるし在庫も有るし、追加後も在庫を超えない。のでok
  $isAccept = true;
} while(false);

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
    <?php }else if(count($errors) > 0){ ?> 
    <p>
      エラー
      <ul>
      <?php
      foreach ($errors as $e) {
        echo "<li>", $e, "</li>";
      }
      ?>
      </ul>
      <br>
      在庫数：<?= $zaiko ?><br>
      注文数：<?= $quantity?><br>
    </p>
    <?php } ?> 

    <a href="cart.php">買い物かごを確認する</a><br>
    <a href="index.php">TOPへ戻る</a>
  </body>
</html>
