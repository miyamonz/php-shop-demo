<?php

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
