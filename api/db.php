<?php

$host     = 'mysql-37dc3805-ikhzasag-2a09.d.aivencloud.com'; 
$port     = '21272';      
$db       = 'sstest';
$user     = 'avnadmin';
$pass     = 'AVNS_Y46KyuHT9cudJFWcInl';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

// PDO холболтын нэмэлт тохиргоо (SSL-ийг идэвхжүүлэх)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, 
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Өгөгдлийн сантай холбогдоход алдаа гарлаа: " . $e->getMessage());
}
?>