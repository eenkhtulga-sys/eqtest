<?php
$host = $_ENV['DB_HOST'] ?? 'localhost';
$db   = $_ENV['DB_NAME'] ?? 'sstest';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Хэрэв асуултууд ороогүй (хоосон) байвал 40 асуулт автоматаар үүсгэх бэлдэц
$count = $pdo->query("SELECT COUNT(*) FROM eq_questions")->fetchColumn();
if ($count == 0) {
    $stmt = $pdo->prepare("INSERT INTO eq_questions (question_text) VALUES (?)");
    for ($i = 1; $i <= 40; $i++) {
        $stmt->execute(["Би өөрийн зан төлөв, сэтгэл хөдлөлөө бүрэн удирдаж чаддаг. (Асуулт №$i)"]);
    }
}
?>