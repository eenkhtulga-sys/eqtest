<?php
include 'db.php';

$stmt = $pdo->query("SELECT id, name, school, phone, email, score, created_at FROM eq_results ORDER BY id DESC");
$results = $stmt->fetchAll();

$filename = "eq_results_" . date('Y-m-d') . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF";

echo "ID" . "\t" . 
     "Овог Нэр" . "\t" . 
     "Сургууль" . "\t" . 
     "Утас" . "\t" . 
     "Мэйл хаяг" . "\t" . 
     "Оноо" . "\t" . 
     "Бөглөсөн огноо" . "\n";

foreach ($results as $row) {
    echo $row['id'] . "\t" . 
         $row['name'] . "\t" . 
         $row['school'] . "\t" . 
         $row['phone'] . "\t" . 
         $row['email'] . "\t" . 
         $row['score'] . "\t" . 
         $row['created_at'] . "\n";
}
exit();
?>