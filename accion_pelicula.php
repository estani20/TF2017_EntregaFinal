<?php 
	require 'inc/conexion.php';

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

		$n_titulo = $_POST['inputTitulo'];
		$n_fecha = $_POST['inputFecha'];
		$n_duracion = $_POST['inputDuracion'];
		$n_sinopsis = $_POST['inputSinopsis'];
		$n_generos = isset($_POST['generos'])?$_POST['generos']:'';
		$n_poster = 'res/img/posters/'.$_POST['inputPoster'];
	
		$sql = "SELECT * FROM pelicula p WHERE p.nombre_pelicula = :titulo;";
      
	      	$sql = $db->prepare($sql);
    	  	$sql->bindParam(":titulo",$n_titulo,PDO::PARAM_STR);
      		$sql->execute();
      		$rs = $sql->fetch();
      		$count = $sql->rowCount();

    		if($count<1){

				$sql = "INSERT INTO pelicula SET nombre_pelicula = :titulo, fecha_estreno = :fecest, tiempo_duracion = :durac, sinopsis = :sinopsis, imagen_poster = :poster;";
			
				$sql = $db->prepare($sql);
				$sql->bindParam(":titulo",$n_titulo,PDO::PARAM_STR);
				$sql->bindParam(":fecest",$n_fecha,PDO::PARAM_STR);
				$sql->bindParam(":durac",$n_duracion,PDO::PARAM_STR);
				$sql->bindParam(":sinopsis",$n_sinopsis,PDO::PARAM_STR);
				$sql->bindParam(":poster",$n_poster,PDO::PARAM_STR);
				$sql->execute();

				$sql = "SELECT * FROM pelicula p WHERE p.nombre_pelicula = :titulo;";
      
	      		$sql = $db->prepare($sql);
    	  		$sql->bindParam(":titulo",$n_titulo,PDO::PARAM_STR);
      			$sql->execute();
      			$rs = $sql->fetch();
      			$count = $sql->rowCount();

      			//Usamos directamente el result set de la ultima consulta porque recuperamos el id auto generado de la pelicula recien insertada
    			$id = $rs['id_pelicula'];

					foreach ($n_generos as $g){

					$sql = "INSERT INTO tiene SET id_pelicula = :id, id_genero = :genero;";
			
					$sql = $db->prepare($sql);
					$sql->bindParam(":id",$id,PDO::PARAM_STR);
					$sql->bindParam(":genero",$g,PDO::PARAM_STR);
					$sql->execute();
					}

				header("location:index.php");

			}else{
		 		header("location:index.php?error=1");
			}
	}

	//EDITAR
	if(isset($_POST['action']) && $_POST['action'] == 'Editar'){
		$id = $_POST['id_pelicula'];
		$n_titulo = $_POST['inputTitulo'];
		$n_fecha = $_POST['inputFecha'];
		$n_duracion = $_POST['inputDuracion'];
		$n_sinopsis = $_POST['inputSinopsis'];
		$n_poster = $_POST['inputPoster'];

		$sql = "DELETE FROM tiene WHERE id_pelicula='$id'";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
			$sql->execute();

		$n_generos = $_POST['generos'];
		foreach ($n_generos as $g) {

			$sql = "INSERT INTO tiene SET id_pelicula = :id, id_genero = :genero;";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
			$sql->bindParam(":genero",$g,PDO::PARAM_STR);
			$sql->execute();
		}

		$sql = "UPDATE pelicula SET nombre_pelicula = :titulo, fecha_estreno = :fecest, tiempo_duracion = :durac, sinopsis = :sinopsis, imagen_poster = :poster WHERE id_pelicula = :id;";
			
		$sql = $db->prepare($sql);
		$sql->bindParam(":titulo",$n_titulo,PDO::PARAM_STR);
		$sql->bindParam(":fecest",$n_fecha,PDO::PARAM_STR);
		$sql->bindParam(":durac",$n_duracion,PDO::PARAM_STR);
		$sql->bindParam(":sinopsis",$n_sinopsis,PDO::PARAM_STR);
		$sql->bindParam(":poster",$n_poster,PDO::PARAM_STR);
		$sql->bindParam(":id",$id,PDO::PARAM_STR);
		$sql->execute();

		 header("location:index.php");
	}


 ?>