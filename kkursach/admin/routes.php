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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    $selectedAutoId = $_POST['auto_id'];
    $selectedRouteId = $_POST['route_id'];
    $departureTime = $_POST['departure_time'];
    $arrivalTime = $_POST['arrival_time'];

    // Преобразовать формат времени
    $departureTime = date('Y-m-d H:i:s', strtotime($_POST['departure_time']));
    $arrivalTime = date('Y-m-d H:i:s', strtotime($_POST['arrival_time']));

    // Значение статуса, которое вам нужно (измените на необходимое)
    $status = 'Запланировано';

    // Вставка информации о рейсе
    $db->Insert("INSERT INTO `schedule` (`auto_id`, `route_id`, `departure_time`, `arrival_time`, `status`, `departure_date`, `arrival_date`) VALUES (:auto_id, :route_id, :departure_time, :arrival_time, :status, :departure_date, :arrival_date)", [
        'auto_id' => $selectedAutoId,
        'route_id' => $selectedRouteId,
        'departure_time' => $departureTime,
        'arrival_time' => $arrivalTime,
        'status' => $status,
        'departure_date' => $departureTime,
        'arrival_date' => $arrivalTime
    ]);

    // Получение ID вставленной записи
    $scheduleId = $db->LastInsertId();

    // Добавление билетов
    $autoInfo = $db->Select("SELECT * FROM `autos` WHERE `id` = :auto_id", ['auto_id' => $selectedAutoId]);
    $seatsCount = $autoInfo[0]['seats'];

    for ($i = 1; $i <= $seatsCount; $i++) {
        $db->Insert("INSERT INTO `tickets` (`schedule_id`, `user_id`, `seat_number`, `status`, `date`) VALUES (:schedule_id, NULL, :seat_number, 'Свободно', :departure_time)", [
            'schedule_id' => $scheduleId,
            'seat_number' => $i,
            'departure_time' => $departureTime
        ]);
    }

    // Редирект после добавления
    header('Location: routes.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление рейса</title>
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

<!-- Navbar -->
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

<div class="container bg-dark text-light">
    <form method="post" action="">
        <div class="form-group">
            <label for="train_id">Выберите автобус:</label>
            <select class="form-control" id="train_id" name="train_id" required>
                <?php foreach ($autos as $auto) : ?>
                    <option value="<?php echo $auto['id']; ?>"><?php echo $auto['auto_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="route_id">Выберите маршрут:</label>
            <select class="form-control" id="route_id" name="route_id" required>
                <?php foreach ($routes as $route) : ?>
                    <option value="<?php echo $route['id']; ?>"><?php echo $route['departure_station'] . ' - ' . $route['arrival_station']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="departure_time">Время отправления:</label>
            <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required step="60">
        </div>
        <div class="form-group">
            <label for="arrival_time">Время прибытия:</label>
            <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" required step="60">
        </div>
        <button type="submit" class="btn btn-primary" name="add_schedule">Добавить рейс</button>
    </form>
</div>
</body>

</html>
