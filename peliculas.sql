-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Base de datos: `prueba`
--
CREATE DATABASE IF NOT EXISTS peliculas DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE peliculas;

-- --------------------------------------------------------
-- Estructura de tabla para entidades
--

CREATE TABLE pelicula (

  id_pelicula INT unsigned AUTO_INCREMENT NOT NULL,
  nombre_pelicula VARCHAR(50) NOT NULL,
  fecha_estreno DATE NOT NULL,
  tiempo_duracion INT NOT NULL,
  calificacion_promedio DECIMAL(1,1) DEFAULT 0,
  sinopsis VARCHAR(500),
  imagen_poster VARCHAR(200),

  CONSTRAINT pk_peliculas PRIMARY KEY (id_pelicula)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE genero (

  id_genero INT unsigned AUTO_INCREMENT NOT NULL,
  nombre_genero VARCHAR(25) NOT NULL,

  CONSTRAINT pk_genero PRIMARY KEY (id_genero)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE director (

  id_director INT unsigned AUTO_INCREMENT NOT NULL,
  nombre_director VARCHAR(35) NOT NULL,
  apellido_director VARCHAR(20) NOT NULL,

  CONSTRAINT pk_director PRIMARY KEY (id_director)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE actor(

  id_actor INT unsigned AUTO_INCREMENT NOT NULL,
  nombre_actor VARCHAR(35) NOT NULL,
  apellido_actor VARCHAR(20) NOT NULL,

  CONSTRAINT pk_actor PRIMARY KEY (id_actor)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE usuario(

  userID INT unsigned AUTO_INCREMENT NOT NULL,
  nombre_usuario VARCHAR(35) NOT NULL, 
  password VARCHAR(128) NOT NULL,

  CONSTRAINT pk_usuario PRIMARY KEY (userID)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* -----------------------------------Estructura de tabla para relaciones--------------------------*/

CREATE TABLE reparto(
  
  id_pelicula INT unsigned  NOT NULL,
  id_actor INT unsigned  NOT NULL,
  rol VARCHAR(20) NOT NULL,

  CONSTRAINT pk_reparto PRIMARY KEY (id_pelicula,id_actor),
  CONSTRAINT fk_peliculaReparto FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
  CONSTRAINT fk_actorReparto FOREIGN KEY (id_actor)  REFERENCES actor(id_actor)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE califica(
  userID INT unsigned NOT NULL,
  id_pelicula INT unsigned  NOT NULL,
  puntaje SMALLINT unsigned NOT NULL,

  CONSTRAINT pk_califica PRIMARY KEY (userID,id_pelicula),
  CONSTRAINT fk_peliculaCalifica FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
  CONSTRAINT fk_userCalifica FOREIGN KEY (userID) REFERENCES usuario(userID)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE dirigida_por(
  id_director INT unsigned NOT NULL,
  id_pelicula INT unsigned NOT NULL,

  CONSTRAINT pk_dirigidaPor PRIMARY KEY (id_director,id_pelicula),
  CONSTRAINT fk_peliculaDirigida FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
  CONSTRAINT fk_dirigidaPorDir FOREIGN KEY (id_director) REFERENCES director(id_director)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tiene(
  id_pelicula INT unsigned NOT NULL,
  id_genero INT unsigned NOT NULL,

  CONSTRAINT pk_tiene PRIMARY KEY (id_pelicula,id_genero),
  CONSTRAINT fk_peliculaTiene FOREIGN KEY (id_pelicula) REFERENCES pelicula(id_pelicula),
  CONSTRAINT fk_generoTiene FOREIGN KEY (id_genero) REFERENCES genero(id_genero)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Creacion y privilegios del usuario "admin" que solo se puede conectar desde el localhost
GRANT ALL PRIVILEGES ON peliculas.* TO admin@localhost
  IDENTIFIED BY 'admin' WITH GRANT OPTION;

# Creacion y privilegios del usuario "usuario_web" que se puede conectar desde cualquier sitio
CREATE USER usuario@'%' IDENTIFIED BY 'usuario';
GRANT SELECT ON peliculas.* TO usuario@'%';



delimiter !
CREATE TRIGGER actualizarCalificacion AFTER INSERT ON califica FOR EACH ROW
BEGIN

  UPDATE pelicula p SET p.calificacion_promedio = (SELECT AVG(puntaje) FROM califica c WHERE p.id_pelicula = c.id_pelicula GROUP BY c.id_pelicula);

END; !
delimiter ;


--
-- --------------------------------------------------------
--
-- Volcado de datos para las tablas de entidades
--

INSERT INTO `pelicula` (`id_pelicula`, `nombre_pelicula`, `fecha_estreno`, `tiempo_duracion`, `calificacion_promedio`, `sinopsis`, `imagen_poster`) VALUES
(2, 'Ironman 3', '2013-04-26', 130, '0.0', 'Cuando el mundo de Tony Stark es destrozado por un temible terrorista llamado Mandarin, comienza una odisea de reconstrucciÃ³n y castigo.', 'https://i.pinimg.com/originals/9e/e9/7c/9ee97ca32deecd3b7465665ba3494f7f.jpg'),
(3, 'Matrix', '1999-06-23', 136, '0.0', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', 'https://images-na.ssl-images-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_UY1200_CR84,0,630,1200_AL_.jpg');


#buscar clave encriptada del usuario
#$salt = '34a@$#aA9823$'; semilla - valor fijo
#$hash = hash('sha512', $salt.$psw); devuelve 128 caracteres
# $hash contine la clave encriptada 

INSERT INTO usuario (userID,nombre_usuario,password) VALUES
(0, 'admin', 'admin'),
(1, 'usuario','usuario');

INSERT INTO genero (id_genero,nombre_genero) VALUES
(1, 'Drama'),
(2, 'Comedia'),
(3, 'Accion'),
(4, 'Terror'),
(5, 'Comedia Romantica'),
(6, 'Ciencia Ficcion'),
(7, 'Thriller'),
(8, 'Animada'),
(9, 'Western'),
(10, 'Documental');



INSERT INTO califica (userID, id_pelicula, puntaje) VALUES
(1, 2, 4),
(1, 3, 5),
(0, 2, 3),
(0, 3, 4);

INSERT INTO tiene (id_pelicula, id_genero) VALUES
(2,3),
(3,6);


/*

INSERT INTO actor (`legajo`, `pers_id`, `cargo_id`, `fechaingreso`) VALUES
(1234, 1, 1, '2015-11-01'),
(45631, 2, 2, '2015-01-01');


INSERT INTO director (`legajo`, `pers_id`, `cargo_id`, `fechaingreso`) VALUES
(1234, 1, 1, '2015-11-01'),
(45631, 2, 2, '2015-01-01');


INSERT INTO usuario (`legajo`, `pers_id`, `cargo_id`, `fechaingreso`) VALUES
(1234, 1, 1, '2015-11-01'),
(45631, 2, 2, '2015-01-01');


--
-- Volcado de datos para las tablas de relacion
--

INSERT INTO reparto (id, id_actor, rol) VALUES
(1, 'Admin'),
(2, 'Auxiliar'),
(3, 'Técnico');



INSERT INTO dirigida_por (id_director, id) VALUES
(1234, 1, 1, '2015-11-01'),
(45631, 2, 2, '2015-01-01');

*/