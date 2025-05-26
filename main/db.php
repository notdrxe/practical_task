<?php
session_start();
$pdo = new PDO('mysql:host=MySQL-8.2;dbname=file_share;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getUserId($pdo) {
    if (!isset($_SESSION['username'])) return null;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    return $stmt->fetchColumn();
}
