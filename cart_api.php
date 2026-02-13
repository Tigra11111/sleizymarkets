<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

session_start();
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'sleizy_market';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    ob_clean();
    die(json_encode(['status' => 'error', 'message' => 'Ошибка подключения к БД']));
}
$conn->set_charset('utf8mb4');

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
    exit;
}

$user_id = $_SESSION['user']['id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    
   // ===== ПОЛУЧИТЬ КОРЗИНУ =====
case 'get_cart':
    $sql = "SELECT 
                c.id as cart_id,
                c.quantity,
                p.id as product_id,
                p.title,
                p.price,
                p.discount,
                p.stock,
                p.main_image,
                p.seller_id,
                u.login as seller_login,
                u.id as user_id
            FROM cart c 
            INNER JOIN products p ON c.product_id = p.id 
            INNER JOIN users u ON p.seller_id = u.id 
            WHERE c.user_id = $user_id AND p.status = 'active'";
    
    $result = $conn->query($sql);
    $items = [];
    $subtotal = 0;
    
    while ($row = $result->fetch_assoc()) {
        $final_price = $row['price'] * (1 - $row['discount'] / 100);
        $item_total = $final_price * $row['quantity'];
        $subtotal += $item_total;
        
        $items[] = [
            'cart_id' => (int)$row['cart_id'],
            'product_id' => (int)$row['product_id'],
            'title' => $row['title'],
            'price' => (float)$row['price'],
            'discount' => (int)$row['discount'],
            'final_price' => round($final_price, 2),
            'quantity' => (int)$row['quantity'],
            'item_total' => round($item_total, 2),
            'stock' => (int)$row['stock'],
            'seller_id' => (int)$row['seller_id'],
            'seller_login' => $row['seller_login'],
            'image' => $row['main_image']
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'items' => $items,
        'summary' => [
            'subtotal' => round($subtotal, 2),
            'total' => round($subtotal, 2),
            'total_quantity' => array_sum(array_column($items, 'quantity'))
        ]
    ]);
    break;
    

    // ===== ДОБАВИТЬ В КОРЗИНУ - УЛЬТРА ПРОВЕРКА =====
case 'add_to_cart':
    $product_id = (int)($_POST['product_id'] ?? 0);
    
    if (!$product_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID товара не указан']);
        break;
    }
    
    // ПОЛУЧАЕМ ИНФОРМАЦИЮ О ТОВАРЕ
    $product = $conn->query("SELECT p.*, u.id as seller_id, u.login as seller_login 
                            FROM products p 
                            JOIN users u ON p.seller_id = u.id 
                            WHERE p.id = $product_id AND p.status = 'active'");
    
    if ($product->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Товар не найден']);
        break;
    }
    
    $product_data = $product->fetch_assoc();
    
    // ========== ЖЕСТКАЯ ПРОВЕРКА ==========
    if ((int)$product_data['seller_id'] === (int)$user_id) {
        echo json_encode([
            'status' => 'error', 
            'message' => '❌ Нельзя добавить свой товар в корзину',
            'debug' => [
                'your_id' => $user_id,
                'seller_id' => (int)$product_data['seller_id'],
                'product' => $product_data['title']
            ]
        ]);
        break;
    }
    
    // Проверка наличия
    if ($product_data['stock'] < 1) {
        echo json_encode(['status' => 'error', 'message' => '❌ Товара нет в наличии']);
        break;
    }
    
    // Проверяем, есть ли уже в корзине
    $existing = $conn->query("SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($existing->num_rows > 0) {
        $cart_item = $existing->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + 1;
        
        if ($new_quantity > $product_data['stock']) {
            echo json_encode(['status' => 'error', 'message' => '❌ Превышает количество в наличии']);
            break;
        }
        
        $conn->query("UPDATE cart SET quantity = $new_quantity WHERE id = {$cart_item['id']}");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES ($user_id, $product_id, 1, NOW())");
    }
    
    echo json_encode(['status' => 'success', 'message' => '✅ Товар добавлен в корзину']);
    break;
    
    // ===== 3. УДАЛИТЬ ИЗ КОРЗИНЫ =====
    case 'remove_from_cart':
        $cart_id = (int)($_POST['cart_id'] ?? 0);
        $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
        echo json_encode(['status' => 'success']);
        break;
    
    // ===== 4. ОЧИСТИТЬ КОРЗИНУ =====
    case 'clear_cart':
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        echo json_encode(['status' => 'success']);
        break;
    
    // ===== 5. ПОЛУЧИТЬ БАЛАНС =====
    case 'get_balance':
        $result = $conn->query("SELECT balance FROM users WHERE id = $user_id");
        $balance = $result->fetch_assoc()['balance'] ?? 0;
        echo json_encode(['status' => 'success', 'balance' => (float)$balance]);
        break;
    
    // ===== 6. ПОЛУЧИТЬ КОЛИЧЕСТВО ТОВАРОВ В КОРЗИНЕ =====
    case 'get_cart_count':
        $result = $conn->query("SELECT SUM(quantity) as count FROM cart WHERE user_id = $user_id");
        $count = $result->fetch_assoc()['count'] ?? 0;
        echo json_encode(['status' => 'success', 'count' => (int)$count]);
        break;
    
 

        // ===== СОЗДАТЬ ЗАКАЗ =====
case 'create_order':
    $payment_method = $_POST['payment_method'] ?? 'balance';
    
    $conn->begin_transaction();
    
    try {
        // ===== 1. ПРОВЕРЯЕМ ПРОМОКОД В СЕССИИ =====
        $discount = 0;
        $promo_id = null;
        $promo_code = null;
        $applied_promo = $_SESSION['applied_promo'] ?? null;
        
        if ($applied_promo) {
            $promo_id = (int)$applied_promo['id'];
            $promo_code = $conn->real_escape_string($applied_promo['code']);
            $discount = (float)$applied_promo['discount_amount'];
            
            // Проверяем, что промокод все еще активен
            $check_promo = $conn->query("SELECT id FROM promos WHERE id = $promo_id AND is_active = 1");
            if ($check_promo->num_rows == 0) {
                // Промокод больше не активен - сбрасываем скидку
                $discount = 0;
                $promo_id = null;
                $promo_code = null;
                unset($_SESSION['applied_promo']);
            }
        }
        
        // ===== 2. ПОЛУЧАЕМ КОРЗИНУ =====
        $cart = $conn->query("SELECT c.*, 
                                    p.id as product_id,
                                    p.title, 
                                    p.price, 
                                    p.discount, 
                                    p.seller_id, 
                                    p.stock
                             FROM cart c 
                             JOIN products p ON c.product_id = p.id 
                             WHERE c.user_id = $user_id AND p.status = 'active'");
        
        if ($cart->num_rows == 0) {
            throw new Exception('Корзина пуста');
        }
        
        $subtotal = 0;
        $order_items = [];
        $seller_payments = [];
        $purchased_product_ids = [];
        
        // ===== 3. РАССЧИТЫВАЕМ ПОДИТОГ =====
        while ($item = $cart->fetch_assoc()) {
            // ПРОВЕРКА - НЕЛЬЗЯ КУПИТЬ СВОЙ ТОВАР
            if ($item['seller_id'] == $user_id) {
                throw new Exception("Нельзя купить свой товар: {$item['title']}");
            }
            
            // ПРОВЕРКА НАЛИЧИЯ
            if ($item['stock'] < $item['quantity']) {
                throw new Exception("Товар '{$item['title']}' закончился в наличии");
            }
            
            // РАССЧИТЫВАЕМ ЦЕНУ СО СКИДКОЙ ТОВАРА
            $price = $item['price'] * (1 - $item['discount'] / 100);
            $item_total = $price * $item['quantity'];
            $subtotal += $item_total;
            
            $order_items[] = $item;
            $purchased_product_ids[] = $item['product_id'];
            
            // СОБИРАЕМ ПЛАТЕЖИ ДЛЯ ПРОДАВЦОВ
            if (!isset($seller_payments[$item['seller_id']])) {
                $seller_payments[$item['seller_id']] = 0;
            }
            $seller_payments[$item['seller_id']] += $item_total;
        }
        
        $subtotal = round($subtotal, 2);
        
        // ===== 4. ПРИМЕНЯЕМ ПРОМОКОД К ПОДИТОГУ =====
        $total = round($subtotal - $discount, 2);
        
        // ===== 5. ПРОВЕРКА БАЛАНСА =====
        if ($payment_method == 'balance') {
            $user_balance = $conn->query("SELECT balance FROM users WHERE id = $user_id")->fetch_assoc()['balance'];
            if ($user_balance < $total) {
                throw new Exception('Недостаточно средств на балансе');
            }
            // СПИСЫВАЕМ ДЕНЬГИ У ПОКУПАТЕЛЯ
            $conn->query("UPDATE users SET balance = balance - $total WHERE id = $user_id");
        }
        
        // ===== 6. СОЗДАЕМ ЗАКАЗ =====
        $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
        $now = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO orders (order_number, user_id, total_amount, payment_method, promo_code, promo_discount, status, created_at, paid_at) 
                VALUES ('$order_number', $user_id, $total, '$payment_method', " . ($promo_code ? "'$promo_code'" : "NULL") . ", $discount, 'paid', '$now', '$now')";
        
        if (!$conn->query($sql)) {
            throw new Exception('Ошибка создания заказа: ' . $conn->error);
        }
        
        $order_id = $conn->insert_id;
        
      // ===== 7. ДОБАВЛЯЕМ ТОВАРЫ В ЗАКАЗ =====
foreach ($order_items as $item) {
    $price = $item['price'] * (1 - $item['discount'] / 100);
    $total_item = $price * $item['quantity'];
    $title = $conn->real_escape_string($item['title']);
    
    // 👇 ЗАПОЛНЯЕМ И total И total_price
    $item_sql = "INSERT INTO order_items (order_id, product_id, title, price, discount, quantity, total, total_price, seller_id) 
                VALUES ($order_id, {$item['product_id']}, '$title', {$item['price']}, {$item['discount']}, {$item['quantity']}, $total_item, $total_item, {$item['seller_id']})";
    
    if (!$conn->query($item_sql)) {
        throw new Exception('Ошибка добавления товара в заказ: ' . $conn->error);
    }
    
    // УМЕНЬШАЕМ СТОК ТОВАРА
    $conn->query("UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['product_id']}");
    
    // УДАЛЯЕМ ИЗ КОРЗИНЫ
    $conn->query("DELETE FROM cart WHERE id = {$item['id']}");
}
        
        // ===== 8. ОБНОВЛЯЕМ СЧЕТЧИК ИСПОЛЬЗОВАНИЙ ПРОМОКОДА =====
        if ($promo_id) {
            $conn->query("UPDATE promos SET uses = uses + 1 WHERE id = $promo_id");
            
            // Записываем использование промокода
            $conn->query("INSERT INTO promo_uses (promo_id, user_id, order_id, discount_amount) 
                         VALUES ($promo_id, $user_id, $order_id, $discount)");
            
            // Очищаем сессию
            unset($_SESSION['applied_promo']);
        }
        
        // ===== 9. НАЧИСЛЯЕМ ДЕНЬГИ ПРОДАВЦАМ =====
        foreach ($seller_payments as $seller_id => $amount) {
            // Проверяем есть ли запись в seller_balance
            $check = $conn->query("SELECT id FROM seller_balance WHERE user_id = $seller_id");
            
            if ($check->num_rows == 0) {
                // Создаем новый баланс продавца
                $conn->query("INSERT INTO seller_balance (user_id, pending_balance, total_earned) 
                             VALUES ($seller_id, $amount, $amount)");
            } else {
                // Обновляем существующий баланс
                $conn->query("UPDATE seller_balance 
                             SET pending_balance = pending_balance + $amount,
                                 total_earned = total_earned + $amount 
                             WHERE user_id = $seller_id");
            }
            
            // Создаем транзакцию для продавца
            $conn->query("INSERT INTO seller_transactions (user_id, order_id, amount, status, created_at) 
                         VALUES ($seller_id, $order_id, $amount, 'pending', NOW())");
        }
        
        // ===== 10. ПОЛУЧАЕМ НОВЫЙ БАЛАНС ПОКУПАТЕЛЯ =====
        $new_balance = $conn->query("SELECT balance FROM users WHERE id = $user_id")->fetch_assoc()['balance'];
        
        $conn->commit();
        
        // ===== 11. ОБНОВЛЯЕМ СЕССИЮ =====
        $_SESSION['user']['balance'] = $new_balance;
        
        // ===== 12. ОТПРАВЛЯЕМ УСПЕШНЫЙ ОТВЕТ =====
        echo json_encode([
            'status' => 'success',
            'message' => 'Заказ успешно оформлен',
            'order_id' => $order_id,
            'order_number' => $order_number,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_paid' => $total,
            'new_balance' => (float)$new_balance,
            'purchased_product_ids' => $purchased_product_ids,
            'promo_applied' => $promo_code ? true : false,
            'promo_code' => $promo_code,
            'promo_discount' => $discount
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage()
        ]);
    }
    break;

   case 'apply_promo':
case 'apply_cart_promo':
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
        // Подключение к БД (если нет глобального $pdo)
        global $pdo;
        if (!isset($pdo)) {
            // Настройки подключения - ЗАМЕНИ НА СВОИ!
            $host = 'localhost';
            $dbname = 'sleizy_market';
            $username = 'root';
            $password = '';
            
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        // Ищем промокод в таблице promos
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
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM promo_uses 
                                  WHERE promo_id = ? AND user_id = ?");
            $stmt->execute([$promo['id'], $user_id]);
            $user_uses = $stmt->fetchColumn();
            
            if ($user_uses >= $promo['per_user_limit']) {
                echo json_encode(['status' => 'error', 'message' => 'Вы уже использовали этот промокод']);
                exit;
            }
        }
        
        // Получаем корзину пользователя
        $stmt = $pdo->prepare("SELECT c.*, p.price, p.discount 
                              FROM cart c 
                              JOIN products p ON c.product_id = p.id 
                              WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($cart_items)) {
            echo json_encode(['status' => 'error', 'message' => 'Корзина пуста']);
            exit;
        }
        
        // Рассчитываем сумму заказа
        $subtotal = 0;
        foreach ($cart_items as $item) {
            $item_price = $item['price'];
            if ($item['discount'] > 0) {
                $item_price = $item['price'] * (1 - $item['discount'] / 100);
            }
            $subtotal += $item_price * $item['quantity'];
        }
        $subtotal = round($subtotal, 2);
        
        // Проверяем минимальную сумму
        if ($promo['min_order'] > 0 && $subtotal < $promo['min_order']) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Минимальная сумма заказа: ' . number_format($promo['min_order'], 2, '.', '') . ' ₽'
            ]);
            exit;
        }
        
        // Рассчитываем скидку
        $discount_amount = 0;
        if ($promo['type'] === 'discount') {
            $discount_amount = round($subtotal * ($promo['value'] / 100), 2);
            if ($promo['max_discount'] > 0 && $discount_amount > $promo['max_discount']) {
                $discount_amount = round($promo['max_discount'], 2);
            }
        }
        
        $total = round($subtotal - $discount_amount, 2);
        
        // Сохраняем в сессию
        $_SESSION['applied_promo'] = [
            'id' => $promo['id'],
            'code' => $promo['code'],
            'type' => $promo['type'],
            'value' => $promo['value'],
            'discount_amount' => $discount_amount
        ];
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Промокод применен!',
            'subtotal' => $subtotal,
            'discount' => $discount_amount,
            'total' => $total,
            'promo' => [
                'code' => $promo['code'],
                'type' => $promo['type'],
                'value' => floatval($promo['value']),
                'discount' => $discount_amount
            ]
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка базы данных: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
    exit;

    
  
// ===== ПОЛУЧИТЬ ЗАКАЗЫ =====
case 'get_orders':
    $orders_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 50";
    $orders_result = $conn->query($orders_sql);
    
    $orders = [];
    while ($order = $orders_result->fetch_assoc()) {
        // ✅ ПОЛУЧАЕМ ТОВАРЫ ДЛЯ КАЖДОГО ЗАКАЗА
        $items_sql = "SELECT * FROM order_items WHERE order_id = {$order['id']}";
        $items_result = $conn->query($items_sql);
        $items = [];
        
        while ($item = $items_result->fetch_assoc()) {
            $items[] = [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'title' => $item['title'],
                'price' => (float)$item['price'],
                'discount' => (int)$item['discount'],
                'quantity' => (int)$item['quantity'],
                'total_price' => (float)$item['total_price'],
                'seller_id' => (int)$item['seller_id']
            ];
        }
        
        $orders[] = [
            'id' => $order['id'],
            'order_number' => $order['order_number'],
            'total_amount' => (float)$order['total_amount'],
            'payment_method' => $order['payment_method'],
            'status' => $order['status'],
            'created_at' => $order['created_at'],
            'items' => $items // ✅ ТОВАРЫ ДОЛЖНЫ БЫТЬ ЗДЕСЬ
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'orders' => $orders
    ]);
    break;

    
 // ===== ПОЛУЧИТЬ БАЛАНС ПРОДАВЦА =====
case 'get_seller_balance':
    // Проверяем существование таблицы
    $table_check = $conn->query("SHOW TABLES LIKE 'seller_balance'");
    if ($table_check->num_rows == 0) {
        echo json_encode(['status' => 'success', 'balance' => ['available' => 0, 'pending' => 0, 'total' => 0]]);
        break;
    }
    
    // Получаем или создаем баланс
    $result = $conn->query("SELECT * FROM seller_balance WHERE user_id = $user_id");
    
    if ($result->num_rows == 0) {
        $conn->query("INSERT INTO seller_balance (user_id) VALUES ($user_id)");
        $balance = ['available_balance' => 0, 'pending_balance' => 0, 'total_earned' => 0];
    } else {
        $balance = $result->fetch_assoc();
    }
    
    echo json_encode([
        'status' => 'success',
        'balance' => [
            'available' => (float)($balance['available_balance'] ?? 0),
            'pending' => (float)($balance['pending_balance'] ?? 0),
            'total' => (float)($balance['total_earned'] ?? 0)
        ]
    ]);
    break;

// ===== ПОЛУЧИТЬ ТРАНЗАКЦИИ ПРОДАВЦА =====
case 'get_seller_transactions':
    $sql = "SELECT * FROM seller_transactions 
            WHERE user_id = $user_id 
            ORDER BY created_at DESC 
            LIMIT 50";
    
    $result = $conn->query($sql);
    $transactions = [];
    
    while ($row = $result->fetch_assoc()) {
        $transactions[] = [
            'id' => $row['id'],
            'order_id' => $row['order_id'],
            'order_number' => $row['order_number'],
            'amount' => (float)$row['amount'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'completed_at' => $row['completed_at']
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'transactions' => $transactions
    ]);
    break;

// ===== ПОЛУЧИТЬ УВЕДОМЛЕНИЯ О ПРОДАЖАХ (ТОЛЬКО НОВЫЕ) =====
case 'get_seller_notifications':
    // ПОКАЗЫВАЕМ ТОЛЬКО ТРАНЗАКЦИИ ЗА ПОСЛЕДНИЕ 24 ЧАСА
    $sql = "SELECT * FROM seller_transactions 
            WHERE user_id = $user_id 
            AND status = 'pending' 
            AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    $notifications = [];
    $total_amount = 0;
    
    while ($row = $result->fetch_assoc()) {
        $total_amount += $row['amount'];
        $notifications[] = [
            'id' => $row['id'],
            'order_id' => $row['order_id'],
            'amount' => (float)$row['amount'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'notifications' => $notifications,
        'count' => count($notifications),
        'total' => $total_amount
    ]);
    break;

// ===== ПОДТВЕРДИТЬ ПОЛУЧЕНИЕ ДЕНЕГ =====
case 'confirm_seller_payment':
    $transaction_id = (int)($_POST['transaction_id'] ?? 0);
    
    if (!$transaction_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID транзакции не указан']);
        break;
    }
    
    $conn->begin_transaction();
    
    try {
        // Получаем транзакцию
        $trans = $conn->query("SELECT * FROM seller_transactions 
                              WHERE id = $transaction_id AND user_id = $user_id AND status = 'pending'");
        
        if ($trans->num_rows == 0) {
            throw new Exception('Транзакция не найдена или уже подтверждена');
        }
        
        $transaction = $trans->fetch_assoc();
        $amount = $transaction['amount'];
        
        // Обновляем баланс продавца (уменьшаем pending, увеличиваем available)
        $conn->query("UPDATE seller_balance 
                     SET available_balance = available_balance + $amount,
                         pending_balance = pending_balance - $amount 
                     WHERE user_id = $user_id");
        
        // Обновляем статус транзакции
        $conn->query("UPDATE seller_transactions 
                     SET status = 'completed', completed_at = NOW() 
                     WHERE id = $transaction_id");
        
        // ✅ НАЧИСЛЯЕМ НА ОСНОВНОЙ БАЛАНС ТОЛЬКО ПРИ ПОДТВЕРЖДЕНИИ!
        $conn->query("UPDATE users SET balance = balance + $amount WHERE id = $user_id");
        
        $conn->commit();
        
        // Получаем новый баланс
        $new_balance = $conn->query("SELECT balance FROM users WHERE id = $user_id")->fetch_assoc()['balance'];
        $_SESSION['user']['balance'] = $new_balance;
        
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Деньги зачислены на основной баланс',
            'amount' => $amount,
            'new_balance' => (float)$new_balance
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;

 // ===== ПОДТВЕРДИТЬ ВСЕ ПЛАТЕЖИ =====
case 'confirm_all_pending':
    $conn->begin_transaction();
    
    try {
        // Получаем сумму всех ожидающих платежей
        $result = $conn->query("SELECT SUM(amount) as total FROM seller_transactions 
                               WHERE user_id = $user_id AND status = 'pending'");
        $total = $result->fetch_assoc()['total'] ?? 0;
        
        if ($total <= 0) {
            throw new Exception('Нет ожидающих платежей');
        }
        
        // Обновляем баланс продавца
        $conn->query("UPDATE seller_balance 
                     SET available_balance = available_balance + $total,
                         pending_balance = pending_balance - $total 
                     WHERE user_id = $user_id");
        
        // Обновляем все транзакции
        $conn->query("UPDATE seller_transactions 
                     SET status = 'completed', completed_at = NOW() 
                     WHERE user_id = $user_id AND status = 'pending'");
        
        // ✅ НАЧИСЛЯЕМ НА ОСНОВНОЙ БАЛАНС
        $conn->query("UPDATE users SET balance = balance + $total WHERE id = $user_id");
        
        $conn->commit();
        
        $new_balance = $conn->query("SELECT balance FROM users WHERE id = $user_id")->fetch_assoc()['balance'];
        $_SESSION['user']['balance'] = $new_balance;
        
        echo json_encode([
            'status' => 'success',
            'message' => "✅ Зачислено $total ₽ на основной баланс",
            'amount' => $total,
            'new_balance' => (float)$new_balance
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    break;
    
    // ===== 10. ПОЛУЧИТЬ УВЕДОМЛЕНИЯ ПРОДАВЦА =====
    case 'get_seller_notifications':
        echo json_encode(['status' => 'success', 'notifications' => [], 'count' => 0]);
        break;
    
    // ===== 11. ПОДТВЕРДИТЬ ПЛАТЕЖ ПРОДАВЦА =====
    case 'confirm_seller_payment':
        echo json_encode(['status' => 'error', 'message' => 'Функция в разработке']);
        break;
    
    // ===== 12. ПОДТВЕРДИТЬ ВСЕ ПЛАТЕЖИ =====
    case 'confirm_all_pending':
        echo json_encode(['status' => 'error', 'message' => 'Функция в разработке']);
        break;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие: ' . $action]);
}

$conn->close();
?>