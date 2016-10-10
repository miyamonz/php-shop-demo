<?php
require_once(__DIR__."/cartUtil.php");

function getPdo(){
  $user = "testuser";
  $password = "test";
  $dbName = "testdb";
  $host = "localhost:8889";
  $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

  try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // echo "データベース{$dbName}に接続しました。";
    return $pdo;
  }catch(Exception $e){
    echo "error";
    echo $e->getMessage();
  }
}

function getRows($pdo, $tableName) {
  $sth = $pdo->prepare('SELECT * from ' . $tableName);
  $sth->execute();
  $rows = [];
  while( $row = $sth->fetch(PDO::FETCH_ASSOC) ){
    $rows[] = $row;
  }
  return $rows;
}

function getColmuns($pdo, $tableName) {
  $sth = $pdo->query("SELECT * from {$tableName}");
  $columns = [];
  for($i=0; $i<$sth->columnCount(); $i++){
    $meta = $sth->getColumnMeta($i);
    $columns[] = $meta['name'];
  }
  return $columns;
}

function getById($goodsid) {
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
  $sth = $pdo->query($query);
  $rows = [];
  while( $row = $sth->fetch(PDO::FETCH_ASSOC) ){
    $rows[] = $row;
  }
  if(count($rows) > 0) return $rows[0];
  else return false;
}
function getDemoCart(){
  $r = [];
  $r[] = ["goodsid"=>"abo01", "quantity"=>2];
  $r[] = ["goodsid"=>"abo02", "quantity"=>5];
  return $r;
}
