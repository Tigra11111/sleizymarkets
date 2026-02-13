<?php
// Функция получения реального IP
function getRealIp() {
    $ip = '0.0.0.0';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Очищаем от лишнего (может прийти несколько IP через запятую)
    $ip = explode(',', $ip)[0];
    $ip = trim($ip);
    $ip = filter_var($ip, FILTER_VALIDATE_IP);
    
    return $ip ?: '0.0.0.0';
}
header('Content-Type: application/json; charset=utf-8');

session_start();

// ====== ПОДКЛЮЧЕНИЕ К БД ======
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'sleizy_market';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Ошибка БД']));
}
$conn->set_charset('utf8mb4');


// Конфигурация БД
$host = 'localhost';
$dbname = 'sleizy_market';
$username = 'root';
$password = '';




try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Ошибка подключения к базе данных']);
    exit();
}

// Получаем action
$action = $_GET['action'] ?? '';





// Получаем данные запроса
$input = json_decode(file_get_contents('php://input'), true);
if ($input === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST;
}

// Функция для логирования
function addLog($pdo, $userId, $action, $details = null) {
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, action, ip, user_agent, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $action, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '', $details]);
}

// Проверка администратора
function checkAdmin() {
    if (!isset($_SESSION['user'])) {
        return ['status' => 'error', 'message' => 'Требуется авторизация'];
    }
    
    if ($_SESSION['user']['role'] !== 'admin') {
        return ['status' => 'error', 'message' => 'Доступ запрещен'];
    }
    
    return null;
}

function checkPin($pdo, $userId) {
    // Администраторы не требуют проверки PIN
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
        return true;
    }
    
    // Проверяем существование таблицы
    try {
        $tableExists = $pdo->query("SHOW TABLES LIKE 'user_pins'")->fetch();
        
        if (!$tableExists) {
            return true; // Если таблицы нет, пропускаем проверку
        }
        
        $stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
        $stmt->execute([$userId]);
        $pinData = $stmt->fetch();
        
        // Если PIN не установлен, пропускаем проверку
        if (!$pinData || empty($pinData['pin_code'])) {
            return true;
        }
        
        // Проверяем, ввел ли пользователь PIN в этой сессии
        if (!isset($_SESSION['pin_verified']) || $_SESSION['pin_verified'] !== $userId) {
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Ошибка проверки PIN: " . $e->getMessage());
        return true; // При ошибке пропускаем проверку
    }
}

// Проверка сессии пользователя
function checkUserSession() {
    if (!isset($_SESSION['user'])) {
        return ['status' => 'error', 'message' => 'Требуется авторизация'];
    }
    return null;
}

// Основной обработчик
switch ($action) {
    // === РЕГИСТРАЦИЯ И АВТОРИЗАЦИЯ ===
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Метод не поддерживается']);
            break;
        }
        
        $login = trim($input['login'] ?? '');
        $password = trim($input['password'] ?? '');
        $username = trim($input['username'] ?? $login);
        
        if (empty($login) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните все обязательные поля']);
            break;
        }
        
        if (strlen($login) < 3 || strlen($login) > 32) {
            echo json_encode(['status' => 'error', 'message' => 'Логин должен быть от 3 до 32 символов']);
            break;
        }
        
        if (strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Пароль должен быть не менее 6 символов']);
            break;
        }
        
        // Проверяем существование пользователя
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ? OR username = ?");
        $stmt->execute([$login, $username]);
        
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Логин или имя пользователя уже заняты']);
            break;
        }
        
        // Хэшируем пароль
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("
                INSERT INTO users (login, password, username, role, balance, created_at) 
                VALUES (?, ?, ?, 'user', 0.00, NOW())
            ");
            
            $stmt->execute([$login, $passwordHash, $username]);
            $userId = $pdo->lastInsertId();
            
            // Автоматически логиним
            $stmt = $pdo->prepare("SELECT id, login, username, role, balance, banned FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            $_SESSION['user'] = $user;
            $_SESSION['pin_verified'] = $userId; // При регистрации PIN сразу проверен
            
            $pdo->commit();
            
            addLog($pdo, $userId, "Регистрация");
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Регистрация успешна!',
                'user' => $user
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Ошибка регистрации: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка регистрации']);
        }
        break;
        case 'adm_users':
    header('Content-Type: application/json');
    
    // Проверка авторизации
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    // Проверка прав администратора
    if ($_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    try {
        // Подключение к БД (используйте ваше подключение)
        // Если у вас $pdo:
        $stmt = $pdo->query("SELECT id, login, username, role, balance, banned, created_at FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'users' => $users
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }
    exit;
        
   case 'login':
    header('Content-Type: application/json');
    
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? 1 : 0;
    
    if (!$login || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        // Сохраняем в сессию
        $_SESSION['user'] = [
            'id' => $user['id'],
            'login' => $user['login'],
            'username' => $user['username'],
            'role' => $user['role'],
            'balance' => $user['balance'],
            'avatar' => $user['avatar'] ?? null,
            'banned' => $user['banned'] ?? 0
        ];
        
        // Если "запомнить меня" - ставим долгую куку
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + 86400 * 30, '/');
            // Сохраняем токен в БД...
        }
        
        echo json_encode([
            'status' => 'success',
            'user' => $_SESSION['user']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Неверный логин или пароль']);
    }
    exit;

    
case 'register':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Метод не поддерживается']);
        break;
    }
    
    $login = trim($input['login'] ?? '');
    $username = trim($input['username'] ?? '');
    $password = trim($input['password'] ?? '');
    $remember = isset($input['remember']) && $input['remember'] === 'on';
    
    if (empty($login) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Логин и пароль обязательны']);
        break;
    }
    
    // Проверка на существующий логин
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Логин уже занят']);
        break;
    }
    
    // Если имя пользователя не указано, используем логин
    if (empty($username)) {
        $username = $login;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (login, username, password, balance, role, created_at) VALUES (?, ?, ?, 0, 'user', NOW())");
    $stmt->execute([$login, $username, $hashedPassword]);
    
    $userId = $pdo->lastInsertId();
    
    // Получаем данные нового пользователя
    $stmt = $pdo->prepare("SELECT id, login, username, balance, role, banned FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $newUser = $stmt->fetch();
    
    $_SESSION['user'] = $newUser;
    
    // Если выбрано "Запомнить меня"
    if ($remember) {
        // Генерируем токен
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        // Сохраняем в базу данных
        $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $token, $expires]);
        
        // Устанавливаем куки на 30 дней
        $cookieOptions = [
            'expires' => time() + 30 * 24 * 60 * 60,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ];
        
        setcookie('remember_token', $token, $cookieOptions);
        setcookie('user_id', $userId, $cookieOptions);
    }
    
    addLog($pdo, $userId, "Регистрация нового пользователя");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Регистрация успешна' . ($remember ? ' (сессия сохранена)' : ''),
        'user' => $newUser
    ]);
    break;

case 'check_remembered_session':
    if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
        $token = $_COOKIE['remember_token'];
        $userId = (int)$_COOKIE['user_id'];
        
        // Проверяем токен в базе данных
        $stmt = $pdo->prepare("SELECT * FROM remember_tokens WHERE user_id = ? AND token = ? AND expires_at > NOW()");
        $stmt->execute([$userId, $token]);
        $tokenData = $stmt->fetch();
        
        if ($tokenData) {
            // Получаем данные пользователя
            $stmt = $pdo->prepare("SELECT id, login, username, balance, role, banned FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Проверяем бан
                if ($user['banned'] == 1 && $user['ban_expires'] && strtotime($user['ban_expires']) > time()) {
                    // Пользователь забанен
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Аккаунт заблокирован',
                        'user_banned' => 1
                    ]);
                    exit;
                }
                
                $_SESSION['user'] = $user;
                
                // Проверяем наличие PIN
                $stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $pinData = $stmt->fetch();
                
                if (!$pinData || empty($pinData['pin_code'])) {
                    $_SESSION['pin_verified'] = $user['id'];
                }
                
                addLog($pdo, $user['id'], "Автовход по сохраненной сессии");
                
                echo json_encode([
                    'status' => 'success',
                    'user' => $user,
                    'has_pin' => ($pinData && !empty($pinData['pin_code']))
                ]);
                exit;
            }
        } else {
            // Удаляем невалидные куки
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('user_id', '', time() - 3600, '/');
        }
    }
    
    echo json_encode(['status' => 'error', 'message' => 'Нет сохраненной сессии']);
    break;

case 'logout':
    // Удаляем токен из базы, если есть
    if (isset($_COOKIE['remember_token'])) {
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token = ?");
        $stmt->execute([$_COOKIE['remember_token']]);
        
        // Удаляем куки
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('user_id', '', time() - 3600, '/');
    }
    
    // Очищаем сессию
    session_destroy();
    
    echo json_encode(['status' => 'success', 'message' => 'Вы вышли из системы']);
    break;
        
    case 'check_pin_status':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        $userId = $_SESSION['user']['id'];
        $hasPin = false;
        $pinVerified = false;
        
        try {
            // Проверяем существование таблицы
            $tableExists = $pdo->query("SHOW TABLES LIKE 'user_pins'")->fetch();
            
            if ($tableExists) {
                // Проверяем, есть ли PIN у пользователя
                $stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
                $stmt->execute([$userId]);
                $pinData = $stmt->fetch();
                
                $hasPin = ($pinData && !empty($pinData['pin_code']));
                
                // Проверяем, был ли PIN подтвержден
                $pinVerified = isset($_SESSION['pin_verified']) && $_SESSION['pin_verified'] == $userId;
            }
            
            echo json_encode([
                'status' => 'success',
                'has_pin' => $hasPin,
                'pin_verified' => $pinVerified,
                'require_pin' => $hasPin && !$pinVerified
            ]);
        } catch (Exception $e) {
            error_log("Ошибка проверки PIN статуса: " . $e->getMessage());
            echo json_encode([
                'status' => 'success',
                'has_pin' => false,
                'pin_verified' => true,
                'require_pin' => false
            ]);
        }
        break;
        
    case 'verify_pin':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        $pin = trim($input['pin'] ?? '');
        $userId = $_SESSION['user']['id'];
        
        if (empty($pin)) {
            echo json_encode(['status' => 'error', 'message' => 'Введите PIN-код']);
            break;
        }
        
        if (!preg_match('/^\d{4,6}$/', $pin)) {
            echo json_encode(['status' => 'error', 'message' => 'PIN-код должен быть 4-6 цифр']);
            break;
        }
        
        try {
            // Проверяем существование таблицы
            $tableExists = $pdo->query("SHOW TABLES LIKE 'user_pins'")->fetch();
            
            if (!$tableExists) {
                // Если таблицы нет, значит PIN не установлен
                $_SESSION['pin_verified'] = $userId;
                echo json_encode([
                    'status' => 'success',
                    'message' => 'PIN не требуется',
                    'pin_verified' => true
                ]);
                break;
            }
            
            // Проверяем PIN пользователя
            $stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
            $stmt->execute([$userId]);
            $pinData = $stmt->fetch();
            
            // Если PIN не установлен
            if (!$pinData || empty($pinData['pin_code'])) {
                $_SESSION['pin_verified'] = $userId;
                echo json_encode([
                    'status' => 'success',
                    'message' => 'PIN не установлен, доступ разрешен',
                    'pin_verified' => true
                ]);
                break;
            }
            
            // Проверяем введенный PIN
            if ($pinData['pin_code'] === $pin) {
                $_SESSION['pin_verified'] = $userId;
                addLog($pdo, $userId, "Проверка PIN-кода", "Успешно");
                echo json_encode([
                    'status' => 'success',
                    'message' => 'PIN-код верный',
                    'pin_verified' => true
                ]);
            } else {
                addLog($pdo, $userId, "Проверка PIN-кода", "Неудачно");
                echo json_encode(['status' => 'error', 'message' => 'Неверный PIN-код']);
            }
            
        } catch (Exception $e) {
            error_log("Ошибка проверки PIN: " . $e->getMessage());
            // При ошибке разрешаем доступ
            $_SESSION['pin_verified'] = $userId;
            echo json_encode([
                'status' => 'success',
                'message' => 'Ошибка проверки, доступ разрешен',
                'pin_verified' => true
            ]);
        }
        break;
        
    case 'set_pin':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        // Проверяем PIN если он уже установлен
        if (!checkPin($pdo, $_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется верификация текущего PIN-кода']);
            break;
        }
        
        $pin = trim($input['pin'] ?? '');
        $confirmPin = trim($input['confirm_pin'] ?? '');
        $userId = $_SESSION['user']['id'];
        
        if (empty($pin) || empty($confirmPin)) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
            break;
        }
        
        if ($pin !== $confirmPin) {
            echo json_encode(['status' => 'error', 'message' => 'PIN-коды не совпадают']);
            break;
        }
        
        if (!preg_match('/^\d{4,6}$/', $pin)) {
            echo json_encode(['status' => 'error', 'message' => 'PIN-код должен быть 4-6 цифр']);
            break;
        }
        
        try {
            // Создаем таблицу если ее нет (без внешних ключей)
            $createTableSQL = "
                CREATE TABLE IF NOT EXISTS user_pins (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    pin_code VARCHAR(6) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY user_id_unique (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ";
            $pdo->exec($createTableSQL);
            
            // Устанавливаем/обновляем PIN
            $stmt = $pdo->prepare("
                INSERT INTO user_pins (user_id, pin_code) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE pin_code = ?
            ");
            $stmt->execute([$userId, $pin, $pin]);
            
            // Сразу отмечаем PIN как проверенный (после установки)
            $_SESSION['pin_verified'] = $userId;
            
            addLog($pdo, $userId, "Установка PIN-кода");
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'PIN-код успешно установлен',
                'pin_set' => true,
                'pin_verified' => true
            ]);
            
        } catch (Exception $e) {
            error_log("Ошибка установки PIN: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка установки PIN-кода']);
        }
        break;
        
    case 'change_pin':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        // Проверяем текущий PIN
        if (!checkPin($pdo, $_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется верификация текущего PIN-кода']);
            break;
        }
        
        $currentPin = trim($input['current_pin'] ?? '');
        $newPin = trim($input['new_pin'] ?? '');
        $confirmPin = trim($input['confirm_pin'] ?? '');
        $userId = $_SESSION['user']['id'];
        
        if (empty($currentPin) || empty($newPin) || empty($confirmPin)) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
            break;
        }
        
        if ($newPin !== $confirmPin) {
            echo json_encode(['status' => 'error', 'message' => 'Новые PIN-коды не совпадают']);
            break;
        }
        
        if (!preg_match('/^\d{4,6}$/', $newPin)) {
            echo json_encode(['status' => 'error', 'message' => 'PIN-код должен быть 4-6 цифр']);
            break;
        }
        
        try {
            // Проверяем текущий PIN
            $stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
            $stmt->execute([$userId]);
            $pinData = $stmt->fetch();
            
            if (!$pinData || $pinData['pin_code'] !== $currentPin) {
                echo json_encode(['status' => 'error', 'message' => 'Текущий PIN-код неверен']);
                break;
            }
            
            // Обновляем PIN
            $stmt = $pdo->prepare("UPDATE user_pins SET pin_code = ? WHERE user_id = ?");
            $stmt->execute([$newPin, $userId]);
            
            // PIN остается проверенным после смены
            $_SESSION['pin_verified'] = $userId;
            
            addLog($pdo, $userId, "Смена PIN-кода");
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'PIN-код успешно изменен',
                'pin_verified' => true
            ]);
            
        } catch (Exception $e) {
            error_log("Ошибка смены PIN: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка смены PIN-кода']);
        }
        break;
        
    case 'remove_pin':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        // Проверяем текущий PIN
        if (!checkPin($pdo, $_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется верификация PIN-кода']);
            break;
        }
        
        $userId = $_SESSION['user']['id'];
        
        try {
            // Удаляем PIN
            $stmt = $pdo->prepare("DELETE FROM user_pins WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Очищаем флаг проверки PIN
            unset($_SESSION['pin_verified']);
            
            addLog($pdo, $userId, "Удаление PIN-кода");
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'PIN-код успешно удален'
            ]);
            
        } catch (Exception $e) {
            error_log("Ошибка удаления PIN: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка удаления PIN-кода']);
        }
        break;
        
    case 'change_password':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        // Проверяем PIN
        if (!checkPin($pdo, $_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется верификация PIN-кодом']);
            break;
        }
        
        $currentPassword = trim($input['current_password'] ?? '');
        $newPassword = trim($input['new_password'] ?? '');
        $confirmPassword = trim($input['confirm_password'] ?? '');
        $userId = $_SESSION['user']['id'];
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
            break;
        }
        
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Пароли не совпадают']);
            break;
        }
        
        if (strlen($newPassword) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Пароль должен быть не менее 6 символов']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!password_verify($currentPassword, $user['password'])) {
                echo json_encode(['status' => 'error', 'message' => 'Текущий пароль неверен']);
                break;
            }
            
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newPasswordHash, $userId]);
            
            addLog($pdo, $userId, "Смена пароля");
            
            echo json_encode(['status' => 'success', 'message' => 'Пароль успешно изменен']);
        } catch (Exception $e) {
            error_log("Ошибка смены пароля: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка смены пароля']);
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Вы вышли из системы']);
        break;
        
    case 'check_session':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Сессия не активна']);
            break;
        }
        
        $userId = $_SESSION['user']['id'];
        $hasPin = false;
        $pinVerified = false;
        $requirePin = false;
        
        try {
            // Проверяем наличие PIN
            $tableExists = $pdo->query("SHOW TABLES LIKE 'user_pins'")->fetch();
            
            if ($tableExists) {
                $stmt = $pdo->prepare("SELECT pin_code FROM user_pins WHERE user_id = ?");
                $stmt->execute([$userId]);
                $pinData = $stmt->fetch();
                
                $hasPin = ($pinData && !empty($pinData['pin_code']));
                
                // Проверяем, был ли PIN подтвержден
                $pinVerified = isset($_SESSION['pin_verified']) && $_SESSION['pin_verified'] == $userId;
                $requirePin = $hasPin && !$pinVerified;
            }
            
            // Обновляем данные пользователя из БД
            $stmt = $pdo->prepare("SELECT id, login, username, role, balance, banned FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                $_SESSION['user'] = $user;
                
                echo json_encode([
                    'status' => 'success',
                    'user' => $user,
                    'has_pin' => $hasPin,
                    'pin_verified' => $pinVerified,
                    'require_pin' => $requirePin
                ]);
            } else {
                session_destroy();
                echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            }
        } catch (Exception $e) {
            error_log("Ошибка проверки сессии: " . $e->getMessage());
            echo json_encode([
                'status' => 'success',
                'user' => $_SESSION['user'],
                'has_pin' => false,
                'pin_verified' => true,
                'require_pin' => false
            ]);
        }
        break;
   

    case 'get_products':
    header('Content-Type: application/json');
    
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
    $sort = $_GET['sort'] ?? 'newest';
    
    // Базовый запрос - ИСПРАВЛЕНО: seller_id = u.id
    $query = "SELECT p.*, 
                     u.login as seller_login,
                     (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image
              FROM products p 
              LEFT JOIN users u ON p.seller_id = u.id 
              WHERE p.status = 'active'";
    
    // Поиск по названию или описанию
    if (!empty($search)) {
        $query .= " AND (p.title LIKE '%$search%' OR p.description LIKE '%$search%')";
    }
    
    // Фильтр по категории
    if (!empty($category)) {
        $query .= " AND p.category = '$category'";
    }
    
    // Сортировка
    switch ($sort) {
        case 'price_asc':
            $query .= " ORDER BY p.price ASC";
            break;
        case 'price_desc':
            $query .= " ORDER BY p.price DESC";
            break;
        case 'discount':
            $query .= " ORDER BY p.discount DESC";
            break;
        case 'newest':
        default:
            $query .= " ORDER BY p.id DESC";
            break;
    }
    
    $result = $conn->query($query);
    
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка запроса: ' . $conn->error]);
        exit;
    }
    
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        // Получаем все картинки товара
        $img_query = "SELECT image_url FROM product_images WHERE product_id = {$row['id']} ORDER BY is_main DESC";
        $img_result = $conn->query($img_query);
        $images = [];
        
        while ($img = $img_result->fetch_assoc()) {
            $images[] = '/uploads/products/' . $img['image_url'];
        }
        
        // Если есть главная картинка, добавляем полный путь
        if ($row['main_image']) {
            $row['main_image'] = '/uploads/products/' . $row['main_image'];
        }
        
        $row['images'] = $images;
        $products[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'products' => $products,
        'count' => count($products)
    ]);
    exit;
        
  case 'add_product':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $seller_id = $_SESSION['user']['id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $discount = intval($_POST['discount'] ?? 0);
    $stock = intval($_POST['stock'] ?? 1);
    $category = $_POST['category'] ?? 'other';
    
    if (!$title || !$description || $price <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
        exit;
    }
    
    // Экранируем строки
    $title = $conn->real_escape_string($title);
    $description = $conn->real_escape_string($description);
    $category = $conn->real_escape_string($category);
    
    // ВАЖНО: status = 'active'
    $query = "INSERT INTO products (seller_id, title, description, price, discount, stock, category, status, created_at) 
              VALUES ($seller_id, '$title', '$description', $price, $discount, $stock, '$category', 'active', NOW())";
    
    if ($conn->query($query)) {
        $product_id = $conn->insert_id;
        
        // Загружаем картинки если есть
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = 'uploads/products/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $files = $_FILES['images'];
            $uploaded = 0;
            
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] == 0) {
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $ext;
                    $filepath = $upload_dir . $filename;
                    
                    if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                        $is_main = ($uploaded == 0) ? 1 : 0;
                        $conn->query("INSERT INTO product_images (product_id, image_url, is_main) 
                                     VALUES ($product_id, '$filename', $is_main)");
                        $uploaded++;
                    }
                }
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Товар добавлен',
            'product_id' => $product_id
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка БД: ' . $conn->error
        ]);
    }
    exit;
   

    case 'clear_cart':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    $userId = $_SESSION['user']['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $deleted = $stmt->rowCount();
        
        error_log("DEBUG clear_cart: Deleted $deleted items for user $userId");
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Корзина очищена',
            'deleted_count' => $deleted
        ]);
    } catch (Exception $e) {
        error_log("Ошибка очистки корзины: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка очистки корзины']);
    }
    break;

    // ========== СОЗДАНИЕ ЗАКАЗА - РАБОЧАЯ ВЕРСИЯ ==========
case 'create_order':
    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        // 1. ПРОВЕРЯЕМ КОРЗИНУ
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart_count = $stmt->fetchColumn();
        
        if ($cart_count == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Корзина пуста']);
            exit;
        }
        
        // 2. ПОЛУЧАЕМ ТОВАРЫ ИЗ КОРЗИНЫ
        $stmt = $pdo->prepare("
            SELECT 
                c.product_id, 
                c.quantity,
                p.id,
                p.title, 
                p.price, 
                p.discount,
                p.main_image,
                p.user_id as seller_id,
                u.login as seller_login
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            LEFT JOIN users u ON p.user_id = u.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. НАЧИНАЕМ ТРАНЗАКЦИЮ
        $pdo->beginTransaction();
        
        $purchased_ids = [];
        
        foreach ($cart_items as $item) {
            $product_id = (int)$item['product_id'];
            $quantity = (int)($item['quantity'] ?? 1);
            $price = floatval($item['price'] ?? 0);
            $discount = intval($item['discount'] ?? 0);
            $final_price = $discount > 0 ? $price * (1 - $discount/100) : $price;
            
            // 4. УМЕНЬШАЕМ СТОК
            $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")
                ->execute([$quantity, $product_id]);
            
            // 5. ⚡⚡⚡ СОХРАНЯЕМ В PURCHASES - ЭТО КРИТИЧЕСКИ ВАЖНО!
            $sql = "INSERT INTO purchases (
                user_id, 
                product_id, 
                product_title, 
                product_price, 
                product_discount,
                final_price, 
                quantity, 
                seller_id, 
                seller_login, 
                product_image,
                purchase_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $pdo->prepare($sql)->execute([
                $user_id,
                $product_id,
                $item['title'] ?? 'Без названия',
                $price,
                $discount,
                $final_price,
                $quantity,
                $item['seller_id'] ?? 0,
                $item['seller_login'] ?? 'Продавец',
                $item['main_image'] ?? null
            ]);
            
            $purchased_ids[] = $product_id;
        }
        
        // 6. ОЧИЩАЕМ КОРЗИНУ
        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
        
        // 7. ПРИМЕНЯЕМ ИЗМЕНЕНИЯ
        $pdo->commit();
        
        // 8. ПРОВЕРЯЕМ ЧТО ЗАПИСАЛОСЬ
        $check = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ?");
        $check->execute([$user_id]);
        $saved_count = $check->fetchColumn();
        
        error_log("✅ ЗАКАЗ ОФОРМЛЕН! Пользователь: {$user_id}, Товары: " . implode(',', $purchased_ids) . ", Записей в purchases: {$saved_count}");
        
        // 9. ВОЗВРАЩАЕМ УСПЕХ
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Заказ успешно оформлен!',
            'purchased_product_ids' => $purchased_ids
        ]);
        exit;
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("❌ ОШИБКА create_order: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
        exit;
    }
    break;
        
    case 'remove_from_cart':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    $userId = $_SESSION['user']['id'];
    $cartId = intval($input['cart_id'] ?? 0);
    $productId = intval($input['product_id'] ?? 0);
    
    error_log("DEBUG remove_from_cart: user_id=$userId, cart_id=$cartId, product_id=$productId");
    
    if (!$cartId && !$productId) {
        echo json_encode(['status' => 'error', 'message' => 'Не указан товар для удаления']);
        break;
    }
    
    try {
        $deleted = 0;
        
        if ($cartId) {
            // Удаляем по ID в корзине
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cartId, $userId]);
            $deleted = $stmt->rowCount();
            error_log("DEBUG: Deleted by cart_id, rows affected: $deleted");
        } else {
            // Удаляем по ID товара
            $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ? AND user_id = ?");
            $stmt->execute([$productId, $userId]);
            $deleted = $stmt->rowCount();
            error_log("DEBUG: Deleted by product_id, rows affected: $deleted");
        }
        
        if ($deleted > 0) {
            // Получаем обновленное количество в корзине
            $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
            $stmt->execute([$userId]);
            $cartTotal = $stmt->fetchColumn() ?? 0;
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Товар удален из корзины',
                'deleted' => $deleted,
                'cart_total' => intval($cartTotal)
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Товар не найден в корзине',
                'debug' => [
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                    'user_id' => $userId
                ]
            ]);
        }
    } catch (Exception $e) {
        error_log("Ошибка удаления из корзины: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка удаления из корзины: ' . $e->getMessage()
        ]);
    }
    break;

   case 'get_cart':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    $userId = $_SESSION['user']['id'];
    
    try {
        // ЛОГ для отладки
        error_log("DEBUG get_cart: user_id = $userId");
        
        // 1. Проверяем существование таблицы cart
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'cart'")->fetch();
        error_log("DEBUG: Table cart exists: " . ($tableCheck ? 'YES' : 'NO'));
        
        if (!$tableCheck) {
            // Если таблицы нет, создаем простую
            try {
                $createSQL = "CREATE TABLE cart (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    product_id INT NOT NULL,
                    quantity INT DEFAULT 1,
                    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                $pdo->exec($createSQL);
                error_log("DEBUG: Created cart table");
            } catch (Exception $e) {
                error_log("DEBUG: Failed to create cart table: " . $e->getMessage());
            }
        }
        
        // 2. Простой запрос без сложных JOIN
        $sql = "SELECT 
                    c.id as cart_id,
                    c.quantity,
                    c.product_id,
                    p.title,
                    p.price,
                    p.discount,
                    p.main_image
                FROM cart c 
                LEFT JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll();
        
        error_log("DEBUG: Found " . count($items) . " items in cart");
        
        // 3. Если товары есть, но нет данных из products
        $validItems = [];
        $subtotal = 0;
        
        foreach ($items as $item) {
            // Если нет данных о товаре, пропускаем
            if (empty($item['title'])) {
                error_log("DEBUG: Skipping item - no product data for product_id: " . $item['product_id']);
                continue;
            }
            
            $price = floatval($item['price'] ?? 0);
            $quantity = intval($item['quantity'] ?? 1);
            $discount = intval($item['discount'] ?? 0);
            
            $itemTotal = $price * $quantity;
            $itemDiscount = ($discount > 0) ? $itemTotal * ($discount / 100) : 0;
            $itemFinal = $itemTotal - $itemDiscount;
            
            $subtotal += $itemFinal;
            
            $validItems[] = [
                'cart_id' => $item['cart_id'],
                'product_id' => $item['product_id'],
                'title' => $item['title'],
                'price' => round($price, 2),
                'discount' => $discount,
                'quantity' => $quantity,
                'image' => $item['main_image'],
                'item_total' => round($itemTotal, 2),
                'item_discount' => round($itemDiscount, 2),
                'item_final' => round($itemFinal, 2)
            ];
        }
        
        // 4. Рассчитываем итоги
        $shipping = (count($validItems) > 0) ? 300 : 0;
        $total = $subtotal + $shipping;
        
        echo json_encode([
            'status' => 'success',
            'items' => $validItems,
            'summary' => [
                'subtotal' => round($subtotal, 2),
                'discount' => 0, // Упрощенно
                'shipping' => round($shipping, 2),
                'total' => round($total, 2),
                'items_count' => count($validItems),
                'quantity_total' => array_sum(array_column($validItems, 'quantity'))
            ],
            'debug' => [
                'user_id' => $userId,
                'raw_items_count' => count($items),
                'valid_items_count' => count($validItems)
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка получения корзины: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка получения корзины: ' . $e->getMessage(),
            'debug_trace' => $e->getTraceAsString()
        ]);
    }
    break;
        
case 'get_cart_count':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация', 'count' => 0]);
        break;
    }
    
    $userId = $_SESSION['user']['id'];
    
    try {
        // Проверяем таблицу cart
        $tableExists = $pdo->query("SHOW TABLES LIKE 'cart'")->fetch();
        
        if (!$tableExists) {
            echo json_encode(['status' => 'success', 'count' => 0]);
            break;
        }
        
        // Считаем общее количество товаров в корзине
        $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        
        $count = intval($result['count'] ?? 0);
        
        echo json_encode(['status' => 'success', 'count' => $count]);
        
    } catch (Exception $e) {
        // В случае ошибки возвращаем 0
        echo json_encode(['status' => 'success', 'count' => 0]);
    }
    break;
        
case 'activate':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $code = strtoupper(trim($_POST['code'] ?? ''));
    
    if (!$code) {
        echo json_encode(['status' => 'error', 'message' => 'Введите код промокода']);
        exit;
    }
    
    try {
        // 👇 ИЗМЕНИТЬ С promocodes НА promos!
        $stmt = $pdo->prepare("SELECT * FROM promos 
                              WHERE code = ? AND is_active = 1 
                              AND (expires IS NULL OR expires > NOW())
                              AND (max_uses = 0 OR uses < max_uses)");
        $stmt->execute([$code]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$promo) {
            echo json_encode(['status' => 'error', 'message' => 'Промокод не найден или истек']);
            exit;
        }
        
        // Проверяем лимит на пользователя
        if ($promo['per_user_limit'] > 0) {
            // 👇 ИЗМЕНИТЬ С promocodes НА promos!
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM promo_uses 
                                  WHERE promo_id = ? AND user_id = ?");
            $stmt->execute([$promo['id'], $user_id]);
            $user_uses = $stmt->fetchColumn();
            
            if ($user_uses >= $promo['per_user_limit']) {
                echo json_encode(['status' => 'error', 'message' => 'Вы уже использовали этот промокод']);
                exit;
            }
        }
        
        // Начисляем награду
        if ($promo['type'] === 'balance') {
            $reward = $promo['reward'];
            
            $pdo->beginTransaction();
            
            // Обновляем баланс пользователя
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$reward, $user_id]);
            
            // Обновляем счетчик использований
            $stmt = $pdo->prepare("UPDATE promos SET uses = uses + 1 WHERE id = ?");
            $stmt->execute([$promo['id']]);
            
            // Записываем использование
            $stmt = $pdo->prepare("INSERT INTO promo_uses (promo_id, user_id) VALUES (?, ?)");
            $stmt->execute([$promo['id'], $user_id]);
            
            // Получаем новый баланс
            $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $new_balance = $stmt->fetchColumn();
            
            // Обновляем сессию
            $_SESSION['user']['balance'] = floatval($new_balance);
            
            $pdo->commit();
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Промокод активирован!',
                'reward' => $reward,
                'new_balance' => $new_balance
            ]);
            
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Этот промокод для скидки в корзине, а не для баланса'
            ]);
        }
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    exit;
        
    // === УВЕДОМЛЕНИЯ (РАССЫЛКИ) ===
    case 'get_notifications':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        // Проверяем PIN
        if (!checkPin($pdo, $_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется верификация PIN-кодом']);
            break;
        }
        
        $userId = $_SESSION['user']['id'];
        
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM notifications 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT 50
            ");
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll();
            
            // Получаем количество непрочитанных
            $stmt = $pdo->prepare("SELECT COUNT(*) as unread FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$userId]);
            $unread = $stmt->fetchColumn();
            
            echo json_encode([
                'status' => 'success',
                'notifications' => $notifications,
                'unread_count' => intval($unread)
            ]);
        } catch (Exception $e) {
            error_log("Ошибка получения уведомлений: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка получения уведомлений']);
        }
        break;
        
    case 'mark_notification_read':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        // Проверяем PIN
        if (!checkPin($pdo, $_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется верификация PIN-кодом']);
            break;
        }
        
        $userId = $_SESSION['user']['id'];
        $notificationId = intval($input['notification_id'] ?? 0);
        
        if (!$notificationId) {
            echo json_encode(['status' => 'error', 'message' => 'ID уведомления не указан']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$notificationId, $userId]);
            
            echo json_encode(['status' => 'success', 'message' => 'Уведомление помечено как прочитанное']);
        } catch (Exception $e) {
            error_log("Ошибка обновления уведомления: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка обновления уведомления']);
        }
        break;
        
 
        case 'create_ticket':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (!$subject || !$message) {
        echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
        exit;
    }
    
    try {
        $conn->begin_transaction();
        
        // 1. Создаем тикет (БЕЗ message)
        $subject_esc = $conn->real_escape_string($subject);
        $query = "INSERT INTO tickets (user_id, subject, status, created_at) 
                  VALUES ($user_id, '$subject_esc', 'open', NOW())";
        
        if (!$conn->query($query)) {
            throw new Exception('Ошибка создания тикета: ' . $conn->error);
        }
        
        $ticket_id = $conn->insert_id;
        
        // 2. Добавляем первое сообщение в ticket_messages
        $message_esc = $conn->real_escape_string($message);
        $msg_query = "INSERT INTO ticket_messages (ticket_id, user_id, message, created_at) 
                      VALUES ($ticket_id, $user_id, '$message_esc', NOW())";
        
        if (!$conn->query($msg_query)) {
            throw new Exception('Ошибка сохранения сообщения: ' . $conn->error);
        }
        
        $conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Тикет создан',
            'ticket_id' => $ticket_id
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    exit;
// ========== СОЗДАТЬ АПЕЛЛЯЦИЮ ==========
case 'create_ban_appeal':
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    global $conn;
    
    $user_id = (int)($_POST['user_id'] ?? 0);
    $login = $_POST['login'] ?? '';
    
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID не указан']);
        exit;
    }
    
    // ПРОСТОЙ ЗАПРОС БЕЗ ПОДГОТОВЛЕННЫХ СТАТЕМЕНТОВ!
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка БД: ' . $conn->error]);
        exit;
    }
    
    $user_data = $result->fetch_assoc();
    
    if (!$user_data) {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        exit;
    }
    
    // УБИРАЕМ ПРОВЕРКУ НА БАН!
    
    $conn->begin_transaction();
    
    try {
        // Создаем тикет
        $subject = "Апелляция #{$user_id} - " . date('d.m.Y H:i');
        $ban_reason = $user_data['ban_reason'] ?? 'Не указана';
        
        $message_text = "🔴 **АПЕЛЛЯЦИЯ**\n\n";
        $message_text .= "👤 Пользователь: @{$user_data['login']} (ID: {$user_id})\n";
        $message_text .= "📌 Причина бана: {$ban_reason}\n";
        $message_text .= "📅 Дата: " . date('d.m.Y H:i:s');
        
        $conn->query("INSERT INTO tickets (user_id, subject, message, status, created_at) 
                      VALUES ($user_id, '$subject', '$message_text', 'open', NOW())");
        $ticket_id = $conn->insert_id;
        
        // Создаем апелляцию
        $email = $_POST['email'] ?? $user_data['email'] ?? null;
        $conn->query("INSERT INTO appeals (user_id, ticket_id, ban_reason, message, email, status, created_at) 
                      VALUES ($user_id, $ticket_id, '$ban_reason', '$message_text', " . ($email ? "'$email'" : "NULL") . ", 'new', NOW())");
        $appeal_id = $conn->insert_id;
        
        $conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Апелляция создана',
            'appeal_id' => $appeal_id,
            'ticket_id' => $ticket_id,
            'appeal_number' => 'APL-' . str_pad($appeal_id, 6, '0', STR_PAD_LEFT)
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    break;

   case 'get_ticket_messages':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $ticket_id = intval($_GET['ticket_id'] ?? 0);
    $user_id = $_SESSION['user']['id'];
    
    if ($ticket_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID']);
        exit;
    }
    
    // 1. Проверяем доступ
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Тикет не найден']);
        exit;
    }
    
    if ($ticket['user_id'] != $user_id && $_SESSION['user']['role'] != 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Нет доступа']);
        exit;
    }
    
    // 2. Получаем сообщения
    $stmt = $pdo->prepare("
        SELECT tm.*, u.login, u.username, u.role
        FROM ticket_messages tm
        LEFT JOIN users u ON tm.user_id = u.id
        WHERE tm.ticket_id = ?
        ORDER BY tm.created_at ASC
    ");
    $stmt->execute([$ticket_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'messages' => $messages,
        'current_status' => $ticket['status'],
        'created_at' => $ticket['created_at'],
        'updated_at' => $ticket['updated_at']
    ]);
    break;
        
    case 'add_ticket_message':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    
    if ($ticket_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID']);
        exit;
    }
    
    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Введите сообщение']);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // 1. Проверяем доступ
        $stmt = $pdo->prepare("SELECT user_id, status FROM tickets WHERE id = ?");
        $stmt->execute([$ticket_id]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$ticket) {
            echo json_encode(['status' => 'error', 'message' => 'Тикет не найден']);
            exit;
        }
        
        if ($ticket['user_id'] != $user_id && $_SESSION['user']['role'] != 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Нет доступа']);
            exit;
        }
        
        // 2. Добавляем сообщение
        $stmt = $pdo->prepare("
            INSERT INTO ticket_messages (ticket_id, user_id, message, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$ticket_id, $user_id, $message]);
        
        // 3. Обновляем время тикета
        $stmt = $pdo->prepare("UPDATE tickets SET updated_at = NOW() WHERE id = ?");
        $stmt->execute([$ticket_id]);
        
        $pdo->commit();
        
        echo json_encode(['status' => 'success', 'message' => 'Сообщение отправлено']);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
    }
    break;
    case 'close_ticket':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    
    if ($ticket_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID']);
        exit;
    }
    
    try {
        // 1. Проверяем доступ
        $stmt = $pdo->prepare("SELECT user_id, status FROM tickets WHERE id = ?");
        $stmt->execute([$ticket_id]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$ticket) {
            echo json_encode(['status' => 'error', 'message' => 'Тикет не найден']);
            exit;
        }
        
        if ($ticket['user_id'] != $user_id && $_SESSION['user']['role'] != 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Нет прав']);
            exit;
        }
        
        // 2. Закрываем тикет
        $stmt = $pdo->prepare("UPDATE tickets SET status = 'closed', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$ticket_id]);
        
        echo json_encode(['status' => 'success', 'message' => 'Тикет закрыт']);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
    }
    break;
   case 'get_my_tickets':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    $stmt = $pdo->prepare("
        SELECT t.*, 
               (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id) as message_count
        FROM tickets t
        WHERE t.user_id = ?
        ORDER BY t.updated_at DESC
    ");
    $stmt->execute([$user_id]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'tickets' => $tickets]);
    break;


case 'adm_update_ticket':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $ticket_id = (int)($_POST['ticket_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    
    $allowed_status = ['open', 'in_progress', 'waiting', 'closed'];
    
    if (!$ticket_id || !in_array($status, $allowed_status)) {
        echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
        exit;
    }
    
    $query = "UPDATE tickets SET status = '$status', updated_at = NOW() WHERE id = $ticket_id";
    
    if ($conn->query($query)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Статус обновлен'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка БД: ' . $conn->error
        ]);
    }
    exit;

case 'adm_users':
    // Проверка прав администратора
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    try {
        // Подключение к БД (если еще не подключено)
        global $pdo;
        
        // Запрос всех пользователей
        $stmt = $pdo->query("SELECT id, login, username, role, balance, banned, created_at FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Отправляем JSON
        echo json_encode([
            'status' => 'success',
            'users' => $users
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка базы данных: ' . $e->getMessage()
        ]);
    }
    exit;

case 'adm_delete_product':
    // Проверяем авторизацию и права администратора
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    // Получаем ID товара разными способами
    $product_id = 0;
    
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
    } else {
        // Пробуем получить из JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && isset($input['product_id'])) {
            $product_id = intval($input['product_id']);
        }
    }
    
    error_log("DEBUG adm_delete_product: product_id = $product_id");
    
    if ($product_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID товара']);
        exit;
    }
    
    try {
        // Начинаем транзакцию
        $pdo->beginTransaction();
        
        // 1. Удаляем товар из корзин пользователей
        $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
        $stmt->execute([$product_id]);
        error_log("DEBUG: Удалено из корзин: " . $stmt->rowCount() . " записей");
        
        // 2. Удаляем товар из избранного
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE product_id = ?");
        $stmt->execute([$product_id]);
        error_log("DEBUG: Удалено из избранного: " . $stmt->rowCount() . " записей");
        
        // 3. Удаляем сам товар
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $deleted = $stmt->rowCount();
        
        // Коммитим транзакцию
        $pdo->commit();
        
        if ($deleted > 0) {
            // Логируем действие
            addLog($pdo, $_SESSION['user']['id'], "Удаление товара", "ID товара: $product_id");
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Товар успешно удален',
                'product_id' => $product_id,
                'deleted' => true
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Товар не найден',
                'product_id' => $product_id
            ]);
        }
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Ошибка удаления товара: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка удаления товара: ' . $e->getMessage()
        ]);
    }
    break;
        
    case 'adm_update_balance':
        $adminCheck = checkAdmin();
        if ($adminCheck) {
            echo json_encode($adminCheck);
            break;
        }
        
        $userId = intval($input['user_id'] ?? 0);
        $amount = floatval($input['amount'] ?? 0);
        $operation = $input['operation'] ?? 'add'; // add или set
        
        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
            break;
        }
        
        try {
            if ($operation === 'set') {
                $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
                $stmt->execute([$amount, $userId]);
                
                // Если это текущий пользователь, обновляем сессию
                if ($_SESSION['user']['id'] == $userId) {
                    $_SESSION['user']['balance'] = $amount;
                }
            } else {
                $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
                $stmt->execute([$amount, $userId]);
                
                // Если это текущий пользователь, обновляем сессию
                if ($_SESSION['user']['id'] == $userId) {
                    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                    $newBalance = $stmt->fetchColumn();
                    $_SESSION['user']['balance'] = $newBalance;
                }
            }
            
            addLog($pdo, $_SESSION['user']['id'], "Изменение баланса", "Пользователь ID: $userId, Сумма: $amount, Операция: $operation");
            
            echo json_encode(['status' => 'success', 'message' => 'Баланс обновлен']);
        } catch (Exception $e) {
            error_log("Ошибка обновления баланса: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка обновления баланса']);
        }
        break;
 
// ========== ЗАБАНИТЬ ==========
case 'adm_ban':
    global $conn;
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $user_id = (int)($_POST['user_id'] ?? 0);
    $reason = $_POST['reason'] ?? 'Нарушение правил';
    $hours = (int)($_POST['hours'] ?? 24);
    
    $expires = null;
    if ($hours > 0) {
        $expires = date('Y-m-d H:i:s', strtotime("+$hours hours"));
    }
    
    $sql = "UPDATE users SET banned = 1, ban_reason = ?, ban_expires = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $reason, $expires, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Забанен']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка']);
    }
    break;

// ========== РАЗБАНИТЬ ПОЛЬЗОВАТЕЛЯ ==========
case 'adm_unban':
    global $conn;
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $user_id = (int)($_POST['user_id'] ?? 0);
    
    if ($user_id === 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        exit;
    }
    
    // Проверяем существует ли пользователь
    $check = $conn->query("SELECT id FROM users WHERE id = $user_id");
    if (!$check || $check->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        exit;
    }
    
    // Разбаниваем
    $result = $conn->query("UPDATE users SET banned = 0, ban_reason = NULL, ban_expires = NULL WHERE id = $user_id");
    
    if ($result) {
        // Закрываем апелляции
        $conn->query("UPDATE appeals SET status = 'closed' WHERE user_id = $user_id AND status != 'closed'");
        
        echo json_encode(['status' => 'success', 'message' => 'Пользователь разблокирован']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка БД: ' . $conn->error]);
    }
    exit;
    break;


// ========== ОБНОВИТЬ СТАТУС АПЕЛЛЯЦИИ ==========
case 'adm_update_appeal':
    if ($user['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $appeal_id = (int)$_POST['appeal_id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE appeals SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $appeal_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка обновления']);
    }
    break;


        
    case 'adm_send_notification':
        $adminCheck = checkAdmin();
        if ($adminCheck) {
            echo json_encode($adminCheck);
            break;
        }
        
        $title = trim($input['title'] ?? '');
        $message = trim($input['message'] ?? '');
        
        if (!$title || !$message) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
            break;
        }
        
        try {
            // Получаем всех пользователей
            $stmt = $pdo->query("SELECT id FROM users");
            $users = $stmt->fetchAll();
            
            $pdo->beginTransaction();
            
            // Отправляем уведомление каждому пользователю
            foreach ($users as $user) {
                $stmt = $pdo->prepare("
                    INSERT INTO notifications (user_id, title, message, created_at) 
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$user['id'], $title, $message]);
            }
            
            $pdo->commit();
            
            addLog($pdo, $_SESSION['user']['id'], "Рассылка уведомлений", "Тема: $title");
            
            echo json_encode(['status' => 'success', 'message' => 'Рассылка отправлена всем пользователям']);
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Ошибка рассылки уведомлений: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка рассылки уведомлений']);
        }
        break;
        
  
        
    case 'adm_update_ticket_status':
        $adminCheck = checkAdmin();
        if ($adminCheck) {
            echo json_encode($adminCheck);
            break;
        }
        
        $ticketId = intval($input['ticket_id'] ?? 0);
        $status = $input['status'] ?? '';
        
        if (!$ticketId || !in_array($status, ['open', 'in_progress', 'closed', 'waiting'])) {
            echo json_encode(['status' => 'error', 'message' => 'Неверные параметры']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$status, $ticketId]);
            
            addLog($pdo, $_SESSION['user']['id'], "Изменение статуса тикета", "Тикет ID: $ticketId, Статус: $status");
            
            echo json_encode(['status' => 'success', 'message' => 'Статус обновлен']);
        } catch (Exception $e) {
            error_log("Ошибка обновления статуса тикета: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка обновления статуса тикета']);
        }
        break;
        
 case 'adm_stats':
    $adminCheck = checkAdmin();
    if ($adminCheck) {
        echo json_encode($adminCheck);
        break;
    }
    
    try {
        // Упрощенные запросы
        $stats = [
            'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 0,
            'total_products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() ?: 0,
            'total_orders' => 0,
            'total_revenue' => 0,
            'total_promos' => $pdo->query("SELECT COUNT(*) FROM promos")->fetchColumn() ?: 0,
            'total_active_products' => $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn() ?: 0,
            'new_users_today' => $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()")->fetchColumn() ?: 0,
            'total_tickets' => $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn() ?: 0,
            'open_tickets' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'open'")->fetchColumn() ?: 0
        ];
        
        echo json_encode(['status' => 'success', 'stats' => $stats]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка получения статистики']); // ← ВОТ ОШИБКА!
    }
    break;


    case 'adm_promos':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    try {
        // ВАЖНО: таблица называется promos, НЕ promocodes!
        $stmt = $pdo->query("SELECT * FROM promos ORDER BY id DESC");
        $promos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'promos' => $promos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка загрузки промокодов: ' . $e->getMessage()
        ]);
    }
    exit;

    case 'adm_delete_promo':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $promo_id = $_POST['promo_id'] ?? 0;
    
    if (!$promo_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID не указан']);
        exit;
    }
    
    try {
        // ВАЖНО: таблица называется promos
        $stmt = $pdo->prepare("DELETE FROM promos WHERE id = ?");
        $stmt->execute([$promo_id]);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Промокод удален'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка удаления: ' . $e->getMessage()
        ]);
    }
    exit;


case 'adm_tickets':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $query = "SELECT t.*, u.login, 
                     (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id) as message_count
              FROM tickets t 
              LEFT JOIN users u ON t.user_id = u.id 
              ORDER BY t.id DESC";
    
    $result = $conn->query($query);
    $tickets = [];
    
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'tickets' => $tickets,
        'count' => count($tickets)
    ]);
    exit;

    case 'forum_logout':
    unset($_SESSION['forum_access']);
    unset($_SESSION['forum_auth_time']);
    echo json_encode(['status' => 'success', 'message' => 'Выход выполнен']);
    break;
        
case 'adm_add_promo':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $type = $_POST['type'] ?? 'balance';
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $value = floatval($_POST['value'] ?? 0);
    $reward = ($type === 'balance') ? $value : null;
    $min_order = floatval($_POST['min_order'] ?? 0);
    $max_discount = isset($_POST['max_discount']) ? floatval($_POST['max_discount']) : null;
    $max_uses = intval($_POST['max_uses'] ?? 1);
    $per_user_limit = intval($_POST['per_user_limit'] ?? 1);
    $expires = $_POST['expires'] ?? null;
    $description = trim($_POST['description'] ?? '');
    
    if (!$code || $value <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
        exit;
    }
    
    try {
        // 👇 ИЗМЕНИТЬ С promocodes НА promos
        $stmt = $pdo->prepare("SELECT id FROM promos WHERE code = ?");
        $stmt->execute([$code]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Такой код уже существует']);
            exit;
        }
        
        // 👇 ИЗМЕНИТЬ С promocodes НА promos
        $sql = "INSERT INTO promos (code, type, value, reward, min_order, max_discount, max_uses, per_user_limit, expires, description, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $code, 
            $type, 
            $value, 
            $reward, 
            $min_order, 
            $max_discount, 
            $max_uses, 
            $per_user_limit, 
            $expires, 
            $description
        ]);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Промокод создан',
            'id' => $pdo->lastInsertId()
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }
    exit;



   case 'adm_products':
    $adminCheck = checkAdmin();
    if ($adminCheck) {
        echo json_encode($adminCheck);
        break;
    }
    
    try {
        // ВАЖНО: Сначала проверим таблицу products
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'products'")->fetch();
        
        if (!$tableCheck) {
            echo json_encode([
                'status' => 'success', 
                'products' => [], 
                'count' => 0,
                'message' => 'Таблица товаров не найдена'
            ]);
            break;
        }
        
        // Получаем структуру таблицы products для отладки
        $stmt = $pdo->query("DESCRIBE products");
        $columns = $stmt->fetchAll();
        
        error_log("DEBUG adm_products: Таблица products существует, колонки: " . count($columns));
        
        // Простой и безопасный запрос ТОЛЬКО товаров
        // Сначала получим все товары без JOIN
        $sql = "SELECT * FROM products ORDER BY id DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();
        
        error_log("DEBUG adm_products: Найдено товаров: " . count($products));
        
        // Теперь для каждого товара получим данные продавца отдельно
        $enhancedProducts = [];
        foreach ($products as $product) {
            $sellerId = $product['user_id'] ?? 0;
            $sellerLogin = 'Неизвестный';
            
            // Получаем данные продавца отдельным запросом
            if ($sellerId > 0) {
                try {
                    $sellerStmt = $pdo->prepare("SELECT login, username FROM users WHERE id = ?");
                    $sellerStmt->execute([$sellerId]);
                    $seller = $sellerStmt->fetch();
                    
                    if ($seller) {
                        $sellerLogin = $seller['login'] ?? $seller['username'] ?? 'Неизвестный';
                    }
                } catch (Exception $e) {
                    error_log("DEBUG: Ошибка получения продавца для user_id=$sellerId: " . $e->getMessage());
                }
            }
            
            // Форматируем данные товара
            $enhancedProduct = [
                'id' => intval($product['id']),
                'title' => $product['title'] ?? 'Без названия',
                'description' => $product['description'] ?? '',
                'price' => floatval($product['price'] ?? 0),
                'discount' => intval($product['discount'] ?? 0),
                'category' => $product['category'] ?? 'other',
                'stock' => intval($product['stock'] ?? 0),
                'main_image' => $product['main_image'] ?? null,
                'status' => $product['status'] ?? 'active',
                'user_id' => $sellerId,
                'seller_login' => $sellerLogin,
                'created_at' => $product['created_at'] ?? null,
                // Рассчитываем финальную цену
                'final_price' => ($product['discount'] > 0) 
                    ? floatval($product['price']) * (1 - intval($product['discount']) / 100)
                    : floatval($product['price'])
            ];
            
            $enhancedProducts[] = $enhancedProduct;
        }
        
        echo json_encode([
            'status' => 'success', 
            'products' => $enhancedProducts,
            'count' => count($enhancedProducts),
            'debug' => [
                'raw_count' => count($products),
                'table_exists' => true,
                'seller_queries' => count($products) // количество запросов за продавцами
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка получения товаров (adm_products): " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка получения товаров: ' . $e->getMessage()
        ]);
    }
    break;
        
    case 'adm_logs':
        $adminCheck = checkAdmin();
        if ($adminCheck) {
            echo json_encode($adminCheck);
            break;
        }
        
        try {
            $stmt = $pdo->query("
                SELECT l.*, u.login as user_login 
                FROM logs l 
                LEFT JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC 
                LIMIT 100
            ");
            $logs = $stmt->fetchAll();
            
            echo json_encode(['status' => 'success', 'logs' => $logs]);
        } catch (Exception $e) {
            error_log("Ошибка получения логов: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Ошибка получения логов']);
        }
        break;

  
case 'adm_give_promo':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $user_id = $_POST['user_id'] ?? 0;
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $reward = floatval($_POST['reward'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $expires_days = intval($_POST['expires_days'] ?? 7);
    
    if (!$user_id || !$code || !$reward) {
        echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
        exit;
    }
    
    try {
        // 👇 ИЗМЕНИТЬ С promocodes НА promos!
        $stmt = $pdo->prepare("SELECT id FROM promos WHERE code = ?");
        $stmt->execute([$code]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Такой код промокода уже существует']);
            exit;
        }
        
        // Рассчитываем дату истечения
        $expires = date('Y-m-d H:i:s', strtotime("+{$expires_days} days"));
        
        // 👇 ИЗМЕНИТЬ С promocodes НА promos!
        $stmt = $pdo->prepare("INSERT INTO promos (user_id, code, type, value, reward, message, expires, created_at, is_active, max_uses, per_user_limit) 
                               VALUES (?, ?, 'balance', ?, ?, ?, ?, NOW(), 1, 1, 1)");
        $stmt->execute([$user_id, $code, $reward, $reward, $message, $expires]);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Персональный промокод выдан',
            'promo_id' => $pdo->lastInsertId(),
            'code' => $code
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка БД: ' . $e->getMessage()
        ]);
    }
    exit;


case 'check_personal_promo':
    header('Content-Type: application/json');
    
    $user_id = $_GET['user_id'] ?? 0;
    
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID не указан']);
        exit;
    }
    
    try {
        // 👇 ИЗМЕНИТЬ С promocodes НА promos!
        $stmt = $pdo->prepare("SELECT * FROM promos 
                              WHERE user_id = ? AND type = 'balance' AND is_active = 1 
                              AND (expires IS NULL OR expires > NOW())
                              AND (max_uses = 0 OR uses < max_uses)
                              ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user_id]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($promo) {
            echo json_encode([
                'status' => 'success',
                'has_promo' => true,
                'promo' => $promo
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'has_promo' => false
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;

// ========== АКТИВАЦИЯ ПЕРСОНАЛЬНОГО ПРОМОКОДА ==========
case 'activate_personal_promo':
    $user_id = (int)($_POST['user_id'] ?? 0);
    $promo_id = (int)($_POST['promo_id'] ?? 0);
    
    if (!$user_id || !$promo_id) {
        echo json_encode(['status' => 'error', 'message' => 'Данные не указаны']);
        exit;
    }
    
    $conn->begin_transaction();
    
    try {
        // Получаем промокод
        $promo = $conn->query("SELECT * FROM personal_promos WHERE id = $promo_id AND user_id = $user_id AND status = 'new'")->fetch_assoc();
        
        if (!$promo) {
            throw new Exception('Промокод не найден или уже активирован');
        }
        
        // Активируем промокод
        $conn->query("UPDATE personal_promos SET status = 'activated', activated_at = NOW() WHERE id = $promo_id");
        
        // Начисляем баланс
        $conn->query("UPDATE users SET balance = balance + {$promo['reward']} WHERE id = $user_id");
        
        // Получаем новый баланс
        $user = $conn->query("SELECT balance FROM users WHERE id = $user_id")->fetch_assoc();
        
        $conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Промокод активирован!',
            'reward' => $promo['reward'],
            'new_balance' => $user['balance']
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;

    // ========== ПОЛУЧИТЬ СООБЩЕНИЯ АПЕЛЛЯЦИИ ==========
case 'get_appeal_messages':
    $appeal_id = (int)($_GET['appeal_id'] ?? 0);
    
    if (!$appeal_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID апелляции не указан']);
        exit;
    }
    
    $sql = "SELECT am.*, u.login as sender_login, u.username as sender_name 
            FROM appeal_messages am 
            LEFT JOIN users u ON am.user_id = u.id 
            WHERE am.appeal_id = $appeal_id 
            ORDER BY am.created_at ASC";
    
    $result = $conn->query($sql);
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    // Получаем информацию об апелляции
    $appeal = $conn->query("SELECT * FROM appeals WHERE id = $appeal_id")->fetch_assoc();
    
    echo json_encode([
        'status' => 'success',
        'messages' => $messages,
        'appeal' => $appeal
    ]);
    break;

// ========== ОТПРАВИТЬ СООБЩЕНИЕ В АПЕЛЛЯЦИЮ ==========
case 'send_appeal_message':
    $appeal_id = (int)($_POST['appeal_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $message = $conn->real_escape_string($_POST['message'] ?? '');
    
    if (!$appeal_id || !$user_id || !$message) {
        echo json_encode(['status' => 'error', 'message' => 'Не все данные заполнены']);
        exit;
    }
    
    // Определяем тип отправителя
    $sender_type = 'user';
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
        $sender_type = 'admin';
    }
    
    $conn->begin_transaction();
    
    try {
        // Создаем таблицу для сообщений если её нет
        $conn->query("CREATE TABLE IF NOT EXISTS `appeal_messages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `appeal_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `message` text NOT NULL,
            `sender_type` enum('user','admin') DEFAULT 'user',
            `is_read` tinyint(1) DEFAULT 0,
            `created_at` datetime DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `appeal_id` (`appeal_id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        // Добавляем сообщение
        $sql = "INSERT INTO appeal_messages (appeal_id, user_id, message, sender_type, is_read, created_at) 
                VALUES ($appeal_id, $user_id, '$message', '$sender_type', 0, NOW())";
        $conn->query($sql);
        
        // Обновляем статус апелляции и время последнего сообщения
        $new_status = $sender_type === 'admin' ? 'in_progress' : 'new';
        $conn->query("UPDATE appeals SET status = '$new_status', last_message_at = NOW() WHERE id = $appeal_id");
        
        $conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Сообщение отправлено',
            'message_id' => $conn->insert_id
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
    }
    break;

// ========== ПОМЕТИТЬ СООБЩЕНИЯ КАК ПРОЧИТАННЫЕ ==========
case 'mark_appeal_messages_read':
    $appeal_id = (int)($_POST['appeal_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    
    if (!$appeal_id || !$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Данные не указаны']);
        exit;
    }
    
    $conn->query("UPDATE appeal_messages SET is_read = 1 
                  WHERE appeal_id = $appeal_id AND user_id != $user_id AND is_read = 0");
    
    echo json_encode(['status' => 'success']);
    break;

    case 'check_special_notifications':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    $userId = $_SESSION['user']['id'];
    
    try {
        // Проверим существование таблицы notifications
        $tableExists = $pdo->query("SHOW TABLES LIKE 'notifications'")->fetch();
        if (!$tableExists) {
            echo json_encode(['status' => 'success', 'has_special' => false]);
            break;
        }
        
        // Проверим колонку is_special
        $columnExists = $pdo->query("SHOW COLUMNS FROM notifications LIKE 'is_special'")->fetch();
        if (!$columnExists) {
            echo json_encode(['status' => 'success', 'has_special' => false]);
            break;
        }
        
        // Ищем специальные непрочитанные уведомления
        $stmt = $pdo->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? AND is_special = 1 AND is_read = 0 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $notification = $stmt->fetch();
        
        if ($notification) {
            // Помечаем как прочитанное
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            $stmt->execute([$notification['id']]);
            
            echo json_encode([
                'status' => 'success',
                'has_special' => true,
                'notification' => $notification
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'has_special' => false
            ]);
        }
    } catch (Exception $e) {
        error_log("Ошибка проверки уведомлений: " . $e->getMessage());
        echo json_encode(['status' => 'success', 'has_special' => false]);
    }
    break;
    
    $userId = intval($input['user_id'] ?? 0);
    $code = trim($input['code'] ?? '');
    $reward = floatval($input['reward'] ?? 0);
    $message = trim($input['message'] ?? 'Персональный промокод от администратора');
    
    if (!$userId || !$code || $reward <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Заполните все обязательные поля']);
        break;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Создаем персональный промокод
        $stmt = $pdo->prepare("
            INSERT INTO promos (code, reward, max_uses, expires_at, is_active, created_at, is_personal, personal_user_id)
            VALUES (?, ?, 1, DATE_ADD(NOW(), INTERVAL 7 DAY), 1, NOW(), 1, ?)
        ");
        $stmt->execute([$code, $reward, $userId]);
        $promoId = $pdo->lastInsertId();
        
        // Создаем уведомление для пользователя
        $notificationMessage = "Вам выдан персональный промокод!<br><br>";
        $notificationMessage .= "<strong>Сообщение от администратора:</strong><br>";
        $notificationMessage .= nl2br(htmlspecialchars($message)) . "<br><br>";
        $notificationMessage .= "<strong>Промокод:</strong> <code>" . htmlspecialchars($code) . "</code><br>";
        $notificationMessage .= "<strong>Награда:</strong> " . $reward . " ₽<br>";
        $notificationMessage .= "<em>Промокод действует 7 дней</em>";
        
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, title, message, is_special, created_at)
            VALUES (?, '🎁 Персональный промокод!', ?, 1, NOW())
        ");
        $stmt->execute([$userId, $notificationMessage]);
        
        $pdo->commit();
        
        addLog($pdo, $_SESSION['user']['id'], "Выдача персонального промокода", 
               "Пользователь ID: $userId, Промокод: $code, Награда: $reward ₽");
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Промокод выдан пользователю'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Ошибка выдачи промокода: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка выдачи промокода']);
    }
    break;

    case 'check_special_notifications':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    $userId = $_SESSION['user']['id'];
    
    try {
        // Ищем специальные непрочитанные уведомления
        $stmt = $pdo->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? AND is_special = 1 AND is_read = 0 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $notification = $stmt->fetch();
        
        if ($notification) {
            // Помечаем как прочитанное
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            $stmt->execute([$notification['id']]);
            
            echo json_encode([
                'status' => 'success',
                'has_special' => true,
                'notification' => $notification
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'has_special' => false
            ]);
        }
    } catch (Exception $e) {
        error_log("Ошибка проверки уведомлений: " . $e->getMessage());
        echo json_encode(['status' => 'success', 'has_special' => false]);
    }
    break;
        
  

    // ========== ПРОВЕРКА БАНА ==========
case 'check_ban':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $sql = "SELECT * FROM users WHERE id = ? AND banned = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banned_user = $result->fetch_assoc();
    
    if ($banned_user) {
        // ФОРМАТИРУЕМ ДАТУ ПРАВИЛЬНО
        $expires = $banned_user['ban_expires'];
        $expires_formatted = null;
        $is_permanent = false;
        
        if ($expires && $expires != '0000-00-00 00:00:00') {
            $expires_formatted = date('d.m.Y H:i', strtotime($expires));
            $is_permanent = false;
        } else {
            $is_permanent = true;
        }
        
        echo json_encode([
            'status' => 'success',
            'banned' => 1,
            'ban_info' => [
                'reason' => $banned_user['ban_reason'] ?? 'Нарушение правил',
                'expires' => $expires,
                'expires_formatted' => $expires_formatted,
                'is_permanent' => $is_permanent,
                'user_id' => $banned_user['id'],
                'login' => $banned_user['login']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'success', 'banned' => 0]);
    }
    break;



   // ========== ПОЛУЧИТЬ АПЕЛЛЯЦИИ ==========
case 'adm_get_appeals':
    global $conn;
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $result = $conn->query("SELECT * FROM appeals ORDER BY created_at DESC");
    $appeals = [];
    while ($row = $result->fetch_assoc()) {
        $appeals[] = $row;
    }
    
    echo json_encode(['status' => 'success', 'appeals' => $appeals, 'count' => count($appeals)]);
    exit;
    break;


    case 'add_to_cart':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if (!$product_id || $quantity < 1) {
        echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
        exit;
    }
    
    // ПРОВЕРЯЕМ ПРОДАВЦА
    $check = $conn->query("SELECT seller_id, stock FROM products WHERE id = $product_id");
    
    if ($check->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
        exit;
    }
    
    $product = $check->fetch_assoc();
    
    // Запрещаем продавцу покупать свой товар
    if ($product['seller_id'] == $user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Нельзя купить свой товар']);
        exit;
    }
    
    // Проверяем наличие
    if ($product['stock'] < $quantity) {
        echo json_encode(['status' => 'error', 'message' => 'Недостаточно товара']);
        exit;
    }
    
    // Добавляем в корзину...
    // (остальной код)
    
    break;


case 'add_to_cart':
    addToCart();
    break;

case 'update_cart_quantity':
    updateCartQuantity();
    break;



function getCart() {
    if(!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        return;
    }
    
    // Тестовые данные корзины
    $cartItems = [
        [
            'id' => 1,
            'product_id' => 1,
            'title' => 'Смартфон iPhone 15',
            'price' => 80991,
            'discount' => 10,
            'quantity' => 1,
            'seller_login' => 'admin',
            'image' => null
        ],
        [
            'id' => 2,
            'product_id' => 3,
            'title' => 'Наушники Sony WH-1000XM5',
            'price' => 25491.5,
            'discount' => 15,
            'quantity' => 2,
            'seller_login' => 'seller1',
            'image' => null
        ]
    ];
    
    // Рассчитываем итоги
    $subtotal = 0;
    $totalDiscount = 0;
    
    foreach($cartItems as $item) {
        $itemTotal = $item['price'] * $item['quantity'];
        $subtotal += $itemTotal;
        $totalDiscount += ($itemTotal * $item['discount'] / 100);
    }
    
    $shipping = 300; // Стоимость доставки
    $total = $subtotal - $totalDiscount + $shipping;
    
    echo json_encode([
        'status' => 'success',
        'items' => $cartItems,
        'subtotal' => $subtotal,
        'discount' => $totalDiscount,
        'shipping' => $shipping,
        'total' => $total,
        'count' => count($cartItems)
    ]);
}

    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if ($product_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID товара']);
        exit;
    }
    
    // Подключение к БД (должно быть уже в начале файла)
    require_once 'config/database.php'; // или твой файл подключения
    
    try {
        // Проверяем, существует ли товар
        $stmt = $pdo->prepare("SELECT id, title FROM products WHERE id = ? AND status = 'active'");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            echo json_encode(['status' => 'error', 'message' => 'Товар не найден или неактивен']);
            exit;
        }
        
        // Проверяем, есть ли уже в избранном
        $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            // Удаляем из избранного
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            
            $response = [
                'status' => 'success',
                'is_favorite' => false,
                'message' => 'Товар "' . $product['title'] . '" удален из избранного',
                'product_id' => $product_id
            ];
        } else {
            // Добавляем в избранное
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
            
            $response = [
                'status' => 'success',
                'is_favorite' => true,
                'message' => 'Товар "' . $product['title'] . '" добавлен в избранное',
                'product_id' => $product_id,
                'favorite_id' => $pdo->lastInsertId()
            ];
        }
        
        echo json_encode($response);
        
    } catch (PDOException $e) {
        error_log("Favorite error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка базы данных: ' . $e->getMessage()
        ]);
    }
    break;

case 'get_favorites':
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        // ПРОСТОЙ ЗАПРОС - БЕЗ JOIN
        $stmt = $pdo->prepare("
            SELECT p.*, u.login as seller_login 
            FROM products p 
            JOIN favorites f ON p.id = f.product_id 
            LEFT JOIN users u ON p.user_id = u.id 
            WHERE f.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($products as &$product) {
            $product['is_favorite'] = true;
            $product['final_price'] = $product['price'];
            $product['seller_login'] = $product['seller_login'] ?? 'Продавец';
        }
        
        echo json_encode([
            'status' => 'success',
            'favorites' => $products,
            'count' => count($products)
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'favorites' => [],
            'count' => 0
        ]);
    }
    break;
   case 'toggle_favorite':
    header('Content-Type: application/json; charset=utf-8');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if ($product_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID товара']);
        exit;
    }
    
    try {
        // ПРОВЕРЯЕМ ЕСТЬ ЛИ УЖЕ
        $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            // УДАЛЯЕМ
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            echo json_encode([
                'status' => 'success',
                'is_favorite' => false,
                'message' => 'Удалено из избранного'
            ]);
        } else {
            // ДОБАВЛЯЕМ
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
            echo json_encode([
                'status' => 'success',
                'is_favorite' => true,
                'message' => 'Добавлено в избранное'
            ]);
        }
        exit;
        
    } catch (PDOException $e) {
        error_log("Favorites error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка базы данных: ' . $e->getMessage()
        ]);
        exit;
    }
    break;
case 'clear_favorites':
    session_start();
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Избранное очищено',
        'deleted_count' => $stmt->rowCount()
    ]);
    break;

case 'get_favorites_count':
    session_start();
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация', 'count' => 0]);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    
    echo json_encode([
        'status' => 'success',
        'count' => $result['count'] ?? 0
    ]);
    break;

case 'get_favorites_ids':
    session_start();
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'success', 'favorites' => []]);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    $stmt = $pdo->prepare("SELECT product_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'status' => 'success',
        'favorites' => $favorites
    ]);
    break;


function clearCart() {
    if(!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        return;
    }
    
    $userId = $_SESSION['user']['id'];
    
    // Здесь должен быть реальный запрос к БД
    // DELETE FROM cart WHERE user_id = ?
    
    // Удаляем корзину из сессии (для теста)
    if(isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Корзина очищена',
        'count' => 0
    ]);
}

function removeFromCart() {
    if(!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        return;
    }
    
    $productId = $_POST['product_id'] ?? 0;
    
    if(!$productId) {
        echo json_encode(['status' => 'error', 'message' => 'Не указан товар']);
        return;
    }
    
    $userId = $_SESSION['user']['id'];
    
    // Здесь должен быть реальный запрос к БД
    // DELETE FROM cart WHERE user_id = ? AND product_id = ?
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Товар удален из корзины'
    ]);
}


// === ПОИСК И ПРОСМОТР ПОЛЬЗОВАТЕЛЕЙ ===
case 'adm_search_user':
    $adminCheck = checkAdmin();
    if ($adminCheck) {
        echo json_encode($adminCheck);
        break;
    }
    
    $search = trim($input['search'] ?? $_GET['search'] ?? '');
    
    if (strlen($search) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Введите минимум 2 символа']);
        break;
    }
    
    try {
        $searchTerm = "%$search%";
        
        $sql = "SELECT 
                    id,
                    login,
                    COALESCE(username, login) as username,
                    role,
                    balance,
                    banned,
                    ban_reason,
                    ban_expires,
                    created_at,
                    last_login
                FROM users 
                WHERE login LIKE ? OR username LIKE ? 
                ORDER BY 
                    CASE 
                        WHEN login = ? THEN 1
                        WHEN username = ? THEN 2
                        WHEN login LIKE ? THEN 3
                        WHEN username LIKE ? THEN 4
                        ELSE 5
                    END,
                    created_at DESC
                LIMIT 20";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $searchTerm, $searchTerm,
            $search, $search,
            $search . '%', $search . '%'
        ]);
        
        $users = $stmt->fetchAll();
        
        // Форматируем данные
        foreach ($users as &$user) {
            $user['balance'] = floatval($user['balance'] ?? 0);
            $user['banned'] = intval($user['banned'] ?? 0);
            
            // Информация о активности
            $user['is_online'] = false;
            if ($user['last_login']) {
                $lastLogin = strtotime($user['last_login']);
                $user['is_online'] = (time() - $lastLogin) < 300; // онлайн если был активен последние 5 минут
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'users' => $users,
            'count' => count($users),
            'search_query' => $search
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка поиска пользователя: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка поиска пользователя']);
    }
    break;

case 'adm_get_user_details':
    $adminCheck = checkAdmin();
    if ($adminCheck) {
        echo json_encode($adminCheck);
        break;
    }
    
    $userId = intval($input['user_id'] ?? $_GET['user_id'] ?? 0);
    
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        break;
    }
    
    try {
        // 1. Основная информация о пользователе
        $sql = "SELECT 
                    u.*,
                    (SELECT COUNT(*) FROM products WHERE user_id = u.id) as product_count,
                    (SELECT COUNT(*) FROM tickets WHERE user_id = u.id) as ticket_count,
                    (SELECT COUNT(*) FROM logs WHERE user_id = u.id) as log_count,
                    (SELECT SUM(reward) FROM promo_activations WHERE user_id = u.id) as total_promo_rewards
                FROM users u 
                WHERE u.id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            break;
        }
        
        // 2. Товары пользователя
        $stmt = $pdo->prepare("
            SELECT 
                id,
                title,
                price,
                discount,
                category,
                stock,
                main_image,
                status,
                created_at,
                (price * (1 - discount/100)) as final_price
            FROM products 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 20
        ");
        $stmt->execute([$userId]);
        $products = $stmt->fetchAll();
        
        // Обрабатываем изображения товаров
        foreach ($products as &$product) {
            if (!empty($product['main_image'])) {
                $product['image_url'] = '/' . ltrim($product['main_image'], '/');
            } else {
                $product['image_url'] = '/images/no-image.jpg';
            }
        }
        
        // 3. Последние действия (логи)
        $stmt = $pdo->prepare("
            SELECT * FROM logs 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 50
        ");
        $stmt->execute([$userId]);
        $logs = $stmt->fetchAll();
        
        // 4. Баланс и транзакции (если есть таблица transactions)
        $transactions = [];
        try {
            $tableExists = $pdo->query("SHOW TABLES LIKE 'transactions'")->fetch();
            if ($tableExists) {
                $stmt = $pdo->prepare("
                    SELECT * FROM transactions 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT 20
                ");
                $stmt->execute([$userId]);
                $transactions = $stmt->fetchAll();
            }
        } catch (Exception $e) {
            // Игнорируем если таблицы нет
        }
        
        // 5. Тикеты пользователя
        $stmt = $pdo->prepare("
            SELECT * FROM tickets 
            WHERE user_id = ? 
            ORDER BY updated_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        $tickets = $stmt->fetchAll();
        
        // 6. Активированные промокоды
        $stmt = $pdo->prepare("
            SELECT pa.*, p.code as promo_code 
            FROM promo_activations pa
            LEFT JOIN promos p ON pa.promo_id = p.id
            WHERE pa.user_id = ? 
            ORDER BY pa.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        $promo_activations = $stmt->fetchAll();
        
        // Форматируем данные пользователя
        $user['balance'] = floatval($user['balance'] ?? 0);
        $user['banned'] = intval($user['banned'] ?? 0);
        $user['product_count'] = intval($user['product_count'] ?? 0);
        $user['ticket_count'] = intval($user['ticket_count'] ?? 0);
        $user['log_count'] = intval($user['log_count'] ?? 0);
        $user['total_promo_rewards'] = floatval($user['total_promo_rewards'] ?? 0);
        
        // Статистика активности
        $user['activity_stats'] = [
            'products' => $user['product_count'],
            'tickets' => $user['ticket_count'],
            'logs' => $user['log_count'],
            'promo_rewards' => $user['total_promo_rewards']
        ];
        
        // Проверяем онлайн статус
        $user['is_online'] = false;
        if ($user['last_login']) {
            $lastLogin = strtotime($user['last_login']);
            $user['is_online'] = (time() - $lastLogin) < 300; // онлайн если был активен последние 5 минут
            $user['last_login_formatted'] = date('d.m.Y H:i:s', $lastLogin);
        }
        
        $user['created_at_formatted'] = date('d.m.Y H:i:s', strtotime($user['created_at']));
        
        echo json_encode([
            'status' => 'success',
            'user' => $user,
            'products' => $products,
            'logs' => $logs,
            'transactions' => $transactions,
            'tickets' => $tickets,
            'promo_activations' => $promo_activations,
            'stats' => [
                'total_products' => count($products),
                'total_logs' => count($logs),
                'total_transactions' => count($transactions),
                'total_tickets' => count($tickets)
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка получения данных пользователя: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка получения данных пользователя',
            'debug' => $e->getMessage()
        ]);
    }
    break;

case 'adm_get_user_products':
    $adminCheck = checkAdmin();
    if ($adminCheck) {
        echo json_encode($adminCheck);
        break;
    }
    
    $userId = intval($input['user_id'] ?? $_GET['user_id'] ?? 0);
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(100, intval($_GET['limit'] ?? 20)));
    $offset = ($page - 1) * $limit;
    
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        break;
    }
    
    try {
        $sql = "SELECT 
                    p.*,
                    (SELECT COUNT(*) FROM cart WHERE product_id = p.id) as in_cart_count,
                    (SELECT COUNT(*) FROM favorites WHERE product_id = p.id) as favorites_count
                FROM products p 
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $limit, $offset]);
        $products = $stmt->fetchAll();
        
        // Общее количество товаров пользователя
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE user_id = ?");
        $stmt->execute([$userId]);
        $total = $stmt->fetchColumn();
        
        // Обрабатываем изображения и цены
        foreach ($products as &$product) {
            $price = floatval($product['price'] ?? 0);
            $discount = intval($product['discount'] ?? 0);
            
            // Расчет финальной цены
            if ($discount > 0 && $discount <= 100) {
                $product['final_price'] = $price * (1 - $discount / 100);
                $product['has_discount'] = true;
            } else {
                $product['final_price'] = $price;
                $product['has_discount'] = false;
            }
            
            // URL изображения
            if (!empty($product['main_image'])) {
                $product['image_url'] = '/' . ltrim($product['main_image'], '/');
            } else {
                $product['image_url'] = '/images/no-image.jpg';
            }
            
            // Форматирование
            $product['price_formatted'] = number_format($price, 2, ',', ' ');
            $product['final_price_formatted'] = number_format($product['final_price'], 2, ',', ' ');
            $product['in_cart_count'] = intval($product['in_cart_count'] ?? 0);
            $product['favorites_count'] = intval($product['favorites_count'] ?? 0);
        }
        
        echo json_encode([
            'status' => 'success',
            'products' => $products,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => intval($total),
                'pages' => ceil($total / $limit)
            ],
            'user_id' => $userId
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка получения товаров пользователя: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка получения товаров пользователя']);
    }
    break;

    // === ПОИСК ПОЛЬЗОВАТЕЛЕЙ (ПУБЛИЧНЫЙ) ===
case 'search_users_public':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    // Проверяем PIN (если требуется)
    if (!checkPin($pdo, $_SESSION['user']['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется верификация PIN-кодом']);
        break;
    }
    
    $search = trim($input['search'] ?? $_GET['search'] ?? '');
    
    if (strlen($search) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Введите минимум 2 символа']);
        break;
    }
    
    try {
        $searchTerm = "%$search%";
        
        // Только публичная информация - без баланса, без бан-инфо
        $sql = "SELECT 
                    id,
                    login,
                    COALESCE(username, login) as username,
                    role,
                    created_at,
                    last_login
                FROM users 
                WHERE (login LIKE ? OR username LIKE ?) 
                    AND id != ?  // Исключаем себя из поиска
                ORDER BY 
                    CASE 
                        WHEN login = ? THEN 1
                        WHEN username = ? THEN 2
                        WHEN login LIKE ? THEN 3
                        WHEN username LIKE ? THEN 4
                        ELSE 5
                    END,
                    created_at DESC
                LIMIT 20";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $searchTerm, $searchTerm,
            $_SESSION['user']['id'],  // Исключаем себя
            $search, $search,
            $search . '%', $search . '%'
        ]);
        
        $users = $stmt->fetchAll();
        
        // Добавляем только публичную информацию
        foreach ($users as &$user) {
            // Проверяем онлайн статус
            $user['is_online'] = false;
            if ($user['last_login']) {
                $lastLogin = strtotime($user['last_login']);
                $user['is_online'] = (time() - $lastLogin) < 300; // онлайн если был активен последние 5 минут
            }
            
            // Форматируем дату
            $user['created_at_formatted'] = date('d.m.Y', strtotime($user['created_at']));
            
            // Получаем количество товаров пользователя
            $stmt2 = $pdo->prepare("SELECT COUNT(*) as product_count FROM products WHERE user_id = ? AND status = 'active'");
            $stmt2->execute([$user['id']]);
            $productData = $stmt2->fetch();
            $user['product_count'] = intval($productData['product_count'] ?? 0);
            
            // Получаем рейтинг (если есть таблица ratings)
            $user['rating'] = 0;
            try {
                $tableExists = $pdo->query("SHOW TABLES LIKE 'ratings'")->fetch();
                if ($tableExists) {
                    $stmt2 = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE seller_id = ?");
                    $stmt2->execute([$user['id']]);
                    $ratingData = $stmt2->fetch();
                    $user['rating'] = round(floatval($ratingData['avg_rating'] ?? 0), 1);
                }
            } catch (Exception $e) {
                // Игнорируем ошибку
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'users' => $users,
            'count' => count($users),
            'search_query' => $search
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка поиска пользователей: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка поиска пользователей']);
    }
    break;

case 'get_user_public_profile':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        break;
    }
    
    $userId = intval($input['user_id'] ?? $_GET['user_id'] ?? 0);
    $currentUserId = $_SESSION['user']['id'];
    
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        break;
    }
    
    // Запрещаем просматривать самого себя через этот эндпоинт
    if ($userId == $currentUserId) {
        echo json_encode(['status' => 'error', 'message' => 'Для просмотра своего профиля используйте свой личный кабинет']);
        break;
    }
    
    try {
        // Получаем публичную информацию о пользователе
        $sql = "SELECT 
                    u.id,
                    u.login,
                    COALESCE(u.username, u.login) as username,
                    u.role,
                    u.created_at,
                    u.last_login,
                    (SELECT COUNT(*) FROM products WHERE user_id = u.id AND status = 'active') as product_count,
                    (SELECT COUNT(*) FROM products WHERE user_id = u.id AND status = 'active' AND stock > 0) as active_products_count,
                    (SELECT AVG(rating) FROM ratings WHERE seller_id = u.id) as rating
                FROM users u 
                WHERE u.id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            break;
        }
        
        // Получаем товары пользователя (только активные)
        $stmt = $pdo->prepare("
            SELECT 
                id,
                title,
                price,
                discount,
                category,
                stock,
                main_image,
                created_at,
                (price * (1 - discount/100)) as final_price
            FROM products 
            WHERE user_id = ? 
                AND status = 'active'
                AND stock > 0
            ORDER BY created_at DESC 
            LIMIT 12
        ");
        $stmt->execute([$userId]);
        $products = $stmt->fetchAll();
        
        // Обрабатываем изображения товаров
        foreach ($products as &$product) {
            if (!empty($product['main_image'])) {
                $product['image_url'] = '/' . ltrim($product['main_image'], '/');
            } else {
                $product['image_url'] = '/images/no-image.jpg';
            }
            
            // Форматируем цены
            $product['price_formatted'] = number_format($product['price'], 2, ',', ' ');
            $product['final_price_formatted'] = number_format($product['final_price'], 2, ',', ' ');
            $product['has_discount'] = $product['discount'] > 0;
        }
        
        // Проверяем онлайн статус
        $user['is_online'] = false;
        if ($user['last_login']) {
            $lastLogin = strtotime($user['last_login']);
            $user['is_online'] = (time() - $lastLogin) < 300;
            $user['last_login_formatted'] = date('d.m.Y H:i:s', $lastLogin);
        }
        
        // Форматируем остальные данные
        $user['created_at_formatted'] = date('d.m.Y', strtotime($user['created_at']));
        $user['product_count'] = intval($user['product_count'] ?? 0);
        $user['active_products_count'] = intval($user['active_products_count'] ?? 0);
        $user['rating'] = round(floatval($user['rating'] ?? 0), 1);
        
        // Получаем последние отзывы (если есть)
        $reviews = [];
        try {
            $tableExists = $pdo->query("SHOW TABLES LIKE 'reviews'")->fetch();
            if ($tableExists) {
                $stmt = $pdo->prepare("
                    SELECT r.*, u.username as reviewer_name 
                    FROM reviews r
                    LEFT JOIN users u ON r.user_id = u.id
                    WHERE r.seller_id = ?
                    ORDER BY r.created_at DESC
                    LIMIT 5
                ");
                $stmt->execute([$userId]);
                $reviews = $stmt->fetchAll();
            }
        } catch (Exception $e) {
            // Игнорируем
        }
        
        echo json_encode([
            'status' => 'success',
            'user' => $user,
            'products' => $products,
            'reviews' => $reviews,
            'stats' => [
                'total_products' => $user['product_count'],
                'active_products' => $user['active_products_count'],
                'total_reviews' => count($reviews),
                'rating' => $user['rating']
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка получения публичного профиля: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка получения профиля пользователя']);
    }
    break;


case 'search_users':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
    
    if (empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Введите поисковый запрос']);
        exit;
    }
    
    try {
        // ИСПРАВЛЕННЫЙ ЗАПРОС - убрали email, если его нет в таблице
        $searchQuery = "%$query%";
        $stmt = $pdo->prepare("
            SELECT id, login, username, role, balance, banned, created_at 
            FROM users 
            WHERE (login LIKE ? OR username LIKE ? OR id = ?) 
            AND id != ? 
            ORDER BY 
                CASE WHEN login = ? THEN 1 
                     WHEN username LIKE ? THEN 2 
                     ELSE 3 
                END,
                id DESC 
            LIMIT ?
        ");
        
        $userId = $_SESSION['user']['id'];
        $stmt->execute([$searchQuery, $searchQuery, $query, $userId, $query, $searchQuery, $limit]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['status' => 'success', 'users' => $users]);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;

// Статистика пользователя
case 'get_user_stats':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    
    if ($userId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID пользователя']);
        exit;
    }
    
    try {
        $stats = [
            'products_count' => 0,
            'active_products' => 0,
            'orders_count' => 0,
            'total_sales' => 0,
            'positive_reviews' => 0,
            'rating' => 0
        ];
        
        // Количество товаров
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE seller_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['products_count'] = $result['count'] ?? 0;
        
        // Активные товары
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE seller_id = ? AND status = 'active'");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['active_products'] = $result['count'] ?? 0;
        
        // Продажи (если есть таблица orders)
        try {
            $tableCheck = $pdo->query("SHOW TABLES LIKE 'orders'")->fetch();
            if ($tableCheck) {
                $columns = $pdo->query("SHOW COLUMNS FROM orders")->fetchAll(PDO::FETCH_COLUMN);
                
                if (in_array('seller_id', $columns)) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE seller_id = ? AND status = 'completed'");
                    $stmt->execute([$userId]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stats['orders_count'] = $result['count'] ?? 0;
                    
                    // Общее количество проданных единиц
                    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM orders WHERE seller_id = ? AND status = 'completed'");
                    $stmt->execute([$userId]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stats['total_sales'] = $result['total'] ?? 0;
                }
            }
        } catch (Exception $e) {
            // Игнорируем ошибки
        }
        
        echo json_encode(['status' => 'success', 'stats' => $stats]);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;


    

case 'get_user_profile':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $profileUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    
    if ($profileUserId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID пользователя']);
        exit;
    }
    
    try {
        // 1. Получаем основную информацию о пользователе
        $stmt = $pdo->prepare("
            SELECT 
                id,
                login,
                username,
                role,
                balance,
                banned,
                created_at,
                ban_reason,
                ban_expires
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$profileUserId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            exit;
        }
        
        // 2. Упрощенная статистика (без проверки таблиц)
        $stats = [
            'orders' => 0,
            'reviews' => 0,
            'tickets' => 0
        ];
        
        // 3. Пытаемся получить статистику (если таблицы существуют)
        try {
            // Проверяем таблицу orders
            $tableCheck = $pdo->query("SHOW TABLES LIKE 'orders'")->fetch();
            if ($tableCheck) {
                // Проверяем какие столбцы есть
                $columns = $pdo->query("SHOW COLUMNS FROM orders")->fetchAll(PDO::FETCH_COLUMN);
                
                if (in_array('user_id', $columns)) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
                    $stmt->execute([$profileUserId]);
                    $stats['orders'] = $stmt->fetchColumn();
                } elseif (in_array('buyer_id', $columns)) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE buyer_id = ?");
                    $stmt->execute([$profileUserId]);
                    $stats['orders'] = $stmt->fetchColumn();
                }
            }
        } catch (Exception $e) {
            // Игнорируем ошибки статистики
        }
        
        try {
            // Проверяем таблицу tickets
            $tableCheck = $pdo->query("SHOW TABLES LIKE 'tickets'")->fetch();
            if ($tableCheck) {
                $columns = $pdo->query("SHOW COLUMNS FROM tickets")->fetchAll(PDO::FETCH_COLUMN);
                
                if (in_array('user_id', $columns)) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE user_id = ?");
                    $stmt->execute([$profileUserId]);
                    $stats['tickets'] = $stmt->fetchColumn();
                } elseif (in_array('creator_id', $columns)) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE creator_id = ?");
                    $stmt->execute([$profileUserId]);
                    $stats['tickets'] = $stmt->fetchColumn();
                }
            }
        } catch (Exception $e) {
            // Игнорируем
        }
        
        $user['stats'] = $stats;
        
        echo json_encode(['status' => 'success', 'user' => $user]);
        
    } catch (Exception $e) {
        error_log("Profile error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;

case 'send_private_message':
    // Проверка авторизации
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $receiver_id = (int)$_POST['receiver_id'];
    $message = trim($_POST['message']);
    
    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Сообщение не может быть пустым']);
        exit;
    }
    
    if ($receiver_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный получатель']);
        exit;
    }
    
    try {
        // Сохраняем сообщение
        $stmt = $pdo->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user']['id'], $receiver_id, $message]);
        
        // Создаем уведомление для получателя
        $sender_name = $_SESSION['user']['username'] ?: $_SESSION['user']['login'];
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, created_at) VALUES (?, ?, ?, 'private_message', NOW())");
        $stmt->execute([
            $receiver_id, 
            'Новое личное сообщение', 
            $sender_name . ': ' . (strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message)
        ]);
        
        echo json_encode(['status' => 'success', 'message_id' => $pdo->lastInsertId()]);
        
    } catch (PDOException $e) {
        error_log("Ошибка отправки сообщения: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка базы данных']);
    }
    break;

case 'get_conversation':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $other_user_id = (int)$_GET['user_id'];
    $user_id = $_SESSION['user']['id'];
    
    try {
        // Получаем все сообщения между двумя пользователями
        $stmt = $pdo->prepare("
            SELECT pm.*, 
                   u_sender.login as sender_login,
                   u_sender.username as sender_name,
                   u_receiver.login as receiver_login,
                   u_receiver.username as receiver_name
            FROM private_messages pm
            LEFT JOIN users u_sender ON pm.sender_id = u_sender.id
            LEFT JOIN users u_receiver ON pm.receiver_id = u_receiver.id
            WHERE (pm.sender_id = ? AND pm.receiver_id = ?) 
               OR (pm.sender_id = ? AND pm.receiver_id = ?)
            ORDER BY pm.created_at ASC
        ");
        $stmt->execute([$user_id, $other_user_id, $other_user_id, $user_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Помечаем сообщения как прочитанные
        $stmt = $pdo->prepare("UPDATE private_messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ? AND is_read = 0");
        $stmt->execute([$user_id, $other_user_id]);
        
        echo json_encode(['status' => 'success', 'messages' => $messages]);
        
    } catch (PDOException $e) {
        error_log("Ошибка загрузки диалога: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки диалога']);
    }
    break;

case 'get_conversations_list':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        // Получаем список всех диалогов с последним сообщением
        $stmt = $pdo->prepare("
            SELECT 
                u.id as other_user_id,
                u.login,
                u.username,
                u.avatar,
                pm.message as last_message,
                pm.created_at as last_message_time,
                SUM(CASE WHEN pm.receiver_id = ? AND pm.is_read = 0 THEN 1 ELSE 0 END) as unread_count
            FROM (
                SELECT 
                    CASE 
                        WHEN sender_id = ? THEN receiver_id 
                        ELSE sender_id 
                    END as other_user_id,
                    MAX(created_at) as max_time
                FROM private_messages
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END
            ) as last_messages
            JOIN private_messages pm ON (
                (pm.sender_id = ? AND pm.receiver_id = last_messages.other_user_id) 
                OR (pm.receiver_id = ? AND pm.sender_id = last_messages.other_user_id)
            ) AND pm.created_at = last_messages.max_time
            JOIN users u ON u.id = last_messages.other_user_id
            GROUP BY u.id, u.login, u.username, u.avatar, pm.message, pm.created_at
            ORDER BY pm.created_at DESC
        ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['status' => 'success', 'conversations' => $conversations]);
        
    } catch (PDOException $e) {
        error_log("Ошибка загрузки диалогов: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки диалогов']);
    }
    break;

case 'get_conversations':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $userId = $_SESSION['user']['id'];
    
    try {
        // Упрощенный и рабочий запрос
        $stmt = $pdo->prepare("
            SELECT 
                CASE 
                    WHEN from_user_id = ? THEN to_user_id 
                    ELSE from_user_id 
                END as user_id,
                u.login,
                u.username,
                u.role,
                MAX(pm.created_at) as last_message_time,
                (
                    SELECT message 
                    FROM private_messages 
                    WHERE ((from_user_id = ? AND to_user_id = u.id) OR (from_user_id = u.id AND to_user_id = ?))
                    ORDER BY created_at DESC 
                    LIMIT 1
                ) as last_message,
                (
                    SELECT COUNT(*) 
                    FROM private_messages 
                    WHERE from_user_id = u.id 
                    AND to_user_id = ? 
                    AND is_read = 0
                ) as unread_count
            FROM private_messages pm
            JOIN users u ON (
                (pm.from_user_id = ? AND u.id = pm.to_user_id) OR 
                (pm.to_user_id = ? AND u.id = pm.from_user_id)
            )
            WHERE (pm.from_user_id = ? OR pm.to_user_id = ?)
            AND u.id != ?
            GROUP BY u.id, u.login, u.username, u.role
            ORDER BY last_message_time DESC
        ");
        
        $stmt->execute([
            $userId, $userId, $userId, $userId, 
            $userId, $userId, $userId, $userId, $userId
        ]);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Получаем общее количество непрочитанных
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as unread_count 
            FROM private_messages 
            WHERE to_user_id = ? 
            AND is_read = 0
        ");
        $stmt->execute([$userId]);
        $unreadResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $unreadCount = $unreadResult['unread_count'] ?? 0;
        
        echo json_encode([
            'status' => 'success', 
            'conversations' => $conversations ?: [],
            'unread_count' => $unreadCount
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'success', // Возвращаем success даже при ошибке, но пустой массив
            'conversations' => [],
            'unread_count' => 0,
            'debug_error' => $e->getMessage()
        ]);
    }
    break;


case 'send_message':
    try {
        // Старт сессии если еще не начата
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // Проверяем авторизацию
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            exit;
        }
        
        $sender_id = (int)$_SESSION['user']['id'];
        $receiver_id = (int)($_POST['receiver_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        
        // Проверяем данные
        if ($receiver_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Не указан получатель']);
            exit;
        }
        
        if (empty($message)) {
            echo json_encode(['status' => 'error', 'message' => 'Введите текст сообщения']);
            exit;
        }
        
        if ($sender_id === $receiver_id) {
            echo json_encode(['status' => 'error', 'message' => 'Нельзя писать самому себе']);
            exit;
        }
        
        // Проверяем существование таблицы messages
        $table_check = $pdo->query("SHOW TABLES LIKE 'messages'")->fetch();
        if (!$table_check) {
            // Создаем таблицу
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS messages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    sender_id INT NOT NULL,
                    receiver_id INT NOT NULL,
                    message TEXT NOT NULL,
                    is_read TINYINT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }
        
        // Проверяем существование получателя
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND banned = 0");
        $stmt->execute([$receiver_id]);
        if (!$stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Получатель не найден']);
            exit;
        }
        
        // Сохраняем сообщение в БД
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $receiver_id, $message]);
        
        $message_id = $pdo->lastInsertId();
        
        // Получаем имя отправителя для уведомления
        $stmt = $pdo->prepare("SELECT login, username FROM users WHERE id = ?");
        $stmt->execute([$sender_id]);
        $sender = $stmt->fetch();
        $sender_name = $sender['username'] ?: $sender['login'];
        
        // Создаем уведомление для получателя
        $notification_message = "Новое сообщение от " . $sender_name . ": " . (strlen($message) > 50 ? substr($message, 0, 50) . "..." : $message);
        
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, 'Новое сообщение', ?, 'message')");
        $stmt->execute([$receiver_id, $notification_message]);
        
        // Возвращаем успех с деталями
        echo json_encode([
            'status' => 'success',
            'message' => 'Сообщение успешно отправлено!',
            'message_id' => $message_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'timestamp' => date('H:i:s')
        ]);
        
    } catch(PDOException $e) {
        // Логируем ошибку
        error_log("Database error in send_message: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка базы данных',
            'debug' => $e->getMessage()
        ]);
    } catch(Exception $e) {
        error_log("General error in send_message: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Системная ошибка'
        ]);
    }
    break;



    

case 'get_conversations':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    // Получаем последние диалоги для пользователя
    $stmt = $pdo->prepare("
        SELECT 
            u.id as user_id,
            u.login,
            u.username,
            u.avatar,
            m.message as last_message,
            m.created_at as last_message_time,
            (SELECT COUNT(*) FROM messages WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) as unread_count
        FROM users u
        LEFT JOIN messages m ON m.id = (
            SELECT id FROM messages 
            WHERE (sender_id = u.id AND receiver_id = ?) 
               OR (sender_id = ? AND receiver_id = u.id)
            ORDER BY created_at DESC 
            LIMIT 1
        )
        WHERE u.id IN (
            SELECT DISTINCT 
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as other_user
            FROM messages 
            WHERE sender_id = ? OR receiver_id = ?
        )
        AND u.id != ?
        ORDER BY m.created_at DESC
    ");
    $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'conversations' => $conversations]);
    break;

case 'mark_messages_read':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $userId = $_SESSION['user']['id'];
    $otherUserId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    
    try {
        // Помечаем сообщения от другого пользователя как прочитанные
        $stmt = $pdo->prepare("
            UPDATE private_messages 
            SET is_read = 1 
            WHERE from_user_id = ? 
            AND to_user_id = ? 
            AND is_read = 0
        ");
        $stmt->execute([$otherUserId, $userId]);
        
        // Получаем общее количество непрочитанных сообщений
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total_unread 
            FROM private_messages 
            WHERE to_user_id = ? 
            AND is_read = 0
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalUnread = $result['total_unread'] ?? 0;
        
        echo json_encode([
            'status' => 'success', 
            'total_unread' => $totalUnread
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;

    // ДОБАВЬТЕ ЭТОТ КОД после существующего case 'get_products' (примерно на строку 1200)
case 'get_user_products_public':
    // НЕ требует авторизации для просмотра товаров другого пользователя
    // НЕ проверяет PIN
    
    $userId = intval($_GET['user_id'] ?? 0);
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(100, intval($_GET['limit'] ?? 12)));
    $offset = ($page - 1) * $limit;
    
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        break;
    }
    
    try {
        // Проверяем, существует ли пользователь
        $stmt = $pdo->prepare("SELECT id, login FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            break;
        }
        
        // Получаем товары пользователя (только активные)
        $sql = "SELECT 
                    p.*, 
                    u.login as seller_login 
                FROM products p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = ? 
                    AND p.status = 'active' 
                    AND p.stock > 0 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $limit, $offset]);
        $products = $stmt->fetchAll();
        
        // Получаем общее количество товаров
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE user_id = ? AND status = 'active' AND stock > 0");
        $stmt->execute([$userId]);
        $total = $stmt->fetchColumn();
        
        // Рассчитываем финальные цены
        foreach ($products as &$product) {
            $price = floatval($product['price'] ?? 0);
            $discount = intval($product['discount'] ?? 0);
            
            if ($discount > 0 && $discount <= 100) {
                $product['final_price'] = $price * (1 - $discount / 100);
                $product['has_discount'] = true;
                $product['discount_amount'] = $price * ($discount / 100);
            } else {
                $product['final_price'] = $price;
                $product['has_discount'] = false;
                $product['discount_amount'] = 0;
            }
            
            // Форматируем для отображения
            $product['price_formatted'] = number_format($price, 2, '.', '');
            $product['final_price_formatted'] = number_format($product['final_price'], 2, '.', '');
            $product['price_display'] = number_format($price, 2, ',', ' ');
            $product['final_price_display'] = number_format($product['final_price'], 2, ',', ' ');
            $product['old_price'] = $discount > 0 ? $price : null;
        }
        
        echo json_encode([
            'status' => 'success',
            'products' => $products,
            'seller' => [
                'id' => $user['id'],
                'login' => $user['login']
            ],
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => intval($total),
                'pages' => ceil($total / $limit)
            ]
        ]);
        
    } catch (Exception $e) {
        error_log("Ошибка получения товаров пользователя: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ошибка получения товаров']);
    }
    break;

    case 'get_user_products':
case 'user_products':
case 'load_user_products':
case 'get_user_profile_products':
    // Получение товаров конкретного пользователя (публичный доступ)
    
    $userId = intval($_GET['user_id'] ?? $_GET['id'] ?? 0);
    
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        break;
    }
    
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = max(1, min(100, intval($_GET['limit'] ?? 12)));
    $offset = ($page - 1) * $limit;
    
    try {
        // Проверяем существование пользователя
        $stmt = $pdo->prepare("SELECT id, login, username FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            break;
        }
        
        // Получаем товары пользователя
        $sql = "SELECT 
                    p.*,
                    u.login as seller_login
                FROM products p
                JOIN users u ON p.user_id = u.id
                WHERE p.user_id = ?
                    AND p.status = 'active'
                    AND p.stock > 0
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $limit, $offset]);
        $products = $stmt->fetchAll();
        
        // Получаем общее количество
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE user_id = ? AND status = 'active' AND stock > 0");
        $stmt->execute([$userId]);
        $total = $stmt->fetchColumn();
        
        // Обрабатываем цены
        foreach ($products as &$product) {
            $price = floatval($product['price'] ?? 0);
            $discount = intval($product['discount'] ?? 0);
            
            if ($discount > 0 && $discount <= 100) {
                $product['final_price'] = $price * (1 - $discount / 100);
                $product['has_discount'] = true;
            } else {
                $product['final_price'] = $price;
                $product['has_discount'] = false;
            }
            
            // Форматирование
            $product['price_formatted'] = number_format($price, 2, ',', ' ');
            $product['final_price_formatted'] = number_format($product['final_price'], 2, ',', ' ');
            
            // URL изображения
            if (!empty($product['main_image'])) {
                $product['image_url'] = '/' . ltrim($product['main_image'], '/');
            } else {
                $product['image_url'] = null;
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'products' => $products,
            'seller' => [
                'id' => $user['id'],
                'login' => $user['login'],
                'username' => $user['username'] ?? $user['login']
            ],
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => intval($total),
                'pages' => ceil($total / $limit)
            ]
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки товаров']);
    }
    break;

    // Примерная структура API для работы с аватаром
case 'upload_avatar':
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        
        if (isset($_POST['use_default'])) {
            // Удаляем аватар из БД
            $stmt = $pdo->prepare("UPDATE users SET avatar = NULL WHERE id = ?");
            $stmt->execute([$user_id]);
            
            echo json_encode([
                'status' => 'success',
                'initial' => strtoupper(substr($_SESSION['user']['username'], 0, 1))
            ]);
        } elseif (isset($_FILES['avatar'])) {
            // Загрузка файла
            $uploadDir = 'uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $fileName = $user_id . '_' . time() . '.' . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filePath = $uploadDir . $fileName;
            
            // Проверка и сохранение файла
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
                // Обновляем запись в БД
                $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                $stmt->execute([$filePath, $user_id]);
                
                $_SESSION['user']['avatar'] = $filePath;
                
                echo json_encode([
                    'status' => 'success',
                    'avatar_url' => $filePath
                ]);
            }
        }
    }
    break;

case 'remove_avatar':
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        
        $stmt = $pdo->prepare("UPDATE users SET avatar = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
        
        unset($_SESSION['user']['avatar']);
        
        echo json_encode([
            'status' => 'success',
            'initial' => strtoupper(substr($_SESSION['user']['username'], 0, 1))
        ]);
    }
    break;

// Функция для получения реального IP адреса пользователя
function getRealUserIp() {
    $ip = '127.0.0.1'; // По умолчанию
    
    // Проверяем различные HTTP заголовки в правильном порядке
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP из шаред интернета
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP от прокси
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ipList[0]);
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        // IP от сервера (последний резерв)
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'Invalid IP';
}

// Альтернативный вариант (более короткий):
function getUserIp() {
    $headers = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];
    
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = trim(explode(',', $_SERVER[$header])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}



    case 'check_session':
    session_start();
    header('Content-Type: application/json');
    
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        echo json_encode([
            'status' => 'success',
            'user' => $_SESSION['user']
        ]);
    } else {
        // Пробуем получить из других источников
        $user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? 0;
        
        if ($user_id) {
            // Получаем данные пользователя из БД
            $stmt = $pdo->prepare("SELECT id, login, username, banned FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo json_encode([
                    'status' => 'success',
                    'user' => $user
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Сессия не найдена']);
        }
    }
    exit;

// Проверка статуса аккаунта
case 'get_account_status':
    session_start();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    // Проверяем бан
    $stmt = $pdo->prepare("SELECT banned, ban_reason, ban_expires FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    echo json_encode([
        'status' => 'success',
        'banned' => $user['banned'] ?? 0,
        'ban_reason' => $user['ban_reason'] ?? null,
        'ban_expires' => $user['ban_expires'] ?? null
    ]);
    exit;


// Создание апелляции из кабинета
case 'create_appeal_from_cabinet':
    session_start();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $message = trim($_POST['message'] ?? '');
    
    if (empty($message) || strlen($message) < 10) {
        echo json_encode(['status' => 'error', 'message' => 'Сообщение должно содержать минимум 10 символов']);
        exit;
    }
    
    // Вставляем апелляцию
    $stmt = $pdo->prepare("INSERT INTO appeals (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);
    $appeal_id = $pdo->lastInsertId();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Апелляция создана',
        'appeal_id' => $appeal_id
    ]);
    exit;

// Быстрая апелляция (без авторизации)
case 'submit_appeal':
    header('Content-Type: application/json');
    
    $user_id = intval($_POST['user_id'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($user_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Введите корректный ID']);
        exit;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Введите корректный email']);
        exit;
    }
    
    // Проверяем, существует ли пользователь
    $stmt = $pdo->prepare("SELECT id, login FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        exit;
    }
    
    // Создаем таблицу external_appeals если нет
    $check = $pdo->query("SHOW TABLES LIKE 'external_appeals'");
    if (!$check->fetch()) {
        $pdo->exec("
            CREATE TABLE external_appeals (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('pending', 'processing', 'resolved') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
    
    // Вставляем апелляцию
    $stmt = $pdo->prepare("INSERT INTO external_appeals (user_id, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $email, $message]);
    $appeal_id = $pdo->lastInsertId();
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Апелляция принята',
        'appeal_id' => $appeal_id
    ]);
    exit;

// Получение информации о бане
case 'get_ban_info':
    session_start();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    // Получаем информацию о бане
    $stmt = $pdo->prepare("SELECT ban_reason as reason, ban_expires as expires, ban_message as admin_message FROM users WHERE id = ? AND banned = 1");
    $stmt->execute([$user_id]);
    $ban_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ban_info) {
        echo json_encode(['status' => 'success', ...$ban_info]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Информация о бане не найдена']);
    }
    exit;

    // api.php - добавьте этот код в switch(action)
case 'send_personal_message':
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $message = $_POST['message'];
    
    // Сохраняем сообщение в БД
    $stmt = $pdo->prepare("INSERT INTO personal_messages (user_id, admin_id, title, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $_SESSION['user']['id'], $title, $message]);
    
    // Создаем уведомление
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, created_at) VALUES (?, ?, ?, 'personal_message', NOW())");
    $stmt->execute([$user_id, 'Новое сообщение от администратора', $title]);
    
    echo json_encode(['status' => 'success', 'message' => 'Сообщение отправлено']);
    break;

case 'get_personal_messages':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT pm.*, u.username as admin_name FROM personal_messages pm LEFT JOIN users u ON pm.admin_id = u.id WHERE pm.user_id = ? ORDER BY pm.created_at DESC");
    $stmt->execute([$_SESSION['user']['id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'messages' => $messages]);
    break;

// Получить диалоги пользователя
case 'get_dialogs':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    // Получаем всех пользователей, с которыми есть сообщения
    $stmt = $pdo->prepare("
        SELECT DISTINCT 
            CASE 
                WHEN sender_id = ? THEN receiver_id 
                ELSE sender_id 
            END as other_user_id
        FROM messages 
        WHERE sender_id = ? OR receiver_id = ?
    ");
    $stmt->execute([$user_id, $user_id, $user_id]);
    $other_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $dialogs = [];
    foreach ($other_users as $user) {
        $other_user_id = $user['other_user_id'];
        
        // Получаем информацию о пользователе
        $stmt = $pdo->prepare("
            SELECT u.id, u.login, u.username, u.avatar,
                   (SELECT COUNT(*) FROM messages WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) as unread_count,
                   (SELECT message FROM messages WHERE (sender_id = u.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = u.id) ORDER BY created_at DESC LIMIT 1) as last_message,
                   (SELECT created_at FROM messages WHERE (sender_id = u.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = u.id) ORDER BY created_at DESC LIMIT 1) as last_message_time
            FROM users u 
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $other_user_id]);
        $dialog = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($dialog) {
            $dialogs[] = $dialog;
        }
    }
    
    // Сортируем по времени последнего сообщения
    usort($dialogs, function($a, $b) {
        return strtotime($b['last_message_time'] ?? 0) - strtotime($a['last_message_time'] ?? 0);
    });
    
    echo json_encode(['status' => 'success', 'dialogs' => $dialogs]);
    break;

case 'get_messages':
    // Открываем сессию
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    // Проверяем авторизацию
    if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
        exit;
    }
    
    $user_id = (int)$_SESSION['user']['id'];
    $other_user_id = isset($_GET['other_user_id']) ? (int)$_GET['other_user_id'] : 0;
    
    // Логируем для отладки
    error_log("get_messages called: user_id=$user_id, other_user_id=$other_user_id");
    
    // Проверяем ID
    if ($other_user_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID пользователя']);
        exit;
    }
    
    try {
        // Помечаем сообщения как прочитанные
        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
        $stmt->execute([$other_user_id, $user_id]);
        
        // Получаем сообщения (УПРОЩЕННЫЙ ЗАПРОС)
        $stmt = $pdo->prepare("
            SELECT m.*, u.login as sender_login
            FROM messages m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?) 
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$user_id, $other_user_id, $other_user_id, $user_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Логируем результат
        error_log("Found messages: " . count($messages));
        
        echo json_encode([
            'status' => 'success', 
            'messages' => $messages,
            'debug' => [
                'user_id' => $user_id,
                'other_user_id' => $other_user_id,
                'message_count' => count($messages)
            ]
        ]);
        
    } catch(PDOException $e) {
        error_log("Database error in get_messages: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка базы данных',
            'debug' => $e->getMessage()
        ]);
    }
    break;

    // Получение профиля пользователя
case 'get_user_profile':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $requested_user_id = (int)($_GET['user_id'] ?? 0);
    
    if ($requested_user_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Не указан ID пользователя']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT id, login, username, avatar, role, balance, banned, created_at
            FROM users 
            WHERE id = ? AND banned = 0
        ");
        $stmt->execute([$requested_user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Получаем рейтинг пользователя
            $stmt = $pdo->prepare("
                SELECT AVG(rating) as avg_rating 
                FROM reviews 
                WHERE seller_id = ?
            ");
            $stmt->execute([$requested_user_id]);
            $rating = $stmt->fetch();
            $user['rating'] = $rating['avg_rating'] ? number_format($rating['avg_rating'], 1) : '0.0';
            
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        }
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка базы данных']);
    }
    break;




// Пометить как прочитанные
case 'mark_messages_read':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $sender_id = $_POST['sender_id'];
    
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
    $stmt->execute([$sender_id, $user_id]);
    
    echo json_encode(['status' => 'success']);
    break;



    case 'get_product':
    $product_id = intval($_GET['product_id'] ?? 0);
    
    if (!$product_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID товара не указан']);
        break;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, u.login as seller_login 
            FROM products p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            echo json_encode(['status' => 'success', 'product' => $product]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка']);
    }
    break;


   
   
// ===== ПОЛУЧЕНИЕ КУПЛЕННЫХ ТОВАРОВ =====
case 'get_purchased_products':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        break;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        // Проверяем таблицу
        $table_check = $pdo->query("SHOW TABLES LIKE 'purchases'")->fetch();
        if (!$table_check) {
            echo json_encode(['status' => 'success', 'products' => [], 'count' => 0]);
            break;
        }
        
        $stmt = $pdo->prepare("
            SELECT * FROM purchases 
            WHERE user_id = ? 
            ORDER BY purchase_date DESC
        ");
        $stmt->execute([$user_id]);
        $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($purchases as &$p) {
            $p['id'] = $p['product_id'];
            $p['title'] = $p['product_title'];
            $p['price'] = $p['product_price'];
            $p['final_price'] = $p['final_price'] ?? $p['product_price'];
            $p['main_image'] = $p['product_image'];
            $p['seller_login'] = $p['seller_login'] ?? 'Продавец';
            $p['stock'] = 0;
            $p['purchase_date'] = $p['purchase_date'];
        }
        
        echo json_encode([
            'status' => 'success', 
            'products' => $purchases,
            'count' => count($purchases)
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'success', 'products' => [], 'count' => 0]);
    }
    break;

function createOrder() {
    global $pdo;
    
    if(!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        return;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        // 1. СОЗДАЕМ ТАБЛИЦУ PURCHASES (ЕСЛИ НЕТ)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS purchases (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                product_title VARCHAR(255),
                product_price DECIMAL(10,2),
                product_discount INT DEFAULT 0,
                final_price DECIMAL(10,2),
                quantity INT DEFAULT 1,
                seller_id INT,
                seller_login VARCHAR(100),
                product_image VARCHAR(500),
                order_id VARCHAR(50),
                purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user (user_id),
                INDEX idx_product (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // 2. ПОЛУЧАЕМ КОРЗИНУ
        $stmt = $pdo->prepare("
            SELECT 
                c.product_id, 
                c.quantity,
                p.title, 
                p.price, 
                p.discount,
                p.stock,
                p.main_image,
                p.user_id as seller_id,
                u.login as seller_login
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            LEFT JOIN users u ON p.user_id = u.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($cart_items)) {
            echo json_encode(['status' => 'error', 'message' => 'Корзина пуста']);
            return;
        }
        
        $pdo->beginTransaction();
        
        $purchased_ids = [];
        $order_id = 'ORD-' . time() . '-' . $user_id;
        
        foreach ($cart_items as $item) {
            $product_id = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            
            // 3. УМЕНЬШАЕМ КОЛИЧЕСТВО
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$quantity, $product_id]);
            
            // 4. РАССЧИТЫВАЕМ ЦЕНУ
            $price = floatval($item['price'] ?? 0);
            $discount = intval($item['discount'] ?? 0);
            $final_price = $discount > 0 ? $price * (1 - $discount/100) : $price;
            
            // ⚡⚡⚡ 5. СОХРАНЯЕМ В PURCHASES - ЭТО КЛЮЧЕВОЕ!
            $stmt = $pdo->prepare("
                INSERT INTO purchases (
                    user_id, product_id, product_title, product_price,
                    product_discount, final_price, quantity, seller_id,
                    seller_login, product_image, order_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $user_id,
                $product_id,
                $item['title'] ?? 'Товар',
                $price,
                $discount,
                $final_price,
                $quantity,
                $item['seller_id'] ?? 0,
                $item['seller_login'] ?? 'Продавец',
                $item['main_image'] ?? null,
                $order_id
            ]);
            
            // ЛОГ ДЛЯ ПРОВЕРКИ
            error_log("INSERT purchases: " . ($result ? "OK" : "FAIL") . " для товара #$product_id");
            
            $purchased_ids[] = $product_id;
        }
        
        // 6. ОЧИЩАЕМ КОРЗИНУ
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        $pdo->commit();
        
        // 7. ВОЗВРАЩАЕМ УСПЕХ
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Заказ оформлен!',
            'purchased_product_ids' => $purchased_ids,
            'order_id' => $order_id
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("ОШИБКА createOrder: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
}

// ========== МГНОВЕННАЯ ПОКУПКА ТОВАРА #49 ==========
case 'buy_now':
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        break;
    }
    
    $user_id = $_SESSION['user']['id'];
    $product_id = 49; // ФИКСИРОВАННЫЙ ID
    
    try {
        // УМЕНЬШАЕМ СТОК
        $pdo->prepare("UPDATE products SET stock = stock - 1 WHERE id = ?")->execute([$product_id]);
        
        // СОХРАНЯЕМ В purchases
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS purchases (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                product_title VARCHAR(255),
                product_price DECIMAL(10,2),
                final_price DECIMAL(10,2),
                quantity INT DEFAULT 1,
                seller_login VARCHAR(100),
                product_image VARCHAR(500),
                purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // ПОЛУЧАЕМ ДАННЫЕ ТОВАРА
        $stmt = $pdo->prepare("SELECT title, price, main_image, user_id FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        // ПОЛУЧАЕМ ПРОДАВЦА
        $seller = 'Продавец';
        if ($product && $product['user_id']) {
            $stmt = $pdo->prepare("SELECT login FROM users WHERE id = ?");
            $stmt->execute([$product['user_id']]);
            $seller_data = $stmt->fetch();
            $seller = $seller_data['login'] ?? 'Продавец';
        }
        
        // ВСТАВЛЯЕМ
        $stmt = $pdo->prepare("
            INSERT INTO purchases (user_id, product_id, product_title, product_price, final_price, quantity, seller_login, product_image)
            VALUES (?, ?, ?, ?, ?, 1, ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $product_id,
            $product['title'] ?? 'Товар',
            $product['price'] ?? 0,
            $product['price'] ?? 0,
            $seller,
            $product['main_image'] ?? null
        ]);
        
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Товар #49 куплен!',
            'purchased_product_ids' => [$product_id]
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;


// ========== ПОЛУЧИТЬ АПЕЛЛЯЦИИ ПОЛЬЗОВАТЕЛЯ ==========
case 'get_user_appeals':
    global $conn;
    
    $user_id = (int)($_GET['user_id'] ?? $_POST['user_id'] ?? 0);
    
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        exit;
    }
    
    // ❌ НЕТ ПРОВЕРКИ СЕССИИ! Любой может посмотреть апелляции по ID
    $sql = "SELECT * FROM appeals WHERE user_id = $user_id ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $appeals = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $appeals[] = $row;
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'appeals' => $appeals,
        'count' => count($appeals)
    ]);
    exit;
    break;

// ===== АДМИН: ОТВЕТИТЬ НА АПЕЛЛЯЦИЮ =====
case 'admin_reply_appeal':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
        exit;
    }
    
    $appeal_id = (int)($_POST['appeal_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $ticket_id = (int)($_POST['ticket_id'] ?? 0);
    $response = trim($_POST['response'] ?? '');
    
    if (!$appeal_id || !$user_id || !$response) {
        echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
        exit;
    }
    
    // Экранируем
    $response = $conn->real_escape_string($response);
    
    $conn->begin_transaction();
    
    try {
        // Обновляем апелляцию
        $conn->query("UPDATE appeals SET admin_response = '$response', status = 'closed', updated_at = NOW() WHERE id = $appeal_id");
        
        // Если есть тикет, добавляем туда сообщение
        if ($ticket_id) {
            $admin_id = $_SESSION['user']['id'];
            $message = "👨‍⚖️ **Ответ администратора по апелляции:**\n\n$response";
            $message = $conn->real_escape_string($message);
            
            $conn->query("INSERT INTO ticket_messages (ticket_id, user_id, message, created_at) 
                         VALUES ($ticket_id, $admin_id, '$message', NOW())");
        }
        
        // Отправляем уведомление пользователю
        $notif_title = "📬 Ответ на апелляцию";
        $notif_message = "Администратор ответил на вашу апелляцию. Проверьте раздел 'Мои апелляции'.";
        $notif_message = $conn->real_escape_string($notif_message);
        
        $conn->query("INSERT INTO notifications (user_id, type, title, message, created_at) 
                     VALUES ($user_id, 'appeal', '$notif_title', '$notif_message', NOW())");
        
        $conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Ответ отправлен'
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    exit;

   case 'save_cookie_consent':
    // Функция получения реального IP
    function getRealIp() {
        $ip = '0.0.0.0';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $ip = filter_var(trim($ip), FILTER_VALIDATE_IP);
        return $ip ?: '0.0.0.0';
    }

    $consent = $_POST['consent'] ?? 'accepted';
    $ip = getRealIp(); // ← Теперь используем функцию
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $page = $_SERVER['HTTP_REFERER'] ?? 'direct';
    
    // Сохраняем в куки
    setcookie('cookie_consent', $consent, time() + 365*24*60*60, '/');
    setcookie('cookie_consent_time', time(), time() + 365*24*60*60, '/');
    
    // Сохраняем в базу
    try {
        $stmt = $pdo->prepare("INSERT INTO cookie_consent_logs (ip, user_agent, consent, page, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$ip, $user_agent, $consent, $page]);
        
        echo json_encode(['status' => 'success', 'ip' => $ip]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;

   // ========== ПОЛУЧИТЬ АПЕЛЛЯЦИИ ПО ЛОГИНУ ==========
case 'get_user_appeals_by_login':
    global $conn;
    
    $login = $_GET['login'] ?? $_POST['login'] ?? '';
    
    if (!$login) {
        echo json_encode(['status' => 'error', 'message' => 'Логин не указан']);
        exit;
    }
    
    // Сначала находим пользователя по логину
    $login = $conn->real_escape_string($login);
    $user_sql = "SELECT id FROM users WHERE login = '$login' OR username = '$login'";
    $user_result = $conn->query($user_sql);
    $user = $user_result->fetch_assoc();
    
    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
        exit;
    }
    
    $user_id = $user['id'];
    
    // Загружаем апелляции
    $sql = "SELECT * FROM appeals WHERE user_id = $user_id ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $appeals = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $appeals[] = $row;
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'appeals' => $appeals,
        'count' => count($appeals),
        'user_id' => $user_id
    ]);
    exit;
    break;
  


// ========== ПОЛУЧИТЬ ID ПОЛЬЗОВАТЕЛЯ ПО ЛОГИНУ ==========
case 'get_user_id_by_login':
    $login = $_GET['login'] ?? '';
    
    if (!$login) {
        echo json_encode(['status' => 'error', 'message' => 'Логин не указан']);
        exit;
    }
    
    $login = $conn->real_escape_string($login);
    $sql = "SELECT id, login, banned FROM users WHERE login = '$login' OR username = '$login' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'user_id' => $user['id'],
            'login' => $user['login'],
            'banned' => $user['banned']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
    }
    break;


    // ========== ПОЛУЧИТЬ СООБЩЕНИЯ АПЕЛЛЯЦИИ ==========
case 'get_appeal_messages':
    $appeal_id = (int)($_GET['appeal_id'] ?? 0);
    
    if (!$appeal_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID апелляции не указан']);
        exit;
    }
    
    $sql = "SELECT am.*, u.login as sender_login, u.username 
            FROM appeal_messages am 
            LEFT JOIN users u ON am.user_id = u.id 
            WHERE am.appeal_id = $appeal_id 
            ORDER BY am.created_at ASC";
    
    $result = $conn->query($sql);
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'messages' => $messages
    ]);
    break;

// ========== ПОЛУЧИТЬ ИНФОРМАЦИЮ О ТОВАРЕ ==========
case 'get_product_info':
    $product_id = (int)($_GET['product_id'] ?? 0);
    
    if (!$product_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID товара не указан']);
        exit;
    }
    
    $sql = "SELECT p.*, u.id as seller_id, u.login as seller_login 
            FROM products p 
            JOIN users u ON p.seller_id = u.id 
            WHERE p.id = ? AND p.status = 'active'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 'success',
            'product' => [
                'id' => $row['id'],
                'title' => $row['title'],
                'seller_id' => (int)$row['seller_id'],
                'seller_login' => $row['seller_login']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
    }
    break;

    // ===== ПОЛУЧИТЬ МОИ ТОВАРЫ =====
case 'get_my_products':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_GET['user_id'] ?? $_SESSION['user']['id'];
    
    $query = "SELECT p.*, 
                     (SELECT COUNT(*) FROM order_items oi WHERE oi.product_id = p.id) as sold,
                     0 as views
              FROM products p 
              WHERE p.seller_id = $user_id 
              ORDER BY p.id DESC";
    
    $result = $conn->query($query);
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        // Получаем главное изображение
        $img_query = "SELECT image_url FROM product_images WHERE product_id = {$row['id']} AND is_main = 1 LIMIT 1";
        $img_result = $conn->query($img_query);
        if ($img_result->num_rows > 0) {
            $row['main_image'] = $img_result->fetch_assoc()['image_url'];
        } else {
            $row['main_image'] = null;
        }
        $products[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'products' => $products
    ]);
    exit;

// ===== ОБНОВИТЬ СТАТУС ТОВАРА =====
case 'update_product_status':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $product_id = (int)$_POST['product_id'];
    $status = $_POST['status'] === 'active' ? 'active' : 'inactive';
    $user_id = $_SESSION['user']['id'];
    
    // Проверяем, что товар принадлежит пользователю
    $check = $conn->query("SELECT id FROM products WHERE id = $product_id AND seller_id = $user_id");
    if ($check->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
        exit;
    }
    
    $conn->query("UPDATE products SET status = '$status' WHERE id = $product_id");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Статус обновлен'
    ]);
    exit;

// ===== УДАЛИТЬ ТОВАР =====
case 'delete_product':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user']['id'];
    
    // Проверяем, что товар принадлежит пользователю
    $check = $conn->query("SELECT id FROM products WHERE id = $product_id AND seller_id = $user_id");
    if ($check->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
        exit;
    }
    
    // Удаляем изображения
    $conn->query("DELETE FROM product_images WHERE product_id = $product_id");
    
    // Удаляем товар
    $conn->query("DELETE FROM products WHERE id = $product_id");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Товар удален'
    ]);
    exit;


// ОБНОВЛЕНИЕ БАЛАНСА В СЕССИИ
case 'update_session_balance':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $balance = $_POST['balance'] ?? 0;
    
    // Обновляем баланс в сессии
    $_SESSION['user']['balance'] = floatval($balance);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Сессия обновлена'
    ]);
    exit;

    // ===== ПОЛУЧИТЬ ТОВАРЫ ПОЛЬЗОВАТЕЛЯ =====
case 'get_user_products':
    header('Content-Type: application/json');
    
    $user_id = (int)($_GET['user_id'] ?? 0);
    $status = $_GET['status'] ?? 'active';
    
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID пользователя не указан']);
        exit;
    }
    
    // Получаем только активные товары
    $status_filter = $status === 'all' ? '' : " AND p.status = 'active'";
    
    $query = "SELECT p.*, 
                     (SELECT image_url FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) as main_image,
                     u.login as seller_login
              FROM products p 
              LEFT JOIN users u ON p.seller_id = u.id 
              WHERE p.seller_id = $user_id $status_filter
              ORDER BY p.id DESC";
    
    $result = $conn->query($query);
    
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка запроса: ' . $conn->error]);
        exit;
    }
    
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        $row['price'] = (float)$row['price'];
        $row['discount'] = (int)$row['discount'];
        $row['stock'] = (int)$row['stock'];
        $products[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'products' => $products,
        'count' => count($products)
    ]);
    exit;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие']);
        break;
}
?>