<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マッチングアプリ登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>プロフィール登録</h1>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="nickname">ニックネーム:</label>
            <input type="text" id="nickname" name="nickname" placeholder="例: タロウ" maxlength="20" required>
            
            <label for="profileImage">プロフィール画像:</label>
            <input type="file" id="profileImage" name="profileImage" accept="image/*" required>
            
            <label for="age">年齢:</label>
            <select id="age" name="age" required></select>
            
            <label for="gender">性別:</label>
            <select id="gender" name="gender" required>
                <option value="male">男性</option>
                <option value="female">女性</option>
                <option value="other">その他</option>
            </select>
            
            <label for="height">身長 (cm):</label>
            <select id="height" name="height" required></select>
            
            <label for="job">職業:</label>
            <select id="job" name="job" required>
                <option value="kaisyain">会社員</option>
                <option value="keieisya">経営者</option>
                <option value="engineer">エンジニア</option>
            </select>
            
            <button type="submit">登録する</button>
        </form>
    </div>

    <script>
        // 年齢セレクトボックス生成
        const ageSelect = document.getElementById('age');
        for (let i = 18; i <= 100; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i + "歳";
            ageSelect.appendChild(option);
        }
        
        // 身長セレクトボックス生成
        const heightSelect = document.getElementById('height');
        for (let i = 140; i <= 200; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i + "cm";
            heightSelect.appendChild(option);
        }
    </script>
</body>
</html>