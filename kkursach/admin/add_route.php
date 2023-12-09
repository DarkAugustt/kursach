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

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_route'])) {
    $departureStation = $_POST['departure_station'];
    $arrivalStation = $_POST['arrival_station'];

    // Валидация и обработка данных, если необходимо

    // Вставка нового маршрута в базу данных
    $db->Insert("INSERT INTO `routes` (`departure_station`, `arrival_station`) VALUES (:departure_station, :arrival_station)", [
        'departure_station' => $departureStation,
        'arrival_station' => $arrivalStation
    ]);

    // Редирект после добавления
    header('Location: add_route.php'); // Измените на нужный путь
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_route'])) {
    $routeID = $_POST['route_id']; // Corrected from 'id' to 'route_id'

    $db->Insert("DELETE FROM `routes` WHERE `id` = :id", ['id' => $routeID]);

    header('Location: add_route.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_route'])) {
    $routeID = $_POST['route_id'];
    $editDepartureStation = $_POST['departure_station'];
    $editArrivalStation = $_POST['arrival_station'];

    // Update the route in the database
    $db->Update("UPDATE `routes` SET `departure_station` = :departure_station, `arrival_station` = :arrival_station WHERE `id` = :id", [
        'id' => $routeID,
        'departure_station' => $editDepartureStation,
        'arrival_station' => $editArrivalStation
    ]);

    // Redirect after updating
    header('Location: add_route.php');
    exit();
}
// Получение всех маршрутов
$routes = $db->Select("SELECT * FROM `routes`");
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление маршрута</title>
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
    <p><a href="schedule.php">Вернуться</a></p>
</div>

<div class="container bg-dark text-light">
    <h2>Существующие маршруты</h2>
    <table class="table table-bordered table-dark">
        <thead>
        <tr>
            <th>ID</th>
            <th>Станция отправления</th>
            <th>Станция прибытия</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($routes as $route) : ?>
            <tr>
                <td><?php echo $route['id']; ?></td>
                <td><?php echo $route['departure_station']; ?></td>
                <td><?php echo $route['arrival_station']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="route_id" value="<?php echo $route['id']; ?>">
                        <!-- Add a hidden input to identify the action -->
                        <input type="hidden" name="action" value="edit">
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $route['id']; ?>">Редактировать</button>
                        <button type="submit" class="btn btn-danger" name="delete_route">Удалить</button>
                    </form>
                </td>
            </tr>

            <!-- Modal for editing a route -->
            <div class="modal fade" id="editModal<?php echo $route['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $route['id']; ?>" aria-hidden="true">
                <div class="modal-dialog bg-dark text-light" role="document">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header bg-dark text-light">
                            <h5 class="modal-title bg-dark text-light" id="editModalLabel<?php echo $route['id']; ?>">Редактировать</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body bg-dark text-light">
                            <form method="post" action="">
                                <input type="hidden" name="route_id" value="<?php echo $route['id']; ?>">
                                <div class="form-group">
                                    <label for="edit_departure_station">Станция отправления:</label>
                                    <input type="text" class="form-control" id="edit_departure_station" name="departure_station" value="<?php echo $route['departure_station']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_arrival_station">Станция прибытия:</label>
                                    <input type="text" class="form-control" id="edit_arrival_station" name="arrival_station" value="<?php echo $route['arrival_station']; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-success" name="update_route">Обновить маршрут</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Button to trigger modal for adding routes -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRouteModal">Добавить маршрут</button>

    <!-- Modal for adding a new route -->
    <div class="modal fade" id="addRouteModal" tabindex="-1" role="dialog" aria-labelledby="addRouteModalLabel" aria-hidden="true">
        <div class="modal-dialog bg-dark text-light" role="document">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header bg-dark text-light">
                    <h5 class="modal-title bg-dark text-light" id="addRouteModalLabel">Добавить новый маршрут</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-dark text-light">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="add_departure_station">Станция отправления:</label>
                            <input type="text" class="form-control" id="add_departure_station" name="departure_station" required>
                        </div>
                        <div class="form-group">
                            <label for="add_arrival_station">Станция прибытия:</label>
                            <input type="text" class="form-control" id="add_arrival_station" name="arrival_station" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="add_route">Добавить маршрут</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

</body>

</html>
