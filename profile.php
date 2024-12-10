<?php
// エラーレポート表示
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続設定 (省略)

// ユーザーIDを取得
$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($userId) {
    // ユーザー情報を取得
    $stmt = $conn->prepare("SELECT * FROM profiles WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($profile) {
        // プロフィール情報を表示するHTMLを生成
        $profileHtml = '
            <div class="profile-detail">
                <!-- 画像表示 -->
                <div class="profile-image">';
        if (!empty($profile['profile_image'])) {
            $profileHtml .= '<img src="data:image/jpeg;base64,' . base64_encode($profile['profile_image']) . '">';
        } else {
            $profileHtml .= '<p>画像がありません</p>';
        }
        $profileHtml .= '</div>
                <!-- ユーザー情報表示 -->
                <h2>' . htmlspecialchars($profile['nickname']) . '</h2>
                <p>年齢: ' . htmlspecialchars($profile['age']) . '歳</p>
                <p>性別: ' . htmlspecialchars($profile['gender']) . '</p>
                <p>身長: ' . htmlspecialchars($profile['height']) . 'cm</p>
                <p>職業: ' . htmlspecialchars($profile['job']) . '</p>
            </div>
        ';
    } else {
        $profileHtml = '<p>ユーザーが見つかりませんでした。</p>';
    }
} else {
    $profileHtml = '<p>ユーザーIDが指定されていません。</p>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール詳細</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <h1>プロフィール詳細</h1>
        <?= $profileHtml; ?>
    </div>
</body>
</html>