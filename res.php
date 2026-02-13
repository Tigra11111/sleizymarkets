<?php
session_start();



?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Маркетплейс</title>
     <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%231a1a1a%22/><text y=%22.9em%22 x=%225px%22 font-size=%2280%22 font-weight=%22900%22 font-family=%22Orbitron%22 fill=%22red%22>S</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f7;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            transition: background 0.3s;
        }

        .nav a:hover, .nav a.active {
            background: rgba(255,255,255,0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .balance {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background: #d32f2f;
        }

        .btn-admin {
            background: #9c27b0;
            color: white;
        }

        .btn-admin:hover {
            background: #7b1fa2;
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 20px;
            color: white;
        }

        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: modalShow 0.3s;
        }

        @keyframes modalShow {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 22px;
            color: #333;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 60px;
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .product-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .product-price {
            font-size: 22px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 15px;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            color: #888;
            font-size: 14px;
        }

        /* Admin Styles */
        .admin-panel {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .admin-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .admin-tab {
            padding: 10px 25px;
            background: none;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
        }

        .admin-tab.active {
            background: #667eea;
            color: white;
        }

        .admin-content {
            display: none;
        }

        .admin-content.active {
            display: block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 16px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .admin-table th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }

        .admin-table tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        /* Ban Modal */
        .ban-modal {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
            color: white;
            padding: 40px;
            text-align: center;
            border-radius: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .ban-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .ban-title {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .ban-reason {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 18px;
        }

        .ban-timer {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            color: #ffeaa7;
        }

        /* PIN Modal */
        .pin-modal {
            text-align: center;
        }

        .pin-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .pin-digit {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #667eea;
            border-radius: 10px;
            background: #f8f9fa;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #333;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 1001;
            display: none;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Tickets */
        .ticket-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .ticket-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .status-open { background: #d4edda; color: #155724; }
        .status-closed { background: #f8d7da; color: #721c24; }
        .status-progress { background: #fff3cd; color: #856404; }

        /* Loader */
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 50px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Стили для модального окна бана */
.ban-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
    margin-bottom: 20px;
}

.ban-option {
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.ban-option:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.ban-option.selected {
    border-color: #667eea;
    background: #667eea;
    color: white;
}

.ban-option i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}

.ban-duration-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.ban-duration-inputs input {
    flex: 1;
}

.ban-duration-inputs select {
    width: 120px;
}

#cart-count {
    display: inline-block;
    background-color: white;
    color: #ff4757;
    font-size: 11px;
    font-weight: 900;
    border: 2px solid #ff4757;
    border-radius: 50%;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    padding: 0;
    position: relative;
    margin-left: 5px;
}

#cart-count::before {
    content: '';
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b6b, #ff4757);
    z-index: -1;
    animation: rotate 3s linear infinite;
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Добавьте в стили */
.price-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 16px;
}

.final-price {
    color: #e91e63;
    font-size: 20px;
    font-weight: bold;
}

.discount-badge {
    background: #ff4757;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.image-preview-item {
    position: relative;
    transition: transform 0.3s;
}

.image-preview-item:hover {
    transform: scale(1.05);
}

.main-image-badge {
    position: absolute;
    bottom: 5px;
    left: 5px;
    background: #4CAF50;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: bold;
}

/* ==================== СТИЛИ ТИКЕТОВ ==================== */

/* Модальное окно тикета */
.ticket-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(5px);
    z-index: 3000;
    justify-content: center;
    align-items: center;
    animation: overlayFade 0.3s ease;
}

@keyframes overlayFade {
    from { opacity: 0; }
    to { opacity: 1; }
}

.ticket-modal {
    width: 95%;
    max-width: 900px;
    max-height: 90vh;
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
    overflow: hidden;
    animation: modalSlideUp 0.4s ease-out;
    display: flex;
    flex-direction: column;
}

@keyframes modalSlideUp {
    from { 
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Шапка модалки */
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    flex-shrink: 0;
}

.modal-header::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.ticket-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.ticket-title h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.ticket-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.3rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Информация о тикете */
.ticket-info-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: #f8f9ff;
    border-bottom: 1px solid #eef2ff;
    flex-shrink: 0;
}

.ticket-meta {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.ticket-id {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 18px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    white-space: nowrap;
}

.ticket-date {
    color: #666;
    font-size: 0.95rem;
    white-space: nowrap;
}

.ticket-status-select {
    padding: 10px 20px;
    border-radius: 10px;
    border: 2px solid #e1e8ed;
    background: white;
    font-weight: 600;
    color: #333;
    cursor: pointer;
    transition: all 0.3s;
    min-width: 180px;
}

.ticket-status-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Сообщения */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 30px;
    background: #fafafa;
    background-image: 
        radial-gradient(#e1e8ed 1px, transparent 1px),
        radial-gradient(#e1e8ed 1px, transparent 1px);
    background-size: 30px 30px;
    background-position: 0 0, 15px 15px;
    scroll-behavior: smooth;
}

.messages-container::-webkit-scrollbar {
    width: 8px;
}

.messages-container::-webkit-scrollbar-track {
    background: transparent;
}

.messages-container::-webkit-scrollbar-thumb {
    background: rgba(102, 126, 234, 0.3);
    border-radius: 4px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background: rgba(102, 126, 234, 0.5);
}

/* Сообщения в стиле Telegram */
.message {
    margin-bottom: 25px;
    display: flex;
    flex-direction: column;
    max-width: 75%;
    animation: messageAppear 0.3s ease-out;
}

@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-user {
    align-self: flex-end;
    margin-left: auto;
}

.message-admin {
    align-self: flex-start;
    margin-right: auto;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    padding: 0 10px;
}

.message-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.message-user .message-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.message-admin .message-avatar {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.message-sender {
    font-weight: 700;
    font-size: 0.95rem;
}

.message-user .message-sender {
    color: #667eea;
    order: -1;
    margin-right: 10px;
}

.message-admin .message-sender {
    color: #10b981;
}

.message-time {
    color: #888;
    font-size: 0.8rem;
    margin-left: auto;
}

.message-user .message-time {
    margin-left: 10px;
    margin-right: auto;
}

.message-bubble {
    padding: 16px 20px;
    border-radius: 20px;
    position: relative;
    line-height: 1.5;
    word-wrap: break-word;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    min-width: 120px;
}

.message-user .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 5px;
}

.message-admin .message-bubble {
    background: white;
    color: #333;
    border-bottom-left-radius: 5px;
    border: 1px solid #e1e8ed;
}

/* Ошибка изменения статуса админом */
.admin-error {
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 15px;
    padding: 15px 20px;
    margin: 20px auto;
    max-width: 90%;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.admin-error i {
    color: #dc2626;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.admin-error-text {
    color: #7f1d1d;
    font-weight: 600;
    font-size: 0.95rem;
}

/* Форма отправки */
.message-form {
    padding: 25px 30px;
    border-top: 1px solid #eef2ff;
    background: white;
    flex-shrink: 0;
}

.message-input-container {
    display: flex;
    gap: 15px;
    align-items: flex-end;
}

.message-input {
    flex: 1;
    padding: 18px 22px;
    border: 2px solid #e1e8ed;
    border-radius: 15px;
    font-size: 1rem;
    transition: all 0.3s;
    resize: none;
    min-height: 70px;
    line-height: 1.5;
    font-family: inherit;
}

.message-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.send-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 0 30px;
    height: 70px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
}

.send-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.send-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Список тикетов на странице */
.tickets-page-container {
    max-width: 1000px;
    margin: 30px auto;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.new-ticket-button {
    background: white;
    color: #667eea;
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}

.new-ticket-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Список тикетов */
.tickets-list {
    padding: 25px;
}

.ticket-card {
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 20px;
    padding: 20px;
    border-left: 5px solid #667eea;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.ticket-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.7s;
}

.ticket-card:hover::before {
    left: 100%;
}

.ticket-card:hover {
    transform: translateX(8px);
    background: #f0f2f5;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.ticket-card.active {
    background: linear-gradient(135deg, #f0f4ff 0%, #eef2ff 100%);
    border-left-color: #764ba2;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
}

.ticket-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 10px;
}

.ticket-card-id {
    font-weight: 700;
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.ticket-card-date {
    color: #666;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ticket-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
}

.ticket-card-preview {
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 15px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ticket-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.ticket-card-status {
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-new {
    background: #dbeafe;
    color: #1e40af;
}

.status-open {
    background: #d1fae5;
    color: #065f46;
}

.status-in-progress {
    background: #fef3c7;
    color: #92400e;
}

.status-closed {
    background: #e5e7eb;
    color: #374151;
}

.ticket-card-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    color: #888;
    font-size: 0.85rem;
}

.message-count {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Заглушка для пустых тикетов */
.empty-tickets {
    text-align: center;
    padding: 60px 30px;
    color: #666;
}

.empty-tickets i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.empty-tickets h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #444;
}

.empty-tickets p {
    font-size: 1rem;
    margin-bottom: 25px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

/* Адаптивность */
@media (max-width: 768px) {
    .ticket-modal {
        width: 100%;
        max-height: 100vh;
        border-radius: 0;
    }
    
    .modal-header {
        padding: 20px;
    }
    
    .ticket-info-bar {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
        padding: 15px 20px;
    }
    
    .ticket-meta {
        justify-content: center;
    }
    
    .ticket-status-select {
        width: 100%;
    }
    
    .message {
        max-width: 90%;
    }
    
    .message-header {
        flex-wrap: wrap;
    }
    
    .message-input-container {
        flex-direction: column;
    }
    
    .send-button {
        width: 100%;
        justify-content: center;
        height: 55px;
        padding: 0 20px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 20px;
    }
    
    .ticket-card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .ticket-card-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Кнопки действий */
.ticket-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.action-btn {
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.btn-open {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.btn-open:hover {
    background: #a7f3d0;
}

.btn-close {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.btn-close:hover {
    background: #fecaca;
}

.btn-reply {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.btn-reply:hover {
    background: #bfdbfe;
}
/* ==================== СТИЛИ ДЛЯ ПРОКРУТКИ МОДАЛЬНОГО ОКНА ДОБАВЛЕНИЯ ТОВАРА ==================== */
#add-product-modal .modal-content {
    max-height: 85vh; /* Ограничиваем высоту */
    overflow-y: auto; /* Добавляем вертикальную прокрутку */
    margin: 20px auto; /* Добавляем отступы сверху/снизу */
}

/* ==================== СТИЛИ ДЛЯ ПРОКРУТКИ МОДАЛЬНОГО ОКНА УВЕДОМЛЕНИЙ ==================== */
#notifications-modal .modal-content {
    max-height: 85vh; /* Ограничиваем высоту */
    overflow-y: auto; /* Добавляем вертикальную прокрутку */
    max-width: 600px; /* Оптимальная ширина для уведомлений */
}


#notifications-list {
    max-height: 60vh;
    overflow-y: auto;
    padding: 10px 0;
}

/* Кастомный скроллбар для модалки уведомлений */
#notifications-modal .modal-content::-webkit-scrollbar,
#notifications-list::-webkit-scrollbar {
    width: 8px;
}

#notifications-modal .modal-content::-webkit-scrollbar-track,
#notifications-list::-webkit-scrollbar-track {
    background: #f5f5f7;
    border-radius: 4px;
}

#notifications-modal .modal-content::-webkit-scrollbar-thumb,
#notifications-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}




/* Контейнер для тостов — уже есть */
body .toast-container {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    z-index: 99999 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-end !important;
}

/* Стили для каждого тоста */
.toast-container .toast {
    background: #333; /* Цвет фона */
    color: white; /* Цвет текста */
    padding: 12px 16px; /* Отступы */
    margin-bottom: 10px; /* Отступ между тостами */
    border-radius: 8px; /* Закруглённые углы */
    box-shadow: 0 3px 10px rgba(0,0,0,0.2); /* Тень */
    max-width: 350px; /* Максимальная ширина */
    min-width: 200px; /* Минимальная ширина */
    font-size: 14px; /* Размер текста */
    line-height: 1.4; /* Межстрочный интервал */
    word-wrap: break-word; /* Перенос длинных слов */
    white-space: normal; /* Нормальный перенос строк */
    opacity: 0; /* Изначально скрыт */
    transform: translateY(-10px); /* Для анимации */
    transition: opacity 0.3s, transform 0.3s; /* Плавное появление */
}

/* Когда тост активен */
.toast-container .toast.show {
    opacity: 1;
    transform: translateY(0);
}

    /* ==================== СТИЛИ ПРЕЛОАДЕРА ==================== */
    #preloader {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #fff; /* БЕЛЫЙ ФОН */
        z-index: 99999;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        transition: opacity 0.8s ease;
    }

    #preloader.hidden {
        display: none !important;
    }

    /* Огонь по всему фону */
    .fire-bg {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 120%, #ff4500 0%, #ffd700 50%, #ffffff 100%);
        filter: blur(20px);
        opacity: 0.4;
        animation: fire-pulse 4s infinite alternate;
    }

    @keyframes fire-pulse {
        0% { transform: scale(1) translateY(0); opacity: 0.3; }
        100% { transform: scale(1.1) translateY(-20px); opacity: 0.6; }
    }

    /* ГОРЯЩЕЕ ЛОГО */
    .burning-logo {
        font-family: 'Orbitron', sans-serif;
        font-size: 5rem;
        font-weight: 900;
        letter-spacing: 12px;
        text-transform: uppercase;
        position: relative;
        z-index: 10;
        
        /* Текстура огня внутри текста */
        background: linear-gradient(to top, #ff0000 20%, #ff8c00 50%, #ffee00 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        
        /* Интенсивное свечение */
        filter: drop-shadow(0 0 10px rgba(255, 69, 0, 0.8));
        animation: burning-vibration 0.1s infinite, flame-color 3s infinite;
    }

    /* Вибрация от жара */
    @keyframes burning-vibration {
        0% { transform: translate(0, 0) skewX(0.5deg); }
        25% { transform: translate(-1px, 1px) skewX(-0.5deg); }
        50% { transform: translate(1px, -1px) skewX(0.5deg); }
        75% { transform: translate(-1px, -1px) skewX(-0.5deg); }
        100% { transform: translate(1px, 1px) skewX(0.5deg); }
    }

    /* Переливы цвета пламени */
    @keyframes flame-color {
        0%, 100% { filter: drop-shadow(0 0 15px #ff4500) brightness(1); }
        50% { filter: drop-shadow(0 0 30px #ffae00) brightness(1.3); }
    }

    /* Искры (Embers) */
    .embers {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .ember {
        position: absolute;
        bottom: -10px;
        width: 3px;
        height: 3px;
        background: #ff8c00;
        border-radius: 50%;
        filter: blur(1px);
        box-shadow: 0 0 10px #ff4500;
        animation: rise-and-flicker 4s infinite linear;
    }

    /* Генерация случайного полета искр */
    .ember:nth-child(1) { left: 10%; animation-duration: 3s; }
    .ember:nth-child(2) { left: 25%; animation-duration: 4.5s; animation-delay: 1s; }
    .ember:nth-child(3) { left: 40%; animation-duration: 3.5s; animation-delay: 0.5s; }
    .ember:nth-child(4) { left: 55%; animation-duration: 5s; }
    .ember:nth-child(5) { left: 70%; animation-duration: 4s; animation-delay: 1.2s; }
    .ember:nth-child(6) { left: 85%; animation-duration: 3.2s; }
    .ember:nth-child(7) { left: 15%; animation-duration: 4.2s; }
    .ember:nth-child(8) { left: 90%; animation-duration: 3.8s; }

    @keyframes rise-and-flicker {
        0% { transform: translateY(0) scale(1); opacity: 0; }
        20% { opacity: 1; }
        100% { transform: translateY(-100vh) translateX(50px) scale(0); opacity: 0; }
    }

    /* Индикатор загрузки (Раскаленный металл) */
    .loader-bar-container {
        width: 350px;
        height: 3px;
        background: rgba(0, 0, 0, 0.05);
        margin: 30px auto;
        position: relative;
        border-radius: 5px;
        overflow: hidden;
    }

    .loader-progress {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #600, #ff0000, #ffff00);
        box-shadow: 0 0 15px #ff4500;
        transition: width 0.3s ease;
    }

    .status-text {
        font-size: 0.7rem;
        color: #ff4500;
        letter-spacing: 5px;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        animation: blink 0.8s infinite;
        margin-top: 10px;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Контейнер контента прелоадера */
    .loader-content {
        position: relative;
        z-index: 20;
        text-align: center;
    }

    /* Эффект марева */
    .heat-haze {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(transparent, rgba(255, 255, 255, 0.2) 50%, transparent);
        filter: blur(2px);
        z-index: 5;
        pointer-events: none;
    }


/* ==================== СЛАЙДЕР ==================== */
.hero-slider {
    position: relative;
    width: 100%;
    height: 400px;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.slider-container {
    width: 100%;
    height: 100%;
    position: relative;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    padding: 0 40px;
}

.slide.active {
    opacity: 1;
    z-index: 1;
}

.slide-content {
    max-width: 800px;
    z-index: 2;
    position: relative;
}

.slide h2 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
}

.slide p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
}

.slide-button {
    background: rgba(255,255,255,0.9);
    color: #333;
    border: none;
    padding: 15px 35px;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.slide-button:hover {
    background: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

.slider-nav {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 10px;
    z-index: 10;
}

.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.slider-dot.active {
    background: white;
    transform: scale(1.2);
}

.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 10;
    backdrop-filter: blur(10px);
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.slider-arrow:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-50%) scale(1.1);
}

.arrow-prev {
    left: 20px;
}

.arrow-next {
    right: 20px;
}

/* Автоплей индикатор */
.autoplay-indicator {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(0,0,0,0.5);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
    z-index: 10;
}

/* Адаптивность слайдера */
@media (max-width: 768px) {
    .hero-slider {
        height: 300px;
        border-radius: 15px;
    }
    
    .slide h2 {
        font-size: 2rem;
    }
    
    .slide p {
        font-size: 1rem;
    }
    
    .slider-arrow {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .arrow-prev {
        left: 10px;
    }
    
    .arrow-next {
        right: 10px;
    }
}

/* Добавьте эти стили в существующий CSS */
.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.05);
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
}

.avatar-upload-btn {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: #4CAF50;
    color: white;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
    border: 2px solid white;
    transition: all 0.3s;
}

.avatar-upload-btn:hover {
    background: #45a049;
    transform: scale(1.1);
}

.avatar-container {
    position: relative;
    display: inline-block;
}

.avatar-default {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    border: 3px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    overflow: hidden;
}

.avatar-icon {
    font-size: 30px; /* Увеличил иконку человечка */
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Стили для модального окна загрузки аватара */
.avatar-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 3000;
    justify-content: center;
    align-items: center;
}

.avatar-modal {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 400px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.avatar-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin: 20px auto;
    border: 5px solid #f0f0f0;
    display: none;
}

.avatar-options {
    display: flex;
    gap: 10px;
    margin: 20px 0;
}

.avatar-option-btn {
    flex: 1;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.avatar-option-btn:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.avatar-option-btn i {
    font-size: 24px;
    color: #667eea;
}

/* Адаптивность */
@media (max-width: 768px) {
    .user-avatar, .avatar-default {
        width: 50px;
        height: 50px;
    }
    
    .avatar-default .avatar-icon {
        font-size: 24px;
    }
    
    .avatar-upload-btn {
        width: 22px;
        height: 22px;
        font-size: 10px;
    }
}



/* ==================== СТИЛИ ДЛЯ РАСПРОДАННЫХ ТОВАРОВ ==================== */
.product-card.out-of-stock {
    opacity: 0.7 !important;
    filter: grayscale(0.5) !important;
    position: relative;
}

.product-card.out-of-stock::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.3);
    pointer-events: none;
    z-index: 5;
}

.product-card.out-of-stock .product-image img,
.product-card.out-of-stock .product-image div {
    filter: grayscale(100%) !important;
}

.product-card.out-of-stock .product-title,
.product-card.out-of-stock .product-description,
.product-card.out-of-stock .product-price,
.product-card.out-of-stock .final-price,
.product-card.out-of-stock .product-meta {
    opacity: 0.8;
}

.product-card.out-of-stock .btn-primary,
.product-card.out-of-stock .btn:not([disabled]) {
    background: #ccc !important;
    color: #666 !important;
    cursor: not-allowed !important;
    border: 1px solid #999 !important;
}

.product-card .stock-badge {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: bold;
    font-size: 16px;
    z-index: 30;
    backdrop-filter: blur(4px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    border: 2px solid rgba(255,255,255,0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.05); }
    100% { transform: translate(-50%, -50%) scale(1); }
}

/* Для цен со скидкой */
.product-card.out-of-stock .final-price {
    color: #999 !important;
}

.product-card.out-of-stock .discount-badge {
    opacity: 0.5;
}

.seller-modal .swal2-popup {
    padding: 30px !important;
    border-radius: 30px !important;
}

.seller-modal::-webkit-scrollbar {
    width: 8px;
}

.seller-modal::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.seller-modal::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.seller-modal::-webkit-scrollbar-thumb:hover {
    background: #5a67d8;
}


</style>



</head>
<body>
    <body>
    <!-- Добавьте этот код ПЕРВЫМ элементом в body -->
    <div id="preloader">
        <!-- Фоновый огонь -->
        <div class="fire-bg"></div>
        
        <!-- Искры/Угли -->
        <div class="embers">
            <div class="ember"></div><div class="ember"></div><div class="ember"></div>
            <div class="ember"></div><div class="ember"></div><div class="ember"></div>
            <div class="ember"></div><div class="ember"></div><div class="ember"></div>
            <div class="ember"></div><div class="ember"></div><div class="ember"></div>
        </div>

        <div class="loader-content">
            <div class="burning-logo">SLEIZY</div>
            <div class="heat-haze"></div> <!-- Эффект марева -->
            <div class="loader-bar-container">
                <div class="loader-progress" id="load-progress"></div>
            </div>
            <div class="status-text">SYSTEM IGNITING...</div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="#" class="logo" onclick="showHome(); return false;">
                    <i class="fas fa-store"></i> Маркетплейс
                </a>
                
                <div class="nav">
                    <a href="#" class="active" onclick="showHome(); return false;">
                        <i class="fas fa-home"></i> Главная
                    </a>
                    <a href="#" onclick="showProducts(); return false;">
                        <i class="fas fa-box"></i> Товары
                    </a>
                    </a>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                    <a href="#" onclick="showAdminPanel(); return false;">
                        <i class="fas fa-cog"></i> Админ-панель
                    </a>
                    <?php endif; ?>
                    
                    <div class="notification-bell" onclick="showNotifications()">
                        <i class="fas fa-bell"></i>
                        <span id="notification-count" class="notification-count">0</span>
                    </div>
                </div>
                
                
  <div class="user-info">
    <?php if(isset($_SESSION['user'])): ?>
        <div class="balance-container" style="display: flex; align-items: center; gap: 5px;">
            <!-- СКРОЛЛЕР ПЕРЕКЛЮЧЕНИЯ БАЛАНСА -->
            <div onclick="toggleBalanceMode()" style="display: flex; align-items: center; background: rgba(255,255,255,0.15); padding: 3px; border-radius: 30px; cursor: pointer; transition: 0.3s;" id="balanceToggle">
                <div id="mainBalanceIndicator" style="background: white; color: #667eea; padding: 6px 16px; border-radius: 30px; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-wallet"></i> Основной
                </div>
                <div id="sellerBalanceIndicator" style="padding: 6px 16px; border-radius: 30px; font-weight: 600; font-size: 13px; color: white; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-store"></i> Продажи
                </div>
            </div>
            
            <!-- ОТОБРАЖЕНИЕ БАЛАНСА -->
            <div class="balance" style="background: rgba(255,255,255,0.2); padding: 6px 20px; border-radius: 30px; font-weight: bold; min-width: 130px; text-align: center;">
                <span id="balanceAmount"><?php echo number_format($_SESSION['user']['balance'] ?? 0, 2); ?></span> ₽
                <span id="pendingBadge" style="display: none; background: #FFD700; color: #333; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;">
                    <i class="fas fa-clock"></i> <span id="pendingAmount">0.00</span>
                </span>
            </div>
        </div>
        
        <!-- СКРИПТ ДЛЯ БАЛАНСА - ТОЛЬКО ЗДЕСЬ! -->
        <script>
        // ===== ПЕРЕМЕННЫЕ =====
        let currentBalanceMode = 'main';
        let sellerBalanceData = { available: 0, pending: 0 };
        
        // ===== ПЕРЕКЛЮЧЕНИЕ БАЛАНСА =====
        function toggleBalanceMode() {
            const mainIndicator = document.getElementById('mainBalanceIndicator');
            const sellerIndicator = document.getElementById('sellerBalanceIndicator');
            const balanceAmount = document.getElementById('balanceAmount');
            const pendingBadge = document.getElementById('pendingBadge');
            const pendingAmount = document.getElementById('pendingAmount');
            
            if (currentBalanceMode === 'main') {
                // ПЕРЕКЛЮЧАЕМ НА БАЛАНС ПРОДАЖ
                currentBalanceMode = 'seller';
                mainIndicator.style.background = 'transparent';
                mainIndicator.style.color = 'white';
                sellerIndicator.style.background = 'white';
                sellerIndicator.style.color = '#667eea';
                
                // ЗАГРУЗКА БАЛАНСА ПРОДАВЦА
                fetch('cart_api.php?action=get_seller_balance')
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            sellerBalanceData = data.balance;
                            balanceAmount.textContent = sellerBalanceData.available.toFixed(2);
                            
                            if (sellerBalanceData.pending > 0) {
                                pendingAmount.textContent = sellerBalanceData.pending.toFixed(2);
                                pendingBadge.style.display = 'inline-block';
                            } else {
                                pendingBadge.style.display = 'none';
                            }
                        }
                    });
            } else {
                // ПЕРЕКЛЮЧАЕМ НА ОСНОВНОЙ БАЛАНС
                currentBalanceMode = 'main';
                sellerIndicator.style.background = 'transparent';
                sellerIndicator.style.color = 'white';
                mainIndicator.style.background = 'white';
                mainIndicator.style.color = '#667eea';
                
                balanceAmount.textContent = '<?php echo number_format($_SESSION['user']['balance'] ?? 0, 2); ?>';
                pendingBadge.style.display = 'none';
            }
        }
        
        // ===== ЗАГРУЗКА ПРИ СТАРТЕ =====
        document.addEventListener('DOMContentLoaded', function() {
            // ПРОВЕРЯЕМ ЕСТЬ ЛИ ОЖИДАЮЩИЕ ПЛАТЕЖИ
            fetch('cart_api.php?action=get_seller_balance')
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success' && data.balance.pending > 0) {
                        sellerBalanceData = data.balance;
                        document.getElementById('pendingAmount').textContent = data.balance.pending.toFixed(2);
                        document.getElementById('pendingBadge').style.display = 'inline-block';
                    }
                })
                .catch(err => console.error('Ошибка:', err));
        });
        </script>

        
        
        <div class="dropdown">
            <!-- Обновленный HTML для контейнера аватара -->
<div class="avatar-container" onclick="toggleUserMenu(event)">
    <?php if(isset($_SESSION['user']['avatar']) && !empty($_SESSION['user']['avatar'])): ?>
        <img src="<?php echo htmlspecialchars($_SESSION['user']['avatar']); ?>" 
             alt="Аватар" 
             class="user-avatar"
             id="user-avatar">
    <?php else: ?>
        <div class="avatar-default">
          <button id="camera-btn" style="
    position: absolute;
    bottom: -10px;
    right: -10px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #4CAF50;
    color: white;
    border: 3px solid white;
    cursor: pointer;
    z-index: 99999;
    font-size: 16px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
" onclick="openAvatarModal()">
    <i class="fas fa-camera"></i>
</button>
        </div>
    <?php endif; ?>
    
   <!-- Простая и надежная кнопка -->
<div class="avatar-upload-btn" id="camera-button" 
     onclick="openAvatarModal(); return false;"
     style="position: absolute; bottom: -10px; right: -10px; width: 30px; height: 30px; background: #4CAF50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid white; z-index: 1000; box-shadow: 0 0 10px rgba(0,0,0,0.3);">
    <i class="fas fa-camera" style="font-size: 14px;"></i>
</div>

    <div id="user-menu" class="dropdown-menu" style="display:none; position:absolute; background:white; border-radius:10px; padding:15px; box-shadow:0 10px 30px rgba(0,0,0,0.15); z-index:1000; min-width: 250px;">
        <div style="text-align: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white;">
                <i class="fas fa-user"></i>
            </div>
            <div style="font-weight: 600; font-size: 16px;">s<?php echo htmlspecialchars($_SESSION['user']['username']); ?></div>
            <div style="font-size: 14px; color: #666; margin-top: 3px;"><?php echo htmlspecialchars($_SESSION['user']['login']); ?></div>
            <div style="font-size: 14px; color: #4CAF50; margin-top: 5px;">
                <i class="fas fa-wallet"></i> <?php echo number_format($_SESSION['user']['balance'], 2); ?> ₽
            </div>
        </div>
        
        <a href="#" onclick="showUserCabinet(); toggleUserMenu(); return false;" style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
            <i class="fas fa-user-circle" style="color: #667eea; width: 20px;"></i> Личный кабинет
        </a>
        <a href="#" onclick="showSecurity(); toggleUserMenu(); return false;" style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
            <i class="fas fa-shield-alt" style="color: #4CAF50; width: 20px;"></i> Безопасность
        </a>
        <a href="tickets.php" onclick="toggleUserMenu(); return true;" style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
    <i class="fas fa-ticket-alt" style="color: #ff9800; width: 20px;"></i> Мои тикеты
</a>
        <a href="cart.php" onclick="toggleUserMenu(); return true;" 
   style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" 
   onmouseover="this.style.background='#f8f9fa'" 
   onmouseout="this.style.background='transparent'"
   id="cart-button">
    <i class="fas fa-shopping-cart" style="color: #9c27b0; width: 20px;"></i> Корзина
    <span id="cart-count">0</span>
</a>
        <a href="#" onclick="logout(); return false;" style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; background: #fff5f5; color: #dc3545; margin-top: 10px; transition: all 0.3s;" onmouseover="this.style.background='#ffe6e6'" onmouseout="this.style.background='#fff5f5'">
            <i class="fas fa-sign-out-alt" style="width: 20px;"></i> Выйти
        </a>
    </div>
</div>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="showLoginModal()">
                            <i class="fas fa-sign-in-alt"></i> Войти
                        </button>
                        <button class="btn" style="background:#667eea; color:white;" onclick="showRegisterModal()">
                            <i class="fas fa-user-plus"></i> Регистрация
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div id="content"></div>
    </main>

   <div id="login-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Вход в аккаунт</h3>
                <button class="close-modal" onclick="closeModal('login-modal')">&times;</button>
            </div>
            <form id="login-form">
                <div class="form-group">
                    <label class="form-label">Логин</label>
                    <input type="text" class="form-control" name="login" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Пароль</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group" style="margin-top: 15px;">
    <label style="display: flex; align-items: center; cursor: pointer;">
        <input type="checkbox" name="remember" style="margin-right: 10px;">
        <span>Запомнить меня</span>
    </label>
    <small style="color: #666; font-size: 12px;">Оставаться в системе даже после закрытия браузера</small>
</div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </button>
            </form>
        </div>
    </div>


    <!-- Register Modal -->
    <div id="register-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Регистрация</h3>
                <button class="close-modal" onclick="closeModal('register-modal')">&times;</button>
            </div>
            <form id="register-form">
                <div class="form-group">
                    <label class="form-label">Логин*</label>
                    <input type="text" class="form-control" name="login" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username">
                </div>
                <div class="form-group">
                    <label class="form-label">Пароль*</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group" style="margin-top: 15px;">
    <label style="display: flex; align-items: center; cursor: pointer;">
        <input type="checkbox" name="remember" style="margin-right: 10px;">
        <span>Запомнить меня</span>
    </label>
    <small style="color: #666; font-size: 12px;">Оставаться в системе даже после закрытия браузера</small>
</div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-user-plus"></i> Зарегистрироваться
                </button>
            </form>
        </div>
    </div>

    <!-- PIN Modal -->
    <div id="pin-modal" class="modal">
        <div class="modal-content pin-modal">
            <div class="modal-header">
                <h3 class="modal-title">PIN-код</h3>
            </div>
            <p>Введите ваш PIN-код для продолжения</p>
            <div class="pin-inputs">
                <input type="password" class="pin-digit" maxlength="1" oninput="moveToNext(this, 1)">
                <input type="password" class="pin-digit" maxlength="1" oninput="moveToNext(this, 2)">
                <input type="password" class="pin-digit" maxlength="1" oninput="moveToNext(this, 3)">
                <input type="password" class="pin-digit" maxlength="1" oninput="moveToNext(this, 4)">
            </div>
            <p id="pin-error" style="color:#ff4757; display:none;">Неверный PIN-код</p>
            <button class="btn btn-primary" onclick="verifyPin()">
                <i class="fas fa-check"></i> Подтвердить
            </button>
            <button class="btn" onclick="logout()" style="margin-top:10px;">
                <i class="fas fa-sign-out-alt"></i> Выйти
            </button>
        </div>
    </div>

    <!-- Add Product Modal -->
<div id="add-product-modal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 class="modal-title">Добавить товар</h3>
            <button class="close-modal" onclick="closeModal('add-product-modal')">&times;</button>
        </div>
        <form id="add-product-form" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Название*</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Описание*</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Категория</label>
                <select class="form-control" name="category" id="product-category">
                    <option value="electronics">Электроника</option>
                    <option value="clothes">Одежда</option>
                    <option value="books">Книги</option>
                    <option value="home">Товары для дома</option>
                    <option value="beauty">Красота и здоровье</option>
                    <option value="other">Другое</option>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label">Цена*</label>
                    <input type="number" class="form-control" id="product-price" name="price" step="0.01" min="1" required oninput="calculateDiscount()">
                    <small style="color: #666;">Цена в рублях</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tag"></i> Скидка (%)
                    </label>
                    <input type="number" class="form-control" id="product-discount" name="discount" min="0" max="100" value="0" oninput="calculateDiscount()">
                    <small style="color: #666;">0-100%</small>
                </div>
            </div>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 14px; color: #666;">Итоговая цена:</div>
                        <div id="final-price-display" style="font-size: 24px; font-weight: bold; color: #4CAF50;">0 ₽</div>
                    </div>
                    <div style="text-align: right;">
                        <div id="discount-display" style="font-size: 14px; color: #666;">Скидка: 0%</div>
                        <div id="savings-display" style="font-size: 14px; color: #e91e63;">Экономия: 0 ₽</div>
                    </div>
                </div>
                <input type="hidden" id="final-price" name="final_price">
            </div>
            
            <div class="form-group">
                <label class="form-label">Количество*</label>
                <input type="number" class="form-control" name="stock" value="1" min="1" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Фотографии товара</label>
                <div style="border: 2px dashed #ddd; border-radius: 10px; padding: 20px; text-align: center; background: #fafafa;">
                    <div id="image-preview-container" style="display: none; margin-bottom: 15px;">
                        <div id="image-previews" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;"></div>
                    </div>
                    
                    <div id="upload-area" onclick="$('#product-images').click()" 
                         style="cursor: pointer; padding: 20px;">
                        <div style="font-size: 60px; color: #667eea; margin-bottom: 10px;">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h4 style="margin-bottom: 10px; color: #333;">Перетащите или выберите фото</h4>
                        <p style="color: #666; margin-bottom: 15px;">До 5 изображений. Максимум 5MB каждое</p>
                        <button type="button" class="btn" style="background: #667eea; color: white;">
                            <i class="fas fa-images"></i> Выбрать файлы
                        </button>
                        <p style="margin-top: 10px; font-size: 12px; color: #999;">
                            Поддерживаемые форматы: JPG, PNG, GIF
                        </p>
                    </div>
                    
                    <input type="file" id="product-images" name="images[]" 
                           multiple accept="image/*" style="display: none;" 
                           onchange="previewImages(this)">
                </div>
                <small style="color: #666;">Первое изображение будет основным</small>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px;">
                    <i class="fas fa-plus"></i> Добавить товар
                </button>
                <button type="button" class="btn" onclick="closeModal('add-product-modal')" 
                        style="background: #6c757d; color: white; flex: 1;">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Security Modal -->
    <div id="security-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Безопасность</h3>
                <button class="close-modal" onclick="closeModal('security-modal')">&times;</button>
            </div>
            <div class="tabs">
                <button class="btn" onclick="showSecurityTab('password')">Смена пароля</button>
                <button class="btn" onclick="showSecurityTab('pin')">PIN-код</button>
            </div>
            
            <div id="password-tab" style="display:block;">
                <h4>Смена пароля</h4>
                <form id="change-password-form">
                    <div class="form-group">
                        <label class="form-label">Текущий пароль</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Новый пароль</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Подтвердите новый пароль</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Сменить пароль
                    </button>
                </form>
            </div>
            
            <div id="pin-tab" style="display:none;">
                <h4>Установка PIN-кода</h4>
                <form id="set-pin-form">
                    <div class="form-group">
                        <label class="form-label">PIN-код (4-6 цифр)</label>
                        <input type="password" class="form-control" name="pin" pattern="\d{4,6}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Подтвердите PIN-код</label>
                        <input type="password" class="form-control" name="confirm_pin" pattern="\d{4,6}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock"></i> Установить PIN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Promo Modal -->
    <div id="promo-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Активация промокода</h3>
                <button class="close-modal" onclick="closeModal('promo-modal')">&times;</button>
            </div>
            <form id="activate-promo-form">
                <div class="form-group">
                    <label class="form-label">Промокод</label>
                   <input type="text" id="promo-code" class="form-control" placeholder="Введите промокод">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-gift"></i> Активировать
                </button>
            </form>
        </div>
    </div>

    <!-- Add Promo Modal (Admin) -->
    <div id="add-promo-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Добавить промокод</h3>
                <button class="close-modal" onclick="closeModal('add-promo-modal')">&times;</button>
            </div>
            <form id="add-promo-form">
                <div class="form-group">
                    <label class="form-label">Код*</label>
                    <input type="text" class="form-control" name="code" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Награда*</label>
                    <input type="number" class="form-control" name="reward" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Макс. использований</label>
                    <input type="number" class="form-control" name="max_uses" min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Срок действия</label>
                    <input type="datetime-local" class="form-control" name="expires">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-plus"></i> Добавить промокод
                </button>
            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notifications-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Уведомления</h3>
                <button class="close-modal" onclick="closeModal('notifications-modal')">&times;</button>
            </div>
            <div id="notifications-list"></div>
        </div>
    </div>

    <!-- Send Notification Modal (Admin) -->
    <div id="send-notification-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Рассылка уведомлений</h3>
                <button class="close-modal" onclick="closeModal('send-notification-modal')">&times;</button>
            </div>
            <form id="send-notification-form">
                <div class="form-group">
                    <label class="form-label">Заголовок*</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Сообщение*</label>
                    <textarea class="form-control" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-paper-plane"></i> Отправить всем
                </button>
            </form>
        </div>
    </div>

    <!-- Create Ticket Modal -->
    <div id="create-ticket-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Создать тикет</h3>
                <button class="close-modal" onclick="closeModal('create-ticket-modal')">&times;</button>
            </div>
            <form id="create-ticket-form">
                <div class="form-group">
                    <label class="form-label">Тема*</label>
                    <input type="text" class="form-control" name="subject" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Сообщение*</label>
                    <textarea class="form-control" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-paper-plane"></i> Создать тикет
                </button>
            </form>
        </div>
    </div>

    <!-- Ticket Messages Modal -->
    <div id="ticket-messages-modal" class="modal">
        <div class="modal-content" style="max-width:800px;">
            <div class="modal-header">
                <h3 class="modal-title" id="ticket-title"></h3>
                <button class="close-modal" onclick="closeModal('ticket-messages-modal')">&times;</button>
            </div>
            <div id="ticket-messages" style="max-height:400px; overflow-y:auto; margin-bottom:20px;"></div>
            <form id="add-ticket-message-form">
                <input type="hidden" id="current-ticket-id">
                <div class="form-group">
                    <textarea class="form-control" name="message" rows="3" placeholder="Введите сообщение..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Отправить
                </button>
            </form>
        </div>
    </div>
<div id="ban-fullscreen" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #1a1a1a, #2d2d2d); z-index: 1000; overflow-y: auto;">
    <div style="min-height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: white; border-radius: 30px; max-width: 600px; width: 100%; box-shadow: 0 30px 80px rgba(0,0,0,0.5); animation: slideUp 0.5s;">
            
            <!-- Шапка -->
            <div style="background: linear-gradient(135deg, #ff4757, #ff6b6b); color: white; padding: 40px 30px; border-radius: 30px 30px 0 0; text-align: center;">
                <div style="font-size: 80px; margin-bottom: 20px;">
                    <i class="fas fa-ban"></i>
                </div>
                <h1 style="margin: 0 0 10px 0; font-size: 36px;">⛔ Аккаунт заблокирован</h1>
                <p style="margin: 0; opacity: 0.9; font-size: 16px;">Доступ к сайту временно ограничен</p>
            </div>
            
            <!-- Контент -->
            <div style="padding: 40px 30px;">
                <!-- Информация о блокировке -->
                <div style="background: #fff5f5; padding: 25px; border-radius: 20px; margin-bottom: 30px; border-left: 6px solid #ff4757;">
                    <h3 style="margin: 0 0 15px 0; color: #721c24; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Информация о блокировке
                    </h3>
                    
                    <div style="display: grid; gap: 15px;">
                        <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid #ffe0e0;">
                            <span style="color: #721c24;">Причина:</span>
                            <span id="ban-reason-display" style="font-weight: 600; color: #ff4757;">Нарушение правил</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid #ffe0e0;">
                            <span style="color: #721c24;">Дата блокировки:</span>
                            <span id="ban-date-display" style="font-weight: 500;">13.02.2026 15:30</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid #ffe0e0;">
                            <span style="color: #721c24;">Срок действия:</span>
                            <span id="ban-expires-display" style="font-weight: 600;">Бессрочно</span>
                        </div>
                        
                        <div id="ban-timer-display" style="text-align: center; font-size: 24px; font-weight: bold; color: #ff4757; margin-top: 15px;">
                            00:00:00
                        </div>
                    </div>
                </div>
                
                <!-- Сообщение администратора -->
                <div id="ban-admin-message-container" style="background: #e3f2fd; padding: 25px; border-radius: 20px; margin-bottom: 30px; display: none;">
                    <h4 style="margin: 0 0 10px 0; color: #1565C0; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user-shield"></i>
                        Сообщение администратора
                    </h4>
                    <p id="ban-admin-message-text" style="margin: 0; color: #333; font-size: 15px; line-height: 1.6;"></p>
                </div>
                
                <!-- Апелляция -->
                <div style="text-align: center; margin: 40px 0;">
                    <h3 style="margin-bottom: 15px; color: #333;">❗ Хотите обжаловать блокировку?</h3>
                    <p style="color: #666; margin-bottom: 25px;">Нажмите кнопку ниже, чтобы создать апелляцию для администратора</p>
                    
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                        <button onclick="submitBanAppeal()" 
                                style="background: #ff4757; color: white; border: none; padding: 16px 35px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 25px rgba(255,71,87,0.3);">
                            <i class="fas fa-gavel"></i>
                            Подать апелляцию
                        </button>
                        
                        <button onclick="window.userViewMyAppeals()" 
                                style="background: #2196F3; color: white; border: none; padding: 16px 35px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 25px rgba(33,150,243,0.3);">
                            <i class="fas fa-history"></i>
                            Мои апелляции
                        </button>
                    </div>
                    <!-- Кнопка выхода -->
<div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
    <button onclick="logoutFromBan()" 
            style="background: #dc3545; color: white; border: none; padding: 15px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 5px 15px rgba(220,53,69,0.3); transition: all 0.3s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(220,53,69,0.4)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(220,53,69,0.3)';">
        <i class="fas fa-sign-out-alt"></i>
        Выйти из аккаунта
    </button>
    <p style="color: #999; font-size: 12px; margin-top: 15px;">
        <i class="fas fa-info-circle"></i> После выхода вы сможете зайти под другим аккаунтом
    </p>
</div>
                    <p style="margin-top: 20px; color: #999; font-size: 13px;">
                        <i class="fas fa-info-circle"></i> Администратор ответит в течение 24 часов
                    </p>
                </div>
                
                <!-- Подвал -->
                <div style="text-align: center; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                    <p style="color: #888; font-size: 13px;">
                        <i class="fas fa-shield-alt"></i> Блокировка наложена автоматически или администратором
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>


<script>
// ========== ПОКАЗ ОКНА БАНА ==========
function showBanFullscreen(banInfo = null) {
    console.log('🚨 Показ полноэкранного окна бана', banInfo);
    
    // Скрываем обычные модалки
    $('.modal').hide();
    
    // Заполняем данные
    if (banInfo) {
        $('#ban-reason-display').text(banInfo.reason || 'Нарушение правил');
        $('#ban-date-display').text(formatDateForBan(banInfo.created_at) || new Date().toLocaleString());
        
        if (banInfo.expires) {
            $('#ban-expires-display').text(formatDateForBan(banInfo.expires));
            startBanTimer(banInfo.expires);
        } else {
            $('#ban-expires-display').text('Бессрочно');
            $('#ban-timer-display').hide();
        }
        
        if (banInfo.message) {
            $('#ban-admin-message-text').text(banInfo.message);
            $('#ban-admin-message-container').show();
        } else {
            $('#ban-admin-message-container').hide();
        }
    }
    
    // Показываем окно
    $('#ban-fullscreen').fadeIn(300);
    
    // Блокируем скролл
    $('body').css('overflow', 'hidden');
}

// ========== ФОРМАТ ДАТЫ ==========
function formatDateForBan(dateString) {
    if (!dateString) return '';
    let date = new Date(dateString);
    return date.toLocaleString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// ========== ФОРМАТ ДАТЫ ==========
function formatDateForBan(dateString) {
    if (!dateString) return '';
    let date = new Date(dateString);
    return date.toLocaleString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
// ========== ТАЙМЕР БАНА ==========
function startBanTimer(expires) {
    if (!expires) return;
    
    const timerElement = document.getElementById('ban-timer-display');
    if (!timerElement) return;
    
    if (window.banTimerInterval) {
        clearInterval(window.banTimerInterval);
    }
    
    function updateTimer() {
        const now = new Date();
        const expireDate = new Date(expires);
        const diff = expireDate - now;
        
        if (diff <= 0) {
            timerElement.innerHTML = '<span style="color: #4CAF50;">✅ Срок блокировки истек</span>';
            clearInterval(window.banTimerInterval);
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        timerElement.innerHTML = `⏳ ${hours}ч ${minutes}м ${seconds}с`;
    }
    
    updateTimer();
    window.banTimerInterval = setInterval(updateTimer, 1000);
}

// ========== ЗАКРЫТИЕ ОКНА (НО МЫ НЕ ДАДИМ) ==========
function closeBanFullscreen() {
    // Пустая функция - не закрываем окно
    console.log('❌ Нельзя закрыть окно бана');
}

// ========== ПОДАЧА АПЕЛЛЯЦИИ ==========
function submitBanAppeal() {
    let userId = currentUser?.id || 0;
    let userLogin = currentUser?.login || '';
    
    if (!userId) {
        Swal.fire({
            title: 'Введите ID',
            input: 'number',
            text: 'Ваш ID пользователя'
        }).then(result => {
            if (result.value) {
                sendBanAppeal(result.value, 'user' + result.value);
            }
        });
    } else {
        sendBanAppeal(userId, userLogin);
    }
}

// ========== ОТПРАВКА АПЕЛЛЯЦИИ ==========
function sendBanAppeal(userId, userLogin) {
    Swal.fire({
        title: '⏳ Отправка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=create_ban_appeal',
        method: 'POST',
        data: {
            user_id: userId,
            login: userLogin
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция отправлена!',
                    html: `Номер обращения: <strong>#${response.ticket_id}</strong>`,
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения'
            });
        }
    });
}


    </script>
        <!-- Ban User Modal -->
    <div id="ban-user-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Бан пользователя</h3>
                <button class="close-modal" onclick="closeModal('ban-user-modal')">&times;</button>
            </div>
            <form id="ban-user-form">
                <input type="hidden" id="ban-user-id">
                
                <div class="form-group">
                    <label class="form-label">Причина бана*</label>
                    <select class="form-control" id="ban-reason" required>
                        <option value="">-- Выберите причину --</option>
                        <option value="Нарушение правил">Нарушение правил</option>
                        <option value="Мошенничество">Мошенничество</option>
                        <option value="Оскорбления">Оскорбления</option>
                        <option value="Спам">Спам</option>
                        <option value="Другое">Другое</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Другая причина</label>
                    <textarea class="form-control" id="custom-reason" rows="2" placeholder="Укажите свою причину..." style="display:none;"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Срок бана*</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" class="form-control" id="ban-duration" min="1" max="720" value="24" style="flex:1;">
                        <select class="form-control" id="ban-unit" style="width:100px;">
                            <option value="hours">Часов</option>
                            <option value="days">Дней</option>
                            <option value="weeks">Недель</option>
                        </select>
                    </div>
                    <div style="margin-top: 10px; font-size: 14px; color: #666;">
                        <span id="ban-preview">На 24 часов</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Сообщение пользователю</label>
                    <textarea class="form-control" id="ban-message" rows="3" placeholder="Сообщение, которое увидит пользователь при попытке входа"></textarea>
                </div>
                
                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
                    <strong><i class="fas fa-exclamation-triangle"></i> Внимание!</strong>
                    <p style="margin: 5px 0 0 0; font-size: 14px;">Пользователь не сможет войти в систему на указанный срок.</p>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-danger" style="flex:1;">
                        <i class="fas fa-ban"></i> Забанить
                    </button>
                    <button type="button" class="btn" onclick="closeModal('ban-user-modal')" style="background:#6c757d; color:white; flex:1;">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unban User Modal -->
<div id="unban-user-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Разбан пользователя</h3>
            <button class="close-modal" onclick="closeModal('unban-user-modal')">&times;</button>
        </div>
        <div style="padding: 20px;">
            <!-- Скрытое поле для ID пользователя -->
            <input type="hidden" id="unban-user-id">
            
            <p>Вы уверены, что хотите разблокировать пользователя <strong id="unban-user-name"></strong>?</p>
            
            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button class="btn btn-success" onclick="confirmUnban()" style="flex:1;">
                    <i class="fas fa-check"></i> Разблокировать
                </button>
                <button class="btn" onclick="closeModal('unban-user-modal')" style="background:#6c757d; color:white; flex:1;">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Give Promo Modal -->
<div id="give-promo-modal" class="modal">
    <div class="modal-content" style="background: white; border-radius: 15px; max-width: 500px; width: 90%; padding: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
            <h3 style="margin: 0; color: #333; font-size: 22px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-gift" style="color: #9c27b0;"></i>
                Персональный промокод
            </h3>
            <button onclick="closeModal('give-promo-modal')" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #666;">&times;</button>
        </div>
        
        <form id="give-promo-form">
            <input type="hidden" id="promo-user-id">
            <input type="hidden" id="promo-user-login">
            
            <div style="background: linear-gradient(135deg, #667eea15, #764ba215); padding: 15px; border-radius: 12px; margin-bottom: 25px; border-left: 5px solid #9c27b0;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 45px; height: 45px; background: #9c27b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: #666;">Пользователь</div>
                        <div style="font-size: 18px; font-weight: bold; color: #333;" id="promo-user-info">Загрузка...</div>
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    <i class="fas fa-tag" style="color: #9c27b0;"></i> Код промокода*
                </label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" class="form-control" id="promo-code" placeholder="Например: WELCOME2024" required style="flex: 1;">
                    <button type="button" onclick="generatePromoCode()" style="background: #9c27b0; color: white; border: none; padding: 0 20px; border-radius: 10px; cursor: pointer;">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <small style="color: #666;">Уникальный код, который введет пользователь</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    <i class="fas fa-coins" style="color: #4CAF50;"></i> Сумма награды*
                </label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="number" class="form-control" id="promo-reward" step="0.01" min="1" value="100" required style="flex: 1;">
                    <span style="font-size: 18px; font-weight: bold; color: #4CAF50;">₽</span>
                </div>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    <i class="fas fa-comment" style="color: #2196F3;"></i> Сообщение для пользователя
                </label>
                <textarea class="form-control" id="promo-message" rows="4" 
                          placeholder="Напишите сообщение, которое увидит пользователь..."
                          style="resize: vertical;"></textarea>
                <small style="color: #666;">Это сообщение появится при активации промокода</small>
            </div>
            
            <div style="background: #e8f5e9; padding: 15px; border-radius: 10px; margin-bottom: 25px; border-left: 4px solid #4CAF50;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: #4CAF50;"></i>
                    <span style="color: #2E7D32; font-size: 14px;">Промокод будет действовать 7 дней с момента активации</span>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="flex: 2; background: #9c27b0; padding: 14px; font-size: 16px;">
                    <i class="fas fa-gift"></i> Выдать промокод
                </button>
                <button type="button" class="btn" onclick="closeModal('give-promo-modal')" 
                        style="flex: 1; background: #6c757d; color: white; padding: 14px;">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function generatePromoCode() {
    let timestamp = Date.now().toString(36);
    let random = Math.random().toString(36).substr(2, 4).toUpperCase();
    $('#promo-code').val('PROMO_' + random + '_' + timestamp);
}
</script>


<!-- Promo Reward Modal (для пользователя) -->
<div id="promo-reward-modal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 30px; border-radius: 15px 15px 0 0; text-align: center;">
            <div style="font-size: 60px; margin-bottom: 20px;">
                <i class="fas fa-gift"></i>
            </div>
            <h2 style="margin: 0; font-size: 28px;">🎉 Персональный промокод!</h2>
        </div>
        
        <div style="padding: 30px;">
            <div id="admin-message-container" style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
                <h4 style="margin-top: 0; color: #333;">
                    <i class="fas fa-comment"></i> Сообщение от администратора
                </h4>
                <p id="admin-message-text" style="font-size: 16px; line-height: 1.6;"></p>
            </div>
            
            <div style="background: #e7f3ff; padding: 25px; border-radius: 10px; margin-bottom: 25px; text-align: center; border: 2px dashed #2196F3;">
                <div style="font-size: 14px; color: #666; margin-bottom: 10px;">Ваш промокод:</div>
                <div id="promo-code-display" style="font-size: 28px; font-weight: bold; color: #2196F3; letter-spacing: 2px; margin: 15px 0;"></div>
                <div style="font-size: 18px; color: #4CAF50; margin-top: 10px;">
                    <i class="fas fa-coins"></i> Награда: <span id="promo-reward-display"></span> ₽
                </div>
                <div style="font-size: 14px; color: #666; margin-top: 15px;">
                    <i class="fas fa-clock"></i> Действует 7 дней
                </div>
            </div>
            
            <div style="text-align: center;">
                <button class="btn btn-primary" onclick="activatePromoFromModal()" style="padding: 12px 30px; font-size: 16px;">
                    <i class="fas fa-check-circle"></i> Активировать промокод
                </button>
                <p style="margin-top: 15px; color: #666; font-size: 14px;">
                    Промокод будет автоматически применен к вашему балансу
                </p>
            </div>
        </div>
    </div>
</div>

<div id="ticketModal" class="ticket-modal-overlay" style="display: none;">
    <div class="ticket-modal">
        <div class="modal-header">
            <div class="ticket-title">
                <h1><i class="fas fa-ticket-alt"></i> Тикет #<span id="modalTicketId">0</span></h1>
                <span class="ticket-badge" id="ticketBadge">Новый</span>
            </div>
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="ticket-info-bar">
            <div class="ticket-meta">
                <span class="ticket-id">#<span id="modalTicketId2">0</span></span>
                <span class="ticket-date" id="ticketDate">
                    <i class="far fa-calendar"></i> Дата
                </span>
            </div>
            <div>
                <select class="ticket-status-select" id="ticketStatusSelect">
                    <option value="new">Новый</option>
                    <option value="open">Открыт</option>
                    <option value="progress">В работе</option>
                    <option value="closed">Закрыт</option>
                </select>
            </div>
        </div>
        
        <div id="adminError" class="admin-error" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="admin-error-text" id="adminErrorText"></div>
        </div>
        
        <div class="messages-container" id="ticketMessages">
            <!-- Сообщения будут здесь -->
        </div>
        
        <form class="message-form" onsubmit="return false;">
            <div class="message-input-container">
                <textarea 
                    class="message-input" 
                    id="messageInput" 
                    placeholder="Введите сообщение..." 
                    rows="3"
                ></textarea>
                <button type="button" class="send-button" id="sendTicketMessageBtn">
                    <i class="fas fa-paper-plane"></i> Отправить
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно создания нового тикета -->
<div id="newTicketModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Создать новый тикет</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="form-group">
            <label class="form-label">Тема тикета</label>
            <input type="text" class="form-control" id="newTicketSubject" placeholder="Введите тему проблемы">
        </div>
        <div class="form-group">
            <label class="form-label">Сообщение</label>
            <textarea class="form-control" id="newTicketMessage" rows="5" placeholder="Опишите вашу проблему подробно"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" onclick="createNewTicket()" style="width: 100%;">
                <i class="fas fa-plus"></i> Создать тикет
            </button>
        </div>
    </div>
</div>
<!-- Checkout Modal -->
<div id="checkout-modal" class="modal">
    <div class="modal-content" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-credit-card"></i> Оформление заказа
            </h3>
            <button class="close-modal" onclick="closeModal('checkout-modal')">&times;</button>
        </div>
        
        <div style="padding: 25px;">
            <!-- Шаги оформления -->
            <div style="display: flex; justify-content: space-between; margin-bottom: 30px; position: relative;">
                <div style="position: absolute; top: 15px; left: 0; right: 0; height: 3px; background: #e0e0e0; z-index: 1;"></div>
                <div class="checkout-step active" data-step="1" style="position: relative; z-index: 2; text-align: center; flex: 1;">
                    <div style="width: 30px; height: 30px; background: #4CAF50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                        1
                    </div>
                    <span style="font-size: 14px; color: #333;">Данные</span>
                </div>
                <div class="checkout-step" data-step="2" style="position: relative; z-index: 2; text-align: center; flex: 1;">
                    <div style="width: 30px; height: 30px; background: #e0e0e0; color: #666; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                        2
                    </div>
                    <span style="font-size: 14px; color: #666;">Доставка</span>
                </div>
                <div class="checkout-step" data-step="3" style="position: relative; z-index: 2; text-align: center; flex: 1;">
                    <div style="width: 30px; height: 30px; background: #e0e0e0; color: #666; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                        3
                    </div>
                    <span style="font-size: 14px; color: #666;">Оплата</span>
                </div>
            </div>
            
            <!-- Контент шагов -->
            <div id="checkout-step-1" class="checkout-step-content active">
                <h4 style="margin-bottom: 20px; color: #333;">Контактные данные</h4>
                <div class="form-group">
                    <label class="form-label">Имя *</label>
                    <input type="text" class="form-control" id="checkout-name" value="${currentUser?.username || ''}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" id="checkout-email">
                </div>
                <div class="form-group">
                    <label class="form-label">Телефон *</label>
                    <input type="tel" class="form-control" id="checkout-phone" placeholder="+7 (999) 999-99-99">
                </div>
                <button class="btn btn-primary" onclick="nextCheckoutStep(2)" style="width: 100%; padding: 12px; margin-top: 20px;">
                    Продолжить <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            
            <div id="checkout-step-2" class="checkout-step-content" style="display: none;">
                <h4 style="margin-bottom: 20px; color: #333;">Способ доставки</h4>
                <div style="display: grid; gap: 15px; margin-bottom: 25px;">
                    <label class="delivery-option" style="border: 2px solid #e0e0e0; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="delivery" value="courier" checked style="width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="font-weight: bold; color: #333;">Курьерская доставка</div>
                                <div style="color: #666; font-size: 14px;">1-3 дня · 300 ₽</div>
                            </div>
                            <i class="fas fa-motorcycle" style="color: #4CAF50; font-size: 24px;"></i>
                        </div>
                    </label>
                    <label class="delivery-option" style="border: 2px solid #e0e0e0; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="delivery" value="pickup" style="width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="font-weight: bold; color: #333;">Самовывоз</div>
                                <div style="color: #666; font-size: 14px;">2-5 дней · Бесплатно</div>
                            </div>
                            <i class="fas fa-store" style="color: #2196F3; font-size: 24px;"></i>
                        </div>
                    </label>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Адрес доставки</label>
                    <textarea class="form-control" id="checkout-address" rows="3" placeholder="Город, улица, дом, квартира"></textarea>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 25px;">
                    <button class="btn" onclick="prevCheckoutStep()" style="flex: 1; padding: 12px; background: #f8f9fa; color: #333;">
                        <i class="fas fa-arrow-left"></i> Назад
                    </button>
                    <button class="btn btn-primary" onclick="nextCheckoutStep(3)" style="flex: 2; padding: 12px;">
                        Продолжить <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            
            <div id="checkout-step-3" class="checkout-step-content" style="display: none;">
                <h4 style="margin-bottom: 20px; color: #333;">Способ оплаты</h4>
                <div style="display: grid; gap: 15px; margin-bottom: 25px;">
                    <label class="payment-option" style="border: 2px solid #e0e0e0; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="payment" value="card" checked style="width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="font-weight: bold; color: #333;">Банковская карта</div>
                                <div style="color: #666; font-size: 14px;">Visa, Mastercard, МИР</div>
                            </div>
                            <i class="fas fa-credit-card" style="color: #2196F3; font-size: 24px;"></i>
                        </div>
                    </label>
                    <label class="payment-option" style="border: 2px solid #e0e0e0; border-radius: 10px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="payment" value="balance" style="width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="font-weight: bold; color: #333;">Баланс аккаунта</div>
                                <div style="color: #666; font-size: 14px;">Использовать средства на счете</div>
                            </div>
                            <i class="fas fa-wallet" style="color: #4CAF50; font-size: 24px;"></i>
                        </div>
                    </label>
                </div>
                
                <!-- Сводка заказа -->
                <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 25px;">
                    <h5 style="margin-bottom: 15px; color: #333;">Сводка заказа</h5>
                    <div id="checkout-summary">
                        <!-- Будет заполнено через JS -->
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 25px;">
                    <button class="btn" onclick="prevCheckoutStep()" style="flex: 1; padding: 12px; background: #f8f9fa; color: #333;">
                        <i class="fas fa-arrow-left"></i> Назад
                    </button>
                 <button type="button" class="btn btn-primary" id="pay-button-fixed" style="width:100%; padding:15px; font-size:18px; font-weight:bold;">
    💳 ОПЛАТИТЬ ЗАКАЗ
</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Добавьте этот код перед закрывающим тегом </body> -->

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Верхняя часть футера -->
            <div class="footer-top">
                <!-- Лого и описание -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <i class="fas fa-store"></i>
                        <span>Sleizy Marketplace</span>
                    </div>
                    <p class="footer-description">
                        Лучший маркетплейс для покупок с гарантией качества и быстрой доставкой по всей России.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link" title="VK">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="#" class="social-link" title="Telegram">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="#" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Навигация -->
                <div class="footer-nav">
                    <div class="footer-nav-column">
                        <h4 class="footer-title">Покупателям</h4>
                        <ul class="footer-links">
                            <li><a href="#" onclick="showHome(); return false;">Главная</a></li>
                            <li><a href="#" onclick="showProducts(); return false;">Каталог товаров</a></li>
                            <li><a href="#" onclick="showCart(); return false;">Корзина</a></li>
                            <li><a href="#" onclick="showFavorites(); return false;">Избранное</a></li>
                            <li><a href="#" onclick="showPromoModal(); return false;">Промокоды</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-nav-column">
                        <h4 class="footer-title">Поддержка</h4>
                        <ul class="footer-links">
                            <li><a href="tickets.php">Помощь</a></li>
                            <li><a href="#" onclick="showCreateTicketModal(); return false;">Создать тикет</a></li>
                            <li><a href="#">Частые вопросы</a></li>
                            <li><a href="#">Обратная связь</a></li>
                            <li><a href="#">Политика возврата</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-nav-column">
                        <h4 class="footer-title">О компании</h4>
                        <ul class="footer-links">
                            <li><a href="#">О нас</a></li>
                            <li><a href="#">Контакты</a></li>
                            <li><a href="#">Вакансии</a></li>
                            <li><a href="#">Партнерам</a></li>
                            <li><a href="#">Правовая информация</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Контакты -->
                <div class="footer-contact">
                    <h4 class="footer-title">Контакты</h4>
                    <div class="contact-info">
                       
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>support@sleizy.ru</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Разделитель -->
            <div class="footer-divider"></div>

            <!-- Нижняя часть футера -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>&copy; 2024 Sleizy Marketplace. Все права защищены.</p>
                    <div class="footer-legal">
                        <a href="polic.php">Политика конфиденциальности</a>
                        <a href="#">Пользовательское соглашение</a>
                        <a href="#">Cookie</a>
                    </div>
                </div>
                
                <div class="footer-payments">
                    <div class="payment-methods">
                        <i class="fab fa-cc-visa" title="Visa"></i>
                        <i class="fab fa-cc-mastercard" title="MasterCard"></i>
                        <i class="fab fa-cc-mir" title="МИР"></i>
                        <i class="fab fa-cc-paypal" title="PayPal"></i>
                        <i class="fas fa-university" title="Банковские переводы"></i>
                    </div>
                    <div class="footer-secure">
                        <i class="fas fa-lock"></i>
                        <span>Безопасные платежи</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Стили футера - БЕЛЫЙ ВАРИАНТ */
.footer {
    background: white;
    color: #333;
    margin-top: 80px;
    padding: 60px 0 30px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 -5px 30px rgba(0, 0, 0, 0.05);
    border-top: 1px solid #f0f0f0;
}

.footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2, #9c27b0);
}

.footer-content {
    position: relative;
    z-index: 1;
}

.footer-top {
    display: grid;
    grid-template-columns: 1.5fr 2fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
}

.footer-brand {
    max-width: 400px;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
}

.footer-logo i {
    color: #667eea;
    font-size: 28px;
}

.footer-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 25px;
    font-size: 15px;
}

.footer-social {
    display: flex;
    gap: 15px;
}

.social-link {
    width: 40px;
    height: 40px;
    background: #f5f5f7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 18px;
    transition: all 0.3s;
    text-decoration: none;
}

.social-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-3px);
}

.footer-nav {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.footer-title {
    color: #333;
    font-size: 18px;
    margin-bottom: 20px;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: #666;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-block;
    padding: 3px 0;
    position: relative;
}

.footer-links a:hover {
    color: #667eea;
    transform: translateX(5px);
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #666;
    font-size: 15px;
}

.contact-item i {
    color: #667eea;
    width: 20px;
    text-align: center;
}

.footer-divider {
    height: 1px;
    background: #eee;
    margin: 30px 0;
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 30px;
}

.footer-copyright p {
    color: #888;
    font-size: 14px;
    margin-bottom: 10px;
}

.footer-legal {
    display: flex;
    gap: 20px;
}

.footer-legal a {
    color: #666;
    text-decoration: none;
    font-size: 13px;
    transition: color 0.3s;
}

.footer-legal a:hover {
    color: #667eea;
}

.footer-payments {
    text-align: right;
}

.payment-methods {
    display: flex;
    gap: 15px;
    font-size: 24px;
    margin-bottom: 10px;
    justify-content: flex-end;
}

.payment-methods i {
    color: #888;
    transition: color 0.3s;
}

.payment-methods i:hover {
    color: #333;
}

.footer-secure {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: flex-end;
    color: #4CAF50;
    font-size: 14px;
}

/* Анимация появления */
.footer {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Декоративные элементы */
.footer::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle at center, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
    z-index: 0;
}

/* Адаптивность */
@media (max-width: 992px) {
    .footer-top {
        grid-template-columns: 1fr 1fr;
    }
    
    .footer-nav {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 40px 0 20px;
    }
    
    .footer-top {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .footer-nav {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    
    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-legal {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .footer-payments {
        text-align: center;
    }
    
    .payment-methods {
        justify-content: center;
    }
    
    .footer-secure {
        justify-content: center;
    }
    
    .footer-title::after {
        left: 50%;
        transform: translateX(-50%);
    }
}

/* Эффект свечения для ссылок при наведении */
.footer-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s;
}

.footer-links a:hover::after {
    width: 100%;
}

.footer-links li {
    position: relative;
}

.footer-links a {
    position: relative;
    padding-left: 0;
    transition: padding-left 0.3s;
}

.footer-links a:hover {
    padding-left: 10px;
}
</style>

<script>
// Функция для плавного скролла к футеру
function scrollToFooter() {
    const footer = document.querySelector('.footer');
    if (footer) {
        footer.scrollIntoView({ behavior: 'smooth' });
    }
}

// Показываем год в футере динамически
document.addEventListener('DOMContentLoaded', function() {
    const yearElement = document.querySelector('.footer-copyright p');
    if (yearElement) {
        const currentYear = new Date().getFullYear();
        yearElement.innerHTML = yearElement.innerHTML.replace('2024', currentYear);
    }
});
</script>
    <!-- Toast -->
    <div id="toast" class="toast"></div>
    

    <script>

         // --- ПРОСТОЙ И РАБОЧИЙ ПРЕЛОАДЕР ---
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('preloader');
            const progressBar = document.getElementById('load-progress');
            
            if (!preloader) return;
            
            let progress = 0;
            const maxProgress = 90; // Достигаем 90% при загрузке DOM
            const step = 2;
            
            // Анимация прогресс-бара
            const animateProgress = () => {
                if (progress >= maxProgress) return;
                
                progress += step;
                if (progress > maxProgress) progress = maxProgress;
                
                if (progressBar) {
                    progressBar.style.width = progress + '%';
                }
                
                // Обновляем текст статуса в зависимости от прогресса
                updateStatusText(progress);
                
                requestAnimationFrame(animateProgress);
            };
            
            function updateStatusText(p) {
                const statusText = document.querySelector('.status-text');
                if (!statusText) return;
                
                if (p < 20) statusText.textContent = 'LOADING ASSETS...';
                else if (p < 40) statusText.textContent = 'INITIALIZING UI...';
                else if (p < 60) statusText.textContent = 'LOADING DATA...';
                else if (p < 80) statusText.textContent = 'PREPARING SYSTEM...';
                else statusText.textContent = 'ALMOST READY...';
            }
            
            // Начинаем анимацию
            setTimeout(() => {
                animateProgress();
            }, 100);
            
            // Когда вся страница загружена, завершаем загрузку
            window.addEventListener('load', function() {
                // Быстро доливаем до 100%
                const finalize = () => {
                    if (progressBar) {
                        progressBar.style.width = '100%';
                    }
                    
                    if (document.querySelector('.status-text')) {
                        document.querySelector('.status-text').textContent = 'READY!';
                    }
                    
                    // Плавно скрываем прелоадер
                    setTimeout(() => {
                        preloader.style.opacity = '0';
                        setTimeout(() => {
                            preloader.classList.add('hidden');
                            startMainApplication();
                        }, 800);
                    }, 300);
                };
                
                // Если прогресс меньше 90, быстро доливаем
                if (progress < 90) {
                    let finalProgress = progress;
                    const fastInterval = setInterval(() => {
                        finalProgress += 5;
                        if (progressBar) {
                            progressBar.style.width = finalProgress + '%';
                        }
                        if (finalProgress >= 100) {
                            clearInterval(fastInterval);
                            finalize();
                        }
                    }, 50);
                } else {
                    finalize();
                }
            });
            
            // На случай, если страница уже загружена
            if (document.readyState === 'complete') {
                window.dispatchEvent(new Event('load'));
            }
        });
        
        // ФУНКЦИЯ ЗАПУСКА ОСНОВНОГО ПРИЛОЖЕНИЯ
        function startMainApplication() {
            console.log('Starting main application...');
            
            // Ваш существующий код здесь
            let currentUser = <?php echo isset($_SESSION['user']) ? json_encode($_SESSION['user']) : 'null'; ?>;
            let requirePin = false;
            let pinVerified = false; // Глобальная переменная для хранения состояния PIN
            
            $(document).ready(function() {
                checkLoginState();
                loadCartCount();
                loadNotifications();
                loadFavoritesCount();
                
                $('#ban-user-form').submit(function(e) {
                    e.preventDefault();
                    submitBan();
                });
                
                // ... весь остальной ваш код ...
            });
        }
        let currentUser = <?php echo isset($_SESSION['user']) ? json_encode($_SESSION['user']) : 'null'; ?>;
        let requirePin = false;

        $(document).ready(function() {
            checkLoginState();
            loadCartCount();
            loadNotifications();
              loadFavoritesCount(); 
            $('#ban-user-form').submit(function(e) {
    e.preventDefault();
    submitBan();
});




// При изменении причины бана
$('#ban-reason').change(function() {
    if ($(this).val() === 'Другое') {
        $('#custom-reason').show();
    } else {
        $('#custom-reason').hide();
    }
});

// При изменении срока бана
$('#ban-duration, #ban-unit').on('input change', function() {
    updateBanPreview();
});

function updateBanPreview() {
    let duration = $('#ban-duration').val();
    let unit = $('#ban-unit').val();
    let unitText = '';
    
    switch(unit) {
        case 'hours': unitText = 'часов'; break;
        case 'days': unitText = 'дней'; break;
        case 'weeks': unitText = 'недель'; break;
    }
    
    $('#ban-preview').text('На ' + duration + ' ' + unitText);
}

function submitBan() {
    let userId = $('#ban-user-id').val();
    let reason = $('#ban-reason').val() || 'Нарушение правил';
    let customReason = $('#custom-reason').val();
    let duration = $('#ban-duration').val() || '24';
    let unit = $('#ban-unit').val() || 'hours';
    let message = $('#ban-message').val();
    
    // Убираем проверку - если причина не выбрана, используем первую
    if (!reason) {
        reason = 'Нарушение правил';
    }
    
    // Если выбрано "Другое" и есть текст, используем его
    if (reason === 'Другое' && customReason && customReason.trim()) {
        reason = customReason.trim();
    }
    
    // Рассчитываем дату окончания бана
    let hours = parseInt(duration);
    
    switch(unit) {
        case 'days':
            hours *= 24;
            break;
        case 'weeks':
            hours *= 24 * 7;
            break;
    }
    
    let expires = null;
    if (hours > 0) {
        let now = new Date();
        now.setHours(now.getHours() + hours);
        expires = now.toISOString().slice(0, 19).replace('T', ' ');
    }
    
    console.log('Ban data:', {
        user_id: userId,
        reason: reason,
        duration: hours + ' hours',
        expires: expires
    });
    
    $.post('api.php?action=adm_ban', {
        user_id: userId,
        reason: reason,
        expires: expires,
        message: message
    }, function(response) {
        if (response.status === 'success') {
            closeModal('ban-user-modal');
            showToast('Пользователь забанен!');
            loadAdminUsers();
        } else {
             showToast('Ошибка: ' + (response.message || 'Неизвестная ошибка'));
        }
    }).fail(function(xhr, status, error) {
        console.error('Ban error:', error);
         showToast('Ошибка сервера: ' + error);
    });
}
            
            // Forms
            $('#login-form').submit(function(e) {
                e.preventDefault();
                login();
            });
            
            $('#register-form').submit(function(e) {
                e.preventDefault();
                register();
            });
            
            $('#add-product-form').submit(function(e) {
                e.preventDefault();
                addProduct();
            });
            
            $('#activate-promo-form').submit(function(e) {
                e.preventDefault();
                activatePromo();
            });
            
            $('#add-promo-form').submit(function(e) {
                e.preventDefault();
                addPromo();
            });
            
            $('#change-password-form').submit(function(e) {
                e.preventDefault();
                changePassword();
            });
            
            $('#set-pin-form').submit(function(e) {
                e.preventDefault();
                setPin();
            });
            
            $('#send-notification-form').submit(function(e) {
                e.preventDefault();
                sendNotification();
            });
            
            $('#create-ticket-form').submit(function(e) {
                e.preventDefault();
                createTicket();
            });
            
            $('#add-ticket-message-form').submit(function(e) {
                e.preventDefault();
                addTicketMessage();
            });
        });
function checkLoginState() {
    console.log('checkLoginState: currentUser =', currentUser);
    
    if (currentUser) {
        // Сначала скрываем бан-модалку, если она открыта
        closeModal('ban-modal');
        
        // Проверяем статус бана асинхронно
        checkBanStatus();
    } else {
        showHome();
    }
}


function checkBanStatus() {
    $.get('api.php?action=check_ban', function(response) {
        console.log('Ban check response:', response);
        
        if (response.status === 'success') {
            if (response.banned == 1) {
                console.log('User is banned, waiting for preloader...');
                
                // Ждём окончания прелоадера
                waitForPreloaderThenShowBan(response.ban_info);
                
            } else {
                // Проверяем, нужно ли требовать PIN
                checkPinRequirement();
            }
        } else {
            console.log('Ban check failed, checking PIN');
            checkPinRequirement();
        }
    }).fail(function(error) {
        console.error('Ban check error:', error);
        checkPinRequirement();
    });
}

// ========== ОЖИДАНИЕ ПРЕЛОАДЕРА И ПОКАЗ БАНА ==========
function waitForPreloaderThenShowBan(banInfo) {
    console.log('⏳ Ожидание завершения прелоадера...');
    
    const preloader = document.getElementById('preloader');
    
    // Если прелоадера нет или он уже скрыт
    if (!preloader || preloader.classList.contains('hidden')) {
        console.log('✅ Прелоадер уже скрыт, показываем бан');
        showBanModal(banInfo);
        return;
    }
    
    // Следим за изменением класса прелоадера
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                if (preloader.classList.contains('hidden')) {
                    console.log('✅ Прелоадер скрыт, показываем бан');
                    observer.disconnect();
                    showBanModal(banInfo);
                }
            }
        });
    });
    
    // Начинаем наблюдение
    observer.observe(preloader, { attributes: true });
    
    // Запасной вариант - если прелоадер не скроется за 10 секунд
    setTimeout(function() {
        observer.disconnect();
        console.log('⏰ Таймаут ожидания прелоадера, показываем бан');
        showBanModal(banInfo);
    }, 10000); // 10 секунд
}

function checkPinRequirement() {
    // Проверяем, есть ли у пользователя PIN
    $.get('api.php?action=has_pin', function(response) {
        console.log('PIN requirement check:', response);
        
        if (response && response.status === 'success') {
            if (response.has_pin && !response.pin_verified) {
                console.log('PIN required, showing modal');
                showModal('pin-modal');
            } else {
                console.log('PIN not required');
                showHome();
            }
        } else {
            console.log('PIN check failed, showing home');
            showHome();
        }
    }).fail(function(error) {
        console.error('PIN check error:', error);
        showHome();
    });
}

      function showHome() {
    let html = `
        <div style="margin-top:30px;">
            <h1 style="font-size:36px; margin-bottom:20px; color:#333;">Добро пожаловать в Sleizy!</h1>
            
            <!-- СЛАЙДЕР -->
            <div class="hero-slider" id="hero-slider">
                <div class="slider-container" id="slider-container">
                    <!-- Слайды будут добавлены через JavaScript -->
                </div>
                
                <!-- Стрелки навигации -->
                <button class="slider-arrow arrow-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-arrow arrow-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Точки навигации -->
                <div class="slider-nav" id="slider-nav">
                    <!-- Точки будут добавлены через JavaScript -->
                </div>
                
                <!-- Индикатор автоплея -->
                <div class="autoplay-indicator">
                    <i class="fas fa-play-circle"></i> Автопрокрутка
                </div>
            </div>
            
            <div style="background:white; padding:30px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="margin-bottom:20px;">Популярные товары</h3>
                <div id="products-list" class="products-grid"></div>
            </div>
        </div>
    `;
    $('#content').html(html);
    
    // Инициализируем слайдер после добавления HTML
    initSlider();
    loadProducts();
}


// Данные для слайдов
const slidesData = [
    {
        title: "🔥 Распродажа до -70%",
        description: "Только сегодня скидки на электронику и технику",
        buttonText: "Смотреть товары",
        buttonAction: "showProducts()",
        background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
    },
    {
        title: "🎁 Бесплатная доставка",
        description: "При заказе от 5000 рублей по всей России",
        buttonText: "Оформить заказ",
        buttonAction: "showCart()",
        background: "linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%)"
    },
    {
        title: "⭐ Новые поступления",
        description: "Самые свежие товары уже в каталоге",
        buttonText: "Перейти к новинкам",
        buttonAction: "loadNewProducts()",
        background: "linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%)"
    },
    {
        title: "👑 Премиум качество",
        description: "Только проверенные бренды и продавцы",
        buttonText: "Узнать больше",
        buttonAction: "showPremiumProducts()",
        background: "linear-gradient(135deg, #9c27b0 0%, #673ab7 100%)"
    }
];

function initSlider() {
    const sliderContainer = document.getElementById('slider-container');
    const sliderNav = document.getElementById('slider-nav');
    const prevBtn = document.querySelector('.arrow-prev');
    const nextBtn = document.querySelector('.arrow-next');
    
    if (!sliderContainer) return;
    
    let currentSlide = 0;
    let autoPlayInterval;
    let isAutoPlaying = true;
    
    // Создаем слайды
    slidesData.forEach((slide, index) => {
        // Создаем слайд
        const slideElement = document.createElement('div');
        slideElement.className = `slide ${index === 0 ? 'active' : ''}`;
        slideElement.style.background = slide.background;
        slideElement.innerHTML = `
            <div class="slide-content">
                <h2>${slide.title}</h2>
                <p>${slide.description}</p>
                <button class="slide-button" onclick="${slide.buttonAction}">${slide.buttonText}</button>
            </div>
        `;
        sliderContainer.appendChild(slideElement);
        
        // Создаем точку навигации
        const dot = document.createElement('button');
        dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
        dot.addEventListener('click', () => goToSlide(index));
        sliderNav.appendChild(dot);
    });
    
    // Функция перехода к слайду
    function goToSlide(index) {
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        // Убираем активный класс у всех слайдов и точек
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Добавляем активный класс текущему слайду и точке
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        
        currentSlide = index;
        resetAutoPlay();
    }
    
    // Следующий слайд
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slidesData.length;
        goToSlide(currentSlide);
    }
    
    // Предыдущий слайд
    function prevSlide() {
        currentSlide = (currentSlide - 1 + slidesData.length) % slidesData.length;
        goToSlide(currentSlide);
    }
    
    // Автоплей
    function startAutoPlay() {
        if (isAutoPlaying) {
            autoPlayInterval = setInterval(nextSlide, 5000); // Меняем каждые 5 секунд
        }
    }
    
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }
    
    // Обработчики событий
    prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoPlay();
    });
    
    nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoPlay();
    });
    
    // Пауза при наведении
    sliderContainer.addEventListener('mouseenter', () => {
        isAutoPlaying = false;
        stopAutoPlay();
    });
    
    sliderContainer.addEventListener('mouseleave', () => {
        isAutoPlaying = true;
        startAutoPlay();
    });
    
    // Запускаем автоплей
    startAutoPlay();
    
    // Добавляем обработчики свайпа (для мобильных)
    let touchStartX = 0;
    let touchEndX = 0;
    
    sliderContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    sliderContainer.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide(); // Свайп влево
            } else {
                prevSlide(); // Свайп вправо
            }
            resetAutoPlay();
        }
    }
}

// Вспомогательные функции для кнопок слайдера
function loadNewProducts() {
    // Здесь можно добавить фильтрацию по новым товарам
    showProducts();
    showToast('Загружаем новые товары...', 'info');
}

function showPremiumProducts() {
    // Здесь можно добавить фильтрацию по премиум товарам
    showProducts();
    showToast('Показываем премиум товары', 'info');
}
        function showCart() {
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    if (currentUser && currentUser.banned == 1) {
        showToast('Аккаунт заблокирован', 'error');
        return;
    }
    
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h1 style="font-size:36px; color:#333; margin: 0;">
                    <i class="fas fa-shopping-cart"></i> Корзина
                </h1>
                <div style="display: flex; gap: 10px;">
                    <button class="btn" onclick="clearCart()" style="background: #6c757d; color: white;">
                        <i class="fas fa-trash"></i> Очистить корзину
                    </button>
                    <button class="btn" onclick="showHome()" style="background: #667eea; color: white;">
                        <i class="fas fa-shopping-bag"></i> Продолжить покупки
                    </button>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
                <!-- Товары в корзине -->
                <div>
                    <div id="cart-items-container" style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); min-height: 300px;">
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="font-size: 60px; color: #ddd; margin-bottom: 20px;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h3 style="color: #666; margin-bottom: 10px;">Корзина пуста</h3>
                            <p style="color: #999;">Добавьте товары, чтобы увидеть их здесь</p>
                        </div>
                    </div>
                </div>
                
                <!-- Боковая панель с итогами -->
                <div>
                    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                        <h3 style="margin-bottom: 20px; color: #333; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-receipt"></i> Итоги заказа
                        </h3>
                        
                        <div style="margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #666;">Товары</span>
                                <span id="cart-subtotal">0 ₽</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #666;">Скидка</span>
                                <span id="cart-discount" style="color: #4CAF50;">0 ₽</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <span style="color: #666;">Доставка</span>
                                <span id="cart-shipping">0 ₽</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #333;">
                                <span>Итого</span>
                                <span id="cart-total">0 ₽</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" onclick="showCheckoutModal()" id="checkout-btn" 
                                style="width: 100%; padding: 15px; font-size: 16px; margin-bottom: 15px;"
                                disabled>
                            <i class="fas fa-credit-card"></i> Перейти к оплате
                        </button>
                        
                        <div style="text-align: center; color: #666; font-size: 14px; padding-top: 15px; border-top: 1px solid #eee;">
                            <i class="fas fa-lock"></i> Безопасная оплата
                        </div>
                    </div>
                    
                    <!-- Промокод -->
                    <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-top: 20px;">
                        <h4 style="margin-bottom: 15px; color: #333;">
                            <i class="fas fa-tag"></i> Промокод
                        </h4>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="cart-promo-code" class="form-control" placeholder="Введите промокод" 
                                   style="flex: 1; padding: 10px;">
                            <button class="btn" onclick="applyPromoToCart()" style="background: #9c27b0; color: white;">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                        <div id="cart-promo-message" style="margin-top: 10px; font-size: 14px;"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    loadCart();
}

        function showSecurity() {
            if (!currentUser) return;
            showModal('security-modal');
            showSecurityTab('password');
        }

        function showAdminPanel() {
    if (!currentUser || currentUser.role !== 'admin') return;
    
    let html = `
        <div class="admin-panel">
            <h1 style="font-size:36px; margin-bottom:30px; color:#333;">Админ-панель</h1>
            
            <div class="admin-tabs">
                <button class="admin-tab active" onclick="showAdminTab('stats')">Статистика</button>
                <button class="admin-tab" onclick="showAdminTab('users')">Пользователи</button>
                <button class="admin-tab" onclick="showAdminTab('products')">Товары</button>
                <button class="admin-tab" onclick="showAdminTab('promos')">Промокоды</button>
                <button class="admin-tab" onclick="showAdminTab('appeals')">
    <i class="fas fa-gavel"></i> Апелляции 
    <span id="appeals-count" style="background:#ff4757; padding:2px 8px; border-radius:12px; font-size:12px; margin-left:5px;">0</span>
</button>
                <button class="admin-tab" onclick="showAdminTab('tickets')">Тикеты</button>
                <button class="admin-tab" onclick="showAdminTab('logs')">Логи</button>
                <button class="admin-tab" onclick="showSendNotificationModal()">
                    <i class="fas fa-paper-plane"></i> Рассылка
                </button>
            </div>
            
            <div id="stats-tab" class="admin-content active"></div>
            <div id="users-tab" class="admin-content"></div>
            <div id="products-tab" class="admin-content"></div>
            <div id="promos-tab" class="admin-content"></div>

            <div id="tickets-tab" class="admin-content"></div>
            <div id="appeals-tab" class="admin-content"></div>

            <div id="logs-tab" class="admin-content"></div>
        </div>
    `;
    $('#content').html(html);
    loadAdminStats();
}

        function showMyTickets() {
            if (!currentUser) return;
            
            let html = `
                <div style="margin-top:30px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                        <h1 style="font-size:36px; color:#333;">Мои тикеты</h1>
                        <button class="btn btn-primary" onclick="showCreateTicketModal()">
                            <i class="fas fa-plus"></i> Новый тикет
                        </button>
                    </div>
                    <div id="my-tickets-list"></div>
                </div>
            `;
            $('#content').html(html);
            loadMyTickets();
        }


    function loadProducts() {
    console.log('📦 Загрузка товаров...');
    
    // Защита от отсутствия элементов
    let searchInput = '';
    let category = '';
    let sort = 'newest';
    
    if ($('#search-input').length) {
        searchInput = $('#search-input').val() || '';
    }
    
    if ($('#category-select').length) {
        category = $('#category-select').val() || '';
    }
    
    if ($('#sort-select').length) {
        sort = $('#sort-select').val() || 'newest';
    }
    
    $('#products-list').html('<div style="text-align: center; padding: 40px;">Загрузка...</div>');
    
    $.ajax({
        url: 'api.php?action=get_products',
        method: 'GET',
        data: {
            search: searchInput.trim(),
            category: category,
            sort: sort
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.products) {
                let products = response.products;
                
                // Стили для сетки
                $('#products-list').css({
                    'display': 'grid',
                    'grid-template-columns': 'repeat(3, 1fr)',
                    'gap': '20px',
                    'padding': '20px 0'
                });
                
                let html = '';
                
                products.forEach(product => {
                    let price = parseFloat(product.price) || 0;
                    let discount = parseInt(product.discount) || 0;
                    let finalPrice = price;
                    
                    if (discount > 0 && discount <= 100) {
                        finalPrice = price - (price * discount / 100);
                    }
                    
                    html += `
                        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative;">
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea, #764ba2); position: relative;">
                                ${product.main_image ? 
                                    `<img src="${product.main_image}" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\\'fas fa-box\\' style=\\'font-size:48px; color:white;\\'></i>';">` : 
                                    `<div style="width:100%; height:100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                                        <i class="fas fa-box"></i>
                                    </div>`
                                }
                                <button onclick="toggleFavorite(${product.id})" 
                                        style="position: absolute; top: 10px; right: 10px; background: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; z-index: 10;">
                                    <i class="far fa-heart" style="color: #e91e63; font-size: 20px;"></i>
                                </button>
                                ${discount > 0 ? 
                                    `<span style="position: absolute; top: 10px; left: 10px; background: #ff4757; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; z-index: 10;">-${discount}%</span>` 
                                    : ''}
                            </div>
                            
                            <div style="padding: 16px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600;">${product.title || 'Без названия'}</h3>
                                <p style="margin: 0 0 12px 0; color: #666; font-size: 14px; line-height: 1.5;">${product.description || 'Нет описания'}</p>
                                
                                <div style="font-size: 22px; font-weight: bold; color: #4CAF50; margin-bottom: 12px;">
                                    ${finalPrice.toFixed(2)} ₽
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; color: #888; font-size: 14px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #eee;">
                                    <span><i class="fas fa-user"></i> ${product.seller_login || 'res'}</span>
                                   <span><i class="fas fa-layer-group"></i> ${product.stock > 0 ? product.stock + ' шт.' : '<span style="color: #f44336;">Нет в наличии</span>'}</span>
                                </div>
                                
                                <button onclick="addToCart(${product.id})" 
                                        style="width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fas fa-cart-plus"></i> В корзину
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                $('#products-list').html(html);
            }
        },
        error: function() {
            $('#products-list').html('<div style="text-align: center; padding: 40px; color: red;">Ошибка загрузки</div>');
        }
    });
}

function renderProductCard(product) {
    let priceHtml = '';
    if (product.discount > 0) {
        priceHtml = `
            <div class="price-container">
                <span class="original-price">${product.price} ₽</span>
                <span class="final-price">${product.final_price || product.price} ₽</span>
                <span class="discount-badge">-${product.discount}%</span>
            </div>
        `;
    } else {
        priceHtml = `<div class="product-price">${product.price} ₽</div>`;
    }
    
    // Проверяем, есть ли товар в избранном
    const isFavorite = product.is_favorite || false;
    const heartIcon = isFavorite ? 'fas' : 'far';
    const heartColor = isFavorite ? '#e91e63' : '#666';
    
    return `
        <div class="product-card" data-id="${product.id}">
            <div class="product-image">
                ${product.main_image ? 
                    `<img src="${product.main_image}" alt="${product.title}" style="width:100%;height:200px;object-fit:cover;">` : 
                    `<div style="width:100%;height:200px;background:linear-gradient(45deg, #667eea, #764ba2);display:flex;align-items:center;justify-content:center;color:white;font-size:48px;">
                        <i class="fas fa-box"></i>
                    </div>`
                }
                ${product.discount > 0 ? '<div class="discount-badge" style="position:absolute;top:10px;right:10px;">-' + product.discount + '%</div>' : ''}
                
                <!-- КНОПКА ИЗБРАННОГО -->
                <button class="favorite-btn" onclick="toggleFavorite(${product.id})" 
                        style="position:absolute; top:10px; left:10px; background:rgba(255,255,255,0.9); 
                               border:none; width:40px; height:40px; border-radius:50%; 
                               cursor:pointer; display:flex; align-items:center; 
                               justify-content:center; transition:all 0.3s; z-index:10;"
                        onmouseover="this.style.background='white'; this.style.transform='scale(1.1)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.9)'; this.style.transform='scale(1)'">
                    <i class="${heartIcon} fa-heart" style="font-size:20px; color:${heartColor};"></i>
                </button>
            </div>
            <div class="product-info">
                <h3 class="product-title">${product.title}</h3>
                <p class="product-description">${product.description}</p>
                ${priceHtml}
                <div class="product-meta">
                    <span><i class="fas fa-user"></i> ${product.seller_login}</span>
                    <span><i class="fas fa-layer-group"></i> ${product.stock} шт.</span>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button class="btn btn-primary" style="flex: 2;" onclick="addToCart(${product.id})">
                        <i class="fas fa-cart-plus"></i> В корзину
                    </button>
                    <button class="btn favorite-btn-mobile" onclick="toggleFavorite(${product.id})" 
                            style="flex: 1; background: ${isFavorite ? '#ffe6ee' : '#f8f9fa'}; 
                                   color: ${isFavorite ? '#e91e63' : '#666'}; border: 1px solid ${isFavorite ? '#e91e63' : '#ddd'};">
                        <i class="${heartIcon} fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}
        

// Функция сброса
function resetFilters() {
    $('#search-input').val('');
    $('#category-select').val('');
    $('#sort-select').val('newest');
    loadProducts();
    showToast('🔄 Фильтры сброшены', 'info');
}



function renderProductCard(product) {
    // ПРОВЕРКА НА КУПЛЕННЫЙ ТОВАР
    let isPurchased = false;
    if (window.purchasedProducts && window.purchasedProducts.length) {
        isPurchased = window.purchasedProducts.some(p => 
            parseInt(p.product_id) === parseInt(product.id) || 
            parseInt(p.id) === parseInt(product.id)
        );
    }
    
    // БЕЗОПАСНО ПОЛУЧАЕМ ДАННЫЕ
    let title = product.title || 'Без названия';
    let description = product.description || 'Нет описания';
    let price = parseFloat(product.price || 0).toFixed(2).replace('.', ',');
    let seller = product.seller_login || 'Продавец';
    let stock = parseInt(product.stock || 0);
    let discount = parseInt(product.discount || 0);
    let image = product.main_image || '';
    
    // ========== КАРТОЧКА ДЛЯ КУПЛЕННОГО ТОВАРА ==========
    if (isPurchased) {
        return `
            <div class="product-card" data-id="${product.id}" style="border: 3px solid #dc3545; opacity: 0.7; filter: grayscale(0.9); position: relative; background: #f8f9fa; border-radius: 12px; margin-bottom: 20px; overflow: hidden;">
                <div style="position: absolute; top: 10px; left: 10px; background: #dc3545; color: white; padding: 6px 16px; border-radius: 25px; font-weight: bold; z-index: 100;">
                    🔴 ПРОДАНО
                </div>
                <div style="position: absolute; top: 20px; left: -35px; background: #dc3545; color: white; padding: 5px 40px; transform: rotate(-45deg); font-weight: bold; font-size: 13px; z-index: 101;">
                    🏷️ ПРОДАНО
                </div>
                <div class="product-image" style="height: 200px; overflow: hidden; position: relative;">
                    ${image ? 
                        `<img src="${image}" style="width:100%; height:100%; object-fit:cover; filter: grayscale(100%);">` : 
                        `<div style="width:100%; height:100%; background: linear-gradient(45deg, #6c757d, #495057); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-box"></i>
                        </div>`
                    }
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.8); color: white; padding: 10px 20px; border-radius: 30px; font-weight: bold; border: 2px solid white;">
                        <i class="fas fa-ban"></i> ТОВАР ПРОДАН
                    </div>
                </div>
                <div class="product-info" style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #721c24; font-size: 18px;">${title}</h3>
                    <p style="color: #6c757d; font-size: 14px; margin-bottom: 15px;">${description}</p>
                    <div style="background: #f8d7da; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #721c24;">Цена:</span>
                            <span style="color: #721c24; font-size: 22px; font-weight: bold; text-decoration: line-through;">${price} ₽</span>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: #6c757d; margin-bottom: 15px;">
                        <span><i class="fas fa-user"></i> ${seller}</span>
                        <span><i class="fas fa-layer-group"></i> 0 шт.</span>
                    </div>
                    <button class="btn" style="width:100%; background: #6c757d; color: white; padding: 14px; border: none; border-radius: 8px; font-weight: bold; cursor: not-allowed;" disabled>
                        <i class="fas fa-ban"></i> ТОВАР ПРОДАН
                    </button>
                </div>
            </div>
        `;
    }
    
    // ========== ОБЫЧНЫЙ ТОВАР ==========
    let finalPrice = price;
    if (discount > 0 && discount <= 100) {
        finalPrice = (parseFloat(product.price) * (1 - discount / 100)).toFixed(2).replace('.', ',');
        price = price + ' ₽';
    } else {
        price = price + ' ₽';
        finalPrice = price;
    }
    
    let stockStatus = stock > 0 ? 
        `<span><i class="fas fa-layer-group"></i> ${stock} шт.</span>` : 
        `<span style="color: #dc3545;"><i class="fas fa-times-circle"></i> Нет в наличии</span>`;
    
    let actionButton = stock > 0 ?
        `<button class="btn btn-primary" style="flex: 2; padding: 12px;" onclick="addToCart(${product.id})">
            <i class="fas fa-cart-plus"></i> В корзину
         </button>` :
        `<button class="btn" style="flex: 2; background: #ccc; color: #666; padding: 12px;" disabled>
            <i class="fas fa-times-circle"></i> Нет в наличии
         </button>`;
    
    return `
        <div class="product-card" data-id="${product.id}" style="position: relative; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 20px; ${stock <= 0 ? 'opacity: 0.7; filter: grayscale(0.5);' : ''}">
            ${stock <= 0 ? `
                <div style="position: absolute; top: 15px; left: -30px; background: #6c757d; color: white; padding: 5px 35px; transform: rotate(-45deg); font-weight: bold; font-size: 13px; z-index: 50;">
                    <i class="fas fa-ban"></i> НЕТ В НАЛИЧИИ
                </div>
            ` : ''}
            
            <div class="product-image" style="position: relative; height: 200px; overflow: hidden;">
                ${image ? 
                    `<img src="${image}" style="width:100%; height:100%; object-fit:cover; ${stock <= 0 ? 'filter: grayscale(100%);' : ''}">` : 
                    `<div style="width:100%; height:100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                        <i class="fas fa-box"></i>
                    </div>`
                }
                ${discount > 0 && stock > 0 ? 
                    `<span style="position: absolute; top: 10px; right: 10px; background: #ff4757; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">-${discount}%</span>` 
                    : ''}
            </div>
            
            <div class="product-info" style="padding: 20px;">
                <h3 style="margin: 0 0 10px 0; color: #333; font-size: 18px;">${title}</h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 15px; line-height: 1.5;">${description}</p>
                
                ${discount > 0 && stock > 0 ? `
                    <div style="margin-bottom: 15px;">
                        <span style="text-decoration: line-through; color: #999; margin-right: 10px;">${price}</span>
                        <span style="color: #e91e63; font-size: 24px; font-weight: bold;">${finalPrice} ₽</span>
                    </div>
                ` : `
                    <div style="color: #4CAF50; font-size: 24px; font-weight: bold; margin-bottom: 15px;">${finalPrice} ₽</div>
                `}
                
                <div style="display: flex; justify-content: space-between; color: #888; font-size: 13px; margin-bottom: 15px;">
                    <span><i class="fas fa-user"></i> ${seller}</span>
                    ${stockStatus}
                </div>
                
                <div style="display: flex; gap: 12px;">
                    ${actionButton}
                    <button class="btn" onclick="toggleFavorite(${product.id})" style="flex: 1; padding: 12px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px;">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}


function toggleFavorite(productId) {
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
      $.ajax({
        url: 'api.php?action=toggle_favorite',
        method: 'POST',
        data: { product_id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // ... существующий код ...
                
                // Обновляем счетчик
                loadFavoritesCount();
                // Обновляем иконку сердечка
                const $productCard = $(`.product-card[data-id="${productId}"]`);
                const isFavorite = response.is_favorite;
                
                // Обновляем обе иконки
                $productCard.find('.fa-heart')
                    .toggleClass('far fas', isFavorite)
                    .css('color', isFavorite ? '#e91e63' : '#666');
                
                // Обновляем кнопку мобильного вида
                $productCard.find('.favorite-btn-mobile')
                    .css({
                        'background': isFavorite ? '#ffe6ee' : '#f8f9fa',
                        'color': isFavorite ? '#e91e63' : '#666',
                        'border': `1px solid ${isFavorite ? '#e91e63' : '#ddd'}`
                    });
                
                // Показываем уведомление
                showToast(
                    isFavorite ? '❤️ Добавлено в избранное' : '💔 Удалено из избранного',
                    isFavorite ? 'success' : 'info'
                );
                
                // Обновляем счетчик избранного
                loadFavoritesCount();
            } else {
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Favorite error:', error);
            showToast('Ошибка соединения', 'error');
        }
    });
}


function loadFavorites() {
    if (!currentUser) {
        console.log('❌ No user logged in');
        showLoginModal();
        return;
    }
    
    console.log('🔍 Loading favorites for user:', currentUser.id);
    
    // Показываем загрузку
    $('#favorites-container').html(`
        <div style="text-align: center; padding: 40px;">
            <div class="loader"></div>
            <p style="color: #666; margin-top: 20px;">Загрузка избранного...</p>
            <button class="btn" onclick="debugFavoritesAPI()" style="margin-top: 20px; background: #667eea; color: white;">
                <i class="fas fa-bug"></i> Дебаг API
            </button>
        </div>
    `);
    
    $.ajax({
        url: 'api.php?action=get_favorites',
        method: 'GET',
        dataType: 'json', // Ждем JSON
        success: function(response) {
            console.log('📦 API Response:', response);
            
            if (response && response.status === 'success') {
                console.log('✅ Found', response.count, 'favorites');
                
                if (response.favorites && response.favorites.length > 0) {
                    renderFavorites(response.favorites);
                    $('#clear-favorites-btn').show();
                    
                    // Обновляем счетчик
                    updateFavoritesCount(response.count);
                    
                    showToast(`Загружено ${response.favorites.length} товаров`, 'success');
                } else {
                    console.log('📭 No favorites found');
                    showEmptyFavorites();
                    $('#clear-favorites-btn').hide();
                    updateFavoritesCount(0);
                }
            } else {
                console.error('❌ API Error:', response ? response.message : 'No response');
                $('#favorites-container').html(`
                    <div style="text-align: center; padding: 40px; color: #f44336;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                        <h3>Ошибка загрузки</h3>
                        <p>${response ? response.message : 'Неизвестная ошибка'}</p>
                        <button class="btn btn-primary" onclick="debugFavoritesAPI()">
                            <i class="fas fa-bug"></i> Отладка
                        </button>
                        <button class="btn" onclick="loadFavorites()" style="margin-left: 10px;">
                            <i class="fas fa-sync-alt"></i> Повторить
                        </button>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ AJAX Error:', xhr.responseText);
            
            // Пробуем распарсить как текст
            try {
                const textResponse = xhr.responseText;
                console.log('📄 Raw response:', textResponse.substring(0, 200));
                
                // Проверяем, не содержит ли ответ HTML
                if (textResponse.includes('<html') || textResponse.includes('<!DOCTYPE')) {
                    $('#favorites-container').html(`
                        <div style="text-align: center; padding: 40px; color: #f44336;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                            <h3>Ошибка сервера</h3>
                            <p>Сервер вернул HTML вместо JSON</p>
                            <p>Проверьте:</p>
                            <ul style="text-align: left; display: inline-block; margin: 20px 0;">
                                <li>Правильный ли action в API</li>
                                <li>Нет ли ошибок в PHP коде</li>
                                <li>Не выводится ли что-то перед JSON</li>
                            </ul>
                            <button class="btn btn-primary" onclick="debugFavoritesAPI()">
                                <i class="fas fa-bug"></i> Показать детали
                            </button>
                        </div>
                    `);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
            }
        }
    });
}

function debugFavoritesAPI() {
    console.log('🔍 Debug favorites API...');
    
    $.ajax({
        url: 'api.php?action=get_favorites',
        method: 'GET',
        dataType: 'text', // Сначала получаем как текст
        success: function(response) {
            console.log('📥 Raw response:', response);
            
            try {
                // Пытаемся распарсить JSON
                const jsonResponse = JSON.parse(response);
                console.log('✅ Valid JSON:', jsonResponse);
                
                // Показываем результат на странице
                $('#favorites-container').html(`
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                        <h3>Debug API Response</h3>
                        <pre style="background: white; padding: 15px; border-radius: 5px;">
${JSON.stringify(jsonResponse, null, 2)}
                        </pre>
                        <button class="btn btn-primary" onclick="loadFavorites()">
                            Вернуться к избранному
                        </button>
                    </div>
                `);
            } catch (e) {
                console.error('❌ Invalid JSON:', e);
                
                // Показываем сырой ответ
                $('#favorites-container').html(`
                    <div style="background: #fff5f5; padding: 20px; border-radius: 10px; border: 2px solid #fecaca;">
                        <h3 style="color: #dc2626;">Ошибка JSON</h3>
                        <p>Сервер вернул невалидный JSON:</p>
                        <pre style="background: white; padding: 15px; border-radius: 5px; overflow: auto;">
${response.substring(0, 1000)}
                        </pre>
                        <div style="margin-top: 15px;">
                            <button class="btn btn-primary" onclick="loadFavorites()">
                                Попробовать снова
                            </button>
                            <button class="btn" onclick="showProducts()">
                                К товарам
                            </button>
                        </div>
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ AJAX Error:', xhr.responseText);
            $('#favorites-container').html(`
                <div style="background: #fff5f5; padding: 20px; border-radius: 10px; border: 2px solid #fecaca;">
                    <h3 style="color: #dc2626;">Ошибка соединения</h3>
                    <p>Status: ${status}</p>
                    <p>Error: ${error}</p>
                    <pre style="background: white; padding: 15px; border-radius: 5px;">
${xhr.responseText.substring(0, 500)}
                    </pre>
                </div>
            `);
        }
    });
}

function updateFavoritesCount(count) {
    const $countSpan = $('#favorites-count');
    if (count > 0) {
        $countSpan.text(count).show();
    } else {
        $countSpan.hide();
    }
}

function loadFavoritesCount() {
    if (!currentUser) return;
    
    $.ajax({
        url: 'api.php?action=get_favorites_count',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response && response.status === 'success') {
                updateFavoritesCount(response.count);
            }
        },
        error: function(error) {
            console.error('Error loading favorites count:', error);
        }
    });
}

function showEmptyFavorites() {
    $('#favorites-container').html(`
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; color: #ffe6ee; margin-bottom: 20px;">
                <i class="fas fa-heart"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 15px;">Ваше избранное пусто</h3>
            <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Нажимайте на сердечко ❤️ рядом с товарами, чтобы добавить их сюда
            </p>
            <button class="btn btn-primary" onclick="showProducts()" style="padding: 12px 30px;">
                <i class="fas fa-shopping-bag"></i> Перейти к товарам
            </button>
        </div>
    `);
}

function testFavoritesAPI() {
    console.log('🧪 Testing favorites API...');
    
    // Тест 1: Проверяем toggle_favorite
    $.ajax({
        url: 'api.php?action=toggle_favorite',
        method: 'POST',
        data: { product_id: 1 },
        success: function(r) {
            console.log('Toggle test:', r);
        }
    });
    
    // Тест 2: Проверяем get_favorites
    $.ajax({
        url: 'api.php?action=get_favorites',
        method: 'GET',
        success: function(r) {
            console.log('Get favorites test:', r);
            
            // Показываем результат на странице
            $('#favorites-container').html(`
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                    <h3>Тест API</h3>
                    <pre style="background: white; padding: 15px; border-radius: 5px;">
${JSON.stringify(r, null, 2)}
                    </pre>
                    <button class="btn btn-primary" onclick="loadFavorites()">
                        Вернуться к избранному
                    </button>
                </div>
            `);
        }
    });
}

function debugFavorites() {
    console.log('=== 🐛 DEBUG FAVORITES ===');
    console.log('Current user:', currentUser);
    console.log('Favorites container:', $('#favorites-container').length);
    
    // Проверь API вручную
    $.ajax({
        url: 'api.php?action=get_favorites',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('📡 API Response:', response);
            
            if (response.status === 'success') {
                 showToast(`✅ API работает! Найдено ${response.count} товаров в избранном.\n\nПроверь консоль (F12) для деталей.`);
                
                // Покажи сырые данные
                if (response.favorites && response.favorites.length > 0) {
                    console.table(response.favorites);
                }
            } else {
                 showToast(`❌ API ошибка: ${response.message}`);
            }
        },
        error: function(xhr) {
            console.error('API Error:', xhr.responseText);
             showToast('❌ Ошибка соединения с API. Проверь консоль.');
        }
    });
}

function renderFavorites(favorites) {
    console.log('🎨 Rendering favorites:', favorites);
    
    let html = `
        <div style="margin-bottom: 25px;">
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.08);">
                <h3 style="margin: 0 0 10px 0; color: #333;">
                    <i class="fas fa-heart" style="color: #e91e63;"></i> Избранное
                </h3>
                <p style="color: #666; margin: 0; font-size: 14px;">
                    ${favorites.length} товар(ов) в избранном
                </p>
            </div>
        </div>
    `;
    
    if (favorites.length === 0) {
        html += showEmptyFavorites();
    } else {
        html += `<div class="products-grid">`;
        
        favorites.forEach(product => {
            console.log('Processing favorite product:', product);
            
            // Создаем упрощенную карточку товара
            html += `
                <div class="product-card" data-id="${product.id}">
                    <div class="product-image">
                        ${product.main_image ? 
                            `<img src="${product.main_image}" alt="${product.title}" 
                                  style="width:100%;height:200px;object-fit:cover;border-radius: 8px 8px 0 0;">` : 
                            `<div style="width:100%;height:200px;background:linear-gradient(45deg, #667eea, #764ba2);
                               display:flex;align-items:center;justify-content:center;color:white;font-size:48px;">
                                <i class="fas fa-box"></i>
                            </div>`
                        }
                        <div style="position: absolute; top: 10px; left: 10px; 
                                    background: #e91e63; color: white; padding: 5px 10px; 
                                    border-radius: 12px; font-size: 12px;">
                            <i class="fas fa-heart"></i> В избранном
                        </div>
                    </div>
                    
                    <div style="padding: 20px;">
                        <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px;">
                            ${product.title || 'Без названия'}
                        </h4>
                        
                        ${product.description ? 
                            `<p style="color: #666; margin-bottom: 15px; font-size: 14px; line-height: 1.4;">
                                ${product.description.substring(0, 100)}${product.description.length > 100 ? '...' : ''}
                            </p>` : ''
                        }
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div style="font-size: 22px; font-weight: bold; color: #4CAF50;">
                                ${product.price || 0} ₽
                            </div>
                            ${product.discount > 0 ? 
                                `<span style="background: #ff4757; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px;">
                                    -${product.discount}%
                                </span>` : ''
                            }
                        </div>
                        
                        <div style="color: #666; font-size: 12px; margin-bottom: 15px;">
                            <div><i class="fas fa-user"></i> ${product.seller_login || 'Продавец'}</div>
                            <div><i class="fas fa-layer-group"></i> ${product.stock || 0} шт. в наличии</div>
                            ${product.added_date ? 
                                `<div><i class="fas fa-calendar"></i> Добавлено: ${new Date(product.added_date).toLocaleDateString()}</div>` : ''
                            }
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-primary" style="flex: 2; padding: 10px;" 
                                    onclick="addToCart(${product.id})">
                                <i class="fas fa-cart-plus"></i> В корзину
                            </button>
                            <button class="btn" style="flex: 1; padding: 10px; background: #ffe6ee; color: #e91e63;"
                                    onclick="toggleFavorite(${product.id})">
                                <i class="fas fa-heart-broken"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `</div>`;
    }
    
    console.log('📄 Generated HTML length:', html.length);
    $('#favorites-container').html(html);
}

function showEmptyFavorites() {
    return `
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; color: #ffe6ee; margin-bottom: 20px;">
                <i class="fas fa-heart"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 15px;">Ваше избранное пусто</h3>
            <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Нажимайте на сердечко ❤️ рядом с товарами, чтобы добавить их сюда
            </p>
            <button class="btn btn-primary" onclick="showProducts()" style="padding: 12px 30px; margin: 5px;">
                <i class="fas fa-shopping-bag"></i> Перейти к товарам
            </button>
            <button class="btn" onclick="showHome()" style="padding: 12px 30px; margin: 5px; background: #667eea; color: white;">
                <i class="fas fa-home"></i> На главную
            </button>
        </div>
    `;
}

function toggleFavorite(productId) {
    console.log('Toggle favorite for product:', productId);
    
    if (!currentUser) {
        console.log('No user, showing login modal');
        showLoginModal();
        return;
    }
    
    $.ajax({
        url: 'api.php?action=toggle_favorite',
        method: 'POST',
        data: { product_id: productId },
        dataType: 'json',
        beforeSend: function() {
            console.log('Sending favorite request...');
        },
        success: function(response) {
            console.log('Favorite response:', response);
            
            if (response.status === 'success') {
                const isFavorite = response.is_favorite;
                
                // Обновляем иконку на всех страницах
                $(`.product-card[data-id="${productId}"] .fa-heart`)
                    .toggleClass('far fas', isFavorite)
                    .css('color', isFavorite ? '#e91e63' : '#666');
                
                // Обновляем счетчик в меню
                loadFavoritesCount();
                
                // Показываем уведомление
                showToast(response.message, isFavorite ? 'success' : 'info');
                
                // Если мы на странице избранного, перезагружаем ее
                if ($('#favorites-container').length > 0) {
                    console.log('On favorites page, reloading...');
                    loadFavorites();
                }
                
            } else {
                console.error('Favorite error:', response.message);
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Favorite AJAX error:', xhr.responseText, status, error);
            showToast('Ошибка соединения', 'error');
        }
    });
}

function renderFavorites(favorites) {
    let html = `
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333;">Товаров в избранном: ${favorites.length}</h3>
            <button class="btn" onclick="clearFavorites()" style="background: #fff5f5; color: #dc3545; border: 1px solid #fecaca;">
                <i class="fas fa-trash"></i> Очистить всё
            </button>
        </div>
        <div class="products-grid" id="favorites-grid">
    `;
    
    favorites.forEach(product => {
        // Помечаем товар как избранный
        product.is_favorite = true;
        
        html += renderProductCard(product);
    });
    
    html += '</div>';
    
    $('#favorites-container').html(html);
}

function showEmptyFavorites() {
    $('#favorites-container').html(`
        <div style="text-align: center; padding: 80px 20px;">
            <div style="font-size: 80px; color: #ffe6ee; margin-bottom: 20px;">
                <i class="fas fa-heart"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 15px;">Ваше избранное пусто</h3>
            <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Добавьте товары из каталога, нажав на сердечко ❤️
            </p>
            <button class="btn btn-primary" onclick="showProducts()" style="padding: 12px 30px;">
                <i class="fas fa-shopping-bag"></i> Перейти к товарам
            </button>
        </div>
    `);
}

function clearFavorites() {
    if (!confirm('Удалить все товары из избранного?')) return;
    
    $.ajax({
        url: 'api.php?action=clear_favorites',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showToast('Избранное очищено', 'success');
                showFavorites(); // Обновляем страницу
                
                // Обновляем все товары на странице
                $('.product-card').each(function() {
                    $(this).find('.fa-heart')
                        .removeClass('fas').addClass('far')
                        .css('color', '#666');
                });
                
                // Обновляем счетчик
                loadFavoritesCount();
            } else {
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function(error) {
            console.error('Clear favorites error:', error);
            showToast('Ошибка соединения', 'error');
        }
    });
}

function loadFavoritesCount() {
    if (!currentUser) return;
    
    $.ajax({
        url: 'api.php?action=get_favorites_count',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const $countSpan = $('#favorites-count');
                if (response.count > 0) {
                    $countSpan.text(response.count).show();
                } else {
                    $countSpan.hide();
                }
            }
        },
        error: function(error) {
            console.error('Error loading favorites count:', error);
        }
    });
}

function showFavorites() {
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h1 style="font-size:36px; color:#333;">
                    <i class="fas fa-heart" style="color: #e91e63;"></i> Избранное
                </h1>
                <button class="btn" onclick="showProducts()" style="background: #667eea; color: white;">
                    <i class="fas fa-arrow-left"></i> Назад
                </button>
            </div>
            
            <div id="favorites-container" style="min-height: 400px;">
                <div style="text-align: center; padding: 60px;">
                    <div class="loader"></div>
                    <p style="color: #666; margin-top: 20px;">Загрузка избранного...</p>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    loadFavorites();
}

function loadFavorites() {
    if (!currentUser) return;
    
    $.ajax({
        url: 'api.php?action=get_favorites',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Избранное:', response);
            
            if (response.status === 'success') {
                if (response.favorites && response.favorites.length > 0) {
                    let html = '<div class="products-grid">';
                    
                    response.favorites.forEach(product => {
                        html += `
                            <div class="product-card" data-id="${product.id}">
                                <div class="product-image" style="height: 200px; position: relative;">
                                    ${product.main_image ? 
                                        `<img src="${product.main_image}" style="width:100%; height:100%; object-fit:cover;">` : 
                                        `<div style="width:100%; height:100%; background: linear-gradient(45deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                                            <i class="fas fa-box"></i>
                                        </div>`
                                    }
                                    <button onclick="toggleFavorite(${product.id})" 
                                            style="position: absolute; top: 10px; right: 10px; background: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; color: #e91e63;">
                                        <i class="fas fa-heart" style="font-size: 20px;"></i>
                                    </button>
                                </div>
                                <div class="product-info" style="padding: 15px;">
                                    <h3 style="margin: 0 0 10px 0; font-size: 16px;">${product.title}</h3>
                                    <div style="color: #4CAF50; font-size: 20px; font-weight: bold; margin-bottom: 10px;">
                                        ${parseFloat(product.price).toFixed(2).replace('.', ',')} ₽
                                    </div>
                                    <div style="display: flex; justify-content: space-between; color: #666; font-size: 13px; margin-bottom: 15px;">
                                        <span><i class="fas fa-user"></i> ${product.seller_login || 'Продавец'}</span>
                                        <span><i class="fas fa-box"></i> ${product.stock || 0} шт.</span>
                                    </div>
                                    <button class="btn btn-primary" style="width:100%;" onclick="addToCart(${product.id})">
                                        <i class="fas fa-cart-plus"></i> В корзину
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    $('#favorites-container').html(html);
                } else {
                    $('#favorites-container').html(`
                        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px;">
                            <i class="fas fa-heart" style="font-size: 80px; color: #ffe6ee; margin-bottom: 20px;"></i>
                            <h3 style="color: #666; margin-bottom: 10px;">В избранном пока пусто</h3>
                            <p style="color: #999; margin-bottom: 30px;">Нажмите на сердечко рядом с товаром, чтобы добавить его сюда</p>
                            <button class="btn btn-primary" onclick="showProducts()">Перейти к товарам</button>
                        </div>
                    `);
                }
            } else {
                showToast('Ошибка загрузки избранного', 'error');
            }
        },
        error: function(xhr) {
            console.error('Ошибка:', xhr.responseText);
            showToast('Ошибка соединения', 'error');
        }
    });
}
// Иконки для категорий
function getCategoryIcon(category) {
    const icons = {
        'electronics': 'tv',
        'clothes': 'tshirt',
        'books': 'book',
        'home': 'home',
        'beauty': 'spa',
        'other': 'box'
    };
    return icons[category] || 'box';
}
        function loadCart() {
    $.ajax({
        url: 'api.php?action=get_cart',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Cart response:', response);
            
            if (response.status === 'success') {
                if (response.items && response.items.length > 0) {
                    renderCartItems(response.items);
                    updateCartSummary(response);
                    $('#checkout-btn').prop('disabled', false);
                } else {
                    showEmptyCart();
                }
            } else {
                showEmptyCart();
                showToast(response.message || 'Ошибка загрузки корзины', 'error');
            }
        },
        error: function(error) {
            console.error('Cart load error:', error);
            showEmptyCart();
        }
    });
}

function renderCartItems(items) {
    let html = `
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333;">Товары в корзине (${items.length})</h3>
            <button class="btn" onclick="clearCart()" style="background: #fff5f5; color: #dc3545; border: 1px solid #fecaca;">
                <i class="fas fa-trash"></i> Очистить всё
            </button>
        </div>
    `;
    
    items.forEach((item, index) => {
        let itemTotal = (item.price * item.quantity).toFixed(2);
        let discountAmount = item.discount > 0 ? (itemTotal * item.discount / 100).toFixed(2) : 0;
        let finalPrice = (itemTotal - discountAmount).toFixed(2);
        
        html += `
            <div class="cart-item" data-id="${item.id}" style="border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 15px; background: #fff; transition: all 0.3s;">
                <div style="display: flex; gap: 20px; align-items: flex-start;">
                    <!-- Изображение -->
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        ${item.image ? 
                            `<img src="${item.image}" alt="${item.title}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">` : 
                            `<i class="fas fa-box" style="font-size: 40px; color: #667eea;"></i>`
                        }
                    </div>
                    
                    <!-- Информация -->
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <h4 style="margin: 0 0 5px 0; color: #333; font-size: 18px;">${item.title}</h4>
                                <p style="color: #666; margin: 0; font-size: 14px;">
                                    <i class="fas fa-user"></i> ${item.seller_login}
                                </p>
                            </div>
                            <button class="btn btn-danger" onclick="removeFromCart(${item.id})" style="padding: 5px 10px; border-radius: 8px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Цены -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                ${item.discount > 0 ? `
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="text-decoration: line-through; color: #999; font-size: 16px;">
                                            ${item.price} ₽
                                        </span>
                                        <span style="color: #e91e63; font-size: 22px; font-weight: bold;">
                                            ${(item.price * (1 - item.discount/100)).toFixed(2)} ₽
                                        </span>
                                        <span style="background: #ff4757; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                                            -${item.discount}%
                                        </span>
                                    </div>
                                ` : `
                                    <span style="color: #4CAF50; font-size: 22px; font-weight: bold;">
                                        ${item.price} ₽
                                    </span>
                                `}
                            </div>
                            
                            <!-- Количество -->
                            <div style="display: flex; align-items: center; gap: 10px; background: #f8f9fa; padding: 8px 15px; border-radius: 25px;">
                                <button class="btn-quantity" onclick="updateCartQuantity(${item.id}, -1)" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #667eea; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span style="font-weight: bold; min-width: 30px; text-align: center;">${item.quantity}</span>
                                <button class="btn-quantity" onclick="updateCartQuantity(${item.id}, 1)" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #667eea; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Итого за товар -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #eee;">
                            <span style="color: #666;">Стоимость:</span>
                            <span style="font-size: 20px; font-weight: bold; color: #333;">
                                ${finalPrice} ₽
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#cart-items-container').html(html);
}

function updateCartSummary(data) {
    const subtotal = data.subtotal || 0;
    const discount = data.discount || 0;
    const shipping = data.shipping || 0;
    const total = data.total || 0;
    
    $('#cart-subtotal').text(subtotal.toFixed(2) + ' ₽');
    $('#cart-discount').text('-' + discount.toFixed(2) + ' ₽');
    $('#cart-shipping').text(shipping > 0 ? shipping.toFixed(2) + ' ₽' : 'Бесплатно');
    $('#cart-total').text(total.toFixed(2) + ' ₽');
}

function showEmptyCart() {
    $('#cart-items-container').html(`
        <div style="text-align: center; padding: 80px 20px;">
            <div style="font-size: 80px; color: #e0e0e0; margin-bottom: 20px;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 15px;">Ваша корзина пуста</h3>
            <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Добавьте товары из каталога, чтобы увидеть их здесь
            </p>
            <button class="btn btn-primary" onclick="showProducts()" style="padding: 12px 30px;">
                <i class="fas fa-shopping-bag"></i> Перейти к покупкам
            </button>
        </div>
    `);
    $('#checkout-btn').prop('disabled', true);
}

function updateCartQuantity(productId, change) {
    // Находим текущее количество
    const itemElement = $(`.cart-item[data-id="${productId}"]`);
    const quantityElement = itemElement.find('.btn-quantity + span');
    const currentQuantity = parseInt(quantityElement.text()) || 1;
    const newQuantity = currentQuantity + change;
    
    if (newQuantity < 1) {
        // Если количество становится 0 - удаляем товар
        removeFromCart(productId);
        return;
    }
    
    // Визуальное обновление количества
    quantityElement.text(newQuantity);
    
    $.ajax({
        url: 'api.php?action=update_cart_quantity',
        method: 'POST',
        data: {
            product_id: productId,
            change: change
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Анимация обновления
                quantityElement.css({
                    'transform': 'scale(1.3)',
                    'color': change > 0 ? '#4CAF50' : '#ff9800'
                });
                
                setTimeout(() => {
                    quantityElement.css({
                        'transform': 'scale(1)',
                        'color': '#333'
                    });
                }, 300);
                
                // Пересчитываем итоги
                loadCart();
                loadCartCount();
                
                showToast(change > 0 ? '➕ Количество увеличено' : '➖ Количество уменьшено', 'info');
            } else {
                // Откатываем визуальное изменение при ошибке
                quantityElement.text(currentQuantity);
                showToast('❌ ' + response.message, 'error');
            }
        },
        error: function() {
            // Откатываем при ошибке сети
            quantityElement.text(currentQuantity);
            showToast('❌ Ошибка соединения', 'error');
        }
    });
}

function removeFromCart(productId) {
    if (confirm('Удалить товар из корзины?')) {
        $.post('api.php?action=remove_from_cart', {
            product_id: productId
        }, function(response) {
            if (response.status === 'success') {
                loadCart();
                loadCartCount();
                showToast('Товар удален из корзины', 'success');
            }
        });
    }
}


function clearCart() {
    Swal.fire({
        title: 'Очистить корзину?',
        text: 'Все товары будут удалены из корзины',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Да, очистить!',
        cancelButtonText: 'Отмена',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Очистка...',
                text: 'Удаляем товары из корзины',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=clear_cart',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        showToast('🎉 Корзина очищена!', 'success');
                        loadCart();
                        loadCartCount();
                    } else {
                        showToast('❌ ' + (response.message || 'Ошибка очистки'), 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    showToast('❌ Ошибка соединения', 'error');
                }
            });
        }
    });
}

function applyPromoToCart() {
    console.log('🎫 ПРИМЕНЕНИЕ ПРОМОКОДА');
    
    // Ищем поле ввода
    const $input = $('#cart-promo-code');
    if (!$input.length) {
        console.error('❌ Поле cart-promo-code не найдено!');
        showToast('Ошибка: поле ввода не найдено', 'error');
        return;
    }
    
    const code = $input.val().trim();
    console.log('📝 Введенный код:', code);
    
    if (!code) {
        showToast('❌ Введите код промокода', 'warning');
        $input.focus();
        return;
    }
    
    // Показываем загрузку
    $('#cart-promo-message').html('<span style="color: #666;"><i class="fas fa-spinner fa-spin"></i> Проверка промокода...</span>');
    
    // ВАЖНО! Отправляем в cart_api.php, НЕ в api.php!
    $.ajax({
        url: 'cart_api.php?action=apply_promo',  // 👈 ИЗМЕНЕНО!
        type: 'POST',
        data: { 
            code: code.toUpperCase() 
        },
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('✅ Ответ от cart_api.php:', response);
            
            if (response.status === 'success') {
                showToast('✅ Промокод применен!', 'success');
                
                // Обновляем итоги корзины
                $('#cart-subtotal').text(response.subtotal.toFixed(2) + ' ₽');
                $('#cart-discount').text('-' + response.discount.toFixed(2) + ' ₽').parent().show();
                $('#cart-total').text(response.total.toFixed(2) + ' ₽');
                
                // Показываем сообщение об успехе
                $('#cart-promo-message').html(`
                    <div style="color: #4CAF50; padding: 12px; background: #e8f5e9; border-radius: 10px; margin-top: 10px; border-left: 4px solid #4CAF50;">
                        <i class="fas fa-check-circle"></i> 
                        <strong style="font-size: 16px;">${response.promo.code}</strong> - скидка ${response.discount.toFixed(2)} ₽
                    </div>
                `);
                
                // Блокируем поле ввода
                $input.prop('disabled', true).css('background', '#f5f5f5');
                
            } else {
                showToast('❌ ' + response.message, 'error');
                
                $('#cart-promo-message').html(`
                    <div style="color: #f44336; padding: 12px; background: #ffebee; border-radius: 10px; margin-top: 10px; border-left: 4px solid #f44336;">
                        <i class="fas fa-exclamation-circle"></i> ${response.message}
                    </div>
                `);
                
                $input.focus();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Ошибка AJAX:', {status, error, response: xhr.responseText});
            
            showToast('❌ Ошибка соединения', 'error');
            
            $('#cart-promo-message').html(`
                <div style="color: #f44336; padding: 12px; background: #ffebee; border-radius: 10px; margin-top: 10px;">
                    <i class="fas fa-exclamation-triangle"></i> Ошибка соединения с сервером
                </div>
            `);
        }
    });
}

        function loadCartCount() {
            if (!currentUser) return;
            
            $.get('api.php?action=get_cart_count', function(response) {
                if (response.status === 'success') {
                    $('#cart-count').text(response.count);
                }
            });
        }

        function loadNotifications() {
            if (!currentUser) return;
            
            $.get('api.php?action=get_notifications', function(response) {
                if (response.status === 'success') {
                    $('#notification-count').text(response.unread_count);
                }
            });
        }

        function loadAdminStats() {
            $.get('api.php?action=adm_stats', function(response) {
                if (response.status === 'success') {
                    let stats = response.stats;
                    let html = `
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-value">${stats.total_users}</div>
                                <div class="stat-label">Пользователей</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">${stats.total_products}</div>
                                <div class="stat-label">Товаров</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">${stats.total_orders}</div>
                                <div class="stat-label">Заказов</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">${stats.total_revenue} ₽</div>
                                <div class="stat-label">Выручка</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">${stats.open_tickets}</div>
                                <div class="stat-label">Открытых тикетов</div>
                            </div>
                        </div>
                    `;
                    $('#stats-tab').html(html);
                }
            });
        }


function getTabName(tab) {
    const names = {
        'stats': 'Статистика',
        'users': 'Пользователи',
        'products': 'Товары',
        'promos': 'Промокоды',
        'tickets': 'Тикеты',
        'appeals': 'Апелляции',
        'logs': 'Логи'
    };
    return names[tab] || tab;
}


        
// ========== ПЕРЕКЛЮЧЕНИЕ ВКЛАДОК АДМИН-ПАНЕЛИ ==========
function showAdminTab(tab) {
    console.log('📌 Переключение на вкладку:', tab);
    
    // 1. Убираем активный класс у всех кнопок
    $('.admin-tab').removeClass('active');
    
    // 2. Убираем активный класс у всех контентов
    $('.admin-content').removeClass('active').hide();
    
    // 3. Активируем нужную кнопку по тексту или data-атрибуту
    $(`.admin-tab[onclick*="'${tab}'"], .admin-tab:contains("${getTabName(tab)}")`).addClass('active');
    
    // 4. Показываем нужный контент
    $(`#${tab}-tab`).show().addClass('active');
    
    // 5. Загружаем данные для вкладки
    switch(tab) {
        case 'stats':
            if (typeof loadAdminStats === 'function') loadAdminStats();
            break;
            
        case 'users':
            if (typeof loadAdminUsers === 'function') loadAdminUsers();
            break;
            
        case 'products':
            if (typeof loadAdminProducts === 'function') loadAdminProducts();
            break;
            
        case 'promos':
            if (typeof loadAdminPromos === 'function') loadAdminPromos();
            break;
            
        case 'tickets':
            if (typeof loadAdminTickets === 'function') loadAdminTickets();
            break;
            
        case 'appeals': // 👈 НАША ВКЛАДКА АПЕЛЛЯЦИЙ
            console.log('🟠 Загрузка апелляций...');
            // ПРИНУДИТЕЛЬНО ПОКАЗЫВАЕМ КОНТЕЙНЕР
            $('#appeals-tab').show().addClass('active');
            // ЗАГРУЖАЕМ АПЕЛЛЯЦИИ
            if (typeof loadAdminAppeals === 'function') {
                loadAdminAppeals();
            } else {
                console.error('❌ Функция loadAdminAppeals не найдена!');
                $('#appeals-tab').html('<div style="padding:20px; color:#f44336;">Ошибка: функция загрузки апелляций не определена</div>');
            }
            break;
            
        case 'logs':
            if (typeof loadAdminLogs === 'function') loadAdminLogs();
            break;
            
        default:
            console.warn('⚠️ Неизвестная вкладка:', tab);
    }
}

// ========== ПОЛУЧЕНИЕ НАЗВАНИЯ ВКЛАДКИ ==========
function getTabName(tab) {
    const names = {
        'stats': 'Статистика',
        'users': 'Пользователи',
        'products': 'Товары',
        'promos': 'Промокоды',
        'tickets': 'Тикеты',
        'appeals': 'Апелляции', // 👈 ДОБАВЛЕНО
        'logs': 'Логи'
    };
    return names[tab] || tab;
}

// ========== ЗАГРУЗКА АПЕЛЛЯЦИЙ ==========
function loadAdminAppeals() {
    console.log('📥 Загрузка апелляций...');
    
    $.ajax({
        url: 'api.php?action=adm_get_appeals',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(response) {
            console.log('✅ Апелляции загружены:', response);
            
            if (response.status === 'success') {
                let html = `
                    <div style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <h2 style="margin: 0; color: #333; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-gavel" style="color: #ff9800;"></i>
                                Апелляции пользователей
                            </h2>
                            <span style="background: #667eea; color: white; padding: 8px 20px; border-radius: 25px; font-weight: bold;">
                                Всего: ${response.count}
                            </span>
                        </div>
                `;
                
                if (response.appeals && response.appeals.length > 0) {
                    response.appeals.forEach(function(appeal) {
                        let date = appeal.created_at ? new Date(appeal.created_at).toLocaleString('ru-RU') : 'Нет даты';
                        let statusColor = appeal.status === 'new' ? '#ff9800' : 
                                        appeal.status === 'in_progress' ? '#2196F3' : 
                                        appeal.status === 'closed' ? '#4CAF50' : '#6c757d';
                        let statusText = appeal.status === 'new' ? 'Новая' : 
                                       appeal.status === 'in_progress' ? 'В работе' : 
                                       appeal.status === 'closed' ? 'Решена' : 'Закрыта';
                        
                        html += `
                            <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; 
                                        border-left: 6px solid ${statusColor}; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                                
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; flex-wrap: wrap;">
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                        <span style="background: #ff4757; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold;">
                                            <i class="fas fa-ban"></i> ID: ${appeal.user_id}
                                        </span>
                                        <span style="background: #667eea; color: white; padding: 5px 15px; border-radius: 20px;">
                                            <i class="fas fa-ticket-alt"></i> №${appeal.ticket_id || appeal.id}
                                        </span>
                                        <span style="background: ${statusColor}; color: white; padding: 5px 15px; border-radius: 20px;">
                                            ${statusText}
                                        </span>
                                    </div>
                                    <span style="color: #666; font-size: 13px;">
                                        <i class="fas fa-clock"></i> ${date}
                                    </span>
                                </div>
                                
                                <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <strong style="color: #e65100;">📌 Причина бана:</strong>
                                    <span style="margin-left: 10px; color: #333;">${appeal.ban_reason || 'Не указана'}</span>
                                </div>
                                
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <strong style="color: #333;">💬 Сообщение пользователя:</strong>
                                    <p style="margin: 10px 0 0 0; color: #666; white-space: pre-wrap;">${appeal.message || 'Нет сообщения'}</p>
                                </div>
                                
                                ${appeal.admin_response ? `
                                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; margin-bottom: 15px; border-left: 4px solid #2196F3;">
                                    <strong style="color: #1565C0;">👨‍⚖️ Ваш ответ:</strong>
                                    <p style="margin: 10px 0 0 0; color: #333; white-space: pre-wrap;">${appeal.admin_response}</p>
                                </div>
                                ` : ''}
                                
                                <div style="display: flex; gap: 10px; margin-top: 20px;">
                                    <button onclick="adminUnbanFromAppeal(${appeal.user_id}, ${appeal.ticket_id})" 
                                            style="background: #4CAF50; color: white; border: none; padding: 12px 25px; 
                                                   border-radius: 8px; font-size: 14px; font-weight: bold; cursor: pointer;
                                                   display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-check-circle"></i>
                                        Разблокировать
                                    </button>
                                  <button class="reply-appeal-btn" 
        data-appeal-id="${appeal.id}" 
        data-user-id="${appeal.user_id}" 
        data-ticket-id="${appeal.ticket_id}"
        style="background: #2196F3; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer;">
    <i class="fas fa-reply"></i> Ответить
</button>                                  
                                    </div>
                            </div>
                        `;
                    });
                } else {
                    html += `
                        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px;">
                            <div style="width: 100px; height: 100px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                <i class="fas fa-check-circle" style="font-size: 50px; color: #4CAF50;"></i>
                            </div>
                            <h3 style="color: #666; margin-bottom: 10px;">Апелляций нет</h3>
                            <p style="color: #999;">Все апелляции обработаны</p>
                        </div>
                    `;
                }
                
                html += `</div>`;
                $('#appeals-tab').html(html);
                $('#appeals-count').text(response.count);
                
            } else {
                $('#appeals-tab').html(`<div style="padding: 20px; color: #f44336;">❌ ${response.message}</div>`);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Ошибка загрузки апелляций:', error);
            $('#appeals-tab').html(`<div style="padding: 20px; color: #f44336;">❌ Ошибка соединения: ${error}</div>`);
        }
    });
}

      function loadAdminPromos() {
    $.ajax({
        url: 'api.php?action=adm_promos',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.promos) {
                let html = `
                    <div style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <h2 style="margin: 0; color: #333; display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-tags" style="color: #667eea;"></i>
                                Управление промокодами
                            </h2>
                            <div style="display: flex; gap: 15px;">
                                <span style="background: #667eea; color: white; padding: 8px 20px; border-radius: 25px; font-weight: 600;">
                                    Всего: ${response.promos.length}
                                </span>
                                <button onclick="showAddPromoModal()" 
                                        style="background: #4CAF50; color: white; border: none; padding: 8px 20px; 
                                               border-radius: 25px; font-weight: 600; cursor: pointer;
                                               display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-plus"></i> Создать
                                </button>
                            </div>
                        </div>
                        
                        <div style="background: white; border-radius: 20px; box-shadow: 0 5px 30px rgba(0,0,0,0.05); overflow: hidden;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                                    <tr>
                                        <th style="padding: 16px;">Код</th>
                                        <th style="padding: 16px;">Тип</th>
                                        <th style="padding: 16px;">Значение</th>
                                        <th style="padding: 16px;">Использовано</th>
                                        <th style="padding: 16px;">Срок</th>
                                        <th style="padding: 16px;">Статус</th>
                                        <th style="padding: 16px;">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                
                response.promos.forEach(promo => {
                    let typeHtml = promo.type === 'balance' 
                        ? '<span style="background: #4CAF50; color: white; padding: 6px 12px; border-radius: 20px;"><i class="fas fa-coins"></i> Баланс</span>'
                        : '<span style="background: #ff9800; color: white; padding: 6px 12px; border-radius: 20px;"><i class="fas fa-tag"></i> Скидка</span>';
                    
                    let valueHtml = promo.type === 'balance'
                        ? `<span style="font-weight: bold; color: #4CAF50;">+${promo.value} ₽</span>`
                        : `<span style="font-weight: bold; color: #ff9800;">-${promo.value}%</span>`;
                    
                    let status = promo.is_active == 1 
                        ? '<span style="background: #4CAF50; color: white; padding: 6px 12px; border-radius: 20px;">Активен</span>'
                        : '<span style="background: #6c757d; color: white; padding: 6px 12px; border-radius: 20px;">Неактивен</span>';
                    
                    html += `<tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 16px;"><strong style="font-family: monospace; font-size: 16px;">${promo.code}</strong></td>
                        <td style="padding: 16px;">${typeHtml}</td>
                        <td style="padding: 16px;">${valueHtml}</td>
                        <td style="padding: 16px;">${promo.uses || 0} / ${promo.max_uses || '∞'}</td>
                        <td style="padding: 16px;">${promo.expires ? new Date(promo.expires).toLocaleDateString('ru-RU') : '∞'}</td>
                        <td style="padding: 16px;">${status}</td>
                        <td style="padding: 16px;">
                            <button onclick="togglePromoStatus(${promo.id})" 
                                    style="background: #ff9800; color: white; border: none; padding: 8px 12px; border-radius: 8px; margin-right: 5px; cursor: pointer;">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button onclick="deletePromo(${promo.id})" 
                                    style="background: #ff4757; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                
                html += `</tbody></table></div></div>`;
                $('#promos-tab').html(html);
            }
        }
    });
}

// ========== ПОКАЗ МОДАЛКИ СОЗДАНИЯ ПРОМОКОДА ==========
function showAddPromoModal() {
    if (!currentUser || currentUser.role !== 'admin') {
        showToast('Доступ запрещен', 'error');
        return;
    }
    
    Swal.fire({
        title: '🎫 Создание промокода',
        html: `
            <div style="text-align: left; padding: 10px;">
                <!-- Тип промокода -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        📌 Тип промокода
                    </label>
                    <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                        <label style="flex: 1; background: #f8f9fa; padding: 15px; border-radius: 12px; border: 2px solid #e0e0e0; cursor: pointer; transition: all 0.3s;" 
                               onmouseover="this.style.borderColor='#4CAF50'" 
                               onmouseout="this.style.borderColor='#e0e0e0'">
                            <input type="radio" name="promo_type" value="balance" checked style="margin-right: 8px;">
                            <i class="fas fa-coins" style="color: #4CAF50; font-size: 20px;"></i>
                            <strong style="margin-left: 8px;">Начисление на баланс</strong>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Пользователь получит деньги на счет</p>
                        </label>
                        <label style="flex: 1; background: #f8f9fa; padding: 15px; border-radius: 12px; border: 2px solid #e0e0e0; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='#ff9800'" 
                               onmouseout="this.style.borderColor='#e0e0e0'">
                            <input type="radio" name="promo_type" value="discount" style="margin-right: 8px;">
                            <i class="fas fa-tag" style="color: #ff9800; font-size: 20px;"></i>
                            <strong style="margin-left: 8px;">Скидка на товары</strong>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">% скидки на любой товар</p>
                        </label>
                    </div>
                </div>
                
                <!-- Код промокода -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        🔑 Код промокода
                    </label>
                    <div style="display: flex; gap: 10px;">
                        <input id="promo_code_input" class="swal2-input" 
                               value="SALE${Math.random().toString(36).substr(2, 6).toUpperCase()}" 
                               style="flex: 1; margin: 0; font-family: monospace; text-transform: uppercase;">
                        <button type="button" id="generate_code_btn" 
                                style="background: #667eea; color: white; border: none; padding: 0 20px; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Значение промокода (динамическое) -->
                <div id="promo_value_container" style="margin-bottom: 20px;">
                    <label id="promo_value_label" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        💰 Сумма начисления
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input id="promo_value_input" class="swal2-input" type="number" value="100" min="1" step="0.01" style="flex: 1; margin: 0;">
                        <span id="promo_value_suffix" style="font-size: 18px; font-weight: bold; color: #4CAF50;">₽</span>
                    </div>
                </div>
                
                <!-- Минимальная сумма заказа (для скидки) -->
                <div id="min_order_container" style="margin-bottom: 20px; display: none;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        🛒 Минимальная сумма заказа
                    </label>
                    <input id="min_order_input" class="swal2-input" type="number" value="0" min="0" step="0.01" style="width: 100%; margin: 0;">
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">0 = без ограничений</p>
                </div>
                
                <!-- Максимальная скидка (для %) -->
                <div id="max_discount_container" style="margin-bottom: 20px; display: none;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        💎 Максимальная сумма скидки
                    </label>
                    <input id="max_discount_input" class="swal2-input" type="number" value="1000" min="0" step="0.01" style="width: 100%; margin: 0;">
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">0 = без ограничений</p>
                </div>
                
                <!-- Ограничения -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                            🔢 Макс. использований
                        </label>
                        <input id="max_uses_input" class="swal2-input" type="number" value="1" min="1" style="width: 100%; margin: 0;">
                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">0 = без лимита</p>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                            👤 На одного пользователя
                        </label>
                        <input id="per_user_limit_input" class="swal2-input" type="number" value="1" min="1" style="width: 100%; margin: 0;">
                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">0 = без лимита</p>
                    </div>
                </div>
                
                <!-- Срок действия -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        ⏰ Срок действия
                    </label>
                    <div style="display: flex; gap: 10px;">
                        <select id="expires_type" class="swal2-input" style="width: 140px; margin: 0;">
                            <option value="never">Бессрочно</option>
                            <option value="1">1 день</option>
                            <option value="3">3 дня</option>
                            <option value="7" selected>7 дней</option>
                            <option value="14">14 дней</option>
                            <option value="30">30 дней</option>
                        </select>
                        <input id="expires_custom" class="swal2-input" type="datetime-local" style="flex: 1; margin: 0; display: none;">
                    </div>
                </div>
                
                <!-- Описание -->
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        📝 Описание (для пользователя)
                    </label>
                    <textarea id="promo_description_input" class="swal2-textarea" 
                        placeholder="Например: Скидка 10% на первый заказ" 
                        style="min-height: 80px; width: 100%;"></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Создать промокод',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        width: '700px',
        background: 'white',
        didOpen: () => {
            // Генерация кода
            document.getElementById('generate_code_btn').onclick = function() {
                const types = ['SALE', 'BONUS', 'GIFT', 'PROMO', 'HAPPY', 'VIP'];
                const type = types[Math.floor(Math.random() * types.length)];
                const num = Math.floor(Math.random() * 10000);
                document.getElementById('promo_code_input').value = type + num;
            };
            
            // Переключение типа промокода
            document.querySelectorAll('input[name="promo_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const isDiscount = this.value === 'discount';
                    
                    // Меняем label
                    const label = document.getElementById('promo_value_label');
                    const suffix = document.getElementById('promo_value_suffix');
                    const input = document.getElementById('promo_value_input');
                    
                    if (isDiscount) {
                        label.innerHTML = '🏷️ Процент скидки';
                        suffix.innerHTML = '%';
                        input.value = '10';
                        input.min = '1';
                        input.max = '100';
                        input.step = '1';
                        document.getElementById('min_order_container').style.display = 'block';
                        document.getElementById('max_discount_container').style.display = 'block';
                    } else {
                        label.innerHTML = '💰 Сумма начисления';
                        suffix.innerHTML = '₽';
                        input.value = '100';
                        input.min = '1';
                        input.max = '';
                        input.step = '0.01';
                        document.getElementById('min_order_container').style.display = 'none';
                        document.getElementById('max_discount_container').style.display = 'none';
                    }
                });
            });
            
            // Переключение срока действия
            document.getElementById('expires_type').addEventListener('change', function() {
                const custom = document.getElementById('expires_custom');
                if (this.value === 'custom') {
                    custom.style.display = 'block';
                } else {
                    custom.style.display = 'none';
                }
            });
        },
        preConfirm: () => {
            const type = document.querySelector('input[name="promo_type"]:checked').value;
            const code = document.getElementById('promo_code_input').value.trim().toUpperCase();
            const value = parseFloat(document.getElementById('promo_value_input').value);
            const min_order = parseFloat(document.getElementById('min_order_input')?.value || 0);
            const max_discount = parseFloat(document.getElementById('max_discount_input')?.value || 0);
            const max_uses = parseInt(document.getElementById('max_uses_input').value) || 0;
            const per_user_limit = parseInt(document.getElementById('per_user_limit_input').value) || 0;
            const description = document.getElementById('promo_description_input').value.trim();
            
            // Срок действия
            let expires = null;
            const expiresType = document.getElementById('expires_type').value;
            if (expiresType === 'custom') {
                expires = document.getElementById('expires_custom').value;
            } else if (expiresType !== 'never') {
                const days = parseInt(expiresType);
                const date = new Date();
                date.setDate(date.getDate() + days);
                expires = date.toISOString().slice(0, 16).replace('T', ' ');
            }
            
            if (!code) {
                Swal.showValidationMessage('Введите код промокода');
                return false;
            }
            if (!value || value <= 0) {
                Swal.showValidationMessage('Введите корректное значение');
                return false;
            }
            if (type === 'discount' && (value < 1 || value > 100)) {
                Swal.showValidationMessage('Процент скидки должен быть от 1 до 100');
                return false;
            }
            
            return { type, code, value, min_order, max_discount, max_uses, per_user_limit, expires, description };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Создание...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=adm_add_promo',
                type: 'POST',
                data: result.value,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Промокод создан!',
                            html: `
                                <div style="text-align: center; padding: 10px;">
                                    <div style="background: linear-gradient(135deg, #4CAF50, #45a049); 
                                        color: white; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                        <strong style="font-size: 24px; letter-spacing: 2px;">${result.value.code}</strong>
                                    </div>
                                    <p style="color: #666;">
                                        ${result.value.type === 'balance' 
                                            ? `💰 Начисление: +${result.value.value} ₽` 
                                            : `🏷️ Скидка: ${result.value.value}%`}
                                    </p>
                                </div>
                            `,
                            confirmButtonText: 'ОК'
                        });
                        loadAdminPromos();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось создать промокод'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}
function deletePromo(promoId) {
    if (confirm('Удалить этот промокод? Все его использования будут отменены.')) {
        $.post('api.php?action=adm_delete_promo', {promo_id: promoId}, function(response) {
            if (response.status === 'success') {
                showToast('Промокод удален!');
                loadAdminPromos();
            } else {
                 showToast('Ошибка: ' + response.message);
            }
        });
    }
}

function togglePromoStatus(promoId) {
    $.post('api.php?action=adm_toggle_promo', {promo_id: promoId}, function(response) {
        if (response.status === 'success') {
            showToast('Статус промокода изменен!');
            loadAdminPromos();
        } else {
            showToast('Ошибка: ' + response.message);
        }
    });
}

function copyPromoCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        showToast('Код промокода скопирован: ' + code);
    }).catch(err => {
        // Fallback для старых браузеров
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Код промокода скопирован: ' + code);
    });
}
function loadMyTickets() {
    $.get('api.php?action=get_my_tickets', function(response) {
        if (response.status === 'success') {
            let html = '';
            
            response.tickets.forEach(ticket => {
                let statusClass = getStatusClass(ticket.status);
                let statusText = getStatusText(ticket.status);
                
                html += `
                    <div class="ticket-card">
                        <div class="ticket-header">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 5px 0;">${ticket.subject}</h4>
                                <div style="display: flex; gap: 10px; font-size: 12px; color: #666;">
                                    <span><i class="fas fa-hashtag"></i> #${ticket.id}</span>
                                    <span><i class="fas fa-calendar"></i> ${formatDate(ticket.created_at)}</span>
                                    <span><i class="fas fa-sync-alt"></i> ${formatDate(ticket.updated_at)}</span>
                                </div>
                            </div>
                            <span class="badge ${statusClass}">${statusText}</span>
                        </div>
                        <p style="margin-bottom: 15px;">${ticket.message}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                            <div style="color: #666; font-size: 14px;">
                                <i class="fas fa-comments"></i> ${ticket.message_count || 0} сообщений
                            </div>
                            <button class="btn btn-primary" onclick="showTicketMessages(${ticket.id}, '${ticket.subject.replace(/'/g, "\\'")}')">
                                <i class="fas fa-comments"></i> ${ticket.status === 'waiting' ? 'Ответить' : 'Открыть'}
                            </button>
                        </div>
                    </div>
                `;
            });
            
            $('#my-tickets-list').html(html || '<p style="text-align:center; color:#666;">Тикетов нет</p>');
        }
    });
}


function loadAdminTickets() {
    console.log('📋 Загрузка тикетов в админку...');
    
    $('#tickets-tab').html(`
        <div style="text-align: center; padding: 60px;">
            <div class="loader" style="margin: 0 auto 20px;"></div>
            <p style="color: #666;">Загрузка тикетов...</p>
        </div>
    `);
    
    $.ajax({
        url: 'api.php?action=adm_tickets',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('✅ Тикеты загружены:', response);
            
            if (response.status === 'success') {
                if (response.tickets && response.tickets.length > 0) {
                    renderAdminTickets(response.tickets);
                } else {
                    $('#tickets-tab').html(`
                        <div style="text-align: center; padding: 80px 20px;">
                            <i class="fas fa-ticket-alt" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                            <h3 style="color: #666;">Тикетов нет</h3>
                            <p style="color: #999;">Пользователи еще не создавали тикеты</p>
                        </div>
                    `);
                }
            } else {
                $('#tickets-tab').html(`
                    <div style="color: #f44336; padding: 20px;">
                        ❌ Ошибка: ${response.message}
                    </div>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Ошибка:', error);
            $('#tickets-tab').html(`
                <div style="color: #f44336; padding: 20px;">
                    ❌ Ошибка соединения: ${error}<br>
                    <button onclick="loadAdminTickets()" style="margin-top: 10px; padding: 8px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Повторить
                    </button>
                </div>
            `);
        }
    });
}

function adminViewTicket(ticketId, subject) {
    console.log('👨‍💼 Админ открывает тикет #' + ticketId);
    
    if (!ticketId) {
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'ID тикета не указан'
        });
        return;
    }
    
    Swal.fire({
        title: '⏳ Загрузка...',
        text: 'Получаем сообщения',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=get_ticket_messages',
        type: 'GET',
        data: { ticket_id: ticketId },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            console.log('✅ Сообщения:', response);
            
            if (response.status === 'success') {
                let messages = response.messages || [];
                let messagesHtml = '<div style="max-height: 400px; overflow-y: auto; padding: 10px; text-align: left;">';
                
                messages.forEach(msg => {
                    let isAdmin = msg.role === 'admin';
                    let isUser = !isAdmin;
                    let time = new Date(msg.created_at).toLocaleString('ru-RU', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    let avatarColor = isAdmin ? '#9c27b0' : '#667eea';
                    let avatarIcon = isAdmin ? 'fa-crown' : 'fa-user';
                    let senderName = isAdmin ? 'Администратор' : (msg.login || 'Пользователь');
                    
                    messagesHtml += `
                        <div style="margin-bottom: 20px; ${isAdmin ? 'text-align: right;' : 'text-align: left;'}">
                            <div style="display: inline-block; max-width: 80%;">
                                <div style="display: flex; align-items: center; gap: 10px; ${isAdmin ? 'flex-direction: row-reverse;' : ''}">
                                    <div style="width: 40px; height: 40px; background: ${avatarColor}; 
                                                border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                        <i class="fas ${avatarIcon}"></i>
                                    </div>
                                    <div style="background: ${isAdmin ? '#f3e5f5' : '#e3f2fd'}; 
                                                padding: 12px 16px; 
                                                border-radius: ${isAdmin ? '18px 18px 4px 18px' : '18px 18px 18px 4px'}; 
                                                border: 1px solid ${isAdmin ? '#ce93d8' : '#bbdefb'};">
                                        <div style="font-size: 12px; color: #666; margin-bottom: 5px;">
                                            ${senderName} · ${time}
                                        </div>
                                        <div style="word-break: break-word;">${msg.message}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                messagesHtml += '</div>';
                
                // Добавляем поле для ответа
                messagesHtml += `
                    <div style="margin-top: 20px;">
                        <textarea id="admin-reply-${ticketId}" class="swal2-textarea" 
                                  placeholder="Введите ответ..." 
                                  style="width: 100%; min-height: 100px;"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <select id="admin-status-${ticketId}" style="flex: 1; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;">
                            <option value="open">🟢 Открыт</option>
                            <option value="in_progress">🟠 В работе</option>
                            <option value="waiting">🔵 Ожидание</option>
                            <option value="closed">⚪ Закрыт</option>
                        </select>
                    </div>
                `;
                
                Swal.fire({
                    title: 'Тикет #' + ticketId + ': ' + subject,
                    html: messagesHtml,
                    width: '700px',
                    showCancelButton: true,
                    confirmButtonText: '📨 Отправить ответ',
                    cancelButtonText: '❌ Закрыть',
                    confirmButtonColor: '#4CAF50',
                    didOpen: () => {
                        // Устанавливаем текущий статус
                        if (response.current_status) {
                            $('#admin-status-' + ticketId).val(response.current_status);
                        }
                        // Прокрутка вниз
                        setTimeout(() => {
                            let container = document.querySelector('.swal2-html-container div[style*="overflow-y"]');
                            if (container) container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let reply = $('#admin-reply-' + ticketId).val().trim();
                        let newStatus = $('#admin-status-' + ticketId).val();
                        
                        if (reply) {
                            adminReplyToTicket(ticketId, reply, newStatus);
                        } else if (newStatus) {
                            // Если нет ответа, просто обновляем статус
                            updateTicketStatus(ticketId, newStatus);
                        }
                    }
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка',
                    text: response.message || 'Не удалось загрузить сообщения'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Ошибка соединения с сервером'
            });
        }
    });
}

function adminReplyToTicket(ticketId, message, newStatus) {
    Swal.fire({
        title: '⏳ Отправка...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    // Сначала отправляем сообщение
    $.ajax({
        url: 'api.php?action=add_ticket_message',
        type: 'POST',
        data: {
            ticket_id: ticketId,
            message: message
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Если нужно обновить статус
                if (newStatus) {
                    $.ajax({
                        url: 'api.php?action=adm_update_ticket',
                        type: 'POST',
                        data: {
                            ticket_id: ticketId,
                            status: newStatus
                        },
                        dataType: 'json',
                        complete: function() {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: '✅ Готово!',
                                text: 'Ответ отправлен, статус обновлен',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Обновляем список тикетов
                            loadAdminTickets();
                        }
                    });
                } else {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Ответ отправлен!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadAdminTickets();
                }
            } else {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось отправить ответ'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения с сервером'
            });
        }
    });
}


function renderAdminTickets(tickets) {
    let html = `
        <div style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">📋 Управление тикетами (${tickets.length})</h2>
                <div style="display: flex; gap: 10px;">
                    <button onclick="loadAdminTickets()" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Обновить
                    </button>
                </div>
            </div>
            
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">ID</th>
                            <th style="padding: 15px; text-align: left;">Пользователь</th>
                            <th style="padding: 15px; text-align: left;">Тема</th>
                            <th style="padding: 15px; text-align: left;">Статус</th>
                            <th style="padding: 15px; text-align: left;">Сообщений</th>
                            <th style="padding: 15px; text-align: left;">Дата</th>
                            <th style="padding: 15px; text-align: left;">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    tickets.forEach(ticket => {
        // Определяем цвет статуса
        let statusColor = '#6c757d';
        let statusText = 'Неизвестно';
        
        switch(ticket.status) {
            case 'open':
                statusColor = '#4CAF50';
                statusText = '🟢 Открыт';
                break;
            case 'in_progress':
                statusColor = '#ff9800';
                statusText = '🟠 В работе';
                break;
            case 'waiting':
                statusColor = '#2196F3';
                statusText = '🔵 Ожидание';
                break;
            case 'closed':
                statusColor = '#6c757d';
                statusText = '⚪ Закрыт';
                break;
        }
        
        let date = new Date(ticket.created_at).toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        html += `
            <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.2s;" 
                onmouseover="this.style.background='#f8faff';" 
                onmouseout="this.style.background='white';">
                
                <td style="padding: 15px;">
                    <span style="background: #f1f5f9; padding: 5px 10px; border-radius: 8px; font-weight: 700;">
                        #${ticket.id}
                    </span>
                </td>
                
                <td style="padding: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #667eea, #764ba2); 
                                    border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">${ticket.login || 'Пользователь'}</div>
                            <small style="color: #666;">ID: ${ticket.user_id}</small>
                        </div>
                    </div>
                </td>
                
                <td style="padding: 15px; font-weight: 500;">${ticket.subject}</td>
                
                <td style="padding: 15px;">
                    <span style="background: ${statusColor}; color: white; padding: 6px 15px; border-radius: 25px; font-size: 13px; font-weight: 600; display: inline-block;">
                        ${statusText}
                    </span>
                </td>
                
                <td style="padding: 15px; text-align: center;">
                    <span style="background: #f1f5f9; padding: 5px 12px; border-radius: 20px; font-weight: 600;">
                        ${ticket.message_count || 0}
                    </span>
                </td>
                
                <td style="padding: 15px; font-size: 13px; color: #666;">
                    <i class="fas fa-clock"></i> ${date}
                </td>
                
                <td style="padding: 15px;">
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                      <!-- КНОПКА ПРОСМОТРА -->
<button onclick="adminViewTicket(${ticket.id}, '${ticket.subject.replace(/'/g, "\\'")}')" 
        style="background: #2196F3; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
    <i class="fas fa-eye"></i> Просмотр
</button>
                        
                        <!-- КНОПКА ВЗЯТЬ В РАБОТУ -->
                        ${ticket.status !== 'in_progress' ? `
                        <button onclick="updateTicketStatus(${ticket.id}, 'in_progress')" 
                                style="background: #ff9800; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fas fa-play"></i> В работу
                        </button>
                        ` : ''}
                        
                        <!-- КНОПКА ОЖИДАНИЕ -->
                        ${ticket.status !== 'waiting' ? `
                        <button onclick="updateTicketStatus(${ticket.id}, 'waiting')" 
                                style="background: #2196F3; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fas fa-clock"></i> Ожидание
                        </button>
                        ` : ''}
                        
                        <!-- КНОПКА ЗАКРЫТЬ -->
                        ${ticket.status !== 'closed' ? `
                        <button onclick="updateTicketStatus(${ticket.id}, 'closed')" 
                                style="background: #f44336; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fas fa-times"></i> Закрыть
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    });
    
    html += `</tbody></table></div></div>`;
    $('#tickets-tab').html(html);
}

function updateTicketStatus(ticketId, newStatus) {
    console.log('🔄 Обновление статуса тикета #' + ticketId + ' -> ' + newStatus);
    
    let statusText = {
        'open': 'Открыт',
        'in_progress': 'В работе',
        'waiting': 'Ожидание',
        'closed': 'Закрыт'
    };
    
    Swal.fire({
        title: 'Изменение статуса',
        text: `Перевести тикет в статус "${statusText[newStatus]}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Обновление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=adm_update_ticket',
                type: 'POST',
                data: {
                    ticket_id: ticketId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Статус обновлен!',
                            text: `Тикет переведен в статус "${statusText[newStatus]}"`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Обновляем список тикетов
                        loadAdminTickets();
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось обновить статус'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}



function renderAdminAppeals(appeals) {
    if (!appeals || appeals.length === 0) {
        $('#appeals-tab').html(`
            <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <div style="font-size: 80px; color: #ffe0b2; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="color: #666; margin-bottom: 15px;">Апелляций нет</h3>
                <p style="color: #999;">Нет ни одной апелляции от забаненных пользователей.</p>
            </div>
        `);
        return;
    }
    
    let html = `
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333;">
                <i class="fas fa-gavel" style="color: #ff9800;"></i> 
                Апелляции забаненных (${appeals.length})
            </h3>
            <button class="btn" style="background: #4CAF50; color: white;" onclick="loadAdminAppeals()">
                <i class="fas fa-sync-alt"></i> Обновить
            </button>
        </div>
        
        <div style="max-height: 70vh; overflow-y: auto; padding-right: 5px;">
    `;
    
    appeals.forEach(appeal => {
        let statusColor = '#ff9800';
        let statusText = 'Новая';
        if (appeal.status === 'in_progress') { statusColor = '#2196F3'; statusText = 'В работе'; }
        if (appeal.status === 'closed') { statusColor = '#6c757d'; statusText = 'Закрыта'; }
        
        let date = appeal.created_at ? new Date(appeal.created_at).toLocaleString('ru-RU') : 'Нет даты';
        let banReason = appeal.ban_reason || 'Не указана';
        
        html += `
            <div class="appeal-card" data-id="${appeal.id}" style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; 
                        box-shadow: 0 5px 15px rgba(0,0,0,0.08); border-left: 6px solid ${statusColor};">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; flex-wrap: wrap;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <span style="background: #ff4757; color: white; padding: 5px 12px; border-radius: 20px; font-weight: bold;">
                                <i class="fas fa-ban"></i> ID: ${appeal.user_id || '?'}
                            </span>
                            <span style="background: #f8f9fa; color: #333; padding: 5px 12px; border-radius: 20px;">
                                <i class="fas fa-hashtag"></i> Тикет #${appeal.ticket_id || appeal.id}
                            </span>
                            <span style="background: ${statusColor}; color: white; padding: 5px 12px; border-radius: 20px;">
                                ${statusText}
                            </span>
                        </div>
                        <div style="margin-top: 10px; color: #666; font-size: 13px;">
                            <i class="fas fa-calendar"></i> ${date}
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <button class="btn" style="background: #2196F3; color: white; padding: 8px 15px;" 
                                onclick="openTicketMessages(${appeal.ticket_id})">
                            <i class="fas fa-eye"></i> Открыть
                        </button>
                        <button class="btn" style="background: #4CAF50; color: white; padding: 8px 15px;" 
                                onclick="unbanFromAppeal(${appeal.user_id}, ${appeal.ticket_id})">
                            <i class="fas fa-check"></i> Разбанить
                        </button>
                        <button class="btn" style="background: #ff9800; color: white; padding: 8px 15px;" 
                                onclick="markAppealInProgress(${appeal.id})">
                            <i class="fas fa-play"></i> В работу
                        </button>
                    </div>
                </div>
                
                <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <strong style="color: #e65100;">📌 Причина бана:</strong> ${banReason}
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                    <strong>📝 Сообщение:</strong><br>
                    <p style="margin: 10px 0 0 0; color: #333; white-space: pre-wrap;">${appeal.message || 'Нет текста'}</p>
                </div>
                
                ${appeal.email ? `
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                    <i class="fas fa-envelope" style="color: #666;"></i> 
                    <span style="color: #2196F3;">${appeal.email}</span>
                </div>
                ` : ''}
            </div>
        `;
    });
    
    html += `</div>`;
    $('#appeals-tab').html(html);
}

function updateAppealsCount(count) {
    $('#appeals-count').text(count).css('display', count > 0 ? 'inline-block' : 'none');
}

function unbanFromAppeal(userId, ticketId) {
    if (!confirm('Разблокировать пользователя и закрыть апелляцию?')) return;
    
    $.post('api.php?action=adm_unban', { user_id: userId }, function(response) {
        if (response.status === 'success') {
            $.post('api.php?action=adm_update_ticket', {
                ticket_id: ticketId,
                status: 'closed',
                comment: 'Пользователь разблокирован по апелляции'
            }, function() {
                showToast('✅ Пользователь разблокирован!', 'success');
                loadAdminAppeals();
                loadAdminUsers();
            });
        } else {
            showToast('❌ Ошибка: ' + response.message, 'error');
        }
    });
}

function markAppealInProgress(appealId) {
    $.post('api.php?action=adm_update_appeal', {
        appeal_id: appealId,
        status: 'in_progress'
    }, function(response) {
        if (response.status === 'success') {
            showToast('Статус изменен на "В работе"', 'info');
            loadAdminAppeals();
        }
    });
}

function openTicketMessages(ticketId) {
    if (typeof showTicketMessages === 'function') {
        showTicketMessages(ticketId, 'Апелляция #' + ticketId);
    } else {
        window.location.href = 'tickets.php?id=' + ticketId;
    }
}

// Функции фильтрации
let currentStatusFilter = 'all';

function setStatusFilter(status) {
    currentStatusFilter = status;
    filterTicketsTable();
}

function getStatusFilter() {
    return currentStatusFilter;
}

function filterTicketsTable() {
    let searchText = $('#ticket-search').val().toLowerCase();
    
    $('.ticket-row').each(function() {
        let status = $(this).data('status');
        let user = $(this).data('user') || '';
        let subject = $(this).data('subject') || '';
        let id = $(this).data('id') || '';
        let show = true;
        
        // Фильтр по статусу
        if (currentStatusFilter !== 'all' && status !== currentStatusFilter) {
            show = false;
        }
        
        // Фильтр по поиску
        if (searchText && !user.includes(searchText) && !subject.includes(searchText) && 
            !id.toString().includes(searchText)) {
            show = false;
        }
        
        if (show) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function escapeHtml(text) {
    return text.replace(/['"\\]/g, '\\$&').replace(/\n/g, '\\n');
}

// Вспомогательные функции для статусов
function getStatusClass(status) {
    const classes = {
        'open': 'badge-success',
        'in_progress': 'badge-primary',
        'waiting': 'badge-warning',
        'closed': 'badge-danger',
        'resolved': 'badge-info'
    };
    return classes[status] || 'badge-secondary';
}

function getStatusText(status) {
    const texts = {
        'open': 'Открыт',
        'in_progress': 'В работе',
        'waiting': 'Ожидание',
        'closed': 'Закрыт',
        'resolved': 'Решен'
    };
    return texts[status] || status;
}

// Быстрое обновление статуса
function quickUpdateTicket(ticketId, status) {
    $.post('api.php?action=adm_update_ticket', {
        ticket_id: ticketId,
        status: status
    }, function(response) {
        if (response.status === 'success') {
            showToast('Статус обновлен!');
            loadAdminTickets();
        } else {
 showToast('Ошибка: ' + response.message);
        }
    });
}

// Фильтрация тикетов
function filterTickets(status) {
    if (status === 'all') {
        $('.ticket-row').show();
    } else {
        $('.ticket-row').hide();
        $(`.ticket-row[data-status="${status}"]`).show();
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    let date = new Date(dateString);
    return date.toLocaleDateString('ru-RU');
}

        function loadAdminLogs() {
            $.get('api.php?action=adm_logs', function(response) {
                if (response.status === 'success') {
                    let html = `
                        <div class="table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Пользователь</th>
                                        <th>Действие</th>
                                        <th>IP</th>
                                        <th>Детали</th>
                                        <th>Время</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    response.logs.forEach(log => {
                        html += `
                            <tr>
                                <td>${log.id}</td>
                                <td>${log.user_login || 'Система'}</td>
                                <td>${log.action}</td>
                                <td>${log.ip}</td>
                                <td>${log.details || '-'}</td>
                                <td>${log.created_at}</td>
                            </tr>
                        `;
                    });
                    
                    html += `</tbody></table></div>`;
                    $('#logs-tab').html(html);
                }
            });
        }




function renderAdminAppeals(appeals) {
    if (!appeals || appeals.length === 0) {
        $('#appeals-tab').html(`
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <i class="fas fa-check-circle" style="font-size: 60px; color: #4CAF50; margin-bottom: 20px;"></i>
                <h3>Апелляций нет</h3>
                <p>Нет новых апелляций от забаненных пользователей</p>
            </div>
        `);
        return;
    }
    
    let html = '<div style="margin-bottom:20px;"><h3>Апелляции (' + appeals.length + ')</h3></div>';
    
    appeals.forEach(appeal => {
        let status = appeal.status || 'new';
        let statusColor = status === 'new' ? '#ff9800' : (status === 'in_progress' ? '#2196F3' : '#6c757d');
        let statusText = status === 'new' ? 'Новая' : (status === 'in_progress' ? 'В работе' : 'Закрыта');
        
        html += `
            <div style="background:white; border-radius:15px; padding:20px; margin-bottom:20px; box-shadow:0 5px 15px rgba(0,0,0,0.08); border-left:6px solid ${statusColor};">
                <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <span style="background:#ff4757; color:white; padding:5px 12px; border-radius:20px; font-weight:bold;">
                            ID: ${appeal.user_id}
                        </span>
                        <span style="background:#f8f9fa; color:#333; padding:5px 12px; border-radius:20px; margin-left:10px;">
                            Тикет #${appeal.ticket_id}
                        </span>
                        <span style="background:${statusColor}; color:white; padding:5px 12px; border-radius:20px; margin-left:10px;">
                            ${statusText}
                        </span>
                    </div>
                    <div>
                        <button class="btn" style="background:#4CAF50; color:white; padding:8px 15px;" onclick="adminUnbanUser(${appeal.user_id}, ${appeal.ticket_id})">
                            <i class="fas fa-check"></i> Разбанить
                        </button>
                        <button class="btn" style="background:#2196F3; color:white; padding:8px 15px;" onclick="adminOpenTicket(${appeal.ticket_id})">
                            <i class="fas fa-eye"></i> Открыть
                        </button>
                    </div>
                </div>
                <div style="background:#fff3e0; padding:15px; border-radius:10px; margin-bottom:15px;">
                    <strong>Причина бана:</strong> ${appeal.ban_reason || 'Не указана'}
                </div>
                <div style="background:#f8f9fa; padding:15px; border-radius:10px;">
                    <strong>Сообщение:</strong>
                    <p style="margin:10px 0 0 0;">${appeal.message || 'Нет текста'}</p>
                </div>
                <div style="margin-top:10px; font-size:12px; color:#666;">
                    ${appeal.created_at ? new Date(appeal.created_at).toLocaleString() : ''}
                </div>
            </div>
        `;
    });
    
    $('#appeals-tab').html(html);
}


function renderAdminAppeals(appeals) {
    if (!appeals || appeals.length === 0) {
        $('#appeals-tab').html(`
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <i class="fas fa-check-circle" style="font-size: 60px; color: #4CAF50; margin-bottom: 20px;"></i>
                <h3>Апелляций нет</h3>
                <p>Нет новых апелляций от забаненных пользователей</p>
            </div>
        `);
        return;
    }
    
    let html = '<div style="margin-bottom:20px;"><h3>Апелляции (' + appeals.length + ')</h3></div>';
    
    appeals.forEach(appeal => {
        let status = appeal.status || 'new';
        let statusColor = status === 'new' ? '#ff9800' : (status === 'in_progress' ? '#2196F3' : '#6c757d');
        let statusText = status === 'new' ? 'Новая' : (status === 'in_progress' ? 'В работе' : 'Закрыта');
        
        html += `
            <div style="background:white; border-radius:15px; padding:20px; margin-bottom:20px; box-shadow:0 5px 15px rgba(0,0,0,0.08); border-left:6px solid ${statusColor};">
                <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <span style="background:#ff4757; color:white; padding:5px 12px; border-radius:20px; font-weight:bold;">
                            ID: ${appeal.user_id}
                        </span>
                        <span style="background:#f8f9fa; color:#333; padding:5px 12px; border-radius:20px; margin-left:10px;">
                            Тикет #${appeal.ticket_id}
                        </span>
                        <span style="background:${statusColor}; color:white; padding:5px 12px; border-radius:20px; margin-left:10px;">
                            ${statusText}
                        </span>
                    </div>
                    <div>
                        <button class="btn" style="background:#4CAF50; color:white; padding:8px 15px;" onclick="unbanFromAppeal(${appeal.user_id}, ${appeal.ticket_id})">
                            <i class="fas fa-check"></i> Разбанить
                        </button>
                        <button class="btn" style="background:#2196F3; color:white; padding:8px 15px;" onclick="openTicketMessages(${appeal.ticket_id})">
                            <i class="fas fa-eye"></i> Открыть
                        </button>
                    </div>
                </div>
                <div style="background:#fff3e0; padding:15px; border-radius:10px; margin-bottom:15px;">
                    <strong>Причина бана:</strong> ${appeal.ban_reason || 'Не указана'}
                </div>
                <div style="background:#f8f9fa; padding:15px; border-radius:10px;">
                    <strong>Сообщение:</strong>
                    <p style="margin:10px 0 0 0;">${appeal.message || 'Нет текста'}</p>
                </div>
            </div>
        `;
    });
    
    $('#appeals-tab').html(html);
}

function unbanFromAppeal(userId, ticketId) {
    if (!confirm('Разблокировать пользователя и закрыть апелляцию?')) return;
    
    $.post('api.php?action=adm_unban', { user_id: userId }, function(response) {
        if (response.status === 'success') {
            showToast('✅ Пользователь разблокирован!', 'success');
            loadAdminAppeals();
            loadAdminUsers();
        } else {
            showToast('❌ Ошибка: ' + response.message, 'error');
        }
    });
}

function openTicketMessages(ticketId) {
    if (typeof showTicketMessages === 'function') {
        showTicketMessages(ticketId, 'Апелляция #' + ticketId);
    } else {
        window.location.href = 'tickets.php?id=' + ticketId;
    }
}

// ========== ФУНКЦИЯ ДЛЯ ПОЛЬЗОВАТЕЛЯ ==========
function createBanAppealDirect() {
    let userId = currentUser?.id || 0;
    
    if (!userId) {
        Swal.fire({
            title: 'Ваш ID',
            input: 'number',
            inputLabel: 'Введите ваш ID',
            showCancelButton: true,
            confirmButtonText: 'Отправить',
            inputValidator: (value) => !value || value <= 0 ? 'Введите ID' : null
        }).then((result) => {
            if (result.isConfirmed) sendAppealToServer(result.value);
        });
    } else {
        sendAppealToServer(userId);
    }
}

function sendAppealToServer(userId) {
    Swal.fire({ title: 'Отправка...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    
    $.post('api.php?action=create_ban_appeal', { user_id: userId }, function(response) {
        Swal.close();
        if (response.status === 'success') {
            Swal.fire('✅ Успешно!', 'Апелляция отправлена', 'success');
        } else {
            Swal.fire('❌ Ошибка', response.message || 'Не удалось отправить', 'error');
        }
    }).fail(() => {
        Swal.close();
        Swal.fire('❌ Ошибка', 'Соединение потеряно', 'error');
    });
}

        function loadAdminProducts() {
    $.get('api.php?action=adm_products', function(response) {
        if (response.status === 'success') {
            let html = `
                <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; color: #333;">Управление товарами (${response.products.length})</h3>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn btn-primary" onclick="showAddProductModal()">
                            <i class="fas fa-plus"></i> Добавить товар
                        </button>
                        <button class="btn" style="background: #4CAF50; color: white;" onclick="loadAdminProducts()">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                    </div>
                </div>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="100">Изображение</th>
                                <th>Название</th>
                                <th width="120">Продавец</th>
                                <th width="100">Цена</th>
                                <th width="80">Скидка</th>
                                <th width="80">Кол-во</th>
                                <th width="100">Статус</th>
                                <th width="150">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            response.products.forEach(product => {
                let status = product.status === 'active' ? 
                    '<span class="badge badge-success">Активен</span>' : 
                    '<span class="badge badge-danger">Удален</span>';
                
                let priceHtml = '';
                if (product.discount > 0) {
                    priceHtml = `
                        <div>
                            <div style="text-decoration: line-through; color: #999; font-size: 12px;">
                                ${product.price} ₽
                            </div>
                            <div style="color: #e91e63; font-weight: bold;">
                                ${product.final_price || product.price} ₽
                            </div>
                        </div>
                    `;
                } else {
                    priceHtml = `<div style="color: #4CAF50; font-weight: bold;">${product.price} ₽</div>`;
                }
                
                html += `
                    <tr>
                        <td><strong>${product.id}</strong></td>
                        <td>
                            ${product.main_image ? 
                                `<img src="${product.main_image}" alt="${product.title}" 
                                      style="width:60px;height:60px;object-fit:cover;border-radius:8px;">` : 
                                `<div style="width:60px;height:60px;background:#f5f7fa;border-radius:8px;
                                      display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-box" style="color:#667eea;"></i>
                                </div>`
                            }
                        </td>
                        <td>
                            <div style="font-weight:500; margin-bottom:5px;">${product.title}</div>
                            <div style="font-size:12px; color:#666;">
                                ${product.description ? product.description.substring(0, 80) + (product.description.length > 80 ? '...' : '') : 'Нет описания'}
                            </div>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:32px;height:32px;background:#667eea;border-radius:50%;
                                      display:flex;align-items:center;justify-content:center;color:white;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div style="font-weight:500;">${product.seller_login}</div>
                                    <small style="color:#666; font-size:11px;">ID: ${product.seller_id}</small>
                                </div>
                            </div>
                        </td>
                        <td>${priceHtml}</td>
                        <td style="text-align:center;">
                            ${product.discount > 0 ? 
                                `<span style="background:#ff4757;color:white;padding:3px 8px;border-radius:12px;font-size:12px;">
                                    -${product.discount}%
                                </span>` : 
                                '<span style="color:#999;">—</span>'
                            }
                        </td>
                        <td style="text-align:center;">
                            <div style="background:#f8f9fa;padding:5px 10px;border-radius:5px;font-weight:bold;">
                                ${product.stock}
                            </div>
                        </td>
                        <td>${status}</td>
                        <td>
                            <div style="display:flex; gap:5px; flex-wrap:wrap;">
                                <button class="btn btn-primary" style="padding:5px 8px; font-size:12px;" 
                                        onclick="editProduct(${product.id})" title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info" style="padding:5px 8px; font-size:12px;" 
                                        onclick="viewProduct(${product.id})" title="Просмотреть">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${product.status === 'active' ? 
                                    // Сделайте:
`<button class="btn btn-danger" onclick="deleteProductFromAdmin(${product.id}, '${escapeHtml(product.title)}')" 
        style="padding:5px 8px; font-size:12px;" title="Удалить товар">
    <i class="fas fa-trash"></i>
</button>` : 
                                    `<button class="btn btn-success" style="padding:5px 8px; font-size:12px;" 
                                            onclick="restoreProduct(${product.id})" title="Восстановить">
                                        <i class="fas fa-undo"></i>
                                    </button>`
                                }
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            html += `</tbody></table></div>`;
            $('#products-tab').html(html);
        } else {
            $('#products-tab').html(`
                <div style="text-align: center; padding: 60px 20px; color: #666;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: #ff9800; margin-bottom: 20px;"></i>
                    <h3 style="color: #666; margin-bottom: 10px;">Ошибка загрузки товаров</h3>
                    <p>${response.message || 'Неизвестная ошибка'}</p>
                    <button class="btn btn-primary" onclick="loadAdminProducts()" style="margin-top: 20px;">
                        <i class="fas fa-sync-alt"></i> Повторить
                    </button>
                </div>
            `);
        }
    }).fail(function(error) {
        console.error('Error loading products:', error);
        $('#products-tab').html(`
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: #f44336; margin-bottom: 20px;"></i>
                <h3 style="color: #666; margin-bottom: 10px;">Ошибка соединения</h3>
                <p>Не удалось загрузить список товаров</p>
                <button class="btn btn-primary" onclick="loadAdminProducts()" style="margin-top: 20px;">
                    <i class="fas fa-sync-alt"></i> Повторить
                </button>
            </div>
        `);
    });
}



function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
     


      function register() {
    let formData = $('#register-form').serialize();
    
    $.post('api.php?action=register', formData, function(response) {
        if (response.status === 'success') {
            currentUser = response.user;
            closeModal('register-modal');
            showToast('Регистрация успешна!');
            
            // Проверяем, был ли выбран флажок "Запомнить меня"
            if ($('#register-form input[name="remember"]').is(':checked')) {
                showToast('Вы останетесь в системе даже после закрытия браузера');
            }
            
            // ★ ДОБАВЬТЕ ЭТОТ КОД ★
            // Показываем приветственное модальное окно
            Swal.fire({
                title: '🎉 Добро пожаловать!',
                html: `
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 60px; color: #4CAF50; margin-bottom: 15px;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3>Привет, <strong>${response.user.username || response.user.login}</strong>!</h3>
                        <p style="margin: 15px 0; color: #666;">
                            Ваш аккаунт успешно создан. <br>
                            Теперь вы можете пользоваться всеми возможностями маркетплейса.
                        </p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0;">
                            <p style="margin: 5px 0;">
                                <i class="fas fa-coins" style="color: #FFD700;"></i> 
                                Стартовый баланс: <strong>${response.user.balance} ₽</strong>
                            </p>
                        </div>
                        <p style="color: #888; font-size: 14px;">
                            Удачных покупок! 🛍️
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Начать покупки!',
                confirmButtonColor: '#4CAF50',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                // После закрытия приветствия обновляем страницу
                location.reload();
            });
            
        } else {
            showToast(response.message, response.toast_type || 'info');
        }
    });
}
        function logout() {
            $.get('api.php?action=logout', function() {
                currentUser = null;
                location.reload();
            });
        }

       function verifyPin() {
    let pin = '';
    $('.pin-digit').each(function() {
        pin += $(this).val();
    });
    
    if (pin.length !== 4) {
        $('#pin-error').show();
        return;
    }
    
    $.post('api.php?action=verify_pin', {pin: pin}, function(response) {
        if (response.status === 'success') {
            closeModal('pin-modal');
            showToast('PIN подтвержден!');
            
            // После подтверждения PIN обновляем страницу
            location.reload();
        } else {
            $('#pin-error').show().text(response.message || 'Неверный PIN-код');
        }
    });
}

    
function addProduct() {
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    let formData = new FormData($('#add-product-form')[0]);
    formData.append('seller_id', currentUser.id);
    
    // Проверяем файлы
    let images = $('#product-images')[0].files;
    if (images.length > 5) {
        showToast('Можно загрузить не более 5 изображений', 'warning');
        return;
    }
    
    // Проверяем размер
    for (let i = 0; i < images.length; i++) {
        if (images[i].size > 5 * 1024 * 1024) {
            showToast('Файл слишком большой (макс. 5MB)', 'error');
            return;
        }
    }
    
    $.ajax({
        url: 'api.php?action=add_product',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                showToast('✅ Товар добавлен!', 'success');
                closeModal('add-product-modal');
                loadProducts();
            } else {
                showToast('❌ ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('❌ Ошибка загрузки', 'error');
        }
    });
}

// Сброс формы товара
function resetProductForm() {
    $('#add-product-form')[0].reset();
    $('#image-preview-container').hide();
    $('#upload-area').show();
    $('#final-price-display').html('0 ₽');
    $('#discount-display').html('Скидка: 0%');
    $('#savings-display').html('Экономия: 0 ₽');
    $('#final-price').val('');
}

function addToCart(productId) {
    console.log('🛒 Добавление в корзину:', productId);
    
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    // Проверяем, не свой ли это товар
    const $productCard = $(`.product-card[data-id="${productId}"]`);
    const sellerLogin = $productCard.find('.seller-login, [class*="seller"]').text();
    
    // Если есть информация о продавце, проверяем
    if (sellerLogin && currentUser.login && sellerLogin.includes(currentUser.login)) {
        Swal.fire({
            icon: 'error',
            title: '❌ Нельзя добавить',
            text: 'Вы не можете добавить свой собственный товар в корзину',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    $.ajax({
        url: 'cart_api.php?action=add_to_cart',
        method: 'POST',
        data: { 
            product_id: productId,
            quantity: 1 
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Товар добавлен!',
                    text: response.message || 'Успешно',
                    timer: 1500,
                    showConfirmButton: false
                });
                loadCartCount();
            } else {
                let errorMsg = response.message || 'Ошибка добавления';
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: errorMsg,
                    confirmButtonColor: '#ff4757'
                });
            }
        },
        error: function(xhr) {
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Не удалось добавить товар в корзину',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}

function activatePromo() {
    const code = $('#promo-code').val().trim();
    
    if (!code) {
        showToast('❌ Введите код промокода', 'warning');
        return;
    }
    
    $.ajax({
        url: 'api.php?action=activate',
        type: 'POST',
        data: { code: code.toUpperCase() },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showToast('✅ +' + response.reward + ' ₽ начислено!', 'success');
                currentUser.balance = response.new_balance;
                $('#user-balance').text(parseFloat(response.new_balance).toFixed(2));
            } else {
                showToast('❌ ' + response.message, 'error');
            }
        }
    });
}


        function addPromo() {
            let formData = $('#add-promo-form').serialize();
            
            $.post('api.php?action=adm_add_promo', formData, function(response) {
                if (response.status === 'success') {
                    closeModal('add-promo-modal');
                    showToast('Промокод добавлен!');
                    loadAdminPromos();
                } else {
                   showToast(response.message, response.toast_type || 'info');
                }
            });
        }

        function changePassword() {
            if (requirePin) {
                showModal('pin-modal');
                return;
            }
            
            let formData = $('#change-password-form').serialize();
            
            $.post('api.php?action=change_password', formData, function(response) {
                if (response.status === 'success') {
                    showToast('Пароль изменен!');
                    closeModal('security-modal');
                } else {
                   showToast(response.message, response.toast_type || 'info');
                }
            });
        }

        function setPin() {
            let formData = $('#set-pin-form').serialize();
            
            $.post('api.php?action=set_pin', formData, function(response) {
                if (response.status === 'success') {
                    showToast('PIN-код установлен!');
                    closeModal('security-modal');
                } else {
                   showToast(response.message, response.toast_type || 'info');
                }
            });
        }

        function sendNotification() {
            let formData = $('#send-notification-form').serialize();
            
            $.post('api.php?action=adm_send_notification', formData, function(response) {
                if (response.status === 'success') {
                    closeModal('send-notification-modal');
                    showToast('Рассылка отправлена!');
                } else {
                  showToast(response.message, response.toast_type || 'info');
                }
            });
        }

        function createTicket() {
            if (requirePin) {
                showModal('pin-modal');
                return;
            }
            
            let formData = $('#create-ticket-form').serialize();
            
            $.post('api.php?action=create_ticket', formData, function(response) {
                if (response.status === 'success') {
                    closeModal('create-ticket-modal');
                    showToast('Тикет создан!');
                    loadMyTickets();
                } else {
                   showToast(response.message, response.toast_type || 'info');
                }
            });
        }

        function showTicketMessages(ticketId, title) {
            $('#ticket-title').text(title);
            $('#current-ticket-id').val(ticketId);
            showModal('ticket-messages-modal');
            loadTicketMessages(ticketId);
        }

       function loadTicketMessages(ticketId) {
    $.get('api.php?action=get_ticket_messages', {ticket_id: ticketId}, function(response) {
        console.log('Ticket messages response:', response);
        
        if (response.status === 'success') {
            let html = '';
            
            response.messages.forEach(message => {
                console.log('Message:', message);
                
                let isSystem = message.is_system == 1 || message.message_type === 'system';
                let isAdmin = message.message_type === 'admin' || message.role === 'admin';
                let isUser = message.message_type === 'user' && !isAdmin && !isSystem;
                
                let alignClass = isAdmin ? 'style="text-align:right;"' : '';
                let bgColor, textColor, borderColor, icon, author;
                
                if (isSystem) {
                    bgColor = '#fff3cd';
                    textColor = '#856404';
                    borderColor = '#ffeaa7';
                    icon = '<i class="fas fa-cog"></i>';
                    author = 'Система';
                } else if (isAdmin) {
                    bgColor = '#667eea';
                    textColor = 'white';
                    borderColor = '#764ba2';
                    icon = '<i class="fas fa-crown"></i>';
                    author = 'Администратор';
                } else {
                    bgColor = '#f1f1f1';
                    textColor = '#333';
                    borderColor = '#ddd';
                    icon = '<i class="fas fa-user"></i>';
                    author = message.login || 'Пользователь';
                }
                
                html += `
                    <div ${alignClass} style="margin-bottom:15px;">
                        <div style="display:inline-block; max-width:70%; min-width: 200px;">
                            <div style="background:${bgColor}; color:${textColor}; padding:10px 15px; border-radius:15px; border-left:4px solid ${borderColor};">
                                ${message.message}
                            </div>
                            <div style="font-size:12px; color:#666; margin-top:5px;">
                                ${icon} ${author} • ${formatDateTime(message.created_at)}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#ticket-messages').html(html);
            
            // Скроллим вниз
            setTimeout(() => {
                $('#ticket-messages').scrollTop($('#ticket-messages')[0].scrollHeight);
            }, 100);
            
            // Показываем панель управления для админа
            if (currentUser && currentUser.role === 'admin') {
                showTicketAdminControls(ticketId, response.current_status);
            }
            
        } else {
            $('#ticket-messages').html(`
                <div style="text-align: center; padding: 20px; color: #666;">
                    <i class="fas fa-exclamation-triangle"></i> ${response.message}
                </div>
            `);
        }
    });
}

function formatDateTime(dateString) {
    if (!dateString) return '';
    let date = new Date(dateString);
    return date.toLocaleString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

        function addTicketMessage() {
            let formData = $('#add-ticket-message-form').serialize();
            formData += '&ticket_id=' + $('#current-ticket-id').val();
            
            $.post('api.php?action=add_ticket_message', formData, function(response) {
                if (response.status === 'success') {
                    $('#add-ticket-message-form textarea').val('');
                    loadTicketMessages($('#current-ticket-id').val());
                } else {
                  showToast(response.message, response.toast_type || 'info');
                }
            });
        }

        function editUserBalance(userId) {
            let amount = prompt('Введите сумму:');
            let operation = prompt('Операция (add - добавить, set - установить):', 'add');
            
            if (amount && operation) {
                $.post('api.php?action=adm_update_balance', {
                    user_id: userId,
                    amount: amount,
                    operation: operation
                }, function(response) {
                    if (response.status === 'success') {
                        showToast('Баланс обновлен!');
                        loadAdminUsers();
                    } else {
                       showToast(response.message, response.toast_type || 'info');
                    }
                });
            }
        }

   

function confirmUnban() {
    let userId = $('#unban-user-id').val();
    
    $.post('api.php?action=adm_unban', {user_id: userId}, function(response) {
        if (response.status === 'success') {
            closeModal('unban-user-modal');
            showToast('Пользователь разбанен!');
            loadAdminUsers();
        } else {
             showToast('Ошибка: ' + response.message);
        }
    });
}
// Простейший вариант с кастомной модалкой
function deleteProductFromAdmin(productId, productTitle = '') {
    console.log('Удаление товара:', productId, productTitle);
    
    // Создаем простую модалку
    const modalOverlay = document.createElement('div');
    modalOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
    `;
    
    modalContent.innerHTML = `
        <h4>Удалить товар?</h4>
        <p>Удалить товар "${productTitle}"?</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button id="cancelBtn" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Отмена</button>
            <button id="deleteBtn" style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Удалить</button>
        </div>
    `;
    
    modalOverlay.appendChild(modalContent);
    document.body.appendChild(modalOverlay);
    
    // Обработчик отмены
    document.getElementById('cancelBtn').addEventListener('click', function() {
        document.body.removeChild(modalOverlay);
    });
    
    // Обработчик удаления
    document.getElementById('deleteBtn').addEventListener('click', function() {
        console.log('Кнопка удаления нажата');
        document.body.removeChild(modalOverlay);
        
        // 1. Показываем сообщение
        showToast('Удаление...', 'info');
        
        // 2. Отправляем запрос на удаление
        $.ajax({
            url: 'api.php?action=adm_delete_product',
            method: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                console.log('Ответ:', response);
                if (response.status === 'success') {
                    showToast('✅ Товар удален!', 'success');
                    
                    // 3. Просто обновляем админ-панель
                    if (typeof loadAdminProducts === 'function') {
                        loadAdminProducts();
                    }
                    
                    // 4. И через 3 секунды обновляем главную страницу
                    setTimeout(function() {
                        if (typeof refreshMainPageProducts === 'function') {
                            refreshMainPageProducts();
                        }
                    }, 3000);
                    
                } else {
                    showToast('❌ Ошибка: ' + response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка:', error);
                showToast('❌ Ошибка сервера', 'error');
            }
        });
    });
    
    // Закрытие при клике на overlay
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            document.body.removeChild(modalOverlay);
        }
    });
}



// Простая функция для обновления товаров на главной странице
function refreshMainPageProducts() {
    // Проверяем, находимся ли мы на главной странице
    if ($('#products-list').length > 0) {
        console.log('🔄 Обновляю товары на главной странице...');
        
        // Просто перезагружаем товары
        $.ajax({
            url: 'api.php?action=get_products',
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    console.log('✅ Главная страница: получено ' + response.products.length + ' товаров');
                    
                    // Если есть товары - перерисовываем
                    if (response.products && response.products.length > 0) {
                        // Просто перезагружаем страницу через 2 секунды
                        setTimeout(function() {
                            // Самый простой способ - перезагрузить товары
                            if (typeof loadProducts === 'function') {
                                loadProducts();
                            } else {
                                // Или просто обновить страницу
                                location.reload();
                            }
                        }, 2000);
                    }
                }
            }
        });
    }
}



// Функция для удаления товара из интерфейса (анимация)
function removeProductFromUI(productId) {
    console.log(`🎬 Анимация удаления товара #${productId}`);
    
    // 1. Удаляем из админской таблицы
    $(`tr[data-product-id="${productId}"]`).fadeOut(300);
    $(`tr:has(td:contains("${productId}"))`).fadeOut(300);
    
    // 2. Удаляем с главной страницы
    $(`.product-card[data-id="${productId}"]`).fadeOut(300, function() {
        $(this).remove();
    });
    
    // 3. Удаляем из каталога
    $(`.product-item[data-id="${productId}"]`).fadeOut(300);
    
    // 4. Удаляем из корзины (если там был)
    $(`.cart-item[data-id="${productId}"]`).fadeOut(300);
}

// Функция для восстановления товара в интерфейсе (при ошибке)
function restoreProductInUI(productId) {
    $(`tr[data-product-id="${productId}"]`).fadeIn(300);
    $(`.product-card[data-id="${productId}"]`).fadeIn(300);
}

// Функция для проверки и обновления главной страницы
function updateMainPageProducts() {
    console.log('🔍 Проверяем главную страницу...');
    
    // Если на главной странице есть список товаров
    if ($('#products-list').length > 0) {
        console.log('📱 Главная страница обнаружена, обновляем...');
        
        // Проверяем, есть ли функция loadProducts
        if (typeof loadProducts === 'function') {
            console.log('🔄 Вызываем loadProducts()...');
            loadProducts();
        } else {
            console.log('⚠️ Функция loadProducts не найдена!');
            
            // Пробуем загрузить товары вручную
            $.get('api.php?action=get_products', function(response) {
                if (response.status === 'success') {
                    console.log('✅ Товары загружены:', response.products.length);
                    // Здесь нужно обновить #products-list
                }
            });
        }
    } else {
        console.log('📱 Главная страница не активна');
    }
}
// Функция для анимации удаления строки
function removeProductRow(productId) {
    // Находим строку с товаром
    const row = $(`tr:has(td:contains("${productId}"))`).first();
    
    if (row.length) {
        // Анимация удаления
        row.css({
            'background': '#fff5f5',
            'transition': 'all 0.5s'
        });
        
        row.animate({
            opacity: 0,
            height: 0,
            paddingTop: 0,
            paddingBottom: 0
        }, 500, function() {
            // Удаляем строку из DOM
            $(this).remove();
            
            // Обновляем счетчик товаров
            updateProductCount();
            
            // Если таблица пустая, показываем сообщение
            if ($('#products-tab .admin-table tbody tr').length === 0) {
                $('#products-tab').html(`
                    <div style="text-align: center; padding: 60px 20px; color: #666;">
                        <i class="fas fa-box-open" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                        <h3 style="color: #666; margin-bottom: 10px;">Товаров нет</h3>
                        <p>Все товары были удалены.</p>
                        <button class="btn btn-primary" onclick="loadAdminProducts()" style="margin-top: 20px;">
                            <i class="fas fa-sync-alt"></i> Обновить
                        </button>
                    </div>
                `);
            }
        });
    } else {
        // Если не нашли строку, перезагружаем всю таблицу
        loadAdminProducts();
    }
}

// Функция обновления счетчика товаров
function updateProductCount() {
    const count = $('#products-tab .admin-table tbody tr').length;
    
    // Обновляем заголовок
    $('h3:contains("Управление товарами")').each(function() {
        const text = $(this).text();
        const newText = text.replace(/\(\d+\)/, `(${count})`);
        $(this).text(newText);
    });
}



        function showNotifications() {
            $.get('api.php?action=get_notifications', function(response) {
                if (response.status === 'success') {
                    let html = '';
                    
                    response.notifications.forEach(notif => {
                        let readClass = notif.is_read ? '' : 'style="background:#f8f9fa;"';
                        html += `
                            <div ${readClass} style="padding:15px; border-bottom:1px solid #eee;">
                                <h4 style="margin:0 0 5px 0;">${notif.title}</h4>
                                <p style="margin:0; color:#666;">${notif.message}</p>
                                <div style="font-size:12px; color:#999; margin-top:5px;">${notif.created_at}</div>
                            </div>
                        `;
                    });
                    
                    $('#notifications-list').html(html || '<p style="text-align:center; color:#666;">Нет уведомлений</p>');
                    showModal('notifications-modal');
                }
            });
        }

     
   
  // ========== ПОКАЗ ПОЛНОЭКРАННОЙ СТРАНИЦЫ БАНА ==========
function showBanModal(banData = null) {
    console.log('🚨 Показ полноэкранной страницы бана', banData);

    // Скрываем все обычные модальные окна
    $('.modal').hide();

    // Показываем футер
    $('footer.footer').show();

    // Получаем данные (работает с любым названием переменной)
    let data = banData || window.banInfo || null;
    
    // Заполняем данные на странице бана
    if (data) {
        // Причина
        let reason = data.reason || data.ban_reason || 'Нарушение правил';
        $('#ban-reason-display').text(reason);
        
        // Дата
        let date = data.created_at || data.ban_date || new Date().toISOString();
        $('#ban-date-display').text(formatDateForBan(date) || new Date().toLocaleString());

        // Срок действия
        if (data.expires || data.ban_expires) {
            let expires = data.expires || data.ban_expires;
            $('#ban-expires-display').text(formatDateForBan(expires));
            if (typeof startBanTimer === 'function') {
                startBanTimer(expires);
            }
            $('#ban-timer-display').show();
        } else {
            $('#ban-expires-display').text('Бессрочно');
            $('#ban-timer-display').hide();
        }

        // Сообщение администратора
        if (data.message || data.admin_message) {
            let message = data.message || data.admin_message;
            $('#ban-admin-message-text').text(message);
            $('#ban-admin-message-container').show();
        } else {
            $('#ban-admin-message-container').hide();
        }
    } else {
        // Если данных нет, показываем заглушку
        console.log('ℹ️ Данные бана не переданы, показываем заглушку');
        $('#ban-reason-display').text('Нарушение правил');
        $('#ban-date-display').text(new Date().toLocaleString());
        $('#ban-expires-display').text('Бессрочно');
        $('#ban-timer-display').hide();
        $('#ban-admin-message-container').hide();
    }

    // Показываем страницу бана
    $('#ban-fullscreen').fadeIn(300);

    // Блокируем скролл body
    $('body').css('overflow', 'hidden');
}


function createBanModal() {
    console.log('🛠 Проверка наличия страницы бана...');
    
    // Проверяем, есть ли уже полноценная страница бана
    if ($('#ban-fullscreen').length > 0) {
        console.log('✅ Страница бана уже существует');
        return;
    }
    
    console.log('🛠 Создание полноценной страницы бана...');
    
    const fullscreenHtml = `
    <div id="ban-fullscreen" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #1a1a1a, #2d2d2d); z-index: 9999999; overflow-y: auto;">
        <div style="min-height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px;">
            <div style="background: white; border-radius: 30px; max-width: 600px; width: 100%; box-shadow: 0 30px 80px rgba(0,0,0,0.5); animation: slideUp 0.5s;">
                
                <!-- Шапка -->
                <div style="background: linear-gradient(135deg, #ff4757, #ff6b6b); color: white; padding: 40px 30px; border-radius: 30px 30px 0 0; text-align: center;">
                    <div style="font-size: 80px; margin-bottom: 20px;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <h1 style="margin: 0 0 10px 0; font-size: 36px;">⛔ Аккаунт заблокирован</h1>
                    <p style="margin: 0; opacity: 0.9; font-size: 16px;">Доступ к сайту временно ограничен</p>
                </div>
                
                <!-- Контент -->
                <div style="padding: 40px 30px;">
                    <!-- Информация о блокировке -->
                    <div style="background: #fff5f5; padding: 25px; border-radius: 20px; margin-bottom: 30px; border-left: 6px solid #ff4757;">
                        <h3 style="margin: 0 0 15px 0; color: #721c24; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-exclamation-circle"></i>
                            Информация о блокировке
                        </h3>
                        
                        <div style="display: grid; gap: 15px;">
                            <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid #ffe0e0;">
                                <span style="color: #721c24;">Причина:</span>
                                <span id="ban-reason-display" style="font-weight: 600; color: #ff4757;">Нарушение правил</span>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid #ffe0e0;">
                                <span style="color: #721c24;">Дата блокировки:</span>
                                <span id="ban-date-display" style="font-weight: 500;">13.02.2026 15:30</span>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid #ffe0e0;">
                                <span style="color: #721c24;">Срок действия:</span>
                                <span id="ban-expires-display" style="font-weight: 600;">Бессрочно</span>
                            </div>
                            
                            <div id="ban-timer-display" style="text-align: center; font-size: 24px; font-weight: bold; color: #ff4757; margin-top: 15px;">
                                00:00:00
                            </div>
                        </div>
                    </div>
                    
                    <!-- Сообщение администратора -->
                    <div id="ban-admin-message-container" style="background: #e3f2fd; padding: 25px; border-radius: 20px; margin-bottom: 30px; display: none;">
                        <h4 style="margin: 0 0 10px 0; color: #1565C0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user-shield"></i>
                            Сообщение администратора
                        </h4>
                        <p id="ban-admin-message-text" style="margin: 0; color: #333; font-size: 15px; line-height: 1.6;"></p>
                    </div>
                    
                    <!-- Апелляция -->
                    <div style="text-align: center; margin: 40px 0;">
                        <h3 style="margin-bottom: 15px; color: #333;">❗ Хотите обжаловать блокировку?</h3>
                        <p style="color: #666; margin-bottom: 25px;">Нажмите кнопку ниже, чтобы создать апелляцию для администратора</p>
                        
                        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                            <button onclick="userSubmitBanAppeal()" 
                                    style="background: #ff4757; color: white; border: none; padding: 16px 35px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 25px rgba(255,71,87,0.3);">
                                <i class="fas fa-gavel"></i>
                                Подать апелляцию
                            </button>
                            
                           <button onclick="window.userViewMyAppeals ? window.userViewMyAppeals() : alert('Функция временно недоступна')" 
                            style="background: #2196F3; color: white; border: none; 
                                   padding: 15px 30px; border-radius: 50px; font-size: 18px; 
                                   font-weight: bold; cursor: pointer; display: inline-flex; 
                                   align-items: center; gap: 12px; margin: 5px;
                                   box-shadow: 0 5px 20px rgba(33,150,243,0.4);">
                        <i class="fas fa-history"></i> Мои апелляции
                    </button>
                        </div>
                        
                        <p style="margin-top: 20px; color: #999; font-size: 13px;">
                            <i class="fas fa-info-circle"></i> Администратор ответит в течение 24 часов
                        </p>
                    </div>
                    
                    <!-- Подвал -->
                    <div style="text-align: center; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                        <p style="color: #888; font-size: 13px;">
                            <i class="fas fa-shield-alt"></i> Блокировка наложена автоматически или администратором
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    </style>
    `;
    
    $('body').append(fullscreenHtml);
    console.log('✅ Полноценная страница бана создана');
}

// ========== ФУНКЦИЯ ДЛЯ ФОРМАТИРОВАНИЯ ДАТЫ (БЕЗ ОШИБОК) ==========
function formatDateSafe(dateString) {
    if (!dateString) return 'Неизвестно';
    
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return 'Некорректная дата';
        }
        return date.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        console.error('Ошибка форматирования даты:', e);
        return String(dateString);
    }
}

// ========== ФУНКЦИЯ ДЛЯ ЗАПУСКА ТАЙМЕРА БАНА ==========
function startBanTimer(expires) {
    if (!expires) return;
    
    const timerElement = document.getElementById('ban-timer');
    if (!timerElement) return;
    
    // Очищаем предыдущий интервал
    if (window.banTimerInterval) {
        clearInterval(window.banTimerInterval);
    }
    
    function updateTimer() {
        try {
            const now = new Date();
            const expireDate = new Date(expires);
            const diff = expireDate - now;
            
            if (diff <= 0) {
                timerElement.innerHTML = '<span style="color: #4CAF50;">⏰ Срок блокировки истек</span>';
                clearInterval(window.banTimerInterval);
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            timerElement.innerHTML = `
                <div style="display: flex; gap: 15px; justify-content: center; align-items: center; flex-wrap: wrap;">
                    ${days > 0 ? `<div style="text-align: center;"><div style="font-size: 24px; font-weight: bold;">${days}</div><div style="font-size: 12px;">дн</div></div>` : ''}
                    <div style="text-align: center;"><div style="font-size: 24px; font-weight: bold;">${hours}</div><div style="font-size: 12px;">ч</div></div>
                    <div style="text-align: center;"><div style="font-size: 24px; font-weight: bold;">${minutes}</div><div style="font-size: 12px;">мин</div></div>
                    <div style="text-align: center;"><div style="font-size: 24px; font-weight: bold;">${seconds}</div><div style="font-size: 12px;">сек</div></div>
                </div>
            `;
        } catch (e) {
            console.error('Ошибка таймера:', e);
            timerElement.innerHTML = '⏳ Время блокировки: ' + formatDateSafe(expires);
        }
    }
    
    updateTimer();
    window.banTimerInterval = setInterval(updateTimer, 1000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

        function updateBanTimer(expires) {
    function update() {
        let now = new Date();
        let expireDate = new Date(expires);
        let diff = expireDate - now;
        
        if (diff <= 0) {
            $('#ban-timer').html('<span style="color:#4CAF50;">Срок истек</span>');
            $('#ban-action-buttons').show();
            clearInterval(timerInterval);
            return;
        }
        
        let days = Math.floor(diff / (1000 * 60 * 60 * 24));
        let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        $('#ban-timer').html(`
            <div style="display: flex; gap: 15px; justify-content: center;">
                <div style="text-align: center;">
                    <div style="font-size: 24px; font-weight: bold;">${days}</div>
                    <div style="font-size: 12px;">дней</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 24px; font-weight: bold;">${hours}</div>
                    <div style="font-size: 12px;">часов</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 24px; font-weight: bold;">${minutes}</div>
                    <div style="font-size: 12px;">минут</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 24px; font-weight: bold;">${seconds}</div>
                    <div style="font-size: 12px;">секунд</div>
                </div>
            </div>
        `);
    }
    
    update();
    let timerInterval = setInterval(update, 1000);
}

       function showModal(modalId) {
    console.log('Showing modal:', modalId);
    
    // 1. Скрываем все модальные окна
    $('.modal').hide().removeClass('active');
    
    // 2. Получаем нужное модальное окно
    const $modal = $('#' + modalId);
    if ($modal.length === 0) {
        console.error('Modal not found:', modalId);
        return;
    }
    
    // 3. ПРИНУДИТЕЛЬНО устанавливаем стили ДО показа
    $modal.css({
        'display': 'flex',
        'justify-content': 'center',
        'align-items': 'center',
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'width': '100%',
        'height': '100%',
        'background-color': 'rgba(0, 0, 0, 0.5)',
        'z-index': '2000',
        'opacity': '0',
        'transition': 'opacity 0.3s ease'
    });
    
    // 4. Убираем transform у содержимого
    $modal.find('.modal-content').css({
        'position': 'relative',
        'transform': 'none',
        'top': 'auto',
        'left': 'auto',
        'margin': 'auto'
    });
    
    // 5. Показываем
    $modal.show();
    
    // 6. Плавное появление через opacity
    setTimeout(() => {
        $modal.css('opacity', '1');
    }, 10);
    
    // 7. Блокируем прокрутку body
    $('body').css('overflow', 'hidden');
    
    console.log('Modal displayed:', modalId);
}

function closeModal(modalId) {
    console.log('Closing modal:', modalId);
    
    const $modal = $('#' + modalId);
    
    // Плавное исчезновение
    $modal.css('opacity', '0');
    
    setTimeout(() => {
        $modal.hide();
        
        // Разблокируем прокрутку если нет других модальных окон
        if ($('.modal:visible').length === 0) {
            $('body').css('overflow', 'auto');
        }
    }, 300);
}
function showToast(message, type = 'info', duration = 3000) {
    // Удаляем старые тосты
    $('.custom-toast').remove();
    
    const toast = document.createElement('div');
    toast.className = `custom-toast toast-${type}`;
    
    // Иконки для разных типов
    const icons = {
        'success': '✅',
        'error': '❌',
        'warning': '⚠️',
        'info': 'ℹ️'
    };
    
    const icon = icons[type] || '';
    
    toast.style.cssText = `
        position: fixed;
        top: 30px;                    /* ИЗМЕНИЛ: bottom → top */
        right: 30px;
        background: ${type === 'error' ? '#dc3545' : 
                     type === 'success' ? '#28a745' : 
                     type === 'warning' ? '#ffc107' : '#17a2b8'};
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 400px;
        transform: translateY(-100%); /* ИЗМЕНИЛ: translateX → translateY */
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    `;
    
    toast.innerHTML = `
        <span style="font-size: 20px;">${icon}</span>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Анимация появления
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';  /* ИЗМЕНИЛ: translateX → translateY */
        toast.style.opacity = '1';
    }, 10);
    
    // Автоматическое скрытие
    const hideTimeout = setTimeout(() => {
        hideToast(toast);
    }, duration);
    
    // Закрытие по клику
    toast.addEventListener('click', () => {
        clearTimeout(hideTimeout);
        hideToast(toast);
    });
    
    return toast;
}

// Функция для скрытия тоста (если её нет)
function hideToast(toastElement) {
    if (toastElement) {
        toastElement.style.transform = 'translateY(-100%)'; /* ИЗМЕНИЛ */
        toastElement.style.opacity = '0';
        
        setTimeout(() => {
            if (toastElement.parentNode) {
                toastElement.parentNode.removeChild(toastElement);
            }
        }, 400);
    }
}

function showLoginModal() {
    console.log('🔑 Открываем окно входа');
    
    // Создаем модалку напрямую
    Swal.fire({
        title: 'Вход в аккаунт',
        html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Логин</label>
                    <input type="text" id="login-username" class="swal2-input" placeholder="Введите логин">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Пароль</label>
                    <input type="password" id="login-password" class="swal2-input" placeholder="Введите пароль">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Войти',
        cancelButtonText: 'Отмена',
        preConfirm: () => {
            const login = $('#login-username').val();
            const password = $('#login-password').val();
            
            if (!login || !password) {
                Swal.showValidationMessage('Заполните все поля');
                return false;
            }
            
            return { login, password };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Отправляем запрос на вход
            $.ajax({
                url: 'api.php?action=login',
                method: 'POST',
                data: {
                    login: result.value.login,
                    password: result.value.password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Успешный вход!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ошибка',
                            text: response.message || 'Неверный логин или пароль'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка',
                        text: 'Проблема с соединением'
                    });
                }
            });
        }
    });
}

function hideToast(toast) {
    toast.style.transform = 'translateX(100%)';
    toast.style.opacity = '0';
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 400);
}

        function showLoginModal() {
            showModal('login-modal');
        }

        function showRegisterModal() {
            showModal('register-modal');
        }

        function showAddProductModal() {
            if (!currentUser) {
                showLoginModal();
                return;
            }
            showModal('add-product-modal');
        }

// ========== МОДАЛЬНОЕ ОКНО АКТИВАЦИИ ПРОМОКОДА ==========
function showPromoModal() {
    console.log('🎫 Открытие модального окна промокода');
    
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    Swal.fire({
        title: '🎫 Активировать промокод',
        html: `
            <div style="text-align: center; padding: 10px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-ticket-alt" style="font-size: 50px; color: white;"></i>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        Введите код промокода
                    </label>
                    <input id="swal-promo-code" class="swal2-input" 
                           placeholder="Например: PROMO_XXXXXX" 
                           style="width: 100%; margin: 0; font-family: monospace; text-transform: uppercase;"
                           autocomplete="off"
                           autofocus>
                </div>
                
                <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-top: 10px;">
                    <p style="margin: 0; color: #e65100; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i>
                        Персональные промокоды действуют 7 дней и могут быть активированы только один раз
                    </p>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Активировать',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        width: '500px',
        background: 'white',
        preConfirm: () => {
            const code = document.getElementById('swal-promo-code').value.trim().toUpperCase();
            if (!code) {
                Swal.showValidationMessage('Введите код промокода');
                return false;
            }
            return code;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            activatePromoCode(result.value);
        }
    });
}

// ========== АКТИВАЦИЯ ПРОМОКОДА ==========
function activatePromoCode(code) {
    console.log('💰 Активация промокода:', code);
    
    Swal.fire({
        title: '⏳ Активация...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=activate',
        type: 'POST',
        data: { 
            code: code,
            user_id: currentUser.id 
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                // Обновляем баланс
                currentUser.balance = parseFloat(response.new_balance);
                
                // Обновляем сессию
                $.ajax({
                    url: 'api.php?action=update_session_balance',
                    type: 'POST',
                    data: { balance: response.new_balance },
                    async: false
                });
                
                // Обновляем отображение баланса
                $('#balanceAmount, #user-balance').text(parseFloat(response.new_balance).toFixed(2));
                
                Swal.fire({
                    icon: 'success',
                    title: '✅ Промокод активирован!',
                    html: `
                        <div style="text-align: center; padding: 10px;">
                            <div style="font-size: 48px; color: #4CAF50; margin-bottom: 15px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p style="font-size: 20px; font-weight: bold; color: #4CAF50; margin-bottom: 10px;">
                                +${parseFloat(response.reward).toFixed(2)} ₽
                            </p>
                            <p style="color: #666;">
                                Текущий баланс: <strong>${parseFloat(response.new_balance).toFixed(2)} ₽</strong>
                            </p>
                        </div>
                    `,
                    timer: 3000,
                    showConfirmButton: false
                });
                
            } else {
                let errorMessage = response.message || 'Не удалось активировать промокод';
                let icon = 'error';
                let title = '❌ Ошибка';
                
                if (errorMessage.includes('истек')) {
                    title = '⏰ Промокод истек';
                    icon = 'warning';
                } else if (errorMessage.includes('использовали')) {
                    title = '⚠️ Промокод уже использован';
                    icon = 'warning';
                } else if (errorMessage.includes('корзине')) {
                    title = '🛒 Промокод для корзины';
                    icon = 'info';
                }
                
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: errorMessage,
                    confirmButtonColor: '#ff9800'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения с сервером',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}
        function showCreateTicketModal() {
            if (!currentUser) {
                showLoginModal();
                return;
            }
            showModal('create-ticket-modal');
        }

        function showSendNotificationModal() {
            showModal('send-notification-modal');
        }

        function showSecurityTab(tab) {
            $('#password-tab, #pin-tab').hide();
            $('#' + tab + '-tab').show();
        }

        function moveToNext(input, next) {
            if (input.value.length === 1) {
                if (next <= 4) {
                    $(`.pin-digit:nth-child(${next})`).focus();
                }
            }
        }

        function toggleUserMenu() {
            $('#user-menu').toggle();
        }
        

        function checkPinStatus() {
    if (!currentUser) return false;
    
    $.ajax({
        url: 'api.php?action=check_pin_status',
        method: 'GET',
        async: false, // Синхронный запрос для немедленной проверки
        success: function(response) {
            console.log('Sync PIN check:', response);
            if (response && response.status === 'success') {
                requirePin = response.require_pin;
                return response.require_pin;
            }
        }
    });
    
    return false;
}


function showUserCabinet() {
    console.log('👤 Открытие личного кабинета');
    
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    // МГНОВЕННАЯ проверка PIN (синхронный запрос)
    let hasPin = false;
    $.ajax({
        url: 'api.php?action=check_pin_status',
        method: 'GET',
        async: false, // Синхронно - ждем ответ
        success: function(response) {
            console.log('PIN статус:', response);
            hasPin = response.status === 'success' && response.has_pin;
        }
    });
    
    // Если есть PIN и он не подтвержден - показываем модалку
    if (hasPin && !pinVerified) {
        showPinVerificationModal();
        return;
    }
    
    // Если PIN не нужен или уже подтвержден - показываем кабинет
    let html = `
        <div style="margin-top:30px;">
            <h1 style="font-size:36px; margin-bottom:30px; color:#333;">Личный кабинет</h1>
            
            <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px;">
                <!-- Боковая панель -->
                <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 40px; color: white;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 style="margin: 0 0 5px 0; color: #333;">${currentUser.username || ''}</h3>
                        <p style="color: #666; margin: 0;">${currentUser.login || ''}</p>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 10px; margin-top: 15px;">
                            <div style="color: #4CAF50; font-size: 18px; font-weight: bold;">
                                <i class="fas fa-wallet"></i> ${parseFloat(currentUser.balance || 0).toFixed(2)} ₽
                            </div>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid #eee; padding-top: 20px;">
                        <button class="btn" style="width:100%; margin-bottom:10px; text-align:left; background:#f8f9fa;" onclick="showSecurity()">
                            <i class="fas fa-shield-alt" style="color:#4CAF50;"></i> Безопасность
                        </button>
                        <a href="#" onclick="showUserSearch(); toggleUserMenu(); return false;" 
                           style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" 
                           onmouseover="this.style.background='#f8f9fa'" 
                           onmouseout="this.style.background='transparent'">
                            <i class="fas fa-search" style="color: #2196F3; width: 20px;"></i> Поиск пользователей
                        </a>
                        <button class="btn" style="width:100%; margin-bottom:10px; text-align:left; background:#f8f9fa;" onclick="window.myProductsHandler()">
                            <i class="fas fa-box" style="color:#2196F3;"></i> Мои товары
                        </button>
                        <button class="btn" style="width:100%; margin-bottom:10px; text-align:left; background:#f8f9fa;" onclick="showFavorites()">
                            <i class="fas fa-heart" style="color:#e91e63;"></i> Избранное
                        </button>
                        <a href="tickets.php" style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" 
                           onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-ticket-alt" style="color: #ff9800; width: 20px;"></i> Мои тикеты
                        </a>
                        <button class="btn" style="width:100%; margin-bottom:10px; text-align:left; background:#f8f9fa;" onclick="window.location.href='forum.php';">
                            <i class="fas fa-comments" style="color: #4a90e2;"></i> Форум
                        </button>
                        <button class="btn" style="width:100%; margin-bottom:10px; text-align:left; background:#f8f9fa;" onclick="showAddProductModal()">
                            <i class="fas fa-plus" style="color:#667eea;"></i> Добавить товар
                        </button>
                        <a href="#" onclick="showMyMessages(); return false;" 
                           style="display:block; padding:10px 15px; color:#333; text-decoration:none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s;" 
                           onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-comments" style="color: #2196F3; width: 20px;"></i> Мои сообщения
                            <span id="unread-messages-count" style="background: #ff4757; color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px; margin-left: 5px; display: none;">0</span>
                        </a>
                        <button class="btn" style="width:100%; margin-bottom:10px; text-align:left; background:#f8f9fa;" onclick="showPromoModal()">
                            <i class="fas fa-gift" style="color:#fd79a8;"></i> Активировать промокод
                        </button>
                    </div>
                </div>
                
                <!-- Основной контент -->
                <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="margin-bottom: 20px; color: #333;">Мои данные</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                            <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Логин</div>
                            <div style="font-weight: bold; font-size: 18px;">${currentUser.login || ''}</div>
                        </div>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                            <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Username</div>
                            <div style="font-weight: bold; font-size: 18px;">${currentUser.username || ''}</div>
                        </div>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                            <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Роль</div>
                            <div style="font-weight: bold; font-size: 18px;">
                                ${currentUser.role === 'admin' ? 
                                    '<span style="color:#9c27b0;">Администратор</span>' : 
                                    '<span style="color:#4CAF50;">Пользователь</span>'}
                            </div>
                        </div>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                            <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Статус</div>
                            <div style="font-weight: bold; font-size: 18px;">
                                ${currentUser.banned == 1 ? 
                                    '<span style="color:#f44336;">Заблокирован</span>' : 
                                    '<span style="color:#4CAF50;">Активен</span>'}
                            </div>
                        </div>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px 0; color: #333;">Быстрые действия</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <button class="btn btn-primary" onclick="showSecurity()">
                            <i class="fas fa-lock"></i> Сменить пароль
                        </button>
                        <button class="btn" style="background:#9c27b0; color:white;" onclick="showSecurityTab('pin')">
                            <i class="fas fa-key"></i> Настроить PIN
                        </button>
                        <button class="btn" style="background:#ff9800; color:white;" onclick="showCreateTicketModal()">
                            <i class="fas fa-question-circle"></i> Создать тикет
                        </button>
                        <button class="btn" style="background:#4CAF50; color:white;" onclick="showPromoModal()">
                            <i class="fas fa-gift"></i> Ввести промокод
                        </button>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px 0; color: #333;">Статистика</h3>
                    <div id="user-stats" style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
                        <p style="color: #666;">Загрузка статистики...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    loadUserStats();
    $('#user-menu').hide();
}


function loadUserStats() {
    // Здесь можно добавить запрос для получения статистики пользователя
    // Например, количество покупок, продаж и т.д.
    let html = `
        <div style="display: flex; justify-content: space-around; text-align: center;">
            <div>
                <div style="font-size: 24px; font-weight: bold; color: #667eea;">0</div>
                <div style="color: #666; font-size: 14px;">Куплено товаров</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: bold; color: #4CAF50;">0</div>
                <div style="color: #666; font-size: 14px;">Продано товаров</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: bold; color: #ff9800;">0</div>
                <div style="color: #666; font-size: 14px;">Активных тикетов</div>
            </div>
        </div>
    `;
    
    $('#user-stats').html(html);
}

// ========== ВХОД В АККАУНТ ==========
function login() {
    console.log('🔑 Попытка входа');
    
    const login = $('#login-username').val() || $('#login').val() || $('input[name="login"]').val();
    const password = $('#login-password').val() || $('#password').val() || $('input[name="password"]').val();
    const remember = $('#login-remember').is(':checked') || $('input[name="remember"]').is(':checked');
    
    if (!login || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'Внимание',
            text: 'Заполните все поля'
        });
        return;
    }
    
    Swal.fire({
        title: '⏳ Вход...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=login',
        method: 'POST',
        data: {
            login: login,
            password: password,
            remember: remember ? 1 : 0
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                currentUser = response.user;
                
                // Закрываем модалку входа
                closeModal('login-modal');
                
                // ✅ ПРОВЕРЯЕМ PIN
                checkPinStatus();
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Неверный логин или пароль'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Проблема с соединением'
            });
        }
    });
}

// ========== ПРОВЕРКА PIN ==========
function checkPinStatus() {
    console.log('🔐 Проверка PIN');
    
    $.ajax({
        url: 'api.php?action=check_pin_status',
        method: 'GET',
        success: function(response) {
            console.log('📦 Ответ:', response);
            
            if (response.status === 'success' && response.has_pin) {
                // ЕСТЬ PIN
                askForPin();
            } else {
                // НЕТ PIN
                console.log('✅ Вход без PIN');
                location.reload();
            }
        },
        error: function() {
            console.error('❌ Ошибка');
            location.reload();
        }
    });
}

// ========== ЗАПРОС PIN ==========
function askForPin() {
    console.log('🔑 Запрос PIN');
    
    Swal.fire({
        title: 'Введите PIN-код',
        input: 'password',
        inputLabel: 'PIN-код (4 цифры)',
        inputPlaceholder: '****',
        inputAttributes: {
            maxlength: 4,
            inputmode: 'numeric',
            pattern: '\\d*'
        },
        showCancelButton: true,
        confirmButtonText: '✅ Подтвердить',
        cancelButtonText: '❌ Выйти',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#f44336',
        allowOutsideClick: false,
        inputValidator: (value) => {
            if (!value || value.length !== 4) {
                return 'Введите 4 цифры';
            }
            if (!/^\d+$/.test(value)) {
                return 'Только цифры';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            checkPin(result.value);
        } else {
            logout();
        }
    });
}

// ========== ПРОВЕРКА PIN ==========
function checkPin(pin) {
    console.log('🔑 Проверка PIN:', pin);
    
    Swal.fire({
        title: '⏳ Проверка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=verify_pin',
        method: 'POST',
        data: { pin: pin },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                pinVerified = true;
                
                Swal.fire({
                    icon: 'success',
                    title: '✅ PIN подтвержден!',
                    text: 'Вход выполнен',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    console.log('🔄 Перезагрузка...');
                    location.reload();
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Неверный PIN',
                    text: 'Попробуйте снова'
                }).then(() => {
                    askForPin();
                });
            }
        },
        error: function() {
            Swal.close();
            location.reload();
        }
    });
}

function checkPinImmediately() {
    console.log('🔐 Мгновенная проверка PIN после входа');
    
    // Закрываем все модалки
    $('.modal').hide();
    $('.swal2-container').remove();
    
    $.ajax({
        url: 'api.php?action=check_pin_status',
        method: 'GET',
        success: function(response) {
            console.log('📦 Ответ сервера (PIN):', response);
            
            if (response.status === 'success') {
                if (response.has_pin) {
                    // ✅ ЕСТЬ PIN - показываем модалку
                    setTimeout(() => {
                        showPinModalNow();
                    }, 100);
                } else {
                    // ✅ НЕТ PIN - просто показываем главную (БЕЗ ЛИШНЕГО СООБЩЕНИЯ)
                    console.log('ℹ️ PIN не установлен, показываем главную');
                    
                    // Сразу показываем главную
                    showHome();
                    
                    // Обновляем интерфейс
                    refreshUserInterface();
                    
                    // Обновляем счетчики
                    loadCartCount();
                    loadNotifications();
                    loadFavoritesCount();
                }
            } else {
                console.error('❌ Ошибка проверки PIN');
                showHome();
            }
        },
        error: function() {
            console.error('❌ Ошибка соединения при проверке PIN');
            showHome();
        }
    });
}





// ========== ПРОВЕРКА PIN ==========
function verifyUserPinNow(pin) {
    console.log('🔑 Проверка PIN:', pin);
    
    Swal.fire({
        title: '⏳ Проверка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=verify_pin',
        method: 'POST',
        data: { pin: pin },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                pinVerified = true;
                
                // Обновляем статус в currentUser
                if (currentUser) {
                    currentUser.pin_verified = true;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: '✅ PIN подтвержден!',
                    text: 'Добро пожаловать!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // ✅ ПОЛНОСТЬЮ ПЕРЕЗАГРУЖАЕМ СТРАНИЦУ (самый надежный способ)
                    location.reload();
                    
                    // ИЛИ если хочешь без перезагрузки - используй код ниже
                    // fullRefreshInterface();
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Неверный PIN',
                    text: 'Попробуйте снова',
                    confirmButtonColor: '#ff4757'
                }).then(() => {
                    showPinModalNow();
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Проблема с соединением',
                confirmButtonColor: '#ff4757'
            }).then(() => {
                showHome();
            });
        }
    });
}

// ========== ПОЛНОЕ ОБНОВЛЕНИЕ ИНТЕРФЕЙСА БЕЗ ПЕРЕЗАГРУЗКИ ==========
function fullRefreshInterface() {
    console.log('🔄 Полное обновление интерфейса');
    
    // 1. Показываем главную
    showHome();
    
    // 2. Принудительно перестраиваем шапку
    rebuildHeader();
    
    // 3. Обновляем все счетчики
    loadCartCount();
    loadNotifications();
    loadFavoritesCount();
    
    // 4. Проверяем права админа
    if (currentUser && currentUser.role === 'admin') {
        $('.nav a:contains("Админ-панель")').show();
    }
}

// ========== ПЕРЕСТРОЙКА ШАПКИ ==========
function rebuildHeader() {
    console.log('🔧 Перестройка шапки');
    
    // Удаляем старую шапку
    $('header.header').remove();
    
    // Создаем новую шапку с текущим пользователем
    let headerHtml = `
        <header class="header">
            <div class="container">
                <div class="header-content">
                    <a href="#" class="logo" onclick="showHome(); return false;">
                        <i class="fas fa-store"></i> Маркетплейс
                    </a>
                    
                    <div class="nav">
                        <a href="#" class="active" onclick="showHome(); return false;">
                            <i class="fas fa-home"></i> Главная
                        </a>
                        <a href="#" onclick="showProducts(); return false;">
                            <i class="fas fa-box"></i> Товары
                        </a>
                        ${currentUser && currentUser.role === 'admin' ? 
                            `<a href="#" onclick="showAdminPanel(); return false;">
                                <i class="fas fa-cog"></i> Админ-панель
                            </a>` : ''}
                        
                        <div class="notification-bell" onclick="showNotifications()">
                            <i class="fas fa-bell"></i>
                            <span id="notification-count" class="notification-count">0</span>
                        </div>
                    </div>
                    
                    <div class="user-info">
                        <div class="balance">
                            <i class="fas fa-wallet"></i> 
                            <span id="user-balance">${parseFloat(currentUser.balance || 0).toFixed(2)}</span> ₽
                        </div>
                        <div class="dropdown">
                            <div class="avatar-container" onclick="toggleUserMenu(event)">
                                <div class="avatar-default">
                                    <i class="fas fa-user avatar-icon"></i>
                                </div>
                                <div class="avatar-upload-btn" onclick="openAvatarModal()">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <!-- Меню пользователя -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    `;
    
    $('body').prepend(headerHtml);
}

// Проверка специальных уведомлений при входе
function checkSpecialNotifications() {
    if (!currentUser) return;
    
    $.get('api.php?action=check_special_notifications', function(response) {
        if (response.status === 'success' && response.has_special) {
            setTimeout(() => {
                showPromoRewardModal(response.notification);
            }, 1000);
        }
    });
}

// Показ модального окна с промокодом
function showPromoRewardModal(notification) {
    // Парсим HTML из уведомления
    let tempDiv = document.createElement('div');
    tempDiv.innerHTML = notification.message;
    
    // Извлекаем сообщение администратора
    let adminMessage = '';
    let promoCode = '';
    let reward = '';
    
    // Ищем промокод в тексте
    let codeMatch = notification.message.match(/<strong>Промокод:<\/strong> <code>([^<]+)<\/code>/);
    if (codeMatch) {
        promoCode = codeMatch[1];
    }
    
    // Ищем награду
    let rewardMatch = notification.message.match(/<strong>Награда:<\/strong> ([\d.]+) ₽/);
    if (rewardMatch) {
        reward = rewardMatch[1];
    }
    
    // Ищем сообщение администратора
    let messageMatch = notification.message.match(/<strong>Сообщение от администратора:<\/strong><br>([\s\S]*?)<br><br>/);
    if (messageMatch) {
        adminMessage = messageMatch[1].replace(/<br\s*\/?>/gi, '\n');
    }
    
    $('#admin-message-text').text(adminMessage);
    $('#promo-code-display').text(promoCode);
    $('#promo-reward-display').text(reward);
    
    // Сохраняем данные для активации
    $('#promo-reward-modal').data('promo-code', promoCode);
    
    showModal('promo-reward-modal');
}

// Активация промокода из модального окна
function activatePromoFromModal() {
    let promoCode = $('#promo-reward-modal').data('promo-code');
    
    if (!promoCode) {
        showToast('Ошибка: промокод не найден');
        return;
    }
    
    $.post('api.php?action=activate', {code: promoCode}, function(response) {
        if (response.status === 'success') {
            closeModal('promo-reward-modal');
            showToast(response.message);
            currentUser.balance += response.reward;
            $('#user-balance').text(currentUser.balance.toFixed(2));
        } else {
            showToast(response.message);
        }
    });
}

// Добавить проверку при загрузке страницы
$(document).ready(function() {
    // ... существующий код ...
    
    if (currentUser) {
        checkSpecialNotifications();
    }
});


function calculateDiscount() {
    // Получаем значение цены и заменяем запятую на точку
    let priceInput = $('#product-price').val();
    let price = parseFloat(priceInput.replace(',', '.')) || 0;
    
    let discount = parseInt($('#product-discount').val()) || 0;
    
    // Ограничиваем скидку
    if (discount < 0) discount = 0;
    if (discount > 100) discount = 100;
    
    // Устанавливаем правильное значение в поле скидки
    $('#product-discount').val(discount);
    
    // Рассчитываем финальную цену
    let finalPrice = price;
    if (discount > 0) {
        finalPrice = price - (price * discount / 100);
    }
    
    // Отображаем финальную цену (исправлено форматирование)
    $('#final-price-display').text(finalPrice.toFixed(2).replace('.', ',') + ' ₽');
    
    // Обновляем скрытое поле с финальной ценой (если оно есть)
    if ($('#final-price-hidden').length) {
        $('#final-price-hidden').val(finalPrice.toFixed(2));
    }
}

// Предпросмотр изображений
function previewImages(input) {
    const files = input.files;
    const previewContainer = $('#image-preview-container');
    const previews = $('#image-previews');
    const uploadArea = $('#upload-area');
    
    // Очищаем предыдущие превью
    previews.empty();
    
    if (files.length > 0) {
        // Ограничиваем количество файлов
        if (files.length > 5) {
            showToast('Можно загрузить не более 5 изображений');
            input.value = '';
            return;
        }
        
        // Проверяем размер файлов
        for (let i = 0; i < Math.min(files.length, 5); i++) {
            if (files[i].size > 5 * 1024 * 1024) { // 5MB
                showToast('Файл ' + files[i].name + ' слишком большой (макс. 5MB)');
                input.value = '';
                previewContainer.hide();
                uploadArea.show();
                return;
            }
        }
        
        uploadArea.hide();
        previewContainer.show();
        
        // Создаем превью для каждого файла
        for (let i = 0; i < Math.min(files.length, 5); i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewItem = $(`
                    <div style="position: relative; width: 100px; height: 100px;">
                        <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 2px solid #667eea;">
                        <div style="position: absolute; top: -5px; right: -5px; background: #ff4757; color: white; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px;"
                             onclick="removeImagePreview(${i})">
                            ×
                        </div>
                        ${i === 0 ? '<div style="position: absolute; bottom: 5px; left: 5px; background: #4CAF50; color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px;">Основное</div>' : ''}
                    </div>
                `);
                previews.append(previewItem);
            }
            
            reader.readAsDataURL(file);
        }
        
        // Показываем сколько файлов выбрано
        uploadArea.html(`
            <div style="color: #4CAF50; font-weight: bold;">
                <i class="fas fa-check-circle"></i> Выбрано ${files.length} файл(ов)
            </div>
            <button type="button" class="btn" style="margin-top: 10px; background: #667eea; color: white;" 
                    onclick="$('#product-images').click()">
                <i class="fas fa-edit"></i> Изменить
            </button>
        `);
    } else {
        previewContainer.hide();
        uploadArea.show();
        uploadArea.html(`
            <div style="font-size: 60px; color: #667eea; margin-bottom: 10px;">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h4 style="margin-bottom: 10px; color: #333;">Перетащите или выберите фото</h4>
            <p style="color: #666; margin-bottom: 15px;">До 5 изображений. Максимум 5MB каждое</p>
            <button type="button" class="btn" style="background: #667eea; color: white;">
                <i class="fas fa-images"></i> Выбрать файлы
            </button>
        `);
    }
}

// Удаление превью изображения
function removeImagePreview(index) {
    const input = document.getElementById('product-images');
    const dt = new DataTransfer();
    
    // Создаем новый список файлов без удаленного
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) {
            dt.items.add(input.files[i]);
        }
    }
    
    input.files = dt.files;
    previewImages(input); // Обновляем превью
}

// Drag and drop для изображений
function setupImageUpload() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('product-images');
    
    if (uploadArea) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#4CAF50';
            uploadArea.style.background = '#e8f5e9';
        });
        
        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.background = '#fafafa';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.background = '#fafafa';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                previewImages(fileInput);
            }
        });
    }
}

// === ФУНКЦИИ ДЛЯ ТИКЕТОВ ===

let currentTicketId = null;

// Открыть тикет в модалке
function openTicket(ticketId, subject, messages) {
    currentTicketId = ticketId;
    
    // Заполняем данные
    document.getElementById('modalTicketId').textContent = ticketId;
    document.getElementById('modalTicketSubject').textContent = subject;
    
    // Очищаем сообщения
    const messagesContainer = document.getElementById('ticketMessages');
    messagesContainer.innerHTML = '';
    
    // Добавляем сообщения
    if (messages && messages.length > 0) {
        messages.forEach(msg => {
            addMessageToModal(msg.author, msg.text, msg.time, msg.isAdmin);
        });
    }
    
    // Показываем модалку
    document.getElementById('ticketModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Закрыть модалку
function closeTicketModal() {
    document.getElementById('ticketModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    currentTicketId = null;
}

// Добавить сообщение в модалку
function addMessageToModal(author, text, time, isAdmin = false) {
    const messagesContainer = document.getElementById('ticketMessages');
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isAdmin ? 'message-admin' : 'message-user'}`;
    
    const avatarText = isAdmin ? 'A' : author.charAt(0).toUpperCase();
    const senderName = isAdmin ? 'Администратор' : author;
    
    messageDiv.innerHTML = `
        <div class="message-header">
            <div class="message-avatar">${avatarText}</div>
            <span class="message-sender">${senderName}</span>
            <span class="message-time">${time}</span>
        </div>
        <div class="message-bubble">${escapeHtml(text)}</div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Отправить сообщение в тикет
function sendTicketMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    if (!currentTicketId) {
        showToast('Ошибка: тикет не выбран', 'error');
        return;
    }
    
    // Отправка на сервер
    fetch('send_ticket_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `ticket_id=${currentTicketId}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Добавляем сообщение локально
            const now = new Date();
            const timeStr = `${now.toLocaleDateString()}, ${now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
            
            addMessageToModal('Вы', message, timeStr, false);
            
            // Очищаем поле
            input.value = '';
            
            // Обновляем список тикетов
            loadTickets();
        } else {
            showToast('Ошибка отправки: ' + (data.error || 'Неизвестная ошибка'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка соединения', 'error');
    });
}

// Изменить статус тикета (для админа)
function changeTicketStatus(ticketId, newStatus) {
    fetch('change_ticket_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `ticket_id=${ticketId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Статус изменен', 'success');
            
            // Обновляем отображение статуса
            const badge = document.getElementById('ticketBadge');
            if (badge) {
                badge.textContent = getStatusText(newStatus);
                badge.className = `ticket-badge status-${newStatus}`;
            }
            
            // Обновляем список тикетов
            loadTickets();
        } else {
            // Показываем ошибку админа
            const errorDiv = document.getElementById('adminError');
            const errorText = document.getElementById('adminErrorText');
            
            if (errorDiv && errorText) {
                errorText.textContent = data.error || 'Ошибка изменения статуса';
                errorDiv.style.display = 'flex';
                
                // Скрыть через 5 секунд
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                }, 5000);
            }
            
            showToast('Ошибка изменения статуса', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка соединения', 'error');
    });
}

// Загрузить список тикетов
function loadTickets() {
    fetch('get_tickets.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTicketsList(data.tickets);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Создать новый тикет
function createNewTicket() {
    const subject = document.getElementById('newTicketSubject')?.value.trim();
    const message = document.getElementById('newTicketMessage')?.value.trim();
    
    if (!subject || !message) {
        showToast('Заполните тему и сообщение', 'warning');
        return;
    }
    
    fetch('create_ticket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `subject=${encodeURIComponent(subject)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Тикет создан', 'success');
            
            // Закрываем модалку создания
            const modal = document.getElementById('newTicketModal');
            if (modal) modal.style.display = 'none';
            
            // Очищаем поля
            if (document.getElementById('newTicketSubject')) {
                document.getElementById('newTicketSubject').value = '';
            }
            if (document.getElementById('newTicketMessage')) {
                document.getElementById('newTicketMessage').value = '';
            }
            
            // Обновляем список и открываем новый тикет
            loadTickets();
            setTimeout(() => {
                if (data.ticket_id) {
                    openTicket(data.ticket_id, subject, [{author: 'Вы', text: message, time: 'Только что', isAdmin: false}]);
                }
            }, 500);
        } else {
            showToast('Ошибка создания: ' + (data.error || ''), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка соединения', 'error');
    });
}

// Вспомогательные функции
function getStatusText(status) {
    const statusMap = {
        'new': 'Новый',
        'open': 'Открыт',
        'progress': 'В работе',
        'closed': 'Закрыт'
    };
    return statusMap[status] || status;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// === ИНИЦИАЛИЗАЦИЯ ===

// При загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Кнопка отправки сообщения в тикете
    const sendBtn = document.getElementById('sendTicketMessageBtn');
    if (sendBtn) {
        sendBtn.addEventListener('click', sendTicketMessage);
    }
    
    // Enter для отправки сообщения
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendTicketMessage();
            }
        });
    }
    
    // Кнопка создания нового тикета
    const newTicketBtn = document.getElementById('newTicketBtn');
    if (newTicketBtn) {
        newTicketBtn.addEventListener('click', function() {
            document.getElementById('newTicketModal').style.display = 'flex';
        });
    }
    
    // Селектор статуса (для админа)
    const statusSelect = document.getElementById('ticketStatusSelect');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (currentTicketId) {
                changeTicketStatus(currentTicketId, this.value);
            }
        });
    }
    
    // Закрытие модалок по клику на фон
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
                if (this.id === 'ticketModal') {
                    document.body.style.overflow = 'auto';
                }
            }
        });
    });
    
    // Кнопки закрытия модалок
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
                if (modal.id === 'ticketModal') {
                    document.body.style.overflow = 'auto';
                }
            }
        });
    });
});


function showCheckoutModal() {
    // Получаем текущие итоги
    const subtotal = parseFloat($('#cart-subtotal').text()) || 0;
    const discount = parseFloat($('#cart-discount').text().replace('-', '').replace('₽', '')) || 0;
    const total = parseFloat($('#cart-total').text()) || subtotal;
    
    let summaryHtml = `
        <div style="background: #f8f9fa; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; margin-bottom: 15px;">Детали заказа</h4>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span>Сумма заказа:</span>
                <span>${subtotal.toFixed(2)} ₽</span>
            </div>
    `;
    
    if (discount > 0) {
        summaryHtml += `
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #f44336;">
                <span>Скидка по промокоду:</span>
                <span>-${discount.toFixed(2)} ₽</span>
            </div>
        `;
    }
    
    summaryHtml += `
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; margin-top: 10px; padding-top: 10px; border-top: 2px solid #ddd;">
                <span>ИТОГО:</span>
                <span style="color: #4CAF50;">${total.toFixed(2)} ₽</span>
            </div>
        </div>
    `;
    
    // Заполняем сводку в модалке
    $('#checkout-summary').html(summaryHtml);
    
    // Устанавливаем сумму для оплаты
    $('#pay-amount').text(total.toFixed(2));
    
    // Показываем модалку
    $('#checkout-modal').show();
}

function nextCheckoutStep(step) {
    // Валидация текущего шага
    if (step === 2) {
        if (!$('#checkout-name').val() || !$('#checkout-email').val() || !$('#checkout-phone').val()) {
            showToast('Заполните все обязательные поля', 'warning');
            return;
        }
    }
    
    if (step === 3) {
        const deliveryMethod = $('input[name="delivery"]:checked').val();
        if (deliveryMethod === 'courier' && !$('#checkout-address').val()) {
            showToast('Укажите адрес доставки', 'warning');
            return;
        }
    }
    
    // Переход к следующему шагу
    $('.checkout-step').removeClass('active');
    $(`.checkout-step[data-step="${step}"]`).addClass('active');
    $('.checkout-step-content').removeClass('active').hide();
    $(`#checkout-step-${step}`).addClass('active').show();
    
    // Прокрутка вверх модалки
    $('#checkout-modal .modal-content').scrollTop(0);
}

function prevCheckoutStep() {
    const currentStep = $('.checkout-step-content.active').attr('id').split('-')[2];
    const prevStep = parseInt(currentStep) - 1;
    
    if (prevStep >= 1) {
        $('.checkout-step').removeClass('active');
        $(`.checkout-step[data-step="${prevStep}"]`).addClass('active');
        $('.checkout-step-content').removeClass('active').hide();
        $(`#checkout-step-${prevStep}`).addClass('active').show();
    }
}


// ВРЕМЕННО - для проверки
function testCreateOrder() {
    $.ajax({
        url: 'api.php?action=create_order',
        method: 'POST',
        data: {
            name: 'Test',
            email: 'test@test.com',
            phone: '1234567890',
            delivery_method: 'courier',
            address: 'Test address',
            payment_method: 'balance'
        },
        success: function(response) {
            console.log('📡 ТЕСТОВЫЙ ОТВЕТ API:', response);
             showToast('Ответ сервера: ' + JSON.stringify(response));
        },
        error: function(xhr) {
            console.error('❌ ОШИБКА API:', xhr.responseText);
             showToast('Ошибка: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
}

function processPayment() {
    // БЛОКИРУЕМ КНОПКУ
    $('#pay-button').html('<i class="fas fa-spinner fa-spin"></i> Оформление...').prop('disabled', true);
    
    // ОТПРАВЛЯЕМ ЗАПРОС
    $.ajax({
        url: 'api.php?action=create_order',
        type: 'POST',
        data: {
            name: 'Покупатель',
            email: 'test@test.com',
            phone: '123456789',
            delivery_method: 'courier',
            address: 'Адрес',
            payment_method: 'balance'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                closeModal('checkout-modal');
                
                // ПОМЕЧАЕМ ТОВАРЫ
                let ids = response.purchased_product_ids || [];
                ids.forEach(function(id) {
                    let $card = $(`.product-card[data-id="${id}"]`);
                    if ($card.length) {
                        $card.css({
                            'border': '3px solid #dc3545',
                            'opacity': '0.7',
                            'filter': 'grayscale(0.8)'
                        });
                        $card.find('.product-image').append(
                            '<div style="position:absolute; top:10px; left:10px; background:#dc3545; color:white; padding:5px 15px; border-radius:20px; font-weight:bold; z-index:999;">🔴 ПРОДАНО</div>'
                        );
                        $card.find('.btn-primary').replaceWith(
                            '<button class="btn" style="flex:2; background:#6c757d; color:white;" disabled><i class="fas fa-ban"></i> ТОВАР ПРОДАН</button>'
                        );
                    }
                });
                
                showToast('✅ Заказ оформлен!', 'success');
                setTimeout(() => location.reload(), 2000);
                
            } else {
                $('#pay-button').html('<i class="fas fa-lock"></i> Оплатить').prop('disabled', false);
                showToast(response.message, 'error');
            }
        },
        error: function() {
            $('#pay-button').html('<i class="fas fa-lock"></i> Оплатить').prop('disabled', false);
            showToast('Ошибка соединения', 'error');
        }
    });
}

// ПОЛУЧИТЬ КУПЛЕННЫЕ ТОВАРЫ ПОЛЬЗОВАТЕЛЯ
function getPurchasedProducts() {
    if (!currentUser) return;
    
    $.ajax({
        url: 'api.php?action=get_purchased_products',
        method: 'GET',
        data: { user_id: currentUser.id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.products) {
                window.purchasedProducts = response.products;
                
                // ПОКАЗЫВАЕМ ИХ В СПЕЦИАЛЬНОМ БЛОКЕ
                showPurchasedProductsBlock(response.products);
            }
        }
    });
}

/// ЗАГРУЗКА КУПЛЕННЫХ ТОВАРОВ ИЗ БД
function loadPurchasedProducts() {
    if (!currentUser) {
        console.log('❌ Нет авторизации');
        return;
    }
    
    console.log('🔄 Загрузка купленных товаров...');
    
    $.ajax({
        url: 'api.php?action=get_purchased_products',
        method: 'GET',
        dataType: 'json',
        cache: false,
        success: function(response) {
            if (response.status === 'success') {
                window.purchasedProducts = response.products || [];
                console.log('✅ Загружено купленных товаров:', window.purchasedProducts.length);
                
                // ПЕРЕРИСОВЫВАЕМ ТОВАРЫ НА СТРАНИЦЕ
                if (typeof loadProducts === 'function') {
                    loadProducts();
                }
                
                // ПОМЕЧАЕМ КУПЛЕННЫЕ ТОВАРЫ В РЕАЛЬНОМ ВРЕМЕНИ
                if (window.purchasedProducts.length > 0) {
                    window.purchasedProducts.forEach(function(product) {
                        let productId = product.product_id || product.id;
                        if (productId) {
                            markProductAsSoldUI(productId);
                        }
                    });
                }
            } else {
                console.error('❌ Ошибка загрузки:', response.message);
                window.purchasedProducts = [];
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Ошибка AJAX:', error);
            window.purchasedProducts = [];
        }
    });
}

// ПОКАЗАТЬ БЛОК "РАНЕЕ КУПЛЕННЫЕ ТОВАРЫ"
function showPurchasedProductsBlock(products) {
    if (!products || products.length === 0) return;
    
    let html = `
        <div style="margin-top: 50px; margin-bottom: 30px;">
            <h2 style="font-size: 24px; color: #333; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-history" style="color: #6c757d; margin-right: 10px;"></i> 
                Ранее купленные товары
                <span style="font-size: 14px; color: #999; margin-left: 15px;">
                    (больше не доступны для покупки)
                </span>
            </h2>
            <div id="purchased-products-grid" class="products-grid"></div>
        </div>
    `;
    
    // Добавляем блок после обычных товаров
    if ($('#purchased-products-section').length === 0) {
        $('#products-list').parent().append('<div id="purchased-products-section"></div>');
    }
    
    $('#purchased-products-section').html(html);
    
    // РЕНДЕРИМ КАРТОЧКИ
    let cards = '';
    products.forEach(product => {
        cards += renderPurchasedProductCard(product);
    });
    $('#purchased-products-grid').html(cards);
}

// РЕНДЕРИНГ КАРТОЧКИ КУПЛЕННОГО ТОВАРА - СЕРАЯ!
function renderPurchasedProductCard(product) {
    return `
        <div class="product-card purchased-card" data-id="${product.id}" style="
            opacity: 0.6; 
            filter: grayscale(1);
            border: 2px solid #6c757d;
            position: relative;
            background: #f8f9fa;
        ">
            <!-- БЕЙДЖ "КУПЛЕНО" -->
            <div style="position: absolute; top: 10px; left: 10px; background: #28a745; 
                        color: white; padding: 5px 12px; border-radius: 20px; 
                        font-size: 12px; font-weight: bold; z-index: 100; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                <i class="fas fa-check-circle"></i> КУПЛЕНО
            </div>
            
            <div class="product-image" style="position: relative;">
                ${product.main_image ? 
                    `<img src="${product.main_image}" style="width:100%;height:180px;object-fit:cover; filter: grayscale(100%);">` : 
                    `<div style="width:100%;height:180px;background:linear-gradient(135deg, #6c757d, #495057); display:flex;align-items:center;justify-content:center;color:#adb5bd;">
                        <i class="fas fa-box" style="font-size:48px;"></i>
                    </div>`
                }
                <!-- БОЛЬШАЯ НАДПИСЬ -->
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                            background: rgba(108, 117, 125, 0.9); color: white; padding: 8px 16px; 
                            border-radius: 25px; font-weight: bold; font-size: 14px; border: 1px solid white;">
                    <i class="fas fa-ban"></i> НЕТ В НАЛИЧИИ
                </div>
            </div>
            
            <div class="product-info">
                <h3 style="margin: 0 0 10px 0; color: #495057; font-size: 16px;">${product.title}</h3>
                <p style="color: #6c757d; font-size: 13px; margin-bottom: 15px;">${product.description || 'Товар куплен'}</p>
                
                <div style="background: #e9ecef; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #495057;">Цена покупки:</span>
                        <span style="color: #6c757d; font-weight: bold; text-decoration: line-through;">
                            ${product.price} ₽
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        <span style="color: #495057;">Дата:</span>
                        <span style="color: #6c757d;">${product.purchase_date || new Date().toLocaleDateString('ru-RU')}</span>
                    </div>
                </div>
                
                <div style="color: #6c757d; font-size: 12px; margin-bottom: 15px;">
                    <i class="fas fa-user"></i> ${product.seller_login} • 
                    <i class="fas fa-box"></i> 0 шт.
                </div>
                
                <button class="btn" style="width:100%; background: #6c757d; color: white; cursor: not-allowed; border: none;" disabled>
                    <i class="fas fa-ban"></i> НЕТ В НАЛИЧИИ
                </button>
            </div>
        </div>
    `;
}

function returnSoldOutProductToHome(productId) {
    console.log('🔄 Возвращаем товар #' + productId + ' в серой оболочке');
    
    // 📦 БЕРЕМ ДАННЫЕ ИЗ КЭША
    const product = window.purchasedProductsCache?.[productId] || {
        id: productId,
        title: `Товар #${productId}`,
        description: 'Куплен и оплачен',
        price: '0',
        seller: 'Пользователь',
        image: null
    };
    
    // 🎨 СОЗДАЕМ СЕРУЮ КАРТОЧКУ
    const soldOutCard = `
        <div class="product-card sold-out" data-id="${product.id}" style="
            opacity: 0.7; 
            filter: grayscale(0.9);
            border: 2px solid #6c757d;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        ">
            <!-- ВОДЯНОЙ ЗНАК "ПРОДАНО" -->
            <div style="position: absolute; top: 20px; left: -30px; background: #dc3545; 
                        color: white; padding: 5px 40px; transform: rotate(-45deg); 
                        font-weight: bold; font-size: 14px; z-index: 2000;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                🏷️ ПРОДАНО
            </div>
            
            <div class="product-image" style="position: relative; background: #2c3e50;">
                ${product.image ? 
                    `<img src="${product.image}" style="width:100%; height:200px; object-fit:cover; filter: grayscale(100%) brightness(0.7);">` : 
                    `<div style="width:100%; height:200px; background: linear-gradient(135deg, #4a5568, #2d3748); 
                                display: flex; align-items: center; justify-content: center; color: #a0aec0;">
                        <i class="fas fa-box" style="font-size: 64px; opacity: 0.5;"></i>
                    </div>`
                }
                
                <!-- КРУПНЫЙ БЕЙДЖ НЕТ В НАЛИЧИИ -->
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                            background: rgba(220, 53, 69, 0.95); color: white; padding: 15px 25px; 
                            border-radius: 50px; font-weight: 900; font-size: 18px; 
                            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                            border: 2px solid white;
                            z-index: 1000;
                            letter-spacing: 2px;
                            text-transform: uppercase;
                            backdrop-filter: blur(2px);">
                    <i class="fas fa-ban" style="margin-right: 10px;"></i> НЕТ В НАЛИЧИИ
                </div>
                
                <!-- ПРОЗРАЧНЫЙ СЛОЙ -->
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                            background: rgba(0,0,0,0.2); z-index: 500;"></div>
            </div>
            
            <div class="product-info" style="background: #f8f9fa;">
                <h3 class="product-title" style="color: #495057; font-size: 18px; margin-bottom: 10px;">
                    ${product.title}
                </h3>
                <p class="product-description" style="color: #6c757d; font-style: italic;">
                    <i class="fas fa-check-circle" style="color: #28a745;"></i> Товар куплен и оплачен
                </p>
                
                <div style="margin: 15px 0; padding: 12px; background: #e9ecef; border-radius: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #495057; font-weight: 600;">Цена при покупке:</span>
                        <span style="color: #6c757d; font-size: 22px; font-weight: bold; text-decoration: line-through;">
                            ${product.price} ₽
                        </span>
                    </div>
                </div>
                
                <div class="product-meta" style="color: #6c757d; border-top: 1px solid #dee2e6; padding-top: 15px;">
                    <span><i class="fas fa-user"></i> ${product.seller}</span>
                    <span><i class="fas fa-layer-group"></i> 0 шт.</span>
                    <span><i class="fas fa-clock"></i> ${new Date().toLocaleDateString('ru-RU')}</span>
                </div>
                
                <!-- КНОПКА НЕТ В НАЛИЧИИ -->
                <div style="margin-top: 20px;">
                    <button class="btn" style="width:100%; background: #6c757d; color: white; 
                            padding: 12px; border-radius: 8px; font-weight: 600; 
                            cursor: not-allowed; border: none; opacity: 0.9;" disabled>
                        <i class="fas fa-ban" style="margin-right: 8px;"></i> ТОВАР ПРОДАН
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Удаляем старую карточку и добавляем новую
    $(`.product-card[data-id="${productId}"]`).remove();
    
    if ($('#products-list').length) {
        $('#products-list').prepend(soldOutCard);
        
        // АНИМАЦИЯ
        $(`.product-card[data-id="${productId}"]`)
            .hide()
            .fadeIn(600)
            .css('transform', 'scale(0.9)')
            .animate({ transform: 'scale(1)' }, 400);
            
        showToast(`🔄 Товар "${product.title}" возвращен как ПРОДАННЫЙ`, 'info');
    }
}

function markProductAsSoldUI(productId) {
    let $card = $(`.product-card[data-id="${productId}"]`);
    if ($card.length) {
        $card.css({
            'border': '3px solid #dc3545',
            'opacity': '0.7',
            'filter': 'grayscale(0.8)',
            'position': 'relative'
        });
        
        if (!$card.find('.sold-badge').length) {
            $card.find('.product-image').append(
                '<div style="position:absolute; top:10px; left:10px; background:#dc3545; color:white; padding:8px 16px; border-radius:25px; font-weight:bold; font-size:14px; z-index:999;">🔴 ПРОДАНО</div>'
            );
        }
        
        $card.find('.btn-primary').replaceWith(
            '<button class="btn" style="flex:2; background:#6c757d; color:white;" disabled><i class="fas fa-ban"></i> ТОВАР ПРОДАН</button>'
        );
    }
}

function updateAllProductsStock(updatedProducts) {
    if (!updatedProducts || !updatedProducts.length) return;
    
    // Проходим по всем купленным товарам
    updatedProducts.forEach(product => {
        const productId = product.id;
        const newStock = product.stock;
        const $productCard = $(`.product-card[data-id="${productId}"]`);
        
        if ($productCard.length === 0) return;
        
        if (newStock <= 0) {
            // 1. Делаем карточку "неактивной"
            $productCard.css('opacity', '0.7').addClass('out-of-stock');
            $productCard.find('.product-image img').css('filter', 'grayscale(100%)');
            
            // 2. Добавляем бейдж "Нет в наличии"
            if (!$productCard.find('.stock-badge').length) {
                $productCard.find('.product-image').append(`
                    <div class="stock-badge" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); 
                                background: rgba(0,0,0,0.7); color: white; padding: 8px 16px; 
                                border-radius: 20px; font-weight: bold; font-size: 14px; z-index:20;
                                backdrop-filter: blur(4px);">
                        <i class="fas fa-ban"></i> Нет в наличии
                    </div>
                `);
            }
            
            // 3. Меняем кнопку "В корзину" на "Нет в наличии"
            const $actionButton = $productCard.find('.btn-primary, .btn:contains("В корзину")');
            if ($actionButton.length) {
                $actionButton.replaceWith(`
                    <button class="btn" style="flex: 2; background: #ccc; color: #666; 
                            cursor: not-allowed; border: 1px solid #999;" disabled>
                        <i class="fas fa-times-circle"></i> Нет в наличии
                    </button>
                `);
            }
            
            // 4. Обновляем количество в meta-информации
            $productCard.find('.product-meta span:contains("шт.")').html(`<i class="fas fa-layer-group"></i> 0 шт.`);
        } else {
            // Просто обновляем количество, если еще есть в наличии
            $productCard.find('.product-meta span:contains("шт.")').html(`<i class="fas fa-layer-group"></i> ${newStock} шт.`);
        }
    });
    
    showToast('✅ Заказ оформлен! Товары обновлены', 'success');
}

function showOrderSuccessModal(order) {
    const modalHtml = `
        <div class="modal" id="order-success-modal" style="display: flex;">
            <div class="modal-content" style="max-width: 600px; text-align: center;">
                <div style="padding: 40px;">
                    <div style="font-size: 80px; color: #4CAF50; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 style="color: #333; margin-bottom: 15px;">Заказ оформлен!</h2>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
                        <div style="font-size: 24px; font-weight: bold; color: #667eea; margin-bottom: 10px;">
                            № ${order.id || '0001'}
                        </div>
                        <div style="color: #666;">
                            Сумма: <strong>${order.total || 0} ₽</strong>
                        </div>
                        <div style="color: #666; margin-top: 5px;">
                            Статус: <span style="color: #4CAF50; font-weight: bold;">Оплачен</span>
                        </div>
                    </div>
                    <p style="color: #666; margin-bottom: 25px;">
                        Информация о заказе отправлена на ваш email. Вы можете отслеживать статус в личном кабинете.
                    </p>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <button class="btn btn-primary" onclick="closeModal('order-success-modal'); showHome();">
                            <i class="fas fa-home"></i> На главную
                        </button>
                        <button class="btn" onclick="closeModal('order-success-modal'); showMyOrders();" style="background: #667eea; color: white;">
                            <i class="fas fa-list"></i> Мои заказы
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Добавляем модалку в конец body
    $('body').append(modalHtml);
}

showToast(response.message, response.toast_type || 'info');


const forceToastPosition = () => {
    document.querySelectorAll('[class*="toast"], [class*="notification"]').forEach(toast => {
        toast.style.cssText = `
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            left: auto !important;
            bottom: auto !important;
            z-index: 99999 !important;
            background: #333 !important;
            color: white !important;
            padding: 15px !important;
            border-radius: 5px !important;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2) !important;
            max-width: 350px !important;
            min-width: 250px !important;
            font-size: 14px !important;
            font-family: sans-serif !important;
            line-height: 1.4 !important;
            word-wrap: break-word !important;
            white-space: normal !important;
            display: block !important;
        `;
    });
};


// Добавьте в начало $(document).ready()
$(document).ready(function() {
    // Проверяем, есть ли сохраненная сессия
    checkRememberedSession();
    checkLoginState();
    loadCartCount();
    loadNotifications();
    loadFavoritesCount();
    
    // ... остальной код
});

// Функция проверки запомненной сессии
function checkRememberedSession() {
    // Проверяем наличие сохраненного токена в куках
    $.get('api.php?action=check_remembered_session', function(response) {
        if (response.status === 'success' && response.user) {
            currentUser = response.user;
            showToast('Автоматический вход выполнен', 'success');
            
            // Обновляем интерфейс
            updateUserInterface();
            
            // Проверяем бан статус
            checkBanStatus();
        }
    });
}

// Функция обновления интерфейса пользователя
function updateUserInterface() {
    if (currentUser) {
        // Обновляем баланс
        $('#user-balance').text(currentUser.balance.toFixed(2));
        
        // Показываем кнопки пользователя
        $('.user-info').html(`
            <div class="balance">
                <i class="fas fa-wallet"></i> 
                <span id="user-balance">${currentUser.balance.toFixed(2)}</span> ₽
            </div>
            <div class="dropdown">
                <button class="btn btn-primary" onclick="toggleUserMenu()">
                    <i class="fas fa-user"></i> ${currentUser.username}
                    <i class="fas fa-chevron-down" style="margin-left: 5px; font-size: 12px;"></i>
                </button>
                <!-- Меню пользователя -->
            </div>
        `);
    }
}

$(document).ready(function() {
    // Исправляем цену при вводе
    $('#product-price').on('input', function() {
        let value = $(this).val();
        // Заменяем запятую на точку для корректного парсинга
        $(this).val(value.replace(',', '.'));
        // Пересчитываем скидку
        calculateDiscount();
    });
    
    // При изменении скидки
    $('#product-discount').on('input', function() {
        calculateDiscount();
    });
    
    // Инициализируем расчет
    calculateDiscount();
});

function smartSearch(query) {
    if (query.length < 2) return;
    
    $.ajax({
        url: 'api.php?action=search',
        type: 'GET',
        data: { query: query },
        success: function(response) {
            if (response.status === 'success') {
                displaySearchResults(response.results);
            }
        }
    });
}

// Вызывать при вводе в поисковую строку
$('#search-input').on('input', function() {
    smartSearch($(this).val());
});

// Или при отправке формы
$('#search-form').submit(function(e) {
    e.preventDefault();
    smartSearch($('#search-input').val());
});


// Добавьте в JavaScript секцию:

// Функция показа поиска пользователей
function showUserSearch() {
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h1 style="font-size:36px; color:#333; margin: 0;">
                    <i class="fas fa-search"></i> Поиск пользователей
                </h1>
                <button class="btn" onclick="showHome()" style="background: #667eea; color: white;">
                    <i class="fas fa-arrow-left"></i> Назад
                </button>
            </div>
            
            <!-- Поисковая строка -->
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="margin-bottom: 20px; color: #333;">Найти пользователя</h3>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="user-search-input" class="form-control" 
                           placeholder="Введите логин, username или ID пользователя..." 
                           style="flex: 1; padding: 15px; font-size: 16px;"
                           onkeyup="if(event.key === 'Enter') searchUsers()">
                    <button class="btn btn-primary" onclick="searchUsers()" style="padding: 15px 25px;">
                        <i class="fas fa-search"></i> Поиск
                    </button>
                </div>
                <div style="margin-top: 15px; font-size: 14px; color: #666;">
                    <i class="fas fa-info-circle"></i> Поиск по логину, имени пользователя или ID
                </div>
            </div>
            
            <!-- Результаты поиска -->
            <div id="user-search-results">
                <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 style="color: #666; margin-bottom: 15px;">Начните поиск</h3>
                    <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                        Введите данные пользователя в поле поиска выше, чтобы найти его профиль
                    </p>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
}

// Функция поиска пользователей
function searchUsers() {
    const searchTerm = $('#user-search-input').val().trim();
    
    if (!searchTerm) {
        showToast('Введите данные для поиска', 'warning');
        return;
    }
    
    // Показываем загрузку
    $('#user-search-results').html(`
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div class="loader" style="margin: 0 auto 20px;"></div>
            <p style="color: #666;">Ищем пользователей...</p>
        </div>
    `);
    
    $.ajax({
        url: 'api.php?action=search_users',
        method: 'GET',
        data: { 
            query: searchTerm,
            limit: 50
        },
        dataType: 'json',
        success: function(response) {
            console.log('Search results:', response);
            
            if (response.status === 'success') {
                if (response.users && response.users.length > 0) {
                    renderUserSearchResults(response.users);
                } else {
                    $('#user-search-results').html(`
                        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                            <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <h3 style="color: #666; margin-bottom: 15px;">Пользователи не найдены</h3>
                            <p style="color: #999; margin-bottom: 30px;">
                                По запросу "${searchTerm}" ничего не найдено. Попробуйте другой запрос.
                            </p>
                        </div>
                    `);
                }
            } else {
                showToast('Ошибка поиска: ' + (response.message || 'Неизвестная ошибка'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Search error:', error);
            showToast('Ошибка соединения при поиске', 'error');
        }
    });
}

// Функция рендеринга результатов поиска
function renderUserSearchResults(users) {
    let html = `
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">
                    Найдено пользователей: ${users.length}
                </h3>
                <div style="color: #666; font-size: 14px;">
                    <i class="fas fa-info-circle"></i> Нажмите на пользователя для просмотра
                </div>
            </div>
            
            <div class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
    `;
    
    users.forEach(user => {
        // Определяем иконку роли
        let roleIcon = user.role === 'admin' ? 
            '<i class="fas fa-crown" style="color: #9c27b0;"></i>' : 
            '<i class="fas fa-user" style="color: #4CAF50;"></i>';
        
        // Определяем статус
        let statusBadge = '';
        if (user.banned == 1) {
            statusBadge = '<span style="position: absolute; top: 10px; right: 10px; background: #ff4757; color: white; padding: 3px 8px; border-radius: 12px; font-size: 10px;">Забанен</span>';
        }
        
        html += `
            <div class="user-card" data-id="${user.id}" 
                 style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.3s;">
                <!-- Шапка карточки -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; text-align: center; position: relative;">
                    ${statusBadge}
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; 
                         margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 36px; color: white;">
                        ${roleIcon}
                    </div>
                    <h3 style="margin: 0; color: white; font-size: 20px;">${user.username || user.login}</h3>
                    <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.8); font-size: 14px;">@${user.login}</p>
                </div>
                
                <!-- Информация о пользователе -->
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #666; font-size: 14px;">ID:</span>
                            <span style="font-weight: bold; color: #333;">${user.id}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #666; font-size: 14px;">Роль:</span>
                            <span style="color: ${user.role === 'admin' ? '#9c27b0' : '#4CAF50'}; font-weight: bold;">
                                ${user.role === 'admin' ? 'Администратор' : 'Пользователь'}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #666; font-size: 14px;">Баланс:</span>
                            <span style="color: #4CAF50; font-weight: bold;">${parseFloat(user.balance).toFixed(2)} ₽</span>
                        </div>
                        ${user.email ? `
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: #666; font-size: 14px;">Email:</span>
                            <span style="color: #2196F3; font-size: 13px;">${user.email}</span>
                        </div>` : ''}
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #666; font-size: 14px;">Регистрация:</span>
                            <span style="color: #666; font-size: 12px;">
                                ${user.created_at ? new Date(user.created_at).toLocaleDateString('ru-RU') : '-'}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Кнопки действий -->
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button class="btn" onclick="viewUserProfile(${user.id})" 
                                style="flex: 1; padding: 10px; background: #2196F3; color: white; border-radius: 8px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-eye"></i> Профиль
                        </button>
                        <button class="btn" onclick="sendMessageToUser(${user.id}, '${escapeHtml(user.login)}')" 
                                style="flex: 1; padding: 10px; background: #9c27b0; color: white; border-radius: 8px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"
                                ${currentUser && currentUser.id === user.id ? 'disabled' : ''}>
                            <i class="fas fa-envelope"></i> Сообщение
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `</div></div>`;
    $('#user-search-results').html(html);
    
    // Добавляем hover эффект
    $('.user-card').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
}

function viewUserProfile(userId) {
    console.log('Просмотр профиля пользователя ID:', userId);
    
    if (!userId || isNaN(userId)) {
        showToast('Неверный ID пользователя', 'error');
        return;
    }
    
    // Загружаем информацию о пользователе
    $.ajax({
        url: 'api.php?action=get_user_profile',
        method: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            console.log('Ответ профиля:', response);
            
            if (response.status === 'success') {
                // ✅ СОХРАНЯЕМ ID В ГЛОБАЛЬНУЮ ПЕРЕМЕННУЮ
                window.currentProfileUserId = userId;
                renderUserProfile(response.user);
            } else {
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function(error) {
            console.error('Ошибка загрузки профиля:', error);
            showToast('Ошибка загрузки профиля', 'error');
        }
    });
}

// Отображение профиля пользователя
function renderUserProfile(user) {
    if (!user) {
        showToast('Данные пользователя не получены', 'error');
        return;
    }
    
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <button class="btn" onclick="showUserSearch()" style="background: #667eea; color: white;">
                    <i class="fas fa-arrow-left"></i> Назад к поиску
                </button>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="sendMessageToUser(${user.id}, '${user.login}')">
                        <i class="fas fa-comment"></i> Написать сообщение
                    </button>
                </div>
            </div>
            
            <!-- Профиль пользователя -->
            <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <div style="display: flex; align-items: center; gap: 25px; margin-bottom: 30px; flex-wrap: wrap;">
                    <!-- Аватар -->
                    <div style="position: relative;">
                        ${user.avatar ? 
                            `<img src="${user.avatar}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border: 4px solid #667eea;">` :
                            `<div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);
                                  display:flex;align-items:center;justify-content:center;color:white;font-size:36px;">
                                <i class="fas fa-user"></i>
                            </div>`
                        }
                        ${user.role === 'admin' ? 
                            `<div style="position: absolute; bottom: 5px; right: 5px; background: #9c27b0; color: white; 
                                  padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                <i class="fas fa-crown"></i> Админ
                            </div>` : ''
                        }
                    </div>
                    
                    <!-- Информация -->
                    <div style="flex: 1;">
                        <h1 style="font-size: 32px; color: #333; margin: 0 0 10px 0;">
                            ${user.username || user.login}
                            <small style="font-size: 16px; color: #666; margin-left: 10px;">
                                @${user.login}
                            </small>
                        </h1>
                        
                        <div style="display: flex; gap: 25px; flex-wrap: wrap; margin-top: 20px;">
                            <div style="text-align: center;">
                                <div style="font-size: 24px; font-weight: bold; color: #4CAF50;">
                                    <i class="fas fa-wallet"></i> ${parseFloat(user.balance).toFixed(2)} ₽
                                </div>
                                <div style="font-size: 13px; color: #666;">Баланс</div>
                            </div>
                            
                            <div style="text-align: center;">
                                <div style="font-size: 24px; font-weight: bold; color: #2196F3;">
                                    <i class="fas fa-shopping-bag"></i> <span id="user-products-count">0</span>
                                </div>
                                <div style="font-size: 13px; color: #666;">Товары</div>
                            </div>
                            
                            <div style="text-align: center;">
                                <div style="font-size: 24px; font-weight: bold; color: #9c27b0;">
                                    <i class="fas fa-star"></i> ${user.rating || '0.0'}
                                </div>
                                <div style="font-size: 13px; color: #666;">Рейтинг</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Дополнительная информация -->
                <div style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-top: 20px;">
                    <h3 style="margin: 0 0 15px 0; color: #333;">
                        <i class="fas fa-info-circle"></i> Информация
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                        <div>
                            <strong style="color: #666; display: block; font-size: 14px;">ID пользователя</strong>
                            <span style="color: #333; font-weight: bold;">${user.id}</span>
                        </div>
                        <div>
                            <strong style="color: #666; display: block; font-size: 14px;">Роль</strong>
                            <span style="color: ${user.role === 'admin' ? '#9c27b0' : '#4CAF50'}; font-weight: bold;">
                                ${user.role === 'admin' ? 'Администратор' : 'Пользователь'}
                            </span>
                        </div>
                        <div>
                            <strong style="color: #666; display: block; font-size: 14px;">Регистрация</strong>
                            <span style="color: #333;">
                                ${user.created_at ? new Date(user.created_at).toLocaleDateString('ru-RU') : 'Неизвестно'}
                            </span>
                        </div>
                        <div>
                            <strong style="color: #666; display: block; font-size: 14px;">Статус</strong>
                            <span style="color: ${user.banned == 1 ? '#f44336' : '#4CAF50'}; font-weight: bold;">
                                ${user.banned == 1 ? 'Заблокирован' : 'Активен'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Товары пользователя -->
            <div id="user-products-container">
                <h2 style="margin-bottom: 20px; color: #333;">
                    <i class="fas fa-box"></i> Товары пользователя
                </h2>
                <div style="text-align: center; padding: 40px;">
                    <div class="loader"></div>
                    <p style="color: #666; margin-top: 20px;">Загрузка товаров...</p>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    
    // Загружаем товары пользователя
    loadUserProducts(user.id);
}



function showUserProfileModal(user) {
    let modalHtml = `
        <div id="user-profile-modal" class="modal" style="display: block; padding: 20px;">
            <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
                <!-- Шапка профиля -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 30px; border-radius: 15px 15px 0 0; position: relative;">
                    <button class="close-modal" onclick="closeUserProfileModal()" 
                            style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        &times;
                    </button>
                    
                    <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 30px;">
                        <div style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; 
                             display: flex; align-items: center; justify-content: center; font-size: 60px; color: white; flex-shrink: 0;">
                            ${user.role === 'admin' ? '<i class="fas fa-crown"></i>' : '<i class="fas fa-user"></i>'}
                        </div>
                        
                        <div style="flex: 1;">
                            <h1 style="margin: 0 0 10px 0; font-size: 36px;">${user.username || user.login}</h1>
                            <p style="margin: 0 0 15px 0; color: rgba(255,255,255,0.9); font-size: 18px;">
                                <i class="fas fa-at"></i> @${user.login}
                            </p>
                            
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                ${user.banned == 1 ? `
                                    <div style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-ban"></i> Заблокирован
                                    </div>
                                ` : ''}
                                
                                <div style="background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 20px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-user-tag"></i> ${user.role === 'admin' ? 'Администратор' : 'Пользователь'}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Статистика -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-top: 30px;">
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 12px; text-align: center; backdrop-filter: blur(10px);">
                            <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px;" id="user-products-count">0</div>
                            <div style="font-size: 14px;">Товаров</div>
                        </div>
                        
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 12px; text-align: center; backdrop-filter: blur(10px);">
                            <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px;" id="user-orders-count">0</div>
                            <div style="font-size: 14px;">Продаж</div>
                        </div>
                        
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 12px; text-align: center; backdrop-filter: blur(10px);">
                            <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px;">${parseFloat(user.balance).toFixed(2)}</div>
                            <div style="font-size: 14px;">Баланс (₽)</div>
                        </div>
                        
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 12px; text-align: center; backdrop-filter: blur(10px);">
                            <div style="font-size: 32px; font-weight: bold; margin-bottom: 5px;" id="user-rating">-</div>
                            <div style="font-size: 14px;">Рейтинг</div>
                        </div>
                    </div>
                </div>
                
                <!-- Основная информация -->
                <div style="padding: 40px 30px;">
                    <!-- Вкладки -->
                    <div style="display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                        <button class="profile-tab active" onclick="switchProfileTab('info', ${user.id})" 
                                style="padding: 12px 25px; background: #667eea; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-info-circle"></i> Информация
                        </button>
                        <button class="profile-tab" onclick="switchProfileTab('products', ${user.id})" 
                                style="padding: 12px 25px; background: #f8f9fa; color: #333; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-box"></i> Товары
                        </button>
                        <button class="profile-tab" onclick="switchProfileTab('reviews', ${user.id})" 
                                style="padding: 12px 25px; background: #f8f9fa; color: #333; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-star"></i> Отзывы
                        </button>
                    </div>
                    
                    <!-- Контент вкладок -->
                    <div id="profile-tab-content">
                        <!-- Вкладка "Информация" (по умолчанию) -->
                        <div id="profile-tab-info" class="profile-tab-pane active">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                                <div>
                                    <h3 style="margin-bottom: 20px; color: #333; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-id-card" style="color: #667eea;"></i> Основные данные
                                    </h3>
                                    
                                    <div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin-bottom: 20px;">
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                            <div>
                                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">ID пользователя</div>
                                                <div style="font-weight: bold; color: #333; font-size: 18px;">#${user.id}</div>
                                            </div>
                                            <div style="width: 40px; height: 40px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-hashtag" style="color: #2196F3;"></i>
                                            </div>
                                        </div>
                                        
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                            <div>
                                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">Дата регистрации</div>
                                                <div style="font-weight: bold; color: #333;">${user.created_at ? new Date(user.created_at).toLocaleDateString('ru-RU', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'}</div>
                                            </div>
                                            <div style="width: 40px; height: 40px; background: #f3e5f5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-calendar-alt" style="color: #9c27b0;"></i>
                                            </div>
                                        </div>
                                        
                                        ${user.ban_reason ? `
                                        <div style="background: #fff5f5; padding: 15px; border-radius: 10px; margin-top: 15px; border-left: 4px solid #ff4757;">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <i class="fas fa-exclamation-triangle" style="color: #ff4757;"></i>
                                                <div style="font-weight: bold; color: #721c24;">Аккаунт заблокирован</div>
                                            </div>
                                            <div style="color: #333; margin-bottom: 5px;">
                                                <strong>Причина:</strong> ${user.ban_reason}
                                            </div>
                                            ${user.ban_expires ? `
                                                <div style="color: #666; font-size: 14px;">
                                                    <strong>До:</strong> ${new Date(user.ban_expires).toLocaleDateString('ru-RU')}
                                                </div>
                                            ` : ''}
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 style="margin-bottom: 20px; color: #333; display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-chart-line" style="color: #4CAF50;"></i> Статистика
                                    </h3>
                                    
                                    <div style="background: #f8f9fa; padding: 25px; border-radius: 15px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                            <div>
                                                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Баланс</div>
                                                <div style="font-size: 32px; font-weight: bold; color: #4CAF50;">
                                                    ${parseFloat(user.balance).toFixed(2)} ₽
                                                </div>
                                            </div>
                                            <div style="width: 50px; height: 50px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-wallet" style="color: #4CAF50; font-size: 24px;"></i>
                                            </div>
                                        </div>
                                        
                                        <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                <span style="color: #666;">Активных товаров:</span>
                                                <span style="font-weight: bold; color: #333;" id="active-products-count">0</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                <span style="color: #666;">Всего продаж:</span>
                                                <span style="font-weight: bold; color: #333;" id="total-sales-count">0</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span style="color: #666;">Положительных отзывов:</span>
                                                <span style="font-weight: bold; color: #333;" id="positive-reviews-count">0</span>
                                            </div>
                                        </div>
                                        
                                        <div style="background: #e8f4ff; padding: 15px; border-radius: 10px;">
                                            <div style="font-size: 14px; color: #1565C0; margin-bottom: 8px;">
                                                <i class="fas fa-clock"></i> На сайте с ${user.created_at ? new Date(user.created_at).toLocaleDateString('ru-RU', {month: 'long', year: 'numeric'}) : ''}
                                            </div>
                                            <div style="font-size: 12px; color: #666;">
                                                ${user.created_at ? `Зарегистрирован ${new Date(user.created_at).toLocaleTimeString('ru-RU', {hour: '2-digit', minute: '2-digit'})}` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Контактная информация -->
                            ${user.email || user.phone ? `
                            <div style="margin-top: 30px;">
                                <h3 style="margin-bottom: 20px; color: #333; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-address-book" style="color: #2196F3;"></i> Контактная информация
                                </h3>
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                    ${user.email ? `
                                    <div style="background: #e3f2fd; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                                        <div style="width: 45px; height: 45px; background: #2196F3; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 12px; color: #1565C0; margin-bottom: 3px;">Email</div>
                                            <div style="font-weight: bold; color: #333;">${user.email}</div>
                                        </div>
                                    </div>
                                    ` : ''}
                                    
                                    ${user.phone ? `
                                    <div style="background: #e8f5e9; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                                        <div style="width: 45px; height: 45px; background: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 12px; color: #2E7D32; margin-bottom: 3px;">Телефон</div>
                                            <div style="font-weight: bold; color: #333;">${user.phone}</div>
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        
                        <!-- Вкладка "Товары" -->
                        <div id="profile-tab-products" class="profile-tab-pane" style="display: none;">
                            <div id="user-products-container">
                                <div style="text-align: center; padding: 40px 20px;">
                                    <div class="loader" style="margin: 0 auto 20px;"></div>
                                    <p style="color: #666;">Загрузка товаров пользователя...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Вкладка "Отзывы" -->
                        <div id="profile-tab-reviews" class="profile-tab-pane" style="display: none;">
                            <div id="user-reviews-container">
                                <div style="text-align: center; padding: 40px 20px;">
                                    <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h3 style="color: #666; margin-bottom: 10px;">Отзывов пока нет</h3>
                                    <p style="color: #999;">
                                        У этого пользователя еще нет отзывов
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Кнопки действий -->
                <div style="padding: 25px 30px; border-top: 1px solid #eee; background: #fafafa; border-radius: 0 0 15px 15px;">
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        ${currentUser && currentUser.id !== user.id ? `
                        <button class="btn" onclick="sendMessageToUser(${user.id}, '${escapeHtml(user.login)}')" 
                                style="padding: 15px 30px; background: #9c27b0; color: white; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-envelope"></i> Написать сообщение
                        </button>` : ''}
                        
                        ${currentUser && currentUser.id !== user.id ? `
                        <button class="btn" onclick="showUserProducts(${user.id})" 
                                style="padding: 15px 30px; background: #ff9800; color: white; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-shopping-bag"></i> Товары пользователя
                        </button>` : ''}
                        
                        ${currentUser && currentUser.role === 'admin' && currentUser.id !== user.id ? `
                        <button class="btn" onclick="adminActionsForUser(${user.id})" 
                                style="padding: 15px 30px; background: #2196F3; color: white; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-cog"></i> Админ-панель
                        </button>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
    
    // Загружаем статистику и товары пользователя
    loadUserStats(user.id);
    loadUserProducts(user.id);
}

// Переключение вкладок профиля
function switchProfileTab(tabName, userId) {
    // Убираем активный класс у всех вкладок
    $('.profile-tab').removeClass('active').css({
        'background': '#f8f9fa',
        'color': '#333'
    });
    
    // Скрываем все панели
    $('.profile-tab-pane').hide();
    
    // Активируем выбранную вкладку
    $(`.profile-tab:contains(${getTabName(tabName)})`).addClass('active').css({
        'background': '#667eea',
        'color': 'white'
    });
    
    // Показываем выбранную панель
    $(`#profile-tab-${tabName}`).show();
    
    // Загружаем контент если нужно
    if (tabName === 'products') {
        loadUserProducts(userId);
    } else if (tabName === 'reviews') {
        loadUserReviews(userId);
    }
}

function getTabName(tabKey) {
    const tabs = {
        'info': 'Информация',
        'products': 'Товары',
        'reviews': 'Отзывы'
    };
    return tabs[tabKey] || tabKey;
}
// Загрузка статистики пользователя
function loadUserStats(userId) {
    $.ajax({
        url: 'api.php?action=get_user_stats',
        method: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                updateUserStats(response.stats);
            }
        }
    });
}

// Обновление статистики в модальном окне
function updateUserStats(stats) {
    $('#user-products-count').text(stats.products_count || 0);
    $('#user-orders-count').text(stats.orders_count || 0);
    $('#user-rating').text(stats.rating ? stats.rating.toFixed(1) + '★' : '-');
    $('#active-products-count').text(stats.active_products || 0);
    $('#total-sales-count').text(stats.total_sales || 0);
    $('#positive-reviews-count').text(stats.positive_reviews || 0);
}



// ========== ЗАГРУЗКА ТОВАРОВ ПОЛЬЗОВАТЕЛЯ ==========
function loadUserProducts(userId) {
    console.log('📥 Загрузка товаров пользователя ID:', userId);
    
    const container = $('#user-products-container');
    
    if (!container.length) {
        console.error('❌ Контейнер user-products-container не найден');
        return;
    }
    
    container.html(`
        <div style="text-align: center; padding: 40px;">
            <div class="loader" style="margin: 0 auto 20px;"></div>
            <p style="color: #666;">Загрузка товаров...</p>
        </div>
    `);
    
    $.ajax({
        url: 'api.php?action=get_user_products',
        type: 'GET',
        data: { 
            user_id: userId,
            status: 'active' // Только активные товары
        },
        dataType: 'json',
        success: function(response) {
            console.log('✅ Товары пользователя:', response);
            
            if (response.status === 'success') {
                if (response.products && response.products.length > 0) {
                    renderUserProducts(response.products);
                    $('#user-products-count').text(response.products.length);
                } else {
                    container.html(`
                        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px;">
                            <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3 style="color: #666; margin-bottom: 10px;">У пользователя пока нет товаров</h3>
                            <p style="color: #999;">Здесь будут отображаться товары, выставленные на продажу</p>
                        </div>
                    `);
                    $('#user-products-count').text('0');
                }
            } else {
                container.html(`
                    <div style="text-align: center; padding: 40px; color: #f44336;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                        <p style="margin-top: 20px;">${response.message || 'Ошибка загрузки товаров'}</p>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('❌ Ошибка AJAX:', xhr.responseText);
            container.html(`
                <div style="text-align: center; padding: 40px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                    <p style="margin-top: 20px;">Ошибка соединения с сервером</p>
                </div>
            `);
        }
    });
}


// ========== ОТОБРАЖЕНИЕ ТОВАРОВ ПОЛЬЗОВАТЕЛЯ ==========
function renderUserProducts(products) {
    let html = `
        <div style="margin-bottom: 25px;">
            <h3 style="margin: 0; color: #333;">Товары пользователя (${products.length})</h3>
        </div>
        <div class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
    `;
    
    products.forEach(product => {
        let finalPrice = product.discount > 0 
            ? product.price * (1 - product.discount / 100) 
            : product.price;
        
        let stockStatus = product.stock > 0 
            ? `<span style="color: #4CAF50;"><i class="fas fa-check-circle"></i> В наличии: ${product.stock} шт.</span>`
            : `<span style="color: #f44336;"><i class="fas fa-times-circle"></i> Нет в наличии</span>`;
        
        html += `
            <div class="product-card" data-id="${product.id}" style="cursor: pointer;" onclick="viewProduct(${product.id})">
                <div class="product-image" style="height: 200px; overflow: hidden;">
                    ${product.main_image 
                        ? `<img src="${product.main_image}" alt="${product.title}" style="width:100%; height:100%; object-fit:cover;">`
                        : `<div style="width:100%; height:100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-box"></i>
                           </div>`
                    }
                </div>
                
                <div class="product-info" style="padding: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px;">${product.title}</h4>
                    
                    <p style="color: #666; margin-bottom: 15px; font-size: 14px; line-height: 1.4;">
                        ${product.description ? product.description.substring(0, 100) + (product.description.length > 100 ? '...' : '') : 'Нет описания'}
                    </p>
                    
                    <div style="margin-bottom: 15px;">
                        <span style="color: #4CAF50; font-size: 24px; font-weight: bold;">
                            ${finalPrice.toFixed(2)} ₽
                        </span>
                        ${product.discount > 0 ? 
                            `<span style="background: #ff4757; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; margin-left: 10px;">
                                -${product.discount}%
                            </span>` : ''
                        }
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; color: #666; font-size: 14px; margin-bottom: 15px;">
                        <span><i class="fas fa-user"></i> ${product.seller_login || 'Продавец'}</span>
                        ${stockStatus}
                    </div>
                    
                    <button class="btn btn-primary" style="width:100%; margin-top: 15px; padding: 12px;" onclick="addToCart(${product.id}); event.stopPropagation();">
                        <i class="fas fa-cart-plus"></i> В корзину
                    </button>
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    $('#user-products-container').html(html);
}

// Просмотр товаров пользователя на отдельной странице
function showUserProducts(userId) {
    // Закрываем модальное окно профиля
    closeUserProfileModal();
    
    // Показываем загрузку
    $('#content').html(`
        <div style="margin-top:30px;">
            <div style="text-align: center; padding: 60px 20px;">
                <div class="loader" style="margin: 0 auto 20px;"></div>
                <p style="color: #666;">Загрузка товаров пользователя...</p>
            </div>
        </div>
    `);
    
    // Загружаем товары пользователя
    $.ajax({
        url: 'api.php?action=get_user_products',
        method: 'GET',
        data: { user_id: userId, limit: 50 },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.products && response.products.length > 0) {
                showUserProductsPage(userId, response.products);
            } else {
                showEmptyUserProducts(userId);
            }
        },
        error: function() {
            showEmptyUserProducts(userId);
        }
    });
}
// Функция закрытия модального окна профиля
function closeUserProfileModal() {
    $('#user-profile-modal').remove();
}

function sendMessageToUser(userId, userLogin) {
    if (currentUser && currentUser.id === userId) {
        showToast('Вы не можете написать сообщение самому себе', 'warning');
        return;
    }
    
    // Показываем модальное окно для отправки сообщения
    let modalHtml = `
        <div id="send-message-modal" class="modal" style="display: block;">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-envelope"></i> Сообщение для @${userLogin}
                    </h3>
                    <button class="close-modal" onclick="$('#send-message-modal').remove()">&times;</button>
                </div>
                
                <div style="padding: 20px;">
                    <div class="form-group">
                        <label class="form-label">Сообщение*</label>
                        <textarea id="message-text" class="form-control" rows="5" placeholder="Введите ваше сообщение..." required></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; margin-top: 25px;">
                        <button class="btn btn-primary" onclick="submitMessageToUser(${userId})" style="flex: 1; padding: 12px;">
                            <i class="fas fa-paper-plane"></i> Отправить
                        </button>
                        <button class="btn" onclick="$('#send-message-modal').remove()" style="background: #6c757d; color: white; flex: 1;">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
}

// НОВАЯ ФУНКЦИЯ для отправки сообщения через модалку
function submitMessageToUser(userId) {
    const messageInput = $('#message-text');
    const message = messageInput.val().trim();
    
    console.log('Отправка сообщения пользователю ID:', userId);
    console.log('Текст сообщения:', message);
    
    if (!message) {
        showToast('Введите сообщение', 'warning');
        messageInput.focus();
        return;
    }
    
    // Показываем загрузку
    const sendButton = $('#send-message-modal .btn-primary');
    const originalHtml = sendButton.html();
    sendButton.html('<i class="fas fa-spinner fa-spin"></i>');
    sendButton.prop('disabled', true);
    
    $.ajax({
        url: 'api.php',
        method: 'POST',
        data: {
            action: 'send_message',
            receiver_id: userId,
            message: message
        },
        dataType: 'json',
        success: function(response) {
            console.log('Ответ сервера:', response);
            
            // Восстанавливаем кнопку
            sendButton.html(originalHtml);
            sendButton.prop('disabled', false);
            
            if (response.status === 'success') {
                // Закрываем модалку
                $('#send-message-modal').remove();
                
                // Показываем уведомление
                showToast('Сообщение отправлено!', 'success');
                
                // Обновляем диалоги если открыты
                if (typeof loadDialogs === 'function') {
                    setTimeout(loadDialogs, 500);
                }
                
                // Автоматически открываем диалог с пользователем
                if (typeof openDialog === 'function') {
                    // Получаем логин пользователя из модалки
                    const modalTitle = $('#send-message-modal .modal-title').text();
                    const match = modalTitle.match(/@(\w+)/);
                    const userLogin = match ? match[1] : '';
                    
                    if (userLogin) {
                        showMyMessages();
                        setTimeout(() => {
                            openDialog(userId, userLogin, userLogin);
                        }, 300);
                    }
                }
                
            } else {
                showToast('Ошибка: ' + (response.message || 'Неизвестная ошибка'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Ошибка AJAX:', xhr.responseText);
            
            // Восстанавливаем кнопку
            sendButton.html(originalHtml);
            sendButton.prop('disabled', false);
            
            showToast('Ошибка соединения', 'error');
        }
    });
}

// Упрощенная функция отправки (если не работает AJAX)
function submitMessageToUserSimple(userId) {
    const message = $('#message-text').val().trim();
    
    if (!message) {
        showToast('Введите сообщение', 'warning');
        return;
    }
    
    // Простой POST запрос
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=send_message&receiver_id=${userId}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            $('#send-message-modal').remove();
            showToast('✓ Сообщение отправлено', 'success');
        } else {
            showToast('✗ ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Ошибка сети', 'error');
    });
}
// Функция отправки приватного сообщения
function sendPrivateMessage(userId) {
    const subject = $('#message-subject').val().trim();
    const message = $('#message-text').val().trim();
    
    if (!message) {
        showToast('Введите текст сообщения', 'warning');
        return;
    }
    
    $.ajax({
        url: 'api.php?action=send_private_message',
        method: 'POST',
        data: {
            to_user_id: userId,
            subject: subject,
            message: message
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showToast('Сообщение отправлено!', 'success');
                $('#send-message-modal').remove();
                closeUserProfileModal();
            } else {
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('Ошибка соединения', 'error');
        }
    });
}

// Функция действий администратора для пользователя
function adminActionsForUser(userId) {
    // Закрываем текущее модальное окно
    closeUserProfileModal();
    
    // Показываем меню админ-действий
    let modalHtml = `
        <div id="admin-actions-modal" class="modal" style="display: block;">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-cog"></i> Админ-действия
                    </h3>
                    <button class="close-modal" onclick="$('#admin-actions-modal').remove()">&times;</button>
                </div>
                
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 10px;">
                        <button class="btn" onclick="editUserBalance(${userId})" style="text-align: left; padding: 15px; background: #e3f2fd; color: #1565C0; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-wallet"></i> Изменить баланс
                        </button>
                        
                        <button class="btn" onclick="givePromoToUser(${userId})" style="text-align: left; padding: 15px; background: #f3e5f5; color: #7b1fa2; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-gift"></i> Выдать промокод
                        </button>
                        
                        <button class="btn" onclick="banUser(${userId})" style="text-align: left; padding: 15px; background: #ffebee; color: #c62828; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-ban"></i> Забанить пользователя
                        </button>
                        
                        <button class="btn" onclick="viewUserLogs(${userId})" style="text-align: left; padding: 15px; background: #fff3e0; color: #ef6c00; border: none; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-history"></i> Просмотреть логи
                        </button>
                    </div>
                    
                    <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                        <button class="btn" onclick="$('#admin-actions-modal').remove()" style="width: 100%; padding: 12px; background: #6c757d; color: white;">
                            Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
}

// Функция для экранирования HTML (защита от XSS)
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}


// Добавьте в JavaScript секцию:

// Глобальные переменные для сообщений
let currentConversation = null;
let messagePollingInterval = null;

// Функция показа страницы сообщений
function showMessages() {
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h1 style="font-size:36px; color:#333; margin: 0;">
                    <i class="fas fa-comments"></i> Мои сообщения
                </h1>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="startNewConversation()">
                        <i class="fas fa-plus"></i> Новое сообщение
                    </button>
                    <button class="btn" onclick="showHome()" style="background: #667eea; color: white;">
                        <i class="fas fa-arrow-left"></i> Назад
                    </button>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 350px 1fr; gap: 30px; height: 70vh;">
                <!-- Список диалогов -->
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #eee;">
                        <h3 style="margin: 0 0 15px 0; color: #333;">Диалоги</h3>
                        <div style="position: relative;">
                            <input type="text" id="search-conversations" class="form-control" 
                                   placeholder="Поиск диалогов..." 
                                   style="padding-left: 40px;"
                                   onkeyup="filterConversations()">
                            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #666;"></i>
                        </div>
                    </div>
                    
                    <div id="conversations-list" style="flex: 1; overflow-y: auto; padding: 10px 0;">
                        <div style="text-align: center; padding: 40px 20px; color: #666;">
                            <div class="loader" style="margin: 0 auto 20px;"></div>
                            <p>Загрузка диалогов...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Окно сообщений -->
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden;">
                    <div id="no-conversation-selected" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center;">
                        <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <h3 style="color: #666; margin-bottom: 15px;">Выберите диалог</h3>
                        <p style="color: #999; margin-bottom: 30px; max-width: 400px;">
                            Выберите диалог из списка слева или начните новый разговор
                        </p>
                        <button class="btn btn-primary" onclick="startNewConversation()">
                            <i class="fas fa-plus"></i> Начать новый диалог
                        </button>
                    </div>
                    
                    <div id="conversation-container" style="flex: 1; display: none; flex-direction: column;">
                        <!-- Шапка диалога -->
                        <div id="conversation-header" style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; color: #333;" id="conversation-user-name">Имя пользователя</h4>
                                    <p style="margin: 3px 0 0 0; color: #666; font-size: 12px;" id="conversation-user-login">@login</p>
                                </div>
                            </div>
                            <div>
                                <button class="btn" onclick="viewUserProfileFromConversation()" style="background: #2196F3; color: white; padding: 8px 15px; font-size: 12px;">
                                    <i class="fas fa-user"></i> Профиль
                                </button>
                            </div>
                        </div>
                        
                        <!-- Сообщения -->
                        <div id="messages-container" style="flex: 1; overflow-y: auto; padding: 20px; background: #fafafa; background-image: radial-gradient(#e1e8ed 1px, transparent 1px); background-size: 20px 20px;">
                            <!-- Сообщения будут здесь -->
                        </div>
                        
                        <!-- Форма отправки -->
                        <div style="padding: 20px; border-top: 1px solid #eee;">
                            <form id="send-message-form" onsubmit="return false;">
                                <input type="hidden" id="conversation-user-id">
                                <div style="display: flex; gap: 10px;">
                                    <textarea id="message-text-input" class="form-control" 
                                              placeholder="Введите сообщение..." 
                                              rows="2"
                                              style="flex: 1; resize: none;"
                                              onkeydown="handleMessageKeydown(event)"></textarea>
                                    <button type="submit" class="btn btn-primary" style="align-self: flex-end; padding: 12px 20px;">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                                    <div style="font-size: 12px; color: #666;">
                                        <i class="fas fa-info-circle"></i> Нажмите Enter для отправки, Shift+Enter для новой строки
                                    </div>
                                    <div style="font-size: 12px; color: #666;" id="message-length">0/1000</div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    loadConversations();
    setupMessageForm();
    startMessagePolling();
}

function loadConversations() {
    console.log('Loading conversations...'); // Для отладки
    
    $.ajax({
        url: 'api.php?action=get_conversations',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Conversations API response:', response); // Для отладки
            
            if (response.status === 'success') {
                // Проверяем, что данные есть
                if (response.conversations && Array.isArray(response.conversations)) {
                    console.log('Found conversations:', response.conversations.length);
                    renderConversationsList(response.conversations);
                    updateUnreadCount(response.unread_count || 0);
                } else {
                    console.log('No conversations array in response');
                    renderConversationsList([]);
                }
            } else {
                console.error('API error:', response.message);
                showEmptyConversationsList();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            showEmptyConversationsList();
        }
    });
}

function showEmptyConversationsList() {
    $('#conversations-list').html(`
        <div style="text-align: center; padding: 40px 20px; color: #666;">
            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f44336; margin-bottom: 20px;"></i>
            <p>Ошибка загрузки диалогов</p>
            <button class="btn btn-primary" onclick="loadConversations()">
                <i class="fas fa-sync-alt"></i> Повторить
            </button>
        </div>
    `);
}

// Упрощенная функция рендеринга диалогов
function renderConversationsList(conversations) {
    if (!conversations || conversations.length === 0) {
        $('#conversations-list').html(`
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                    <i class="fas fa-comments"></i>
                </div>
                <h4 style="color: #666; margin-bottom: 10px;">Диалогов нет</h4>
                <p style="color: #999; margin-bottom: 20px;">
                    Начните новый диалог, чтобы общаться с другими пользователями
                </p>
                <button class="btn btn-primary" onclick="startNewConversation()">
                    <i class="fas fa-plus"></i> Начать диалог
                </button>
            </div>
        `);
        return;
    }
    
    let html = '';
    
    conversations.forEach(conversation => {
        // Проверяем, что данные существуют
        if (!conversation || !conversation.user_id) return;
        
        const lastMessage = conversation.last_message || 'Нет сообщений';
        const lastMessageTime = conversation.last_message_time ? 
            formatMessageTime(conversation.last_message_time) : 'Нет';
        const unreadCount = conversation.unread_count || 0;
        const userName = conversation.username || conversation.login || 'Пользователь';
        const userLogin = conversation.login || 'user';
        
        html += `
            <div class="conversation-item" 
                 data-user-id="${conversation.user_id}"
                 data-user-login="${userLogin}"
                 data-user-name="${userName}"
                 onclick="openConversation(${conversation.user_id}, '${escapeHtml(userLogin)}', '${escapeHtml(userName)}')"
                 style="padding: 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.2s;"
                 onmouseover="this.style.background='#f8f9fa'" 
                 onmouseout="this.style.background='white'">
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea, #764ba2); 
                                border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-user"></i>
                    </div>
                    
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                            <div style="font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${userName}
                            </div>
                            <div style="font-size: 11px; color: #999;">
                                ${lastMessageTime}
                            </div>
                        </div>
                        
                        <div style="font-size: 13px; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${lastMessage.substring(0, 50)}${lastMessage.length > 50 ? '...' : ''}
                        </div>
                        
                        ${unreadCount > 0 ? `
                            <div style="margin-top: 5px;">
                                <span style="background: #ff4757; color: white; font-size: 11px; padding: 2px 8px; border-radius: 10px;">
                                    ${unreadCount} новое
                                </span>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#conversations-list').html(html);
}


// Функция рендеринга списка диалогов
function renderConversationsList(conversations) {
    if (!conversations || conversations.length === 0) {
        $('#conversations-list').html(`
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                    <i class="fas fa-comments"></i>
                </div>
                <h4 style="color: #666; margin-bottom: 10px;">Диалогов нет</h4>
                <p style="color: #999; margin-bottom: 20px;">
                    Начните новый диалог, чтобы общаться с другими пользователями
                </p>
                <button class="btn btn-primary" onclick="startNewConversation()">
                    <i class="fas fa-plus"></i> Начать диалог
                </button>
            </div>
        `);
        return;
    }
    
    let html = '';
    
    conversations.forEach(conversation => {
        const lastMessage = conversation.last_message || '';
        const lastMessageTime = conversation.last_message_time ? 
            formatMessageTime(conversation.last_message_time) : '';
        const unreadCount = conversation.unread_count || 0;
        const isActive = currentConversation && currentConversation.id === conversation.user_id;
        
        html += `
            <div class="conversation-item ${isActive ? 'active' : ''}" 
                 data-user-id="${conversation.user_id}"
                 data-user-login="${conversation.login}"
                 data-user-name="${conversation.username || conversation.login}"
                 onclick="openConversation(${conversation.user_id}, '${escapeHtml(conversation.login)}', '${escapeHtml(conversation.username || conversation.login)}')"
                 style="padding: 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.2s; position: relative;"
                 onmouseover="this.style.background='#f8f9fa'" 
                 onmouseout="this.style.background='${isActive ? '#f0f4ff' : 'white'}'">
                
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                        <i class="fas fa-user"></i>
                    </div>
                    
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px;">
                            <div style="font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${conversation.username || conversation.login}
                            </div>
                            <div style="font-size: 11px; color: #999; flex-shrink: 0;">
                                ${lastMessageTime}
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 13px; color: #666; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">
                                ${lastMessage}
                            </div>
                            
                            ${unreadCount > 0 ? `
                                <div style="background: #ff4757; color: white; font-size: 11px; font-weight: bold; 
                                            width: 20px; height: 20px; border-radius: 50%; display: flex; 
                                            align-items: center; justify-content: center; flex-shrink: 0; margin-left: 8px;">
                                    ${unreadCount}
                                </div>
                            ` : ''}
                        </div>
                        
                        <div style="font-size: 11px; color: #999; margin-top: 3px;">
                            @${conversation.login}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#conversations-list').html(html);
}

function openConversation(userId, userLogin, userName) {
    console.log('Opening conversation with:', userId, userLogin); // Для отладки
    
    currentConversation = {
        id: userId,
        login: userLogin,
        name: userName
    };
    
    // Показываем контейнер диалога
    $('#no-conversation-selected').hide();
    $('#conversation-container').show();
    
    // Обновляем заголовок
    $('#conversation-user-name').text(userName);
    $('#conversation-user-login').text('@' + userLogin);
    $('#conversation-user-id').val(userId);
    
    // Загружаем сообщения
    loadMessages(userId);
    
    // Помечаем сообщения как прочитанные
    markMessagesAsRead(userId);
    
    // Запускаем автообновление
    startConversationAutoRefresh();
    
    // Фокусируемся на поле ввода
    setTimeout(() => {
        $('#message-text-input').focus();
    }, 100);
}


// Функция отображения сообщений (ИСПРАВЛЕННАЯ)
function renderMessages(messages) {
    console.log('Рендерим сообщения:', messages);
    
    const user_id = currentUser ? currentUser.id : 0;
    let html = '';
    
    messages.forEach(msg => {
        const isMe = parseInt(msg.sender_id) === parseInt(user_id);
        const time = msg.created_at ? 
            new Date(msg.created_at).toLocaleTimeString('ru-RU', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false 
            }) : '--:--';
        
        const senderName = msg.sender_name || msg.sender_login || 'Пользователь';
        
        html += `
            <div class="message-row" style="margin-bottom: 20px; display: flex; ${isMe ? 'justify-content: flex-end' : 'justify-content: flex-start'}">
                <div style="max-width: 70%; min-width: 200px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px; flex-direction: ${isMe ? 'row-reverse' : 'row'}">
                        <!-- Аватар -->
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: ${isMe ? '#4CAF50' : '#667eea'}; 
                                    display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; flex-shrink: 0;">
                            <i class="fas fa-user"></i>
                        </div>
                        
                        <!-- Сообщение -->
                        <div style="flex: 1;">
                            <div style="font-size: 12px; color: #666; margin-bottom: 5px; text-align: ${isMe ? 'right' : 'left'}">
                                ${isMe ? 'Вы' : senderName}
                                <span style="margin-left: 10px; color: #999;">${time}</span>
                            </div>
                            
                            <div style="background: ${isMe ? '#e3f2fd' : 'white'}; 
                                        color: ${isMe ? '#1565c0' : '#333'}; 
                                        padding: 12px 16px; 
                                        border-radius: ${isMe ? '18px 18px 4px 18px' : '18px 18px 18px 4px'}; 
                                        border: ${isMe ? '1px solid #bbdefb' : '1px solid #e0e0e0'};
                                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                                        word-break: break-word;">
                                ${msg.message.replace(/\n/g, '<br>')}
                            </div>
                            
                            ${msg.is_read === '1' && isMe ? 
                                `<div style="font-size: 11px; color: #4CAF50; text-align: right; margin-top: 5px;">
                                    <i class="fas fa-check-double"></i> Прочитано
                                </div>` : ''
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#messages-container').html(html);
    
    // Прокрутка вниз
    setTimeout(() => {
        const container = $('#messages-container')[0];
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }, 100);
}

// Функция когда нет сообщений
function renderNoMessages() {
    $('#messages-container').html(`
        <div style="text-align: center; padding: 100px 20px; color: #666;">
            <i class="fas fa-comment-slash" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 style="color: #666; margin-bottom: 10px;">Нет сообщений</h3>
            <p style="color: #999; max-width: 400px; margin: 0 auto;">
                Начните диалог первым! Отправьте сообщение этому пользователю.
            </p>
        </div>
    `);
}

function openDialog(userId, login, userName) {
    console.log('Открытие диалога с пользователем:', {userId, login, userName});
    
    // Проверяем ID
    if (!userId || userId <= 0) {
        console.error('Неверный ID пользователя:', userId);
        showToast('Ошибка: неверный ID пользователя', 'error');
        return;
    }
    
    // Устанавливаем значения
    $('#current-dialog-user-id').val(userId);
    $('#dialog-with-user').text(userName || login || 'Пользователь');
    $('#dialog-user-info').html(`
        <span style="color: #666;">ID: ${userId}</span>
        ${login ? ` • Логин: ${login}` : ''}
    `);
    
    // Показать элементы диалога
    $('#dialog-header').show();
    $('#message-form-container').show();
    
    // Загрузить сообщения
    loadMessages(userId);
    
    // Автофокус на поле ввода
    setTimeout(() => {
        $('#new-message-text').focus();
    }, 300);
}
// Функция рендеринга сообщений
function renderMessages(messages) {
    if (!messages || messages.length === 0) {
        $('#messages-container').html(`
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <div style="font-size: 60px; color: #e0e0e0; margin-bottom: 20px;">
                    <i class="fas fa-comment-slash"></i>
                </div>
                <h4 style="color: #666; margin-bottom: 10px;">Сообщений нет</h4>
                <p style="color: #999;">
                    Начните общение, отправив первое сообщение
                </p>
            </div>
        `);
        return;
    }
    
    let html = '';
    let lastDate = null;
    
    messages.forEach(message => {
        // Добавляем дату между сообщениями, если она изменилась
        const messageDate = new Date(message.created_at).toLocaleDateString('ru-RU');
        if (messageDate !== lastDate) {
            html += `
                <div style="text-align: center; margin: 20px 0;">
                    <span style="background: #e3f2fd; color: #1565C0; padding: 5px 15px; border-radius: 15px; font-size: 12px;">
                        ${messageDate}
                    </span>
                </div>
            `;
            lastDate = messageDate;
        }
        
        const isOwn = message.from_user_id === currentUser.id;
        const messageTime = new Date(message.created_at).toLocaleTimeString('ru-RU', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        html += `
            <div class="message ${isOwn ? 'own-message' : 'other-message'}" 
                 style="margin-bottom: 15px; display: flex; ${isOwn ? 'justify-content: flex-end;' : 'justify-content: flex-start;'}">
                <div style="max-width: 70%;">
                    <div style="display: flex; align-items: flex-end; gap: 8px; ${isOwn ? 'flex-direction: row-reverse;' : ''}">
                        ${!isOwn ? `
                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #667eea, #764ba2); 
                                        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                        color: white; font-size: 14px; flex-shrink: 0;">
                                <i class="fas fa-user"></i>
                            </div>
                        ` : ''}
                        
                        <div style="${isOwn ? 'background: linear-gradient(135deg, #667eea, #764ba2); color: white;' : 'background: white; border: 1px solid #e0e0e0; color: #333;'} 
                                    padding: 12px 16px; border-radius: 18px; ${isOwn ? 'border-bottom-right-radius: 4px;' : 'border-bottom-left-radius: 4px;'} 
                                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            ${message.message}
                            
                            <div style="font-size: 11px; margin-top: 5px; opacity: 0.7; text-align: ${isOwn ? 'right' : 'left'};">
                                ${messageTime}
                                ${message.is_read && isOwn ? ' <i class="fas fa-check-double" style="font-size: 10px;"></i>' : 
                                  isOwn ? ' <i class="fas fa-check" style="font-size: 10px;"></i>' : ''}
                            </div>
                        </div>
                        
                        ${isOwn ? `
                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #4CAF50, #2E7D32); 
                                        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                        color: white; font-size: 14px; flex-shrink: 0;">
                                <i class="fas fa-user"></i>
                            </div>
                        ` : ''}
                    </div>
                    
                    ${message.subject ? `
                        <div style="font-size: 12px; color: #666; margin-top: 5px; ${isOwn ? 'text-align: right;' : 'text-align: left;'}">
                            <i class="fas fa-tag"></i> ${message.subject}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    });
    
    $('#messages-container').html(html);
    scrollToBottom();
}

function sendMessage() {
    const userId = $('#conversation-user-id').val();
    const messageText = $('#message-text-input').val().trim();
    
    if (!messageText) {
        showToast('Введите сообщение', 'warning');
        return;
    }
    
    if (messageText.length > 1000) {
        showToast('Сообщение слишком длинное (макс. 1000 символов)', 'warning');
        return;
    }
    
    // Очищаем поле ввода сразу
    $('#message-text-input').val('');
    updateMessageLength(0);
    
    // Отправляем на сервер
    $.ajax({
        url: 'api.php?action=send_message',
        method: 'POST',
        data: {
            to_user_id: userId,
            message: messageText
        },
        dataType: 'json',
        success: function(response) {
            console.log('Send message response:', response); // Для отладки
            
            if (response.status === 'success') {
                showToast('Сообщение отправлено', 'success');
                
                // Обновляем сообщения в текущем диалоге
                if (currentConversation && currentConversation.id == userId) {
                    loadMessages(userId);
                }
                
                // Обновляем список диалогов
                loadConversations();
                
                // Прокручиваем вниз
                setTimeout(scrollToBottom, 300);
            } else {
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Send message error:', error);
            showToast('Ошибка соединения', 'error');
        }
    });
}
// Функция для принудительного обновления диалога
function refreshCurrentConversation() {
    if (currentConversation) {
        loadMessages(currentConversation.id);
        loadConversations();
    }
}

// Автоматическое обновление каждые 5 секунд
let conversationRefreshInterval = null;

function startConversationAutoRefresh() {
    if (conversationRefreshInterval) {
        clearInterval(conversationRefreshInterval);
    }
    
    conversationRefreshInterval = setInterval(() => {
        if (currentConversation && $('#conversation-container').is(':visible')) {
            loadMessages(currentConversation.id);
            loadConversations();
        }
    }, 5000); // 5 секунд
}

function stopConversationAutoRefresh() {
    if (conversationRefreshInterval) {
        clearInterval(conversationRefreshInterval);
        conversationRefreshInterval = null;
    }
}
// Функция начала нового диалога
function startNewConversation() {
   
showUserSearch();
}

// Функция показа поиска пользователей для нового сообщения
function showUserSearchForMessage() {
    let modalHtml = `
        <div id="new-conversation-modal" class="modal" style="display: block;">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fas fa-user-plus"></i> Новый диалог
                    </h3>
                    <button class="close-modal" onclick="$('#new-conversation-modal').remove()">&times;</button>
                </div>
                
                <div style="padding: 20px;">
                    <div class="form-group">
                        <label class="form-label">Найти пользователя</label>
                        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                            <input type="text" id="new-conversation-search" class="form-control" 
                                   placeholder="Введите логин или имя пользователя...">
                            <button class="btn btn-primary" onclick="searchUserForConversation()" style="white-space: nowrap;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="conversation-search-results" style="max-height: 300px; overflow-y: auto;">
                        <div style="text-align: center; padding: 40px 20px; color: #666;">
                            <i class="fas fa-search" style="font-size: 48px; color: #e0e0e0; margin-bottom: 20px;"></i>
                            <p>Введите логин пользователя для поиска</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
    
    // Обработка нажатия Enter в поле поиска
    $('#new-conversation-search').on('keyup', function(e) {
        if (e.key === 'Enter') {
            searchUserForConversation();
        }
    });
}

// Функция поиска пользователя для нового диалога
function searchUserForConversation() {
    const searchTerm = $('#new-conversation-search').val().trim();
    
    if (!searchTerm) {
        showToast('Введите логин пользователя', 'warning');
        return;
    }
    
    $('#conversation-search-results').html(`
        <div style="text-align: center; padding: 40px 20px; color: #666;">
            <div class="loader" style="margin: 0 auto 20px;"></div>
            <p>Ищем пользователя...</p>
        </div>
    `);
    
    $.ajax({
        url: 'api.php?action=search_users',
        method: 'GET',
        data: { 
            query: searchTerm,
            limit: 10
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.users && response.users.length > 0) {
                let html = '';
                
                response.users.forEach(user => {
                    if (user.id === currentUser.id) return; // Пропускаем себя
                    
                    html += `
                        <div class="user-search-result" 
                             onclick="selectUserForConversation(${user.id}, '${escapeHtml(user.login)}', '${escapeHtml(user.username || user.login)}')"
                             style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: background 0.2s;"
                             onmouseover="this.style.background='#f8f9fa'" 
                             onmouseout="this.style.background='white'">
                            
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); 
                                            border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-user"></i>
                                </div>
                                
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #333;">${user.username || user.login}</div>
                                    <div style="font-size: 12px; color: #666;">@${user.login}</div>
                                </div>
                                
                                <div style="color: #4CAF50; font-size: 14px;">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                if (html === '') {
                    html = `
                        <div style="text-align: center; padding: 40px 20px; color: #666;">
                            <i class="fas fa-user-slash" style="font-size: 48px; color: #e0e0e0; margin-bottom: 20px;"></i>
                            <p>Пользователи не найдены</p>
                        </div>
                    `;
                }
                
                $('#conversation-search-results').html(html);
            } else {
                $('#conversation-search-results').html(`
                    <div style="text-align: center; padding: 40px 20px; color: #666;">
                        <i class="fas fa-user-slash" style="font-size: 48px; color: #e0e0e0; margin-bottom: 20px;"></i>
                        <p>Пользователь не найден</p>
                    </div>
                `);
            }
        },
        error: function() {
            $('#conversation-search-results').html(`
                <div style="text-align: center; padding: 40px 20px; color: #666;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f44336; margin-bottom: 20px;"></i>
                    <p>Ошибка соединения</p>
                </div>
            `);
        }
    });
}

// Функция выбора пользователя для нового диалога
function selectUserForConversation(userId, userLogin, userName) {
    // Закрываем модальное окно
    $('#new-conversation-modal').remove();
    
    // Открываем диалог с выбранным пользователем
    openConversation(userId, userLogin, userName);
}

// Функция настройки формы отправки сообщения
function setupMessageForm() {
    $('#send-message-form').on('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    // Обновление счетчика символов
    $('#message-text-input').on('input', function() {
        const length = $(this).val().length;
        updateMessageLength(length);
    });
}

// Функция обновления счетчика символов
function updateMessageLength(length) {
    $('#message-length').text(`${length}/1000`);
    
    if (length > 900) {
        $('#message-length').css('color', '#ff9800');
    } else if (length > 1000) {
        $('#message-length').css('color', '#f44336');
    } else {
        $('#message-length').css('color', '#666');
    }
}

// Функция обработки нажатия клавиш в поле сообщения
function handleMessageKeydown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

// Функция прокрутки вниз
function scrollToBottom() {
    setTimeout(() => {
        const container = $('#messages-container')[0];
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }, 100);
}

// Функция отметки сообщений как прочитанных
function markMessagesAsRead(userId) {
    $.ajax({
        url: 'api.php?action=mark_messages_read',
        method: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Обновляем счетчик непрочитанных
                loadConversations();
                updateUnreadCount(response.total_unread || 0);
            }
        }
    });
}

// Функция обновления счетчика непрочитанных сообщений
function updateUnreadCount(count) {
    const $counter = $('#unread-messages-count');
    if (count > 0) {
        $counter.text(count).show();
    } else {
        $counter.hide();
    }
}

// Функция фильтрации диалогов
function filterConversations() {
    const searchTerm = $('#search-conversations').val().toLowerCase();
    
    $('.conversation-item').each(function() {
        const userName = $(this).data('user-name').toLowerCase();
        const userLogin = $(this).data('user-login').toLowerCase();
        
        if (userName.includes(searchTerm) || userLogin.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Функция перехода к профилю из диалога
function viewUserProfileFromConversation() {
    if (currentConversation) {
        viewUserProfile(currentConversation.id);
    }
}

// Функция форматирования времени сообщения
function formatMessageTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'только что';
    if (diffMins < 60) return `${diffMins} мин`;
    if (diffHours < 24) return `${diffHours} ч`;
    if (diffDays < 7) return `${diffDays} д`;
    
    return date.toLocaleDateString('ru-RU', { day: 'numeric', month: 'short' });
}

// Функция запуска опроса новых сообщений
function startMessagePolling() {
    // Останавливаем предыдущий интервал, если он был
    if (messagePollingInterval) {
        clearInterval(messagePollingInterval);
    }
    
    // Опрашиваем новые сообщения каждые 10 секунд
    messagePollingInterval = setInterval(() => {
        if ($('#conversations-list').is(':visible')) {
            loadConversations();
            
            // Если открыт диалог, обновляем сообщения
            if (currentConversation) {
                loadMessages(currentConversation.id);
            }
        }
    }, 10000); // 10 секунд
}

// Функция остановки опроса
function stopMessagePolling() {
    if (messagePollingInterval) {
        clearInterval(messagePollingInterval);
        messagePollingInterval = null;
    }
}


function showProducts() {
    let html = `
        <div style="margin-top:30px;">
            <h1 style="font-size:36px; margin-bottom:20px; color:#333;">Все товары</h1>
            
            <!-- Панель фильтров -->
            <div style="background:white; padding:20px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.1); margin-bottom:30px;">
                <div style="display: grid; grid-template-columns: 1fr 200px 150px auto; gap: 15px; align-items: end;">
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#555;">Поиск товаров</label>
                        <input type="text" id="search-input" class="form-control" placeholder="Название, описание..." 
                               onkeyup="loadProducts()">
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#555;">Категория</label>
                        <select id="category-select" class="form-control" onchange="loadProducts()">
                            <option value="">Все категории</option>
                            <option value="electronics">Электроника</option>
                            <option value="clothes">Одежда</option>
                            <option value="books">Книги</option>
                            <option value="home">Товары для дома</option>
                            <option value="beauty">Красота и здоровье</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#555;">Сортировка</label>
                        <select id="sort-select" class="form-control" onchange="loadProducts()">
                            <option value="newest">Сначала новые</option>
                            <option value="price_asc">Цена по возрастанию</option>
                            <option value="price_desc">Цена по убыванию</option>
                            <option value="popular">Популярные</option>
                            <option value="discount">Со скидкой</option>
                        </select>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="loadProducts()" style="height:42px;">
                            <i class="fas fa-search"></i> Поиск
                        </button>
                        <button class="btn" onclick="resetFilters()" style="height:42px; background:#6c757d; color:white; margin-left:10px;">
                            <i class="fas fa-redo"></i> Сбросить
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="products-list" class="products-grid"></div>
        </div>
    `;
    $('#content').html(html);
    loadProducts();
}

function resetFilters() {
    $('#search-input').val('');
    $('#category-select').val('');
    $('#sort-select').val('newest');
    loadProducts();
}

// Останавливаем опрос при уходе со страницы
$(window).on('beforeunload', function() {
    stopMessagePolling();
});



let avatarFile = null;
let avatarPreviewUrl = null;

// Функция для показа модального окна загрузки аватара
function showAvatarUploadModal(e) {
    // Останавливаем всплытие события
    if (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    }
    
    console.log('Opening avatar modal'); // Для отладки
    
    // Закрываем меню пользователя
    toggleUserMenu();
    
    // Показываем модальное окно
    const modal = document.getElementById('avatar-upload-modal');
    modal.style.display = 'flex';
    modal.style.opacity = '0';
    
    // Анимация появления
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.transition = 'opacity 0.3s';
    }, 10);
    
    resetAvatarPreview();
}

// Функция для закрытия модального окна
function closeAvatarModal() {
    console.log('Closing avatar modal'); // Для отладки
    
    const modal = document.getElementById('avatar-upload-modal');
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
    
    resetAvatarPreview();
}

// Функция сброса превью
function resetAvatarPreview() {
    avatarFile = null;
    avatarPreviewUrl = null;
    
    // Скрываем превью, показываем человечка
    const preview = document.getElementById('avatar-preview');
    const defaultPreview = document.getElementById('avatar-default-preview');
    
    if (preview) preview.style.display = 'none';
    if (defaultPreview) defaultPreview.style.display = 'block';
    
    // Сбрасываем кнопку загрузки
    const saveBtn = document.getElementById('save-avatar-btn');
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить';
    }
    
    // Очищаем input файла
    const fileInput = document.getElementById('avatar-file-input');
    if (fileInput) fileInput.value = '';
}

// Обновленная функция для управления меню пользователя
function toggleUserMenu(e) {
    if (e) {
        e.stopPropagation();
    }
    
    const menu = document.getElementById('user-menu');
    if (!menu) return;
    
    if (menu.style.display === 'block' || menu.style.display === '') {
        menu.style.display = 'none';
        // Удаляем обработчик клика вне меню
        document.removeEventListener('click', closeUserMenuOnClickOutside);
    } else {
        menu.style.display = 'block';
        
        // Закрытие меню при клике вне его
        setTimeout(() => {
            document.addEventListener('click', closeUserMenuOnClickOutside);
        }, 10);
    }
}

// Функция закрытия меню при клике вне его
function closeUserMenuOnClickOutside(e) {
    const menu = document.getElementById('user-menu');
    const avatarContainer = document.querySelector('.avatar-container');
    
    if (!menu || !avatarContainer) return;
    
    // Проверяем, был ли клик вне меню и вне контейнера аватара
    if (!menu.contains(e.target) && !avatarContainer.contains(e.target)) {
        menu.style.display = 'none';
        document.removeEventListener('click', closeUserMenuOnClickOutside);
    }
}

// Остальные функции остаются без изменений, но обновим их для надежности
function previewAvatar(event) {
    if (event && event.target && event.target.files) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Проверяем размер файла (максимум 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showToast('Файл слишком большой (максимум 5MB)', 'error');
            return;
        }
        
        // Проверяем тип файла
        if (!file.type.match('image.*')) {
            showToast('Пожалуйста, выберите изображение', 'error');
            return;
        }
        
        avatarFile = file;
        
        // Создаем URL для превью
        const reader = new FileReader();
        reader.onload = function(e) {
            avatarPreviewUrl = e.target.result;
            const preview = document.getElementById('avatar-preview');
            const defaultPreview = document.getElementById('avatar-default-preview');
            const saveBtn = document.getElementById('save-avatar-btn');
            
            if (preview) {
                preview.src = avatarPreviewUrl;
                preview.style.display = 'block';
            }
            if (defaultPreview) defaultPreview.style.display = 'none';
            if (saveBtn) saveBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }
}

function useDefaultAvatar() {
    avatarFile = null;
    avatarPreviewUrl = null;
    
    // Показываем человечка
    const preview = document.getElementById('avatar-preview');
    const defaultPreview = document.getElementById('avatar-default-preview');
    const saveBtn = document.getElementById('save-avatar-btn');
    
    if (preview) preview.style.display = 'none';
    if (defaultPreview) defaultPreview.style.display = 'block';
    if (saveBtn) saveBtn.disabled = false;
}

function removeAvatar() {
    if (confirm('Удалить аватар и вернуться к человечку?')) {
        $.ajax({
            url: 'api.php?action=remove_avatar',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Обновляем аватар в меню на человечка
                    const avatarContainer = document.querySelector('.avatar-container');
                    if (avatarContainer) {
                        avatarContainer.innerHTML = `
                            <div class="avatar-default">
                                <i class="fas fa-user avatar-icon"></i>
                            </div>
                            <div class="avatar-upload-btn" onclick="showAvatarUploadModal(event)">
                                <i class="fas fa-camera"></i>
                            </div>
                        `;
                    }
                    
                    showToast('Аватар удален', 'success');
                    closeAvatarModal();
                } else {
                    showToast('Ошибка: ' + response.message, 'error');
                }
            },
            error: function() {
                showToast('Ошибка соединения', 'error');
            }
        });
    }
}

function uploadAvatar() {
    const formData = new FormData();
    
    if (avatarFile) {
        formData.append('avatar', avatarFile);
    } else {
        formData.append('use_default', '1');
    }
    
    // Показываем загрузку
    const saveBtn = document.getElementById('save-avatar-btn');
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Загрузка...';
        saveBtn.disabled = true;
    }
    
    $.ajax({
        url: 'api.php?action=upload_avatar',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Обновляем аватар в меню
                const avatarContainer = document.querySelector('.avatar-container');
                if (avatarContainer) {
                    if (response.avatar_url) {
                        avatarContainer.innerHTML = `
                            <img src="${response.avatar_url}" 
                                 alt="Аватар" 
                                 class="user-avatar"
                                 id="user-avatar"
                                 onclick="toggleUserMenu(event)">
                            <div class="avatar-upload-btn" onclick="showAvatarUploadModal(event)">
                                <i class="fas fa-camera"></i>
                            </div>
                        `;
                    } else {
                        // Возвращаем человечка
                        avatarContainer.innerHTML = `
                            <div class="avatar-default" onclick="toggleUserMenu(event)">
                                <i class="fas fa-user avatar-icon"></i>
                            </div>
                            <div class="avatar-upload-btn" onclick="showAvatarUploadModal(event)">
                                <i class="fas fa-camera"></i>
                            </div>
                        `;
                    }
                }
                
                showToast('Аватар обновлен', 'success');
                closeAvatarModal();
            } else {
                showToast('Ошибка: ' + response.message, 'error');
                if (saveBtn) {
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить';
                    saveBtn.disabled = false;
                }
            }
        },
        error: function() {
            showToast('Ошибка соединения', 'error');
            if (saveBtn) {
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить';
                saveBtn.disabled = false;
            }
        }
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    console.log('Avatar system initialized');
    
    // Обработчик клика по модальному окну для закрытия
    const modal = document.getElementById('avatar-upload-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAvatarModal();
            }
        });
    }
    
    // Обработчик для кнопки закрытия в модальном окне
    const closeBtn = document.querySelector('#avatar-upload-modal .close-modal');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeAvatarModal);
    }
    
    // Обработчик для кнопок в модальном окне
    document.addEventListener('click', function(e) {
        // Проверяем, нажата ли кнопка загрузки файла
        if (e.target.closest('.avatar-option-btn')) {
            const btn = e.target.closest('.avatar-option-btn');
            const text = btn.querySelector('span')?.textContent;
            
            if (text === 'Загрузить') {
                document.getElementById('avatar-file-input').click();
            } else if (text === 'Человечек') {
                useDefaultAvatar();
            } else if (text === 'Удалить') {
                removeAvatar();
            }
        }
    });
});

// Глобальная переменная для отладки
window.debugAvatar = true;

function showAvatarModal(e) {
    console.log('=== AVATAR DEBUG ===');
    console.log('1. Function showAvatarModal called');
    console.log('2. Event:', e);
    
    if (e) {
        console.log('3. Preventing default behavior');
        e.preventDefault();
        e.stopPropagation();
        console.log('4. Propagation stopped');
    }
    
    // Проверим, существует ли модальное окно
    const modal = document.getElementById('avatar-upload-modal');
    console.log('5. Modal element found:', !!modal);
    
    if (!modal) {
        console.error('ERROR: Modal element not found!');
        return;
    }
    
    // Проверим функцию showModal
    console.log('6. Calling showModal function');
    
    // Вместо вызова showModal, сделаем прямое управление
    modal.style.display = 'flex';
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.transition = 'opacity 0.3s';
    }, 10);
    
    console.log('7. Modal should be visible now');
    console.log('8. Modal display style:', modal.style.display);
    console.log('=== DEBUG END ===');
    
    resetAvatarPreview();
}

// Также добавим отладку в resetAvatarPreview
function resetAvatarPreview() {
    console.log('resetAvatarPreview called');
    
    const preview = document.getElementById('avatar-preview');
    const defaultPreview = document.getElementById('avatar-default-preview');
    const saveBtn = document.getElementById('save-avatar-btn');
    
    console.log('Elements found:', {
        preview: !!preview,
        defaultPreview: !!defaultPreview,
        saveBtn: !!saveBtn
    });
}


// Глобальная функция для открытия модального окна аватарки
function openAvatarModal() {
    console.log('Opening avatar modal...');
    
    // Найдем модальное окно
    let modal = document.getElementById('avatar-upload-modal');
    
    if (!modal) {
        console.error('Modal not found! Creating new one...');
        createAvatarModal();
        modal = document.getElementById('avatar-upload-modal');
    }
    
    // Покажем модальное окно
    modal.style.display = 'flex';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.background = 'rgba(0, 0, 0, 0.8)';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.zIndex = '99999';
    
    console.log('Modal displayed');
    
    // Сбросим превью
    resetAvatarPreview();
}

// Создаем модальное окно, если его нет
function createAvatarModal() {
    console.log('Creating avatar modal...');
    
    const modalHTML = `
    <div id="avatar-upload-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 99999; justify-content: center; align-items: center;">
        <div style="background: white; border-radius: 15px; padding: 30px; max-width: 400px; width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">Управление аватаркой</h3>
                <button onclick="closeAvatarModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            </div>
            
            <div id="avatar-preview-container" style="width: 150px; height: 150px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                <img id="avatar-preview-img" src="" alt="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                <div id="avatar-default-icon" style="font-size: 60px; color: #667eea;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button onclick="document.getElementById('avatar-file-input').click()" style="flex: 1; padding: 12px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-upload"></i> Загрузить
                </button>
                <button onclick="setDefaultAvatar()" style="flex: 1; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-user"></i> Человечек
                </button>
            </div>
            
            <?php if(isset($_SESSION['user']['avatar']) && !empty($_SESSION['user']['avatar'])): ?>
            <button onclick="removeCurrentAvatar()" style="width: 100%; padding: 12px; background: #ff4757; color: white; border: none; border-radius: 8px; cursor: pointer; margin-bottom: 10px;">
                <i class="fas fa-trash"></i> Удалить текущий аватар
            </button>
            <?php endif; ?>
            
            <input type="file" id="avatar-file-input" accept="image/*" style="display: none;" onchange="previewSelectedAvatar(event)">
            
            <button onclick="saveAvatar()" id="save-avatar-btn" style="width: 100%; padding: 12px; background: #9c27b0; color: white; border: none; border-radius: 8px; cursor: pointer; margin-top: 10px;" disabled>
                <i class="fas fa-save"></i> Сохранить изменения
            </button>
        </div>
    </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Закрыть модальное окно
function closeAvatarModal() {
    const modal = document.getElementById('avatar-upload-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Сбросить превью
function resetAvatarPreview() {
    const previewImg = document.getElementById('avatar-preview-img');
    const defaultIcon = document.getElementById('avatar-default-icon');
    const saveBtn = document.getElementById('save-avatar-btn');
    
    if (previewImg) previewImg.style.display = 'none';
    if (defaultIcon) defaultIcon.style.display = 'block';
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить изменения';
    }
    
    // Сбросить файл
    window.currentAvatarFile = null;
    
    // Сбросить input файла
    const fileInput = document.getElementById('avatar-file-input');
    if (fileInput) fileInput.value = '';
}

// Превью выбранного файла
function previewSelectedAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (file.size > 5 * 1024 * 1024) {
        showToast('Файл слишком большой (максимум 5MB)');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewImg = document.getElementById('avatar-preview-img');
        const defaultIcon = document.getElementById('avatar-default-icon');
        const saveBtn = document.getElementById('save-avatar-btn');
        
        if (previewImg) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        }
        if (defaultIcon) defaultIcon.style.display = 'none';
        if (saveBtn) saveBtn.disabled = false;
    };
    reader.readAsDataURL(file);
    
    // Сохраняем файл
    window.currentAvatarFile = file;
}

// Установить человечка по умолчанию
function setDefaultAvatar() {
    const previewImg = document.getElementById('avatar-preview-img');
    const defaultIcon = document.getElementById('avatar-default-icon');
    const saveBtn = document.getElementById('save-avatar-btn');
    
    if (previewImg) previewImg.style.display = 'none';
    if (defaultIcon) defaultIcon.style.display = 'block';
    if (saveBtn) saveBtn.disabled = false;
    
    // Устанавливаем флаг для человечка
    window.currentAvatarFile = 'default';
}

// Удалить текущий аватар
function removeCurrentAvatar() {
    if (confirm('Удалить текущий аватар и вернуть человечка?')) {
        // Показываем загрузку
        const saveBtn = document.getElementById('save-avatar-btn');
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Удаление...';
            saveBtn.disabled = true;
        }
        
        // Отправляем запрос на удаление
        fetch('api.php?action=remove_avatar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'user_id=<?php echo $_SESSION['user']['id'] ?? 0; ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Аватар удален!');
                // Закрываем модальное окно
                closeAvatarModal();
                // Перезагружаем страницу для обновления аватара
                location.reload();
            } else {
                showToast('Ошибка: ' + (data.message || 'Не удалось удалить аватар'));
            }
        })
        .catch(error => {
            showToast('Ошибка соединения: ' + error);
        })
        .finally(() => {
            if (saveBtn) {
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить изменения';
                saveBtn.disabled = false;
            }
        });
    }
}

// Сохранить аватар
function saveAvatar() {
    if (!window.currentAvatarFile) {
        showToast('Сначала выберите действие');
        return;
    }
    
    const saveBtn = document.getElementById('save-avatar-btn');
    if (!saveBtn) return;
    
    // Показываем загрузку
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    saveBtn.disabled = true;
    
    if (window.currentAvatarFile === 'default') {
        // Устанавливаем человечка по умолчанию
        fetch('api.php?action=set_default_avatar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'user_id=<?php echo $_SESSION['user']['id'] ?? 0; ?>'
        })
        .then(response => response.json())
        .then(data => {
            handleAvatarResponse(data);
        })
        .catch(error => {
            handleAvatarError(error);
        });
    } else {
        // Загружаем файл
        const formData = new FormData();
        formData.append('avatar', window.currentAvatarFile);
        formData.append('user_id', '<?php echo $_SESSION['user']['id'] ?? 0; ?>');
        
        fetch('api.php?action=upload_avatar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            handleAvatarResponse(data);
        })
        .catch(error => {
            handleAvatarError(error);
        });
    }
}

// Обработка ответа от сервера
function handleAvatarResponse(data) {
    const saveBtn = document.getElementById('save-avatar-btn');
    
    if (data.status === 'success') {
        showToast('Аватар успешно обновлен!');
        closeAvatarModal();
        // Перезагружаем страницу для обновления аватара
        setTimeout(() => {
            location.reload();
        }, 500);
    } else {
         showToast('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить изменения';
            saveBtn.disabled = false;
        }
    }
}

// Обработка ошибки
function handleAvatarError(error) {
    const saveBtn = document.getElementById('save-avatar-btn');
    showToast('Ошибка соединения: ' + error);
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Сохранить изменения';
        saveBtn.disabled = false;
    }
}

// Инициализация - вешаем обработчики на все кнопки фотоаппарата
function initAvatarSystem() {
    console.log('Initializing avatar system...');
    
    // Находим все кнопки фотоаппарата
    const cameraButtons = document.querySelectorAll('.avatar-upload-btn, [class*="camera"], [id*="camera"]');
    console.log('Found camera buttons:', cameraButtons.length);
    
    cameraButtons.forEach(btn => {
        // Удаляем старые обработчики
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        // Добавляем новый обработчик
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Camera button clicked via event listener');
            openAvatarModal();
        });
        
        // Также добавляем onclick для надежности
        newBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Camera button clicked via onclick');
            openAvatarModal();
            return false;
        };
    });
    
    // Также добавляем глобальный обработчик
    document.addEventListener('click', function(e) {
        // Проверяем, была ли нажата кнопка с камерой
        if (e.target.closest('.avatar-upload-btn') || 
            e.target.classList.contains('fa-camera') ||
            (e.target.tagName === 'BUTTON' && e.target.textContent.includes('📷'))) {
            
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('Global handler: Camera button clicked');
            openAvatarModal();
            return false;
        }
    });
}

// Запускаем инициализацию при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Даем странице время на загрузку
    setTimeout(initAvatarSystem, 1000);
});


function appealBan() {
    // Проверяем сессию через AJAX
    $.ajax({
        url: 'api.php?action=check_session',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.user) {
                // У нас есть данные пользователя, создаем апелляцию
                createAppeal(response.user);
            } else {
                // Пробуем получить user_id из куки или других источников
                let user_id = getUserIdFromStorage();
                if (user_id) {
                    createAppeal({id: user_id, login: 'забаненный_пользователь'});
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка',
                        text: 'Не удалось определить пользователя'
                    });
                }
            }
        },
        error: function() {
            // Если AJAX не работает, пробуем получить данные из текущей сессии
            if (typeof currentUser !== 'undefined' && currentUser && currentUser.id) {
                createAppeal(currentUser);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка сессии',
                    text: 'Перезагрузите страницу и попробуйте снова'
                });
            }
        }
    });
}

// Вспомогательная функция для создания апелляции
function createAppeal(userData) {
    // Показываем загрузку
    Swal.fire({
        title: 'Создание апелляции...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Создаем тему и сообщение апелляции
    const subject = `Апелляция на блокировку акка ${userData.login || 'ID:' + userData.id}`;
    const message = `Мой аккаунт ${userData.login || 'ID:' + userData.id} (ID: ${userData.id}) был забанен.\n\n` +
                   `Хочу разобраться в ситуации и прошу пересмотреть решение о блокировке.\n` +
                   `Пожалуйста, объясните причину бана и возможность разблокировки.\n\n` +
                   `Дата обращения: ${new Date().toLocaleString()}`;
    
    // Отправляем запрос на создание тикета
    $.ajax({
        url: 'api.php?action=create_ticket',
        method: 'POST',
        data: {
            subject: subject,
            message: message
        },
        success: function(response) {
            Swal.close();
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция создана!',
                    html: `Тикет #${response.ticket_id}<br>Админ ответит в течение 24 часов.`,
                    confirmButtonText: 'ОК'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось создать апелляцию'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка соединения',
                text: 'Не удалось подключиться к серверу'
            });
        }
    });
}

// Функция для получения ID пользователя из localStorage/cookies
function getUserIdFromStorage() {
    // Пробуем получить из localStorage
    const storedUser = localStorage.getItem('user_id') || 
                       localStorage.getItem('currentUser') ||
                       sessionStorage.getItem('user_id');
    
    if (storedUser) {
        try {
            const user = JSON.parse(storedUser);
            return user.id || user;
        } catch (e) {
            return storedUser;
        }
    }
    
    // Пробуем получить из кук
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        const [name, value] = cookie.trim().split('=');
        if (name === 'user_id' || name === 'currentUser') {
            return value;
        }
    }
    
    return null;
}

function appealBanWithId(userId) {
    if (!userId) {
        appealBan(); // Используем старую логику
        return;
    }
    
    Swal.fire({
        title: 'Создание апелляции...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const subject = `Апелляция на блокировку акка ID:${userId}`;
    const message = `Мой аккаунт (ID: ${userId}) был забанен.\n\n` +
                   `Хочу разобраться в ситуации и прошу пересмотреть решение о блокировке.\n` +
                   `Пожалуйста, объясните причину бана и возможность разблокировки.\n\n` +
                   `Дата обращения: ${new Date().toLocaleString()}`;
    
    $.ajax({
        url: 'api.php?action=create_ticket',
        method: 'POST',
        data: {
            subject: subject,
            message: message,
            user_id: userId // Явно передаем user_id
        },
        success: function(response) {
            Swal.close();
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция создана!',
                    html: `Тикет #${response.ticket_id}<br>Админ ответит в течение 24 часов.`,
                    confirmButtonText: 'ОК'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось создать апелляцию'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка соединения',
                text: 'Не удалось подключиться к серверу'
            });
        }
    });
}


function createBanAppeal() {
    // Получаем данные пользователя из БАН-МОДАЛКИ
    let userLogin = 'Забаненный пользователь';
    let userId = 0;
    
    // Пытаемся достать из бан-инфо
    if (typeof banInfo !== 'undefined' && banInfo) {
        userId = banInfo.user_id || 0;
    }
    
    // Или из текущей сессии
    if (typeof currentUser !== 'undefined' && currentUser) {
        userId = currentUser.id || userId;
        userLogin = currentUser.login || userLogin;
    }
    
    // Если совсем не можем определить, показываем форму для ввода
    if (userId === 0) {
        Swal.fire({
            title: 'Введите ваш ID',
            input: 'number',
            inputLabel: 'Ваш ID пользователя',
            inputPlaceholder: 'Например: 123',
            showCancelButton: true,
            confirmButtonText: 'Продолжить',
            cancelButtonText: 'Отмена',
            inputValidator: (value) => {
                if (!value || value <= 0) {
                    return 'Введите корректный ID!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                sendAppeal(result.value, `Пользователь ID:${result.value}`);
            }
        });
    } else {
        sendAppeal(userId, userLogin);
    }
}

function sendAppeal(userId, userLogin) {
    Swal.fire({
        title: 'Создание апелляции...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            
            // Отправляем запрос
            $.ajax({
                url: 'api.php?action=create_ban_appeal',
                method: 'POST',
                data: {
                    user_id: userId,
                    user_login: userLogin
                },
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Апелляция создана!',
                            html: `Тикет #${response.ticket_id}<br>Админ рассмотрит ваше обращение.`,
                            confirmButtonText: 'ОК'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ошибка',
                            text: response.message || 'Не удалось создать апелляцию'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка соединения',
                        text: 'Попробуйте позже'
                    });
                }
            });
        }
    });
}

function createBanAppealWithId(userId) {
    if (!userId || userId <= 0) {
        createBanAppeal(); // Используем основную функцию
        return;
    }
    
    // Показываем форму для подтверждения
    Swal.fire({
        title: 'Подтвердите ID',
        html: `Создать апелляцию для пользователя ID: <b>${userId}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Да, создать',
        cancelButtonText: 'Отмена'
    }).then((result) => {
        if (result.isConfirmed) {
            sendAppeal(userId, `Пользователь ID:${userId}`);
        }
    });
}

function createBanAppealSimple() {
    // Простой prompt для теста
    const userId = prompt('Введите ваш ID пользователя (цифра):');
    
    if (!userId || isNaN(userId) || userId <= 0) {
         showToast('Некорректный ID');
        return;
    }
    
    // Простая анимация загрузки
    const loadDiv = document.createElement('div');
    loadDiv.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(0,0,0,0.7); z-index: 99999; display: flex; 
                    justify-content: center; align-items: center;">
            <div style="background: white; padding: 30px; border-radius: 10px; text-align: center;">
                <div class="loader" style="margin: 0 auto;"></div>
                <p style="margin-top: 15px;">Отправка апелляции...</p>
            </div>
        </div>
    `;
    document.body.appendChild(loadDiv);
    
    // Отправляем запрос
    fetch('api.php?action=create_ban_appeal', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user_id=' + encodeURIComponent(userId)
    })
    .then(response => {
        console.log('Статус ответа:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Ответ сервера:', data);
        document.body.removeChild(loadDiv);
        
        if (data.status === 'success') {
             showToast(`✅ Успешно!\n\nНомер обращения: #${data.ticket_id}\nЗапомните этот номер.`);
        } else {
             showToast(`❌ Ошибка:\n${data.message || 'Неизвестная ошибка'}\n\nПроверьте файл api_debug.log`);
        }
    })
    .catch(error => {
        console.error('Ошибка fetch:', error);
        document.body.removeChild(loadDiv);
         showToast('❌ Ошибка сети или сервера. Проверьте консоль (F12)');
    });
}

// Функция перехода в личный кабинет
function goToUserCabinet() {
    closeModal('ban-modal');
    showUserCabinet();
}

// Функция показа быстрой апелляции
function showSimpleAppealForm() {
    closeModal('ban-modal');
    showModal('simple-appeal-modal');
    
    // Автозаполняем ID если есть
    if (typeof currentUser !== 'undefined' && currentUser && currentUser.id) {
        $('#appeal_user_id').val(currentUser.id);
    }
}

// Отправка быстрой апелляции
function submitSimpleAppeal() {
    const userId = $('#appeal_user_id').val();
    const email = $('#appeal_email').val();
    const message = $('#appeal_message').val();
    
    if (!userId || userId <= 0) {
        showToast('Введите корректный ID', 'error');
        return;
    }
    
    if (!email || !email.includes('@')) {
        showToast('Введите корректный email', 'error');
        return;
    }
    
    // Показываем загрузку
    Swal.fire({
        title: 'Отправка...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Отправляем на сервер
    $.ajax({
        url: 'api.php?action=submit_appeal',
        method: 'POST',
        data: {
            user_id: userId,
            email: email,
            message: message || 'Мой аккаунт был забанен. Прошу разобраться в ситуации.'
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                closeModal('simple-appeal-modal');
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция отправлена!',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Номер обращения:</strong> #${response.appeal_id}</p>
                            <p><strong>Ваш ID:</strong> ${userId}</p>
                            <p><strong>Email для связи:</strong> ${email}</p>
                            <hr style="margin: 15px 0;">
                            <p style="color: #666; font-size: 14px;">
                                <i class="fas fa-info-circle"></i> Администратор свяжется с вами по указанному email в течение 24 часов.
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Понятно'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка',
                    text: response.message || 'Не удалось отправить апелляцию'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Ошибка соединения',
                text: 'Проверьте интернет-соединение'
            });
        }
    });
}

// Функция загрузки бан-информации
function loadBanInfo() {
    $.get('api.php?action=get_ban_info', function(response) {
        if (response.status === 'success') {
            let html = '';
            
            if (response.reason) {
                html += `<p><strong>Причина:</strong> ${response.reason}</p>`;
            }
            
            if (response.expires) {
                const expiresDate = new Date(response.expires);
                html += `<p><strong>Блокировка до:</strong> ${expiresDate.toLocaleDateString()} ${expiresDate.toLocaleTimeString()}</p>`;
            } else {
                html += `<p><strong>Срок:</strong> Бессрочно</p>`;
            }
            
            if (response.admin_message) {
                html += `<p><strong>Сообщение администратора:</strong> ${response.admin_message}</p>`;
            }
            
            $('#ban-info-container').html(html);
        }
    });
}



function showMyMessages() {
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h1 style="font-size:36px; color:#333; margin: 0;">
                    <i class="fas fa-comments" style="color: #2196F3;"></i> Мои сообщения
                </h1>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="loadMyMessages()">
                        <i class="fas fa-sync-alt"></i> Обновить
                    </button>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                <!-- Список диалогов -->
                <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); max-height: 600px; overflow-y: auto;">
                    <h3 style="margin-bottom: 20px; color: #333;">Диалоги</h3>
                    <div id="dialogs-list">
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <div class="loader"></div>
                            <p>Загрузка диалогов...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Выбранный диалог -->
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); display: flex; flex-direction: column; height: 600px;">
                    <div id="dialog-header" style="padding: 20px; border-bottom: 1px solid #eee; display: none;">
                        <h3 style="margin: 0; color: #333;" id="dialog-with-user"></h3>
                        <small style="color: #666;" id="dialog-user-info"></small>
                    </div>
                    
                    <div id="messages-container" style="flex: 1; padding: 20px; overflow-y: auto; background: #f8f9fa; min-height: 400px;">
                        <div style="text-align: center; padding: 100px 20px; color: #666;">
                            <i class="fas fa-comments" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                            <h3 style="color: #666;">Выберите диалог</h3>
                            <p>Выберите пользователя слева, чтобы начать общение</p>
                        </div>
                    </div>
                    
                    <div id="message-form-container" style="padding: 20px; border-top: 1px solid #eee; display: none;">
                        <div style="display: flex; gap: 10px;">
                            <input type="hidden" id="current-dialog-user-id">
                            <textarea id="new-message-text" class="form-control" placeholder="Введите сообщение..." rows="2" style="flex: 1;"></textarea>
                            <button class="btn btn-primary" onclick="sendPrivateMessage()" style="align-self: flex-end; height: 54px;">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    loadDialogs();
}

// Загрузка диалогов
function loadDialogs() {
    if (!currentUser) return;
    
    $.ajax({
        url: 'api.php?action=get_dialogs',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Dialogs response:', response);
            
            if (response.status === 'success') {
                renderDialogs(response.dialogs);
            } else {
                $('#dialogs-list').html(`
                    <div style="color: #f44336; padding: 20px; text-align: center;">
                        <i class="fas fa-exclamation-triangle"></i> ${response.message}
                    </div>
                `);
            }
        },
        error: function(error) {
            console.error('Error loading dialogs:', error);
            $('#dialogs-list').html(`
                <div style="color: #f44336; padding: 20px; text-align: center;">
                    <i class="fas fa-exclamation-triangle"></i> Ошибка загрузки
                </div>
            `);
        }
    });
}

// Отображение списка диалогов (ИСПРАВЛЕННАЯ ВЕРСИЯ)
function renderDialogs(dialogs) {
    console.log('renderDialogs вызвана с данными:', dialogs);
    
    let html = '';
    
    if (dialogs && dialogs.length > 0) {
        dialogs.forEach((dialog, index) => {
            console.log(`Диалог ${index}:`, dialog);
            console.log('Ключи диалога:', Object.keys(dialog));
            
            // ВАЖНО: определяем ID пользователя - пробуем разные варианты
            let userId = 0;
            
            // Пробуем все возможные варианты ключей
            if (dialog.id !== undefined && dialog.id !== null) {
                userId = parseInt(dialog.id);
            } else if (dialog.user_id !== undefined && dialog.user_id !== null) {
                userId = parseInt(dialog.user_id);
            } else if (dialog.userId !== undefined && dialog.userId !== null) {
                userId = parseInt(dialog.userId);
            } else if (dialog.other_user_id !== undefined && dialog.other_user_id !== null) {
                userId = parseInt(dialog.other_user_id);
            }
            
            console.log(`Определили userId: ${userId} (тип: ${typeof userId})`);
            
            // Если ID некорректный, пропускаем
            if (!userId || isNaN(userId) || userId <= 0) {
                console.error('Некорректный ID у диалога:', dialog);
                return;
            }
            
            // Получаем логин и имя
            const userLogin = dialog.login || `user${userId}`;
            const userName = dialog.username || userLogin || 'Пользователь';
            
            // Бэйджик непрочитанных
            const unreadCount = parseInt(dialog.unread_count) || 0;
            const unreadBadge = unreadCount > 0 ? 
                `<span style="background: #ff4757; color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px; float: right;">
                    ${unreadCount}
                </span>` : '';
            
            // Время последнего сообщения
            let lastMessageTime = '';
            if (dialog.last_message_time) {
                try {
                    lastMessageTime = new Date(dialog.last_message_time).toLocaleTimeString('ru-RU', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                } catch(e) {
                    console.error('Ошибка парсинга времени:', e);
                }
            }
            
            // Аватар
            const avatar = dialog.avatar ? 
                `<img src="${dialog.avatar}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">` :
                `<div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;">
                    <i class="fas fa-user"></i>
                </div>`;
            
            // Последнее сообщение (обрезаем если длинное)
            const lastMessage = dialog.last_message || dialog.message || 'Нет сообщений';
            const shortMessage = lastMessage.length > 50 ? 
                lastMessage.substring(0, 50) + '...' : lastMessage;
            
            // HTML диалога
            html += `
                <div class="dialog-item" 
                     data-user-id="${userId}"
                     data-user-login="${userLogin}"
                     data-user-name="${userName}"
                     style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; 
                            transition: background 0.3s; ${unreadCount > 0 ? 'background: #e3f2fd;' : ''}"
                     onmouseover="this.style.background='#f8f9fa'" 
                     onmouseout="this.style.background='${unreadCount > 0 ? '#e3f2fd' : 'white'}'"
                     onclick="openDialogSafe(this)">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        ${avatar}
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong style="color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${userName}
                                </strong>
                                ${unreadBadge}
                            </div>
                            <div style="color: #666; font-size: 13px; margin-top: 3px; 
                                        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${shortMessage}
                            </div>
                            ${lastMessageTime ? `
                                <small style="color: #999; font-size: 11px;">
                                    ${lastMessageTime}
                                </small>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        // Если после фильтрации нет валидных диалогов
        if (html === '') {
            html = getNoDialogsHTML();
        }
    } else {
        html = getNoDialogsHTML();
    }
    
    $('#dialogs-list').html(html);
}

// Безопасное открытие диалога
function openDialogSafe(element) {
    const userId = parseInt($(element).data('user-id'));
    const userLogin = $(element).data('user-login');
    const userName = $(element).data('user-name');
    
    console.log('Безопасное открытие диалога:', { userId, userLogin, userName });
    
    if (!userId || isNaN(userId) || userId <= 0) {
        console.error('Некорректный ID при открытии диалога');
        showToast('Ошибка: некорректный ID пользователя', 'error');
        return;
    }
    
    openDialog(userId, userLogin, userName);
}

// HTML при отсутствии диалогов
function getNoDialogsHTML() {
    return `
        <div style="text-align: center; padding: 60px 20px; color: #666;">
            <i class="fas fa-comments" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 style="color: #666; margin-bottom: 10px;">Нет диалогов</h3>
            <p style="color: #999; margin-bottom: 25px;">
                Начните общение, отправив сообщение другому пользователю
            </p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="btn btn-primary" onclick="showUserSearch()" style="padding: 10px 20px;">
                    <i class="fas fa-search"></i> Найти пользователей
                </button>
                <button class="btn" onclick="testOpenDialog()" style="background: #667eea; color: white; padding: 10px 20px;">
                    <i class="fas fa-bug"></i> Тест диалога
                </button>
            </div>
        </div>
    `;
}

// Тестовая функция для открытия диалога
function testOpenDialog() {
    const testUserId = prompt('Введите ID пользователя для теста:', '2');
    const testUserLogin = prompt('Введите логин пользователя:', 'admin');
    const testUserName = prompt('Введите имя пользователя:', 'Администратор');
    
    if (testUserId && !isNaN(testUserId)) {
        openDialog(parseInt(testUserId), testUserLogin || 'user', testUserName || 'Пользователь');
    } else {
        showToast('Некорректный ID', 'warning');
    }
}

// Функция для проверки структуры диалогов
function debugDialogsData() {
    console.log('=== DEBUG DIALOGS ===');
    
    $.ajax({
        url: 'api.php?action=get_dialogs',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Ответ от get_dialogs:', response);
            
            if (response.status === 'success' && response.dialogs) {
                console.log('Количество диалогов:', response.dialogs.length);
                
                if (response.dialogs.length > 0) {
                    console.log('Первый диалог:', response.dialogs[0]);
                    console.log('Все ключи первого диалога:', Object.keys(response.dialogs[0]));
                    
                    // Создаем тестовую кнопку для этого диалога
                    const dialog = response.dialogs[0];
                    const btn = `
                        <button onclick="testFirstDialog()" 
                                style="position: fixed; top: 100px; right: 20px; z-index: 9999;
                                       background: #4CAF50; color: white; padding: 10px 15px;
                                       border-radius: 5px; border: none; cursor: pointer;">
                            <i class="fas fa-play"></i> Тест 1-го диалога
                        </button>
                    `;
                    
                    $('body').append(btn);
                    
                    // Сохраняем данные для теста
                    window.testDialogData = dialog;
                }
            } else {
                console.error('Ошибка в ответе:', response);
            }
        },
        error: function(error) {
            console.error('Ошибка загрузки диалогов:', error);
        }
    });
}

// Тест первого диалога
function testFirstDialog() {
    if (!window.testDialogData) {
        console.error('Нет тестовых данных');
        return;
    }
    
    const dialog = window.testDialogData;
    console.log('Тестируем диалог:', dialog);
    
    // Пробуем разные варианты ID
    const possibleIds = [
        dialog.id,
        dialog.user_id,
        dialog.userId,
        dialog.other_user_id,
        dialog.sender_id,
        dialog.receiver_id
    ];
    
    console.log('Возможные ID:', possibleIds.filter(id => id));
    
    // Используем первый найденный ID
    const userId = possibleIds.find(id => id && !isNaN(id) && id > 0);
    
    if (userId) {
        openDialog(
            parseInt(userId),
            dialog.login || `user${userId}`,
            dialog.username || dialog.login || 'Пользователь'
        );
    } else {
        showToast('Не удалось определить ID пользователя', 'error');
    }
}

// Вызови debugDialogsData() в консоли для отладки


function loadMessages(otherUserId) {
    console.log('Загрузка сообщений с ID:', otherUserId, 'Тип:', typeof otherUserId);
    
    // Проверка ID
    otherUserId = parseInt(otherUserId);
    if (!otherUserId || otherUserId <= 0) {
        console.error('Неверный otherUserId:', otherUserId);
        showToast('Ошибка: неверный ID пользователя', 'error');
        $('#messages-container').html(`
            <div style="color: #f44336; padding: 20px; text-align: center;">
                <i class="fas fa-exclamation-triangle"></i> Ошибка: неверный ID пользователя
            </div>
        `);
        return;
    }
    
    // Показываем загрузку
    $('#messages-container').html(`
        <div style="text-align: center; padding: 40px;">
            <div class="loader"></div>
            <p style="color: #666; margin-top: 20px;">Загрузка сообщений...</p>
        </div>
    `);
    
    // Делаем AJAX запрос
    $.ajax({
        url: 'api.php?action=get_messages',
        method: 'GET',
        data: { 
            other_user_id: otherUserId 
        },
        dataType: 'json',
        success: function(response) {
            console.log('Ответ от сервера:', response);
            
            if (response.status === 'success') {
                if (response.messages && response.messages.length > 0) {
                    console.log('Найдено сообщений:', response.messages.length);
                    renderMessages(response.messages);
                } else {
                    console.log('Сообщений нет');
                    renderNoMessages();
                }
            } else {
                console.error('Ошибка от сервера:', response.message);
                showToast('Ошибка: ' + response.message, 'error');
                renderNoMessages();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX ошибка:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            showToast('Ошибка сети', 'error');
            renderNoMessages();
        }
    });
}

// Отобразить сообщения
function renderMessages(messages) {
    let html = '';
    
    if (messages && messages.length > 0) {
        const user_id = currentUser ? currentUser.id : 0;
        
        messages.forEach(msg => {
            const isMe = msg.sender_id == user_id;
            const time = new Date(msg.created_at).toLocaleTimeString('ru-RU', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            html += `
                <div style="margin-bottom: 15px; display: flex; ${isMe ? 'justify-content: flex-end' : 'justify-content: flex-start'}">
                    <div style="max-width: 70%;">
                        <div style="display: flex; align-items: flex-end; gap: 8px; flex-direction: ${isMe ? 'row-reverse' : 'row'}">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: ${isMe ? '#4CAF50' : '#667eea'}; 
                                        display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; flex-shrink: 0;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="background: ${isMe ? '#e3f2fd' : 'white'}; 
                                            color: ${isMe ? '#1565c0' : '#333'}; 
                                            padding: 10px 15px; 
                                            border-radius: ${isMe ? '15px 15px 5px 15px' : '15px 15px 15px 5px'}; 
                                            border: ${isMe ? '1px solid #bbdefb' : '1px solid #e0e0e0'};
                                            box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                    <div style="word-break: break-word;">${msg.message}</div>
                                    <div style="font-size: 11px; text-align: right; margin-top: 5px; opacity: 0.7;">
                                        ${time}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html = `
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <i class="fas fa-comment" style="font-size: 48px; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #666;">Нет сообщений</h3>
                <p>Начните диалог первым!</p>
            </div>
        `;
    }
    
    $('#messages-container').html(html);
    
    // Прокрутка вниз
    setTimeout(() => {
        const container = $('#messages-container')[0];
        container.scrollTop = container.scrollHeight;
    }, 100);
}

// Отправить сообщение
function sendPrivateMessage() {
    const receiverId = $('#current-dialog-user-id').val();
    const message = $('#new-message-text').val().trim();
    
    if (!receiverId) {
        showToast('Выберите диалог', 'warning');
        return;
    }
    
    if (!message) {
        showToast('Введите сообщение', 'warning');
        return;
    }
    
    $.ajax({
        url: 'api.php?action=send_message',
        method: 'POST',
        data: {
            receiver_id: receiverId,
            message: message
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#new-message-text').val('');
                
                // Обновить сообщения
                loadMessages(receiverId);
                
                // Обновить список диалогов
                loadDialogs();
                
                // Прокрутка вниз
                setTimeout(() => {
                    const container = $('#messages-container')[0];
                    container.scrollTop = container.scrollHeight;
                }, 100);
                
                showToast('Сообщение отправлено', 'success');
            } else {
                showToast('Ошибка: ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('Ошибка соединения', 'error');
        }
    });
}

// Пометить сообщения как прочитанные
function markMessagesAsRead(senderId) {
    $.ajax({
        url: 'api.php?action=mark_messages_read',
        method: 'POST',
        data: { sender_id: senderId }
    });
}

// Обновить сообщения
function loadMyMessages() {
    loadDialogs();
    showToast('Обновлено', 'info');
}

// Обработчик Enter для отправки
$(document).on('keypress', '#new-message-text', function(e) {
    if (e.which == 13 && !e.shiftKey) {
        e.preventDefault();
        sendPrivateMessage();
    }
});

function submitMessageToUser(userId) {
    const message = $('#message-text').val().trim();
    
    fetch('api.php?action=send_message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'receiver_id=' + userId + '&message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ответ:', data);
        if (data.status === 'success') {
             showToast('Отправлено!');
            $('#send-message-modal').remove();
        } else {
             showToast('Ошибка: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
         showToast('Ошибка сети');
    });
}


// Также запускаем при любых динамических изменениях
setInterval(initAvatarSystem, 3000);




// ========== ПРЯМОЙ ОБРАБОТЧИК КНОПКИ - 100% РАБОТАЕТ ==========
$(document).ready(function() {
    console.log('🚀 УСТАНОВКА ОБРАБОТЧИКА КНОПКИ');
    
    // ЖДЕМ ПОЯВЛЕНИЯ КНОПКИ
    function setupPayButton() {
        let $btn = $('#pay-button-fixed, #pay-button');
        if ($btn.length) {
            console.log('✅ КНОПКА НАЙДЕНА, УСТАНАВЛИВАЮ ОБРАБОТЧИК');
            
            $btn.off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('💰 КЛИК ПО КНОПКЕ ОПЛАТЫ');
                
                // БЛОКИРУЕМ КНОПКУ
                $(this).html('<i class="fas fa-spinner fa-spin"></i> ОФОРМЛЕНИЕ...').prop('disabled', true);
                
                // ОТПРАВЛЯЕМ ЗАПРОС - ТОЧНО ТАКОЙ ЖЕ КАК В КОНСОЛИ!
                fetch('api.php?action=create_order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'name=Test&email=test@test.com&phone=123&delivery_method=courier&address=Test&payment_method=balance'
                })
                .then(response => response.json())
                .then(data => {
                    console.log('✅ ОТВЕТ:', data);
                    
                    if (data.status === 'success') {
                        // ЗАКРЫВАЕМ МОДАЛКУ
                        closeModal('checkout-modal');
                        
                        // ПОМЕЧАЕМ ТОВАРЫ
                        let ids = data.purchased_product_ids || [];
                        ids.forEach(id => markProductAsSoldUI(id));
                        
                         showToast('✅ ЗАКАЗ ОФОРМЛЕН!\n\nТовары: ' + ids.join(', '));
                        
                        // ОБНОВЛЯЕМ СТРАНИЦУ
                        setTimeout(() => location.reload(), 2000);
                    } else {
                         showToast('❌ ОШИБКА: ' + data.message);
                        $(this).html('💳 ОПЛАТИТЬ ЗАКАЗ').prop('disabled', false);
                    }
                })
                .catch(error => {
                    console.error('❌ ОШИБКА:', error);
                     showToast('❌ ОШИБКА СОЕДИНЕНИЯ');
                    $(this).html('💳 ОПЛАТИТЬ ЗАКАЗ').prop('disabled', false);
                });
                
                return false;
            });
        } else {
            console.log('⏳ КНОПКА НЕ НАЙДЕНА, ПОВТОР ЧЕРЕЗ 500ms');
            setTimeout(setupPayButton, 500);
        }
    }
    
    setupPayButton();
});


// ========== ПОДАЧА АПЕЛЛЯЦИИ - РАБОЧАЯ ВЕРСИЯ ==========
function userSubmitBanAppeal() {
    let login = prompt('🔍 Введите ваш логин:', 'logina');
    if (!login) return;
    
    // Показываем загрузку
    Swal.fire({
        title: '⏳ Отправка апелляции...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    // Получаем ID по логину
    fetch('api.php?action=get_user_id_by_login&login=' + encodeURIComponent(login))
    .then(r => r.json())
    .then(data => {
        console.log('🔥 Найден пользователь:', data);
        
        if (data.status === 'success' && data.user_id) {
            if (data.banned != 1) {
                throw new Error('Пользователь не забанен!');
            }
            // Создаем апелляцию
            return fetch('api.php?action=create_ban_appeal', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + data.user_id + '&login=' + encodeURIComponent(login)
            }).then(r => r.json());
        } else {
            throw new Error('Пользователь не найден');
        }
    })
    .then(result => {
        Swal.close();
        
        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '✅ Апелляция создана!',
                html: `
                    <div style="text-align: center;">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); 
                            color: white; padding: 20px; border-radius: 15px; margin: 20px 0;">
                            <div style="font-size: 14px;">Номер обращения</div>
                            <div style="font-size: 32px; font-weight: bold;">#${result.ticket_id}</div>
                        </div>
                        <p style="color: #4CAF50;">⏱ Администратор ответит в течение 24 часов</p>
                    </div>
                `,
                confirmButtonText: 'Понятно',
                confirmButtonColor: '#4CAF50'
            });
            closeModal('ban-modal');
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: result.message || 'Не удалось создать апелляцию',
                confirmButtonColor: '#ff4757'
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: error.message,
            confirmButtonColor: '#ff4757'
        });
    });
}


// ========== СОЗДАНИЕ АПЕЛЛЯЦИИ ПО ID ==========
function createAppealForBannedUser(userId, userLogin) {
    Swal.fire({
        title: '⏳ Отправка апелляции...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=create_ban_appeal',
        type: 'POST',
        data: {
            user_id: userId,
            login: userLogin || 'user'
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция создана!',
                    html: `
                        <div style="text-align: center; padding: 10px;">
                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); 
                                color: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                <div style="font-size: 14px;">Номер обращения</div>
                                <div style="font-size: 36px; font-weight: bold; letter-spacing: 3px;">
                                    #${response.ticket_id}
                                </div>
                            </div>
                            <p style="color: #4CAF50; font-weight: bold;">
                                ⏱ Администратор ответит в течение 24 часов
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Понятно',
                    confirmButtonColor: '#4CAF50'
                });
                
                closeModal('ban-modal');
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось создать апелляцию',
                    confirmButtonColor: '#ff4757'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения с сервером',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}

// ========== СОЗДАНИЕ АПЕЛЛЯЦИИ ДЛЯ ЗАБАНЕННОГО ==========
function createAppealForBannedUser(userId, userLogin) {
    Swal.fire({
        title: '⏳ Отправка апелляции...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=create_ban_appeal',
        type: 'POST',
        data: {
            user_id: userId,
            login: userLogin || 'user'
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция создана!',
                    html: `
                        <div style="text-align: center; padding: 10px;">
                            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #4CAF50, #45a049); 
                                border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                margin: 0 auto 20px; box-shadow: 0 10px 25px rgba(76,175,80,0.3);">
                                <i class="fas fa-check" style="font-size: 50px; color: white;"></i>
                            </div>
                            <h3 style="color: #333; margin-bottom: 15px;">Апелляция принята!</h3>
                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); 
                                color: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                <div style="font-size: 14px; opacity: 0.9;">Номер обращения</div>
                                <div style="font-size: 36px; font-weight: bold; letter-spacing: 3px;">
                                    #${response.ticket_id}
                                </div>
                            </div>
                            <p style="color: #4CAF50; font-weight: bold;">
                                ⏱ Администратор ответит в течение 24 часов
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Понятно',
                    confirmButtonColor: '#4CAF50'
                });
                
                closeModal('ban-modal');
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось создать апелляцию',
                    confirmButtonColor: '#ff4757'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения с сервером',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}

// ========== ОТПРАВКА АПЕЛЛЯЦИИ ПО ЛОГИНУ ==========
function submitAppealByLogin(login) {
    Swal.fire({
        title: '⏳ Отправка апелляции...',
        html: `
            <div style="text-align: center; padding: 20px;">
                <div class="loader" style="margin: 0 auto 20px;"></div>
                <p style="color: #666;">Пожалуйста, подождите</p>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
    });

    // Сначала получаем ID по логину
    $.ajax({
        url: 'api.php?action=get_user_id_by_login',
        type: 'GET',
        data: { login: login },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.user_id) {
                // Есть ID - создаем апелляцию
                createAppealWithId(response.user_id, login);
            } else {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: 'Пользователь с таким логином не найден',
                    confirmButtonColor: '#ff9800'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Не удалось проверить логин',
                confirmButtonColor: '#ff9800'
            });
        }
    });
}

// ========== СОЗДАНИЕ АПЕЛЛЯЦИИ ПО ID ==========
function createAppealWithId(userId, login) {
    $.ajax({
        url: 'api.php?action=create_ban_appeal',
        type: 'POST',
        data: { 
            user_id: userId,
            login: login 
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция создана!',
                    html: `
                        <div style="text-align: center; padding: 10px;">
                            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #4CAF50, #45a049); 
                                border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                margin: 0 auto 20px; box-shadow: 0 10px 25px rgba(76,175,80,0.3);">
                                <i class="fas fa-check" style="font-size: 50px; color: white;"></i>
                            </div>
                            
                            <h3 style="color: #333; margin-bottom: 15px;">Апелляция принята!</h3>
                            
                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); 
                                color: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                <div style="font-size: 14px; opacity: 0.9;">Номер обращения</div>
                                <div style="font-size: 36px; font-weight: bold; letter-spacing: 3px;">
                                    #${response.ticket_id}
                                </div>
                                <div style="font-size: 12px; margin-top: 10px; opacity: 0.8;">
                                    ${response.appeal_number || ''}
                                </div>
                            </div>
                            
                            <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                <p style="margin: 0; color: #e65100; display: flex; align-items: center; justify-content: center; gap: 10px;">
                                    <i class="fas fa-clock"></i>
                                    ⏱ Администратор ответит в течение 24 часов
                                </p>
                            </div>
                            
                            <p style="color: #666; font-size: 14px; margin-top: 10px;">
                                <i class="fas fa-user"></i> Логин: <strong>${login}</strong>
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Понятно',
                    confirmButtonColor: '#4CAF50'
                });
                
                closeModal('ban-modal');
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось создать апелляцию',
                    confirmButtonColor: '#ff9800'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            let errorMsg = 'Ошибка соединения с сервером';
            try {
                const resp = JSON.parse(xhr.responseText);
                errorMsg = resp.message || errorMsg;
            } catch(e) {}
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: errorMsg,
                confirmButtonColor: '#ff9800'
            });
        }
    });
}
// ========== ОТПРАВКА АПЕЛЛЯЦИИ НА СЕРВЕР ==========
function submitAppealToServer(userId) {
    // Показываем загрузку
    Swal.fire({
        title: '📤 Отправка апелляции...',
        html: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: 'api.php?action=create_ban_appeal',
        method: 'POST',
        data: { 
            user_id: userId,
            message: 'Прошу разблокировать мой аккаунт.'
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                // ПОКАЗЫВАЕМ НОМЕР АПЕЛЛЯЦИИ
                Swal.fire({
                    icon: 'success',
                    title: '✅ АПЕЛЛЯЦИЯ ПРИНЯТА!',
                    html: `
                        <div style="text-align: center;">
                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); 
                                      color: white; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                <div style="font-size: 14px; opacity: 0.9;">Номер обращения</div>
                                <div style="font-size: 36px; font-weight: bold; letter-spacing: 2px;">
                                    #${response.ticket_id}
                                </div>
                                <div style="font-size: 12px; margin-top: 10px; opacity: 0.8;">
                                    ${response.appeal_number || ''}
                                </div>
                            </div>
                            <p style="color: #4CAF50; font-weight: bold; margin-bottom: 10px;">
                                ⏱ Администратор ответит в течение 24 часов
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Понятно',
                    confirmButtonColor: '#4CAF50'
                });
                
                // Закрываем бан-модалку
                closeModal('ban-modal');
                
            } else {
                // Ошибка от сервера
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось отправить апелляцию',
                    confirmButtonColor: '#ff4757'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            
            let errorMsg = 'Ошибка соединения с сервером';
            try {
                const resp = JSON.parse(xhr.responseText);
                errorMsg = resp.message || errorMsg;
            } catch(e) {}
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка сети',
                text: errorMsg,
                confirmButtonColor: '#ff4757'
            });
        }
    });
}


// ========== ОТОБРАЖЕНИЕ АПЕЛЛЯЦИЙ (АДМИН) ==========
function renderAdminAppeals(appeals) {
    if (!appeals || appeals.length === 0) {
        $('#appeals-tab').html(`
            <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px;">
                <div style="font-size: 80px; color: #4CAF50; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 style="color: #666; margin-bottom: 15px;">Апелляций нет</h3>
                <p style="color: #999;">Все апелляции обработаны</p>
            </div>
        `);
        return;
    }
    
    let html = `
        <div style="margin-bottom: 20px;">
            <h3 style="margin: 0; color: #333;">
                <i class="fas fa-gavel"></i> Апелляции (${appeals.length})
            </h3>
        </div>
        <div style="max-height: 70vh; overflow-y: auto;">
    `;
    
    appeals.forEach(appeal => {
        let date = appeal.created_at ? new Date(appeal.created_at).toLocaleString('ru-RU') : 'Дата неизвестна';
        let statusColor = appeal.status === 'new' ? '#ff9800' : (appeal.status === 'in_progress' ? '#2196F3' : '#6c757d');
        let statusText = appeal.status === 'new' ? 'Новая' : (appeal.status === 'in_progress' ? 'В работе' : 'Закрыта');
        
        html += `
            <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; border-left: 6px solid ${statusColor};">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div>
                        <span style="background: #ff4757; color: white; padding: 5px 12px; border-radius: 20px;">
                            ID: ${appeal.user_id}
                        </span>
                        <span style="background: #f8f9fa; color: #333; padding: 5px 12px; border-radius: 20px; margin-left: 10px;">
                            #${appeal.ticket_id}
                        </span>
                        <span style="background: ${statusColor}; color: white; padding: 5px 12px; border-radius: 20px; margin-left: 10px;">
                            ${statusText}
                        </span>
                    </div>
                    <div>
                        <button class="btn" style="background: #4CAF50; color: white;" onclick="adminUnbanUser(${appeal.user_id})">
                            <i class="fas fa-check"></i> Разбанить
                        </button>
                    </div>
                </div>
                <div style="background: #fff3e0; padding: 15px; border-radius: 10px;">
                    <strong>Причина бана:</strong> ${appeal.ban_reason || 'Не указана'}
                </div>
                <div style="margin-top: 10px; color: #666; font-size: 12px;">
                    ${date}
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    $('#appeals-tab').html(html);
}

// ========== АДМИН: РАЗБАНИТЬ ==========
function adminUnbanUser(userId) {
    if (!confirm('Разблокировать пользователя?')) return;
    
    $.post('api.php?action=adm_unban', { user_id: userId }, function(response) {
        if (response.status === 'success') {
            showToast('✅ Пользователь разблокирован!', 'success');
            loadAdminAppeals();
            loadAdminUsers();
        }
    });
}




function adminOpenTicket(ticketId) {
    if (ticketId && typeof showTicketMessages === 'function') {
        showTicketMessages(ticketId, 'Апелляция #' + ticketId);
    }
}

// ========== РАБОЧАЯ ФУНКЦИЯ АПЕЛЛЯЦИИ ==========
function createBanAppealDirect() {
    console.log('🚨 Подача апелляции');
    
    let userId = 0;
    let userLogin = 'user';
    
    if (typeof currentUser !== 'undefined' && currentUser) {
        userId = currentUser.id || 0;
        userLogin = currentUser.login || 'user';
    }
    
    if (!userId || userId === 0) {
        Swal.fire({
            title: 'Ваш ID',
            input: 'number',
            inputLabel: 'Введите ваш ID пользователя',
            showCancelButton: true,
            confirmButtonText: 'Продолжить',
            cancelButtonText: 'Отмена',
            inputValidator: (value) => {
                if (!value || value <= 0) return 'Введите корректный ID!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                sendAppealToServer(result.value, 'user' + result.value);
            }
        });
        return;
    }
    
    sendAppealToServer(userId, userLogin);
}

function sendAppealToServer(userId, userLogin) {
    Swal.fire({
        title: '📤 Отправка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: 'api.php?action=create_ban_appeal',
        method: 'POST',
        data: { 
            user_id: userId,
            user_login: userLogin
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция отправлена!',
                    html: `
                        <p><span style="background: #4CAF50; color: white; padding: 5px 15px; border-radius: 20px;">
                            Тикет #${response.ticket_id || '?'}
                        </span></p>
                        <p style="margin-top: 15px;">Администратор рассмотрит вашу апелляцию в течение 24 часов.</p>
                    `,
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось отправить апелляцию'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка сети',
                text: 'Проверьте подключение к интернету'
            });
        }
    });
}

function sendAppealToAdmin(userId, userLogin, userName) {
    // Показываем загрузку
    Swal.fire({
        title: '📤 Отправка апелляции...',
        html: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    // Формируем сообщение
    const subject = `АПЕЛЛЯЦИЯ: Блокировка аккаунта @${userLogin || 'user'}`;
    const message = `**Апелляция на блокировку**\n\n` +
                    `👤 Пользователь: @${userLogin || 'Неизвестно'} (ID: ${userId})\n` +
                    `📅 Дата обращения: ${new Date().toLocaleString('ru-RU')}\n\n` +
                    `💬 Сообщение:\n` +
                    `Мой аккаунт был заблокирован. Прошу пересмотреть решение и разблокировать доступ.\n` +
                    `Готов предоставить дополнительную информацию при необходимости.\n\n` +
                    `— Автоматическая апелляция из бан-модалки`;

    // Отправляем запрос на создание тикета
    $.ajax({
        url: 'api.php?action=create_ticket',
        method: 'POST',
        data: {
            subject: subject,
            message: message,
            user_id: userId
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            console.log('✅ Ответ сервера:', response);
            
            if (response.status === 'success') {
                // Успех — показываем окно с номером тикета
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция отправлена!',
                    html: `
                        <div style="text-align: left;">
                            <p style="font-size: 16px; margin-bottom: 10px;">
                                <span style="background: #4CAF50; color: white; padding: 5px 10px; border-radius: 5px;">
                                    Тикет #${response.ticket_id || '??'}
                                </span>
                            </p>
                            <p style="color: #333; margin: 15px 0;">
                                Ваше обращение передано администратору.<br>
                                <strong>Ожидайте ответ в течение 24 часов.</strong>
                            </p>
                            <hr style="margin: 15px 0;">
                            <p style="color: #666; font-size: 14px;">
                                <i class="fas fa-clock"></i> Вы можете проверить статус тикета в разделе "Мои тикеты" после разблокировки.
                            </p>
                        </div>
                    `,
                    confirmButtonText: 'Понятно',
                    confirmButtonColor: '#4CAF50'
                });
                
                // Автоматически закрываем бан-модалку через 2 секунды
                setTimeout(() => {
                    closeModal('ban-modal');
                }, 2000);
                
            } else {
                // Ошибка от сервера
                let errorMsg = response.message || 'Не удалось создать апелляцию';
                console.error('❌ Ошибка сервера:', errorMsg);
                
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    html: `
                        <p style="color: #f44336; margin-bottom: 15px;">${errorMsg}</p>
                        <p style="color: #666; font-size: 14px;">Попробуйте использовать "Быструю апелляцию" ниже.</p>
                    `,
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('❌ AJAX Error:', xhr.responseText);
            
            // Пробуем распарсить ответ
            try {
                const resp = JSON.parse(xhr.responseText);
                if (resp.message) {
                    showToast('❌ ' + resp.message, 'error');
                }
            } catch(e) {}
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка соединения',
                text: 'Не удалось подключиться к серверу. Попробуйте позже.',
                confirmButtonText: 'OK'
            });
        }
    });
}

// ✅ ПЕРЕОПРЕДЕЛЯЕМ submitSimpleAppeal для гарантированной работы
function submitSimpleAppeal() {
    const userId = $('#appeal_user_id').val();
    const email = $('#appeal_email').val();
    const customMessage = $('#appeal_message').val();
    
    if (!userId || userId <= 0) {
        showToast('❌ Введите ID пользователя', 'error');
        return;
    }
    
    // Показываем загрузку
    Swal.fire({
        title: '📤 Отправка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    // Формируем сообщение
    const subject = `АПЕЛЛЯЦИЯ: Быстрая форма (ID: ${userId})`;
    const message = `**Быстрая апелляция**\n\n` +
                    `👤 ID пользователя: ${userId}\n` +
                    `📧 Email: ${email || 'Не указан'}\n` +
                    `📅 Дата: ${new Date().toLocaleString('ru-RU')}\n\n` +
                    `💬 Сообщение:\n${customMessage || 'Пользователь запросил разблокировку аккаунта.'}`;
    
    // Отправляем запрос
    $.ajax({
        url: 'api.php?action=create_ticket',
        method: 'POST',
        data: {
            subject: subject,
            message: message,
            user_id: userId
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                closeModal('simple-appeal-modal');
                closeModal('ban-modal');
                
                Swal.fire({
                    icon: 'success',
                    title: '✅ Апелляция отправлена!',
                    html: `
                        <p style="margin-bottom: 15px; font-size: 16px;">
                            <span style="background: #4CAF50; color: white; padding: 4px 10px; border-radius: 20px;">
                                Тикет #${response.ticket_id}
                            </span>
                        </p>
                        <p style="color: #333;">Администратор рассмотрит вашу апелляцию в ближайшее время.</p>
                    `,
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось отправить апелляцию'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка сети',
                text: 'Проверьте подключение к интернету'
            });
        }
    });
}

// ✅ ГЛОБАЛЬНЫЙ ПЕРЕХВАТ КЛИКА
$(document).ready(function() {
    console.log('🔧 Инициализация системы апелляций');
    
    // Назначаем обработчик на все кнопки с надписью "апелляц"
    $(document).on('click', 'button:contains("апелляц"), button:contains("Апелляц"), .appeal-btn, [onclick*="appeal"]', function(e) {
        const btnText = $(this).text().toLowerCase();
        if (btnText.includes('апелляц') || btnText.includes('appeal')) {
            console.log('🖱️ Перехват клика по кнопке апелляции');
            e.preventDefault();
            e.stopImmediatePropagation();
            createBanAppealDirect();
            return false;
        }
    });


    function loadAdminAppeals() {
    // ЕСЛИ ТАБА НЕТ - СОЗДАЕМ
    if ($('#appeals-tab').length === 0) {
        $('.admin-panel').append('<div id="appeals-tab" class="admin-content"></div>');
        console.log('✅ Таб appeals создан принудительно');
    }
    
    // ЗАГРУЖАЕМ АПЕЛЛЯЦИИ
    $.get('api.php?action=adm_get_appeals', function(response) {
        console.log('Апелляции:', response);
        
        if (response.status === 'success') {
            let html = '<div style="padding:20px;">';
            html += '<h3>Апелляции (' + response.count + ')</h3>';
            
            if (response.appeals.length > 0) {
                response.appeals.forEach(function(a) {
                    html += '<div style="background:#fff3e0; padding:15px; margin-bottom:10px; border-radius:10px;">' +
                            '👤 Пользователь ID: <strong>' + a.user_id + '</strong><br>' +
                            '📅 Дата: ' + (a.created_at || '') + '<br>' +
                            '📌 Статус: ' + (a.status || 'new') + '<br>' +
                            '<button onclick="adminUnbanFromAppeal(' + a.user_id + ', ' + a.ticket_id + ')" ' +
                            'style="background:#4CAF50; color:white; border:none; padding:8px 15px; border-radius:5px; margin-top:10px; cursor:pointer;">✅ Разбанить пользователя</button>' +
                            '</div>';
                });
            } else {
                html += '<p style="color:#666;">✅ Нет активных апелляций</p>';
            }
            
            html += '</div>';
            $('#appeals-tab').html(html);
            $('#appeals-count').text(response.count);
            
            // АКТИВИРУЕМ ТАБ
            $('#appeals-tab').addClass('active');
        }
    }, 'json').fail(function(xhr) {
        console.error('Ошибка:', xhr.responseText);
        $('#appeals-tab').html('<p style="color:red;">❌ Ошибка загрузки апелляций</p>');
    });
}

// ========== РАЗБАНИТЬ ЧЕРЕЗ АПЕЛЛЯЦИЮ ==========
function adminUnbanFromAppeal(userId, ticketId) {
    if (!userId) {
        showToast('❌ ID пользователя не указан', 'error');
        return;
    }
    
    // Подтверждение
    if (!confirm('✅ Разблокировать пользователя?')) {
        return;
    }
    
    // Отправляем запрос
    $.ajax({
        url: 'api.php?action=adm_unban',
        type: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        beforeSend: function() {
            showToast('⏳ Разблокировка...', 'info');
        },
        success: function(response) {
            if (response.status === 'success') {
                showToast('✅ Пользователь разблокирован!', 'success');
                loadAdminAppeals(); // Обновляем апелляции
                if (typeof loadAdminUsers === 'function') {
                    loadAdminUsers(); // Обновляем список пользователей
                }
            } else {
                showToast('❌ Ошибка: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            let msg = 'Ошибка соединения';
            try {
                let resp = JSON.parse(xhr.responseText);
                msg = resp.message || msg;
            } catch(e) {}
            showToast('❌ ' + msg, 'error');
        }
    });
}

function adminOpenTicket(ticketId) {
    if (!ticketId) {
        showToast('ID тикета не указан', 'warning');
        return;
    }
    if (typeof showTicketMessages === 'function') {
        showTicketMessages(ticketId, 'Апелляция #' + ticketId);
    } else {
        window.location.href = 'tickets.php?id=' + ticketId;
    }
}


// ========== ПЕРЕКЛЮЧЕНИЕ ВКЛАДОК АДМИНКИ ==========
function showAdminTab(tab) {
    // Убираем активный класс у всех вкладок
    $('.admin-tab').removeClass('active');
    // Убираем активный класс у всех контентов
    $('.admin-content').removeClass('active');
    
    // Активируем нужную вкладку
    $(`.admin-tab:contains("${getTabName(tab)}")`).addClass('active');
    // Показываем нужный контент
    $(`#${tab}-tab`).addClass('active');
    
    // Загружаем данные для вкладки
    switch(tab) {
        case 'stats':
            loadAdminStats();
            break;
        case 'users':
            loadAdminUsers();
            break;
        case 'products':
            loadAdminProducts();
            break;
        case 'promos':
            loadAdminPromos();
            break;
        case 'tickets':
            loadAdminTickets();
            break;
        case 'appeals': // 👈 ЭТО НАША НОВАЯ ВКЛАДКА
            loadAdminAppeals();
            break;
        case 'logs':
            loadAdminLogs();
            break;
    }
}

// ========== ПОЛУЧЕНИЕ НАЗВАНИЯ ВКЛАДКИ ==========
function getTabName(tab) {
    const names = {
        'stats': 'Статистика',
        'users': 'Пользователи',
        'products': 'Товары',
        'promos': 'Промокоды',
        'tickets': 'Тикеты',
        'appeals': 'Апелляции', // 👈 ЭТО НАША НОВАЯ ВКЛАДКА
        'logs': 'Логи'
    };
    return names[tab] || tab;
}


// ========== ЗАГРУЗКА АПЕЛЛЯЦИЙ ==========
function loadAdminAppeals() {
    $.ajax({
        url: 'api.php?action=adm_get_appeals',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                let html = `
                    <div style="padding: 25px;">
                        <h2 style="margin-bottom: 25px; color: #333; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-gavel" style="color: #ff9800;"></i>
                            Апелляции (${response.count})
                        </h2>
                `;
                
                if (response.appeals.length > 0) {
                    response.appeals.forEach(function(appeal) {
                        let date = appeal.created_at ? appeal.created_at.substring(0, 16).replace('T', ' ') : 'Нет даты';
                        
                        html += `
                            <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; 
                                        border-left: 6px solid #ff9800; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <div style="display: flex; gap: 10px;">
                                        <span style="background: #ff4757; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold;">
                                            <i class="fas fa-ban"></i> ID: ${appeal.user_id}
                                        </span>
                                        <span style="background: #f8f9fa; color: #333; padding: 5px 15px; border-radius: 20px;">
                                            <i class="fas fa-ticket-alt"></i> #${appeal.ticket_id || 0}
                                        </span>
                                        <span style="background: #ff9800; color: white; padding: 5px 15px; border-radius: 20px;">
                                            ${appeal.status || 'new'}
                                        </span>
                                    </div>
                                    <span style="color: #666; font-size: 13px;">
                                        <i class="fas fa-clock"></i> ${date}
                                    </span>
                                </div>
                                
                                <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                                    <strong style="color: #e65100;">📌 Причина бана:</strong> 
                                    ${appeal.ban_reason || 'Не указана'}
                                </div>
                                
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                                    <strong>💬 Сообщение:</strong>
                                    <p style="margin: 10px 0 0 0; color: #333;">${appeal.message || 'Нет сообщения'}</p>
                                </div>
                                
                                
                                <button onclick="adminReplyToAppeal(${appeal.id}, ${appeal.user_id}, ${appeal.ticket_id})" 
        style="background: #2196F3; color: white; border: none; padding: 12px 25px; 
               border-radius: 8px; font-size: 14px; font-weight: bold; cursor: pointer;
               display: flex; align-items: center; gap: 8px;">
    <i class="fas fa-reply"></i> Ответить
</button>
                        `;
                    });
                } else {
                    html += `
                        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px;">
                            <i class="fas fa-check-circle" style="font-size: 60px; color: #4CAF50;"></i>
                            <h3 style="margin-top: 20px; color: #666;">Апелляций нет</h3>
                            <p style="color: #999;">Все апелляции обработаны</p>
                        </div>
                    `;
                }
                
                html += `</div>`;
                $('#appeals-tab').html(html);
                $('#appeals-count').text(response.count);
            }
        },
        error: function(xhr) {
            $('#appeals-tab').html(`
                <div style="padding: 25px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Ошибка загрузки: ${xhr.status} ${xhr.statusText}
                </div>
            `);
        }
    });
}

// ========== РАЗБАНИТЬ ЧЕРЕЗ АПЕЛЛЯЦИЮ ==========
function adminUnbanFromAppeal(userId, ticketId) {
    if (!confirm('✅ Разблокировать пользователя?')) return;
    
    $.ajax({
        url: 'api.php?action=adm_unban',
        type: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
             showToast('✅ Пользователь разблокирован!');
                loadAdminAppeals();
                if (typeof loadAdminUsers === 'function') loadAdminUsers();
            } else {
                 showToast('❌ Ошибка: ' + response.message);
            }
        },
        error: function() {
             showToast('❌ Ошибка соединения');
        }
    });
}


// ========== МОИ АПЕЛЛЯЦИИ - ПРОСТАЯ РАБОЧАЯ ВЕРСИЯ ==========
function userViewMyAppeals() {
     showToast('🔍 Функция вызвана!');
    
    let userId = 0;
    if (typeof currentUser !== 'undefined' && currentUser) {
        userId = currentUser.id || 0;
         showToast('ID пользователя: ' + userId);
    }
    
    if (!userId) {
        userId = prompt('Введите ваш ID:');
        if (!userId) return;
    }
    
    // Показываем заглушку
    Swal.fire({
        title: '📋 Мои апелляции',
        html: `
            <div style="text-align: left; padding: 10px;">
                <p style="color: #666;">Загрузка апелляций для ID: <strong>${userId}</strong></p>
                <div style="background: #f0f0f0; padding: 15px; border-radius: 10px; margin-top: 15px;">
                    <i class="fas fa-spinner fa-spin"></i> Получение данных...
                </div>
            </div>
        `,
        showConfirmButton: false,
        didOpen: () => {
            // Загружаем апелляции
            $.get('api.php?action=get_user_appeals&user_id=' + userId, function(response) {
                Swal.close();
                if (response.status === 'success') {
                    let html = '<div style="text-align: left; padding: 10px;">';
                    html += '<h3 style="margin-bottom: 15px;">Мои апелляции (' + response.count + ')</h3>';
                    
                    if (response.appeals.length > 0) {
                        response.appeals.forEach(function(a) {
                            html += '<div style="background: #fff3e0; padding: 15px; margin-bottom: 15px; border-radius: 10px;">';
                            html += '<p><strong>ID апелляции:</strong> ' + a.id + '</p>';
                            html += '<p><strong>Статус:</strong> ' + (a.status || 'new') + '</p>';
                            html += '<p><strong>Дата:</strong> ' + (a.created_at || '') + '</p>';
                            html += '<p><strong>Причина бана:</strong> ' + (a.ban_reason || 'Не указана') + '</p>';
                            if (a.admin_response) {
                                html += '<p><strong>Ответ админа:</strong> ' + a.admin_response + '</p>';
                            }
                            html += '</div>';
                        });
                    } else {
                        html += '<p style="color: #666;">У вас нет апелляций</p>';
                    }
                    html += '</div>';
                    
                    Swal.fire({
                        title: '📋 Мои апелляции',
                        html: html,
                        width: '600px',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire('Ошибка', response.message, 'error');
                }
            }, 'json').fail(function() {
                Swal.close();
                Swal.fire('Ошибка', 'Не удалось загрузить апелляции', 'error');
            });
        }
    });
}

// Проверка что функция загружена
console.log('✅ Функция userViewMyAppeals загружена!');

// ========== ЗАГРУЗКА АПЕЛЛЯЦИЙ ПОЛЬЗОВАТЕЛЯ ==========
function loadUserAppeals(userId) {
    console.log('📥 Загрузка апелляций для ID:', userId);
    
    // Показываем загрузку
    Swal.fire({
        title: 'Загрузка...',
        text: 'Получаем ваши апелляции',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=get_user_appeals',
        type: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            console.log('📦 Ответ сервера:', response);
            
            if (response.status === 'success') {
                showUserAppealsModal(response.appeals || [], userId);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка',
                    text: response.message || 'Не удалось загрузить апелляции'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            
            let errorMsg = 'Ошибка соединения';
            try {
                const resp = JSON.parse(xhr.responseText);
                errorMsg = resp.message || errorMsg;
            } catch(e) {}
            
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: errorMsg
            });
        }
    });
}



// ========== ПОКАЗ АПЕЛЛЯЦИЙ В КРАСИВОЙ МОДАЛКЕ С ДИАЛОГОМ ==========
function showUserAppealsModal(appeals, userInfo) {
    let html = `
        <div style="max-height: 600px; overflow-y: auto; padding: 5px;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; position: sticky; top: 0; background: white; z-index: 10;">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #ff9800, #ff5722); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 26px; box-shadow: 0 5px 15px rgba(255,87,34,0.3);">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: #333; font-size: 24px; font-weight: 700;">Мои апелляции</h3>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">${userInfo}</p>
                </div>
                <span style="background: #ff9800; color: white; padding: 6px 18px; border-radius: 25px; font-size: 14px; font-weight: 600; margin-left: auto; box-shadow: 0 3px 10px rgba(255,152,0,0.3);">
                    <i class="fas fa-list"></i> Всего: ${appeals.length}
                </span>
            </div>
    `;

    appeals.forEach(appeal => {
        let date = appeal.created_at ? new Date(appeal.created_at).toLocaleString('ru-RU', {
            day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }) : 'Нет даты';
        
        let statusColor = appeal.status === 'new' ? '#ff9800' : 
                        appeal.status === 'in_progress' ? '#2196F3' : 
                        appeal.status === 'closed' ? '#4CAF50' : '#6c757d';
        
        let statusText = appeal.status === 'new' ? '🟡 Новая' : 
                       appeal.status === 'in_progress' ? '🔵 В работе' : 
                       appeal.status === 'closed' ? '🟢 Решена' : '⚪ Закрыта';
        
        let statusBg = appeal.status === 'new' ? 'rgba(255,152,0,0.1)' : 
                      appeal.status === 'in_progress' ? 'rgba(33,150,243,0.1)' : 
                      appeal.status === 'closed' ? 'rgba(76,175,80,0.1)' : 'rgba(108,117,125,0.1)';

        html += `
            <div style="background: white; border-radius: 20px; padding: 25px; margin-bottom: 25px; 
                border-left: 8px solid ${statusColor}; box-shadow: 0 8px 25px rgba(0,0,0,0.05); 
                transition: all 0.3s ease; position: relative;"
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.05)';">
                
                <!-- Шапка карточки -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <span style="background: ${statusColor}; color: white; padding: 6px 18px; 
                            border-radius: 25px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px;
                            box-shadow: 0 3px 10px ${statusColor}40;">
                            ${statusText}
                        </span>
                        <span style="background: #667eea; color: white; padding: 6px 18px; 
                            border-radius: 25px; font-size: 13px; font-weight: 600;
                            box-shadow: 0 3px 10px rgba(102,126,234,0.3);">
                            <i class="fas fa-ticket-alt"></i> № ${appeal.ticket_id || appeal.id}
                        </span>
                        ${appeal.last_message_at ? `
                            <span style="background: #ff6b6b; color: white; padding: 6px 18px; 
                                border-radius: 25px; font-size: 12px; font-weight: 600;
                                box-shadow: 0 3px 10px rgba(255,107,107,0.3);">
                                <i class="fas fa-comment-dots"></i> Есть новые сообщения
                            </span>
                        ` : ''}
                    </div>
                    <span style="color: #666; font-size: 13px; background: #f8f9fa; padding: 6px 15px; border-radius: 20px;">
                        <i class="fas fa-clock"></i> ${date}
                    </span>
                </div>

                <!-- Информация о бане -->
                <div style="background: ${statusBg}; padding: 18px; border-radius: 15px; margin-bottom: 20px; border: 1px solid ${statusColor}20;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div style="width: 32px; height: 32px; background: ${statusColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-ban" style="font-size: 14px;"></i>
                        </div>
                        <strong style="color: ${statusColor}; font-size: 15px;">Причина блокировки:</strong>
                    </div>
                    <p style="margin: 0 0 0 44px; color: #333; font-size: 15px; line-height: 1.5;">${appeal.ban_reason || 'Не указана'}</p>
                </div>

                <!-- Сообщение пользователя -->
                <div style="background: #f8f9fa; padding: 18px; border-radius: 15px; margin-bottom: 20px; border: 1px solid #e9ecef;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div style="width: 32px; height: 32px; background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-user"></i>
                        </div>
                        <strong style="color: #333;">Ваше обращение:</strong>
                    </div>
                    <p style="margin: 0 0 0 44px; color: #495057; white-space: pre-wrap; line-height: 1.6; font-size: 15px;">
                        ${appeal.message || 'Нет текста'}
                    </p>
                </div>
        `;

        // Ответ администратора
        if (appeal.admin_response) {
            html += `
                <div style="background: #e3f2fd; padding: 18px; border-radius: 15px; margin-bottom: 20px; border-left: 6px solid #2196F3; border: 1px solid #bbdefb;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div style="width: 32px; height: 32px; background: #2196F3; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-crown"></i>
                        </div>
                        <strong style="color: #1565C0;">👨‍⚖️ Ответ администратора:</strong>
                    </div>
                    <p style="margin: 0 0 0 44px; color: #333; white-space: pre-wrap; line-height: 1.6; font-size: 15px;">
                        ${appeal.admin_response}
                    </p>
                    <p style="margin: 15px 0 0 44px; font-size: 12px; color: #666; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-check-circle" style="color: #4CAF50;"></i>
                        Ответ получен: ${appeal.updated_at ? new Date(appeal.updated_at).toLocaleString('ru-RU') : ''}
                    </p>
                </div>
            `;
        } else {
            html += `
                <div style="background: #fff3cd; padding: 18px; border-radius: 15px; margin-bottom: 20px; border-left: 6px solid #ffc107; border: 1px solid #ffeaa7;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div style="width: 32px; height: 32px; background: #ffc107; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <strong style="color: #856404;">⏳ Ожидание ответа:</strong>
                    </div>
                    <p style="margin: 0 0 0 44px; color: #666; font-size: 15px;">
                        Администратор еще не ответил на вашу апелляцию
                    </p>
                </div>
            `;
        }

        // Кнопки действий
        html += `
            <div style="display: flex; gap: 15px; margin-top: 25px; padding-top: 20px; border-top: 2px dashed #e0e0e0;">
                <button onclick='openAppealDialog(${appeal.id}, ${JSON.stringify(appeal).replace(/'/g, "\\'")})' 
                    style="flex: 2; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 14px 20px; 
                           border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer;
                           display: flex; align-items: center; justify-content: center; gap: 12px;
                           box-shadow: 0 8px 20px rgba(102,126,234,0.3); transition: all 0.3s;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(102,126,234,0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 20px rgba(102,126,234,0.3)';">
                    <i class="fas fa-comments"></i>
                    Открыть диалог с администратором
                    ${appeal.last_message_at ? '<span style="background: #ff4757; color: white; padding: 3px 10px; border-radius: 20px; font-size: 11px; margin-left: 8px;">Новые сообщения</span>' : ''}
                </button>
                
                ${appeal.status !== 'closed' ? `
                    <button onclick="closeAppeal(${appeal.id})" 
                        style="flex: 1; background: linear-gradient(135deg, #ff4757, #ff6b81); color: white; border: none; padding: 14px 20px; 
                               border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer;
                               display: flex; align-items: center; justify-content: center; gap: 12px;
                               box-shadow: 0 8px 20px rgba(255,71,87,0.3); transition: all 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(255,71,87,0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 20px rgba(255,71,87,0.3)';">
                        <i class="fas fa-lock"></i>
                        Закрыть апелляцию
                    </button>
                ` : `
                    <div style="flex: 1; background: #6c757d; color: white; padding: 14px 20px; 
                        border-radius: 12px; font-size: 15px; font-weight: 700; 
                        display: flex; align-items: center; justify-content: center; gap: 12px; opacity: 0.7;">
                        <i class="fas fa-check-circle"></i>
                        Апелляция закрыта
                    </div>
                `}
            </div>
        `;

        html += `</div>`;
    });

    html += `</div>`;

    Swal.fire({
        title: '',
        html: html,
        showConfirmButton: false,
        showCloseButton: true,
        width: '800px',
        padding: '20px',
        background: 'white',
        customClass: {
            popup: 'appeals-modal',
            closeButton: 'custom-close-btn'
        }
    });
}

// ========== ФУНКЦИЯ ДЛЯ ЗАКРЫТИЯ АПЕЛЛЯЦИИ ==========
function closeAppeal(appealId) {
    Swal.fire({
        title: '❓ Закрыть апелляцию?',
        text: 'Вы уверены, что хотите закрыть эту апелляцию? Диалог будет завершен.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Да, закрыть',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api.php?action=close_appeal',
                type: 'POST',
                data: { appeal_id: appealId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Апелляция закрыта!',
                            text: 'Диалог завершен',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Обновляем список апелляций
                        if (currentUser) {
                            loadUserAppeals(currentUser.id);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message
                        });
                    }
                }
            });
        }
    });
}

// ========== ДОБАВЛЯЕМ СТИЛИ ==========
$('<style>')
    .prop('type', 'text/css')
    .html(`
        .appeals-modal .swal2-popup {
            padding: 25px !important;
            border-radius: 25px !important;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2) !important;
        }
        .custom-close-btn {
            color: #666 !important;
            font-size: 28px !important;
            transition: 0.3s !important;
            position: absolute !important;
            right: 20px !important;
            top: 20px !important;
        }
        .custom-close-btn:hover {
            color: #ff4757 !important;
            transform: rotate(90deg);
        }
        #appeal-messages-container::-webkit-scrollbar {
            width: 8px;
        }
        #appeal-messages-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        #appeal-messages-container::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }
        #appeal-messages-container::-webkit-scrollbar-thumb:hover {
            background: #5a67d8;
        }
    `)
    .appendTo('head');


// ========== АДМИН: ОТВЕТИТЬ НА АПЕЛЛЯЦИЮ ==========
function adminReplyToAppeal(appealId, userId, ticketId) {
    console.log('💬 Ответ на апелляцию:', appealId, userId, ticketId);
    
    if (!appealId || !userId) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'ID апелляции или пользователя не указан'
        });
        return;
    }
    
    Swal.fire({
        title: '✍️ Ответ на апелляцию',
        html: `
            <div style="text-align: left;">
                <p style="margin-bottom: 15px; color: #666;">
                    <i class="fas fa-info-circle" style="color: #2196F3;"></i> 
                    Пользователь ID: <strong>${userId}</strong>
                    ${ticketId ? ` • Тикет #${ticketId}` : ''}
                </p>
                <textarea id="admin-response-text" rows="6" 
                    style="width: 100%; padding: 15px; border: 2px solid #e0e0e0; border-radius: 12px; 
                           font-size: 14px; resize: vertical; font-family: inherit;"
                    placeholder="Введите ваш ответ пользователю..."></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '📨 Отправить ответ',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        width: '600px',
        preConfirm: () => {
            const response = document.getElementById('admin-response-text').value.trim();
            if (!response) {
                Swal.showValidationMessage('Введите текст ответа');
                return false;
            }
            return response;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            sendAppealReply(appealId, userId, ticketId, result.value);
        }
    });
}

// ========== ОТПРАВКА ОТВЕТА ==========
function sendAppealReply(appealId, userId, ticketId, message) {
    console.log('📤 Отправка ответа на апелляцию');
    
    Swal.fire({
        title: '⏳ Отправка...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=admin_reply_appeal',
        type: 'POST',
        data: {
            appeal_id: appealId,
            user_id: userId,
            ticket_id: ticketId || 0,
            response: message
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Ответ отправлен!',
                    text: 'Пользователь увидит ваш ответ в разделе "Мои апелляции"',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Обновляем список апелляций
                if (typeof loadAdminAppeals === 'function') {
                    setTimeout(loadAdminAppeals, 500);
                }
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось отправить ответ'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            
            let errorMsg = 'Ошибка соединения с сервером';
            try {
                let resp = JSON.parse(xhr.responseText);
                errorMsg = resp.message || errorMsg;
            } catch(e) {}
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: errorMsg
            });
        }
    });
}

// ========== ОБНОВЛЕННАЯ КАРТОЧКА АПЕЛЛЯЦИИ (ДОБАВЬ КНОПКУ ОТВЕТА) ==========
// ВСТАВЬ ЭТУ КНОПКУ РЯДОМ С КНОПКОЙ "РАЗБАНИТЬ"
`
<button onclick="adminReplyToAppeal(${appeal.id}, ${appeal.user_id}, ${appeal.ticket_id})" 
        style="background: #2196F3; color: white; border: none; padding: 12px 25px; 
               border-radius: 8px; font-size: 14px; font-weight: bold; cursor: pointer;
               display: flex; align-items: center; gap: 8px; margin-left: 10px;">
    <i class="fas fa-reply"></i>
    Ответить
</button>
`


});

     </script>


<script>
// ========== МОИ АПЕЛЛЯЦИИ - МОДАЛКА С ВВОДОМ ЛОГИНА ==========
window.userViewMyAppeals = function() {
    console.log('🖱️ Нажата кнопка "Мои апелляции"');
    
    Swal.fire({
        title: '🔍 Просмотр апелляций',
        html: `
            <div style="text-align: center; padding: 10px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #ff9800, #ff5722); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;
                    box-shadow: 0 10px 25px rgba(255,87,34,0.3);">
                    <i class="fas fa-gavel" style="font-size: 50px; color: white;"></i>
                </div>
                
                <h3 style="color: #333; margin-bottom: 20px; font-size: 24px;">
                    Введите ваш логин
                </h3>
                
                <div style="margin-bottom: 25px;">
                    <input type="text" id="appeal-login-input" class="swal2-input" 
                        placeholder="Например: user123" 
                        style="width: 100%; padding: 15px; border: 2px solid #e0e0e0; 
                               border-radius: 12px; font-size: 16px; margin: 0;
                               transition: 0.3s;"
                        onfocus="this.style.borderColor='#ff9800'"
                        onblur="this.style.borderColor='#e0e0e0'">
                </div>
                
                <p style="color: #666; font-size: 14px; margin-bottom: 10px;">
                    <i class="fas fa-info-circle" style="color: #ff9800;"></i>
                    Введите логин, который использовали при регистрации
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '📋 Показать апелляции',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#ff9800',
        cancelButtonColor: '#6c757d',
        width: '500px',
        background: 'white',
        preConfirm: () => {
            const login = document.getElementById('appeal-login-input').value.trim();
            if (!login) {
                Swal.showValidationMessage('Введите логин!');
                return false;
            }
            return login;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            loadUserAppealsByLogin(result.value);
        }
    });
};

// ========== ЗАГРУЗКА АПЕЛЛЯЦИЙ ПО ЛОГИНУ ==========
function loadUserAppealsByLogin(login) {
    Swal.fire({
        title: '⏳ Загрузка...',
        html: '<div style="text-align: center;"><i class="fas fa-spinner fa-spin" style="font-size: 40px; color: #ff9800;"></i><p style="margin-top: 20px;">Получаем ваши апелляции</p></div>',
        allowOutsideClick: false,
        background: 'white',
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: 'api.php?action=get_user_appeals_by_login',
        type: 'GET',
        data: { login: login },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                if (response.appeals && response.appeals.length > 0) {
                    showUserAppealsModal(response.appeals, 'Логин: ' + login);
                } else {
                    showEmptyAppealsModal(login);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Пользователь не найден',
                    confirmButtonColor: '#ff9800'
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Не удалось соединиться с сервером',
                confirmButtonColor: '#ff9800'
            });
        }
    });
}

// ========== ПОКАЗ АПЕЛЛЯЦИЙ В КРАСИВОЙ МОДАЛКЕ ==========
function showUserAppealsModal(appeals, userInfo) {
    let html = `
        <div style="max-height: 500px; overflow-y: auto; padding: 5px;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #ff9800, #ff5722); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 26px;">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: #333; font-size: 24px;">Мои апелляции</h3>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">${userInfo}</p>
                </div>
                <span style="background: #ff9800; color: white; padding: 6px 18px; border-radius: 25px; font-size: 14px; margin-left: auto;">
                    Всего: ${appeals.length}
                </span>
            </div>
    `;

    appeals.forEach(appeal => {
        let date = appeal.created_at ? new Date(appeal.created_at).toLocaleString('ru-RU', {
            day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }) : 'Нет даты';
        
        let statusColor = appeal.status === 'new' ? '#ff9800' : 
                        appeal.status === 'in_progress' ? '#2196F3' : 
                        appeal.status === 'closed' ? '#4CAF50' : '#6c757d';
        
        let statusText = appeal.status === 'new' ? '🟡 Новая' : 
                       appeal.status === 'in_progress' ? '🔵 В работе' : 
                       appeal.status === 'closed' ? '🟢 Решена' : '⚪ Закрыта';

        html += `
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 20px; 
                border-left: 6px solid ${statusColor}; box-shadow: 0 3px 10px rgba(0,0,0,0.05);">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <span style="background: ${statusColor}; color: white; padding: 6px 18px; 
                            border-radius: 25px; font-size: 12px; font-weight: bold;">
                            ${statusText}
                        </span>
                        <span style="background: #667eea; color: white; padding: 6px 18px; 
                            border-radius: 25px; font-size: 12px;">
                            № ${appeal.ticket_id || appeal.id}
                        </span>
                    </div>
                    <span style="color: #666; font-size: 12px;">
                        <i class="fas fa-clock"></i> ${date}
                    </span>
                </div>

                <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fas fa-ban" style="color: #ff4757;"></i>
                        <strong style="color: #e65100;">Причина блокировки:</strong>
                    </div>
                    <p style="margin: 0 0 0 28px; color: #333;">${appeal.ban_reason || 'Не указана'}</p>
                </div>

                <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <i class="fas fa-user" style="color: #667eea;"></i>
                        <strong style="color: #333;">Ваше обращение:</strong>
                    </div>
                    <p style="margin: 0 0 0 28px; color: #666; white-space: pre-wrap;">${appeal.message || 'Нет текста'}</p>
                </div>
        `;

        if (appeal.admin_response) {
            html += `
                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; border-left: 4px solid #2196F3;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i class="fas fa-crown" style="color: #2196F3;"></i>
                        <strong style="color: #1565C0;">👨‍⚖️ Ответ администратора:</strong>
                    </div>
                    <p style="margin: 0 0 0 28px; color: #333; white-space: pre-wrap;">${appeal.admin_response}</p>
                    <p style="margin: 10px 0 0 28px; font-size: 11px; color: #666;">
                        <i class="fas fa-check-circle" style="color: #4CAF50;"></i>
                        ${appeal.updated_at ? new Date(appeal.updated_at).toLocaleString('ru-RU') : ''}
                    </p>
                </div>
            `;
        } else {
            html += `
                <div style="background: #fff3cd; padding: 15px; border-radius: 10px; border-left: 4px solid #ffc107;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i class="fas fa-clock" style="color: #856404;"></i>
                        <strong style="color: #856404;">⏳ Ожидание ответа:</strong>
                    </div>
                    <p style="margin: 0 0 0 28px; color: #666;">Администратор еще не ответил на вашу апелляцию</p>
                </div>
            `;
        }

        html += `</div>`;
    });

    html += `</div>`;

    Swal.fire({
        title: '',
        html: html,
        showConfirmButton: false,
        showCloseButton: true,
        width: '700px',
        background: 'white',
        customClass: {
            popup: 'appeals-modal',
            closeButton: 'custom-close-btn'
        }
    });
}


// ========== ПУСТАЯ МОДАЛКА ==========
function showEmptyAppealsModal(login) {
    Swal.fire({
        icon: 'info',
        title: '❌ Апелляций нет',
        html: `
            <div style="text-align: center; padding: 20px;">
                <div style="width: 100px; height: 100px; background: #f8f9fa; border-radius: 50%; 
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                    <i class="fas fa-inbox" style="font-size: 50px; color: #cbd5e0;"></i>
                </div>
                <p style="color: #666; margin-bottom: 15px; font-size: 18px;">
                    По логину <strong style="color: #ff9800;">${login}</strong> апелляций не найдено
                </p>
                <p style="color: #999; margin-bottom: 25px;">
                    Если вас заблокируют, вы сможете подать апелляцию через окно блокировки
                </p>
                <button onclick="Swal.close(); window.userSubmitBanAppeal ? window.userSubmitBanAppeal() : null" 
                    style="background: linear-gradient(135deg, #ff9800, #ff5722); color: white; border: none; 
                           padding: 14px 35px; border-radius: 50px; font-size: 16px; font-weight: bold; 
                           cursor: pointer; display: inline-flex; align-items: center; gap: 12px;
                           box-shadow: 0 10px 25px rgba(255,87,34,0.3);">
                    <i class="fas fa-gavel"></i>
                    Подать апелляцию
                </button>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '500px',
        background: 'white'
    });
}

// ========== ЗАГРУЗКА АПЕЛЛЯЦИЙ ==========
function loadUserAppeals(userId) {
    console.log('📥 Загрузка апелляций для ID:', userId);
    
    Swal.fire({
        title: '📋 Загрузка...',
        html: '<div style="text-align: center;"><i class="fas fa-spinner fa-spin" style="font-size: 40px; color: #667eea;"></i><p style="margin-top: 20px;">Получаем ваши обращения</p></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        background: 'white'
    });
    
    $.ajax({
        url: 'api.php?action=get_user_appeals',
        type: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            console.log('📦 Ответ сервера:', response);
            
            if (response.status === 'success') {
                showUserAppealsModal(response.appeals || [], userId);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось загрузить апелляции',
                    confirmButtonColor: '#667eea'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            
            let errorMsg = 'Ошибка соединения с сервером';
            try {
                const resp = JSON.parse(xhr.responseText);
                errorMsg = resp.message || errorMsg;
            } catch(e) {}
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: errorMsg,
                confirmButtonColor: '#667eea'
            });
        }
    });
}



// ========== УЛЬТРА-ПРИНУДИТЕЛЬНЫЙ ПОКАЗ МОДАЛКИ ==========
function forceShowModal(modalId) {
    console.log('💪 ФОРСИРОВАННЫЙ ПОКАЗ:', modalId);
    
    // НАХОДИМ МОДАЛКУ
    let modal = document.getElementById(modalId);
    if (!modal) {
        console.error('❌ Модалка не найдена!');
        return;
    }
    
    // УДАЛЯЕМ ВСЕ КЛАССЫ И СТАВИМ СВОИ СТИЛИ ПРЯМО В ЭЛЕМЕНТ
    modal.style.cssText = `
        display: flex !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: rgba(0, 0, 0, 0.8) !important;
        z-index: 9999999 !important;
        justify-content: center !important;
        align-items: center !important;
        opacity: 1 !important;
        visibility: visible !important;
        margin: 0 !important;
        padding: 0 !important;
    `;
    
    // СТИЛИ ДЛЯ КОНТЕНТА
    let content = modal.querySelector('.modal-content');
    if (content) {
        content.style.cssText = `
            background: white !important;
            padding: 30px !important;
            border-radius: 15px !important;
            max-width: 500px !important;
            width: 90% !important;
            max-height: 90vh !important;
            overflow-y: auto !important;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
            transform: scale(1) !important;
            opacity: 1 !important;
            margin: auto !important;
            border: none !important;
            position: relative !important;
        `;
    }
    
    // БЛОКИРУЕМ ПРОКРУТКУ
    document.body.style.overflow = 'hidden';
    
    console.log('✅ Модалка принудительно показана!');
    return modal;
}



// ========== ПОДАЧА АПЕЛЛЯЦИИ - МОДАЛКА + TOAST ==========
function userSubmitBanAppeal() {
    let modal = document.createElement('div');
    modal.id = 'appeal-modal-final';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.85);
        z-index: 999999;
        display: flex;
        justify-content: center;
        align-items: center;
    `;
    
    modal.innerHTML = `
        <div style="background: white; border-radius: 25px; width: 90%; max-width: 450px; padding: 30px; box-shadow: 0 25px 60px rgba(0,0,0,0.4);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 50px; height: 50px; background: #ff9800; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-gavel" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h3 style="margin: 0; color: #333; font-size: 22px;">Подача апелляции</h3>
                </div>
                <button onclick="this.closest('#appeal-modal-final').remove()" style="background: none; border: none; font-size: 28px; cursor: pointer; color: #666;">&times;</button>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">
                    <i class="fas fa-user" style="color: #ff9800; margin-right: 8px;"></i>
                    Ваш логин
                </label>
                <input type="text" id="appeal-login-final" placeholder="Введите ваш логин" 
                    style="width: 100%; padding: 15px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; box-sizing: border-box;"
                    autofocus>
            </div>
            
            <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                <p style="margin: 0; color: #666; font-size: 14px;">
                    <i class="fas fa-info-circle" style="color: #ff9800;"></i>
                    Администратор ответит в течение 24 часов
                </p>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button onclick="submitAppealFinal()" style="flex: 2; background: #ff9800; color: white; border: none; padding: 15px; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer;">
                    <i class="fas fa-paper-plane"></i>
                    Отправить
                </button>
                <button onclick="this.closest('#appeal-modal-final').remove()" style="flex: 1; background: #6c757d; color: white; border: none; padding: 15px; border-radius: 12px; font-size: 16px; cursor: pointer;">
                    Отмена
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// ========== ОТПРАВКА АПЕЛЛЯЦИИ ==========
window.submitAppealFinal = function() {
    let login = document.getElementById('appeal-login-final')?.value.trim();
    
    if (!login) {
        showToast('❌ Введите логин!', 'error');
        return;
    }
    
    let modal = document.getElementById('appeal-modal-final');
    if (modal) modal.remove();
    
    showToast('⏳ Отправка апелляции...', 'info');
    
    fetch('api.php?action=get_user_id_by_login&login=' + encodeURIComponent(login))
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success' && data.user_id) {
            return fetch('api.php?action=create_ban_appeal', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + data.user_id + '&login=' + encodeURIComponent(login)
            }).then(r => r.json());
        } else {
            throw new Error('Пользователь не найден');
        }
    })
    .then(result => {
        if (result.status === 'success') {
            showToast('✅ Апелляция #' + result.ticket_id + ' создана!', 'success');
            let banModal = document.getElementById('ban-modal');
            if (banModal) banModal.style.display = 'none';
        } else {
            showToast('❌ ' + (result.message || 'Ошибка'), 'error');
        }
    })
    .catch(error => {
        showToast('❌ ' + error.message, 'error');
    });
};

// ========== ОТПРАВКА АПЕЛЛЯЦИИ ==========
window.submitAppealFinal = function() {
    let login = document.getElementById('appeal-login-final')?.value.trim();
    
    if (!login) {
         showToast('❌ Введите логин!');
        return;
    }
    
    // Закрываем модалку
    let modal = document.getElementById('appeal-modal-final');
    if (modal) modal.remove();
    
    // Отправляем
    fetch('api.php?action=get_user_id_by_login&login=' + encodeURIComponent(login))
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success' && data.user_id) {
            return fetch('api.php?action=create_ban_appeal', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'user_id=' + data.user_id + '&login=' + encodeURIComponent(login)
            }).then(r => r.json());
        } else {
            throw new Error('Пользователь не найден');
        }
    })
    .then(result => {
        if (result.status === 'success') {
            showToast('✅ Апелляция #' + result.ticket_id + ' создана!');
            let banModal = document.getElementById('ban-modal');
            if (banModal) banModal.style.display = 'none';
        } else {
            showToast('❌ ' + (result.message || 'Ошибка'));
        }
    })
    .catch(error => {
         showToast('❌ ' + error.message);
    });
};

// ========== ЗАГРУЗКА СООБЩЕНИЙ АПЕЛЛЯЦИИ ==========
function loadAppealMessages(appealId) {
    $.ajax({
        url: 'api.php?action=get_appeal_messages',
        type: 'GET',
        data: { appeal_id: appealId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                renderAppealMessages(response.messages, response.appeal);
                
                // Помечаем сообщения как прочитанные
                if (currentUser) {
                    $.post('api.php?action=mark_appeal_messages_read', {
                        appeal_id: appealId,
                        user_id: currentUser.id
                    });
                }
            }
        }
    });
}

// ========== ОТОБРАЖЕНИЕ СООБЩЕНИЙ ==========
function renderAppealMessages(messages, appeal) {
    const container = document.getElementById('appeal-messages-container');
    if (!container) return;
    
    if (!messages || messages.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 50px 20px; background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-comments" style="font-size: 40px; color: #94a3b8;"></i>
                </div>
                <h4 style="color: #475569; margin-bottom: 10px;">Нет сообщений</h4>
                <p style="color: #64748b; max-width: 300px; margin: 0 auto;">
                    Напишите первое сообщение, чтобы начать диалог с администратором
                </p>
            </div>
        `;
        return;
    }
    
    let html = '';
    const currentUserId = currentUser ? currentUser.id : 0;
    
    messages.forEach(msg => {
        const isMe = msg.user_id == currentUserId;
        const time = new Date(msg.created_at).toLocaleString('ru-RU', {
            hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit'
        });
        
        let senderName = msg.sender_login || 'Пользователь';
        let avatarBg = isMe ? '#4CAF50' : (msg.sender_type === 'admin' ? '#2196F3' : '#667eea');
        let avatarIcon = msg.sender_type === 'admin' ? 'fa-crown' : 'fa-user';
        let messageBg = isMe ? '#e3f2fd' : (msg.sender_type === 'admin' ? '#f0f9ff' : '#f1f5f9');
        let messageBorder = isMe ? '#bbdefb' : (msg.sender_type === 'admin' ? '#b8e2f2' : '#e2e8f0');
        let align = isMe ? 'flex-end' : 'flex-start';
        
        html += `
            <div style="display: flex; justify-content: ${align}; margin-bottom: 15px; animation: fadeIn 0.3s;">
                <div style="max-width: 80%; min-width: 250px;">
                    <div style="display: flex; align-items: flex-end; gap: 12px; flex-direction: ${isMe ? 'row-reverse' : 'row'};">
                        <div style="width: 42px; height: 42px; border-radius: 50%; background: ${avatarBg}; 
                            display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; 
                            flex-shrink: 0; box-shadow: 0 4px 10px ${avatarBg}40;">
                            <i class="fas ${avatarIcon}"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 6px; text-align: ${isMe ? 'right' : 'left'}; display: flex; justify-content: ${isMe ? 'flex-end' : 'flex-start'}; gap: 8px;">
                                <span style="font-weight: 600; color: ${avatarBg};">${isMe ? 'Вы' : (msg.sender_type === 'admin' ? '👨‍⚖️ Администратор' : senderName)}</span>
                                <span style="color: #94a3b8;">${time}</span>
                            </div>
                            <div style="background: ${messageBg}; color: #1e293b; padding: 14px 18px; 
                                border-radius: ${isMe ? '18px 18px 4px 18px' : '18px 18px 18px 4px'}; 
                                border: 1px solid ${messageBorder}; box-shadow: 0 2px 8px rgba(0,0,0,0.02);
                                word-break: break-word; white-space: pre-wrap; line-height: 1.5; font-size: 14px;">
                                ${msg.message}
                            </div>
                            ${msg.is_read == 1 && isMe ? 
                                `<div style="font-size: 11px; color: #4CAF50; text-align: right; margin-top: 6px;">
                                    <i class="fas fa-check-double"></i> Прочитано
                                </div>` : 
                                (isMe ? `<div style="font-size: 11px; color: #94a3b8; text-align: right; margin-top: 6px;">
                                    <i class="fas fa-check"></i> Отправлено
                                </div>` : '')
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    container.scrollTop = container.scrollHeight;
}

// ========== ОТПРАВКА СООБЩЕНИЯ ==========
function sendAppealMessage(appealId) {
    const input = document.getElementById('appeal-message-input');
    if (!input) return;
    
    const message = input.value.trim();
    if (!message) return;
    
    if (!currentUser || !currentUser.id) {
        Swal.fire('Ошибка', 'Необходимо авторизоваться', 'error');
        return;
    }
    
    // Очищаем поле
    input.value = '';
    
    $.ajax({
        url: 'api.php?action=send_appeal_message',
        type: 'POST',
        data: {
            appeal_id: appealId,
            user_id: currentUser.id,
            message: message
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                loadAppealMessages(appealId);
            } else {
                Swal.fire('❌ Ошибка', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('❌ Ошибка', 'Не удалось отправить сообщение', 'error');
        }
    });
}


// ========== ДОБАВЛЕНИЕ В КОРЗИНУ - ИСПРАВЛЕНО ==========
window.addToCart = function(productId) {
    console.log('🛒 Добавление товара:', productId);
    
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    // БЛОКИРУЕМ КНОПКУ
    const btn = event?.target;
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }
    
    // ОТПРАВЛЯЕМ ПРЯМО В cart_api.php
    $.ajax({
        url: 'cart_api.php?action=add_to_cart',  // ← ВАЖНО! НЕ api.php, А cart_api.php!
        method: 'POST',
        data: { 
            product_id: productId,
            quantity: 1 
        },
        dataType: 'json',
        success: function(response) {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-cart-plus"></i> В корзину';
            }
            
            console.log('📦 Ответ от сервера:', response);
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Добавлено!',
                    text: 'Товар добавлен в корзину',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                if (typeof loadCartCount === 'function') {
                    loadCartCount();
                }
            } else {
                let errorMsg = response.message || 'Ошибка добавления';
                
                if (errorMsg.includes('свой товар')) {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Нельзя добавить',
                        text: 'Вы не можете добавить свой собственный товар',
                        confirmButtonColor: '#ff4757'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: errorMsg,
                        confirmButtonColor: '#ff4757'
                    });
                }
            }
        },
        error: function(xhr, status, error) {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-cart-plus"></i> В корзину';
            }
            
            console.error('❌ Ошибка AJAX:', {
                status: status,
                error: error,
                response: xhr.responseText
            });
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Не удалось добавить товар в корзину',
                confirmButtonColor: '#ff4757'
            });
        }
    });
};
// ========== ПРОВЕРКА УВЕДОМЛЕНИЙ О ПРОДАЖАХ ==========
function checkSellerNotifications() {
    if (!currentUser) return;
    
    $.ajax({
        url: 'cart_api.php?action=get_seller_notifications',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.notifications.length > 0) {
                showSellerEarningsModal(response.notifications);
            }
        }
    });
}

// ========== МОДАЛКА С ПРОДАЖАМИ ==========
function showSellerEarningsModal(notifications) {
    let totalPending = notifications.reduce((sum, n) => sum + n.amount, 0);
    
    $.ajax({
        url: 'cart_api.php?action=get_seller_balance',
        method: 'GET',
        dataType: 'json',
        success: function(balanceRes) {
            let balance = balanceRes.balance || { available: 0, pending: 0, total: 0 };
            
            let html = `
                <div style="text-align: left; max-height: 600px; overflow-y: auto; padding: 10px;">
                    <!-- ШАПКА -->
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #4CAF50, #2E7D32); 
                            border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; color: #333; font-size: 24px;">💰 Мои продажи</h3>
                            <p style="margin: 5px 0 0 0; color: #666;">Всего заработано: <strong style="color: #4CAF50;">${balance.total.toFixed(2)} ₽</strong></p>
                        </div>
                    </div>
                    
                    <!-- БАЛАНС ПРОДАВЦА -->
                    <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 20px; padding: 25px; margin-bottom: 30px; color: white;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="font-weight: 700; font-size: 18px;">
                                <i class="fas fa-wallet"></i> Баланс продавца
                            </span>
                            <span style="background: rgba(255,255,255,0.2); padding: 6px 20px; border-radius: 30px; font-size: 14px;">
                                Доступно для вывода
                            </span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <div>
                                <div style="font-size: 42px; font-weight: bold; margin-bottom: 5px;">${balance.available.toFixed(2)} ₽</div>
                                <div style="font-size: 16px; opacity: 0.9;">
                                    <i class="fas fa-clock"></i> Ожидает подтверждения: ${balance.pending.toFixed(2)} ₽
                                </div>
                            </div>
                            
                            ${balance.available > 0 ? `
                            <button onclick="withdrawAllMoney()" 
                                style="background: white; color: #667eea; border: none; padding: 15px 30px; border-radius: 50px; 
                                       font-weight: 700; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 12px;
                                       box-shadow: 0 5px 20px rgba(0,0,0,0.2); transition: 0.3s;"
                                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.3)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.2)';">
                                <i class="fas fa-arrow-right"></i> Забрать ${balance.available.toFixed(2)} ₽
                            </button>
                            ` : ''}
                        </div>
                    </div>
                    
                    <h4 style="margin-bottom: 20px; font-size: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-bell" style="color: #ff9800;"></i> 
                        Новые продажи (${notifications.length})
                    </h4>
            `;
            
            notifications.forEach(n => {
                let date = new Date(n.created_at).toLocaleString('ru-RU', {
                    day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
                });
                
                html += `
                    <div style="background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 15px; 
                        border-left: 8px solid #4CAF50; box-shadow: 0 3px 15px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                    <span style="background: #4CAF50; color: white; padding: 6px 16px; border-radius: 25px; font-size: 13px; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Продано
                                    </span>
                                    <span style="color: #666; font-size: 13px;">
                                        <i class="fas fa-clock"></i> ${date}
                                    </span>
                                </div>
                                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px; font-weight: 700;">
                                    Заказ #${n.order_number}
                                </h4>
                                <p style="margin: 0 0 5px 0; color: #555; font-size: 15px;">
                                    <i class="fas fa-box"></i> ${n.product_titles}
                                </p>
                                <p style="margin: 0; color: #777; font-size: 14px;">
                                    <i class="fas fa-cube"></i> ${n.total_items} шт.
                                </p>
                            </div>
                            <div style="text-align: right; min-width: 150px;">
                                <div style="font-size: 28px; font-weight: bold; color: #4CAF50; margin-bottom: 12px;">
                                    +${n.amount.toFixed(2)} ₽
                                </div>
                                <button onclick="confirmPayment(${n.id})" 
                                    style="background: linear-gradient(135deg, #4CAF50, #45a049); color: white; border: none; 
                                           padding: 10px 24px; border-radius: 30px; font-weight: 600; font-size: 14px; 
                                           cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
                                           box-shadow: 0 3px 10px rgba(76,175,80,0.3); transition: 0.3s;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(76,175,80,0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 10px rgba(76,175,80,0.3)';">
                                    <i class="fas fa-arrow-right"></i> Забрать деньги
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (totalPending > 0) {
                html += `
                    <div style="margin-top: 25px; padding-top: 25px; border-top: 2px dashed #e0e0e0;">
                        <button onclick="confirmAllPending()" 
                            style="width: 100%; background: linear-gradient(135deg, #ff9800, #ff5722); color: white; 
                                   border: none; padding: 18px; border-radius: 15px; font-weight: 700; font-size: 18px; 
                                   cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 15px;
                                   box-shadow: 0 5px 20px rgba(255,87,34,0.3); transition: 0.3s;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(255,87,34,0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(255,87,34,0.3)';">
                            <i class="fas fa-gift"></i> Забрать все деньги (${totalPending.toFixed(2)} ₽)
                        </button>
                    </div>
                `;
            }
            
            html += `</div>`;
            
            Swal.fire({
                title: '',
                html: html,
                showConfirmButton: false,
                showCloseButton: true,
                width: '700px',
                background: 'white',
                customClass: {
                    popup: 'seller-modal'
                }
            });
        }
    });
}

// ========== ПОДТВЕРДИТЬ ПЛАТЕЖ ==========
function confirmPayment(transactionId) {
    Swal.fire({
        title: '💰 Забрать деньги?',
        html: 'Средства будут зачислены на ваш основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать',
        cancelButtonText: '❌ Позже',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_seller_payment',
                method: 'POST',
                data: { transaction_id: transactionId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Обновляем баланс
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        // Обновляем модалку
                        setTimeout(checkSellerNotifications, 1000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('❌ Ошибка', 'Не удалось зачислить деньги', 'error');
                }
            });
        }
    });
}

// ========== ПОДТВЕРДИТЬ ВСЕ ПЛАТЕЖИ ==========
function confirmAllPending() {
    Swal.fire({
        title: '💰 Забрать все деньги?',
        html: 'Все ожидающие платежи будут зачислены на ваш основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать всё',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_all_pending',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Все деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        setTimeout(checkSellerNotifications, 1000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                }
            });
        }
    });
}

// ========== ВЫВЕСТИ ВСЕ ДЕНЬГИ ==========
function withdrawAllMoney() {
    confirmAllPending();
}

// ========== ЗАПУСК ПРИ ЗАГРУЗКЕ ==========
$(document).ready(function() {
    // ... существующий код ...
    
    // Проверяем уведомления о продажах
    if (currentUser) {
        setTimeout(checkSellerNotifications, 2000);
    }
});

// ========== МОДАЛКА С ПРОДАЖАМИ И БАЛАНСОМ ==========
function showSellerEarningsModal(notifications) {
    let totalPending = notifications.reduce((sum, n) => sum + n.amount, 0);
    
    // Получаем текущий баланс продавца
    $.ajax({
        url: 'cart_api.php?action=get_seller_balance',
        method: 'GET',
        dataType: 'json',
        success: function(balanceRes) {
            let sellerBalance = balanceRes.balance || { available: 0, pending: 0, total: 0 };
            
            let html = `
                <div style="text-align: left; max-height: 500px; overflow-y: auto; padding: 10px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4CAF50, #2E7D32); 
                            border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px;">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; color: #333; font-size: 22px;">💰 Продажи</h3>
                            <p style="margin: 5px 0 0 0; color: #666;">Ваш магазин заработал!</p>
                        </div>
                    </div>
                    
                    <!-- СЧЕТЧИК БАЛАНСА С ПЕРЕКЛЮЧЕНИЕМ -->
                    <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 15px; padding: 20px; margin-bottom: 25px; color: white;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="font-weight: 600; font-size: 16px;">
                                <i class="fas fa-wallet"></i> Баланс продавца
                            </span>
                            <div style="display: flex; gap: 10px;">
                                <span id="balanceType" style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 13px;">
                                    ОСНОВНОЙ
                                </span>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <div>
                                <div style="font-size: 14px; opacity: 0.9;">Доступно</div>
                                <div id="availableBalance" style="font-size: 32px; font-weight: bold;">${sellerBalance.available.toFixed(2)} ₽</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 14px; opacity: 0.9;">Ожидает</div>
                                <div id="pendingBalance" style="font-size: 24px; font-weight: bold; color: #FFD700;">${sellerBalance.pending.toFixed(2)} ₽</div>
                            </div>
                        </div>
                        
                        <!-- КНОПКИ УПРАВЛЕНИЯ БАЛАНСОМ -->
                        <div style="display: flex; gap: 15px; margin-top: 20px;">
                            <button onclick="switchBalanceView('seller')" style="flex: 1; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-store"></i> Баланс продаж
                            </button>
                            <button onclick="switchBalanceView('main')" style="flex: 1; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-wallet"></i> Основной баланс
                            </button>
                        </div>
                    </div>
                    
                    <h4 style="margin-bottom: 15px;">🆕 Новые продажи (${notifications.length})</h4>
            `;
            
            notifications.forEach(n => {
                let date = new Date(n.created_at).toLocaleString('ru-RU', {
                    day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                
                html += `
                    <div style="background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 15px; 
                        border-left: 6px solid #4CAF50; box-shadow: 0 3px 10px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                    <span style="background: #4CAF50; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                        <i class="fas fa-check-circle"></i> Продано
                                    </span>
                                    <span style="color: #666; font-size: 12px;">
                                        <i class="fas fa-clock"></i> ${date}
                                    </span>
                                </div>
                                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 16px;">
                                    Заказ #${n.order_number}
                                </h4>
                                <p style="margin: 0 0 5px 0; color: #666;">
                                    ${n.product_titles} (${n.total_items} шт.)
                                </p>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 24px; font-weight: bold; color: #4CAF50; margin-bottom: 10px;">
                                    +${n.amount.toFixed(2)} ₽
                                </div>
                                <button onclick="confirmSellerPayment(${n.id})" 
                                    style="background: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-arrow-right"></i> Забрать деньги
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (totalPending > 0) {
                html += `
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                        <button onclick="confirmAllPending()" 
                            style="width: 100%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 16px; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 12px;">
                            <i class="fas fa-gift"></i> Забрать все деньги (${totalPending.toFixed(2)} ₽)
                        </button>
                    </div>
                `;
            }
            
            html += `</div>`;
            
            Swal.fire({
                title: '',
                html: html,
                showConfirmButton: false,
                showCloseButton: true,
                width: '650px',
                background: 'white',
                customClass: {
                    popup: 'seller-modal'
                }
            });
        }
    });
}

// ========== ПОДТВЕРДИТЬ ПЛАТЕЖ ==========
function confirmSellerPayment(transactionId) {
    Swal.fire({
        title: '💰 Забрать деньги?',
        text: 'Средства будут зачислены на ваш основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать',
        cancelButtonText: '❌ Позже',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_seller_payment',
                method: 'POST',
                data: { transaction_id: transactionId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Обновляем баланс в интерфейсе
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        // Обновляем модалку
                        setTimeout(() => {
                            checkSellerNotifications();
                        }, 500);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('❌ Ошибка', 'Не удалось зачислить деньги', 'error');
                }
            });
        }
    });
}

// ========== ПОДТВЕРДИТЬ ВСЕ ПЛАТЕЖИ ==========
function confirmAllPending() {
    Swal.fire({
        title: '💰 Забрать все деньги?',
        html: 'Все ожидающие платежи будут зачислены на ваш основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать всё',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_all_pending',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Все деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        checkSellerNotifications();
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                }
            });
        }
    });
}

// ========== ПЕРЕКЛЮЧЕНИЕ БАЛАНСА ==========
function switchBalanceView(type) {
    if (type === 'main') {
        $('#balanceType').text('ОСНОВНОЙ');
        $('#availableBalance').text(currentUser.balance.toFixed(2) + ' ₽');
        $('#pendingBalance').text('0.00 ₽');
    } else {
        $.ajax({
            url: 'cart_api.php?action=get_seller_balance',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    $('#balanceType').text('ПРОДАВЕЦ');
                    $('#availableBalance').text(res.balance.available.toFixed(2) + ' ₽');
                    $('#pendingBalance').text(res.balance.pending.toFixed(2) + ' ₽');
                }
            }
        });
    }
}

// ВЫЗЫВАЕМ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ
$(document).ready(function() {
    if (currentUser) {
        setTimeout(checkSellerNotifications, 1500);
    }
});

// ========== ВЫЗОВ ПРИ ЗАГРУЗКЕ ==========
$(document).ready(function() {
    if (typeof currentUser !== 'undefined' && currentUser) {
        setTimeout(checkPersonalPromo, 1500);
    }
});


// ========== АДМИН: ОТВЕТ НА АПЕЛЛЯЦИЮ ==========
window.adminReplyToAppeal = function(appealId, userId, ticketId) {
    console.log('💬 Ответ на апелляцию:', {appealId, userId, ticketId});
    
    // Сначала показываем что функция вызвана
    alert('Функция вызвана! ID апелляции: ' + appealId);
    
    Swal.fire({
        title: '📝 Ответ на апелляцию',
        html: `
            <div style="text-align: left;">
                <p style="margin-bottom: 15px; color: #666;">
                    <i class="fas fa-info-circle"></i> 
                    Пользователь ID: <strong>${userId}</strong>
                    ${ticketId ? `<br>Тикет: #${ticketId}` : ''}
                </p>
                <textarea id="appeal-reply-text" class="swal2-textarea" 
                    placeholder="Введите ваш ответ..." 
                    style="min-height: 150px; width: 100%;"></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Отправить',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        preConfirm: () => {
            const reply = document.getElementById('appeal-reply-text').value.trim();
            if (!reply) {
                Swal.showValidationMessage('Введите текст ответа');
                return false;
            }
            return reply;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            alert('Отправляем ответ: ' + result.value);
            // Здесь будет AJAX запрос
        }
    });
};


// ========== ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ ==========
let currentBalanceMode = 'main';
let sellerBalanceData = { available: 0, pending: 0, total: 0 };

// ========== ПЕРЕКЛЮЧЕНИЕ БАЛАНСА ==========
function switchBalanceMode() {
    console.log('🔄 Переключение баланса, текущий режим:', currentBalanceMode);
    
    if (currentBalanceMode === 'main') {
        // Загружаем баланс продавца
        $.ajax({
            url: 'cart_api.php?action=get_seller_balance',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    sellerBalanceData = response.balance;
                    currentBalanceMode = 'seller';
                    
                    // Меняем отображение
                    $('#balanceModeText').text('Баланс продаж');
                    $('#userBalance').text(sellerBalanceData.available.toFixed(2));
                    $('#balanceToggleIcon').html('<i class="fas fa-wallet" style="color: white; font-size: 18px;"></i>');
                    $('#balanceToggleIcon').attr('title', 'Переключить на основной баланс');
                    
                    // Показываем ожидающие деньги
                    if (sellerBalanceData.pending > 0) {
                        if ($('.balance-pending').length === 0) {
                            $('#userBalance').after(`<span class="balance-pending" style="background: #FFD700; color: #333; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;">
                                <i class="fas fa-clock"></i> ${sellerBalanceData.pending.toFixed(2)} ₽
                            </span>`);
                        }
                    }
                    
                    showToast('💰 Баланс продавца', 'success');
                }
            },
            error: function() {
                showToast('❌ Ошибка загрузки баланса', 'error');
            }
        });
    } else {
        // Возвращаем основной баланс
        currentBalanceMode = 'main';
        
        $('#balanceModeText').text('Баланс');
        $('#userBalance').text(currentUser.balance.toFixed(2));
        $('#balanceToggleIcon').html('<i class="fas fa-store" style="color: white; font-size: 18px;"></i>');
        $('#balanceToggleIcon').attr('title', 'Переключить на баланс продавца');
        $('.balance-pending').remove();
        
        showToast('💰 Основной баланс', 'info');
    }
}



// ========== МОДАЛКА С ПРОДАЖАМИ ==========
function showSellerEarningsModal(notifications) {
    let totalAmount = notifications.reduce((sum, n) => sum + n.amount, 0);
    
    let html = `
        <div style="text-align: left; padding: 10px;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4CAF50, #2E7D32); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px;">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: #333; font-size: 24px;">💰 Новые продажи!</h3>
                    <p style="margin: 5px 0 0 0; color: #666;">Ваши товары купили</p>
                </div>
            </div>
            
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 16px; color: #666;">Всего к получению:</span>
                    <span style="font-size: 32px; font-weight: bold; color: #4CAF50;">+${totalAmount.toFixed(2)} ₽</span>
                </div>
                <p style="margin-top: 10px; color: #666; font-size: 14px;">
                    <i class="fas fa-info-circle"></i> Деньги поступят на баланс продавца
                </p>
            </div>
    `;
    
    notifications.forEach(n => {
        let date = new Date(n.created_at).toLocaleString('ru-RU', {
            day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
        });
        
        html += `
            <div style="background: white; border-radius: 12px; padding: 15px; margin-bottom: 15px; border-left: 4px solid #4CAF50; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <span style="font-weight: bold;">Заказ #${n.order_number}</span>
                        <div style="color: #666; font-size: 12px; margin-top: 5px;">${date}</div>
                    </div>
                    <div style="font-size: 20px; font-weight: bold; color: #4CAF50;">+${n.amount.toFixed(2)} ₽</div>
                </div>
            </div>
        `;
    });
    
    html += `
        <div style="margin-top: 25px; text-align: center;">
            <button onclick="switchBalanceMode(); Swal.close();" 
                style="background: #667eea; color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: bold; cursor: pointer;">
                <i class="fas fa-store"></i> Перейти к балансу продавца
            </button>
        </div>
    `;
    
    html += `</div>`;
    
    Swal.fire({
        title: '',
        html: html,
        showConfirmButton: false,
        showCloseButton: true,
        width: '500px'
    });
}

// ========== ЗАГРУЗКА БАЛАНСА ПРИ СТАРТЕ ==========
$(document).ready(function() {
    // ... твой существующий код ...
    
    // Загружаем баланс продавца в фоне
    if (currentUser) {
        setTimeout(() => {
            $.ajax({
                url: 'cart_api.php?action=get_seller_balance',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        sellerBalanceData = response.balance;
                        console.log('💰 Баланс продавца загружен:', sellerBalanceData);
                    }
                }
            });
            
            // Проверяем уведомления
            checkSellerNotifications();
        }, 2000);
    }
});



// ========== ПРОВЕРКА УВЕДОМЛЕНИЙ ПРИ ЗАГРУЗКЕ ==========
function checkSellerNotificationsOnLoad() {
    if (!currentUser) return;
    
    console.log('🔍 Проверка уведомлений для пользователя ID:', currentUser.id);
    
    $.ajax({
        url: 'cart_api.php?action=get_seller_notifications',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('📦 Уведомления получены:', response);
            
            if (response.status === 'success' && response.notifications && response.notifications.length > 0) {
                console.log('🎉 Найдено уведомлений:', response.notifications.length);
                // НЕБОЛЬШАЯ ЗАДЕРЖКА, ЧТОБЫ НЕ ПЕРЕГРУЖАТЬ
                setTimeout(() => {
                    showSellerEarningsModal(response.notifications);
                }, 1000);
            } else {
                console.log('ℹ️ Нет новых уведомлений');
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Ошибка загрузки уведомлений:', error);
        }
    });
}

// ========== МОДАЛКА С ПРОДАЖАМИ ==========
function showSellerEarningsModal(notifications) {
    let totalPending = notifications.reduce((sum, n) => sum + n.amount, 0);
    
    $.ajax({
        url: 'cart_api.php?action=get_seller_balance',
        method: 'GET',
        dataType: 'json',
        success: function(balanceRes) {
            let balance = balanceRes.balance || { available: 0, pending: 0, total: 0 };
            
            let html = `
                <div style="text-align: left; max-height: 600px; overflow-y: auto; padding: 10px;">
                    <!-- ШАПКА -->
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #4CAF50, #2E7D32); 
                            border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; color: #333; font-size: 24px;">💰 Мои продажи</h3>
                            <p style="margin: 5px 0 0 0; color: #666;">Всего заработано: <strong style="color: #4CAF50;">${balance.total.toFixed(2)} ₽</strong></p>
                        </div>
                    </div>
                    
                    <!-- БАЛАНС ПРОДАВЦА -->
                    <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 20px; padding: 25px; margin-bottom: 30px; color: white;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="font-weight: 700; font-size: 18px;">
                                <i class="fas fa-wallet"></i> Баланс продавца
                            </span>
                            <span style="background: rgba(255,255,255,0.2); padding: 6px 20px; border-radius: 30px; font-size: 14px;">
                                Доступно для вывода
                            </span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <div>
                                <div style="font-size: 42px; font-weight: bold; margin-bottom: 5px;">${balance.available.toFixed(2)} ₽</div>
                                <div style="font-size: 16px; opacity: 0.9;">
                                    <i class="fas fa-clock"></i> Ожидает: ${balance.pending.toFixed(2)} ₽
                                </div>
                            </div>
                            
                            ${balance.available > 0 ? `
                            <button onclick="withdrawAllMoney()" 
                                style="background: white; color: #667eea; border: none; padding: 15px 30px; border-radius: 50px; 
                                       font-weight: 700; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 12px;
                                       box-shadow: 0 5px 20px rgba(0,0,0,0.2); transition: 0.3s;"
                                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.3)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.2)';">
                                <i class="fas fa-arrow-right"></i> Забрать ${balance.available.toFixed(2)} ₽
                            </button>
                            ` : ''}
                        </div>
                    </div>
                    
                    <h4 style="margin-bottom: 20px; font-size: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-bell" style="color: #ff9800;"></i> 
                        Новые продажи (${notifications.length})
                    </h4>
            `;
            
            notifications.forEach(n => {
                let date = new Date(n.created_at).toLocaleString('ru-RU', {
                    day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
                });
                
                html += `
                    <div style="background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 15px; 
                        border-left: 8px solid #4CAF50; box-shadow: 0 3px 15px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                    <span style="background: #4CAF50; color: white; padding: 6px 16px; border-radius: 25px; font-size: 13px; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Продано
                                    </span>
                                    <span style="color: #666; font-size: 13px;">
                                        <i class="fas fa-clock"></i> ${date}
                                    </span>
                                </div>
                                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px; font-weight: 700;">
                                    Заказ #${n.order_number}
                                </h4>
                                <p style="margin: 0 0 5px 0; color: #555; font-size: 15px;">
                                    <i class="fas fa-box"></i> ${n.product_titles || 'Товар'}
                                </p>
                                <p style="margin: 0; color: #777; font-size: 14px;">
                                    <i class="fas fa-cube"></i> ${n.total_items || 1} шт.
                                </p>
                            </div>
                            <div style="text-align: right; min-width: 150px;">
                                <div style="font-size: 28px; font-weight: bold; color: #4CAF50; margin-bottom: 12px;">
                                    +${n.amount.toFixed(2)} ₽
                                </div>
                                <button onclick="confirmPayment(${n.id})" 
                                    style="background: linear-gradient(135deg, #4CAF50, #45a049); color: white; border: none; 
                                           padding: 10px 24px; border-radius: 30px; font-weight: 600; font-size: 14px; 
                                           cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
                                           box-shadow: 0 3px 10px rgba(76,175,80,0.3); transition: 0.3s;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(76,175,80,0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 10px rgba(76,175,80,0.3)';">
                                    <i class="fas fa-arrow-right"></i> Забрать
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (totalPending > 0 && notifications.length > 1) {
                html += `
                    <div style="margin-top: 25px; padding-top: 25px; border-top: 2px dashed #e0e0e0;">
                        <button onclick="confirmAllPending()" 
                            style="width: 100%; background: linear-gradient(135deg, #ff9800, #ff5722); color: white; 
                                   border: none; padding: 18px; border-radius: 15px; font-weight: 700; font-size: 18px; 
                                   cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 15px;
                                   box-shadow: 0 5px 20px rgba(255,87,34,0.3); transition: 0.3s;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 30px rgba(255,87,34,0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(255,87,34,0.3)';">
                            <i class="fas fa-gift"></i> Забрать все (${totalPending.toFixed(2)} ₽)
                        </button>
                    </div>
                `;
            }
            
            html += `</div>`;
            
            Swal.fire({
                title: '',
                html: html,
                showConfirmButton: false,
                showCloseButton: true,
                width: '700px',
                background: 'white',
                confirmButtonColor: '#4CAF50',
                customClass: {
                    popup: 'seller-modal'
                }
            });
        }
    });
}

// ========== ПОДТВЕРДИТЬ ПЛАТЕЖ ==========
function confirmPayment(transactionId) {
    Swal.fire({
        title: '💰 Забрать деньги?',
        text: 'Средства будут зачислены на ваш основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать',
        cancelButtonText: '❌ Позже',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_seller_payment',
                method: 'POST',
                data: { transaction_id: transactionId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Обновляем баланс
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        // Обновляем модалку
                        setTimeout(() => {
                            checkSellerNotificationsOnLoad();
                        }, 1000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('❌ Ошибка', 'Не удалось зачислить деньги', 'error');
                }
            });
        }
    });
}

// ========== ПОДТВЕРДИТЬ ВСЕ ПЛАТЕЖИ ==========
function confirmAllPending() {
    Swal.fire({
        title: '💰 Забрать все деньги?',
        text: 'Все ожидающие платежи будут зачислены на основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать всё',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_all_pending',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Все деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        setTimeout(() => {
                            checkSellerNotificationsOnLoad();
                        }, 1000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                }
            });
        }
    });
}

function withdrawAllMoney() {
    confirmAllPending();
}

// ========== ЗАПУСК ПРИ ЗАГРУЗКЕ СТРАНИЦЫ ==========
$(document).ready(function() {
    // ЖДЕМ ЗАГРУЗКИ ПОЛЬЗОВАТЕЛЯ
    setTimeout(function() {
        if (typeof currentUser !== 'undefined' && currentUser) {
            console.log('👤 Проверка уведомлений для пользователя:', currentUser.login);
            checkSellerNotificationsOnLoad();
        }
    }, 2000);
});

// ========== МОДАЛКА ПРОДАЖ - ПОЛНАЯ ИНФОРМАЦИЯ ==========
window.showSellerEarningsModal = function(notifications) {
    console.log('💰 МОДАЛКА ПРОДАЖ', notifications);
    
    if (!notifications || notifications.length === 0) {
        return;
    }
    
    let total = notifications.reduce((sum, n) => sum + (n.amount || 0), 0);
    
    let html = `
        <div style="max-height: 600px; overflow-y: auto; padding: 10px;">
            <!-- ШАПКА С ИТОГО -->
            <div style="background: linear-gradient(135deg, #667eea, #764ba2); 
                        color: white; padding: 25px; border-radius: 15px; 
                        margin-bottom: 25px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 10px;">
                    <i class="fas fa-coins"></i>
                </div>
                <h2 style="margin: 0 0 5px 0; font-size: 32px;">+${total.toFixed(2)} ₽</h2>
                <p style="margin: 0; opacity: 0.9; font-size: 16px;">
                    Заработано за ${notifications.length} продаж(и)
                </p>
            </div>
            
            <h3 style="color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-history" style="color: #667eea;"></i>
                История продаж
            </h3>
    `;
    
    notifications.forEach((n, index) => {
        // ФОРМАТИРУЕМ ДАТУ
        let date = new Date(n.created_at).toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // ГЕНЕРИРУЕМ СЛУЧАЙНОГО ПОКУПАТЕЛЯ (В РЕАЛЬНОСТИ БРАТЬ ИЗ БД)
        let buyers = ['Алексей', 'Дмитрий', 'Елена', 'Ольга', 'Сергей', 'Анна', 'Михаил', 'Татьяна'];
        let buyer = n.buyer_name || buyers[index % buyers.length];
        
        // НАЗВАНИЕ ТОВАРА
        let productName = n.product_titles || 'Товар';
        
        html += `
            <div style="background: #f8f9fa; border-radius: 16px; padding: 20px; 
                        margin-bottom: 15px; border-left: 8px solid #4CAF50;
                        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
                        transition: transform 0.2s;"
                 onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.1)';"
                 onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 3px 15px rgba(0,0,0,0.05)';">
                
                <!-- ЗАГОЛОВОК С ДАТОЙ -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span style="background: #4CAF50; color: white; padding: 6px 16px; 
                                border-radius: 25px; font-size: 13px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Продано
                    </span>
                    <span style="color: #666; font-size: 13px;">
                        <i class="fas fa-clock"></i> ${date}
                    </span>
                </div>
                
                <!-- ИНФОРМАЦИЯ О ПОКУПАТЕЛЕ -->
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2);
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                color: white; font-size: 20px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #333; font-size: 16px;">
                            ${buyer}
                        </div>
                        <div style="color: #666; font-size: 13px;">
                            <i class="fas fa-shopping-cart"></i> Заказ #${n.order_number || 'ORD-' + (1000 + n.id)}
                        </div>
                    </div>
                </div>
                
                <!-- ТОВАР И ЦЕНА -->
                <div style="background: white; border-radius: 12px; padding: 15px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: #333; margin-bottom: 5px;">
                                <i class="fas fa-box"></i> ${productName}
                            </div>
                            <div style="color: #666; font-size: 13px;">
                                <i class="fas fa-cube"></i> ${n.total_items || 1} шт.
                            </div>
                        </div>
                        <div style="font-size: 24px; font-weight: bold; color: #4CAF50;">
                            +${n.amount.toFixed(2)} ₽
                        </div>
                    </div>
                </div>
                
                <!-- КНОПКА ЗАБРАТЬ -->
                <button onclick="withdrawTransaction(${n.id}, ${n.amount})" 
                        style="width: 100%; background: linear-gradient(135deg, #4CAF50, #45a049); 
                               color: white; border: none; padding: 12px; border-radius: 10px;
                               font-weight: 600; font-size: 15px; cursor: pointer;
                               display: flex; align-items: center; justify-content: center; gap: 10px;
                               transition: all 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(76,175,80,0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-arrow-right"></i> Забрать ${n.amount.toFixed(2)} ₽
                </button>
            </div>
        `;
    });
    
    // КНОПКА "ЗАБРАТЬ ВСЕ"
    if (notifications.length > 1) {
        html += `
            <div style="margin-top: 25px; padding-top: 20px; border-top: 2px dashed #e0e0e0;">
                <button onclick="withdrawAllMoney(${total})" 
                        style="width: 100%; background: linear-gradient(135deg, #ff9800, #ff5722); 
                               color: white; border: none; padding: 18px; border-radius: 15px;
                               font-weight: 700; font-size: 18px; cursor: pointer;
                               display: flex; align-items: center; justify-content: center; gap: 15px;
                               transition: all 0.3s;"
                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(255,87,34,0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-gift"></i> Забрать все (${total.toFixed(2)} ₽)
                </button>
            </div>
        `;
    }
    
    html += `</div>`;
    
    Swal.fire({
        title: '',
        html: html,
        showConfirmButton: false,
        showCloseButton: true,
        width: '650px',
        background: 'white',
        customClass: {
            popup: 'seller-modal'
        }
    });
};

// ========== ЗАБРАТЬ ОДНУ ТРАНЗАКЦИЮ ==========
window.withdrawTransaction = function(transactionId, amount) {
    Swal.fire({
        title: '💰 Забрать деньги?',
        html: `Вы хотите зачислить <strong>${amount.toFixed(2)} ₽</strong> на основной баланс?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_seller_payment',
                method: 'POST',
                data: { transaction_id: transactionId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        // ОБНОВЛЯЕМ МОДАЛКУ ЧЕРЕЗ 2 СЕКУНДЫ
                        setTimeout(() => {
                            $.get('cart_api.php?action=get_seller_notifications', function(res) {
                                if (res.notifications?.length > 0) {
                                    window.showSellerEarningsModal(res.notifications);
                                }
                            }, 'json');
                        }, 2000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('❌ Ошибка', 'Не удалось зачислить деньги', 'error');
                }
            });
        }
    });
};

// ========== ЗАБРАТЬ ВСЕ ==========
window.withdrawAllMoney = function(total) {
    Swal.fire({
        title: '💰 Забрать все деньги?',
        html: `Будет зачислено <strong>${total.toFixed(2)} ₽</strong> на основной баланс`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать всё',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_all_pending',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Все деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                    }
                }
            });
        }
    });
};

// СТИЛИ
$('<style>')
    .prop('type', 'text/css')
    .html(`
        .seller-modal .swal2-popup {
            padding: 25px !important;
            border-radius: 25px !important;
        }
        .seller-modal::-webkit-scrollbar {
            width: 8px;
        }
        .seller-modal::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .seller-modal::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }
    `)
    .appendTo('head');

// ========== ПОДТВЕРДИТЬ ПЛАТЕЖ ==========
window.confirmPayment = function(transactionId) {
    Swal.fire({
        title: '💰 Забрать деньги?',
        text: 'Средства будут зачислены на ваш основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать',
        cancelButtonText: '❌ Позже',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_seller_payment',
                method: 'POST',
                data: { transaction_id: transactionId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('❌ Ошибка', 'Не удалось зачислить деньги', 'error');
                }
            });
        }
    });
};

// ========== ПОДТВЕРДИТЬ ВСЕ ПЛАТЕЖИ ==========
window.confirmAllPending = function() {
    Swal.fire({
        title: '💰 Забрать все деньги?',
        text: 'Все ожидающие платежи будут зачислены на основной баланс',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Забрать всё',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Зачисление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'cart_api.php?action=confirm_all_pending',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Все деньги зачислены!',
                            html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${response.amount.toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        if (currentUser) {
                            currentUser.balance = response.new_balance;
                            $('#user-balance').text(response.new_balance.toFixed(2));
                        }
                        
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        
                    } else {
                        Swal.fire('❌ Ошибка', response.message, 'error');
                    }
                }
            });
        }
    });
};

window.withdrawAllMoney = function() {
    confirmAllPending();
};

console.log('✅ Функции модалки продаж загружены!');
</script>
<script>
// ========== АВТОМАТИЧЕСКАЯ ПРОВЕРКА ПРОДАЖ ==========
$(document).ready(function() {
    // ЖДЕМ ЗАГРУЗКИ ПОЛЬЗОВАТЕЛЯ
    setTimeout(function() {
        if (typeof currentUser !== 'undefined' && currentUser) {
            console.log('👤 Проверка продаж для:', currentUser.login);
            
            // ЗАПРАШИВАЕМ РЕАЛЬНЫЕ ПРОДАЖИ
            $.ajax({
                url: 'cart_api.php?action=get_seller_notifications',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('📦 ОТВЕТ ОТ СЕРВЕРА:', response);
                    
                    if (response.status === 'success' && response.notifications && response.notifications.length > 0) {
                        // ПОКАЗЫВАЕМ МОДАЛКУ С РЕАЛЬНЫМИ ДАННЫМИ
                        window.showSellerEarningsModal(response.notifications);
                    }
                },
                error: function(xhr) {
                    console.error('❌ Ошибка загрузки продаж:', xhr.responseText);
                }
            });
        }
    }, 2000);
});

<script>
// ========== ПРИНУДИТЕЛЬНАЯ ПРОВЕРКА МОДАЛКИ ==========
$(document).ready(function() {
    console.log('🔥 СКРИПТ ЗАГРУЖЕН');
    
    // ФУНКЦИЯ ПРИНУДИТЕЛЬНОГО ПОКАЗА
    window.forceShowModal = function() {
        console.log('🎯 ПРИНУДИТЕЛЬНЫЙ ВЫЗОВ МОДАЛКИ');
        
        // ТЕСТОВЫЕ ДАННЫЕ
        let testNotifications = [
            {
                id: 1,
                order_id: 1,
                order_number: 'TEST-001',
                amount: 500.00,
                product_titles: 'Тестовый товар',
                total_items: 1,
                created_at: new Date().toISOString()
            }
        ];
        
        // ВЫЗЫВАЕМ МОДАЛКУ
        if (typeof showSellerEarningsModal === 'function') {
            showSellerEarningsModal(testNotifications);
        } else {
            console.error('❌ ФУНКЦИЯ НЕ НАЙДЕНА!');
        }
    };

    // ПРОВЕРЯЕМ РЕАЛЬНЫЕ УВЕДОМЛЕНИЯ
    function checkRealNotifications() {
        $.ajax({
            url: 'cart_api.php?action=get_seller_notifications',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('📦 РЕАЛЬНЫЕ УВЕДОМЛЕНИЯ:', response);
                
                if (response.status === 'success' && response.notifications && response.notifications.length > 0) {
                    if (typeof showSellerEarningsModal === 'function') {
                        showSellerEarningsModal(response.notifications);
                    }
                } else {
                    console.log('❌ НЕТ РЕАЛЬНЫХ УВЕДОМЛЕНИЙ');
                    // ЕСЛИ НЕТ - ПОКАЗЫВАЕМ ТЕСТОВУЮ
                    setTimeout(() => {
                        if (confirm('Показать тестовую модалку?')) {
                            forceShowModal();
                        }
                    }, 2000);
                }
            },
            error: function(xhr) {
                console.error('❌ ОШИБКА API:', xhr.responseText);
            }
        });
    }

    // ЗАПУСКАЕМ ЧЕРЕЗ 3 СЕКУНДЫ
    setTimeout(checkRealNotifications, 3000);
});

// ========== ТЕСТОВАЯ КНОПКА ==========
$('body').append(`
    <button id="testModalBtn" 
            style="position: fixed; bottom: 20px; left: 20px; z-index: 99999; 
                   background: #ff9800; color: white; border: none; 
                   padding: 12px 24px; border-radius: 50px; font-weight: bold;
                   box-shadow: 0 5px 20px rgba(255,152,0,0.4); cursor: pointer;">
        🧪 ТЕСТ МОДАЛКИ
    </button>
`);

$('#testModalBtn').click(function() {
    forceShowModal();
});
</script>
<script>
// ========== ПРОВЕРКА ПРОДАЖ - ТОЛЬКО ЗА ПОСЛЕДНИЕ 24 ЧАСА ==========
$(document).ready(function() {
    setTimeout(function() {
        if (typeof currentUser !== 'undefined' && currentUser) {
            $.ajax({
                url: 'cart_api.php?action=get_seller_notifications',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('📦 ПРОДАЖИ ЗА 24 ЧАСА:', response);
                    
                    if (response.status === 'success' && response.notifications && response.notifications.length > 0) {
                        
                        let total = response.total || 0;
                        
                        Swal.fire({
                            title: '💰 НОВЫЕ ПРОДАЖИ!',
                            html: `
                                <div style="text-align: center; padding: 20px;">
                                    <div style="font-size: 60px; color: #4CAF50; margin-bottom: 20px;">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <h2 style="font-size: 36px; color: #333; margin-bottom: 5px;">
                                        +${total.toFixed(2)} ₽
                                    </h2>
                                    <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
                                        За последние 24 часа • ${response.notifications.length} продаж
                                    </p>
                                    <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                                        <div style="display: flex; justify-content: space-between; color: #666; font-size: 14px;">
                                            <span><i class="fas fa-clock"></i> Ожидает заморозки:</span>
                                            <span style="color: #ff9800; font-weight: bold;">${(total * 0.2).toFixed(2)} ₽</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; color: #666; font-size: 14px; margin-top: 5px;">
                                            <span><i class="fas fa-check-circle"></i> К зачислению:</span>
                                            <span style="color: #4CAF50; font-weight: bold;">${(total * 0.8).toFixed(2)} ₽</span>
                                        </div>
                                    </div>
                                    <button id="claimMoneyBtn" 
                                        style="background: #4CAF50; color: white; border: none; 
                                               padding: 15px 40px; border-radius: 50px; 
                                               font-size: 18px; font-weight: bold; cursor: pointer;
                                               box-shadow: 0 5px 15px rgba(76,175,80,0.3);
                                               transition: all 0.3s;"
                                        onmouseover="this.style.transform='translateY(-2px'; this.style.boxShadow='0 8px 25px rgba(76,175,80,0.4)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(76,175,80,0.3)';">
                                        <i class="fas fa-arrow-right"></i> ЗАБРАТЬ ${(total * 0.8).toFixed(2)} ₽
                                    </button>
                                    <p style="color: #999; font-size: 12px; margin-top: 15px;">
                                        <i class="fas fa-info-circle"></i> 20% заморожено на 14 дней гарантии
                                    </p>
                                </div>
                            `,
                            showConfirmButton: false,
                            showCloseButton: true,
                            didOpen: () => {
                                document.getElementById('claimMoneyBtn').onclick = function() {
                                    Swal.fire({
                                        title: '⏳ Зачисление...',
                                        text: 'Пожалуйста, подождите',
                                        allowOutsideClick: false,
                                        didOpen: () => Swal.showLoading()
                                    });
                                    
                                    $.ajax({
                                        url: 'cart_api.php?action=confirm_all_pending',
                                        method: 'POST',
                                        dataType: 'json',
                                        success: function(res) {
                                            if (res.status === 'success') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: '✅ Деньги зачислены!',
                                                    html: `<span style="font-size: 24px; font-weight: bold; color: #4CAF50;">+${res.amount.toFixed(2)} ₽</span>`,
                                                    timer: 2000,
                                                    showConfirmButton: false
                                                });
                                                
                                                if (currentUser) {
                                                    currentUser.balance = res.new_balance;
                                                    $('#user-balance').text(res.new_balance.toFixed(2));
                                                }
                                                
                                                setTimeout(() => {
                                                    location.reload();
                                                }, 2000);
                                            } else {
                                                Swal.fire('❌ Ошибка', res.message, 'error');
                                            }
                                        },
                                        error: function() {
                                            Swal.close();
                                            Swal.fire('❌ Ошибка', 'Не удалось зачислить деньги', 'error');
                                        }
                                    });
                                };
                            }
                        });
                    }
                }
            });
        }
    }, 3000);
});

</script>
<script>
// ========== ФУНКЦИИ УПРАВЛЕНИЯ ПОЛЬЗОВАТЕЛЯМИ ==========
// ВСЕ АЛЕРТЫ ЗАМЕНЕНЫ НА SWEETALERT2 МОДАЛКИ!

// ----- БАН ПОЛЬЗОВАТЕЛЯ (КРАСИВАЯ МОДАЛКА) -----
function banUser(userId, userLogin) {
    console.log('🔨 Бан пользователя:', userId, userLogin);
    
    if (!userId) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'ID пользователя не указан',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    Swal.fire({
        title: '🔨 Блокировка пользователя',
        html: `
            <div style="text-align: left; padding: 10px;">
                <div style="background: linear-gradient(135deg, #ffebee, #ffcdd2); padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 6px solid #ff4757;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 45px; height: 45px; background: #ff4757; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div>
                            <div style="font-size: 13px; color: #666;">Пользователь</div>
                            <div style="font-size: 18px; font-weight: bold; color: #333;">${userLogin || 'ID: ' + userId} (ID: ${userId})</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">📌 Причина бана</label>
                    <select id="ban-reason-select" class="swal2-input" style="width: 100%; margin: 0;">
                        <option value="Нарушение правил">Нарушение правил</option>
                        <option value="Мошенничество">Мошенничество</option>
                        <option value="Оскорбления">Оскорбления</option>
                        <option value="Спам">Спам</option>
                        <option value="Другое">Другое</option>
                    </select>
                </div>
                
                <div id="custom-reason-container" style="margin-bottom: 20px; display: none;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">✏️ Укажите причину</label>
                    <input id="custom-reason-input" class="swal2-input" placeholder="Введите причину..." style="width: 100%; margin: 0;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">⏱ Срок бана</label>
                    <div style="display: flex; gap: 10px;">
                        <input id="ban-duration-input" class="swal2-input" type="number" value="24" min="0" style="flex: 1; margin: 0;">
                        <select id="ban-unit-select" class="swal2-input" style="width: 120px; margin: 0;">
                            <option value="hours">Часов</option>
                            <option value="days">Дней</option>
                            <option value="weeks">Недель</option>
                        </select>
                    </div>
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                        <i class="fas fa-info-circle"></i> 0 = бессрочно
                    </p>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">💬 Сообщение пользователю</label>
                    <textarea id="ban-message-input" class="swal2-textarea" 
                        placeholder="Сообщение, которое увидит пользователь..." 
                        style="min-height: 80px; width: 100%;">Ваш аккаунт заблокирован за нарушение правил.</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '🔨 Заблокировать',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#ff4757',
        cancelButtonColor: '#6c757d',
        width: '600px',
        background: 'white',
        didOpen: () => {
            document.getElementById('ban-reason-select').addEventListener('change', function() {
                const container = document.getElementById('custom-reason-container');
                container.style.display = this.value === 'Другое' ? 'block' : 'none';
            });
        },
        preConfirm: () => {
            let reason = document.getElementById('ban-reason-select').value;
            if (reason === 'Другое') {
                reason = document.getElementById('custom-reason-input')?.value;
                if (!reason) {
                    Swal.showValidationMessage('Укажите причину бана');
                    return false;
                }
            }
            
            const duration = parseInt(document.getElementById('ban-duration-input').value) || 0;
            const unit = document.getElementById('ban-unit-select').value;
            const message = document.getElementById('ban-message-input').value;
            
            return { reason, duration, unit, message };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Блокировка...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            // Конвертируем в часы
            let hours = result.value.duration;
            switch(result.value.unit) {
                case 'days': hours *= 24; break;
                case 'weeks': hours *= 168; break;
            }
            
            $.ajax({
                url: 'api.php?action=adm_ban',
                type: 'POST',
                data: {
                    user_id: userId,
                    reason: result.value.reason,
                    hours: hours,
                    message: result.value.message
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Пользователь забанен!',
                            html: `
                                <div style="text-align: center;">
                                    <p style="margin-bottom: 5px;"><strong>Причина:</strong> ${result.value.reason}</p>
                                    <p><strong>Срок:</strong> ${result.value.duration} ${result.value.unit}</p>
                                </div>
                            `,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        loadAdminUsers();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось заблокировать пользователя'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}

// ----- РАЗБАН ПОЛЬЗОВАТЕЛЯ (КРАСИВАЯ МОДАЛКА) -----
function adminUnbanUser(userId) {
    console.log('✅ Разбан пользователя:', userId);
    
    if (!userId) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'ID пользователя не указан',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    Swal.fire({
        title: '✅ Разблокировка пользователя',
        text: 'Вы уверены, что хотите разблокировать пользователя?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Да, разбанить',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Разблокировка...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=adm_unban',
                type: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Пользователь разблокирован!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadAdminUsers();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось разблокировать'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}

// ----- ИЗМЕНЕНИЕ БАЛАНСА (КРАСИВАЯ МОДАЛКА) -----
function editUserBalance(userId, userLogin) {
    console.log('💰 Изменение баланса:', userId, userLogin);
    
    if (!userId) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'ID пользователя не указан',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    Swal.fire({
        title: '💰 Изменение баланса',
        html: `
            <div style="text-align: left; padding: 10px;">
                <div style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 6px solid #4CAF50;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 45px; height: 45px; background: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div style="font-size: 13px; color: #666;">Пользователь</div>
                            <div style="font-size: 18px; font-weight: bold; color: #333;">${userLogin || 'ID: ' + userId} (ID: ${userId})</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">🔢 Операция</label>
                    <select id="balance-operation-select" class="swal2-input" style="width: 100%; margin: 0;">
                        <option value="add">➕ Добавить к балансу</option>
                        <option value="set">🔄 Установить баланс</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">💵 Сумма</label>
                    <input id="balance-amount-input" class="swal2-input" type="number" value="100" min="0.01" step="0.01" style="width: 100%; margin: 0;">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Сохранить',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        width: '500px',
        background: 'white',
        preConfirm: () => {
            const amount = document.getElementById('balance-amount-input').value;
            const operation = document.getElementById('balance-operation-select').value;
            
            if (!amount || parseFloat(amount) <= 0) {
                Swal.showValidationMessage('Введите корректную сумму');
                return false;
            }
            return { amount, operation };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Сохранение...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=adm_update_balance',
                type: 'POST',
                data: {
                    user_id: userId,
                    amount: result.value.amount,
                    operation: result.value.operation
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Баланс обновлен!',
                            html: `<span style="font-size: 20px; font-weight: bold; color: #4CAF50;">${parseFloat(result.value.amount).toFixed(2)} ₽</span>`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadAdminUsers();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось обновить баланс'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}



// ========== ЗАГРУЗКА ПОЛЬЗОВАТЕЛЕЙ С ГОРИЗОНТАЛЬНЫМИ КНОПКАМИ ==========
function loadAdminUsers() {
    console.log('👥 Загрузка пользователей...');
    
    fetch('api.php?action=adm_users')
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success' && data.users) {
                let html = `
                    <div style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <h2 style="margin: 0; color: #333; display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-users" style="color: #667eea;"></i>
                                Управление пользователями
                            </h2>
                            <div style="display: flex; gap: 15px;">
                                <span style="background: #667eea; color: white; padding: 8px 20px; border-radius: 25px; font-weight: 600;">
                                    Всего: ${data.users.length}
                                </span>
                                <button onclick="loadAdminUsers()" 
                                        style="background: #4CAF50; color: white; border: none; padding: 8px 20px; 
                                               border-radius: 25px; font-weight: 600; cursor: pointer;
                                               display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-sync-alt"></i> Обновить
                                </button>
                            </div>
                        </div>
                        
                        <div style="background: white; border-radius: 20px; box-shadow: 0 5px 30px rgba(0,0,0,0.05); overflow: hidden;">
                            <div style="max-height: 70vh; overflow-y: auto;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; position: sticky; top: 0;">
                                        <tr>
                                            <th style="padding: 16px; text-align: left;">ID</th>
                                            <th style="padding: 16px; text-align: left;">Пользователь</th>
                                            <th style="padding: 16px; text-align: left;">Имя</th>
                                            <th style="padding: 16px; text-align: left;">Роль</th>
                                            <th style="padding: 16px; text-align: left;">Баланс</th>
                                            <th style="padding: 16px; text-align: left;">Статус</th>
                                            <th style="padding: 16px; text-align: left;">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                data.users.forEach(user => {
                    let status = user.banned == 1 ? 
                        '<span style="background: #ff4757; color: white; padding: 6px 16px; border-radius: 25px; font-size: 13px; font-weight: 600; display: inline-block;">🔴 Забанен</span>' : 
                        '<span style="background: #4CAF50; color: white; padding: 6px 16px; border-radius: 25px; font-size: 13px; font-weight: 600; display: inline-block;">🟢 Активен</span>';
                    
                    let roleBadge = user.role === 'admin' ? 
                        '<span style="background: #9c27b0; color: white; padding: 6px 16px; border-radius: 25px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-crown"></i> Админ</span>' : 
                        '<span style="background: #667eea; color: white; padding: 6px 16px; border-radius: 25px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-user"></i> Пользователь</span>';
                    
                    html += `
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.2s;" 
                            onmouseover="this.style.background='#f8faff';" 
                            onmouseout="this.style.background='white';">
                            
                            <td style="padding: 16px;">
                                <span style="background: #f1f5f9; padding: 6px 12px; border-radius: 10px; font-weight: 700; color: #333;">
                                    #${user.id}
                                </span>
                            </td>
                            
                            <td style="padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #667eea, #764ba2); 
                                                border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                                                color: white; font-size: 18px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #333; font-size: 16px;">${user.login || '—'}</div>
                                        <small style="color: #94a3b8; font-size: 12px;">ID: ${user.id}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <td style="padding: 16px; color: #64748b; font-weight: 500;">${user.username || '—'}</td>
                            
                            <td style="padding: 16px;">${roleBadge}</td>
                            
                            <td style="padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-wallet" style="color: #4CAF50; font-size: 16px;"></i>
                                    <span style="font-weight: 700; color: #4CAF50; font-size: 18px;">
                                        ${parseFloat(user.balance || 0).toFixed(2)} ₽
                                    </span>
                                </div>
                            </td>
                            
                            <td style="padding: 16px;">${status}</td>
                            
                            <td style="padding: 16px;">
                                <div style="display: flex; gap: 6px; flex-wrap: nowrap; white-space: nowrap; justify-content: flex-start;">
                                    <!-- КНОПКА БАЛАНСА -->
                                    <button onclick="editUserBalance(${user.id}, '${user.login || ''}')" 
                                            style="background: linear-gradient(135deg, #2196F3, #1976D2); color: white; border: none; 
                                                   padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; 
                                                   display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
                                                   box-shadow: 0 2px 8px rgba(33,150,243,0.3); transition: all 0.2s;
                                                   white-space: nowrap; min-width: 80px; justify-content: center;">
                                        <i class="fas fa-wallet" style="font-size: 12px;"></i>
                                        <span>💰 Баланс</span>
                                    </button>
                                    
                                    <!-- КНОПКА ПРОМОКОДА -->
                                    <button onclick="givePromoToUser(${user.id}, '${user.login || ''}')" 
                                            style="background: linear-gradient(135deg, #9c27b0, #7b1fa2); color: white; border: none; 
                                                   padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; 
                                                   display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
                                                   box-shadow: 0 2px 8px rgba(156,39,176,0.3); transition: all 0.2s;
                                                   white-space: nowrap; min-width: 80px; justify-content: center;">
                                        <i class="fas fa-gift" style="font-size: 12px;"></i>
                                        <span>🎁 Промо</span>
                                    </button>
                    `;
                    
                    if (user.banned == 1) {
                        html += `
                                    <!-- КНОПКА РАЗБАНА -->
                                    <button onclick="adminUnbanUser(${user.id})" 
                                            style="background: linear-gradient(135deg, #4CAF50, #388E3C); color: white; border: none; 
                                                   padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; 
                                                   display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
                                                   box-shadow: 0 2px 8px rgba(76,175,80,0.3); transition: all 0.2s;
                                                   white-space: nowrap; min-width: 80px; justify-content: center;">
                                        <i class="fas fa-check-circle" style="font-size: 12px;"></i>
                                        <span>✅ Разбан</span>
                                    </button>
                        `;
                    } else {
                        html += `
                                    <!-- КНОПКА БАНА -->
                                    <button onclick="banUser(${user.id}, '${user.login || ''}')" 
                                            style="background: linear-gradient(135deg, #ff4757, #d32f2f); color: white; border: none; 
                                                   padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; 
                                                   display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
                                                   box-shadow: 0 2px 8px rgba(255,71,87,0.3); transition: all 0.2s;
                                                   white-space: nowrap; min-width: 80px; justify-content: center;">
                                        <i class="fas fa-ban" style="font-size: 12px;"></i>
                                        <span>🔨 Бан</span>
                                    </button>
                        `;
                    }
                    
                    html += `   </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `</tbody></table></div></div></div>`;
                $('#users-tab').html(html);
            }
        })
        .catch(error => {
            console.error('❌ Ошибка:', error);
            $('#users-tab').html(`
                <div style="padding: 20px; color: #f44336; text-align: center;">
                    ❌ Ошибка загрузки пользователей<br>
                    <button onclick="loadAdminUsers()" style="margin-top: 15px; padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Повторить
                    </button>
                </div>
            `);
        });
}

// ========== ПРОВЕРКА ПЕРСОНАЛЬНОГО ПРОМОКОДА ПРИ ВХОДЕ ==========
function checkPersonalPromo() {
    console.log('🎁 Проверка персонального промокода...');
    
    if (!currentUser || !currentUser.id) {
        console.log('❌ Пользователь не авторизован');
        return;
    }
    
    fetch('api.php?action=check_personal_promo&user_id=' + currentUser.id)
        .then(r => r.json())
        .then(data => {
            console.log('📦 Ответ от сервера:', data);
            
            if (data.status === 'success' && data.has_promo && data.promo) {
                // Показываем модалку через 1 секунду после загрузки
                setTimeout(() => {
                    showPersonalPromoModal(data.promo);
                }, 1000);
            }
        })
        .catch(error => {
            console.error('❌ Ошибка проверки промокода:', error);
        });
}

// ========== ПОКАЗ МОДАЛКИ С ПЕРСОНАЛЬНЫМ ПРОМОКОДОМ ==========
function showPersonalPromoModal(promo) {
    console.log('🎉 Показываем промокод:', promo);
    
    Swal.fire({
        title: '🎉 Персональный промокод!',
        html: `
            <div style="text-align: center; padding: 10px;">
                <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;
                    box-shadow: 0 10px 25px rgba(102,126,234,0.3);">
                    <i class="fas fa-gift" style="font-size: 60px; color: white;"></i>
                </div>
                
                <h3 style="color: #333; margin-bottom: 15px; font-size: 24px;">Вам подарок от администратора! 🎁</h3>
                
                <div style="background: #f8f9fa; padding: 25px; border-radius: 15px; margin-bottom: 25px;">
                    <p style="color: #666; margin-bottom: 10px;">Ваш персональный промокод:</p>
                    <div style="background: white; padding: 15px; border-radius: 10px; 
                        border: 2px solid #667eea; font-size: 24px; font-weight: bold; 
                        color: #667eea; letter-spacing: 3px; margin-bottom: 15px;
                        font-family: monospace; word-break: break-all;">
                        ${promo.code}
                    </div>
                    
                    <div style="font-size: 28px; font-weight: bold; color: #4CAF50;">
                        +${parseFloat(promo.reward).toFixed(2)} ₽
                    </div>
                </div>
                
                ${promo.message ? `
                    <div style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); padding: 20px; border-radius: 15px; margin-bottom: 25px; border-left: 6px solid #2196F3;">
                        <p style="color: #1565C0; margin-bottom: 8px; font-weight: 600;">
                            <i class="fas fa-envelope"></i> Сообщение:
                        </p>
                        <p style="color: #333; margin: 0; font-size: 16px; line-height: 1.5;">
                            ${promo.message}
                        </p>
                    </div>
                ` : ''}
                
                <button onclick="activatePersonalPromo(${promo.id}, '${promo.code}')" 
                        style="background: linear-gradient(135deg, #667eea, #764ba2); 
                               color: white; border: none; padding: 16px 32px; 
                               border-radius: 50px; font-size: 18px; font-weight: bold; 
                               cursor: pointer; width: 100%; display: flex;
                               align-items: center; justify-content: center; gap: 12px;
                               box-shadow: 0 8px 20px rgba(102,126,234,0.4);
                               transition: all 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(102,126,234,0.5)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 20px rgba(102,126,234,0.4)';">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    Активировать промокод
                </button>
                
                <p style="color: #999; font-size: 13px; margin-top: 20px;">
                    <i class="fas fa-clock"></i> Промокод действителен 7 дней
                </p>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '600px',
        background: 'white',
        customClass: {
            closeButton: 'custom-close-btn'
        }
    });
}
// ========== АКТИВАЦИЯ ПЕРСОНАЛЬНОГО ПРОМОКОДА - ИСПРАВЛЕННАЯ ==========
function activatePersonalPromo(promoId, promoCode) {
    console.log('💰 Активация промокода:', promoId, promoCode);
    
    if (!currentUser || !currentUser.id) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'Необходимо авторизоваться',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    Swal.fire({
        title: '⏳ Активация...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=activate_personal_promo',
        type: 'POST',
        data: {
            user_id: currentUser.id,
            promo_id: promoId,
            code: promoCode
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                // 1. ОБНОВЛЯЕМ currentUser
                currentUser.balance = response.new_balance;
                
                // 2. ОБНОВЛЯЕМ СЕССИЮ НА СЕРВЕРЕ
                $.ajax({
                    url: 'api.php?action=update_session_balance',
                    type: 'POST',
                    data: {
                        balance: response.new_balance
                    },
                    async: false // Важно! Дожидаемся обновления сессии
                });
                
                // 3. ОБНОВЛЯЕМ ВСЕ ЭЛЕМЕНТЫ С БАЛАНСОМ НА СТРАНИЦЕ
                $('.balance span, #user-balance, #balanceAmount, [id*="balance"]').each(function() {
                    if ($(this).is('span') || $(this).is('div')) {
                        $(this).text(parseFloat(response.new_balance).toFixed(2));
                    }
                });
                
                // 4. ПОКАЗЫВАЕМ УСПЕХ
                Swal.fire({
                    icon: 'success',
                    title: '✅ Промокод активирован!',
                    html: `
                        <div style="text-align: center; padding: 10px;">
                            <div style="font-size: 48px; color: #4CAF50; margin-bottom: 15px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p style="font-size: 20px; font-weight: bold; color: #4CAF50; margin-bottom: 10px;">
                                +${parseFloat(response.reward).toFixed(2)} ₽
                            </p>
                            <p style="color: #666;">
                                Текущий баланс: <strong>${parseFloat(response.new_balance).toFixed(2)} ₽</strong>
                            </p>
                            <p style="color: #4CAF50; margin-top: 15px; font-size: 14px;">
                                <i class="fas fa-check-circle"></i> Баланс сохранен в сессии
                            </p>
                        </div>
                    `,
                    timer: 3000,
                    showConfirmButton: false
                });
                
                // 5. ПРИНУДИТЕЛЬНО ОБНОВЛЯЕМ БАЛАНС В ШАПКЕ
                setTimeout(function() {
                    location.reload(); // Перезагружаем страницу через 3 секунды
                }, 3000);
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось активировать промокод',
                    confirmButtonColor: '#ff4757'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Не удалось активировать промокод',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}

// ========== ВЫДАЧА ПЕРСОНАЛЬНОГО ПРОМОКОДА ==========
function givePromoToUser(userId, userLogin) {
    console.log('🎁 Выдача промокода:', userId, userLogin);
    
    if (!userId) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'ID пользователя не указан',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    const generateCode = () => {
        const prefix = 'PROMO';
        const random = Math.random().toString(36).substr(2, 6).toUpperCase();
        const timestamp = Date.now().toString(36).substr(-4).toUpperCase();
        return `${prefix}_${random}_${timestamp}`;
    };
    
    Swal.fire({
        title: '🎁 Персональный промокод',
        html: `
            <div style="text-align: left; padding: 10px;">
                <div style="background: linear-gradient(135deg, #f3e5f5, #ede7f6); padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 6px solid #9c27b0;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 45px; height: 45px; background: #9c27b0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div>
                            <div style="font-size: 13px; color: #666;">Пользователь</div>
                            <div style="font-size: 18px; font-weight: bold; color: #333;">${userLogin || 'ID: ' + userId} (ID: ${userId})</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">🔑 Код промокода</label>
                    <div style="display: flex; gap: 10px;">
                        <input id="promo-code-input" class="swal2-input" value="${generateCode()}" 
                               style="flex: 1; margin: 0; font-family: monospace; letter-spacing: 1px; font-size: 14px;" 
                               readonly>
                        <button type="button" id="generate-promo-btn" 
                                style="background: #9c27b0; color: white; border: none; padding: 0 20px; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" id="copy-promo-btn" 
                                style="background: #2196F3; color: white; border: none; padding: 0 20px; border-radius: 8px; cursor: pointer;">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">💰 Сумма награды</label>
                    <input id="promo-reward-input" class="swal2-input" type="number" value="100" min="1" step="0.01" style="width: 100%; margin: 0;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">📝 Сообщение пользователю</label>
                    <textarea id="promo-message-input" class="swal2-textarea" 
                        placeholder="Сообщение для пользователя..." 
                        style="min-height: 80px; width: 100%;">🎉 Персональный промокод за активность! Спасибо, что вы с нами!</textarea>
                </div>
                
                <div style="background: #e8f5e9; padding: 15px; border-radius: 10px; margin-top: 10px;">
                    <p style="margin: 0; color: #2E7D32; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i>
                        ✅ Промокод действует 7 дней<br>
                        ✅ Можно активировать только 1 раз<br>
                        ✅ Пользователь введет его в разделе "Активировать промокод"
                    </p>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Выдать промокод',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#9c27b0',
        cancelButtonColor: '#6c757d',
        width: '650px',
        background: 'white',
        didOpen: () => {
            document.getElementById('generate-promo-btn').addEventListener('click', function() {
                document.getElementById('promo-code-input').value = generateCode();
            });
            
            document.getElementById('copy-promo-btn').addEventListener('click', function() {
                const code = document.getElementById('promo-code-input').value;
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Скопировано!',
                        text: 'Код промокода скопирован в буфер обмена',
                        timer: 1500,
                        showConfirmButton: false
                    });
                });
            });
        },
        preConfirm: () => {
            const code = document.getElementById('promo-code-input').value.trim();
            const reward = document.getElementById('promo-reward-input').value;
            const message = document.getElementById('promo-message-input').value.trim();
            
            if (!code) {
                Swal.showValidationMessage('Введите код промокода');
                return false;
            }
            if (!reward || parseFloat(reward) <= 0) {
                Swal.showValidationMessage('Введите корректную сумму');
                return false;
            }
            return { code, reward, message };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Отправка...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=adm_give_promo',
                type: 'POST',
                data: {
                    user_id: userId,
                    code: result.value.code,
                    reward: result.value.reward,
                    message: result.value.message,
                    expires_days: 7 // Срок действия 7 дней
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Промокод выдан!',
                            html: `
                                <div style="text-align: center; padding: 10px;">
                                    <div style="background: linear-gradient(135deg, #9c27b0, #7b1fa2); 
                                        color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
                                        <div style="font-size: 14px; opacity: 0.9;">Промокод</div>
                                        <div style="font-size: 28px; font-weight: bold; font-family: monospace; letter-spacing: 3px; word-break: break-all;">
                                            ${result.value.code}
                                        </div>
                                    </div>
                                    <p style="font-size: 20px; font-weight: bold; color: #4CAF50; margin-bottom: 10px;">
                                        +${parseFloat(result.value.reward).toFixed(2)} ₽
                                    </p>
                                    <p style="color: #666; margin-top: 10px;">
                                        <i class="fas fa-clock"></i> Срок действия: <strong>7 дней</strong><br>
                                        <i class="fas fa-check-circle"></i> Можно активировать только 1 раз
                                    </p>
                                    <p style="color: #2196F3; margin-top: 15px;">
                                        <i class="fas fa-bell"></i> Пользователь введет код в разделе "Активировать промокод"
                                    </p>
                                </div>
                            `,
                            confirmButtonText: 'Готово',
                            confirmButtonColor: '#4CAF50'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось выдать промокод',
                            confirmButtonColor: '#ff4757'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    console.error('❌ Ошибка:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером',
                        confirmButtonColor: '#ff4757'
                    });
                }
            });
        }
    });
}

// ========== ЗАПУСК ПРОВЕРКИ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ ==========
$(document).ready(function() {
    // Ждем загрузки currentUser
    setTimeout(function() {
        if (typeof currentUser !== 'undefined' && currentUser) {
            console.log('👤 Проверка промокода для пользователя:', currentUser.login);
            checkPersonalPromo();
        }
    }, 1500); // Задержка 1.5 секунды
});


// ========== ПРОВЕРКА ПЕРСОНАЛЬНОГО ПРОМОКОДА ПРИ ЗАГРУЗКЕ ==========
function checkPersonalPromoOnLoad() {
    console.log('🎁 ПРОВЕРКА ПЕРСОНАЛЬНОГО ПРОМОКОДА...');
    
    // Проверяем, есть ли пользователь
    if (typeof currentUser === 'undefined' || !currentUser || !currentUser.id) {
        console.log('❌ Пользователь не авторизован, проверка отложена');
        // Пробуем снова через 2 секунды
        setTimeout(checkPersonalPromoOnLoad, 2000);
        return;
    }
    
    console.log('✅ Пользователь найден, ID:', currentUser.id, 'Логин:', currentUser.login);
    
    // Отправляем запрос на сервер
    fetch('api.php?action=check_personal_promo&user_id=' + currentUser.id + '&t=' + Date.now())
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('📦 Ответ от сервера:', data);
            
            if (data.status === 'success' && data.has_promo && data.promo) {
                console.log('🎉 НАЙДЕН ПРОМОКОД!', data.promo);
                
                // Показываем модалку через 1 секунду
                setTimeout(() => {
                    showPersonalPromoModal(data.promo);
                }, 1000);
            } else {
                console.log('ℹ️ Активных промокодов нет');
            }
        })
        .catch(error => {
            console.error('❌ Ошибка проверки промокода:', error);
            // Пробуем снова через 5 секунд при ошибке
            setTimeout(checkPersonalPromoOnLoad, 5000);
        });
}

// ========== ПОКАЗ МОДАЛКИ С ПЕРСОНАЛЬНЫМ ПРОМОКОДОМ ==========
function showPersonalPromoModal(promo) {
    console.log('🎉 ПОКАЗ МОДАЛКИ С ПРОМОКОДОМ:', promo);
    
    // Форматируем дату
    let expiresDate = '';
    if (promo.expires) {
        const date = new Date(promo.expires);
        expiresDate = date.toLocaleDateString('ru-RU', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        });
    }
    
    Swal.fire({
        title: '🎉 Персональный промокод!',
        html: `
            <div style="text-align: center; padding: 15px;">
                <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #ff9800, #ff5722); 
                    border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;
                    box-shadow: 0 10px 25px rgba(255,87,34,0.3); animation: pulse 2s infinite;">
                    <i class="fas fa-gift" style="font-size: 60px; color: white;"></i>
                </div>
                
                <h3 style="color: #333; margin-bottom: 20px; font-size: 24px; font-weight: 700;">
                    Вам подарок от администратора! 🎁
                </h3>
                
                <div style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); 
                    padding: 25px; border-radius: 20px; margin-bottom: 25px;
                    border: 2px solid #ff9800; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    
                    <p style="color: #666; margin-bottom: 15px; font-size: 16px;">
                        Ваш персональный промокод:
                    </p>
                    
                    <div style="background: white; padding: 20px; border-radius: 15px; 
                        border: 3px solid #ff9800; font-size: 28px; font-weight: bold; 
                        color: #ff5722; letter-spacing: 4px; margin-bottom: 20px;
                        font-family: 'Courier New', monospace; word-break: break-all;
                        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);">
                        ${promo.code}
                    </div>
                    
                    <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                        <div style="background: #4CAF50; color: white; padding: 12px 25px; 
                            border-radius: 50px; font-size: 24px; font-weight: bold;
                            box-shadow: 0 5px 15px rgba(76,175,80,0.3);">
                            +${parseFloat(promo.reward).toFixed(2)} ₽
                        </div>
                    </div>
                </div>
                
                ${promo.message ? `
                    <div style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); 
                        padding: 20px; border-radius: 15px; margin-bottom: 25px; 
                        border-left: 6px solid #2196F3; text-align: left;">
                        <p style="color: #1565C0; margin-bottom: 10px; font-weight: 700; font-size: 16px;">
                            <i class="fas fa-envelope" style="margin-right: 10px;"></i>
                            Сообщение от администратора:
                        </p>
                        <p style="color: #333; margin: 0; font-size: 16px; line-height: 1.5;">
                            ${promo.message}
                        </p>
                    </div>
                ` : ''}
                
                ${expiresDate ? `
                    <div style="background: #fff3e0; padding: 15px; border-radius: 12px; margin-bottom: 25px;">
                        <p style="margin: 0; color: #e65100; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-clock"></i>
                            Срок действия: до <strong>${expiresDate}</strong>
                        </p>
                    </div>
                ` : ''}
                
                <button onclick="activatePersonalPromo(${promo.id}, '${promo.code}')" 
                        style="background: linear-gradient(135deg, #ff9800, #ff5722); 
                               color: white; border: none; padding: 18px 35px; 
                               border-radius: 50px; font-size: 20px; font-weight: 700; 
                               cursor: pointer; width: 100%; display: flex;
                               align-items: center; justify-content: center; gap: 15px;
                               box-shadow: 0 10px 25px rgba(255,87,34,0.4);
                               transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);"
                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(255,87,34,0.5)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(255,87,34,0.4)';">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    АКТИВИРОВАТЬ ПРОМОКОД
                </button>
                
                <p style="color: #999; font-size: 14px; margin-top: 25px; padding-top: 15px; border-top: 1px dashed #ddd;">
                    <i class="fas fa-info-circle"></i> 
                    Промокод можно активировать только один раз
                </p>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '650px',
        background: 'white',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            closeButton: 'custom-close-btn'
        },
        didOpen: () => {
            console.log('✅ Модалка с промокодом открыта');
        }
    });
}

// ========== АКТИВАЦИЯ ПЕРСОНАЛЬНОГО ПРОМОКОДА ==========
function activatePersonalPromo(promoId, promoCode) {
    console.log('💰 Активация промокода:', promoId, promoCode);
    
    if (!currentUser || !currentUser.id) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'Необходимо авторизоваться',
            confirmButtonColor: '#ff4757'
        });
        return;
    }
    
    Swal.fire({
        title: '⏳ Активация...',
        text: 'Пожалуйста, подождите',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=activate_personal_promo',
        type: 'POST',
        data: {
            user_id: currentUser.id,
            promo_id: promoId,
            code: promoCode
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            console.log('✅ Ответ:', response);
            
            if (response.status === 'success') {
                // Обновляем баланс
                currentUser.balance = parseFloat(response.new_balance);
                
                // Обновляем сессию
                $.ajax({
                    url: 'api.php?action=update_session_balance',
                    type: 'POST',
                    data: { balance: response.new_balance },
                    async: false
                });
                
                // Обновляем баланс на странице
                $('#balanceAmount, #user-balance').text(parseFloat(response.new_balance).toFixed(2));
                
                Swal.fire({
                    icon: 'success',
                    title: '✅ Промокод активирован!',
                    html: `
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 80px; color: #4CAF50; margin-bottom: 20px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p style="font-size: 28px; font-weight: bold; color: #4CAF50; margin-bottom: 15px;">
                                +${parseFloat(response.reward).toFixed(2)} ₽
                            </p>
                            <p style="font-size: 18px; color: #333;">
                                Текущий баланс: <strong>${parseFloat(response.new_balance).toFixed(2)} ₽</strong>
                            </p>
                        </div>
                    `,
                    timer: 3000,
                    showConfirmButton: false
                });
                
                // Перезагружаем страницу через 3 секунды
                setTimeout(() => {
                    location.reload();
                }, 3000);
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось активировать промокод',
                    confirmButtonColor: '#ff4757'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения с сервером',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}

// ========== ЗАПУСК ПРОВЕРКИ ПРИ ЗАГРУЗКЕ ==========
$(document).ready(function() {
    console.log('🚀 СТРАНИЦА ЗАГРУЖЕНА, ЗАПУСКАЕМ ПРОВЕРКУ...');
    
    // Проверяем сразу
    setTimeout(function() {
        if (typeof currentUser !== 'undefined' && currentUser && currentUser.id) {
            console.log('👤 Пользователь найден сразу:', currentUser.login);
            checkPersonalPromoOnLoad();
        } else {
            console.log('⏳ Пользователь не найден, ждем...');
            
            // Проверяем каждые 2 секунды, пока не появится пользователь
            let checkInterval = setInterval(function() {
                if (typeof currentUser !== 'undefined' && currentUser && currentUser.id) {
                    console.log('✅ Пользователь появился:', currentUser.login);
                    clearInterval(checkInterval);
                    checkPersonalPromoOnLoad();
                }
            }, 2000);
            
            // Останавливаем проверку через 30 секунд
            setTimeout(function() {
                clearInterval(checkInterval);
                console.log('⏰ Таймаут проверки пользователя');
            }, 30000);
        }
    }, 500);
});


// ========== ПОКАЗ МОИХ ТОВАРОВ ==========
function showMyProducts() {
    console.log('📦 Загрузка моих товаров...');
    
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    let html = `
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h1 style="font-size:36px; color:#333; margin: 0;">
                    <i class="fas fa-box" style="color: #667eea;"></i> Мои товары
                </h1>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="showAddProductModal()">
                        <i class="fas fa-plus"></i> Добавить товар
                    </button>
                    <button class="btn" onclick="showUserCabinet()" style="background: #667eea; color: white;">
                        <i class="fas fa-arrow-left"></i> Назад
                    </button>
                </div>
            </div>
            
            <div id="my-products-container" style="min-height: 400px;">
                <div style="text-align: center; padding: 60px;">
                    <div class="loader"></div>
                    <p style="color: #666; margin-top: 20px;">Загрузка ваших товаров...</p>
                </div>
            </div>
        </div>
    `;
    
    $('#content').html(html);
    loadMyProducts();
}

// ========== ЗАГРУЗКА МОИХ ТОВАРОВ ==========
function loadMyProducts() {
    console.log('📥 Загрузка товаров пользователя ID:', currentUser.id);
    
    $.ajax({
        url: 'api.php?action=get_my_products',
        type: 'GET',
        data: { user_id: currentUser.id },
        dataType: 'json',
        success: function(response) {
            console.log('✅ Мои товары:', response);
            
            if (response.status === 'success') {
                if (response.products && response.products.length > 0) {
                    renderMyProducts(response.products);
                } else {
                    showEmptyMyProducts();
                }
            } else {
                showToast('❌ Ошибка загрузки: ' + response.message, 'error');
                $('#my-products-container').html(`
                    <div style="text-align: center; padding: 60px; color: #f44336;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                        <p style="margin-top: 20px;">${response.message || 'Ошибка загрузки товаров'}</p>
                        <button class="btn btn-primary" onclick="loadMyProducts()" style="margin-top: 20px;">
                            <i class="fas fa-sync-alt"></i> Повторить
                        </button>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('❌ Ошибка:', xhr.responseText);
            $('#my-products-container').html(`
                <div style="text-align: center; padding: 60px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                    <p style="margin-top: 20px;">Ошибка соединения с сервером</p>
                    <button class="btn btn-primary" onclick="loadMyProducts()" style="margin-top: 20px;">
                        <i class="fas fa-sync-alt"></i> Повторить
                    </button>
                </div>
            `);
        }
    });
}

// ========== ОТОБРАЖЕНИЕ МОИХ ТОВАРОВ ==========
function renderMyProducts(products) {
    let html = `
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333;">Всего товаров: ${products.length}</h3>
            <div style="display: flex; gap: 10px;">
                <span style="background: #4CAF50; color: white; padding: 5px 15px; border-radius: 20px;">
                    Активных: ${products.filter(p => p.status === 'active').length}
                </span>
                <span style="background: #6c757d; color: white; padding: 5px 15px; border-radius: 20px;">
                    Снятых: ${products.filter(p => p.status === 'inactive').length}
                </span>
            </div>
        </div>
        <div class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
    `;
    
    products.forEach(product => {
        let statusText = product.status === 'active' ? '🟢 Активен' : '⚪ Снят с продажи';
        let statusColor = product.status === 'active' ? '#4CAF50' : '#6c757d';
        
        let actionButton = product.status === 'active' 
            ? `<button class="btn" onclick="toggleProductStatus(${product.id}, 'inactive')" 
                    style="background: #ff9800; color: white; width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-pause-circle"></i> Снять с продажи
               </button>`
            : `<button class="btn" onclick="toggleProductStatus(${product.id}, 'active')" 
                    style="background: #4CAF50; color: white; width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-play-circle"></i> Выставить на продажу
               </button>`;
        
        html += `
            <div class="product-card" style="border: 2px solid ${statusColor}; position: relative;">
                <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                    <span style="background: ${statusColor}; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                        ${statusText}
                    </span>
                </div>
                
                <div class="product-image" style="height: 200px; overflow: hidden;">
                    ${product.main_image 
                        ? `<img src="${product.main_image}" style="width:100%; height:100%; object-fit:cover;">`
                        : `<div style="width:100%; height:100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-box"></i>
                           </div>`
                    }
                </div>
                
                <div class="product-info" style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #333; font-size: 18px;">${product.title}</h3>
                    <p style="color: #666; margin-bottom: 15px; font-size: 14px;">${product.description || 'Нет описания'}</p>
                    
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="color: #4CAF50; font-size: 24px; font-weight: bold;">
                                ${parseFloat(product.price).toFixed(2)} ₽
                            </span>
                            ${product.discount > 0 ? 
                                `<span style="background: #ff4757; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px;">
                                    -${product.discount}%
                                </span>` : ''
                            }
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; color: #666; font-size: 13px;">
                            <span><i class="fas fa-layer-group"></i> В наличии: ${product.stock} шт.</span>
                            <span><i class="fas fa-eye"></i> Просмотров: ${product.views || 0}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: #666; font-size: 13px; margin-top: 5px;">
                            <span><i class="fas fa-shopping-cart"></i> Продано: ${product.sold || 0}</span>
                            <span><i class="fas fa-calendar"></i> ${new Date(product.created_at).toLocaleDateString('ru-RU')}</span>
                        </div>
                    </div>
                    
                    ${actionButton}
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    $('#my-products-container').html(html);
}

// ========== ПУСТОЙ СПИСОК ТОВАРОВ ==========
function showEmptyMyProducts() {
    $('#my-products-container').html(`
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; color: #e0e0e0; margin-bottom: 20px;">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 15px;">У вас пока нет товаров</h3>
            <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Добавьте свой первый товар и начните продавать!
            </p>
            <button class="btn btn-primary" onclick="showAddProductModal()" style="padding: 12px 30px;">
                <i class="fas fa-plus"></i> Добавить товар
            </button>
        </div>
    `);
}

// ========== ПЕРЕКЛЮЧЕНИЕ СТАТУСА ТОВАРА ==========
function toggleProductStatus(productId, newStatus) {
    console.log('🔄 Изменение статуса товара:', productId, '->', newStatus);
    
    let actionText = newStatus === 'active' ? 'выставить на продажу' : 'снять с продажи';
    
    Swal.fire({
        title: 'Подтверждение',
        text: `Вы уверены, что хотите ${actionText} этот товар?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        confirmButtonColor: newStatus === 'active' ? '#4CAF50' : '#ff9800'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Обновление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=update_product_status',
                type: 'POST',
                data: {
                    product_id: productId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Готово!',
                            text: `Товар ${newStatus === 'active' ? 'выставлен на продажу' : 'снят с продажи'}`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Обновляем список товаров
                        setTimeout(() => {
                            showMyProducts();
                        }, 500);
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось изменить статус'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}

// ========== ЗАГРУЗКА МОИХ ТОВАРОВ ==========
function loadMyProducts() {
    console.log('📥 Загрузка товаров пользователя ID:', currentUser.id);
    
    $.ajax({
        url: 'api.php?action=get_my_products',
        type: 'GET',
        data: { user_id: currentUser.id },
        dataType: 'json',
        success: function(response) {
            console.log('✅ Мои товары:', response);
            
            if (response.status === 'success') {
                if (response.products && response.products.length > 0) {
                    renderMyProducts(response.products);
                } else {
                    showEmptyMyProducts();
                }
            } else {
                showToast('❌ Ошибка загрузки: ' + response.message, 'error');
                $('#my-products-container').html(`
                    <div style="text-align: center; padding: 60px; color: #f44336;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                        <p style="margin-top: 20px;">${response.message || 'Ошибка загрузки товаров'}</p>
                        <button class="btn btn-primary" onclick="loadMyProducts()" style="margin-top: 20px;">
                            <i class="fas fa-sync-alt"></i> Повторить
                        </button>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('❌ Ошибка:', xhr.responseText);
            $('#my-products-container').html(`
                <div style="text-align: center; padding: 60px; color: #f44336;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px;"></i>
                    <p style="margin-top: 20px;">Ошибка соединения с сервером</p>
                    <button class="btn btn-primary" onclick="loadMyProducts()" style="margin-top: 20px;">
                        <i class="fas fa-sync-alt"></i> Повторить
                    </button>
                </div>
            `);
        }
    });
}

// ========== ОТОБРАЖЕНИЕ МОИХ ТОВАРОВ ==========
function renderMyProducts(products) {
    let html = `
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #333;">Всего товаров: ${products.length}</h3>
            <div style="display: flex; gap: 10px;">
                <span style="background: #4CAF50; color: white; padding: 5px 15px; border-radius: 20px;">
                    Активных: ${products.filter(p => p.status === 'active').length}
                </span>
                <span style="background: #6c757d; color: white; padding: 5px 15px; border-radius: 20px;">
                    Снятых: ${products.filter(p => p.status === 'inactive').length}
                </span>
            </div>
        </div>
        <div class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
    `;
    
    products.forEach(product => {
        let statusClass = product.status === 'active' ? 'success' : 'secondary';
        let statusText = product.status === 'active' ? '🟢 Активен' : '⚪ Снят с продажи';
        let statusColor = product.status === 'active' ? '#4CAF50' : '#6c757d';
        
        let actionButton = product.status === 'active' 
            ? `<button class="btn" onclick="toggleProductStatus(${product.id}, 'inactive')" 
                    style="background: #ff9800; color: white; width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-pause-circle"></i> Снять с продажи
               </button>`
            : `<button class="btn" onclick="toggleProductStatus(${product.id}, 'active')" 
                    style="background: #4CAF50; color: white; width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-play-circle"></i> Выставить на продажу
               </button>`;
        
        html += `
            <div class="product-card" style="border: 2px solid ${statusColor}; position: relative;">
                <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                    <span style="background: ${statusColor}; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                        ${statusText}
                    </span>
                </div>
                
                <div class="product-image" style="height: 200px; overflow: hidden;">
                    ${product.main_image 
                        ? `<img src="${product.main_image}" style="width:100%; height:100%; object-fit:cover;">`
                        : `<div style="width:100%; height:100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-box"></i>
                           </div>`
                    }
                </div>
                
                <div class="product-info" style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #333; font-size: 18px;">${product.title}</h3>
                    <p style="color: #666; margin-bottom: 15px; font-size: 14px;">${product.description || 'Нет описания'}</p>
                    
                    <div style="margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="color: #4CAF50; font-size: 24px; font-weight: bold;">
                                ${parseFloat(product.price).toFixed(2)} ₽
                            </span>
                            ${product.discount > 0 ? 
                                `<span style="background: #ff4757; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px;">
                                    -${product.discount}%
                                </span>` : ''
                            }
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; color: #666; font-size: 13px;">
                            <span><i class="fas fa-layer-group"></i> В наличии: ${product.stock} шт.</span>
                            <span><i class="fas fa-eye"></i> Просмотров: ${product.views || 0}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; color: #666; font-size: 13px; margin-top: 5px;">
                            <span><i class="fas fa-shopping-cart"></i> Продано: ${product.sold || 0}</span>
                            <span><i class="fas fa-calendar"></i> ${new Date(product.created_at).toLocaleDateString('ru-RU')}</span>
                        </div>
                    </div>
                    
                    ${actionButton}
                    
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <button class="btn" onclick="editProduct(${product.id})" 
                                style="flex: 1; background: #2196F3; color: white; padding: 8px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-edit"></i> Редактировать
                        </button>
                        <button class="btn" onclick="deleteProduct(${product.id})" 
                                style="flex: 1; background: #f44336; color: white; padding: 8px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-trash"></i> Снять с продажи
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    $('#my-products-container').html(html);
}

// ========== ПУСТОЙ СПИСОК ТОВАРОВ ==========
function showEmptyMyProducts() {
    $('#my-products-container').html(`
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; color: #e0e0e0; margin-bottom: 20px;">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 style="color: #666; margin-bottom: 15px;">У вас пока нет товаров</h3>
            <p style="color: #999; margin-bottom: 30px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Добавьте свой первый товар и начните продавать!
            </p>
            <button class="btn btn-primary" onclick="showAddProductModal()" style="padding: 12px 30px;">
                <i class="fas fa-plus"></i> Добавить товар
            </button>
        </div>
    `);
}

function toggleProductStatus(productId, newStatus) {
    console.log('🔄 Изменение статуса товара:', productId, '->', newStatus);
    
    let actionText = newStatus === 'active' ? 'выставить на продажу' : 'снять с продажи';
    let actionColor = newStatus === 'active' ? '#4CAF50' : '#ff9800';
    
    Swal.fire({
        title: 'Подтверждение',
        text: `Вы уверены, что хотите ${actionText} этот товар?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        confirmButtonColor: actionColor
    }).then((result) => {
        if (result.isConfirmed) {
            // Показываем загрузку
            Swal.fire({
                title: '⏳ Обновление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=update_product_status',
                type: 'POST',
                data: {
                    product_id: productId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    console.log('✅ Ответ сервера:', response);
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Готово!',
                            text: `Товар ${newStatus === 'active' ? 'выставлен на продажу' : 'снят с продажи'}`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Принудительно перезагружаем список товаров
                        setTimeout(() => {
                            loadMyProducts();
                        }, 500);
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось изменить статус'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error('❌ Ошибка AJAX:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    
                    let errorMsg = 'Ошибка соединения с сервером';
                    try {
                        const resp = JSON.parse(xhr.responseText);
                        errorMsg = resp.message || errorMsg;
                    } catch(e) {
                        if (xhr.responseText) {
                            errorMsg = xhr.responseText.substring(0, 100);
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: errorMsg
                    });
                }
            });
        }
    });
}

// ========== УДАЛЕНИЕ ТОВАРА ==========
function deleteProduct(productId) {
    console.log('🗑️ Удаление товара:', productId);
    
    Swal.fire({
        title: 'Удаление товара',
        text: 'Вы уверены, что хотите удалить этот товар? Это действие нельзя отменить!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить',
        cancelButtonText: 'Отмена',
        confirmButtonColor: '#f44336'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Удаление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=delete_product',
                type: 'POST',
                data: {
                    product_id: productId
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Товар удален',
                            text: 'Товар успешно удален',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Обновляем список товаров
                        loadMyProducts();
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось удалить товар'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером'
                    });
                }
            });
        }
    });
}
// ===== УНИВЕРСАЛЬНЫЙ ПОИСК =====
function searchProducts() {
    const searchText = $('#search-input').val().toLowerCase().trim();
    console.log('🔍 Поиск:', searchText);
    
    const $cards = $('#products-list').children();
    
    if (!searchText) {
        $cards.show();
        $('#search-results-message').remove();
        return;
    }
    
    let found = 0;
    $cards.each(function() {
        const text = $(this).text().toLowerCase();
        if (text.includes(searchText)) {
            $(this).show();
            found++;
        } else {
            $(this).hide();
        }
    });
    
    // Удаляем старое сообщение
    $('#search-results-message').remove();
    
    // Добавляем сообщение о результате
    if (found === 0) {
        $('#products-list').after(`<div id="search-results-message" style="text-align: center; padding: 20px; color: #f44336; background: #ffebee; border-radius: 8px; margin-top: 20px;">
            <i class="fas fa-search"></i> Ничего не найдено по запросу "<strong>${searchText}</strong>"
        </div>`);
    } else {
        $('#products-list').after(`<div id="search-results-message" style="text-align: center; padding: 10px; color: #4CAF50; background: #e8f5e9; border-radius: 8px; margin-top: 20px;">
            <i class="fas fa-check-circle"></i> Найдено товаров: <strong>${found}</strong>
        </div>`);
    }
}

// ===== ЖИВАЯ ФИЛЬТРАЦИЯ ТОВАРОВ =====
$('#search-input').on('input', function() {
    const searchText = $(this).val().toLowerCase().trim();
    console.log('🔍 Фильтр:', searchText);
    
    let visibleCount = 0;
    
    // Проходим по всем карточкам товаров
    $('#products-list').children().each(function() {
        const $card = $(this);
        const title = $card.find('h3, .product-title, strong').first().text().toLowerCase();
        const desc = $card.find('p').first().text().toLowerCase();
        const allText = $card.text().toLowerCase();
        
        // Проверяем есть ли поисковый текст в названии или описании
        if (searchText === '' || title.includes(searchText) || desc.includes(searchText) || allText.includes(searchText)) {
            $card.show();
            visibleCount++;
        } else {
            $card.hide();
        }
    });
    
    // Удаляем старые сообщения
    $('#filter-message').remove();
    
    // Показываем сообщение если ничего не найдено
    if (visibleCount === 0 && searchText !== '') {
        $('#products-list').after(`
            <div id="filter-message" style="text-align: center; padding: 40px; color: #666; background: #f8f9fa; border-radius: 10px; margin-top: 20px;">
                <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                <h3>Ничего не найдено</h3>
                <p>По запросу "<strong>${searchText}</strong>" товаров нет</p>
            </div>
        `);
    }
    
    // Показываем количество найденных
    if (searchText !== '' && visibleCount > 0) {
        $('#products-list').before(`
            <div id="filter-message" style="margin-bottom: 15px; padding: 10px; background: #e8f5e9; color: #4CAF50; border-radius: 8px;">
                <i class="fas fa-check-circle"></i> Найдено товаров: ${visibleCount}
            </div>
        `);
    }
});

// Также фильтруем при загрузке
$(document).ready(function() {
    // Запускаем фильтр после загрузки товаров
    setTimeout(function() {
        $('#search-input').trigger('input');
    }, 1000);
});


    
    // Вешаем обработчик на все ссылки с текстом "Личный кабинет"
    $(document).on('click', 'a:contains("Личный кабинет")', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🖱️ Клик по кабинету');
        
        if (!currentUser) {
            showLoginModal();
        } else {
            showUserCabinet();
        }
        return false;
    });

    // ========== МОИ ТОВАРЫ - ПОЛНАЯ ВЕРСИЯ ==========
window.myProductsHandler = function() {
    console.log('🖱️ Открытие Моих товаров');
    
    if (!currentUser) {
        showLoginModal();
        return;
    }
    
    // Показываем загрузку
    $('#content').html(`
        <div style="margin-top:30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h1 style="font-size:36px; color:#333;">📦 Мои товары</h1>
                <button class="btn" onclick="showUserCabinet()" style="background: #667eea; color: white;">
                    ← Назад
                </button>
            </div>
            <div id="my-products-container" style="text-align: center; padding: 60px;">
                <div class="loader"></div>
                <p style="color: #666; margin-top: 20px;">Загрузка ваших товаров...</p>
            </div>
        </div>
    `);
    
    // Загружаем товары с сервера
    $.ajax({
        url: 'api.php?action=get_my_products',
        type: 'GET',
        data: { user_id: currentUser.id },
        dataType: 'json',
        success: function(response) {
            console.log('✅ Товары загружены:', response);
            
            if (response.status === 'success') {
                if (response.products && response.products.length > 0) {
                    displayMyProducts(response.products);
                } else {
                    $('#my-products-container').html(`
                        <div style="background: white; border-radius: 15px; padding: 60px 20px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                            <i class="fas fa-box-open" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
                            <h3 style="color: #666; margin-bottom: 15px;">У вас пока нет товаров</h3>
                            <p style="color: #999; margin-bottom: 30px;">Добавьте свой первый товар и начните продавать!</p>
                            <button class="btn btn-primary" onclick="showAddProductModal()">
                                <i class="fas fa-plus"></i> Добавить товар
                            </button>
                        </div>
                    `);
                }
            } else {
                $('#my-products-container').html(`
                    <div style="color: #f44336; text-align: center; padding: 40px;">
                        ❌ Ошибка: ${response.message || 'Не удалось загрузить товары'}
                    </div>
                `);
            }
        },
        error: function() {
            $('#my-products-container').html(`
                <div style="color: #f44336; text-align: center; padding: 40px;">
                    ❌ Ошибка соединения с сервером
                </div>
            `);
        }
    });
};

// ========== ОТОБРАЖЕНИЕ ТОВАРОВ ==========
function displayMyProducts(products) {
    let html = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="margin: 0;">Всего товаров: ${products.length}</h3>
            <div>
                <span style="background: #4CAF50; color: white; padding: 5px 15px; border-radius: 20px; margin-right: 10px;">
                    Активных: ${products.filter(p => p.status === 'active').length}
                </span>
                <span style="background: #6c757d; color: white; padding: 5px 15px; border-radius: 20px;">
                    Снятых: ${products.filter(p => p.status === 'inactive').length}
                </span>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    `;
    
    products.forEach(product => {
        let statusColor = product.status === 'active' ? '#4CAF50' : '#6c757d';
        let statusText = product.status === 'active' ? '🟢 Активен' : '⚪ Снят';
        
        html += `
            <div style="border: 2px solid ${statusColor}; border-radius: 12px; overflow: hidden; background: white;">
                <div style="height: 200px; background: linear-gradient(135deg, #667eea, #764ba2); position: relative;">
                    ${product.main_image ? 
                        `<img src="${product.main_image}" style="width:100%; height:100%; object-fit:cover;">` : 
                        `<div style="width:100%; height:100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-box"></i>
                        </div>`
                    }
                    <span style="position: absolute; top: 10px; right: 10px; background: ${statusColor}; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                        ${statusText}
                    </span>
                </div>
                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0;">${product.title}</h3>
                    <p style="color: #666; margin-bottom: 15px;">${product.description || 'Нет описания'}</p>
                    
                    <div style="font-size: 22px; font-weight: bold; color: #4CAF50; margin-bottom: 15px;">
                        ${parseFloat(product.price).toFixed(2)} ₽
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        ${product.status === 'active' 
                            ? `<button onclick="toggleMyProductStatus(${product.id}, 'inactive')" 
                                    style="flex:1; background: #ff9800; color: white; padding: 10px; border: none; border-radius: 6px; cursor: pointer;">
                                    Снять с продажи
                               </button>`
                            : `<button onclick="toggleMyProductStatus(${product.id}, 'active')" 
                                    style="flex:1; background: #4CAF50; color: white; padding: 10px; border: none; border-radius: 6px; cursor: pointer;">
                                    Выставить
                               </button>`
                        }
                        <button onclick="deleteMyProduct(${product.id})" 
                                style="flex:1; background: #f44336; color: white; padding: 10px; border: none; border-radius: 6px; cursor: pointer;">
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    $('#my-products-container').html(html);
}

// ========== ИЗМЕНЕНИЕ СТАТУСА (С МОДАЛКОЙ) ==========
function toggleMyProductStatus(productId, newStatus) {
    let actionText = newStatus === 'active' ? 'выставить на продажу' : 'снять с продажи';
    
    Swal.fire({
        title: 'Подтверждение',
        text: `Вы уверены, что хотите ${actionText} этот товар?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        confirmButtonColor: newStatus === 'active' ? '#4CAF50' : '#ff9800'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Обновление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=update_product_status',
                type: 'POST',
                data: { product_id: productId, status: newStatus },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Статус обновлен!',
                            text: `Товар ${newStatus === 'active' ? 'выставлен на продажу' : 'снят с продажи'}`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        window.myProductsHandler(); // Перезагружаем список
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось изменить статус',
                            confirmButtonColor: '#ff4757'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером',
                        confirmButtonColor: '#ff4757'
                    });
                }
            });
        }
    });
}

// ========== УДАЛЕНИЕ ТОВАРА (С МОДАЛКОЙ) ==========
function deleteMyProduct(productId) {
    Swal.fire({
        title: '❌ Удаление товара',
        text: 'Вы уверены, что хотите удалить этот товар? Это действие нельзя отменить!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить',
        cancelButtonText: 'Отмена',
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Удаление...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: 'api.php?action=delete_product',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Товар удален!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        window.myProductsHandler(); // Перезагружаем список
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось удалить товар',
                            confirmButtonColor: '#ff4757'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером',
                        confirmButtonColor: '#ff4757'
                    });
                }
            });
        }
    });
}

// ========== ПРОВЕРКА PIN-КОДА ==========
let pinVerified = false;

function checkPinAccess() {
    console.log('🔐 Проверка PIN-кода');
    
    // Если пользователь не авторизован - пропускаем
    if (!currentUser) return true;
    
    // Если PIN уже подтвержден в этой сессии - пропускаем
    if (pinVerified) return true;
    
    // Проверяем, установлен ли PIN у пользователя
    $.ajax({
        url: 'api.php?action=check_pin_status',
        method: 'GET',
        async: false,
        success: function(response) {
            console.log('PIN статус:', response);
            
            if (response.status === 'success' && response.has_pin) {
                // Запрашиваем PIN
                showPinVerificationModal();
            }
        }
    });
}

// ========== МОДАЛКА ВВОДА PIN (БЫСТРАЯ) ==========
function showPinVerificationModal() {
    console.log('🔑 Показ модалки PIN');
    
    let pinInputs = '';
    for (let i = 1; i <= 4; i++) {
        pinInputs += `<input type="password" id="pin${i}" class="pin-digit" maxlength="1" style="width:50px; height:60px; text-align:center; font-size:24px; margin:0 5px;" onkeyup="movePinFocus(${i})">`;
    }
    
    Swal.fire({
        title: 'Введите PIN-код',
        html: `
            <div style="text-align: center;">
                <p style="margin-bottom: 20px;">Для доступа к кабинету введите ваш PIN-код</p>
                <div style="display: flex; justify-content: center;">
                    ${pinInputs}
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Подтвердить',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#6c757d',
        allowOutsideClick: false,
        didOpen: () => {
            $('#pin1').focus();
        },
        preConfirm: () => {
            const pin1 = $('#pin1').val() || '';
            const pin2 = $('#pin2').val() || '';
            const pin3 = $('#pin3').val() || '';
            const pin4 = $('#pin4').val() || '';
            const pin = pin1 + pin2 + pin3 + pin4;
            
            if (pin.length !== 4) {
                Swal.showValidationMessage('Введите 4 цифры');
                return false;
            }
            return pin;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            verifyPin(result.value);
        } else {
            showHome(); // Если отмена - на главную
        }
    });
}

// ========== ПЕРЕМЕЩЕНИЕ МЕЖДУ ПОЛЯМИ PIN ==========
function movePinFocus(current) {
    if ($(`#pin${current}`).val().length === 1) {
        if (current < 4) {
            $(`#pin${current+1}`).focus();
        } else {
            // Если ввели последнюю цифру - автоматически подтверждаем
            setTimeout(() => {
                const pin1 = $('#pin1').val() || '';
                const pin2 = $('#pin2').val() || '';
                const pin3 = $('#pin3').val() || '';
                const pin4 = $('#pin4').val() || '';
                const pin = pin1 + pin2 + pin3 + pin4;
                
                if (pin.length === 4) {
                    Swal.clickConfirm();
                }
            }, 100);
        }
    }
}

// ========== ПЕРЕМЕЩЕНИЕ МЕЖДУ ПОЛЯМИ PIN ==========
function movePinFocus(next) {
    if (next < 4 && $(`#pin${next}`).val().length === 1) {
        $(`#pin${next+1}`).focus();
    }
    
    // Если ввели 4 цифры - автоматическая отправка
    if (next === 4) {
        setTimeout(() => {
            const pin1 = $('#pin1').val() || '';
            const pin2 = $('#pin2').val() || '';
            const pin3 = $('#pin3').val() || '';
            const pin4 = $('#pin4').val() || '';
            const pin = pin1 + pin2 + pin3 + pin4;
            
            if (pin.length === 4) {
                Swal.clickConfirm();
            }
        }, 100);
    }
}

// ========== ПРОВЕРКА PIN ==========
function verifyPin(pin) {
    console.log('🔑 Проверка PIN');
    
    Swal.fire({
        title: '⏳ Проверка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=verify_pin',
        method: 'POST',
        data: { pin: pin },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                pinVerified = true;
                Swal.fire({
                    icon: 'success',
                    title: '✅ PIN подтвержден!',
                    timer: 1500,
                    showConfirmButton: false
                });
                // После подтверждения открываем кабинет
                showUserCabinet();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Неверный PIN',
                    text: 'Попробуйте снова',
                    confirmButtonColor: '#ff4757'
                }).then(() => {
                    showPinVerificationModal(); // Показываем снова
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Проблема с соединением',
                confirmButtonColor: '#ff4757'
            });
        }
    });
}

// ========== ПРИНУДИТЕЛЬНАЯ ПРОВЕРКА PIN ==========
function forcePinCheck() {
    if (!currentUser) return false;
    
    $.ajax({
        url: 'api.php?action=check_pin_status',
        method: 'GET',
        async: false,
        success: function(response) {
            if (response.status === 'success' && response.has_pin && !pinVerified) {
                showPinVerificationModal();
                return false;
            }
        }
    });
    return true;
}
// ========== ОБРАБОТЧИК ДЛЯ КНОПОК ОТВЕТА ==========
$(document).on('click', '.reply-appeal-btn', function() {
    const appealId = $(this).data('appeal-id');
    const userId = $(this).data('user-id');
    const ticketId = $(this).data('ticket-id');
    
    console.log('🔘 Нажата кнопка ответа:', {appealId, userId, ticketId});
    
    if (!appealId || !userId) {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'ID апелляции или пользователя не указан'
        });
        return;
    }
    
    Swal.fire({
        title: '✍️ Ответ на апелляцию',
        html: `
            <div style="text-align: left;">
                <p style="margin-bottom: 15px; color: #666;">
                    <i class="fas fa-info-circle"></i> 
                    Пользователь ID: <strong>${userId}</strong>
                    ${ticketId ? `<br>Тикет: #${ticketId}` : ''}
                </p>
                <textarea id="admin-reply-text" class="swal2-textarea" 
                    placeholder="Введите ваш ответ..." 
                    style="min-height: 150px; width: 100%;"></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '✅ Отправить',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#4CAF50',
        preConfirm: () => {
            const reply = document.getElementById('admin-reply-text').value.trim();
            if (!reply) {
                Swal.showValidationMessage('Введите текст ответа');
                return false;
            }
            return reply;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            sendAppealReply(appealId, userId, ticketId, result.value);
        }
    });
});

// ========== ОТПРАВКА ОТВЕТА ==========
function sendAppealReply(appealId, userId, ticketId, message) {
    Swal.fire({
        title: '⏳ Отправка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: 'api.php?action=admin_reply_appeal',
        type: 'POST',
        data: {
            appeal_id: appealId,
            user_id: userId,
            ticket_id: ticketId || 0,
            response: message
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Ответ отправлен!',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                if (typeof loadAdminAppeals === 'function') {
                    loadAdminAppeals();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось отправить ответ'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            console.error('❌ Ошибка:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Ошибка соединения с сервером'
            });
        }
    });
}
</script>

<style>
/* Карточка товара */
.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Распроданный товар */
.product-card.out-of-stock {
    opacity: 0.85;
    filter: grayscale(0.3);
}

/* Изображение */
.product-image {
    height: 200px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 48px;
}

/* Кнопка избранного */
.favorite-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    z-index: 10;
    transition: transform 0.2s;
}

.favorite-btn:hover {
    transform: scale(1.1);
}

.favorite-btn i {
    color: #e91e63;
    font-size: 20px;
}

/* Бейдж скидки */
.discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ff4757;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    z-index: 10;
}

/* Бейдж "НЕТ В НАЛИЧИИ" */
.out-of-stock-badge {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.85);
    color: white;
    padding: 12px 24px;
    border-radius: 40px;
    font-weight: bold;
    font-size: 16px;
    z-index: 20;
    border: 2px solid white;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.05); }
    100% { transform: translate(-50%, -50%) scale(1); }
}

/* Информация о товаре */
.product-info {
    padding: 16px;
}

.product-title {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-description {
    margin: 0 0 12px 0;
    color: #666;
    font-size: 14px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 42px;
}

.product-price {
    font-size: 22px;
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 12px;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    color: #888;
    font-size: 14px;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #eee;
}

/* Кнопки */
.add-to-cart-btn {
    width: 100%;
    padding: 12px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.3s;
}

.add-to-cart-btn:hover {
    background: #45a049;
}

.out-of-stock-btn {
    width: 100%;
    padding: 12px;
    background: #ccc;
    color: #666;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 16px;
    cursor: not-allowed;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Адаптивность */
@media (max-width: 992px) {
    #products-list {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 576px) {
    #products-list {
        grid-template-columns: 1fr !important;
    }
}
</style>



<script>
// ========== МОИ АПЕЛЛЯЦИИ ==========
window.userViewMyAppeals = function() {
    console.log('📋 Открытие апелляций');
    
    // Показываем модалку для ввода логина
    Swal.fire({
        title: '🔍 Поиск апелляций',
        html: `
            <div style="text-align: left; padding: 10px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Введите логин пользователя
                </label>
                <input type="text" id="appeal-login-input" class="swal2-input" 
                       placeholder="Например: admin, user123" 
                       style="width: 100%; margin: 0; border: 2px solid #e0e0e0; border-radius: 10px; padding: 12px;">
                <p style="color: #666; font-size: 13px; margin-top: 10px;">
                    <i class="fas fa-info-circle" style="color: #2196F3;"></i>
                    Введите логин, который использовали при регистрации
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '🔍 Найти апелляции',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#2196F3',
        cancelButtonColor: '#6c757d',
        background: 'white',
        width: '500px',
        preConfirm: () => {
            const login = document.getElementById('appeal-login-input').value.trim();
            if (!login) {
                Swal.showValidationMessage('Введите логин');
                return false;
            }
            return login;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            loadAppealsByLogin(result.value);
        }
    });
};

// Загрузка апелляций по логину
function loadAppealsByLogin(login) {
    Swal.fire({
        title: '⏳ Загрузка...',
        html: `
            <div style="text-align: center; padding: 20px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 40px; color: #2196F3;"></i>
                <p style="margin-top: 20px; color: #666;">Ищем апелляции для <strong>${login}</strong></p>
            </div>
        `,
        allowOutsideClick: false,
        background: 'white',
        showConfirmButton: false
    });
    
    fetch('api.php?action=get_user_appeals_by_login&login=' + encodeURIComponent(login))
        .then(r => r.json())
        .then(response => {
            Swal.close();
            console.log('Ответ сервера:', response);
            
            if (response.status === 'success') {
                if (response.appeals && response.appeals.length > 0) {
                    showAppealsModal(response.appeals, login);
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: '❌ Апелляций нет',
                        html: `
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-inbox" style="font-size: 60px; color: #ccc; margin-bottom: 20px;"></i>
                                <p style="color: #666;">У пользователя <strong>${login}</strong> нет апелляций</p>
                            </div>
                        `,
                        confirmButtonText: 'Понятно',
                        confirmButtonColor: '#4CAF50',
                        background: 'white'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Пользователь не найден',
                    confirmButtonColor: '#ff4757',
                    background: 'white'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: 'Проблема с соединением',
                confirmButtonColor: '#ff4757',
                background: 'white'
            });
        });
}

// Показать апелляции в модалке
function showAppealsModal(appeals, login) {
    let html = `
        <div style="max-height: 500px; overflow-y: auto; padding: 10px;">
            <div style="background: linear-gradient(135deg, #2196F3, #1976D2); color: white; padding: 20px; border-radius: 15px; margin-bottom: 25px; text-align: center;">
                <i class="fas fa-gavel" style="font-size: 40px; margin-bottom: 10px;"></i>
                <h3 style="margin: 0;">Апелляции пользователя</h3>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Логин: ${login}</p>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">Всего: ${appeals.length}</p>
            </div>
    `;
    
    appeals.forEach(appeal => {
        // Определяем цвет и текст статуса
        let statusColor = '#6c757d';
        let statusText = 'Неизвестно';
        let statusBg = '#f8f9fa';
        
        switch(appeal.status) {
            case 'new':
                statusColor = '#ff9800';
                statusText = '🟡 Новая';
                statusBg = '#fff3e0';
                break;
            case 'in_progress':
                statusColor = '#2196F3';
                statusText = '🔵 В работе';
                statusBg = '#e3f2fd';
                break;
            case 'closed':
                statusColor = '#4CAF50';
                statusText = '🟢 Решена';
                statusBg = '#e8f5e9';
                break;
        }
        
        let date = appeal.created_at ? new Date(appeal.created_at).toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) : 'Нет даты';
        
        html += `
            <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; border-left: 6px solid ${statusColor}; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                <!-- Шапка с статусом и номером -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <span style="background: ${statusColor}; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px; font-weight: bold;">
                            ${statusText}
                        </span>
                        <span style="background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-size: 13px;">
                            №${appeal.ticket_id || appeal.id}
                        </span>
                    </div>
                    <span style="color: #666; font-size: 12px;">
                        <i class="fas fa-calendar"></i> ${date}
                    </span>
                </div>
                
                <!-- Причина бана -->
                <div style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <strong style="color: #e65100;">📌 Причина блокировки:</strong>
                    <p style="margin: 10px 0 0 0; color: #333;">${appeal.ban_reason || 'Не указана'}</p>
                </div>
                
                <!-- Сообщение пользователя -->
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                    <strong style="color: #333;">💬 Ваше сообщение:</strong>
                    <p style="margin: 10px 0 0 0; color: #666; white-space: pre-wrap;">${appeal.message || 'Нет сообщения'}</p>
                </div>
        `;
        
        // Ответ администратора (если есть)
        if (appeal.admin_response) {
            html += `
                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; border-left: 4px solid #2196F3;">
                    <strong style="color: #1565C0;">👨‍⚖️ Ответ администратора:</strong>
                    <p style="margin: 10px 0 0 0; color: #333; white-space: pre-wrap;">${appeal.admin_response}</p>
                </div>
            `;
        } else {
            html += `
                <div style="background: #fff3cd; padding: 15px; border-radius: 10px;">
                    <strong style="color: #856404;">⏳ Статус:</strong>
                    <p style="margin: 10px 0 0 0; color: #666;">Ожидание ответа администратора</p>
                </div>
            `;
        }
        
        html += `</div>`;
    });
    
    html += '</div>';
    
    Swal.fire({
        title: '',
        html: html,
        showConfirmButton: true,
        confirmButtonText: 'Закрыть',
        confirmButtonColor: '#4CAF50',
        width: '700px',
        background: 'white',
        showCloseButton: true,
        customClass: {
            closeButton: 'swal2-close'
        }
    });

}

// Дублируем для надежности
window.viewMyAppeals = window.userViewMyAppeals;
console.log('✅ Модальные окна апелляций загружены');

            function hideBanModal() {
    $('#ban-fullscreen').css('display', 'none').hide();
}

// Функция выхода из бана
function logoutFromBan() {
    Swal.fire({
        title: '⚠️ Выход из аккаунта',
        text: 'Вы уверены, что хотите выйти?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Да, выйти',
        cancelButtonText: '❌ Отмена',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        background: 'white'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '⏳ Выход...',
                text: 'Пожалуйста, подождите',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            // Вызываем выход
            fetch('api.php?action=logout')
                .then(() => {
                    Swal.close();
                    // Показываем сообщение
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Вы вышли',
                        text: 'До свидания!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Перезагружаем страницу
                        location.reload();
                    });
                })
                .catch(() => {
                    Swal.close();
                    // Если ошибка - всё равно перезагружаем
                    location.reload();
                });
        }
    });
}
</script>
<?php
// Проверяем, первый ли раз пользователь на сайте
if (!isset($_COOKIE['cookie_consent'])) {
    $showCookieModal = true;
} else {
    $showCookieModal = false;
}
?>

<!-- Модальное окно согласия на cookies -->
<?php if ($showCookieModal): ?>
<div id="cookie-consent-modal" style="position: fixed; bottom: 30px; left: 30px; right: 30px; max-width: 450px; background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); z-index: 10000; padding: 25px; animation: slideUpCookie 0.5s ease-out; margin: 0 auto; border: 1px solid #f0f0f0;">
    <style>
        @keyframes slideUpCookie {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-cookie-bite" style="font-size: 24px; color: white;"></i>
        </div>
        <div>
            <h3 style="margin: 0; color: #333; font-size: 20px;">Мы используем cookies</h3>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 13px;">Чтобы сайт работал лучше</p>
        </div>
    </div>
    
    <p style="color: #555; line-height: 1.6; margin-bottom: 25px; font-size: 14px;">
        Мы собираем техническую информацию (IP-адрес, тип браузера, действия на сайте) для улучшения работы сервиса. Продолжая использовать сайт, вы соглашаетесь с 
        <a href="polic.php" style="color: #667eea; text-decoration: none;">политикой конфиденциальности</a>.
    </p>
    
    <div style="display: flex; gap: 15px;">
        <button onclick="acceptCookies()" style="flex: 2; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 600; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102,126,234,0.3)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <i class="fas fa-check-circle"></i>
            Принять
        </button>
        <button onclick="declineCookies()" style="flex: 1; background: #f8f9fa; color: #666; border: 1px solid #ddd; padding: 14px; border-radius: 12px; font-weight: 600; font-size: 15px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;"
                onmouseover="this.style.background='#e9ecef';"
                onmouseout="this.style.background='#f8f9fa';">
            <i class="fas fa-times"></i>
            Отклонить
        </button>
    </div>
    
    <p style="color: #999; font-size: 11px; margin-top: 15px; text-align: center;">
        <i class="fas fa-info-circle"></i> 
        Вы можете изменить настройки в любое время
    </p>
</div>

<script>
function acceptCookies() {
    // Отправляем данные на сервер
    fetch('api.php?action=save_cookie_consent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'consent=accepted&ip=' + getIP()
    })
    .then(r => r.json())
    .then(data => {
        console.log('✅ Cookie consent saved:', data);
        document.getElementById('cookie-consent-modal').style.display = 'none';
    })
    .catch(error => {
        console.error('❌ Error:', error);
        document.getElementById('cookie-consent-modal').style.display = 'none';
    });
}

function declineCookies() {
    fetch('api.php?action=save_cookie_consent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'consent=declined&ip=' + getIP()
    })
    .then(r => r.json())
    .then(data => {
        console.log('✅ Cookie consent declined:', data);
        document.getElementById('cookie-consent-modal').style.display = 'none';
    })
    .catch(error => {
        console.error('❌ Error:', error);
        document.getElementById('cookie-consent-modal').style.display = 'none';
    });
}

function getIP() {
    // Получаем IP через сторонний сервис (запасной вариант)
    fetch('https://api.ipify.org?format=json')
        .then(r => r.json())
        .then(data => {
            console.log('🌐 IP адрес:', data.ip);
            return data.ip;
        })
        .catch(e => {
            console.log('❌ Не удалось получить IP');
            return 'unknown';
        });
}
</script>
<?php endif; ?>
</body>
</html>