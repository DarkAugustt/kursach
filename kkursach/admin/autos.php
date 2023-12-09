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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auto'])) {
    $auto_name = $_POST['auto_name'];
    $seats = $_POST['seats']; // Используем seats вместо edit_seats


    $db->Insert("INSERT INTO `autos` (`auto_name`, `seats`) VALUES (:auto_name, :seats)", [
        'auto_name' => $auto_name,
        'seats' => $seats
    ]);

    // Редирект после добавления
    header('Location: autos.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_auto'])) {
    $autoID = $_POST['id'];

    $db->Insert("DELETE FROM `autos` WHERE `id` = :id", ['id' => $autoID]);

    header('Location: autos.php');
    exit();
}


// Check if the form is submitted for editing a route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_route'])) {
    $routeID = $_POST['route_id'];
    $editDepartureStation = $_POST['edit_departure_station'];
    $editArrivalStation = $_POST['edit_arrival_station'];

    // Update route information
    $db->Update("UPDATE `routes` SET `departure_station` = :departure_station, `arrival_station` = :arrival_station WHERE `id` = :id", [
        'id' => $routeID,
        'departure_station' => $editDepartureStation,
        'arrival_station' => $editArrivalStation
    ]);

    // Redirect after updating
    header('Location: add_route.php');
    exit();
}

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
    <form method="post" action="">
        <div class="form-group">
            <label for="auto_name">Название автобуса:</label>
            <input type="text" class="form-control" id="auto_name" name="auto_name" required>
        </div>
        <div class="form-group">
            <label for="seats">Количество мест:</label>
            <input type="number" class="form-control" id="seats" name="seats" required>
        </div>
        <button type="submit" class="btn btn-primary" name="auto">Добавить</button>
    </form>
</div>

<div class="container bg-dark text-light mt-3">
    <h2>Автобусы</h2>
    <?php if (!empty($autos)) : ?>
        <table class="table bg-dark text-light">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Автобус</th>
                <th scope="col">Редактировать</th>
                <th scope="col">Удалить</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($autos as $auto) : ?>
                <tr>
                    <th scope="row"><?php echo $auto['id']; ?></th>
                    <td><?php echo $auto['auto_name']; ?></td>
                    <td>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $auto['id']; ?>">Редактировать</button>
                    </td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?php echo $auto['id']; ?>">
                            <button type="submit" class="btn btn-danger" name="delete_auto">Удалить</button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal<?php echo $auto['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $auto['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog bg-dark text-light" role="document">
                        <div class="modal-content bg-dark text-light">
                            <div class="modal-header bg-dark text-light">
                                <h5 class="modal-title bg-dark text-light" id="editModalLabel<?php echo $auto['id']; ?>">Редактировать</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body bg-dark text-light">
                                <form method="post" action="">
                                    <input type="hidden" name="id" value="<?php echo $auto['id']; ?>">
                                    <div class="form-group">
                                        <label for="edit_name">Автобус:</label>
                                        <input type="text" class="form-control" id="edit_name" name="edit_name" value="<?php echo $auto['auto_name']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_seats">Количество мест:</label>
                                        <input type="number" class="form-control" id="edit_seats" name="edit_seats" value="<?php echo $auto['seats']; ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="edit_auto">Сохранить изменения</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>В базе данных не содержится информации о поездах</p>
    <?php endif; ?>
</div>
</body>

</html>
