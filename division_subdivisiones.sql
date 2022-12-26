-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-12-2022 a las 19:19:53
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mandu_back`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `division_subdivisiones`
--

CREATE TABLE `division_subdivisiones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_division` bigint(20) UNSIGNED DEFAULT NULL,
  `id_subdivision` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `division_subdivisiones`
--

INSERT INTO `division_subdivisiones` (`id`, `id_division`, `id_subdivision`) VALUES
(1, 11, 15),
(2, 11, 19),
(3, 21, 19),
(4, 21, 18),
(5, 22, 13),
(6, 22, 12);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `division_subdivisiones`
--
ALTER TABLE `division_subdivisiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_division` (`id_division`),
  ADD KEY `id_subdivision` (`id_subdivision`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `division_subdivisiones`
--
ALTER TABLE `division_subdivisiones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `division_subdivisiones`
--
ALTER TABLE `division_subdivisiones`
  ADD CONSTRAINT `division_subdivisiones_ibfk_1` FOREIGN KEY (`id_division`) REFERENCES `division` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `division_subdivisiones_ibfk_2` FOREIGN KEY (`id_subdivision`) REFERENCES `division` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
