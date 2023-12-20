# АврораТур.
### Описание.
## Система бронирования автобилетов.
Цель проекта - создание веб-приложения для бронирования автобилетов. Пользователи должны иметь возможность выбирать направление, дату и рейс, а также производить бронирование.
##### Пользователю доступно:
1. Выбор даты и маршрута отправления.
2. Выбор доступных рейсов и места в автобусе с дальнейшим бронированием.
3. Просмотр забронированных билетов в личном кабинете, возможность отмены.

##### Администратору доступно:
1. Просмотр зарегистрированных пользователей, их удаление, выдача роли администратора.
2. Возможность добавления и удаления автобусов, редактирования их названия и количества мест.
3. Возможность добавления рейсов: можно выбрать автобус, маршрут, даты отправления и прибытия. 
4. Возможность изменения статуса рейса: его отмена и восстановление, отправка.
5. Возможность просмотра всех забронированных мест.
6. Возможность добавления маршрутов, их удаление и редактирование.

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

## Интерфейс.
![Изображение](https://github.com/DarkAugustt/kursach/blob/main/Интерфейс.png)
## Диаграммы
![Изображение](https://github.com/DarkAugustt/kursach/blob/main/diagram.jpg)
## Архитектура системы
##### API сервера:
## Описание базы данных:
