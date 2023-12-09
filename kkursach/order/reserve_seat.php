<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "zzd") or die('Не удается подключиться к серверу');
    mysqli_select_db($conn, "zzd") or die('Не удается открыть базу данных');
    mysqli_set_charset($conn, 'utf8');

    $ticket_id = $_POST['ticket_id'];
    $user_id = $_SESSION['user_id']; // Предполагается, что у вас есть информация о пользователе в сессии

    // Обновление статуса места на 'Занято' и привязка к пользователю
    $update_sql = "UPDATE tickets SET status = 'Занято', user_id = '$user_id' WHERE ticket_id = '$ticket_id'";
    $update_result = $conn->query($update_sql);

    if ($update_result === true) {
        echo "<p>Место успешно забронировано.</p>";
    } else {
        echo "<p>Произошла ошибка при бронировании места: " . $conn->error . "</p>";
    }

    $conn->close();
} else {
    echo "<p>Недопустимый метод запроса.</p>";
}
?>
