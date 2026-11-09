-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: database:3306
-- Tiempo de generación: 09-11-2026 a las 18:01:00
-- Versión del servidor: 8.4.13
-- Versión de PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agenda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociado`
--

CREATE TABLE `asociado` (
  `id_estudiante` int NOT NULL,
  `id_responsable` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `asociado`
--

INSERT INTO `asociado` (`id_estudiante`, `id_responsable`) VALUES
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_usr`
--

CREATE TABLE `categoria_usr` (
  `tipo` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categoria_usr`
--

INSERT INTO `categoria_usr` (`tipo`) VALUES
('administrador'),
('estudiante'),
('familia'),
('profesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int NOT NULL,
  `nombre_grupo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `nombre_grupo`) VALUES
(1, 'Grupo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pertenece`
--

CREATE TABLE `pertenece` (
  `id_usuario` int NOT NULL,
  `id_grupo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pertenece`
--

INSERT INTO `pertenece` (`id_usuario`, `id_grupo`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL,
  `categoria_usuario` varchar(20) NOT NULL COMMENT 'estudiante, profesor, familia, administrador',
  `estado_usuario` tinyint(1) NOT NULL COMMENT 'Flase = dado de baja\r\nTrue = activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `contraseña`, `categoria_usuario`, `estado_usuario`) VALUES
(1, 'Estudiante', 'contraseña no destinada a ser reproducible', 'estudiante', 0),
(2, 'Profesor', '$2y$10$aArA5AzzKNBWvGxBvQEYe.OUvCDDKrBJc32VgqKFQXGJc2XOvhASi', 'profesor', 1),
(3, 'Familia', '$2y$10$VuSIt8lmhj6rn/W/RyoMsOZChSiQDYkSE.ZO9ROjIlRNxkLWpRbru', 'familia', 1),
(4, 'Admin', '$2y$10$LB82qcERUbXkG.f6fXmyueSZ3LgWFtUJnuvMpdLqJF1j0uVNOtqe2', 'administrador', 1);

-- --------------------------------------------------------

--
-- Estructura para la tabla `actividad`
--

CREATE TABLE `actividad` (
  `id_actividad` int NOT NULL AUTO_INCREMENT,
  `nombre_actividad` varchar(150) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  PRIMARY KEY (`id_actividad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura para la tabla `asignacion_actividad`
--

CREATE TABLE `asignacion_actividad` (
  `id_usuario` int NOT NULL,
  `id_actividad` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura para la tabla `paso_actividad`
--

CREATE TABLE `paso_actividad` (
  `id_actividad` int NOT NULL,
  `num_paso` int NOT NULL,
  `titulo_paso` varchar(150) NOT NULL,
  `completado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura para la tabla `preferencia`
--

CREATE TABLE `preferencia` (
  `id_preferencia` int NOT NULL AUTO_INCREMENT,
  `formato_visual` varchar(20) NOT NULL DEFAULT 'texto' COMMENT 'texto o pictogramas',
  `permite_vista_semanal` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_preferencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura para la tabla `configura`
--

CREATE TABLE `configura` (
  `id_usuario` int NOT NULL,
  `id_preferencia` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asociado`
--
ALTER TABLE `asociado`
  ADD PRIMARY KEY (`id_estudiante`,`id_responsable`),
  ADD UNIQUE KEY `id_estudiante_2` (`id_estudiante`,`id_responsable`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_responsable` (`id_responsable`);

--
-- Indices de la tabla `categoria_usr`
--
ALTER TABLE `categoria_usr`
  ADD PRIMARY KEY (`tipo`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `nombre_grupo` (`nombre_grupo`);

--
-- Indices de la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD PRIMARY KEY (`id_usuario`,`id_grupo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `categoria_usuario` (`categoria_usuario`);

--
-- Indices de la tabla `asignacion_actividad`
--
ALTER TABLE `asignacion_actividad`
  ADD PRIMARY KEY (`id_usuario`,`id_actividad`),
  ADD KEY `id_actividad` (`id_actividad`);

--
-- Indices de la tabla `paso_actividad`
--
ALTER TABLE `paso_actividad`
  ADD PRIMARY KEY (`id_actividad`,`num_paso`);

--
-- Indices de la tabla `configura`
--
ALTER TABLE `configura`
  ADD PRIMARY KEY (`id_usuario`,`id_preferencia`),
  ADD KEY `id_preferencia` (`id_preferencia`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asociado`
--
ALTER TABLE `asociado`
  ADD CONSTRAINT `asociado_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `asociado_ibfk_2` FOREIGN KEY (`id_responsable`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD CONSTRAINT `pertenece_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `pertenece_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`categoria_usuario`) REFERENCES `categoria_usr` (`tipo`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `asignacion_actividad`
--
ALTER TABLE `asignacion_actividad`
  ADD CONSTRAINT `asig_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `asig_ibfk_2` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id_actividad`) ON DELETE CASCADE;

--
-- Filtros para la tabla `paso_actividad`
--
ALTER TABLE `paso_actividad`
  ADD CONSTRAINT `paso_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id_actividad`) ON DELETE CASCADE;

--
-- Filtros para la tabla `configura`
--
ALTER TABLE `configura`
  ADD CONSTRAINT `conf_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `conf_ibfk_2` FOREIGN KEY (`id_preferencia`) REFERENCES `preferencia` (`id_preferencia`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
