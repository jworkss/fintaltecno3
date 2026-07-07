-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2026 a las 17:35:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pulsaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias`
--

CREATE TABLE `familias` (
  `id` int(11) NOT NULL,
  `codigo_familiar` varchar(4) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `familias`
--

INSERT INTO `familias` (`id`, `codigo_familiar`, `fecha_creacion`) VALUES
(1, '106Z', '2026-07-06 23:26:00'),
(2, 'ILR8', '2026-07-06 23:26:28'),
(3, 'T5S1', '2026-07-07 11:05:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_oximetro`
--

CREATE TABLE `registros_oximetro` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pulsaciones` int(11) NOT NULL,
  `oxigeno` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registros_oximetro`
--

INSERT INTO `registros_oximetro` (`id`, `usuario_id`, `pulsaciones`, `oxigeno`, `fecha`) VALUES
(1, 4, 65, 100, '2026-07-07 10:39:17'),
(3, 5, 45, 70, '2026-07-07 10:39:45'),
(5, 1, 40, 75, '2026-07-07 10:39:57'),
(9, 7, 74, 97, '2026-07-07 11:40:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `familia_id` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `esp_mac` varchar(17) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `correo`, `contrasenia`, `rol`, `familia_id`, `fecha_registro`, `esp_mac`, `edad`) VALUES
(1, 'Admin', 'Tecno 3', 'tecno3@unm.edu.ar', 'cabj', 'admin', NULL, '2026-07-06 22:23:50', NULL, NULL),
(4, 'marcos', 'sfsdf', 'tecno323@unm.edu.ar', 'nosoyyo', 'usuario', 1, '2026-07-06 23:26:00', NULL, NULL),
(5, 'marcos', 'sfsdf', 'marcos@unm.edu.ar', 'nosoyyo', 'usuario', 2, '2026-07-06 23:26:28', NULL, NULL),
(6, 'asfasfas', 'sfsdf', 'tenografo@unm.edu.ar', '123456', 'usuario', 3, '2026-07-07 11:05:38', NULL, NULL),
(7, 'aimee', 'meza', 'mezatecno@unm.edu.ar', '1234', 'usuario', 3, '2026-07-07 11:39:20', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_familiar` (`codigo_familiar`);

--
-- Indices de la tabla `registros_oximetro`
--
ALTER TABLE `registros_oximetro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `esp_mac` (`esp_mac`),
  ADD KEY `familia_id` (`familia_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `familias`
--
ALTER TABLE `familias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `registros_oximetro`
--
ALTER TABLE `registros_oximetro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registros_oximetro`
--
ALTER TABLE `registros_oximetro`
  ADD CONSTRAINT `registros_oximetro_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`familia_id`) REFERENCES `familias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
