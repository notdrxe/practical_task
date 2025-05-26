<?php require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $hash = $stmt->fetchColumn();

    if ($hash && password_verify($_POST['password'], $hash)) {
        $_SESSION['username'] = $_POST['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<h2>Вход</h2>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<form method="post">
    Логин: <input type="text" name="username" required><br>
    Пароль: <input type="password" name="password" required><br>
    <button type="submit">Войти</button>
</form>
<a href="register.php">Регистрация</a>
