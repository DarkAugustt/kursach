-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 10 2023 г., 10:50
-- Версия сервера: 5.7.36
-- Версия PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `zzd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `autos`
--

DROP TABLE IF EXISTS `autos`;
CREATE TABLE IF NOT EXISTS `autos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_name` varchar(255) NOT NULL,
  `seats` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `autos`
--

INSERT INTO `autos` (`id`, `auto_name`, `seats`) VALUES
(2, 'Автобус 325', 51),
(26, 'помогите', 24);

-- --------------------------------------------------------

--
-- Структура таблицы `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departure_station` varchar(64) NOT NULL,
  `arrival_station` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `routes`
--

INSERT INTO `routes` (`id`, `departure_station`, `arrival_station`) VALUES
(1, 'Москва', 'Санкт-Петербург'),
(2, 'Санкт-Петербург', 'Москва');

-- --------------------------------------------------------

--
-- Структура таблицы `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_id` int(11) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `departure_date` date DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `schedule`
--

INSERT INTO `schedule` (`id`, `auto_id`, `route_id`, `departure_time`, `arrival_time`, `departure_date`, `arrival_date`, `status`) VALUES
(6, 2, 1, '17:09:00', '21:07:00', '2023-12-30', '2023-12-30', 'Запланировано');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `seat_number` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `tickets`
--

INSERT INTO `tickets` (`id`, `schedule_id`, `user_id`, `seat_number`, `status`, `date`) VALUES
(26, 3, NULL, 26, 'Свободно', '2023-12-09'),
(27, 3, NULL, 27, 'Свободно', '2023-12-09'),
(28, 3, NULL, 28, 'Свободно', '2023-12-09'),
(29, 3, NULL, 29, 'Свободно', '2023-12-09'),
(30, 3, NULL, 30, 'Свободно', '2023-12-09'),
(31, 3, NULL, 31, 'Свободно', '2023-12-09'),
(32, 3, NULL, 32, 'Свободно', '2023-12-09'),
(33, 3, NULL, 33, 'Свободно', '2023-12-09'),
(34, 3, NULL, 34, 'Свободно', '2023-12-09'),
(35, 3, NULL, 35, 'Свободно', '2023-12-09'),
(36, 3, NULL, 36, 'Свободно', '2023-12-09'),
(37, 3, NULL, 37, 'Свободно', '2023-12-09'),
(38, 3, NULL, 38, 'Свободно', '2023-12-09'),
(39, 3, NULL, 39, 'Свободно', '2023-12-09'),
(40, 3, NULL, 40, 'Свободно', '2023-12-09'),
(41, 3, NULL, 41, 'Свободно', '2023-12-09'),
(42, 3, NULL, 42, 'Свободно', '2023-12-09'),
(43, 3, NULL, 43, 'Свободно', '2023-12-09'),
(44, 3, NULL, 44, 'Свободно', '2023-12-09'),
(45, 3, NULL, 45, 'Свободно', '2023-12-09'),
(46, 3, NULL, 46, 'Свободно', '2023-12-09'),
(47, 3, NULL, 47, 'Свободно', '2023-12-09'),
(48, 3, NULL, 48, 'Свободно', '2023-12-09'),
(49, 3, NULL, 49, 'Свободно', '2023-12-09'),
(50, 3, NULL, 50, 'Свободно', '2023-12-09'),
(51, 3, NULL, 51, 'Свободно', '2023-12-09'),
(52, 4, NULL, 1, 'Свободно', '2023-12-09'),
(53, 4, NULL, 2, 'Свободно', '2023-12-09'),
(54, 4, NULL, 3, 'Свободно', '2023-12-09'),
(55, 4, NULL, 4, 'Свободно', '2023-12-09'),
(56, 4, NULL, 5, 'Свободно', '2023-12-09'),
(57, 4, NULL, 6, 'Свободно', '2023-12-09'),
(58, 4, NULL, 7, 'Свободно', '2023-12-09'),
(59, 4, NULL, 8, 'Свободно', '2023-12-09'),
(60, 4, NULL, 9, 'Свободно', '2023-12-09'),
(61, 4, NULL, 10, 'Свободно', '2023-12-09'),
(62, 4, NULL, 11, 'Свободно', '2023-12-09'),
(63, 4, NULL, 12, 'Свободно', '2023-12-09'),
(64, 4, NULL, 13, 'Свободно', '2023-12-09'),
(65, 4, NULL, 14, 'Свободно', '2023-12-09'),
(66, 4, NULL, 15, 'Свободно', '2023-12-09'),
(67, 4, NULL, 16, 'Свободно', '2023-12-09'),
(68, 4, NULL, 17, 'Свободно', '2023-12-09'),
(69, 4, NULL, 18, 'Свободно', '2023-12-09'),
(70, 4, NULL, 19, 'Свободно', '2023-12-09'),
(71, 4, NULL, 20, 'Свободно', '2023-12-09'),
(72, 4, NULL, 21, 'Свободно', '2023-12-09'),
(73, 4, NULL, 22, 'Свободно', '2023-12-09'),
(74, 4, NULL, 23, 'Свободно', '2023-12-09'),
(75, 4, NULL, 24, 'Свободно', '2023-12-09'),
(76, 4, NULL, 25, 'Свободно', '2023-12-09'),
(77, 4, NULL, 26, 'Свободно', '2023-12-09'),
(78, 4, NULL, 27, 'Свободно', '2023-12-09'),
(79, 4, NULL, 28, 'Свободно', '2023-12-09'),
(80, 4, NULL, 29, 'Свободно', '2023-12-09'),
(81, 4, NULL, 30, 'Свободно', '2023-12-09'),
(82, 4, NULL, 31, 'Свободно', '2023-12-09'),
(83, 4, NULL, 32, 'Свободно', '2023-12-09'),
(84, 4, NULL, 33, 'Свободно', '2023-12-09'),
(85, 4, NULL, 34, 'Свободно', '2023-12-09'),
(86, 4, NULL, 35, 'Свободно', '2023-12-09'),
(87, 4, NULL, 36, 'Свободно', '2023-12-09'),
(88, 4, NULL, 37, 'Свободно', '2023-12-09'),
(89, 4, NULL, 38, 'Свободно', '2023-12-09'),
(90, 4, NULL, 39, 'Свободно', '2023-12-09'),
(91, 4, NULL, 40, 'Свободно', '2023-12-09'),
(92, 4, NULL, 41, 'Свободно', '2023-12-09'),
(93, 4, NULL, 42, 'Свободно', '2023-12-09'),
(94, 4, NULL, 43, 'Свободно', '2023-12-09'),
(95, 4, NULL, 44, 'Свободно', '2023-12-09'),
(96, 4, NULL, 45, 'Свободно', '2023-12-09'),
(97, 4, NULL, 46, 'Свободно', '2023-12-09'),
(98, 4, NULL, 47, 'Свободно', '2023-12-09'),
(99, 4, NULL, 48, 'Свободно', '2023-12-09'),
(100, 4, NULL, 49, 'Свободно', '2023-12-09'),
(101, 4, NULL, 50, 'Свободно', '2023-12-09'),
(102, 4, NULL, 51, 'Свободно', '2023-12-09'),
(103, 6, 1972320740, 1, 'Занято', '2023-12-30'),
(104, 6, NULL, 2, 'Свободно', '2023-12-30'),
(105, 6, NULL, 3, 'Свободно', '2023-12-30'),
(106, 6, NULL, 4, 'Свободно', '2023-12-30'),
(107, 6, NULL, 5, 'Свободно', '2023-12-30'),
(108, 6, 1972320740, 6, 'Занято', '2023-12-30'),
(109, 6, 1972320740, 7, 'Занято', '2023-12-30'),
(110, 6, 1972320740, 8, 'Занято', '2023-12-30'),
(111, 6, NULL, 9, 'Свободно', '2023-12-30'),
(112, 6, NULL, 10, 'Свободно', '2023-12-30'),
(113, 6, NULL, 11, 'Свободно', '2023-12-30'),
(114, 6, NULL, 12, 'Свободно', '2023-12-30'),
(115, 6, NULL, 13, 'Свободно', '2023-12-30'),
(116, 6, NULL, 14, 'Свободно', '2023-12-30'),
(117, 6, NULL, 15, 'Свободно', '2023-12-30'),
(118, 6, NULL, 16, 'Свободно', '2023-12-30'),
(119, 6, NULL, 17, 'Свободно', '2023-12-30'),
(120, 6, NULL, 18, 'Свободно', '2023-12-30'),
(121, 6, NULL, 19, 'Свободно', '2023-12-30'),
(122, 6, NULL, 20, 'Свободно', '2023-12-30'),
(123, 6, NULL, 21, 'Свободно', '2023-12-30'),
(124, 6, NULL, 22, 'Свободно', '2023-12-30'),
(125, 6, NULL, 23, 'Свободно', '2023-12-30'),
(126, 6, NULL, 24, 'Свободно', '2023-12-30'),
(127, 6, NULL, 25, 'Свободно', '2023-12-30'),
(128, 6, NULL, 26, 'Свободно', '2023-12-30'),
(129, 6, NULL, 27, 'Свободно', '2023-12-30'),
(130, 6, NULL, 28, 'Свободно', '2023-12-30'),
(131, 6, NULL, 29, 'Свободно', '2023-12-30'),
(132, 6, NULL, 30, 'Свободно', '2023-12-30'),
(133, 6, NULL, 31, 'Свободно', '2023-12-30'),
(134, 6, NULL, 32, 'Свободно', '2023-12-30'),
(135, 6, NULL, 33, 'Свободно', '2023-12-30'),
(136, 6, NULL, 34, 'Свободно', '2023-12-30'),
(137, 6, NULL, 35, 'Свободно', '2023-12-30'),
(138, 6, NULL, 36, 'Свободно', '2023-12-30'),
(139, 6, NULL, 37, 'Свободно', '2023-12-30'),
(140, 6, NULL, 38, 'Свободно', '2023-12-30'),
(141, 6, NULL, 39, 'Свободно', '2023-12-30'),
(142, 6, NULL, 40, 'Свободно', '2023-12-30'),
(143, 6, NULL, 41, 'Свободно', '2023-12-30'),
(144, 6, NULL, 42, 'Свободно', '2023-12-30'),
(145, 6, NULL, 43, 'Свободно', '2023-12-30'),
(146, 6, NULL, 44, 'Свободно', '2023-12-30'),
(147, 6, NULL, 45, 'Свободно', '2023-12-30'),
(148, 6, NULL, 46, 'Свободно', '2023-12-30'),
(149, 6, NULL, 47, 'Свободно', '2023-12-30'),
(150, 6, NULL, 48, 'Свободно', '2023-12-30'),
(151, 6, NULL, 49, 'Свободно', '2023-12-30'),
(152, 6, NULL, 50, 'Свободно', '2023-12-30'),
(153, 6, NULL, 51, 'Свободно', '2023-12-30');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `telegram_id` varchar(52) NOT NULL,
  `telegram_username` varchar(522) DEFAULT NULL,
  `profile_picture` varchar(1500) DEFAULT NULL,
  `auth_date` int(16) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `telegram_id`, `telegram_username`, `profile_picture`, `auth_date`, `is_admin`) VALUES
(1, 'DAugust', ':)', '1972320740', 'https://t.me/i/userpic/320/mXwlEMLqQ_4rtJbNhbvnO4CeembP4IiahTS_0usBVMg.jpg', 'https://t.me/i/userpic/320/mXwlEMLqQ_4rtJbNhbvnO4CeembP4IiahTS_0usBVMg.jpg', 1702110927, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
