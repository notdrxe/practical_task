<?php
require 'db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Неверный запрос: отсутствует ID.";
    exit;
}

$publicId = $_GET['id'];


$stmt = $pdo->prepare("SELECT filename FROM files WHERE public_id = ?");
$stmt->execute([$publicId]);
$filename = $stmt->fetchColumn();

if (!$filename || !file_exists("uploads/$filename")) {
    http_response_code(404);
    echo "Файл не найден.";
    exit;
}


header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize("uploads/$filename"));
readfile("uploads/$filename");
exit;
