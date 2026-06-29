
<?php

include 'db.php';

$stmt = $pdo->query("SELECT * FROM eq_questions ORDER BY id ASC");
$questions = $stmt->fetchAll();

$final_score = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_test'])) {
    
    // Хувьсагчдыг зөв тохируулж авлаа
    $name = htmlspecialchars($_POST['name'] ?? '');
    $school    = htmlspecialchars($_POST['school'] ?? '');
    $phone     = htmlspecialchars($_POST['phone'] ?? '');
    $email     = htmlspecialchars($_POST['email'] ?? '');
    
    $total_score = 0;
    $has_answers = false; 

    foreach ($questions as $q) {
        $q_id = $q['id'];
        if (isset($_POST["q_$q_id"])) {
            $total_score += (int)$_POST["q_$q_id"];
            $has_answers = true;
        }
    }
    
    if ($has_answers && !empty($name)) {
        $ins = $pdo->prepare('INSERT INTO eq_results (name, school, phone, email, score) VALUES (?, ?, ?, ?, ?)');
        $ins->execute([$name, $school, $phone, $email, $total_score]);
        $final_score = $total_score;
    }
}
?>

<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Зөөлөн пастел уусалттай дэвсгэр */
        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #fff1f2 50%, #e0f2fe 100%);
        }
    </style>
</head>
<body class="font-sans min-h-screen pb-12 antialiased">
    <div class="max-w-4xl mx-auto mt-10 p-10 bg-white rounded-3xl shadow-2xl shadow-indigo-100 border-2 border-indigo-50">
        <h1 class="text-4xl font-extrabold text-center bg-gradient-to-r from-blue-900 via-indigo-900 to-rose-600 bg-clip-text text-transparent mb-8">Сэтгэл хөдлөлийн оюун ухаан буюу EQ тест</h1>
        
        <?php if ($final_score !== null): 
            // Онооны мужаас хамаарч түвшин, тайлбар, өнгийг тодорхойлно
            if ($final_score >= 160) {
                $level = "Төгс Найдвартай Манлайлагч 🌟 (Маш өндөр EQ)";
                $color = "text-emerald-950 bg-emerald-100/60 border-emerald-200";
                $btn_color = "bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700";
                $desc = "
                    <p class='font-bold text-lg mb-2 text-emerald-950'>Та бол эргэн тойрныхоо хүмүүсийн 'Амар амгалан' юм!</p>
                    Та өөрийнхөө сэтгэл зүйг маш сайн удирдахаас гадна, бусдын хэлээгүй гуниг, ил гаргаагүй баяр баясгаланг ч зүрхээрээ мэдэрч чаддаг ховорхон чадвартай хүн байна. Шуургатай өдөр ч өөрийн дотоод амар амгаланг хадгалж, бусдад гэрэл түгээдэг таны энэ чанар бол төрөлхийн ухааных юм. <br><br>
                    <span class='font-semibold text-emerald-950'>🌱 Зөвлөгөө:</span> Та үргэлж бусдыг сонсож, тэднийг дэмждэг учраас заримдаа өөрийнхөө сэтгэл хөдлөлийг дотроо түгжиж, ядардаг талтай. Бусдыг хайрлахын зэрэгцээ өөрийнхөө 'сэтгэл зүйн аяга'-ыг ч бас дүүргэж, амрахад цаг гаргаж байгаарай. Таны дэргэд байгаа хүмүүс үнэхээр азтай!
                ";
            } elseif ($final_score >= 120) {
                $level = "Дулаан Сэтгэлт Тэнцвэржүүлэгч 👍 (Сайн EQ)";
                $color = "text-blue-950 bg-blue-100/60 border-blue-200";
                $btn_color = "bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700";
                $desc = "
                    <p class='font-bold text-lg mb-2 text-blue-950'>Таны зүрх сэтгэл маш эрүүл, дулаахан хэмнэлээр цохилж байна.</p>
                    Та амьдралын баяр баясгалан, гуниг гутралын тэнцвэрийг маш сайн олж чадсан хүн юм. Хэцүү зүйл тохиолдсон ч хэсэг хугацааны дараа өөрийгөө зоригжуулаад босоод ирэх дотоод хүч танд бий. Хүмүүстэй ойлголцож, тэднийг байгаагаар нь хүлээж авахыг хичээдэг таны зан чанар амьдралыг тань илүү гэрэлтэй болгодог.<br><br>
                    <span class='font-semibold text-blue-950'>🌱 Зөвлөгөө:</span> Заримдаа сэтгэл хөдлөлөө хэт барьж, логикоор шийдэх гэж хичээдэг байж магадгүй. Сэтгэл чинь өвдөж байвал уйлж, баярлаж байвал чангаар инээж, мэдрэмжүүдээ илүү чөлөөтэй гаргаж байгаарай. Та маш зөв замаар алхаж байна, өөртөө улам их итгээрэй!
                ";
            } elseif ($final_score >= 80) {
                $level = "Эрэлхийлэгч Гүн Сэтгэгч ⚠️ (Анхаарах түвшин)";
                $color = "text-amber-950 bg-amber-100/60 border-amber-200";
                $btn_color = "bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700";
                $desc = "
                    <p class='font-bold text-lg mb-2 text-amber-950'>Та бол маш нандин, мэдрэмтгий зүрх сэтгэлтэй хүн юм.</p>
                    Та амьдралыг, хүмүүсийг маш гүн гүнзгийгээр мэдэрдэг учраас заримдаа эргэн тойрны үйл явдал, бусдын хэлсэн үг таны сэтгэлд хэтэрхий хүнд тусдаг байж магадгүй. Сэтгэл хөдлөлөө хянаж чадалгүй уурлах эсвэл өөрийгөө буруутгах үе байдаг ч энэ нь таныг муу хүн гэсэн үг огт биш. Та зүгээр л өөрийн дотоод 'Би'-гээ хайж яваа аялагч юм.<br><br>
                    <span class='font-semibold text-amber-950'>🌱 Зөвлөгөө:</span> Сэтгэл хөдлөл бол цаг агаар шиг түр зуурынх гэдгийг санаарай. Шуурга дэгдсэн ч дараа нь заавал нар гардаг. Сэтгэл чинь үймрэх үед гүнзгий амьсгаа авч, өөртөө 'Зүгээр дээ, би хичээж байна' гэж хэлж сураарай. Өөрийгөө хайрлах хайраа хэзээ ч бүү багасгаарай.
                ";
            } else {
                $level = "Ил гаргаагүй Тэвчээртэн 🚨 (Маш бага EQ)";
                $color = "text-rose-950 bg-rose-100/60 border-rose-200";
                $btn_color = "bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700";
                $desc = "
                    <p class='font-bold text-lg mb-2 text-rose-950'>Таны сэтгэл одоо маш их ядарсан, магадгүй тусламж эрэн хашхирч байж болох юм.</p>
                    Одоо танд маш хэцүү байгааг, сэтгэл хөдлөлөө удирдахад үнэхээр хүч хүрэхгүй байгааг энэ оноо илтгэж байна. Магадгүй та дэндүү урт хугацаанд хэтэрхий гэж их ачааг ганцаараа үүрч, тэвчиж ирсэн байх. Бусдад гомдох, уурлах, эсвэл бүх зүйлээс тусгаарлагдмал мэт мэдрэгдэх нь таны буруу биш, зүгээр л таны сэтгэлд дулаан дулаахан тэврэлт, амралт хэрэгтэй байна.<br><br>
                    <span class='font-semibold text-rose-950'>🌱 Зөвлөгөө:</span> Бүх зүйлийг ганцаараа даван туулах гэж бүү зүтгэ. Таныг сонсох, танд туслахыг хүсдэг хүмүүс заавал байгаа. Сэтгэлийн гүнд байгаа уураа, гунигаа хуваалцаж сураарай. Өнөөдөр таны сэтгэл харанхуй байгаа ч, маргааш заавал гэрэл асна. Та бол үнэхээр хүчтэй хүн шүү!
                ";
            }
        ?>
            <div class="border-2 rounded-2xl p-10 text-center shadow-xl <?php echo $color; ?> my-8">
                <p class="text-base font-semibold text-slate-800">Хүндэт <span class="font-bold text-slate-900"><?php echo htmlspecialchars($name); ?></span> Таны EQ тестийн дүн бэлэн боллоо</p>
                
                <p class="text-5xl font-black mt-4 tracking-tight text-slate-900">Нийт оноо: <?php echo $final_score; ?> / 200</p>
                
                <div class="mt-5 inline-block font-extrabold px-7 py-3 rounded-full border-2 text-xs uppercase tracking-wider <?php echo $color; ?> shadow-md">
                    <?php echo $level; ?>
                </div>
                
                <div class="mt-8 text-left text-base max-w-3xl mx-auto text-slate-800 leading-relaxed bg-white/70 p-7 rounded-xl border border-slate-200 shadow-sm">
                    <?php echo $desc; ?>
                </div>
                
                <a href="./" class="mt-10 inline-block text-white px-10 py-4 rounded-xl font-bold tracking-wide transition transform hover:-translate-y-1 shadow-lg active:translate-y-0 <?php echo $btn_color; ?>">
                    🔄 Дахин шалгаж үзэх
                </a>
            </div>
        <?php else: ?>

            <div id="progress-container" class="mb-10 hidden">
                <div class="flex justify-between text-xs font-bold text-indigo-700 mb-2">
                    <span id="progress-text">Явц: 0% (1/8 Алхам)</span>
                    <span class="text-slate-500">Нийт 40 асуулт</span>
                </div>
                <div class="w-full bg-indigo-50/70 h-3 rounded-full border-2 border-indigo-100 overflow-hidden">
                    <div id="progress-bar" class="bg-gradient-to-r from-blue-600 to-indigo-800 h-full w-0 transition-all duration-500 rounded-full"></div>
                </div>
            </div>
            
            <form id="eqForm" action="" method="POST" class="space-y-8">
                
                <div id="step-0" class="step-block bg-white p-3 space-y-6">
                    <div class="bg-gradient-to-r from-blue-50 to-pink-50 p-5 rounded-2xl text-blue-950 text-base font-semibold border border-blue-100/50 shadow-inner">
                        ✨ Тестийг эхлүүлэхийн өмнө дараах мэдээллийг үнэн зөв бөглөнө үү.
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2">Нэр:</label>
                            <input type="text" id="name" name="name" required class="w-full p-4 bg-slate-50/70 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition outline-none">
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2">Төгссөн / Сурч буй сургууль:</label>
                            <input type="text" id="school" name="school" required class="w-full p-4 bg-slate-50/70 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition outline-none" placeholder="М1-р сургууль гм...">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2">Утасны дугаар:</label>
                                <input type="tel" id="phone" name="phone" required class="w-full p-4 bg-slate-50/70 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition outline-none" placeholder="88******">
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2">Мэйл хаяг:</label>
                                <input type="email" id="email" name="email" required class="w-full p-4 bg-slate-50/70 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition outline-none" placeholder="example@mail.com">
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="startTest()" class="w-full mt-8 bg-gradient-to-r from-blue-700 to-indigo-900 text-white p-4 rounded-xl font-bold hover:from-blue-800 hover:to-indigo-950 transition transform hover:-translate-y-1 active:translate-y-0 shadow-xl shadow-indigo-100">Тестийг эхлүүлэх</button>
                </div>

                <?php 
                $chunks = array_chunk($questions, 5);
                foreach ($chunks as $chunkIndex => $chunkQuestions): 
                    $stepNumber = $chunkIndex + 1;
                ?>
                    <div id="step-<?php echo $stepNumber; ?>" class="step-block hidden space-y-6">
                        <?php foreach ($chunkQuestions as $q): ?>
                            <div class="border-2 border-slate-100 p-6 rounded-3xl bg-white shadow-md hover:border-indigo-100 transition duration-300">
                                <p class="text-slate-900 font-semibold text-lg mb-5"><?php echo htmlspecialchars($q['id'] . ". " . $q['question_text']); ?></p>
                                
                                <div class="grid grid-cols-5 gap-4 text-center">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label class="cursor-pointer bg-slate-50/70 hover:bg-indigo-50 p-4 rounded-2xl border-2 border-slate-200 block transition relative group">
                                            <input type="radio" name="q_<?php echo $q['id']; ?>" value="<?php echo $i; ?>" class="w-5 h-5 text-indigo-700 border-slate-300 focus:ring-indigo-500">
                                            <span class="block text-sm font-black text-slate-700 group-hover:text-indigo-900 mt-2"><?php echo $i; ?></span>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="flex justify-between items-center pt-6">
                            <?php if ($stepNumber > 1): ?>
                                <button type="button" onclick="prevStep()" class="bg-slate-100 text-slate-700 px-7 py-3 rounded-xl font-bold hover:bg-slate-200 transition border-2 border-slate-200/60">Өмнөх</button>
                            <?php else: ?> <div></div> <?php endif; ?>

                            <?php if ($stepNumber < count($chunks)): ?>
                                <button type="button" onclick="nextStep(<?php echo $stepNumber; ?>)" class="bg-gradient-to-r from-blue-700 to-indigo-900 text-white px-8 py-3 rounded-xl font-bold hover:from-blue-800 hover:to-indigo-950 transition shadow-lg shadow-indigo-100">Дараах</button>
                            <?php else: ?>
                                <button type="submit" name="submit_test" onclick="return validateFinalStep()" class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-9 py-3 rounded-xl font-bold hover:from-emerald-700 hover:to-teal-700 transition shadow-xl shadow-emerald-100">Илгээх (Дуусгах) 🎉</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </form>
        <?php endif; ?>
    </div>

<script>
    let currentStep = 0;
    const totalSteps = <?php echo count($chunks); ?>;

    function startTest(){
        const name = document.getElementById('name').value.trim();
        const school = document.getElementById('school').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();

        if(!name || !school || !phone || !email){
            alert("Мэдээллээ гүйцэт бөглөнө үү!");
            return;
        }

        document.getElementById('step-0').classList.add('hidden');
        document.getElementById('progress-container').classList.remove('hidden');
        
        currentStep = 1;
        showStep(currentStep);
    }

    function showStep(step) {
        document.querySelectorAll('.step-block').forEach(el => el.classList.add('hidden'));
        
        const activeStep = document.getElementById(`step-${step}`);
        if (activeStep) {
            activeStep.classList.remove('hidden');
        }
        
        updateProgressBar();
        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function nextStep(step) {
        const currentBlock = document.getElementById(`step-${step}`);
        const checkedRadios = currentBlock.querySelectorAll('input[type="radio"]:checked');
        const checkedNames = new Set();
        checkedRadios.forEach(r => checkedNames.add(r.name));

        if(checkedNames.size < 5) {
            alert("Энэ хуудасны бүх асуултад хариулна уу!");
            return;
        }

        currentStep++;
        showStep(currentStep);
    }

    function prevStep(){
        if(currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function validateFinalStep() {
        const currentBlock = document.getElementById(`step-${totalSteps}`);
        const checkedRadios = currentBlock.querySelectorAll('input[type="radio"]:checked');
        const checkedNames = new Set();
        checkedRadios.forEach(r => checkedNames.add(r.name));
        
        if(checkedNames.size < 5) {
            alert("Сүүлийн хуудасны бүх асуултад хариулна уу!");
            return false;
        }
        return true;
    }

    function updateProgressBar(){
        if(currentStep > 0) {
            const percentage = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = percentage + '%';
            document.getElementById('progress-text').innerText = `Явц: ${Math.round(percentage)}% (${currentStep}/${totalSteps} Алхам)`;
        }
    }
</script>
</body>
</html>

```
