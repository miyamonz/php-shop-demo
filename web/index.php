<?php
session_start();
require_once(__DIR__ . "/../util/pdo.php");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h1>SHOP</h1>

    <?php
    if(is_null($_SESSION['cart'])) $_SESSION['cart'] =[];
    $kensu = $_SESSION['cart'] ? count($_SESSION['cart']) : 0;
    ?>
    <span>買い物かごには<?= $kensu?>件入っています</span>
<a href="cart.php">確認する</a>
    <a href="add.php">add</a>

<h2>商品リスト</h2>
    <?php
        
$pdo = getPdo(); 
$table = "inventory.goods";
        $columns = getColmuns($pdo,$table);
        $rows    = getRows($pdo, $table);
// echo var_dump($rows);
    ?>

<table>
    <tr>
<?php
        $th = ['id', 'name', 'price'];
        $th2 = ['id', "商品名", '値段'];
        ?>
        <?php for($i=0; $i<count($th2); $i++) { ?>
        <td><?= $th2[$i]?></td>
        <?php } ?>
    </tr>

    <?php for($i=0; $i<count($rows); $i++) { ?>
    <tr>
        <?php

            for($j=0; $j < count($th); $j++){
                echo "<td>",$rows[$i][$th[$j]],"</td>",PHP_EOL;
            }
        ?>
            <td><a href="goods.php?goodsid=<?= $rows[$i]['id']?>">商品ページへ</a></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
