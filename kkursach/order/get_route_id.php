<?php
// get_route_id.php

$conn = @mysqli_connect("localhost", "root", "", "zzd") or die ('Cannot connect to server');
mysqli_select_db($conn, "zzd") or die('Cannot open database');
mysqli_set_charset($conn, 'utf8');

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных из запроса
$from = $_POST['from'];
$to = $_POST['to'];

// Используйте полученные данные для определения route_id
$sql = "SELECT route_id FROM routes WHERE departure_station = '$from' AND arrival_station = '$to'";
$result = $conn->query($sql);

if ($result === false) {
    // Обработка ошибки выполнения запроса
    die("Error in SQL query: " . $conn->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $route_id = $row['route_id'];
    echo $route_id;
} else {
    echo 'No matching route found for the specified departure and arrival stations.';
}

$conn->close();
?>
