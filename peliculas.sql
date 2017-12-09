-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2017 a las 21:48:00
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `peliculas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actor`
--

CREATE TABLE `actor` (
  `id_actor` int(10) UNSIGNED NOT NULL,
  `nombre_actor` varchar(35) NOT NULL,
  `apellido_actor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `califica`
--

CREATE TABLE `califica` (
  `userID` int(10) UNSIGNED NOT NULL,
  `id_pelicula` int(10) UNSIGNED NOT NULL,
  `puntaje` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `califica`
--

INSERT INTO `califica` (`userID`, `id_pelicula`, `puntaje`) VALUES
(8, 1, 1),
(8, 5, 5),
(9, 1, 1),
(9, 5, 5);

--
-- Disparadores `califica`
--
DELIMITER $$
CREATE TRIGGER `actualizarCalifUpdate` AFTER UPDATE ON `califica` FOR EACH ROW UPDATE pelicula p SET p.calificacion_promedio = (SELECT AVG(puntaje) FROM califica WHERE p.id_pelicula = id_pelicula GROUP BY id_pelicula)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizarCalificacion` AFTER INSERT ON `califica` FOR EACH ROW BEGIN

  UPDATE pelicula p SET p.calificacion_promedio = (SELECT AVG(puntaje) FROM califica WHERE p.id_pelicula = id_pelicula GROUP BY id_pelicula);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `director`
--

CREATE TABLE `director` (
  `id_director` int(10) UNSIGNED NOT NULL,
  `nombre_director` varchar(35) NOT NULL,
  `apellido_director` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dirigida_por`
--

CREATE TABLE `dirigida_por` (
  `id_director` int(10) UNSIGNED NOT NULL,
  `id_pelicula` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(10) UNSIGNED NOT NULL,
  `nombre_genero` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre_genero`) VALUES
(1, 'Drama'),
(2, 'Comedia'),
(3, 'Accion'),
(4, 'Terror3'),
(5, 'Comedia Romantica'),
(6, 'Ciencia Ficcion'),
(7, 'Thriller'),
(8, 'Animada'),
(9, 'Western');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--

CREATE TABLE `pelicula` (
  `id_pelicula` int(10) UNSIGNED NOT NULL,
  `nombre_pelicula` varchar(50) NOT NULL,
  `fecha_estreno` date NOT NULL,
  `tiempo_duracion` int(11) NOT NULL,
  `calificacion_promedio` decimal(10,1) DEFAULT '0.0',
  `sinopsis` varchar(500) DEFAULT NULL,
  `imagen_poster` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pelicula`
--

INSERT INTO `pelicula` (`id_pelicula`, `nombre_pelicula`, `fecha_estreno`, `tiempo_duracion`, `calificacion_promedio`, `sinopsis`, `imagen_poster`) VALUES
(1, 'asdasd23', '1990-01-15', 1262, '1.0', 'After being held captive in an Afghan cave, billionaire engineer Tony Stark creates a unique weaponized suit of armor to fight evil.', 'https://t3.kn3.net/taringa/5/B/F/8/F/3/MarlingReina/FA7.jpg'),
(5, 'PELICULA 1', '1990-12-07', 456, '5.0', 'asdasdasdasdasd', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparto`
--

CREATE TABLE `reparto` (
  `id_pelicula` int(10) UNSIGNED NOT NULL,
  `id_actor` int(10) UNSIGNED NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiene`
--

CREATE TABLE `tiene` (
  `id_pelicula` int(10) UNSIGNED NOT NULL,
  `id_genero` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tiene`
--

INSERT INTO `tiene` (`id_pelicula`, `id_genero`) VALUES
(1, 5),
(5, 1),
(5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `userID` int(10) UNSIGNED NOT NULL,
  `nombre_usuario` varchar(35) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`userID`, `nombre_usuario`, `password`) VALUES
(7, 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(8, 'usuario', 'f8032d5cae3de20fcec887f395ec9a6a'),
(9, 'usuario2', '2fb6c8d2f3842a5ceaa9bf320e649ff0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actor`
--
ALTER TABLE `actor`
  ADD PRIMARY KEY (`id_actor`);

--
-- Indices de la tabla `califica`
--
ALTER TABLE `califica`
  ADD PRIMARY KEY (`userID`,`id_pelicula`),
  ADD KEY `fk_peliculaCalifica` (`id_pelicula`);

--
-- Indices de la tabla `director`
--
ALTER TABLE `director`
  ADD PRIMARY KEY (`id_director`);

--
-- Indices de la tabla `dirigida_por`
--
ALTER TABLE `dirigida_por`
  ADD PRIMARY KEY (`id_director`,`id_pelicula`),
  ADD KEY `fk_peliculaDirigida` (`id_pelicula`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD PRIMARY KEY (`id_pelicula`);

--
-- Indices de la tabla `reparto`
--
ALTER TABLE `reparto`
  ADD PRIMARY KEY (`id_pelicula`,`id_actor`),
  ADD KEY `fk_actorReparto` (`id_actor`);

--
-- Indices de la tabla `tiene`
--
ALTER TABLE `tiene`
  ADD PRIMARY KEY (`id_pelicula`,`id_genero`),
  ADD KEY `fk_generoTiene` (`id_genero`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actor`
--
ALTER TABLE `actor`
  MODIFY `id_actor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `director`
--
ALTER TABLE `director`
  MODIFY `id_director` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  MODIFY `id_pelicula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `userID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `califica`
--
ALTER TABLE `califica`
  ADD CONSTRAINT `fk_peliculaCalifica` FOREIGN KEY (`id_pelicula`) REFERENCES `pelicula` (`id_pelicula`),
  ADD CONSTRAINT `fk_userCalifica` FOREIGN KEY (`userID`) REFERENCES `usuario` (`userID`);

--
-- Filtros para la tabla `dirigida_por`
--
ALTER TABLE `dirigida_por`
  ADD CONSTRAINT `fk_dirigidaPorDir` FOREIGN KEY (`id_director`) REFERENCES `director` (`id_director`),
  ADD CONSTRAINT `fk_peliculaDirigida` FOREIGN KEY (`id_pelicula`) REFERENCES `pelicula` (`id_pelicula`);

--
-- Filtros para la tabla `reparto`
--
ALTER TABLE `reparto`
  ADD CONSTRAINT `fk_actorReparto` FOREIGN KEY (`id_actor`) REFERENCES `actor` (`id_actor`),
  ADD CONSTRAINT `fk_peliculaReparto` FOREIGN KEY (`id_pelicula`) REFERENCES `pelicula` (`id_pelicula`);

--
-- Filtros para la tabla `tiene`
--
ALTER TABLE `tiene`
  ADD CONSTRAINT `fk_generoTiene` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`),
  ADD CONSTRAINT `fk_peliculaTiene` FOREIGN KEY (`id_pelicula`) REFERENCES `pelicula` (`id_pelicula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
