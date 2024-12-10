<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベース接続設定
$host = 'localhost';
$dbname = 'matching_app';
$username = 'root';
$password = '';

// データベース接続
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// フォームが送信されたかチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // フォームからデータ取得
        $nickname = $_POST['nickname'];
        $age = (int)$_POST['age'];
        $gender = $_POST['gender'];
        $height = (int)$_POST['height'];
        $job = $_POST['job'];

        // 画像アップロード処理
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['profileImage']['tmp_name'];
            $imageData = file_get_contents($imageTmpPath);

            // SQLクエリ準備
            $stmt = $conn->prepare("INSERT INTO profiles (nickname, profile_image, age, gender, height, job) VALUES (:nickname, :profile_image, :age, :gender, :height, :job)");
            
            // パラメータをバインド
            $stmt->bindParam(':nickname', $nickname);
            $stmt->bindParam(':profile_image', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':age', $age, PDO::PARAM_INT);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':height', $height, PDO::PARAM_INT);
            $stmt->bindParam(':job', $job);

            // クエリ実行
            if ($stmt->execute()) {
                echo "データが正常に登録されました！";
            } else {
                echo "登録失敗";
            }
        } else {
            echo "画像のアップロードに失敗しました。";
        }
    } catch(PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "不正なリクエストメソッドです。";
}

// 接続を閉じる
$conn = null;
?>