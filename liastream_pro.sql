-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2025 a las 20:03:47
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
-- Base de datos: `liastream_pro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_evento` varchar(255) NOT NULL,
  `id_productor` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `nombre_evento`, `id_productor`, `created_at`, `updated_at`) VALUES
(1, 'Copa 13 de Marzo 2025', 6, NULL, '2025-11-22 00:37:24'),
(4, 'Simpocio Ciencia y Medio Ambiente 2025', 10, '2025-11-22 00:45:45', '2025-11-22 00:45:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores`
--

CREATE TABLE `operadores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_operador` bigint(20) UNSIGNED NOT NULL,
  `id_productor` bigint(20) UNSIGNED NOT NULL,
  `id_punto` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `operadores`
--

INSERT INTO `operadores` (`id`, `id_operador`, `id_productor`, `id_punto`, `created_at`, `updated_at`) VALUES
(1, 7, 6, 1, NULL, NULL),
(5, 11, 10, 5, NULL, NULL),
(6, 6, 10, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_transmisions`
--

CREATE TABLE `puntos_transmisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_punto` varchar(255) NOT NULL,
  `id_evento` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `puntos_transmisions`
--

INSERT INTO `puntos_transmisions` (`id`, `nombre_punto`, `id_evento`, `created_at`, `updated_at`) VALUES
(1, 'Plaza America', 1, NULL, NULL),
(5, 'Hotel Ordoño 1', 4, '2025-11-22 00:46:02', NULL),
(6, 'Museo Emilio Bacardi', 4, '2025-11-22 13:29:48', NULL),
(7, 'Emp.Informatica y Medios AudioVisuales (CineSoft)', 1, '2025-11-22 13:32:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rol` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', NULL, NULL),
(2, 'Productor', NULL, NULL),
(3, 'Operador', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `nombre_usuario`, `correo`, `clave`, `telefono`, `id_rol`, `created_at`, `updated_at`) VALUES
(5, 'Admin', 'Principal', 'admin', 'admin@liastream.com', '$2y$10$B6BsrnmF7nChQiFuW3RUzeI7Bxb9bDap0jxNEOy.gcSzapmiX9J6q', '+5359089398', 1, NULL, NULL),
(6, 'Joazmin', 'Iribar Marin', 'jiribarmarin82', 'jiribarmarin82@gmail.com', '$2y$10$oUbTHg.wxShRRH9Liu85Ees.vep4PgdKNeu9EeVaLWxeSeeDCmsLe', '+5359089398', 2, NULL, NULL),
(7, 'Osmani', 'Torres Pal', 'osmani', 'osmani@gmail.com', '$2y$10$rzPlxcU3xncis5KZJcZ6DOY4YXTjaCSsVBd.ajNflRDfY76jEZPL2', '+5351417893', 3, NULL, NULL),
(9, 'Angel', 'Quintero Salsedo', 'angel@gmail.com', 'angel@gmail.com', '$2y$10$i6avQft.SEi8SLDtZIHEIuZYwnCABzJb8RWWg8PYJ0PXuWtB5h/w6', '+1102153124', 3, NULL, NULL),
(10, 'Grisel Valdes Seguen', '', 'grisel.valdes@gmail.com', 'grisel.valdes@gmail.com', '$2y$10$EB6205bQu/4sK8B1IS11n.4NP71xEjtLBWFAkpyguKp/vMM4S3phO', '', 2, NULL, NULL),
(11, 'William Emilio Barreras', '', 'william@gmail.com', 'william@gmail.com', '$2y$10$jp2DrqqBhPp1wu6CufJ4Q.7St/ozCpKG8HGEf6LWtVg40Qgx1mH/W', '', 3, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventos_id_productor_foreign` (`id_productor`);

--
-- Indices de la tabla `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operadores_id_operador_foreign` (`id_operador`),
  ADD KEY `operadores_id_productor_foreign` (`id_productor`),
  ADD KEY `operadores_id_punto_foreign` (`id_punto`);

--
-- Indices de la tabla `puntos_transmisions`
--
ALTER TABLE `puntos_transmisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `puntos_transmisions_id_evento_foreign` (`id_evento`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_nombre_usuario_unique` (`nombre_usuario`),
  ADD UNIQUE KEY `usuarios_correo_unique` (`correo`),
  ADD KEY `usuarios_id_rol_foreign` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `operadores`
--
ALTER TABLE `operadores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `puntos_transmisions`
--
ALTER TABLE `puntos_transmisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_id_productor_foreign` FOREIGN KEY (`id_productor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `operadores`
--
ALTER TABLE `operadores`
  ADD CONSTRAINT `operadores_id_operador_foreign` FOREIGN KEY (`id_operador`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `operadores_id_productor_foreign` FOREIGN KEY (`id_productor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `operadores_id_punto_foreign` FOREIGN KEY (`id_punto`) REFERENCES `puntos_transmisions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `puntos_transmisions`
--
ALTER TABLE `puntos_transmisions`
  ADD CONSTRAINT `puntos_transmisions_id_evento_foreign` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
