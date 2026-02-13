<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Политика конфиденциальности | Маркетплейс</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%231a1a1a%22/><text y=%22.9em%22 x=%225px%22 font-size=%2280%22 font-weight=%22900%22 font-family=%22Orbitron%22 fill=%22%23667eea%22>P</text></svg>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 20px rgba(102,126,234,0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            flex: 1;
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 28px;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-3px);
        }

        .privacy-container {
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            margin: 40px 0;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .privacy-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .privacy-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .privacy-header h1 {
            font-size: 48px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .privacy-header p {
            font-size: 18px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .privacy-header i {
            font-size: 80px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .last-updated {
            background: #f8f9fa;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .last-updated span {
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .last-updated strong {
            color: #667eea;
            background: #e9ecef;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .privacy-content {
            padding: 50px;
        }

        .section {
            margin-bottom: 40px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 40px;
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .section-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea15, #764ba215);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 24px;
        }

        .section h2 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        .section h3 {
            font-size: 20px;
            color: #444;
            margin: 20px 0 15px 0;
        }

        .section p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .section ul, .section ol {
            margin: 15px 0 15px 30px;
            color: #666;
            line-height: 1.8;
        }

        .section li {
            margin-bottom: 8px;
        }

        .section strong {
            color: #333;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border-left: 6px solid #667eea;
        }

        .info-box h4 {
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box p {
            margin-bottom: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin: 25px 0;
        }

        .card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card i {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .card h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .card p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
            margin: 25px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            color: #666;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 8px;
        }

        .badge-blue {
            background: #e3f2fd;
            color: #1565C0;
        }

        .badge-green {
            background: #e8f5e9;
            color: #2E7D32;
        }

        .badge-orange {
            background: #fff3e0;
            color: #e65100;
        }

        .footer {
            background: white;
            padding: 30px 0;
            margin-top: auto;
            border-top: 1px solid #e9ecef;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-copyright {
            color: #666;
            font-size: 14px;
        }

        .footer-links {
            display: flex;
            gap: 30px;
        }

        .footer-links a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #667eea;
        }

        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(102,126,234,0.3);
            transition: all 0.3s;
            border: none;
            z-index: 99;
        }

        .scroll-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }

        @media (max-width: 768px) {
            .privacy-header {
                padding: 40px 20px;
            }

            .privacy-header h1 {
                font-size: 32px;
            }

            .privacy-content {
                padding: 30px 20px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 24px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
        }

        @media print {
            .header, .footer, .back-btn, .scroll-top {
                display: none;
            }
            
            .privacy-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <i class="fas fa-store"></i>
                    Маркетплейс
                </a>
                <a href="javascript:history.back()" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Назад
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="privacy-container">
            <div class="privacy-header">
                <i class="fas fa-shield-alt"></i>
                <h1>Политика конфиденциальности</h1>
                <p>Мы заботимся о защите ваших персональных данных</p>
            </div>

            <div class="last-updated">
                <span>
                    <i class="fas fa-calendar-alt"></i>
                    Последнее обновление: 14 февраля 2026 г.
                </span>
                <strong>Версия 2.0</strong>
            </div>

            <div class="privacy-content">
                <!-- Оглавление -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <h2>Содержание</h2>
                    </div>
                    <div class="grid-2">
                        <div class="card" onclick="document.getElementById('section1').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer;">
                            <i class="fas fa-database"></i>
                            <h4>1. Общие положения</h4>
                            <p>Основные принципы обработки данных</p>
                        </div>
                        <div class="card" onclick="document.getElementById('section2').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer;">
                            <i class="fas fa-user-lock"></i>
                            <h4>2. Сбор информации</h4>
                            <p>Какие данные мы собираем</p>
                        </div>
                        <div class="card" onclick="document.getElementById('section3').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer;">
                            <i class="fas fa-cogs"></i>
                            <h4>3. Использование данных</h4>
                            <p>Как мы используем вашу информацию</p>
                        </div>
                        <div class="card" onclick="document.getElementById('section4').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer;">
                            <i class="fas fa-share-alt"></i>
                            <h4>4. Передача данных</h4>
                            <p>Кому мы можем передать данные</p>
                        </div>
                    </div>
                </div>

                <!-- Раздел 1 -->
                <div id="section1" class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <h2>1. Общие положения</h2>
                    </div>
                    
                    <p>Настоящая политика обработки персональных данных составлена в соответствии с требованиями Федерального закона от 27.07.2006. №152-ФЗ «О персональных данных» и определяет порядок обработки персональных данных и меры по обеспечению безопасности персональных данных, предпринимаемые Маркетплейс (далее – Оператор).</p>

                    <div class="info-box">
                        <h4><i class="fas fa-info-circle"></i> Важно знать</h4>
                        <p>1.1. Оператор ставит своей важнейшей целью и условием осуществления своей деятельности соблюдение прав и свобод человека и гражданина при обработке его персональных данных, в том числе защиты прав на неприкосновенность частной жизни, личную и семейную тайну.</p>
                        <p>1.2. Настоящая политика Оператора в отношении обработки персональных данных (далее – Политика) применяется ко всей информации, которую Оператор может получить о посетителях веб-сайта https://marketplace.ru.</p>
                    </div>

                    <h3>Основные понятия</h3>
                    <ul>
                        <li><strong>Персональные данные</strong> — любая информация, относящаяся к прямо или косвенно определенному или определяемому физическому лицу (субъекту персональных данных);</li>
                        <li><strong>Оператор</strong> — государственный орган, муниципальный орган, юридическое или физическое лицо, самостоятельно или совместно с другими лицами организующие и (или) осуществляющие обработку персональных данных;</li>
                        <li><strong>Обработка персональных данных</strong> — любое действие (операция) или совокупность действий (операций), совершаемых с использованием средств автоматизации или без использования таких средств с персональными данными;</li>
                        <li><strong>Конфиденциальность персональных данных</strong> — обязательное для соблюдения Оператором или иным получившим доступ к персональным данным лицом требование не допускать их распространения без согласия субъекта персональных данных или наличия иного законного основания.</li>
                    </ul>
                </div>

                <!-- Раздел 2 -->
                <div id="section2" class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <h2>2. Сбор информации</h2>
                    </div>

                    <p>Оператор может обрабатывать следующие персональные данные пользователя:</p>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Категория данных</th>
                                    <th>Состав данных</th>
                                    <th>Цель сбора</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge badge-blue">Идентификационные</span></td>
                                    <td>ФИО, дата рождения, пол</td>
                                    <td>Идентификация пользователя</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-green">Контактные</span></td>
                                    <td>Номер телефона, email, адрес</td>
                                    <td>Связь с пользователем, доставка</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-orange">Платёжные</span></td>
                                    <td>Данные банковских карт, платёжные аккаунты</td>
                                    <td>Оплата товаров и услуг</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-blue">Технические</span></td>
                                    <td>IP-адрес, cookies, данные браузера</td>
                                    <td>Обеспечение безопасности, аналитика</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="info-box" style="border-left-color: #4CAF50;">
                        <h4><i class="fas fa-check-circle" style="color: #4CAF50;"></i> Правовые основания</h4>
                        <p>Обработка персональных данных осуществляется на основании:</p>
                        <ul>
                            <li>Согласия пользователя на обработку персональных данных</li>
                            <li>Договора, заключаемого между оператором и пользователем</li>
                            <li>Федеральных законов и иных нормативно-правовых актов</li>
                        </ul>
                    </div>
                </div>

                <!-- Раздел 3 -->
                <div id="section3" class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h2>3. Использование данных</h2>
                    </div>

                    <p>Персональные данные пользователя используются Оператором в следующих целях:</p>

                    <div class="grid-2">
                        <div class="card">
                            <i class="fas fa-user-check"></i>
                            <h4>Идентификация</h4>
                            <p>Создание учетной записи, вход в личный кабинет</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-shopping-cart"></i>
                            <h4>Оформление заказов</h4>
                            <p>Обработка и доставка заказов, оплата</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-envelope"></i>
                            <h4>Обратная связь</h4>
                            <p>Уведомления о статусе заказа, ответы на вопросы</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-chart-line"></i>
                            <h4>Аналитика</h4>
                            <p>Улучшение качества услуг, анализ предпочтений</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Безопасность</h4>
                            <p>Предотвращение мошенничества, защита данных</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-gift"></i>
                            <h4>Маркетинг</h4>
                            <p>Персональные предложения, акции (с согласия)</p>
                        </div>
                    </div>

                    <div class="info-box" style="background: #e3f2fd; border-left-color: #2196F3;">
                        <h4><i class="fas fa-clock"></i> Сроки обработки</h4>
                        <p>Персональные данные обрабатываются до момента отзыва согласия пользователем или до прекращения действия договора. По истечении срока данные уничтожаются или обезличиваются.</p>
                    </div>
                </div>

                <!-- Раздел 4 -->
                <div id="section4" class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h2>4. Передача данных</h2>
                    </div>

                    <p>Оператор может передавать персональные данные третьим лицам только в следующих случаях:</p>

                    <ul>
                        <li><strong>С согласия пользователя</strong> — при наличии явно выраженного согласия</li>
                        <li><strong>Для выполнения заказа</strong> — службам доставки, платёжным системам</li>
                        <li><strong>По требованию закона</strong> — государственным органам в установленных законом случаях</li>
                        <li><strong>Для защиты прав</strong> — при защите прав и законных интересов</li>
                    </ul>

                    <h3>Третьи лица, получающие данные</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Получатель</th>
                                    <th>Цель передачи</th>
                                    <th>Состав данных</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Платёжные системы</td>
                                    <td>Обработка платежей</td>
                                    <td>Данные карт, сумма платежа</td>
                                </tr>
                                <tr>
                                    <td>Службы доставки</td>
                                    <td>Доставка товаров</td>
                                    <td>ФИО, адрес, телефон</td>
                                </tr>
                                <tr>
                                    <td>Партнёры по маркетингу</td>
                                    <td>Персонализация предложений</td>
                                    <td>История покупок, предпочтения*</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p style="font-size: 12px; color: #999; margin-top: 5px;">* Только с согласия пользователя</p>
                </div>

                <!-- Раздел 5 -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-cookie-bite"></i>
                        </div>
                        <h2>5. Использование cookie-файлов</h2>
                    </div>

                    <p>На сайте используются cookie-файлы и аналогичные технологии для:</p>

                    <div class="grid-2">
                        <div class="card">
                            <i class="fas fa-cookie"></i>
                            <h4>Технические cookies</h4>
                            <p>Обеспечение работы сайта, запоминание сессии</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-chart-bar"></i>
                            <h4>Аналитические cookies</h4>
                            <p>Сбор статистики, анализ посещаемости</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-ad"></i>
                            <h4>Рекламные cookies</h4>
                            <p>Показ персонализированной рекламы</p>
                        </div>
                        <div class="card">
                            <i class="fas fa-thumbs-up"></i>
                            <h4>Функциональные cookies</h4>
                            <p>Запоминание настроек пользователя</p>
                        </div>
                    </div>

                    <div class="info-box" style="background: #fff3e0; border-left-color: #ff9800;">
                        <h4><i class="fas fa-exclamation-triangle" style="color: #ff9800;"></i> Управление cookies</h4>
                        <p>Вы можете отключить cookie-файлы в настройках браузера. Однако это может повлиять на работу некоторых функций сайта.</p>
                    </div>
                </div>

                <!-- Раздел 6 -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h2>6. Права пользователей</h2>
                    </div>

                    <p>В соответствии с законодательством о защите персональных данных, вы имеете следующие права:</p>

                    <ol>
                        <li><strong>Право на доступ</strong> — получать информацию об обработке ваших данных</li>
                        <li><strong>Право на исправление</strong> — требовать исправления неточных данных</li>
                        <li><strong>Право на удаление</strong> — требовать удаления ваших данных ("право быть забытым")</li>
                        <li><strong>Право на ограничение обработки</strong> — в случаях, предусмотренных законом</li>
                        <li><strong>Право на переносимость</strong> — получать данные в машиночитаемом формате</li>
                        <li><strong>Право на отзыв согласия</strong> — в любое время отозвать согласие на обработку</li>
                    </ol>

                    <div class="info-box" style="background: #e8f5e9; border-left-color: #4CAF50;">
                        <h4><i class="fas fa-envelope"></i> Реализация прав</h4>
                        <p>Для реализации своих прав отправьте запрос на email: <strong>privacy@marketplace.ru</strong> или через форму обратной связи в личном кабинете. Мы ответим в течение 30 дней.</p>
                    </div>
                </div>

                <!-- Раздел 7 -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h2>7. Защита данных</h2>
                    </div>

                    <p>Для защиты персональных данных мы применяем следующие меры:</p>

                    <ul>
                        <li><strong>Организационные меры</strong> — разграничение доступа, обучение персонала</li>
                        <li><strong>Технические меры</strong> — шифрование, SSL-сертификаты, файерволы</li>
                        <li><strong>Физические меры</strong> — охрана помещений, контроль доступа</li>
                        <li><strong>Регулярный аудит</strong> — проверка систем безопасности</li>
                    </ul>

                    <div class="info-box" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <h4><i class="fas fa-check-circle" style="color: #4CAF50;"></i> Соответствие требованиям</h4>
                        <p>Наша система защиты данных соответствует требованиям 152-ФЗ и GDPR. Мы регулярно проходим независимый аудит безопасности.</p>
                    </div>
                </div>

                <!-- Раздел 8 -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h2>8. Дети</h2>
                    </div>

                    <p>Наш сайт не предназначен для лиц младше 16 лет. Мы не собираем намеренно персональные данные несовершеннолетних. Если нам станет известно, что мы получили данные ребенка без согласия родителей, мы примем меры для их удаления.</p>
                </div>

                <!-- Раздел 9 -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h2>9. Трансграничная передача</h2>
                    </div>

                    <p>При необходимости передачи данных за пределы РФ мы обеспечиваем:</p>
                    <ul>
                        <li>Наличие достаточного уровня защиты в стране получателя</li>
                        <li>Заключение соответствующих договоров</li>
                        <li>Получение согласия пользователя (если требуется)</li>
                    </ul>
                </div>

                <!-- Раздел 10 -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h2>10. Изменения политики</h2>
                    </div>

                    <p>Мы можем обновлять нашу политику конфиденциальности. О существенных изменениях мы будем уведомлять:</p>
                    <ul>
                        <li>Публикацией новой версии на сайте</li>
                        <li>Отправкой уведомления на email (при наличии согласия)</li>
                        <li>Информированием в личном кабинете</li>
                    </ul>
                    <p>Рекомендуем периодически проверять эту страницу на предмет изменений.</p>
                </div>

                <!-- Контакты -->
                <div class="section">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h2>Контактная информация</h2>
                    </div>

                    <div class="grid-2">
                        <div>
                            <h4><i class="fas fa-building"></i> Оператор</h4>
                            <p>ООО "Маркетплейс"<br>
                            ИНН: 1234567890<br>
                            ОГРН: 1234567890123<br>
                            Юридический адрес: г. Москва, ул. Примерная, д. 1</p>
                        </div>
                        <div>
                            <h4><i class="fas fa-envelope"></i> Для обращений</h4>
                            <p>Email: privacy@marketplace.ru<br>
                            Телефон: +7 (999) 123-45-67<br>
                            Ответственный за данные: Иванов И.И.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-copyright">
                    © 2026 Маркетплейс. Все права защищены.
                </div>
                <div class="footer-links">
                    <a href="terms.php">Пользовательское соглашение</a>
                    <a href="privacy.php">Политика конфиденциальности</a>
                    <a href="cookies.php">Использование cookie</a>
                </div>
            </div>
        </div>
    </footer>

    <button class="scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>
</body>
</html>