<?php
// エラーレポート表示
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続設定
$host = 'localhost';
$dbname = 'matching_app';
$username = 'root';  // XAMPPのデフォルトユーザー
$password = '';      // 設定している場合は入力

try {
    // PDOを使用してデータベース接続
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 初期条件
    $min_age = isset($_GET['min_age']) ? (int)$_GET['min_age'] : 18; // 最小年齢
    $max_age = isset($_GET['max_age']) ? (int)$_GET['max_age'] : 100; // 最大年齢
    $selected_job = isset($_GET['job']) ? $_GET['job'] : ''; // 職業選択

    // 職業一覧を取得するクエリ
    $job_stmt = $conn->query("SELECT DISTINCT job FROM profiles");
    $jobs = $job_stmt->fetchAll(PDO::FETCH_COLUMN);

    // SQLクエリ: profilesテーブルから絞り込み条件に合致するデータを取得
    $sql = "SELECT nickname, profile_image, age, gender, height, job 
            FROM profiles 
            WHERE age BETWEEN :min_age AND :max_age";
    if ($selected_job) {
        $sql .= " AND job = :job";
    }
    $sql .= " ORDER BY age ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':min_age', $min_age, PDO::PARAM_INT);
    $stmt->bindParam(':max_age', $max_age, PDO::PARAM_INT);
    if ($selected_job) {
        $stmt->bindParam(':job', $selected_job, PDO::PARAM_STR);
    }
    $stmt->execute();
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マッチングアプリ ホーム</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <h1>プロフィール一覧</h1>

        <!-- 絞り込みフォーム -->
        <form method="GET" action="" class="filter-form">
            <!-- 年齢範囲選択 -->
            <label for="min_age">年齢:</label>
            <select name="min_age" id="min_age"></select>
            <span>~</span>
            <select name="max_age" id="max_age"></select>

            <!-- 職業選択 -->
            <label for="job">職業:</label>
            <select name="job" id="job">
                <option value="">すべて</option>
                <?php foreach ($jobs as $job): ?>
                    <option value="<?= htmlspecialchars($job); ?>" 
                        <?= $selected_job === $job ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($job); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">絞り込み</button>
        </form>

        <!-- プロフィール表示 -->
        <div class="profile-grid">
            <?php foreach ($profiles as $profile): ?>
                <div class="profile-card">
                    <!-- 画像表示 -->
                    <?php if (!empty($profile['profile_image'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($profile['profile_image']); ?>" 
                             alt="<?= htmlspecialchars($profile['nickname']); ?>">
                    <?php else: ?>
                        <p>画像がありません</p>
                    <?php endif; ?>

                    <!-- ユーザー情報表示 -->
                    <h2><?= htmlspecialchars($profile['nickname']); ?></h2>
                    <p><?= htmlspecialchars($profile['age']); ?>歳</p>
                    <p>性別: <?= htmlspecialchars($profile['gender']); ?></p>
                    <p>身長: <?= htmlspecialchars($profile['height']); ?>cm</p>
                    <p>職業: <?= htmlspecialchars($profile['job']); ?></p>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>

    <!-- JavaScriptで年齢セレクトボックス生成 -->
    <script>
        function createAgeOptions() {
            const minAgeSelect = document.getElementById('min_age');
            const maxAgeSelect = document.getElementById('max_age');

            for (let i = 18; i <= 100; i++) {
                const minOption = document.createElement('option');
                minOption.value = i;
                minOption.textContent = i + "歳";
                minAgeSelect.appendChild(minOption);

                const maxOption = document.createElement('option');
                maxOption.value = i;
                maxOption.textContent = i + "歳";
                maxAgeSelect.appendChild(maxOption);
            }

            // 選択状態の保持
            const urlParams = new URLSearchParams(window.location.search);
            minAgeSelect.value = urlParams.get('min_age') || 18;
            maxAgeSelect.value = urlParams.get('max_age') || 100;
        }
        window.onload = createAgeOptions;
    </script>
</body>
</html>
