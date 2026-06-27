<?php
    include 'db.php';

    if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename=EQ_Test_Results_' . date('Y-m-d') . '.xls');
    
    echo "\xEF\xBB\xBF"; 
    
    echo "ID\\tНэр\tСургууль\tУтас\tМэйл хаяг\tАвсан оноо\tОгноо\n";
    
    $stmt = $pdo->query('SELECT * FROM eq_results ORDER BY created_at DESC');
    while ($row = $stmt->fetch()) {
        echo $row['id'] . "\t" . 
             $row['name'] . "\t" . 
             $row['school'] . "\t" . 
             $row['phone'] . "\t" . 
             $row['email'] . "\t" . 
             $row['score'] . "\t" . 
             $row['created_at'] . "\n";
    }
    exit;
}
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $new_q = htmlspecialchars(trim($_POST['question_text'] ?? ''));
    if (!empty($new_q)) {
        $ins = $pdo->prepare("INSERT INTO eq_questions (question_text) VALUES (?)");
        $ins->execute([$new_q]);
        $msg = "Асуулт амжилттай нэмэгдлээ!";
    }
}
$results = $pdo->query('SELECT * FROM eq_results ORDER BY created_at DESC')->fetchAll();
$total_questions = $pdo->query("SELECT COUNT(*) FROM eq_questions")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ хэсэг - EQ Тест</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans min-h-screen p-6">

    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800">EQ Тестийн Удирдах Самбар (Админ)</h1>
                <p class="text-sm text-slate-500 mt-1">Системд нийт <span class="font-bold text-indigo-600"><?php echo $total_questions; ?></span> асуулт идэвхтэй байна.</p>
            </div>
            <div class="flex gap-2">
                <a href="?export=excel" class="bg-emerald-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                    📊 Excel Татах
                </a>
                <a href="/" target="_blank" class="bg-slate-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-slate-700 transition">
                    Нүүр хуудас
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-xl shadow-md p-6 h-fit">
                <h2 class="text-lg font-bold text-slate-700 mb-4 border-b pb-2">➕ Шинэ асуулт нэмэх</h2>
                
                <?php if (!empty($msg)): ?>
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-2 rounded-md text-sm mb-4">
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Асуултын текст:</label>
                        <textarea name="question_text" required rows="4" class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" placeholder="Жишээ нь: Би өөрийнхөө сэтгэл хөдлөлийг маш сайн хянадаг."></textarea>
                    </div>
                    <button type="submit" name="add_question" class="w-full bg-indigo-600 text-white p-2.5 rounded-lg font-bold hover:bg-indigo-700 transition text-sm">
                        Асуулт хадгалах
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-slate-700 mb-4 border-b pb-2">👥 Тест өгсөн хүүхдүүдийн жагсаалт</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 text-left uppercase text-[11px] font-bold tracking-wider">
                                <th class="p-3 border-b">Овог Нэр</th>
                                <th class="p-3 border-b">Сургууль</th>
                                <th class="p-3 border-b">Холбоо барих</th>
                                <th class="p-3 border-b text-center">Оноо</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-700">
                            <?php if (count($results) > 0): ?>
                                <?php foreach ($results as $row): 
                                    // Онооны өнгөний үнэлгээ
                                    if ($row['score'] >= 160) {
                                        $badge = "bg-emerald-50 text-emerald-700 border-emerald-200";
                                    } elseif ($row['score'] >= 120) {
                                        $badge = "bg-blue-50 text-blue-700 border-blue-200";
                                    } elseif ($row['score'] >= 80) {
                                        $badge = "bg-amber-50 text-amber-700 border-amber-200";
                                    } else {
                                        $badge = "bg-rose-50 text-rose-700 border-rose-200";
                                    }
                                ?>
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-3">
                                            <div class="font-bold text-slate-900"><?php echo htmlspecialchars($row['name']); ?></div>
                                        </td>
                                        <td class="p-3 text-slate-600"><?php echo htmlspecialchars($row['school']); ?></td>
                                        <td class="p-3 text-xs space-y-0.5">
                                            <div class="font-medium text-slate-700">📱 <?php echo htmlspecialchars($row['phone']); ?></div>
                                            <div class="text-slate-400">✉️ <?php echo htmlspecialchars($row['email']); ?></div>
                                        </td>
                                        <td class="p-3 text-center">
                                            <span class="px-2.5 py-1 rounded-full border text-xs font-bold <?php echo $badge; ?>">
                                                <?php echo $row['score']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400 italic">Одоогоор тест өгсөн хүүхэд алга байна.</td>
                                
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>