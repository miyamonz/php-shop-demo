<?php
session_start();
require_once(__DIR__ . "/../util/pdo.php");

if(isset($_POST['goodsid'])) {
    $goodsid = $_POST['goodsid'];
}else{
    $goodsid = "abo02";
    // $goodsid = "";
}
$pdo = getPdo();
$query = <<<EOL
select 
g.id as id,
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
echo var_dump($row);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?=$row['name']?></title>
</head>
<body>
<?php if($row === FALSE){  ?>
<span>お探しの商品は見つかりませんでした</span>
<?php }else{ ?>
<h2></h2>
<p>商品説明</p>
<table>
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
        <?php
            $zaiko = $row['quantity']==0 ? "無し" : $row['quantity'];
        ?>
        <td><?= $zaiko ?></td>
    </tr>
</table>
<?php } ?>
</body>
</html>
