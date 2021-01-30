<?php
$password = '';
$dsn = 'mysql:dbname=laravel_news;host=127.0.0.1';
$user = 'root';
try {
    $db = new PDO($dsn,$user,$password);
} catch(PDOException $e) {
    echo '接続エラー:' . $e->getMessage();
}
?>