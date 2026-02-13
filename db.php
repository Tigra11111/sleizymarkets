<?php
// db_connect.php
$host = 'localhost';
$dbname = 'sleizy_market';  // ИЗМЕНИТЕ НА ВАШУ БД
$username = 'root';       // ИЗМЕНИТЕ НА ВАШЕГО ПОЛЬЗОВАТЕЛЯ
$password = '';           // ИЗМЕНИТЕ НА ВАШ ПАРОЛЬ

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создаем БД если не существует
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE `$dbname`");
    
} catch (PDOException $e) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(['status' => 'error', 'message' => 'Ошибка подключения к БД: ' . $e->getMessage()]));
}
?>