<?php require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Профиль</h2>
<p>Пользователь: <?= htmlspecialchars($_SESSION['username']) ?></p>
<a href="index.php">Загрузка</a> | <a href="history.php">История</a> | <a href="logout.php">Выйти</a>
