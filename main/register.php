<?php require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Пользователь уже существует";
    }
}
?>

<h2>Регистрация</h2>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<form method="post">
    Логин: <input type="text" name="username" required><br>
    Пароль: <input type="password" name="password" required><br>
    <button type="submit">Зарегистрироваться</button>
</form>
<a href="login.php">Войти</a>
