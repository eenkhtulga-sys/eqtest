<?php
// export.php
include 'db.php';

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Students_RIASEC_Report_" . date('Y-m-d') . ".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

echo "\xEF\xBB\xBF"; 
$students = $pdo->query("SELECT * FROM eq_results ORDER BY id DESC")->fetchAll();
?>
<table border="1">
    <tr style="background-color: #1E3A8A; color: white; font-weight: bold;">
        <th>ID</th>
        <th>Овог Нэр</th>
        <th>Төгссөн Сургууль</th>
        <th>Утасны дугаар</th>
        <th>Мэйл хаяг</th>
        <th>Оноо</th>
        <th>Бүртгүүлсэн огноо</th>
    </tr>
    <?php foreach($students as $s): ?>
    <tr>
        <td><?php echo $s['id']; ?></td>
        <td><?php echo htmlspecialchars($s['name']); ?></td>
        <td><?php echo htmlspecialchars($s['school']); ?></td>
        <td><?php echo htmlspecialchars($s['phone']); ?></td>
        <td><?php echo htmlspecialchars($s['email']); ?></td>
        <td><?php echo $s['score']; ?></td>
        <td><?php echo $s['created_at']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>