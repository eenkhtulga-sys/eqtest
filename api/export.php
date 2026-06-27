<?php
include 'db.php';

$stmt = $pdo->query("SELECT id, name, school, phone, email, score, created_at FROM eq_results ORDER BY id DESC");
$results = $stmt->fetchAll();

$filename = "eq_results_" . date('Y-m-d') . ".csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// UTF-8 BOM
echo "\xEF\xBB\xBF";

$output = fopen("php://output", "w");

// Excel баганаар салгах
fputcsv($output, [
    "ID",
    "Овог Нэр",
    "Сургууль",
    "Утас",
    "Мэйл хаяг",
    "Оноо",
    "Бөглөсөн огноо"
], ",");

foreach ($results as $row) {

    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['school'],
        $row['phone'],
        $row['email'],
        $row['score'],
        $row['created_at']
    ], ",");
}

fclose($output);
exit();
?>