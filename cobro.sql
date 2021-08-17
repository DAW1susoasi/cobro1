-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 05-08-2021 a las 12:00:14
-- Versión del servidor: 8.0.13-4
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cobro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobradores`
--

CREATE TABLE `cobradores` (
  `id_cobrador` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `token` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quedo_pendiente`
--

CREATE TABLE `quedo_pendiente` (
  `id_recibo` bigint(10) NOT NULL,
  `fecha` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos`
--

CREATE TABLE `recibos` (
  `auto` bigint(10) NOT NULL,
  `id_recibo` bigint(10) NOT NULL,
  `id_cobrador` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_valor` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `importe` int(5) NOT NULL,
  `semana_cobro` tinyint(1) NOT NULL DEFAULT '0',
  `semana_descargo` tinyint(1) NOT NULL DEFAULT '0',
  `observaciones` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos_temp`
--

CREATE TABLE `recibos_temp` (
  `id_recibo` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cobradores`
--
ALTER TABLE `cobradores`
  ADD PRIMARY KEY (`id_cobrador`);

--
-- Indices de la tabla `quedo_pendiente`
--
ALTER TABLE `quedo_pendiente`
  ADD KEY `id_recibo` (`id_recibo`);

--
-- Indices de la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD PRIMARY KEY (`id_recibo`),
  ADD UNIQUE KEY `auto` (`auto`),
  ADD KEY `id_cobrador` (`id_cobrador`);

--
-- Indices de la tabla `recibos_temp`
--
ALTER TABLE `recibos_temp`
  ADD KEY `id_recibo` (`id_recibo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `recibos`
--
ALTER TABLE `recibos`
  MODIFY `auto` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `quedo_pendiente`
--
ALTER TABLE `quedo_pendiente`
  ADD CONSTRAINT `quedo_pendiente_ibfk_1` FOREIGN KEY (`id_recibo`) REFERENCES `recibos` (`id_recibo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD CONSTRAINT `recibos_ibfk_1` FOREIGN KEY (`id_cobrador`) REFERENCES `cobradores` (`id_cobrador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recibos_temp`
--
ALTER TABLE `recibos_temp`
  ADD CONSTRAINT `recibos_temp_ibfk_1` FOREIGN KEY (`id_recibo`) REFERENCES `recibos` (`id_recibo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
