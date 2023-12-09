<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}
ini_set('error_log', 'C:\wamp64\www\kkursach\error.log');

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

    // Convert time format
    $departureTime = date('Y-m-d H:i:s', strtotime($departureTime));
    $arrivalTime = date('Y-m-d H:i:s', strtotime($arrivalTime));

    // Insert flight information
    $db->Insert("INSERT INTO `schedule` (`auto_id`, `route_id`, `departure_time`, `arrival_time`, `is_daily`) VALUES (:auto_id, :route_id, :departure_time, :arrival_time, 1)", [
        'auto_id' => $selectedAutoId,
        'route_id' => $selectedRouteId,
        'departure_time' => $departureTime,
        'arrival_time' => $arrivalTime
    ]);

    // Get the ID of the new schedule
    $scheduleId = $db->LastInsertId();

    // Get the number of seats in the train
    $autoInfo = $db->Select("SELECT `seats` FROM `autos` WHERE `id` = :auto_id", ['auto_id' => $selectedAutoId]);
    $numSeats = $autoInfo[0]['seats'];

    // Add tickets for two months ahead
    $startDate = new DateTime(); // Current date and time
    $endDate = clone $startDate;
    $endDate->modify('+2 months'); // Add two months

// Initialize $startDate before the loop
    while ($startDate <= $endDate) {
        // Add tickets for each seat in the train
        for ($seatNumber = 1; $seatNumber <= $numSeats; $seatNumber++) {
            $query = $db->prepare("INSERT INTO `tickets` (`schedule_id`, `seat_number`, `status`, `date`) VALUES (:schedule_id, :seat_number, 'Свободно', :date)");
            $query->bindParam(':schedule_id', $scheduleId);
            $query->bindParam(':seat_number', $seatNumber);
            $query->bindParam(':date', $startDate->format('Y-m-d H:i:s'));

            // Execute the query
            $insertResult = $query->execute();

// Check for errors in the query
            if (!$insertResult) {
                $errorInfo = $query->errorInfo();
                error_log('Ticket insertion error: ' . $errorInfo[2]);
                echo 'Ticket insertion failed: ' . $errorInfo[2] . '<br>';
                var_dump($errorInfo);
            } else {
                echo 'Ticket inserted successfully<br>';
            }


            $query->closeCursor();


        }


        // Increment the current date by one day
        $startDate->modify('+1 day');
    }

    // Redirect after insertion
    header('Location: daily_routes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление ежедневного рейса</title>
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
            <label for="auto_id">Выберите автобус:</label>
            <select class="form-control" id="auto_id" name="auto_id" required>
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
            <input type="time" class="form-control" id="departure_time" name="departure_time" required>
        </div>
        <div class="form-group">
            <label for="arrival_time">Время прибытия:</label>
            <input type="time" class="form-control" id="arrival_time" name="arrival_time" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add_schedule">Добавить ежедневный рейс</button>
    </form>
</div>
</body>

</html>
