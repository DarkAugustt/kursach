<?php

// Подключение к базе данных
$conn = @mysqli_connect("localhost", "root", "", "zzd") or die ('Cannot connect to server');
mysqli_select_db($conn, "zzd") or die('Cannot open database');
mysqli_set_charset($conn, 'utf8');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Удаление устаревших билетов
$today = date('Y-m-d H:i:s', time());
$delete_expired_sql = $conn->prepare("DELETE FROM tickets WHERE date < ? AND status = 'Свободен'");
$delete_expired_sql->bind_param("s", $today);
$delete_expired_sql->execute();
$delete_expired_sql->close();

// Добавление билетов
$schedule_ids = [1, 2]; // Названия маршрутов
$future_date = date('Y-m-d', strtotime($today . ' +2 months')); // Дата через 2 месяца

foreach ($schedule_ids as $schedule_id) {
    // Подготовленный запрос для проверки наличия записей
    $check_sql = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE schedule_id = ? AND date >= ?");
    $check_sql->bind_param("is", $schedule_id, $today);
    $check_sql->execute();
    $check_sql->bind_result($count);
    $check_sql->fetch();
    $check_sql->close();

    // Если записей нет, то добавляем билеты
    if ($count == 0) {
        for ($wagon_number = 1; $wagon_number <= 8; $wagon_number++) { //кол-во вагонов должно зависеть от админа
            $wagon_type = ($wagon_number <= 4) ? 'Эконом' : 'Люкс'; // Пускай будут классы обслуживания
            $num_seats = 26;

            // Подготовленный запрос для добавления билетов
            $insert_sql = $conn->prepare("INSERT INTO tickets (wagon_number, wagon_type, seat_number, status, date, schedule_id) VALUES (?, ?, ?, 'Свободно', ?, ?)");
            $insert_sql->bind_param("isisi", $wagon_number, $wagon_type, $seat_number, $date, $schedule_id);

            for ($seat_number = 1; $seat_number <= $num_seats; $seat_number++) {
                // Используем будущую дату
                $date = $today;

                // Добавляем запись для каждой даты сегодня до двух месяцев вперед
                while (strtotime($date) <= strtotime($future_date)) {
                    // Выполняем запрос
                    if (!$insert_sql->execute()) {
                        echo "Error in SQL query: " . $insert_sql->error;
                    }

                    // Переходим к следующей дате
                    $date = date('Y-m-d', strtotime($date . ' +1 day'));
                }
            }

            // Закрытие подготовленного запроса
            $insert_sql->close();
        }
    }
}

// Закрытие соединения с базой данных
mysqli_close($conn);
?>
