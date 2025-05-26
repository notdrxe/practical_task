<?php
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$userId = getUserId($pdo);


if (isset($_GET['delete'])) {
    $fileId = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$fileId, $userId]);
    $file = $stmt->fetchColumn();

    if ($file && file_exists("uploads/$file")) {
        unlink("uploads/$file");
        $pdo->prepare("DELETE FROM files WHERE id = ? AND user_id = ?")->execute([$fileId, $userId]);
    }
    header("Location: history.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, filename, uploaded_at, public_id FROM files WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$userId]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>История файлов</title>
</head>
<body>
    <h1>История загрузок</h1>

    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <strong><?= htmlspecialchars($file['filename']) ?></strong>
                (<?= $file['uploaded_at'] ?>) —
                <a href="uploads/<?= urlencode($file['filename']) ?>" download>скачать</a> |
                <a href="download.php?id=<?= $file['public_id'] ?>" target="_blank">поделиться</a> |
                <a href="?delete=<?= $file['id'] ?>" onclick="return confirm('Удалить файл?')">удалить</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <hr>
    <a href="index.php">Загрузка</a> |
    <a href="profile.php">Профиль</a> |
    <a href="logout.php">Выйти</a>
</body>
</html>
