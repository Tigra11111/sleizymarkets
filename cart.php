<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: res.php');
    exit;
}

// Подключение к БД
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'sleizy_market';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Ошибка подключения к БД');
}
$conn->set_charset('utf8mb4');

$user_id = $_SESSION['user']['id'];
$user_balance = $_SESSION['user']['balance'] ?? 0;

// Проверяем уведомления о продажах для текущего пользователя (как продавца)
$seller_notifications = [];
$notif_query = "SELECT * FROM notifications WHERE user_id = $user_id AND type = 'sale' AND is_read = 0 ORDER BY created_at DESC LIMIT 5";
$notif_result = $conn->query($notif_query);
while ($row = $notif_result->fetch_assoc()) {
    $seller_notifications[] = $row;
}

// Помечаем уведомления как прочитанные
if (count($seller_notifications) > 0) {
    $conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id AND type = 'sale'");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина - Маркетплейс</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --success: #06d6a0;
            --danger: #ef476f;
            --warning: #ffd166;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --gray: #6c757d;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f4f5f7;
            color: var(--dark);
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .balance-badge {
            background: linear-gradient(135deg, var(--primary), #7209b7);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
        }
        
        .container {
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .cart-items {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #edf2f7;
            transition: all 0.2s;
            position: relative;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item.own-product {
            background: #fff5f5;
            border-left: 4px solid var(--danger);
        }
        
        .item-image {
            width: 100px;
            height: 100px;
            background: linear-gradient(145deg, #f1f5f9, #e9eef3);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            color: var(--gray);
            font-size: 40px;
            overflow: hidden;
        }
        
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .item-seller {
            font-size: 14px;
            color: var(--primary);
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .item-seller i {
            margin-right: 5px;
        }
        
        .item-price {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .current-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .old-price {
            text-decoration: line-through;
            color: var(--gray);
            font-size: 16px;
        }
        
        .discount-badge {
            background: var(--danger);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .stock-status {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .in-stock {
            color: var(--success);
        }
        
        .out-of-stock {
            color: var(--danger);
        }
        
        .own-product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #6c757d;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8f9fa;
            padding: 5px 15px;
            border-radius: 25px;
        }
        
        .qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--dark);
        }
        
        .qty-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .qty-input {
            width: 70px;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px;
            font-weight: 600;
            background: #f8f9fa;
        }
        
        .remove-btn {
            background: none;
            border: 1px solid var(--danger);
            color: var(--danger);
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remove-btn:hover:not(:disabled) {
            background: var(--danger);
            color: white;
        }
        
        .remove-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: var(--gray);
            color: var(--gray);
        }
        
        .item-total {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            text-align: right;
            min-width: 150px;
        }
        
        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
            position: sticky;
            top: 100px;
        }
        
        .summary-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #edf2f7;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #edf2f7;
        }
        
        .summary-total {
            background: #f8fafc;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, var(--primary), #7209b7);
            color: white;
            border: none;
            padding: 16px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 15px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .checkout-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        }
        
        .checkout-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
        }
        
        .empty-cart {
            text-align: center;
            padding: 80px 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        }
        
        .empty-icon {
            font-size: 80px;
            color: var(--gray);
            margin-bottom: 25px;
        }
        
        /* Модалка оплаты */
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .payment-modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlide 0.3s;
        }
        
        @keyframes modalSlide {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .payment-method {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        
        .payment-method:hover,
        .payment-method.selected {
            border-color: var(--primary);
            background: #eef2ff;
        }
        
        .payment-method i {
            font-size: 30px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        /* Уведомления */
        .notification-modal {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            max-width: 400px;
            animation: slideInRight 0.3s;
        }
        
        .notification-item {
            background: white;
            border-left: 6px solid;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .item-image {
                margin-bottom: 15px;
            }
            
            .item-total {
                text-align: left;
                margin-top: 15px;
                width: 100%;
            }
            
            .payment-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="res.php" class="navbar-brand">
                    <i class="fas fa-store"></i>
                    <span>Маркетплейс</span>
                </a>
                
                <div class="d-flex align-items-center gap-3">
                    <div class="balance-badge">
                        <i class="fas fa-wallet"></i>
                        <span id="userBalance"><?php echo number_format($user_balance, 2); ?></span> ₽
                    </div>
                    
                    <button id="ordersButton" class="btn btn-outline-primary">
                        <i class="fas fa-box me-2"></i> Мои заказы
                    </button>
                    
                    <a href="res.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Уведомления о продажах (для продавца) -->
    <div id="sellerNotifications" class="notification-modal">
        <?php foreach ($seller_notifications as $notification): ?>
        <div class="notification-item" style="border-left-color: #4CAF50;">
            <div style="width: 50px; height: 50px; background: #4CAF20; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div style="flex: 1;">
                <h6 style="margin: 0 0 5px 0; color: #333;"><?php echo htmlspecialchars($notification['title']); ?></h6>
                <p style="margin: 0; color: #666; font-size: 14px;"><?php echo htmlspecialchars($notification['message']); ?></p>
                <small style="color: #999;"><?php echo date('d.m.Y H:i', strtotime($notification['created_at'])); ?></small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="container">
        <div class="row">
            <!-- Левая колонка - товары -->
            <div class="col-lg-8">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        Корзина
                    </h1>
                    <span id="cartBadge" class="badge bg-primary" style="display: none;">0</span>
                </div>
                
                <div id="cartItemsContainer" class="cart-items">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3 text-muted">Загрузка корзины...</p>
                    </div>
                </div>
                
                <div id="emptyCart" class="empty-cart" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2 class="empty-title">Корзина пуста</h2>
                    <p class="text-muted mb-4">Добавьте товары, чтобы оформить заказ</p>
                    <a href="res.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-left"></i> Вернуться к покупкам
                    </a>
                </div>
            </div>
            
            <!-- Правая колонка - итоги -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h3 class="summary-title">
                        <i class="fas fa-file-invoice"></i>
                        Сводка заказа
                    </h3>
                    
                    <div class="summary-row">
                        <span>Товары:</span>
                        <span id="subtotal" class="fw-bold">0.00 ₽</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Скидка:</span>
                        <span id="discount" class="fw-bold text-success">-0.00 ₽</span>
                    </div>
                    
                    <div id="promoRow" class="summary-row" style="display: none;">
                        <span>Промокод:</span>
                        <span id="promoDiscount" class="fw-bold text-success">-0.00 ₽</span>
                    </div>
                    
                    <div class="summary-total">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5">Итого:</span>
                            <span id="total" class="fs-2 fw-bold text-primary">0.00 ₽</span>
                        </div>
                    </div>
                    
                    <button id="checkoutBtn" class="checkout-btn" disabled>
                        <i class="fas fa-lock"></i>
                        <span>Оформить заказ</span>
                    </button>
                    
                    <div class="mt-4 d-flex justify-content-between text-muted small">
                        <span><i class="fas fa-shield-alt"></i> Безопасная оплата</span>
                        <span><i class="fas fa-undo"></i> Возврат 14 дней</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно оплаты -->
    <div id="paymentModal" class="payment-modal">
        <div class="payment-modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">
                    <i class="fas fa-credit-card" style="color: var(--primary);"></i>
                    Оформление заказа
                </h3>
                <button onclick="closePaymentModal()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #666;">&times;</button>
            </div>
            
            <h6 style="font-weight: 700; margin-bottom: 15px;">Способ оплаты</h6>
            
            <div class="payment-methods">
                <div id="cardMethod" class="payment-method selected" onclick="selectPaymentMethod('card')">
                    <i class="far fa-credit-card"></i>
                    <span style="display: block; font-weight: 600;">Банковская карта</span>
                </div>
                <div id="balanceMethod" class="payment-method" onclick="selectPaymentMethod('balance')">
                    <i class="fas fa-wallet"></i>
                    <span style="display: block; font-weight: 600;">Баланс</span>
                </div>
            </div>
            
            <!-- Форма карты -->
            <div id="cardForm">
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; margin-bottom: 5px; display: block;">Номер карты</label>
                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19" id="cardNumber">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="font-weight: 600; margin-bottom: 5px; display: block;">Срок действия</label>
                        <input type="text" class="form-control" placeholder="ММ/ГГ" id="cardExpiry">
                    </div>
                    <div>
                        <label style="font-weight: 600; margin-bottom: 5px; display: block;">CVV</label>
                        <input type="text" class="form-control" placeholder="123" maxlength="3" id="cardCvv">
                    </div>
                </div>
            </div>
            
            <!-- Баланс -->
<div id="balanceForm" style="display: none;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 15px; text-align: center; margin-bottom: 20px;">
        <i class="fas fa-wallet" style="font-size: 48px; color: var(--primary); margin-bottom: 15px;"></i>
        <h5 style="margin-bottom: 10px;">Ваш баланс: <span id="modalBalance"><?php echo number_format($user_balance, 2); ?></span> ₽</h5>
        
        <!-- 👇 СЮДА ВСТАВЛЯЕМ checkout-summary -->
        <div id="checkout-summary" style="margin-bottom: 20px; text-align: left;">
            <!-- Сюда будет вставляться детали заказа -->
        </div>
        
        <p style="color: #666; margin-bottom: 5px;">Сумма заказа: <span id="orderTotal">0.00 ₽</span></p>
        
        <div id="balanceWarning" style="color: var(--danger); font-weight: 600; margin-top: 10px; display: none;">
            ❌ Недостаточно средств!
        </div>
    </div>
</div>
            
            <!-- Промокод -->
            <div style="margin-top: 20px;">
                <div style="display: flex; gap: 10px;">
                   <input type="text" id="cart-promo-code" class="form-control" placeholder="Введите промокод">
                    <button onclick="applyPromo()" style="background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer;">Применить</button>
                </div>
                <div id="promoMessage" style="margin-top: 10px; font-size: 14px;"></div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button onclick="closePaymentModal()" style="flex: 1; background: #6c757d; color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 600; cursor: pointer;">Отмена</button>
                <button id="payButton" onclick="processOrder()" style="flex: 2; background: linear-gradient(135deg, var(--primary), #7209b7); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fas fa-arrow-right"></i> Оплатить заказ
                </button>
            </div>
        </div>
    </div>

    <!-- Модальное окно Мои заказы -->
    <div class="modal fade" id="ordersModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-box me-2"></i>
                        Мои заказы
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="ordersList" style="min-height: 200px;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-3 text-muted">Загрузка заказов...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // ==========================================
    // ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
    // ==========================================
    const API_URL = 'cart_api.php';
  let currentUser = <?php 
    $user = $_SESSION['user'];
    // ПРЕОБРАЗУЕМ balance В ЧИСЛО!
    $user['balance'] = (float)$user['balance'];
    echo json_encode($user); 
?>;
    let cartData = null;
    let selectedPaymentMethod = 'card';
    
    // ==========================================
    // ИНИЦИАЛИЗАЦИЯ
    // ==========================================
    $(document).ready(function() {
        console.log('✅ Корзина загружена');
        loadCart();
        
        // Автоматически скрываем уведомления через 5 секунд
        setTimeout(function() {
            $('#sellerNotifications').fadeOut();
        }, 8000);
        
        // Обработчик кнопки оформления заказа
        $('#checkoutBtn').on('click', function(e) {
            e.preventDefault();
            
            if (!cartData || !cartData.items || cartData.items.length === 0) {
                Swal.fire('❌ Ошибка', 'Корзина пуста', 'warning');
                return;
            }
            
            // Проверяем, есть ли свои товары в корзине
            const hasOwnProducts = cartData.items.some(item => item.seller_id == currentUser.id);
            if (hasOwnProducts) {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Нельзя купить свои товары',
                    text: 'Удалите свои товары из корзины перед оформлением заказа',
                    confirmButtonText: 'Понятно'
                });
                return;
            }
            
            // Заполняем модалку
            $('#orderTotal').text(cartData.summary.total.toFixed(2) + ' ₽');
           $('#modalBalance').text(parseFloat(currentUser.balance || 0).toFixed(2));
            
            // Проверяем баланс
            if (cartData.summary.total > currentUser.balance) {
                $('#balanceWarning').show();
            } else {
                $('#balanceWarning').hide();
            }
            
            // Показываем модалку
            openPaymentModal();
        });
        
        // Обработчик кнопки "Мои заказы"
        $('#ordersButton').on('click', function() {
            loadUserOrders();
            $('#ordersModal').modal('show');
        });
    });
    
    // ==========================================
    // ЗАГРУЗКА КОРЗИНЫ
    // ==========================================
    function loadCart() {
        $.ajax({
            url: `${API_URL}?action=get_cart`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    cartData = response;
                    
                    if (response.items && response.items.length > 0) {
                        renderCart(response.items);
                        updateSummary(response.summary);
                        $('#checkoutBtn').prop('disabled', false);
                        $('#emptyCart').hide();
                        $('#cartItemsContainer').show();
                        $('#cartBadge').text(response.summary.total_quantity).show();
                    } else {
                        $('#cartItemsContainer').hide();
                        $('#emptyCart').show();
                        $('#checkoutBtn').prop('disabled', true);
                        $('#cartBadge').hide();
                    }
                }
            },
            error: function(xhr) {
                console.error('Ошибка загрузки корзины:', xhr.responseText);
                showToast('Ошибка загрузки корзины', 'error');
            }
        });
    }
    
    // ==========================================
    // ОТОБРАЖЕНИЕ КОРЗИНЫ
    // ==========================================
    function renderCart(items) {
        let html = '';
        
        items.forEach(item => {
            const isOwn = item.seller_id == currentUser.id;
            const outOfStock = item.stock <= 0;
            
            html += `
                <div class="cart-item ${isOwn ? 'own-product' : ''}" data-cart-id="${item.cart_id}">
                    ${isOwn ? '<span class="own-product-badge"><i class="fas fa-store"></i> Ваш товар</span>' : ''}
                    <div class="item-image">
                        ${item.image ? `<img src="${item.image}" alt="${escapeHtml(item.title)}">` : '<i class="fas fa-box"></i>'}
                    </div>
                    <div class="item-details">
                        <h4 class="item-title">${escapeHtml(item.title)}</h4>
                        <div class="item-seller">
                            <i class="fas fa-store"></i> ${escapeHtml(item.seller_login || 'Продавец')}
                        </div>
                        <div class="item-price">
                            <span class="current-price">${item.final_price.toFixed(2)} ₽</span>
                            ${item.discount > 0 ? `
                                <span class="old-price">${item.price.toFixed(2)} ₽</span>
                                <span class="discount-badge">-${item.discount}%</span>
                            ` : ''}
                        </div>
                        <div class="stock-status ${outOfStock ? 'out-of-stock' : 'in-stock'} mb-3">
                            <i class="fas ${outOfStock ? 'fa-times-circle' : 'fa-check-circle'}"></i>
                            ${outOfStock ? 'Нет в наличии' : `${item.stock} шт.`}
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="quantity-control">
                                <button class="qty-btn" onclick="updateQuantity(${item.cart_id}, ${item.quantity - 1})" 
                                    ${isOwn || outOfStock || item.quantity <= 1 ? 'disabled' : ''}>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="qty-input" value="${item.quantity}" 
                                    min="1" max="${item.stock}" readonly>
                                <button class="qty-btn" onclick="updateQuantity(${item.cart_id}, ${item.quantity + 1})" 
                                    ${isOwn || outOfStock || item.quantity >= item.stock ? 'disabled' : ''}>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button class="remove-btn" onclick="removeItem(${item.cart_id})" ${isOwn ? 'disabled' : ''}>
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </div>
                    </div>
                    <div class="item-total">
                        ${item.item_total.toFixed(2)} ₽
                    </div>
                </div>
            `;
        });
        
        $('#cartItemsContainer').html(html);
    }
    
    // ==========================================
    // ОБНОВЛЕНИЕ СУММЫ
    // ==========================================
    function updateSummary(summary) {
        $('#subtotal').text((summary.subtotal || 0).toFixed(2) + ' ₽');
        $('#discount').text('-0.00 ₽');
        $('#total').text((summary.total || 0).toFixed(2) + ' ₽');
        $('#orderTotal').text((summary.total || 0).toFixed(2) + ' ₽');
    }
    
    // ==========================================
    // ОБНОВЛЕНИЕ КОЛИЧЕСТВА
    // ==========================================
    function updateQuantity(cartId, quantity) {
        if (quantity < 1) {
            removeItem(cartId);
            return;
        }
        
        $.ajax({
            url: `${API_URL}?action=update_cart_quantity`,
            method: 'POST',
            data: { cart_id: cartId, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadCart();
                } else {
                    showToast(response.message || 'Ошибка обновления', 'error');
                }
            },
            error: function() {
                showToast('Ошибка соединения', 'error');
            }
        });
    }
    
    // ==========================================
    // УДАЛЕНИЕ ТОВАРА
    // ==========================================
    function removeItem(cartId) {
        if (!confirm('Удалить товар из корзины?')) return;
        
        $.ajax({
            url: `${API_URL}?action=remove_from_cart`,
            method: 'POST',
            data: { cart_id: cartId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadCart();
                    showToast('Товар удален из корзины', 'success');
                }
            },
            error: function() {
                showToast('Ошибка удаления', 'error');
            }
        });
    }
    
   // ========== ОТКРЫТИЕ МОДАЛКИ ОПЛАТЫ ==========
function openPaymentModal() {
    $('#orderTotal').text(parseFloat(cartData.summary.total || 0).toFixed(2) + ' ₽');
    $('#modalBalance').text(parseFloat(currentUser.balance || 0).toFixed(2));
    
    if (parseFloat(cartData.summary.total) > parseFloat(currentUser.balance || 0)) {
        $('#balanceWarning').show();
    } else {
        $('#balanceWarning').hide();
    }
    
    $('#paymentModal').css('display', 'flex');
}


function processOrder() {
    if (selectedPaymentMethod === 'balance' && cartData.summary.total > currentUser.balance) {
        Swal.fire('❌ Ошибка', 'Недостаточно средств', 'error');
        return;
    }
    
    const btn = $('#payButton');
    btn.html('<i class="fas fa-spinner fa-spin"></i> Обработка...').prop('disabled', true);
    
    $.ajax({
        url: `${API_URL}?action=create_order`,
        method: 'POST',
        data: { payment_method: selectedPaymentMethod },
        dataType: 'json',
        success: function(res) {
            btn.html('<i class="fas fa-arrow-right"></i> Оплатить').prop('disabled', false);
            
            if (res.status === 'success') {
                $('#paymentModal').hide();
                
                currentUser.balance = res.new_balance;
                $('#userBalance').text(res.new_balance.toFixed(2));
                
                Swal.fire({
                    icon: 'success',
                    title: '✅ Заказ оформлен!',
                    html: `Номер заказа: <strong>${res.order_number}</strong><br>Сумма: ${res.total_paid.toFixed(2)} ₽`,
                    confirmButtonText: 'OK'
                });
                
                loadCart();
            } else {
                Swal.fire('❌ Ошибка', res.message, 'error');
            }
        },
        error: function() {
            btn.html('<i class="fas fa-arrow-right"></i> Оплатить').prop('disabled', false);
            Swal.fire('❌ Ошибка', 'Ошибка соединения', 'error');
        }
    });
}
    
    function closePaymentModal() {
        $('#paymentModal').css('display', 'none');
    }
    
    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        
        $('#cardMethod, #balanceMethod').removeClass('selected');
        
        if (method === 'card') {
            $('#cardMethod').addClass('selected');
            $('#cardForm').show();
            $('#balanceForm').hide();
        } else {
            $('#balanceMethod').addClass('selected');
            $('#cardForm').hide();
            $('#balanceForm').show();
        }
    }
    
    
function applyPromo() {
    console.log('🛒 ПРИМЕНЕНИЕ ПРОМОКОДА В КОРЗИНЕ');
    
    const $input = $('#cart-promo-code');
    const $message = $('#promoMessage');
    
    if (!$input.length) {
        console.error('❌ Поле cart-promo-code не найдено!');
        $message.html('<span style="color: #f44336;">❌ Ошибка: поле ввода не найдено</span>');
        return;
    }
    
    const code = $input.val().trim();
    console.log('📝 Введенный код:', code);
    
    if (!code) {
        $message.html('<span style="color: #f44336;">❌ Введите код промокода</span>');
        $input.focus();
        return;
    }
    
    // Показываем загрузку
    $message.html('<span style="color: #666;"><i class="fas fa-spinner fa-spin"></i> Проверка промокода...</span>');
    
    // Отправляем запрос
    $.ajax({
        url: 'cart_api.php?action=apply_promo',
        type: 'POST',
        data: { 
            code: code.toUpperCase() 
        },
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('✅ Ответ:', response);
            
            if (response.status === 'success') {
                // ОБНОВЛЯЕМ ВСЕ ЦЕНЫ В КОРЗИНЕ
                
                // 1. Обновляем подытог (сумма без скидки)
                $('#cart-subtotal').text(parseFloat(response.subtotal).toFixed(2) + ' ₽');
                
                // 2. Показываем и обновляем скидку
                const $discountElement = $('#cart-discount');
                $discountElement.text('-' + parseFloat(response.discount).toFixed(2) + ' ₽');
                $discountElement.parent().show(); // Показываем строку со скидкой
                
                // 3. Обновляем итоговую сумму
                $('#cart-total').text(parseFloat(response.total).toFixed(2) + ' ₽');
                
                // 4. Показываем сообщение об успехе
                $message.html(`
                    <div style="color: #4CAF50; padding: 12px; background: #e8f5e9; border-radius: 10px; margin-top: 10px; border-left: 4px solid #4CAF50;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                            <i class="fas fa-check-circle" style="font-size: 18px;"></i>
                            <strong style="font-size: 16px;">Промокод ${response.promo.code} применен!</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 8px; padding-top: 8px; border-top: 1px dashed #a5d6a7;">
                            <span>Сумма без скидки:</span>
                            <span>${parseFloat(response.subtotal).toFixed(2)} ₽</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: #f44336; font-weight: bold;">
                            <span>Скидка:</span>
                            <span>-${parseFloat(response.discount).toFixed(2)} ₽</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 5px;">
                            <span>ИТОГО к оплате:</span>
                            <span style="color: #4CAF50; font-size: 18px;">${parseFloat(response.total).toFixed(2)} ₽</span>
                        </div>
                    </div>
                `);
                
                // 5. Блокируем поле ввода и кнопку
                $input.prop('disabled', true).css('background', '#f5f5f5');
                $('button[onclick="applyPromo()"]').prop('disabled', true).css('opacity', '0.6');
                
                // 6. Сохраняем промокод в localStorage для проверки при оплате
                localStorage.setItem('applied_promo', JSON.stringify({
                    code: response.promo.code,
                    discount: response.discount
                }));
                
            } else {
                // Показываем ошибку
                $message.html(`
                    <div style="color: #f44336; padding: 12px; background: #ffebee; border-radius: 10px; margin-top: 10px; border-left: 4px solid #f44336;">
                        <i class="fas fa-exclamation-circle"></i> 
                        ${response.message || 'Ошибка применения промокода'}
                    </div>
                `);
                
                $input.focus();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Ошибка:', error);
            $message.html(`
                <div style="color: #f44336; padding: 12px; background: #ffebee; border-radius: 10px;">
                    <i class="fas fa-exclamation-triangle"></i> Ошибка соединения с сервером
                </div>
            `);
        }
    });
}
    
    // ==========================================
    // ОФОРМЛЕНИЕ ЗАКАЗА
    // ==========================================
    function processOrder() {
        // Проверка баланса
      if (selectedPaymentMethod === 'balance' && parseFloat(cartData.summary.total) > parseFloat(currentUser.balance)) {
            showToast('❌ Недостаточно средств на балансе', 'error');
            return;
        }
        
        const btn = $('#payButton');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Обработка...').prop('disabled', true);
        
        $.ajax({
            url: `${API_URL}?action=create_order`,
            method: 'POST',
            data: {
                payment_method: selectedPaymentMethod,
                promo_code: $('#promoInput').val()
            },
            dataType: 'json',
            success: function(response) {
                btn.html('<i class="fas fa-arrow-right"></i> Оплатить заказ').prop('disabled', false);
                
                if (response.status === 'success') {
                    // Закрываем модалку
                    closePaymentModal();
                    
                  currentUser.balance = parseFloat(response.new_balance || 0);
$('#userBalance').text(currentUser.balance.toFixed(2));

                    // Показываем успешное оформление
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Заказ оформлен!',
                        html: `
                            <div style="text-align: center;">
                                <div style="font-size: 48px; color: var(--success); margin-bottom: 15px;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <p style="font-size: 18px; margin-bottom: 10px;">
                                    Номер заказа: <strong>${response.order_number}</strong>
                                </p>
                                <p style="font-size: 16px; color: var(--primary);">
                                    Сумма: <strong>${response.total_paid.toFixed(2)} ₽</strong>
                                </p>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: 'var(--primary)'
                    });
                    
                    // Перезагружаем корзину
                    loadCart();
                    
                    // Очищаем промокод
                    $('#promoInput').val('');
                    $('#promoMessage').html('');
                    
                } else {
                    showToast('❌ ' + (response.message || 'Ошибка оформления заказа'), 'error');
                }
            },
            error: function(xhr) {
                btn.html('<i class="fas fa-arrow-right"></i> Оплатить заказ').prop('disabled', false);
                console.error('Ошибка создания заказа:', xhr.responseText);
                showToast('❌ Ошибка соединения с сервером', 'error');
            }
        });
    }
    
    // ==========================================
    // ЗАГРУЗКА ЗАКАЗОВ
    // ==========================================
    function loadUserOrders() {
        $('#ordersList').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3 text-muted">Загрузка заказов...</p></div>');
        
        $.ajax({
            url: `${API_URL}?action=get_orders`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    renderOrders(response.orders || []);
                } else {
                    showToast('Ошибка загрузки заказов', 'error');
                }
            },
            error: function() {
                showToast('Ошибка соединения', 'error');
            }
        });
    }
    
    function renderOrders(orders) {
        let html = '';
        
        if (!orders || orders.length === 0) {
            html = `
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">У вас пока нет заказов</h5>
                    <p class="text-muted">Совершите первую покупку!</p>
                </div>
            `;
        } else {
            orders.forEach(order => {
                const date = new Date(order.created_at).toLocaleString('ru-RU', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
                
                html += `
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">Заказ №${order.order_number}</h6>
                                <small class="text-muted">${date}</small>
                            </div>
                            <span class="badge bg-success">Оплачен</span>
                        </div>
                        <div class="mt-2">
                            ${order.items ? order.items.map(item => 
                                `<div class="d-flex justify-content-between">
                                    <span>${escapeHtml(item.title)} x${item.quantity}</span>
                                    <span class="fw-bold">${Number(item.total_price || item.price).toFixed(2)} ₽</span>
                                </div>`
                            ).join('') : ''}
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <span class="fw-bold">Итого:</span>
                            <span class="fw-bold text-primary">${Number(order.total_amount).toFixed(2)} ₽</span>
                        </div>
                    </div>
                `;
            });
        }
        
        $('#ordersList').html(html);
    }
    
    // ==========================================
    // УВЕДОМЛЕНИЯ
    // ==========================================
    function showToast(message, type = 'success') {
        const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️';
        const color = type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#ff9800';
        
        Swal.fire({
            icon: type,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
    
    // ==========================================
    // ЭКРАНИРОВАНИЕ HTML
    // ==========================================
    function escapeHtml(text) {
        if (!text) return '';
        return $('<div>').text(text).html();
    }
    
    // ==========================================
    // ЗАКРЫТИЕ МОДАЛКИ ПО КЛИКУ НА ФОН
    // ==========================================
    $(window).on('click', function(e) {
        if ($(e.target).is('#paymentModal')) {
            closePaymentModal();
        }
    });
    
    // ==========================================
    // ОБРАБОТКА НАЖАТИЯ ESC
    // ==========================================
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#paymentModal').is(':visible')) {
            closePaymentModal();
        }
    });

    $(document).ready(function() {
    loadCart();
    // ПРОВЕРЯЕМ УВЕДОМЛЕНИЯ О ПРОДАЖАХ
if (currentUser) {
    $.ajax({
        url: `${API_URL}?action=get_seller_notifications`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.notifications.length > 0) {
                response.notifications.forEach(function(notif) {
                    Swal.fire({
                        icon: 'success',
                        title: '💰 ' + notif.title,
                        html: notif.message,
                        timer: 5000,
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4CAF50'
                    });
                });
            }
        }
    });
}
    
      
function applyPromoToCart() {
    console.log('🛒 Промокод в корзине');
    
    // В КОРЗИНЕ ID = cart-promo-code !!!
    const $input = $('#cart-promo-code');
    
    if (!$input.length) {
        console.error('❌ Поле cart-promo-code не найдено!');
        showToast('Ошибка: поле для промокода не найдено', 'error');
        return;
    }
    
    const code = $input.val().trim();
    console.log('📝 Код из корзины:', code);
    
    if (!code) {
        showToast('❌ Введите код промокода', 'warning');
        $input.focus();
        return;
    }
    
    // Отправка в cart_api.php
    $.ajax({
        url: 'cart_api.php?action=apply_promo',
        type: 'POST',
        data: { code: code.toUpperCase() },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showToast('✅ Промокод применен!', 'success');
                // ... обновление корзины ...
            }
        }
    });
}
        });
    </script>
    
    <?php if (count($seller_notifications) > 0): ?>
    <script>
        $(document).ready(function() {
            // Показываем уведомления о продажах
            <?php foreach ($seller_notifications as $notification): ?>
            Swal.fire({
                icon: 'success',
                title: '💰 Продажа!',
                html: '<?php echo addslashes($notification['message']); ?>',
                timer: 5000,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
            <?php endforeach; ?>
        });
    </script>
    <?php endif; ?>
</body>
</html>