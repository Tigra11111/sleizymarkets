<?php
session_start();
if (isset($_SESSION['user']) && isset($_SESSION['forum_access']) && $_SESSION['forum_access'] === true) {
    header('Location: forum.php');
    exit;
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Доступ к форуму жалоб</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-box {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        <div class="logo">🚨</div>
        <h2 class="text-center mb-4">Доступ к форуму жалоб</h2>
        <p class="text-center text-muted mb-4">Для входа на форум жалоб требуется подтверждение личности</p>
        
        <div class="alert alert-danger" id="error-alert" style="display:none;"></div>
        
        <form id="forum-auth-form">
            <div class="mb-3">
                <label class="form-label">Логин</label>
                <input type="text" class="form-control" id="login" 
                       value="<?php echo htmlspecialchars($_SESSION['user']['login'] ?? ''); ?>" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Пароль *</label>
                <input type="password" class="form-control" id="password" required>
                <small class="text-muted">Введите пароль для подтверждения</small>
            </div>
            
            <button type="submit" class="btn btn-danger w-100">Войти в форум жалоб</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="res.php" class="text-decoration-none">← Вернуться на главную</a>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#forum-auth-form').submit(function(e) {
            e.preventDefault();
            
            const password = $('#password').val();
            
            $('#error-alert').hide();
            
            $.ajax({
                url: 'forum_api.php?action=forum_auth',
                type: 'POST',
                data: {
                    password: password
                },
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = 'forum.php';
                    } else {
                        $('#error-alert').text(response.message).show();
                    }
                },
                error: function() {
                    $('#error-alert').text('Ошибка соединения').show();
                }
            });
        });
    });
    </script>
</body>
</html>