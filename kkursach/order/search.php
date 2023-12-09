<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Билеты</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body class="bg-dark text-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php">АврораТур</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            <li class="nav-item active">
                <a class="nav-link" href="#">Билеты</a>
            </li>
        </ul>
    </div>
</nav>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', 1);
ini_set('error_log', 'C:/wamp64/www/kkursach/error.log');
header("X-Content-Type-Options: nosniff");
header("Cache-Control: max-age=3600, public");

$route_id = $_POST['route_id'] ?? '';
$date = $_POST['date'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = @mysqli_connect("localhost", "root", "", "zzd") or die ('Cannot connect to server');
    mysqli_select_db($conn, "zzd") or die('Cannot open database');
    mysqli_set_charset($conn, 'utf8');

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    if (!empty($route_id) && !empty($date)) {
        $sql = "SELECT 
            schedule.id AS schedule_id, 
            schedule.*, 
            routes.departure_station, 
            routes.arrival_station, 
            autos.auto_name, 
            (SELECT COUNT(*) FROM tickets WHERE schedule.id = tickets.schedule_id AND tickets.status = 'Свободно' AND tickets.date = '$date') AS num_tickets
        FROM 
            schedule
        JOIN 
            autos ON schedule.auto_id = autos.id
        JOIN 
            routes ON schedule.route_id = routes.id
        WHERE 
            schedule.route_id = '$route_id'
            AND (schedule.status = 'Запланировано' AND DATE(schedule.departure_date) = '$date')";

        $result = $conn->query($sql);

        if ($result === false) {
            echo "Error in SQL query: " . $conn->error . "<br>";
        } else {
            echo "<div class='container mt-5 bg-dark text-light'>";
            echo "<h2>Расписание автобусов на " . date('d.m.Y', strtotime($date)) . "</h2>";


            if ($result->num_rows > 0) {
                echo "<table class='table bg-dark text-light'>
                <thead>
                    <tr>
                        <th scope='col'>Название автобуса</th>
                        <th scope='col'>Откуда</th>
                        <th scope='col'>Куда</th>
                        <th scope='col'>Свободных мест</th>
                        <th scope='col'>Забронировать</th>
                    </tr>
                </thead>
                <tbody>";

                while ($row = $result->fetch_assoc()) {
                    $auto_id = $row['auto_id'];
                    $auto_name = $row['auto_name'];
                    $departure_time = date('H:i', strtotime($row['departure_time']));
                    $arrival_time = date('H:i', strtotime($row['arrival_time']));
                    $departure_date = date('d.m.Y', strtotime($row['departure_date']));
                    $arrival_date = date('d.m.Y', strtotime($row['arrival_date']));
                    $departure_station = $row['departure_station'];
                    $arrival_station = $row['arrival_station'];
                    $num_tickets = isset($row['num_tickets']) ? $row['num_tickets'] : 0;
                    $schedule_id = $row['schedule_id'];

                    echo "<tr>
    <th scope='row'>$auto_name</th>
    <th scope='row'>$departure_station в $departure_time <br> $departure_date</th>
    <th scope='row'>$arrival_station в $arrival_time <br> $arrival_date</th>
    <th scope='row'>$num_tickets</th>
    <th scope='row'>
        <form action='order.php' method='get'>
            <input type='hidden' name='schedule_id' value='$schedule_id'>
            <input type='hidden' name='date' value='$date'>
            <input type='hidden' name='auto_id' value='$auto_id'>
            <button type='submit' class='btn btn-primary'>Забронировать</button>
        </form>
    </th>
</tr>";


                }

                echo "</tbody></table></div>";
            } else {
                echo "<p>Рейсы не найдены.</p>";
            }
        }
    } else {
        echo "<div class='container'>";
        echo "<p>Не все данные были предоставлены.</p>";
        echo "</div>";
    }
    $conn->close();
} else {
    echo "<div class='container'>";
    echo "<p>Данные не были отправлены методом POST.</p>";
    echo "</div>";
}
?>
