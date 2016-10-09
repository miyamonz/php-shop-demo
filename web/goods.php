<?php
session_start();
require_once(__DIR__ . "/../util/pdo.php");

if(isset($_GET['goodsid'])) {
  $goodsid = $_GET['goodsid'];
}else{
  $goodsid = "";
  // $goodsid = "";
}
$pdo = getPdo();
$query = <<<EOL
select 
g.id as id,
g.price as price,
g.name as name,
g.size as size,
s.quantity,
b.name as brand_name,
b.country as brand_country
from 
inventory.goods as g ,
inventory.stock as s ,
inventory.brand as b
where g.id = '{$goodsid}' 
and g.id = s.goods_id
and g.brand = b.id 
EOL;
// $query = "select * from inventory.goods"; 
$st = $pdo->query($query);
$row = $st->fetch(PDO::FETCH_ASSOC); 
//echo var_dump($row);

$zaiko = $row['quantity']==0 ? "無し" : $row['quantity'];
//まとめて取るのはよくない。商品自体はあるのに、在庫数が未セットの場合も、商品が無いように見える
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title><?=$row['name']?></title>
<script id="php" zaiko='<?= $zaiko?>'></script>
  </head>
  <body>
    <?php if($row === FALSE){  ?>
    <span>お探しの商品は見つかりませんでした</span>
    <?php }else{ ?>
    <h2></h2>
    <a href="index.php">TOPへ戻る</a>
    <hr>

    <p>商品説明</p>
    <table>
      <tr>
        <td>商品名</td>
        <td><?=$row['name']?></td>
      </tr>
      <tr>
        <td>単価</td>
        <td><?=$row['price']?></td>
      </tr>
      <tr>
        <td>サイズ</td>
        <td><?=$row['size']?></td>
      </tr>
      <tr>
        <td>ブランド</td>
        <td><?=$row['brand_name'] ." (". $row['brand_country'].")"?></td>
      </tr>
      <tr>
        <td>在庫数</td>
       <td><?= $zaiko ?></td>
      </tr>
    </table>
    <hr>
    <form id="chumonFrom" action="added.php" method="post">
      <input type="hidden" name="goodsid" value="<?= $row['id']?>">
      <input id="chumonNum" type="number" name="quantity">
      <input type="submit" value="買い物かごに入れる">
    </form>
    <p id="attention"></p>
    <?php } ?>
  </body>
<script src="./jquery-3.1.1.min.js"></script>
<script src="./goods.js"></script>
</html>
