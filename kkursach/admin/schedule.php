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

$autos = $db->Select("SELECT * FROM `autos`");
$routes = $db->Select("SELECT * FROM `routes`");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_schedule'])) {
    $scheduleId = $_POST['schedule_id'];

    // Получение текущего статуса рейса
    $currentStatus = $db->Select("SELECT `status` FROM `schedule` WHERE `id` = :schedule_id", ['schedule_id' => $scheduleId]);

    // Переключение статуса
    $newStatus = ($currentStatus[0]['status'] == 'Отменён') ? 'Запланирован' : 'Отменён';

    // Обновление статуса рейса
    $db->Update("UPDATE `schedule` SET `status` = :new_status WHERE `id` = :schedule_id", ['new_status' => $newStatus, 'schedule_id' => $scheduleId]);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка, была ли нажата кнопка "Отправлен"
    if (isset($_POST['toggle_departure'])) {
        $scheduleId = $_POST['schedule_id'];

        // Получение текущего статуса рейса
        $currentStatus = $db->Select("SELECT `status` FROM `schedule` WHERE `id` = :schedule_id", ['schedule_id' => $scheduleId]);

        // Переключение статуса
        $newStatus = ($currentStatus[0]['status'] == 'Отправлен') ? 'Запланирован' : 'Отправлен';

        // Обновление статуса рейса
        $db->Update("UPDATE `schedule` SET `status` = :new_status WHERE `id` = :schedule_id", ['new_status' => $newStatus, 'schedule_id' => $scheduleId]);
    }
}
// Получение всех рейсов
$schedules = $db->Select("SELECT s.*, t.auto_name, r.departure_station, r.arrival_station 
                          FROM `schedule` s
                          JOIN `autos` t ON s.auto_id = t.id
                          JOIN `routes` r ON s.route_id = r.id");
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание рейсов</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .modal-header .close {
            color: white;
        }
    </style>
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
            <li class="nav-item">
                <a class="nav-link" href="../user/profile.php">Профиль</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../order/search.php">Расписание</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-3">
    <p><a href="panel.php">Вернуться</a></p>
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-6">
            <h2>Существующие рейсы</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="routes.php" class="btn btn-primary">Добавить рейс</a>
        </div>
    </div>
</div>

<div class="container bg-dark text-light">
    <table class="table table-bordered table-dark">
        <thead>
        <tr>
            <th>ID</th>
            <th>Поезд</th>
            <th>Маршрут</th>
            <th>Дата</th>
            <th>Время отправления</th>
            <th>Время прибытия</th>
            <th>Статус</th>
            <th>Восстановить / Отменить</th>
            <th>Отправить / Отменить отправку</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($schedules as $schedule) : ?>
            <tr>
                <td><?php echo $schedule['id']; ?></td>
                <td><?php echo $schedule['auto_name']; ?></td>
                <td><?php echo $schedule['departure_station'] . ' - ' . $schedule['arrival_station']; ?></td>
                <td><?php echo $schedule['departure_date']; ?></td>
                <td><?php echo $schedule['departure_time']; ?></td>
                <td><?php echo $schedule['arrival_time']; ?></td>
                <td><?php echo $schedule['status']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="schedule_id" value="<?php echo $schedule['id']; ?>">

                        <button type="submit" class="btn <?php echo ($schedule['status'] == 'Отменён') ? 'btn-success' : 'btn-danger'; ?>" name="toggle_schedule">
                            <?php echo ($schedule['status'] == 'Отменён') ? 'Восстановить' : 'Отменить'; ?>
                        </button>
                    </form>
                </td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="schedule_id" value="<?php echo $schedule['id']; ?>">

                        <button type="submit" class="btn btn-primary" name="toggle_departure">
                            <?php echo ($schedule['status'] == 'Отправлен') ? 'Отменить отправку' : 'Отправить'; ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>

</html>
