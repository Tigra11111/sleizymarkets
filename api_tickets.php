<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Включим отладку
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Настройки БД
$host = 'localhost';
$dbname = 'sleizy_market';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка БД: ' . $e->getMessage()]);
    exit;
}

// Получаем action
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);
if ($input === null) {
    $input = $_POST;
}

// Проверка авторизации - РАЗРЕШАЕМ ЗАБАНЕННЫХ!
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        return ['status' => 'error', 'message' => 'Требуется авторизация'];
    }
    return null;
}

// Проверка авторизации с особыми правами для забаненных
function checkAuthWithAppeal() {
    if (!isset($_SESSION['user'])) {
        return ['status' => 'error', 'message' => 'Требуется авторизация'];
    }
    
    // Если пользователь забанен, проверяем флаг appeal_mode
    $user_status = $_SESSION['user']['status'] ?? 'active';
    if ($user_status === 'banned') {
        if (!isset($_SESSION['appeal_mode']) || $_SESSION['appeal_mode'] !== true) {
            return ['status' => 'error', 'message' => 'Для подачи апелляции используйте кнопку на странице блокировки'];
        }
    }
    
    return null;
}

// Создаем таблицу для тикетов если нет
function ensureTicketsTable($pdo) {
    try {
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'tickets'")->fetch(PDO::FETCH_ASSOC);
        
        if (!$tableCheck) {
            $sql = "CREATE TABLE tickets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                subject VARCHAR(200) NOT NULL DEFAULT 'Без темы',
                message TEXT NOT NULL,
                status VARCHAR(20) DEFAULT 'open',
                is_appeal BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                INDEX idx_status (status),
                INDEX idx_is_appeal (is_appeal)
            )";
            $pdo->exec($sql);
            
            // Создаем таблицу для сообщений тикетов
            $sql2 = "CREATE TABLE ticket_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ticket_id INT NOT NULL,
                user_id INT NOT NULL,
                message TEXT NOT NULL,
                is_admin BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_ticket_id (ticket_id),
                FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
            )";
            $pdo->exec($sql2);
            
            return ['status' => 'success', 'message' => 'Таблицы созданы'];
        }
        return ['status' => 'success', 'message' => 'Таблицы существуют'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Ошибка создания таблиц: ' . $e->getMessage()];
    }
}

switch ($action) {
    // ========================
    // ПОЛУЧЕНИЕ ТИКЕТОВ ПОЛЬЗОВАТЕЛЯ
    // ========================
    case 'get_my_tickets':
        // Для этого действия проверяем обычную авторизацию
        // Забаненные должны иметь доступ к своим апелляциям
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        $user_id = $_SESSION['user']['id'];
        $user_status = $_SESSION['user']['status'] ?? 'active';
        $limit = intval($_GET['limit'] ?? 50);
        
        try {
            ensureTicketsTable($pdo);
            
            // Если пользователь забанен, показываем только апелляции
            if ($user_status === 'banned') {
                $sql = "
                    SELECT t.*, 
                           (SELECT COUNT(*) FROM ticket_messages tm WHERE tm.ticket_id = t.id) as message_count
                    FROM tickets t
                    WHERE t.user_id = ? AND t.is_appeal = TRUE
                    ORDER BY t.updated_at DESC
                    LIMIT ?
                ";
            } else {
                $sql = "
                    SELECT t.*, 
                           (SELECT COUNT(*) FROM ticket_messages tm WHERE tm.ticket_id = t.id) as message_count
                    FROM tickets t
                    WHERE t.user_id = ?
                    ORDER BY t.updated_at DESC
                    LIMIT ?
                ";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'status' => 'success',
                'tickets' => $tickets
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Ошибка SQL: ' . $e->getMessage()
            ]);
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
    
   case 'get_ticket_messages':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $ticket_id = (int)($_GET['ticket_id'] ?? 0);
    
    if (!$ticket_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID тикета не указан']);
        exit;
    }
    
    try {
        // СНАЧАЛА ПРОВЕРЯЕМ СУЩЕСТВОВАНИЕ ТИКЕТА
        $ticket_query = "SELECT * FROM tickets WHERE id = $ticket_id";
        $ticket_result = $conn->query($ticket_query);
        
        if ($ticket_result->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Тикет не найден']);
            exit;
        }
        
        $ticket = $ticket_result->fetch_assoc();
        
        // ПРОВЕРЯЕМ ПРАВА ДОСТУПА
        $is_admin = false;
        $admin_check = $conn->query("SELECT role FROM users WHERE id = $user_id");
        if ($admin_check->num_rows > 0) {
            $user_data = $admin_check->fetch_assoc();
            $is_admin = ($user_data['role'] === 'admin');
        }
        
        if ($ticket['user_id'] != $user_id && !$is_admin) {
            echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
            exit;
        }
        
        // ПОЛУЧАЕМ СООБЩЕНИЯ
        $query = "SELECT m.*, u.login, u.role 
                  FROM ticket_messages m
                  LEFT JOIN users u ON m.user_id = u.id
                  WHERE m.ticket_id = $ticket_id 
                  ORDER BY m.created_at ASC";
        
        $result = $conn->query($query);
        
        if (!$result) {
            throw new Exception($conn->error);
        }
        
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            // Добавляем флаг is_admin для удобства
            $row['is_admin'] = ($row['role'] === 'admin');
            $messages[] = $row;
        }
        
        echo json_encode([
            'status' => 'success',
            'messages' => $messages,
            'ticket' => $ticket
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    exit;
    
    

        case 'add_ticket_message':
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $ticket_id = (int)($_POST['ticket_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    
    if (!$ticket_id || !$message) {
        echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
        exit;
    }
    
    try {
        // Проверяем, принадлежит ли тикет пользователю или пользователь - админ
        $check_query = "SELECT t.* FROM tickets t 
                       WHERE t.id = $ticket_id 
                       AND (t.user_id = $user_id OR 
                            EXISTS (SELECT 1 FROM users WHERE id = $user_id AND role = 'admin'))";
        
        $check = $conn->query($check_query);
        
        if ($check->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Доступ запрещен']);
            exit;
        }
        
        // ВСТАВЛЯЕМ СООБЩЕНИЕ БЕЗ is_admin!
        $message_esc = $conn->real_escape_string($message);
        $query = "INSERT INTO ticket_messages (ticket_id, user_id, message, created_at) 
                  VALUES ($ticket_id, $user_id, '$message_esc', NOW())";
        
        if (!$conn->query($query)) {
            throw new Exception('Ошибка сохранения: ' . $conn->error);
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Сообщение отправлено'
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    exit;
    
    // ========================
    // ЗАКРЫТИЕ ТИКЕТА
    // ========================
    case 'close_ticket':
        // Простая проверка - если есть пользователь в сессии
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        $ticket_id = intval($input['ticket_id'] ?? 0);
        $user_id = $_SESSION['user']['id'];
        $user_role = $_SESSION['user']['role'] ?? 'user';
        $user_status = $_SESSION['user']['status'] ?? 'active';
        
        if (!$ticket_id) {
            echo json_encode(['status' => 'error', 'message' => 'Не указан ID тикета']);
            break;
        }
        
        try {
            ensureTicketsTable($pdo);
            
            // Проверяем доступ к тикету
            $checkSql = "SELECT user_id, is_appeal FROM tickets WHERE id = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$ticket_id]);
            $ticket = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$ticket) {
                echo json_encode(['status' => 'error', 'message' => 'Тикет не найден']);
                break;
            }
            
            // Проверяем права доступа
            if ($user_status === 'banned') {
                // Забаненные не могут закрывать тикеты
                echo json_encode(['status' => 'error', 'message' => 'Забаненные пользователи не могут закрывать тикеты']);
                break;
            }
            
            if ($user_role !== 'admin' && $ticket['user_id'] != $user_id) {
                echo json_encode(['status' => 'error', 'message' => 'Нет доступа к этому тикету']);
                break;
            }
            
            // Закрываем тикет
            $stmt = $pdo->prepare("
                UPDATE tickets SET status = 'closed', updated_at = CURRENT_TIMESTAMP WHERE id = ?
            ");
            $stmt->execute([$ticket_id]);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Тикет закрыт'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    
    // ========================
    // ОТМЕТКА ЧТО АПЕЛЛЯЦИЯ ОТПРАВЛЕНА
    // ========================
    case 'appeal_submitted':
        // Просто возвращаем успех - не требует проверки
        echo json_encode(['status' => 'success', 'message' => 'OK']);
        break;
    
    // ========================
    // ОКОНЧАНИЕ СЕССИИ АПЕЛЛЯЦИИ
    // ========================
    case 'end_appeal_session':
        // Убираем флаг режима апелляции
        if (isset($_SESSION['appeal_mode'])) {
            unset($_SESSION['appeal_mode']);
        }
        echo json_encode(['status' => 'success', 'message' => 'Сессия завершена']);
        break;
    
    // ========================
    // ПРОВЕРКА ТАБЛИЦ
    // ========================
    case 'check_tables':
        $result = ensureTicketsTable($pdo);
        echo json_encode($result);
        break;
    
    // ========================
    // ОЧИСТКА ТАБЛИЦ (для тестов)
    // ========================
    case 'clear_tables':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Требуется авторизация']);
            break;
        }
        
        if ($_SESSION['user']['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Только для админов']);
            break;
        }
        
        try {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            $pdo->exec("TRUNCATE TABLE ticket_messages");
            $pdo->exec("TRUNCATE TABLE tickets");
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            echo json_encode(['status' => 'success', 'message' => 'Таблицы очищены']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие: ' . $action]);
        break;
}
?>