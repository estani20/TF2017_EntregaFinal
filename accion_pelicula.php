<?php 
	require 'inc/conn.php';

	// Inicializo vacíos los parámetros
	$id = '';
	$n_titulo = '';
	$n_fecha = '';
	$n_duracion = 0;

	// Seteo los valores a los atributos de la película dependiendo de los parámetros recibidos
	if(isset($_POST['id_pelicula'])){
		$id = $_POST['id_pelicula'];
	}
	if(isset($_POST['inputTiulo'])){
		$n_titulo = $_POST['inputTitulo'];
	}
	if(isset($_POST['inputFecha'])){
		$n_fecha = $_POST['inputFecha'];
	}
	if(isset($_POST['inputDuracion'])){
		$n_duracion = $_POST['inputDuracion'];
	}
	if(isset($_POST['inputSinopsis'])){
		$n_sinopsis = $_POST['inputSinopsis'];
	}
	if(isset($_POST['inputPoster'])){
		$n_poster = $_POST['inputPoster'];
	}


	// Dependiendo de los parámetros recibidos, realizolas distintas operaciones
	//AGREGAR
	if(isset($_POST['action']) && $_POST['action'] == 'Agregar'){
		//$id = $_POST['id_pelicula'];
		$n_titulo = $_POST['inputTitulo'];
		$n_fecha = $_POST['inputFecha'];
		$n_duracion = $_POST['inputDuracion'];
		$n_sinopsis = $_POST['inputSinopsis'];
		$n_generos = $_POST['generos'];
		$n_poster = 'res/img/posters/'.$_POST['inputPoster'];
	
		$rs = $mysqli->query("INSERT INTO pelicula SET nombre_pelicula = '$n_titulo', fecha_estreno = '$n_fecha', tiempo_duracion = '$n_duracion', sinopsis = '$n_sinopsis', imagen_poster = '$n_poster';");


		$rs = $mysqli->query("SELECT * FROM pelicula p WHERE p.nombre_pelicula = '$n_titulo';");

		$rs = $rs->fetch_assoc();

		$id = $rs['id_pelicula'];
		foreach ($n_generos as $g) {
			$rs = $mysqli->query("INSERT INTO tiene SET id_pelicula = '$id', id_genero = '$g';");
		}
		

		 header("location:index.php");
	}

	//EDITAR
	if(isset($_POST['action']) && $_POST['action'] == 'Editar'){
		$id = $_POST['id_pelicula'];
		$n_titulo = $_POST['inputTitulo'];
		$n_fecha = $_POST['inputFecha'];
		$n_duracion = $_POST['inputDuracion'];
		$n_sinopsis = $_POST['inputSinopsis'];
		$n_poster = 'res/img/posters/'.$_POST['inputPoster'];

		$rsgen = $mysqli->query("DELETE FROM tiene WHERE id_pelicula='$id'");
		$n_generos = $_POST['generos'];
		foreach ($n_generos as $g) {
			$rs = $mysqli->query("INSERT INTO tiene SET id_pelicula = '$id', id_genero = '$g';");
		}


		$rs = $mysqli->query("UPDATE pelicula SET nombre_pelicula = '$n_titulo', fecha_estreno = '$n_fecha', tiempo_duracion = '$n_duracion', sinopsis = '$n_sinopsis', imagen_poster = '$n_poster' WHERE id_pelicula = '$id';");
		//$rs = $mysqli->query("UPDATE tiene SET id_genero = '$n_generos' WHERE id_pelicula = '$id';");
		 header("location:index.php");
	}


 ?>