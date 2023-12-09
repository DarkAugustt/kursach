<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database.php');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../error.php');
    exit();
}

// Получите параметры из формы на странице order.php
$schedule_id = isset($_POST['schedule_id']) ? $_POST['schedule_id'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$auto_id = isset($_POST['auto_id']) ? $_POST['auto_id'] : '';
$seat_number = isset($_POST['seat']) ? $_POST['seat'] : '';

// Получите информацию о пользователе
$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id", ['id' => $_SESSION['telegram_id']]);
$userID = $user[0]['telegram_id'];

// Обновите запись в таблице tickets, указав нового пользователя и измените статус на "Занято"
$updateTicket = $db->Update(
    "UPDATE tickets SET user_id = :user_id, status = 'Занято', ticket_time = CURRENT_TIMESTAMP WHERE schedule_id = :schedule_id AND date = :date AND auto_id = :auto_id AND seat_number = :seat_number AND status = 'Свободно'",
    [
        ':user_id' => $userID,
        ':schedule_id' => $schedule_id,
        ':date' => $date,
        ':auto_id' => $auto_id,
        ':seat_number' => $seat_number,
    ]
);

if ($updateTicket) {
    // Успешно обновлено, можно добавить дополнительные действия, например, перенаправление на страницу с подтверждением заказа.
    header('Location: confirmation.php');
} else {
    // Обработка ошибки
    echo "Error updating ticket. Details:";
    print_r($updateTicket);
    // Дополнительные действия при ошибке, если необходимо
}
?>