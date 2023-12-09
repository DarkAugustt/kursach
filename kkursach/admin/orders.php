<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

require('../database/database.php');

$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id", ['id' => $_SESSION['telegram_id']]);

if ($user[0]['is_admin'] != 1) {
    header('Location: ../user/profile.php');
    exit();
}

$tickets = $db->Select("
    SELECT tickets.*, 
           schedule.departure_date, 
           TIME_FORMAT(schedule.departure_time, '%H:%i') AS formatted_departure_time, 
           schedule.arrival_date, 
           TIME_FORMAT(schedule.arrival_time, '%H:%i') AS formatted_arrival_time, 
           routes.departure_station, 
           routes.arrival_station, 
           autos.auto_name
    FROM tickets
    JOIN schedule ON tickets.schedule_id = schedule.id
    JOIN routes ON schedule.route_id = routes.id
    JOIN autos ON schedule.auto_id = autos.id
    WHERE tickets.status = 'Занято'
");

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php">ZZD</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../user/profile.php">Профиль</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../order/tickets.php">Забронировать</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-3">
    <p><a href="panel.php">Вернуться</a></p>
</div>

<div class="container bg-dark text-light">
    <h2>Все бронирования</h2>
    <?php if (!empty($tickets)) : ?>
        <table class="table bg-dark text-light">
            <thead>
            <tr>
                <th scope="col">Билет ID</th>
                <th scope="col">Откуда</th>
                <th scope="col">Куда</th>
                <th scope="col">Название поезда</th>
                <th scope="col">Место</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $ticket) : ?>
                <tr>
                    <td><?php echo $ticket['id']; ?></td>
                    <td class="multiline-cell"><?php echo $ticket['departure_station']." в ".$ticket['formatted_departure_time']."<br>".date('d.m.Y', strtotime($ticket['departure_date'])); ?></td>
                    <td class="multiline-cell"><?php echo $ticket['arrival_station']." в ".$ticket['formatted_arrival_time']."<br>".date('d.m.Y', strtotime($ticket['arrival_date'])); ?></td>
                    <td><?php echo $ticket['auto_name']; ?></td>
                    <td><?php echo $ticket['seat_number']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>У вас пока нет записей.</p>
    <?php endif; ?>
</div>

</body>

</html>
