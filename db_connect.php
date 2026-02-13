<?php
$host = 'localhost';
$dbname = 'sleizy_market'; // ТВОЯ БД
$username = 'root';      // ТВОЙ ПОЛЬЗОВАТЕЛЬ
$password = '';          // ТВОЙ ПАРОЛЬ

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Ошибка БД']));
}
?>