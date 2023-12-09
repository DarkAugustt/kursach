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

<script>
    function updateRouteIdAndSubmitForm() {
        // Получаем выбранный маршрут
        var routeId = $('#direction').val();
        var date = $('#date').val();

        // Проверяем, что направление и дата выбраны
        if (!routeId || !date) {
            alert('Пожалуйста, выберите направление и введите дату.');
            return;
        }

        console.log("Selected route_id: " + routeId);

        // Обновляем скрытые поля формы
        $('#route_id_input').val(routeId);
        $('#date_input').val(date);

        // Теперь, когда у нас есть route_id и date, отправляем форму
        console.log("Form about to be submitted. Route ID: " + routeId + ", Date: " + $('#date_input').val());
        $('#search-form').submit();
    }
</script>



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

<div class="container mt-4">
    <form id="search-form" method="POST" action="search.php">
        <div>
            <!-- Элементы input для передачи данных -->
            <input type="hidden" id="route_id_input" name="route_id">
            <input type="hidden" id="date_input" name="date">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="direction">Выберите направление</label>
                    <select class="form-control" id="direction" name="direction">
                        <option value="1">Москва - Санкт-Петербург</option>
                        <option value="2">Санкт-Петербург - Москва</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    date_default_timezone_set('Europe/Moscow');
                    ?>
                    <label for="date">Выберите дату</label>
                    <input type="date" class="form-control mx-auto" id="date" name="date" required
                           min="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" onclick="updateRouteIdAndSubmitForm()">Показать расписание</button>
    </form>
</div>

</body>

</html>
