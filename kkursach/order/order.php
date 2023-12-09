<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database.php');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['auto_id'])) {
    header('Location: ../error.php');
    exit();
}

$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id", ['id' => $_SESSION['telegram_id']]);
$userID = $user[0]['telegram_id'];

$schedule_id = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$auto_id = isset($_GET['auto_id']) ? $_GET['auto_id'] : '';

$availableTickets = $db->Select("SELECT * FROM tickets WHERE schedule_id = :schedule_id AND date = :date AND status = 'Свободно'", [':schedule_id' => $schedule_id, ':date' => $date]);

// Обработка данных при отправке формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seat_number = isset($_POST['seat']) ? $_POST['seat'] : '';

    // Дополнительно можно добавить проверки валидности данных перед обновлением базы данных.

    // Обновите запись в таблице tickets, указав нового пользователя и измените статус на "Занято"
    $updateTicket = $db->Update(
        "UPDATE tickets SET user_id = :user_id, status = 'Занято' WHERE schedule_id = :schedule_id AND date = :date AND seat_number = :seat_number AND status = 'Свободно'",
        [
            ':user_id' => $userID,
            ':schedule_id' => $schedule_id,
            ':date' => $date,
            ':seat_number' => $seat_number,
        ]
    );


    header('Location: confirmation.php');
        exit();
}
?>

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
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-light mb-4">Выберите место</h2>
        <form action="" method="post">
            <input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>">
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="auto_id" value="<?php echo $auto_id; ?>">


            <div class="form-group">
                <label for="seat">Выберите место:</label>
                <select class="form-control" id="seat" name="seat">
                    <?php
                    // Populate seat dropdown with all available seats initially
                    $allAvailableSeats = array_column($availableTickets, 'seat_number');
                    foreach ($allAvailableSeats as $seatNumber):
                    ?>
                        <option value="<?php echo $seatNumber; ?>">
                            <?php echo 'Место ' . $seatNumber; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Заказать билет</button>
        </form>
    </div>
</body>

</html>