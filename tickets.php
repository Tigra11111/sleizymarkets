<?php
session_start();

// ЕСЛИ ПОЛЬЗОВАТЕЛЬ ЗАБАНЕН - ПРОВЕРЯЕМ ДОСТУП
if (isset($_SESSION['user']) && ($_SESSION['user']['status'] ?? 'active') === 'banned') {
    // Забаненный может зайти ТОЛЬКО если:
    // 1. Пришел с параметром ?appeal=1 (кликнул кнопку в res.php)
    // 2. И еще не создал апелляцию
    
    $canAccess = false;
    
    // Проверяем параметр appeal
    if (isset($_GET['appeal']) && $_GET['appeal'] == '1') {
        // Устанавливаем флаг что это доступ для апелляции
        $_SESSION['appeal_mode'] = true;
        $canAccess = true;
    }
    
    // Если пытается зайти без appeal=1 - сразу в бан
    if (!$canAccess) {
        // JavaScript + PHP редирект
        echo '<script>window.location.href = "res.php";</script>';
        header('Location: res.php');
        exit();
    }
}

// НЕ создаем тестовых пользователей!
// Просто продолжаем

$user_id = $_SESSION['user']['id'] ?? 0;
$user_status = $_SESSION['user']['status'] ?? 'active';
$isBanned = ($user_status === 'banned');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои тикеты - Маркетплейс</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f5f5f7; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Баннер для забаненных */
        .ban-banner {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            border-left: 5px solid #ffc107;
        }
        .ban-banner h3 { margin-bottom: 10px; font-size: 20px; }
        .ban-banner p { margin-bottom: 10px; opacity: 0.9; }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky; top: 0; z-index: 1000;
        }
        .header-content {
            display: flex; justify-content: space-between; align-items: center;
        }
        .logo { font-size: 24px; font-weight: bold; color: white; text-decoration: none; }
        .nav { display: flex; gap: 20px; align-items: center; }
        .nav a {
            color: white; text-decoration: none; padding: 8px 15px; border-radius: 20px;
            transition: background 0.3s;
        }
        .nav a:hover, .nav a.active { background: rgba(255,255,255,0.2); }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .balance {
            background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px;
            font-weight: bold;
        }
        .btn {
            padding: 8px 20px; border: none; border-radius: 25px; cursor: pointer;
            font-weight: 600; transition: all 0.3s;
        }
        .btn-primary { background: #4CAF50; color: white; }
        .btn-primary:hover { background: #45a049; transform: translateY(-2px); }
        
        .tickets-page { margin-top: 30px; min-height: 70vh; }
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px; flex-wrap: wrap; gap: 15px;
        }
        .page-title { font-size: 32px; color: #333; margin: 0; }
        .create-ticket-btn {
            background: #4CAF50; color: white; border: none; padding: 12px 25px;
            border-radius: 25px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 8px; font-size: 16px;
            transition: all 0.3s;
        }
        .create-ticket-btn:hover {
            background: #45a049; transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        .create-ticket-btn:disabled {
            background: #6c757d; cursor: not-allowed; transform: none;
        }
        
        .tickets-list { display: flex; flex-direction: column; gap: 15px; }
        .ticket-card {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s;
            border-left: 5px solid #667eea;
        }
        .ticket-card:hover {
            transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-left-color: #764ba2;
        }
        .ticket-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 15px; flex-wrap: wrap; gap: 10px;
        }
        .ticket-title {
            font-size: 20px; font-weight: 600; color: #333; margin: 0; flex: 1;
        }
        .ticket-meta {
            display: flex; align-items: center; gap: 15px; color: #666;
            font-size: 14px; flex-wrap: wrap;
        }
        .ticket-id {
            background: #667eea; color: white; padding: 4px 12px;
            border-radius: 20px; font-weight: bold; font-size: 12px;
        }
        .ticket-preview {
            color: #666; line-height: 1.6; margin-bottom: 20px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .ticket-footer {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 15px; border-top: 1px solid #eee;
            flex-wrap: wrap; gap: 10px;
        }
        .ticket-status {
            padding: 6px 15px; border-radius: 20px; font-size: 14px; font-weight: 600;
        }
        .status-open { background: #d4edda; color: #155724; }
        .status-in_progress { background: #cce5ff; color: #004085; }
        .status-waiting { background: #fff3cd; color: #856404; }
        .status-closed { background: #f8d7da; color: #721c24; }
        
        .ticket-actions { display: flex; gap: 10px; }
        .action-btn {
            padding: 8px 16px; border-radius: 8px; border: none;
            font-weight: 600; font-size: 14px; cursor: pointer;
            transition: all 0.3s; display: flex; align-items: center; gap: 5px;
        }
        .btn-view { background: #667eea; color: white; }
        .btn-view:hover { background: #5a6fd8; transform: translateY(-2px); }
        .btn-close { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn-close:hover { background: #f5c6cb; }
        
        .empty-tickets {
            text-align: center; padding: 60px 20px; background: white;
            border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .empty-icon { font-size: 60px; color: #ddd; margin-bottom: 20px; }
        .empty-title { color: #666; margin-bottom: 10px; font-size: 24px; }
        .empty-text { color: #999; margin-bottom: 30px; max-width: 400px; margin: 0 auto 30px; }
        
        .modal {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5);
            z-index: 2000; justify-content: center; align-items: center;
        }
        .modal-content {
            background: white; padding: 30px; border-radius: 15px;
            max-width: 800px; width: 90%; max-height: 90vh;
            overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;
        }
        .modal-title { font-size: 22px; color: #333; margin: 0; }
        .close-modal {
            background: none; border: none; font-size: 24px; cursor: pointer;
            color: #666; width: 30px; height: 30px; display: flex;
            align-items: center; justify-content: center; border-radius: 50%;
        }
        .close-modal:hover { background: #f5f5f5; }
        
        .messages-container {
            max-height: 400px; overflow-y: auto; margin: 20px 0;
            padding: 15px; background: #f8f9fa; border-radius: 10px;
        }
        .message {
            margin-bottom: 15px; padding: 12px 15px; border-radius: 10px;
            max-width: 80%;
        }
        .message-user {
            background: #667eea; color: white; margin-left: auto;
            border-bottom-right-radius: 3px;
        }
        .message-admin {
            background: #4CAF50; color: white; border-bottom-left-radius: 3px;
        }
        .message-other {
            background: white; border: 1px solid #e0e0e0;
            border-bottom-left-radius: 3px;
        }
        .message-header {
            display: flex; justify-content: space-between; margin-bottom: 5px;
            font-size: 14px;
        }
        .message-author { font-weight: bold; }
        .message-user .message-author { color: rgba(255,255,255,0.9); }
        .message-time { color: rgba(255,255,255,0.8); font-size: 12px; }
        .message-other .message-time { color: #666; }
        .message-content { line-height: 1.5; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0;
            border-radius: 10px; font-size: 16px; transition: border 0.3s;
        }
        .form-control:focus { outline: none; border-color: #667eea; }
        textarea.form-control { min-height: 120px; resize: vertical; }
        
        .loader {
            border: 5px solid #f3f3f3; border-top: 5px solid #667eea;
            border-radius: 50%; width: 50px; height: 50px;
            animation: spin 1s linear infinite; margin: 30px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        @media (max-width: 768px) {
            .header-content { flex-direction: column; gap: 15px; }
            .nav { flex-wrap: wrap; justify-content: center; }
            .page-header { flex-direction: column; text-align: center; }
            .message { max-width: 90%; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <?php if ($isBanned): ?>
                    <span class="logo"><i class="fas fa-store"></i> Маркетплейс <small style="font-size:12px;color:#ffc107;">(РЕЖИМ АПЕЛЛЯЦИИ)</small></span>
                <?php else: ?>
                    <a href="res.php" class="logo"><i class="fas fa-store"></i> Маркетплейс</a>
                <?php endif; ?>
                <div class="nav">
                    <?php if ($isBanned): ?>
                        <a href="#" style="opacity:0.5;cursor:not-allowed;"><i class="fas fa-home"></i> Главная</a>
                    <?php else: ?>
                        <a href="res.php"><i class="fas fa-home"></i> Главная</a>
                    <?php endif; ?>
                    <a href="#" class="active"><i class="fas fa-ticket-alt"></i> Мои тикеты</a>
                </div>
                <div class="user-info">
                    <?php if(isset($_SESSION['user'])): ?>
                        <div class="balance">
                            <i class="fas fa-wallet"></i> 
                            <span><?php echo number_format($_SESSION['user']['balance'] ?? 0, 2); ?></span> ₽
                            <?php if($isBanned): ?><small style="color:#ffc107;margin-left:5px;">(ЗАБАНЕН)</small><?php endif; ?>
                        </div>
                        <?php if($isBanned): ?>
                            <button class="btn btn-primary" onclick="returnToBan()">
                                <i class="fas fa-arrow-left"></i> Вернуться
                            </button>
                        <?php else: ?>
                            <button class="btn btn-primary" onclick="location.href='res.php'">
                                <i class="fas fa-arrow-left"></i> Назад
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Основной контент -->
    <main class="container">
        <div class="tickets-page">
            <!-- Баннер для забаненных -->
            <?php if($isBanned): ?>
                <div class="ban-banner">
                    <h3><i class="fas fa-ban"></i> РЕЖИМ АПЕЛЛЯЦИИ</h3>
                    <p>Ваш аккаунт заблокирован. Вы можете создать ТОЛЬКО ОДНУ апелляцию.</p>
                    <p>После отправки апелляции вы будете автоматически возвращены на страницу блокировки.</p>
                    <p><strong>Другие функции сайта недоступны в режиме блокировки.</strong></p>
                </div>
            <?php endif; ?>
            
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-ticket-alt"></i> Мои тикеты</h1>
                <button class="create-ticket-btn" onclick="showCreateModal()" id="create-ticket-btn">
                    <i class="fas fa-plus"></i> <?php echo $isBanned ? 'Создать апелляцию' : 'Создать тикет'; ?>
                </button>
            </div>

            <!-- Список тикетов -->
            <div id="tickets-list" class="tickets-list">
                <div class="loader"></div>
            </div>
        </div>
    </main>

    <!-- Модальное окно создания тикета -->
    <div id="create-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?php echo $isBanned ? 'ПОДАТЬ АПЕЛЛЯЦИЮ' : 'Создать тикет'; ?></h3>
                <button class="close-modal" onclick="closeModal('create-modal')">&times;</button>
            </div>
            <form id="create-form">
                <div class="form-group">
                    <label class="form-label">Тема *</label>
                    <input type="text" class="form-control" name="subject" required 
                           placeholder="<?php echo $isBanned ? 'Причина апелляции (например: "Несправедливая блокировка")' : 'Краткое описание проблемы'; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Сообщение *</label>
                    <textarea class="form-control" name="message" rows="5" required 
                              placeholder="<?php echo $isBanned ? 'Подробно опишите, почему вы считаете блокировку несправедливой. Укажите все детали и доказательства.' : 'Подробное описание проблемы...'; ?>"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-paper-plane"></i> <?php echo $isBanned ? 'ОТПРАВИТЬ АПЕЛЛЯЦИЮ' : 'Создать тикет'; ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Модальное окно просмотра тикета -->
    <div id="view-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="view-title"></h3>
                <button class="close-modal" onclick="closeModal('view-modal')">&times;</button>
            </div>
            
            <div id="ticket-info" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                <!-- Информация о тикете -->
            </div>
            
            <h4 style="margin-bottom: 10px;">Сообщения</h4>
            <div id="messages-container" class="messages-container">
                <!-- Сообщения будут здесь -->
            </div>
            
            <form id="reply-form" style="margin-top: 20px;">
                <input type="hidden" id="reply-ticket-id">
                <div class="form-group">
                    <textarea class="form-control" id="reply-message" rows="3" 
                              placeholder="Введите ваш ответ..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    <i class="fas fa-reply"></i> Отправить ответ
                </button>
            </form>
        </div>
    </div>

    <script>
        let currentUserId = <?php echo $user_id; ?>;
        let userStatus = '<?php echo $user_status; ?>';
        let isBanned = <?php echo $isBanned ? 'true' : 'false'; ?>;
        
        $(document).ready(function() {
            loadTickets();
            
            // Для забаненных: сразу проверяем есть ли уже апелляция
            if (isBanned) {
                checkExistingAppeal();
            }
            
            $('#create-form').submit(function(e) {
                e.preventDefault();
                createTicket();
            });
            
            $('#reply-form').submit(function(e) {
                e.preventDefault();
                replyToTicket();
            });
            
            // Таймер для забаненных: через 10 минут автоматический возврат
            if (isBanned) {
                setTimeout(function() {
                    if (confirm('Время для подачи апелляции истекло. Вернуться на страницу блокировки?')) {
                        returnToBan();
                    }
                }, 600000); // 10 минут
            }
        });

        function returnToBan() {
            // Устанавливаем флаг что пользователь ушел
            $.ajax({
                url: 'api_tickets.php?action=end_appeal_session',
                method: 'GET'
            });
            
            window.location.href = 'res.php';
        }

        function checkExistingAppeal() {
            $.ajax({
                url: 'api_tickets.php?action=get_my_tickets',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.tickets) {
                        // Ищем апелляцию (тикет начинающийся с [АПЕЛЛЯЦИЯ])
                        let hasAppeal = false;
                        response.tickets.forEach(ticket => {
                            if (ticket.subject && ticket.subject.startsWith('[АПЕЛЛЯЦИЯ]')) {
                                hasAppeal = true;
                            }
                        });
                        
                        if (hasAppeal) {
                            // Уже есть апелляция - блокируем кнопку
                            $('#create-ticket-btn').prop('disabled', true)
                                .html('<i class="fas fa-ban"></i> Апелляция уже подана')
                                .css('background', '#6c757d');
                            
                            // Показываем сообщение
                            $('#tickets-list').before(`
                                <div class="ban-banner" style="background: #ffc107; color: #333;">
                                    <h3><i class="fas fa-info-circle"></i> АПЕЛЛЯЦИЯ УЖЕ ПОДАНА</h3>
                                    <p>Вы уже создали апелляцию. Ожидайте ответа администрации.</p>
                                    <p>Через 10 секунд вы будете возвращены на страницу блокировки...</p>
                                </div>
                            `);
                            
                            // Через 10 секунд возвращаем в бан
                            setTimeout(function() {
                                returnToBan();
                            }, 10000);
                        }
                    }
                }
            });
        }

        function loadTickets() {
            $.ajax({
                url: 'api_tickets.php?action=get_my_tickets',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#tickets-list').html('<div class="loader"></div>');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.tickets && response.tickets.length > 0) {
                            renderTickets(response.tickets);
                        } else {
                            showEmptyTickets();
                        }
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showError('Ошибка загрузки тикетов: ' + error);
                }
            });
        }

        function renderTickets(tickets) {
            let html = '';
            
            tickets.forEach(ticket => {
                let statusClass = 'status-open';
                let statusText = 'Открыт';
                
                if (ticket.status) {
                    statusClass = 'status-' + ticket.status;
                    switch(ticket.status) {
                        case 'in_progress': statusText = 'В работе'; break;
                        case 'waiting': statusText = 'Ожидание'; break;
                        case 'closed': statusText = 'Закрыт'; break;
                    }
                }
                
                let dateText = 'Нет даты';
                if (ticket.created_at) {
                    const date = new Date(ticket.created_at);
                    dateText = date.toLocaleDateString('ru-RU');
                }
                
                // Проверяем, является ли тикет апелляцией
                const isAppeal = ticket.subject && ticket.subject.startsWith('[АПЕЛЛЯЦИЯ]');
                const appealBadge = isAppeal ? '<span class="ticket-id" style="background:#dc3545; margin-right:5px;">АПЕЛЛЯЦИЯ</span>' : '';
                
                html += `
                    <div class="ticket-card" data-id="${ticket.id}">
                        <div class="ticket-header">
                            <h3 class="ticket-title">${ticket.subject || 'Без темы'}</h3>
                            <div class="ticket-meta">
                                ${appealBadge}
                                <span class="ticket-id">#${ticket.id}</span>
                                <span><i class="fas fa-calendar"></i> ${dateText}</span>
                            </div>
                        </div>
                        
                        <p class="ticket-preview">
                            ${ticket.message_count || 0} сообщений
                        </p>
                        
                        <div class="ticket-footer">
                            <div class="ticket-status ${statusClass}">${statusText}</div>
                            <div class="ticket-actions">
                                <button class="action-btn btn-view" onclick="viewTicket(${ticket.id})">
                                    <i class="fas fa-eye"></i> Просмотр
                                </button>
                                ${!isBanned && ticket.status !== 'closed' ? `
                                <button class="action-btn btn-close" onclick="closeTicket(${ticket.id})">
                                    <i class="fas fa-times"></i> Закрыть
                                </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#tickets-list').html(html);
        }

        function showEmptyTickets() {
            $('#tickets-list').html(`
                <div class="empty-tickets">
                    <i class="fas fa-ticket-alt empty-icon"></i>
                    <h3 class="empty-title">Тикетов пока нет</h3>
                    <p class="empty-text">${isBanned ? 'Создайте апелляцию для разблокировки аккаунта' : 'Создайте свой первый тикет для обращения в поддержку'}</p>
                    <button class="create-ticket-btn" onclick="showCreateModal()" id="empty-create-btn">
                        <i class="fas fa-plus"></i> ${isBanned ? 'Создать апелляцию' : 'Создать тикет'}
                    </button>
                </div>
            `);
        }

        function showError(message) {
            $('#tickets-list').html(`
                <div class="empty-tickets">
                    <i class="fas fa-exclamation-triangle empty-icon"></i>
                    <h3 class="empty-title">Ошибка</h3>
                    <p class="empty-text">${message}</p>
                    <button class="btn" onclick="loadTickets()" style="background: #667eea; color: white;">
                        <i class="fas fa-sync-alt"></i> Обновить
                    </button>
                </div>
            `);
        }

        function showCreateModal() {
            // Для забаненных: предупреждение
            if (isBanned) {
                if (!confirm('Вы создаёте апелляцию на блокировку аккаунта. Вы можете создать только ОДНУ апелляцию. Продолжить?')) {
                    return;
                }
            }
            
            $('#create-form')[0].reset();
            showModal('create-modal');
        }

       function createTicket() {
    const subject = prompt('Тема:');
    const message = prompt('Сообщение:');
    
    if (!subject || !message) return;
    
    fetch('api.php?action=create_ticket', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'subject=' + encodeURIComponent(subject) + '&message=' + encodeURIComponent(message)
    })
    .then(r => r.json())
    .then(d => console.log('✅', d))
    .catch(e => console.error('❌', e));
}

function addTicketMessage() {
    const ticketId = $('#current-ticket-id').val();
    const message = $('#ticket-message-input').val().trim();
    
    if (!message) {
        showToast('Введите сообщение', 'warning');
        return;
    }
    
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
                $('#ticket-message-input').val('');
                loadTicketMessages(ticketId);
            } else {
                showToast('❌ ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('❌ Ошибка соединения', 'error');
        }
    });
}

function showTicketMessages(ticketId, subject) {
    console.log('📋 Загрузка сообщений тикета #' + ticketId);
    
    if (!ticketId) {
        showToast('ID тикета не указан', 'error');
        return;
    }
    
    // Показываем модалку с загрузкой
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
            console.log('✅ Ответ:', response);
            
            if (response.status === 'success') {
                // Сохраняем ID текущего тикета
                $('#current-ticket-id').val(ticketId);
                
                // Формируем HTML сообщений
                let messagesHtml = '';
                
                if (response.messages && response.messages.length > 0) {
                    response.messages.forEach(msg => {
                        let isMe = (msg.user_id == currentUser?.id);
                        let isAdmin = msg.is_admin || msg.role === 'admin';
                        let time = msg.created_at ? new Date(msg.created_at).toLocaleString() : '';
                        
                        let avatarColor = isAdmin ? '#9c27b0' : (isMe ? '#4CAF50' : '#667eea');
                        let avatarIcon = isAdmin ? 'fa-crown' : 'fa-user';
                        let senderName = isAdmin ? 'Администратор' : (isMe ? 'Вы' : (msg.login || 'Пользователь'));
                        
                        messagesHtml += `
                            <div style="margin-bottom: 20px; display: flex; ${isMe ? 'justify-content: flex-end' : 'justify-content: flex-start'}">
                                <div style="max-width: 80%; min-width: 200px;">
                                    <div style="display: flex; align-items: flex-start; gap: 12px; ${isMe ? 'flex-direction: row-reverse' : ''}">
                                        <div style="width: 40px; height: 40px; background: ${avatarColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="fas ${avatarIcon}"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-size: 12px; color: #666; margin-bottom: 5px; text-align: ${isMe ? 'right' : 'left'}">
                                                ${senderName} · ${time}
                                            </div>
                                            <div style="background: ${isMe ? '#e3f2fd' : 'white'}; color: #333; padding: 12px 16px; border-radius: 12px; border: 1px solid #e0e0e0;">
                                                ${msg.message}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    messagesHtml = '<p style="text-align: center; color: #666; padding: 40px;">Нет сообщений</p>';
                }
                
                // Показываем модалку с сообщениями
                Swal.fire({
                    title: 'Тикет #' + ticketId + ': ' + (subject || 'Без темы'),
                    html: `
                        <div style="max-height: 400px; overflow-y: auto; padding: 10px; text-align: left;">
                            ${messagesHtml}
                        </div>
                        <div style="margin-top: 20px;">
                            <textarea id="ticket-reply-message" class="swal2-textarea" placeholder="Введите сообщение..." style="width: 100%;"></textarea>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: '📨 Отправить',
                    cancelButtonText: '❌ Закрыть',
                    confirmButtonColor: '#4CAF50',
                    width: '700px',
                    didOpen: () => {
                        // Прокрутка вниз
                        setTimeout(() => {
                            const container = document.querySelector('.swal2-html-container div');
                            if (container) container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reply = $('#ticket-reply-message').val().trim();
                        if (reply) {
                            sendTicketReply(ticketId, reply);
                        }
                    }
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '❌ Ошибка',
                    text: response.message || 'Не удалось загрузить сообщения'
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

function sendTicketReply(ticketId, message) {
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
                showToast('✅ Сообщение отправлено', 'success');
                // Перезагружаем сообщения
                showTicketMessages(ticketId);
            } else {
                showToast('❌ ' + response.message, 'error');
            }
        },
        error: function() {
            showToast('❌ Ошибка отправки', 'error');
        }
    });
}

        function viewTicket(ticketId, subject) {
    console.log('📋 Открываем тикет #' + ticketId);
    
    if (!ticketId) {
        alert('ID тикета не указан');
        return;
    }
    
    fetch('api.php?action=get_ticket_messages&ticket_id=' + ticketId)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                let messages = data.messages || [];
                let html = '<div style="text-align: left; padding: 10px; max-height: 400px; overflow-y: auto;">';
                
                messages.forEach(msg => {
                    let isMe = msg.user_id === 4;
                    let time = new Date(msg.created_at).toLocaleString();
                    
                    html += `
                        <div style="margin-bottom: 15px; ${isMe ? 'text-align: right;' : 'text-align: left;'}">
                            <div style="background: ${isMe ? '#e3f2fd' : '#f5f5f5'}; 
                                        padding: 12px; 
                                        border-radius: 12px;
                                        display: inline-block;
                                        max-width: 80%;
                                        border: 1px solid ${isMe ? '#bbdefb' : '#e0e0e0'};">
                                <strong style="color: ${isMe ? '#1565c0' : '#333'};">${isMe ? 'Вы' : (msg.login || 'Пользователь')}</strong>
                                <p style="margin: 5px 0 0 0;">${msg.message}</p>
                                <div style="font-size: 11px; color: #666; margin-top: 5px;">
                                    ${time}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                
                // Добавляем поле для ответа
                html += `
                    <div style="margin-top: 20px;">
                        <textarea id="reply-message-${ticketId}" class="swal2-textarea" 
                                  placeholder="Введите сообщение..." 
                                  style="width: 100%; min-height: 80px;"></textarea>
                    </div>
                `;
                
                Swal.fire({
                    title: 'Тикет #' + ticketId + ': ' + (subject || 'Без темы'),
                    html: html,
                    width: '700px',
                    showCancelButton: true,
                    confirmButtonText: '📨 Отправить',
                    cancelButtonText: '❌ Закрыть',
                    confirmButtonColor: '#4CAF50',
                    didOpen: () => {
                        // Прокрутка вниз
                        setTimeout(() => {
                            let container = document.querySelector('.swal2-html-container div');
                            if (container) container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let reply = $('#reply-message-' + ticketId).val().trim();
                        if (reply) {
                            sendTicketReply(ticketId, reply);
                        }
                    }
                });
                
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка',
                    text: data.message || 'Не удалось загрузить тикет'
                });
            }
        })
        .catch(error => {
            console.error('❌ Ошибка:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Ошибка соединения с сервером'
            });
        });
}

function sendTicketReply(ticketId, message) {
    Swal.fire({
        title: '⏳ Отправка...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch('api.php?action=add_ticket_message', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'ticket_id=' + ticketId + '&message=' + encodeURIComponent(message)
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '✅ Отправлено!',
                timer: 1500,
                showConfirmButton: false
            });
            // Обновляем тикет через 1.5 секунды
            setTimeout(() => viewTicket(ticketId), 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ Ошибка',
                text: data.message
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: '❌ Ошибка',
            text: 'Ошибка соединения'
        });
    });
}

        function showViewModal(ticketId, data) {
            $('#view-title').text(data.ticket?.subject || 'Тикет #' + ticketId);
            
            const status = data.ticket?.status || 'open';
            const statusText = status === 'open' ? 'Открыт' : 
                              status === 'in_progress' ? 'В работе' :
                              status === 'closed' ? 'Закрыт' : status;
            const statusClass = 'status-' + status;
            
            let dateText = '';
            if (data.ticket?.created_at) {
                const date = new Date(data.ticket.created_at);
                dateText = date.toLocaleString('ru-RU');
            }
            
            $('#ticket-info').html(`
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <strong>Статус:</strong> 
                        <span class="ticket-status ${statusClass}" style="margin-left: 8px;">${statusText}</span>
                    </div>
                    <div>
                        <strong>ID:</strong> #${ticketId}
                    </div>
                    <div>
                        <strong>Создан:</strong> ${dateText}
                    </div>
                </div>
            `);
            
            let messagesHtml = '';
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    const isAdmin = msg.role === 'admin';
                    const isCurrentUser = msg.user_id == currentUserId;
                    const messageClass = isAdmin ? 'message-admin' : 
                                        isCurrentUser ? 'message-user' : 'message-other';
                    
                    let author = msg.login || 'Пользователь';
                    if (isAdmin) author = '👑 Администратор';
                    if (isCurrentUser) author = 'Вы';
                    
                    let time = '';
                    if (msg.created_at) {
                        const msgDate = new Date(msg.created_at);
                        time = msgDate.toLocaleString('ru-RU');
                    }
                    
                    messagesHtml += `
                        <div class="message ${messageClass}">
                            <div class="message-header">
                                <span class="message-author">${author}</span>
                                <span class="message-time">${time}</span>
                            </div>
                            <div class="message-content">
                                ${msg.message || ''}
                            </div>
                        </div>
                    `;
                });
            } else {
                messagesHtml = `
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-comment-slash" style="font-size: 48px; margin-bottom: 15px; color: #ddd;"></i>
                        <p>Сообщений пока нет</p>
                    </div>
                `;
            }
            
            $('#messages-container').html(messagesHtml);
            $('#reply-ticket-id').val(ticketId);
            
            if (status === 'closed' || isBanned) {
                $('#reply-form').hide();
                if (status === 'closed') {
                    $('#reply-form').before(`
                        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: center;">
                            <i class="fas fa-lock"></i> Тикет закрыт. Дальнейшие сообщения не принимаются.
                        </div>
                    `);
                }
                if (isBanned) {
                    $('#reply-form').before(`
                        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: center;">
                            <i class="fas fa-ban"></i> Забаненные пользователи не могут отвечать в тикетах.
                        </div>
                    `);
                }
            } else {
                $('#reply-form').show();
            }
            
            showModal('view-modal');
            
            setTimeout(() => {
                $('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
            }, 100);
        }

        function replyToTicket() {
            const ticketId = $('#reply-ticket-id').val();
            const message = $('#reply-message').val().trim();
            
            if (!message) {
                alert('Введите сообщение');
                return;
            }
            
            $.ajax({
                url: 'api_tickets.php?action=add_ticket_message',
                method: 'POST',
                data: { ticket_id: ticketId, message: message },
                dataType: 'json',
                beforeSend: function() {
                    $('#reply-form button').prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i> Отправка...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#reply-message').val('');
                        viewTicket(ticketId);
                    } else {
                        alert('❌ ' + response.message);
                    }
                },
                error: function() {
                    alert('❌ Ошибка сети');
                },
                complete: function() {
                    $('#reply-form button').prop('disabled', false)
                        .html('<i class="fas fa-reply"></i> Отправить ответ');
                }
            });
        }

        function closeTicket(ticketId) {
            if (!confirm('Закрыть тикет #' + ticketId + '?')) return;
            
            $.ajax({
                url: 'api_tickets.php?action=close_ticket',
                method: 'POST',
                data: { ticket_id: ticketId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Тикет закрыт');
                        loadTickets();
                        closeModal('view-modal');
                    } else {
                        alert('Ошибка: ' + response.message);
                    }
                },
                error: function() {
                    alert('Ошибка сети');
                }
            });
        }

        function showModal(modalId) {
            $('#' + modalId).fadeIn();
        }

        function closeModal(modalId) {
            $('#' + modalId).fadeOut();
        }

        $(document).on('click', function(e) {
            if ($(e.target).hasClass('modal')) {
                $(e.target).fadeOut();
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.modal').fadeOut();
            }
        });
    </script>
</body>
</html>