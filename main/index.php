<?php
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES['file']['name']);
    $target = "uploads/" . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $userId = getUserId($pdo);
        $publicId = bin2hex(random_bytes(16));

        $stmt = $pdo->prepare("INSERT INTO files (filename, user_id, public_id) VALUES (?, ?, ?)");
        $stmt->execute([$filename, $userId, $publicId]);

        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $link = $baseUrl . "/download.php?id=" . $publicId;
        $success = "Файл успешно загружен! <br> Ссылка для скачивания: <a href='$link' target='_blank'>$link</a>";
    } else {
        $error = "Ошибка загрузки файла.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Загрузка файла</title>
</head>
<body>
    <h1>Загрузить файл</h1>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Загрузить</button>
    </form>

    <hr>
    <a href="profile.php">Профиль</a> |
    <a href="history.php">История</a> |
    <a href="logout.php">Выйти</a>
</body>
</html>
