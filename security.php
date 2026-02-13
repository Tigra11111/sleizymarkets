<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

// Проверяем PIN
$hasPin = false;
$pinVerified = isset($_SESSION['pin_verified']) && $_SESSION['pin_verified'] == $user['id'];

// Подключение к БД
$host = 'localhost';
$dbname = 'sleizy_market';
$username = 'root';
$password = '';
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// Проверяем наличие PIN
$stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
$stmt->execute([$user['id']]);
$pinData = $stmt->fetch();
$hasPin = ($pinData && !empty($pinData['pin_code']));

// Если есть PIN и он не верифицирован - перенаправляем
if ($hasPin && !$pinVerified) {
    header('Location: res.php'); // Вернемся на главную, там есть PIN модалка
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Безопасность - Sleizy Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Безопасность</h1>
        <div class="card mt-3">
            <div class="card-body">
                <h4>Смена пароля</h4>
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label>Текущий пароль</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label>Новый пароль</label>
                        <input type="password" class="form-control" name="new_password" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label>Подтвердите пароль</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Изменить пароль</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h4>PIN-код</h4>
                <?php if ($hasPin): ?>
                <p>PIN-код установлен</p>
                <button class="btn btn-warning" onclick="changePin()">Изменить PIN</button>
                <?php else: ?>
                <p>PIN-код не установлен</p>
                <button class="btn btn-primary" onclick="setPin()">Установить PIN</button>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="res.php" class="btn btn-secondary mt-3">Назад</a>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#changePasswordForm').submit(function(e) {
            e.preventDefault();
            $.post('api.php?action=change_password', $(this).serialize(), function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    location.reload();
                }
            });
        });
        
        function setPin() {
            const pin = prompt('Введите PIN-код (4-6 цифр):');
            if (pin && pin.length >= 4 && pin.length <= 6 && /^\d+$/.test(pin)) {
                const confirmPin = prompt('Подтвердите PIN-код:');
                if (pin === confirmPin) {
                    $.post('api.php?action=set_pin', { pin: pin, confirm_pin: confirmPin }, function(response) {
                        alert(response.message);
                        if (response.status === 'success') {
                            location.reload();
                        }
                    });
                } else {
                    alert('PIN-коды не совпадают!');
                }
            } else {
                alert('PIN-код должен содержать 4-6 цифр!');
            }
        }
        
        function changePin() {
            const currentPin = prompt('Введите текущий PIN-код:');
            if (currentPin) {
                $.post('api.php?action=verify_pin', { pin: currentPin }, function(response) {
                    if (response.status === 'success') {
                        const newPin = prompt('Введите новый PIN-код (4-6 цифр):');
                        if (newPin && newPin.length >= 4 && newPin.length <= 6 && /^\d+$/.test(newPin)) {
                            const confirmPin = prompt('Подтвердите новый PIN-код:');
                            if (newPin === confirmPin) {
                                $.post('api.php?action=set_pin', { pin: newPin, confirm_pin: confirmPin }, function(response) {
                                    alert(response.message);
                                    if (response.status === 'success') {
                                        location.reload();
                                    }
                                });
                            } else {
                                alert('PIN-коды не совпадают!');
                            }
                        } else {
                            alert('PIN-код должен содержать 4-6 цифр!');
                        }
                    } else {
                        alert('Неверный текущий PIN-код!');
                    }
                });
            }
        }
    </script>
</body>
</html>