<?php
include 'db.php';

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

$stmt = $pdo->query("
    SELECT id, name, school, phone, email, score, created_at 
    FROM eq_results 
    ORDER BY id DESC
");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle("EQ Results");


// Толгой мөр
$headers = [
    "ID",
    "Овог Нэр",
    "Сургууль",
    "Утас",
    "Мэйл хаяг",
    "Оноо",
    "Бөглөсөн огноо"
];

$sheet->fromArray($headers, null, "A1");


// Өгөгдөл
$row = 2;

foreach ($results as $data) {

    $sheet->fromArray([
        $data['id'],
        $data['name'],
        $data['school'],
        $data['phone'],
        $data['email'],
        $data['score'],
        $data['created_at']
    ], null, "A".$row);

    $row++;
}


// Гарчгийн загвар
$sheet->getStyle("A1:G1")->applyFromArray([
    'font' => [
        'bold' => true
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER
    ]
]);


// Бүх баганын auto width
foreach (range('A','G') as $column) {

    $sheet->getColumnDimension($column)
          ->setAutoSize(true);

}


// Хүснэгтийн бүх хэсэгт текст тохируулах
$sheet->getDefaultRowDimension()
      ->setRowHeight(20);


$filename = "eq_results_" . date('Y-m-d') . ".xlsx";


header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment; filename="'.$filename.'"'
);

header('Cache-Control: max-age=0');


$writer = new Xlsx($spreadsheet);

$writer->save("php://output");

exit;
?>