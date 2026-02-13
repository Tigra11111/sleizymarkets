<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Проверяем доступ к форуму
if (!isset($_SESSION['forum_access']) || $_SESSION['forum_access'] !== true) {
    header('Location: forum_auth.php');
    exit;
}

$userRole = $_SESSION['user']['role'] ?? 'user';
$userId = $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форум жалоб</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .complaint-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
        .user-suggestion:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Шапка -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🚨 Форум жалоб</h1>
            <div>
                <a href="res.php" class="btn btn-outline-secondary">← На главную</a>
                <button class="btn btn-danger" onclick="logoutForum()">Выйти из форума</button>
            </div>
        </div>
        
        <?php if ($userRole === 'admin'): ?>
        <!-- Админ панель -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Админ-панель жалоб</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3" id="admin-stats">
                    <!-- Статистика -->
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" onclick="loadComplaints('pending')">Ожидающие</button>
                    <button class="btn btn-warning" onclick="loadComplaints('reviewing')">В работе</button>
                    <button class="btn btn-success" onclick="loadComplaints('resolved')">Решенные</button>
                    <button class="btn btn-secondary" onclick="loadComplaints('rejected')">Отклоненные</button>
                    <button class="btn btn-info" onclick="loadAllComplaints()">Все жалобы</button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Основной контент -->
        <div class="row">
            <!-- Левая колонка -->
            <div class="col-md-4">
                <!-- Форма создания жалобы -->
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">📝 Подать жалобу</h5>
                    </div>
                    <div class="card-body">
                        <form id="complaint-form">
                            <div class="mb-3">
                                <label class="form-label">Тема жалобы *</label>
                                <input type="text" class="form-control" id="title" 
                                       placeholder="Кратко опишите проблему" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">На кого жалуетесь (необязательно)</label>
                                <input type="text" class="form-control" id="search-accused" 
                                       placeholder="Введите логин... (можно пропустить)">
                                <input type="hidden" id="accused_id">
                                <div id="user-suggestions" class="mt-2" style="display:none;"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Причина *</label>
                                <select class="form-select" id="reason" required>
                                    <option value="scam">Мошенничество</option>
                                    <option value="not_sent">Не отправил товар</option>
                                    <option value="bad_quality">Некачественный товар</option>
                                    <option value="fake">Поддельный товар</option>
                                    <option value="insults">Оскорбления</option>
                                    <option value="other">Другое</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Описание ситуации *</label>
                                <textarea class="form-control" id="description" rows="4" 
                                          placeholder="Подробно опишите ситуацию..." required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Доказательства (ссылки на скриншоты)</label>
                                <textarea class="form-control" id="evidence" rows="2" 
                                          placeholder="https://imgur.com/... (можно несколько ссылок через запятую)"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-danger w-100">Отправить жалобу</button>
                        </form>
                    </div>
                </div>
                
                <!-- Мои жалобы -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">📋 Мои жалобы</h5>
                    </div>
                    <div class="card-body">
                        <div id="my-complaints">
                            <div class="text-center">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Загрузка...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Правая колонка -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📢 Активные жалобы</h5>
                    </div>
                    <div class="card-body">
                        <div id="complaints-list">
                            <div class="text-center">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Загрузка...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Модальное окно Bootstrap -->
    <div class="modal fade" id="complaintModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Жалоба #<span id="modal-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-body"></div>
                <div class="modal-footer" id="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const userRole = '<?php echo $userRole; ?>';
        const userId = <?php echo $userId; ?>;
        
        // ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==========
        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            Toast.fire({
                icon: type,
                title: message
            });
        }

        function showLoading(message = 'Загрузка...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        // ========== ИНИЦИАЛИЗАЦИЯ ==========
        $(document).ready(function() {
            loadMyComplaints();
            loadAllComplaints();
            if (userRole === 'admin') {
                loadStats();
            }
            
            // Поиск пользователей
            $('#search-accused').on('input', function() {
                const query = $(this).val();
                if (query.length > 1) {
                    searchUsers(query);
                } else {
                    $('#user-suggestions').hide();
                    $('#accused_id').val('');
                }
            });
            
            // Отправка жалобы
            $('#complaint-form').submit(function(e) {
                e.preventDefault();
                
                const title = $('#title').val();
                const accused_id = $('#accused_id').val();
                const reason = $('#reason').val();
                const description = $('#description').val();
                
                if (!title || !description) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Внимание',
                        text: 'Заполните тему и описание!',
                        confirmButtonColor: '#ff9800'
                    });
                    return;
                }
                
                showLoading('Отправка жалобы...');
                
                $.ajax({
                    url: 'forum_api.php?action=create_complaint',
                    type: 'POST',
                    data: {
                        title: title,
                        accused_id: accused_id || 0,
                        reason: reason,
                        description: description,
                        evidence: $('#evidence').val()
                    },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '✅ Жалоба отправлена!',
                                html: `Номер жалобы: <strong>#${response.complaint_id}</strong>`,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            $('#complaint-form')[0].reset();
                            $('#accused_id').val('');
                            loadMyComplaints();
                            loadAllComplaints();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '❌ Ошибка',
                                text: response.message || 'Не удалось отправить жалобу',
                                confirmButtonColor: '#f44336'
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: 'Ошибка соединения с сервером',
                            confirmButtonColor: '#f44336'
                        });
                    }
                });
            });
        });

        // ========== ПОИСК ПОЛЬЗОВАТЕЛЕЙ ==========
        function searchUsers(query) {
            $.ajax({
                url: 'forum_api.php?action=search_users&query=' + encodeURIComponent(query),
                success: function(response) {
                    if (response.status === 'success') {
                        let html = '<div class="list-group">';
                        if (response.users && response.users.length > 0) {
                            response.users.forEach(user => {
                                html += `
                                    <a href="#" class="list-group-item list-group-item-action user-suggestion" 
                                       onclick="selectUser(${user.id}, '${user.login}')">
                                        ${user.login} (${user.username})
                                    </a>
                                `;
                            });
                        } else {
                            html += '<div class="list-group-item">Пользователь не найден</div>';
                        }
                        html += '</div>';
                        $('#user-suggestions').html(html).show();
                    }
                },
                error: function() {
                    showToast('❌ Ошибка поиска пользователей', 'error');
                }
            });
        }

        function selectUser(id, login) {
            $('#accused_id').val(id);
            $('#search-accused').val(login);
            $('#user-suggestions').hide();
        }

        // ========== ЗАГРУЗКА ЖАЛОБ ==========
        function loadMyComplaints() {
            $.ajax({
                url: 'forum_api.php?action=get_complaints&limit=20',
                success: function(response) {
                    if (response.status === 'success') {
                        let html = '';
                        if (response.complaints && response.complaints.length > 0) {
                            response.complaints.forEach(complaint => {
                                const statusColors = {
                                    'pending': 'warning',
                                    'reviewing': 'info',
                                    'resolved': 'success',
                                    'rejected': 'danger'
                                };
                                
                                html += `
                                    <div class="mb-2 p-2 border rounded complaint-item" 
                                         onclick="viewComplaint(${complaint.id})" style="cursor:pointer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>${complaint.title || 'Без темы'}</strong>
                                                <br>
                                                <small class="text-muted">${complaint.accused_login || 'Общая жалоба'}</small>
                                            </div>
                                            <span class="badge bg-${statusColors[complaint.status] || 'secondary'}">
                                                ${complaint.status}
                                            </span>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = '<p class="text-muted">У вас нет жалоб</p>';
                        }
                        $('#my-complaints').html(html);
                    } else {
                        $('#my-complaints').html(`<p class="text-danger">Ошибка: ${response.message}</p>`);
                        showToast('❌ ' + response.message, 'error');
                    }
                },
                error: function() {
                    $('#my-complaints').html('<p class="text-danger">Ошибка соединения с сервером</p>');
                    showToast('❌ Ошибка соединения', 'error');
                }
            });
        }

        function loadAllComplaints() {
            $.ajax({
                url: 'forum_api.php?action=get_complaints&limit=50',
                success: function(response) {
                    if (response.status === 'success') {
                        displayComplaints(response.complaints || []);
                    } else {
                        $('#complaints-list').html(`<p class="text-danger">Ошибка: ${response.message}</p>`);
                        showToast('❌ ' + response.message, 'error');
                    }
                },
                error: function() {
                    $('#complaints-list').html('<p class="text-danger">Ошибка соединения с сервером</p>');
                    showToast('❌ Ошибка соединения', 'error');
                }
            });
        }

        function loadComplaints(status) {
            $.ajax({
                url: 'forum_api.php?action=get_complaints&status=' + status + '&limit=50',
                success: function(response) {
                    if (response.status === 'success') {
                        displayComplaints(response.complaints || []);
                    } else {
                        $('#complaints-list').html(`<p class="text-danger">Ошибка: ${response.message}</p>`);
                        showToast('❌ ' + response.message, 'error');
                    }
                },
                error: function() {
                    $('#complaints-list').html('<p class="text-danger">Ошибка соединения с сервером</p>');
                    showToast('❌ Ошибка соединения', 'error');
                }
            });
        }

        // ========== ОТОБРАЖЕНИЕ ЖАЛОБ ==========
        function displayComplaints(complaints) {
            let html = '';
            
            if (complaints.length === 0) {
                html = '<p class="text-center text-muted">Жалоб нет</p>';
            } else {
                complaints.forEach(complaint => {
                    const date = new Date(complaint.created_at).toLocaleDateString('ru-RU');
                    
                    html += `
                        <div class="complaint-item border rounded p-3 mb-3" 
                             onclick="viewComplaint(${complaint.id})" style="cursor:pointer">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${complaint.title || 'Без темы'}</strong>
                                    <br>
                                    <small class="text-muted">
                                        От: ${complaint.complainant_login || 'Пользователь'} 
                                        ${complaint.accused_login ? '→ На: ' + complaint.accused_login : ''}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-${complaint.status === 'pending' ? 'danger' : 'secondary'}">
                                        ${complaint.status}
                                    </span>
                                    <br>
                                    <small class="text-muted">${date}</small>
                                </div>
                            </div>
                            <p class="mt-2 mb-1">${(complaint.description || '').substring(0, 100)}...</p>
                        </div>
                    `;
                });
            }
            
            $('#complaints-list').html(html);
        }

        // ========== ПРОСМОТР ЖАЛОБЫ ==========
        function viewComplaint(id) {
            showLoading('Загрузка жалобы...');
            
            $.ajax({
                url: `forum_api.php?action=get_complaint&id=${id}`,
                success: function(response) {
                    Swal.close();
                    
                    if (response.status === 'success') {
                        const complaint = response.complaint;
                        
                        $('#modal-id').text(complaint.id);
                        
                        let body = `
                            <div class="complaint-header mb-4">
                                <h4 class="mb-3">${complaint.title || 'Без темы'}</h4>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="bg-light p-2 rounded">
                                            <small class="text-muted">От кого:</small><br>
                                            <strong>${complaint.complainant_login || 'Пользователь'}</strong>
                                        </div>
                                    </div>
                                    ${complaint.accused_login ? `
                                    <div class="col-md-6">
                                        <div class="bg-light p-2 rounded">
                                            <small class="text-muted">На кого:</small><br>
                                            <strong>${complaint.accused_login}</strong>
                                        </div>
                                    </div>
                                    ` : ''}
                                    <div class="col-md-6">
                                        <div class="bg-light p-2 rounded">
                                            <small class="text-muted">Статус:</small><br>
                                            <span class="badge bg-${complaint.status === 'pending' ? 'danger' : complaint.status === 'reviewing' ? 'warning' : complaint.status === 'resolved' ? 'success' : 'secondary'}">
                                                ${complaint.status === 'pending' ? 'Ожидает рассмотрения' : 
                                                  complaint.status === 'reviewing' ? 'В работе' : 
                                                  complaint.status === 'resolved' ? 'Решено' : 'Отклонено'}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-light p-2 rounded">
                                            <small class="text-muted">Дата создания:</small><br>
                                            <strong>${new Date(complaint.created_at).toLocaleString('ru-RU')}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="complaint-section mb-4">
                                <h6 class="border-bottom pb-2 mb-3">📌 Причина жалобы</h6>
                                <div class="alert alert-warning p-3">
                                    ${getReasonText(complaint.reason)}
                                </div>
                            </div>
                            
                            <div class="complaint-section mb-4">
                                <h6 class="border-bottom pb-2 mb-3">📝 Описание ситуации</h6>
                                <div class="complaint-description p-3 bg-light rounded" style="min-height: 150px; max-height: 300px; overflow-y: auto;">
                                    ${formatDescription(complaint.description || '')}
                                </div>
                            </div>
                        `;
                        
                        if (complaint.evidence) {
                            body += `
                                <div class="complaint-section mb-4">
                                    <h6 class="border-bottom pb-2 mb-3">📎 Доказательства</h6>
                                    <div class="complaint-evidence p-3 bg-light rounded" style="min-height: 100px; max-height: 200px; overflow-y: auto;">
                                        ${formatEvidence(complaint.evidence)}
                                    </div>
                                </div>
                            `;
                        }
                        
                        if (complaint.admin_comment) {
                            body += `
                                <div class="complaint-section mb-4">
                                    <h6 class="border-bottom pb-2 mb-3">💬 Комментарий администратора</h6>
                                    <div class="admin-comment p-3 bg-info bg-opacity-10 border border-info rounded" 
                                         style="min-height: 200px; max-height: 400px; overflow-y: auto; font-size: 1.1rem; line-height: 1.8;">
                                        ${formatAdminComment(complaint.admin_comment)}
                                    </div>
                                </div>
                            `;
                        }
                        
                        $('#modal-body').html(body);
                        
                        $('#complaintModal .modal-dialog')
                            .addClass('modal-xl')
                            .css({
                                'max-height': '90vh',
                                'margin-top': '5vh'
                            });
                        
                        if (userRole === 'admin' && complaint.status === 'pending') {
                            $('#modal-footer').html(`
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                      <button class="btn btn-warning" onclick="updateStatus(${complaint.id}, 'reviewing')">
    <i class="fas fa-tasks"></i> Взять в работу
</button>
<button class="btn btn-success ms-2" onclick="updateStatus(${complaint.id}, 'resolved')">
    <i class="fas fa-check"></i> Решено
</button>
<button class="btn btn-danger ms-2" onclick="updateStatus(${complaint.id}, 'rejected')">
    <i class="fas fa-times"></i> Отклонить
</button>
                            `);
                        } else {
                            $('#modal-footer').html(`
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Закрыть
                                </button>
                            `);
                        }
                        
                        $('#complaintModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: response.message || 'Не удалось загрузить жалобу',
                            confirmButtonColor: '#f44336'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Ошибка',
                        text: 'Ошибка соединения с сервером',
                        confirmButtonColor: '#f44336'
                    });
                }
            });
        }

       function updateStatus(complaintId, status) {
    // Сначала закроем Bootstrap модалку, чтобы она не мешала
    $('#complaintModal').modal('hide');
    
    // Немного подождем, пока модалка закроется
    setTimeout(() => {
        Swal.fire({
            title: 'Введите комментарий',
            input: 'textarea',
            inputPlaceholder: 'Опишите решение или причину...',
            inputAttributes: {
                'required': 'required',
                'autofocus': 'true'
            },
            showCancelButton: true,
            confirmButtonText: '✅ Подтвердить',
            cancelButtonText: '❌ Отмена',
            confirmButtonColor: '#4CAF50',
            cancelButtonColor: '#6c757d',
            allowOutsideClick: false,
            didOpen: () => {
                // Фокус на поле ввода
                setTimeout(() => {
                    const input = document.querySelector('.swal2-textarea');
                    if (input) input.focus();
                }, 100);
            },
            preConfirm: (inputValue) => {
                if (!inputValue || inputValue.trim() === '') {
                    Swal.showValidationMessage('Комментарий не может быть пустым!');
                    return false;
                }
                return inputValue;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const comment = result.value.trim();
                
                if (comment === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка',
                        text: 'Комментарий не может быть пустым!',
                        confirmButtonColor: '#f44336'
                    });
                    return;
                }
                
                showLoading('Обновление статуса...');
                
                $.ajax({
                    url: 'forum_api.php?action=update_complaint_status',
                    type: 'POST',
                    data: {
                        complaint_id: complaintId,
                        status: status,
                        comment: comment
                    },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '✅ Статус обновлен!',
                                text: 'Жалоба обработана',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadMyComplaints();
                            loadAllComplaints();
                            if (userRole === 'admin') {
                                loadStats();
                            }
                            showToast('✅ Статус обновлен', 'success');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '❌ Ошибка',
                                text: response.message || 'Не удалось обновить статус',
                                confirmButtonColor: '#f44336'
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Ошибка',
                            text: 'Ошибка соединения с сервером',
                            confirmButtonColor: '#f44336'
                        });
                    }
                });
            }
        });
    }, 300);
}

        // ========== ЗАГРУЗКА СТАТИСТИКИ ==========
        function loadStats() {
            $.ajax({
                url: 'forum_api.php?action=get_stats',
                success: function(response) {
                    if (response.status === 'success') {
                        const stats = response.stats;
                        $('#admin-stats').html(`
                            <div class="col-3 text-center">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5>${stats.pending || 0}</h5>
                                        <small>Ожидают</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5>${stats.reviewing || 0}</h5>
                                        <small>В работе</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5>${stats.resolved || 0}</h5>
                                        <small>Решены</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body">
                                        <h5>${stats.rejected || 0}</h5>
                                        <small>Отклонены</small>
                                    </div>
                                </div>
                            </div>
                        `);
                        showToast('📊 Статистика обновлена', 'info');
                    }
                },
                error: function() {
                    showToast('❌ Ошибка загрузки статистики', 'error');
                }
            });
        }

        // ========== ВЫХОД ИЗ ФОРУМА ==========
        function logoutForum() {
            Swal.fire({
                title: 'Выход из форума',
                text: 'Вы уверены, что хотите выйти?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '✅ Да, выйти',
                cancelButtonText: '❌ Отмена',
                confirmButtonColor: '#f44336',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Выход...');
                    
                    $.ajax({
                        url: 'forum_api.php?action=forum_logout',
                        success: function() {
                            Swal.close();
                            showToast('👋 До свидания!', 'success');
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 1000);
                        },
                        error: function() {
                            Swal.close();
                            window.location.href = 'res.php';
                        }
                    });
                }
            });
        }

        // ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ФОРМАТИРОВАНИЯ ==========
        function getReasonText(reason) {
            const reasons = {
                'scam': 'Мошенничество',
                'not_sent': 'Не отправил товар',
                'bad_quality': 'Некачественный товар',
                'fake': 'Поддельный товар',
                'insults': 'Оскорбления',
                'other': 'Другое'
            };
            return reasons[reason] || reason;
        }

        function formatDescription(text) {
            return text.replace(/\n/g, '<br>');
        }

        function formatEvidence(text) {
            const links = text.split(/[,\n]+/).filter(link => link.trim());
            let html = '';
            
            links.forEach((link) => {
                const cleanLink = link.trim();
                if (cleanLink.startsWith('http')) {
                    html += `<div class="mb-1">
                        <a href="${cleanLink}" target="_blank" class="text-decoration-none">
                            <i class="fas fa-external-link-alt"></i> ${cleanLink}
                        </a>
                    </div>`;
                } else {
                    html += `<div class="mb-1">${cleanLink}</div>`;
                }
            });
            
            return html || text;
        }

        function formatAdminComment(text) {
            return text.replace(/\n/g, '<br>');
        }
    </script>
</body>
</html>