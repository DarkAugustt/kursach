# АврораТур.

## Система бронирования автобилетов.
Цель данного проекта заключается в разработке веб-приложения, предназначенного для удобного бронирования авиабилетов. Пользователи смогут легко выбирать желаемое направление, определять предпочтительные даты и рейсы, а также осуществлять процесс бронирования с максимальным комфортом.
# Техническое задание

## Пользовательские сценарии.
##### Сценарии работы пользователя.
1. Неавторизованный пользователь заходит на главную страницу. При посещении сайта он ограничен в возможности выполнения любых действий, за исключением регистрации или входа.
2. При нажатии на кнопку авторизации происходит вход/регистрация через Телеграм. 
3. При переходе на страницу бронирования у пользователя есть возможность выбрать дату и маршрут отправления.
4. Если рейсы, соответствующие введённым данным, существуют, то пользователь увидит их список.
5. После выбора рейса пользователю предоставляется выбор места в автобусе. Уже забронированные места высвечены не будут.
6. После выполнения бронированния пользователь может увидеть свои забронированные места в личном кабинете.
##### Сценарии работы администратора.
1. В панель администратора можно попасть через профиль. Кнопка для перехода видна только пользователю с ролью администратора.
2. В панели можно посмотреть на всех зарегистрированных пользователей, выдать и забрать роль администратора, удалить пользователя
3. Администратор может добавить и отредактировать данные об автобусе, отправлении и маршруте.
4. Для просмотра данных об бронированиях администратор может перейти на соответствующую страницу.
## Функциональные требования:
### Система авторизации:
1. Реализация авторизации пользователей через мессенджер Telegram.
2. Защита от несанкционированного доступа.
### Бронирование билета:
1. Возможность выбора даты и маршрута.
2. Возможность выбора места.
3. Возможность отмены бронирования.
### Просмотр истории бронирований:
1. Отображение списка предыдущих бронирований пользователя.
2. Детали каждого бронирования.
## Технологический стек:
### Backend:
1. Язык программирования: PHP.
2. Система управления базами данных: MySQL.
### Frontend:
1. Языки программирования: JavaScript, HTML, CSS.
2. Использование фреймворка Bootstrap для улучшения дизайна и адаптивности.
### Интеграция с мессенджером Telegram:
 Использование Telegram API для реализации системы авторизации.
## Интерфейс.
![Изображение](https://github.com/DarkAugustt/kursach/blob/main/Интерфейс.png)
## Диаграммы
![Изображение](https://github.com/DarkAugustt/kursach/blob/main/diagram.jpg)
![Изображение](https://github.com/DarkAugustt/kursach/blob/main/umldiag.png)
## Документация
https://github.com/DarkAugustt/kursach/blob/main/openapi.yaml
## Развёртывание 
Для развёртывания у себя или на сервере в директории /var/www/html в терминале пропишите git clone https://github.com/DarkAugustt/BusBooking.git. В phpmyadmin импортируйте дамп базы данных. Настройте в database/config.php необходимые параметры. Для авторизации через Telegram создайте у @BotFather бота и укажите /setdomain с вашим доменом.
