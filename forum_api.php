<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Настройки БД
$host = 'localhost';
$dbname = 'sleizy_market';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка БД']);
    exit;
}

// Получаем action
$action = $_GET['action'] ?? '';

// Проверка авторизации
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        return ['status' => 'error', 'message' => 'Требуется авторизация'];
    }
    return null;
}

switch ($action) {
    // ========================
    // ПОЛУЧЕНИЕ ЖАЛОБ - ПРОСТОЙ ВАРИАНТ
    // ========================
    case 'get_complaints':
        $auth = checkAuth();
        if ($auth) {
            echo json_encode($auth);
            break;
        }
        
        if (!isset($_SESSION['forum_access'])) {
            echo json_encode(['status' => 'error', 'message' => 'Доступ к форуму запрещен']);
            break;
        }
        
        $user_id = $_SESSION['user']['id'];
        $user_role = $_SESSION['user']['role'] ?? 'user';
        $status = $_GET['status'] ?? '';
        $my_complaints = isset($_GET['my_complaints']) ? true : false;
        
        try {
            // ПРОСТОЙ SQL ЗАПРОС БЕЗ ФИЛЬТРОВ
            if ($my_complaints) {
                // Мои жалобы
                $sql = "SELECT c.*, u.login as accused_login 
                        FROM complaints c 
                        LEFT JOIN users u ON c.accused_id = u.id 
                        WHERE c.complainant_id = ? 
                        ORDER BY c.created_at DESC 
                        LIMIT 20";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);
            } else {
                // Все жалобы
                $sql = "SELECT c.*, 
                               u1.login as complainant_login,
                               u2.login as accused_login
                        FROM complaints c
                        LEFT JOIN users u1 ON c.complainant_id = u1.id
                        LEFT JOIN users u2 ON c.accused_id = u2.id
                        ORDER BY c.created_at DESC 
                        LIMIT 50";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
            
            $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'status' => 'success',
                'complaints' => $complaints
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    
    // ========================
    // СОЗДАНИЕ ЖАЛОБЫ
    // ========================
    case 'create_complaint':
        $auth = checkAuth();
        if ($auth) {
            echo json_encode($auth);
            break;
        }
        
        if (!isset($_SESSION['forum_access'])) {
            echo json_encode(['status' => 'error', 'message' => 'Доступ к форуму запрещен']);
            break;
        }
        
        $complainant_id = $_SESSION['user']['id'];
        $accused_id = intval($_POST['accused_id'] ?? 0);
        $title = trim($_POST['title'] ?? 'Без темы');
        $reason = trim($_POST['reason'] ?? 'scam');
        $description = trim($_POST['description'] ?? '');
        $evidence = trim($_POST['evidence'] ?? '');
        
        if (empty($title) || empty($description)) {
            echo json_encode(['status' => 'error', 'message' => 'Заполните тему и описание']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO complaints (complainant_id, accused_id, title, reason, description, evidence, status)
                VALUES (?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$complainant_id, $accused_id, $title, $reason, $description, $evidence]);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Жалоба отправлена',
                'complaint_id' => $pdo->lastInsertId()
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    
    // ========================
    // ПОЛУЧЕНИЕ ОДНОЙ ЖАЛОБЫ
    // ========================
    case 'get_complaint':
        $auth = checkAuth();
        if ($auth) {
            echo json_encode($auth);
            break;
        }
        
        if (!isset($_SESSION['forum_access'])) {
            echo json_encode(['status' => 'error', 'message' => 'Доступ к форуму запрещен']);
            break;
        }
        
        $complaint_id = intval($_GET['id'] ?? 0);
        
        if (!$complaint_id) {
            echo json_encode(['status' => 'error', 'message' => 'Не указан ID']);
            break;
        }
        
        try {
            $sql = "
                SELECT c.*, 
                       u1.login as complainant_login,
                       u2.login as accused_login
                FROM complaints c
                LEFT JOIN users u1 ON c.complainant_id = u1.id
                LEFT JOIN users u2 ON c.accused_id = u2.id
                WHERE c.id = ?
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$complaint_id]);
            $complaint = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$complaint) {
                echo json_encode(['status' => 'error', 'message' => 'Жалоба не найдена']);
                break;
            }
            
            echo json_encode([
                'status' => 'success',
                'complaint' => $complaint
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    
    // ========================
    // ПОИСК ПОЛЬЗОВАТЕЛЕЙ
    // ========================
    case 'search_users':
        $auth = checkAuth();
        if ($auth) {
            echo json_encode($auth);
            break;
        }
        
        $query = trim($_GET['query'] ?? '');
        
        if (strlen($query) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Минимум 2 символа']);
            break;
        }
        
        try {
            $searchTerm = "%" . $query . "%";
            
            $sql = "SELECT id, login, username FROM users 
                    WHERE (login LIKE ? OR username LIKE ?) 
                    AND id != ? 
                    LIMIT 10";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$searchTerm, $searchTerm, $_SESSION['user']['id']]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'status' => 'success',
                'users' => $users
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка поиска']);
        }
        break;
    
    // ========================
    // ИЗМЕНЕНИЕ СТАТУСА
    // ========================
    case 'update_complaint_status':
        $auth = checkAuth();
        if ($auth) {
            echo json_encode($auth);
            break;
        }
        
        if (!isset($_SESSION['forum_access'])) {
            echo json_encode(['status' => 'error', 'message' => 'Доступ к форуму запрещен']);
            break;
        }
        
        if ($_SESSION['user']['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Только для админов']);
            break;
        }
        
        $complaint_id = intval($_POST['complaint_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        if (!$complaint_id) {
            echo json_encode(['status' => 'error', 'message' => 'Не указана жалоба']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("
                UPDATE complaints 
                SET status = ?, admin_comment = ?, admin_id = ?
                WHERE id = ?
            ");
            $stmt->execute([$status, $comment, $_SESSION['user']['id'], $complaint_id]);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Статус обновлен'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    
    // ========================
    // АВТОРИЗАЦИЯ НА ФОРУМ
    // ========================
    case 'forum_auth':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        $password = trim($_POST['password'] ?? '');
        
        if (empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Введите пароль']);
            break;
        }
        
        try {
            // Проверяем пароль
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user']['id']]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($password, $user['password'])) {
                echo json_encode(['status' => 'error', 'message' => 'Неверный пароль']);
                break;
            }
            
            // Устанавливаем доступ
            $_SESSION['forum_access'] = true;
            $_SESSION['forum_auth_time'] = time();
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Доступ разрешен'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка']);
        }
        break;
    
    case 'forum_logout':
        unset($_SESSION['forum_access']);
        echo json_encode(['status' => 'success', 'message' => 'Выход выполнен']);
        break;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие']);
        break;
}
?>