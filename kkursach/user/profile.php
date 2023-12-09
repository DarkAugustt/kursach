<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    die(header('Location: ../auth/login.php'));
}

require('../database/database.php');

// Проверяем, был ли отправлен запрос на отмену бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $ticketIdToCancel = $_POST['cancel_booking'];

    // Выполняем обновление записи в базе данных
    $db->Update("UPDATE tickets SET user_id = NULL, status = 'Свободно' WHERE id = :ticket_id", ['ticket_id' => $ticketIdToCancel]);

    // После выполнения действия перенаправляем пользователя на эту же страницу
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id", ['id' => $_SESSION['telegram_id']]);

$firstName = $user[0]['first_name'];
$telegramID = $user[0]['telegram_id'];
$telegramUsername = $user[0]['telegram_username'];
$userID = $user[0]['id'];
$isAdmin = ($user[0]['is_admin'] == 1);

$tickets = $db->Select("
    SELECT tickets.*, 
           schedule.departure_date, 
           TIME_FORMAT(schedule.departure_time, '%H:%i') AS formatted_departure_time, 
           schedule.arrival_date, 
           TIME_FORMAT(schedule.arrival_time, '%H:%i') AS formatted_arrival_time, 
           routes.departure_station, 
           routes.arrival_station, 
           autos.auto_name,
           schedule.status AS schedule_status
    FROM tickets
    JOIN schedule ON tickets.schedule_id = schedule.id
    JOIN routes ON schedule.route_id = routes.id
    JOIN autos ON schedule.auto_id = autos.id
    WHERE tickets.user_id = :user_id", ['user_id' => $telegramID]);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php">АврораТур</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Главная</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Профиль</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../order/tickets.php">Забронировать</a>
            </li>
            <?php if ($isAdmin) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-4 bg-dark text-light">
    <div class="jumbotron bg-dark text-light">
        <h1 class="display-5"><strong><?php echo $firstName ?></strong>, это ваш профиль</h1>
        <p class="lead">Здесь вы можете посмотреть историю бронирований билетов</p>
        <p>
            <a href="../order/tickets.php" class="btn btn-primary">Забронировать</a>
            <a href="../auth/logic/logout.php" class="btn btn-danger">Выйти из аккаунта</a>
        </p>
    </div>
</div>

<div class="container bg-dark text-light">
    <h2>История</h2>
    <?php if (!empty($tickets)) : ?>
        <table class="table bg-dark text-light">
            <thead>
            <tr>
                <th scope="col">Откуда</th>
                <th scope="col">Куда</th>
                <th scope="col">Название поезда</th>
                <th scope="col">Место</th>
                <th scope="col">Статус</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $ticket) : ?>
                <tr>
                    <td class="multiline-cell"><?php echo $ticket['departure_station']." в ".$ticket['formatted_departure_time']."<br>".date('d.m.Y', strtotime($ticket['departure_date'])); ?></td>
                    <td class="multiline-cell"><?php echo $ticket['arrival_station']." в ".$ticket['formatted_arrival_time']."<br>".date('d.m.Y', strtotime($ticket['arrival_date'])); ?></td>
                    <td><?php echo $ticket['auto_name']; ?></td>
                    <td><?php echo $ticket['seat_number']; ?></td>
                    <td><?php echo $ticket['schedule_status']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="cancel_booking" value="<?php echo $ticket['id']; ?>">
                            <button type="submit" class="btn btn-danger">Отменить бронирование</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="lead"><strong>В базе данных не содержится данных о ваших бронированиях</strong></p>
    <?php endif; ?>
</div>


</body>
</html>
