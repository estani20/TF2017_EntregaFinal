<!DOCTYPE html>
<html lang="es">
<?php

require 'inc/conexion.php';  #crea la conexión a la BD

include_once("navbar.php"); 


generar_menu($menu_ppal,1);

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
		$n_genero = $_POST['inputGenero'];
		$n_poster = $_POST['inputPoster'];
	

		//$rs = $mysqli->query("INSERT INTO pelicula SET nombre_pelicula = '$n_titulo', fecha_estreno = '$n_fecha', tiempo_duracion = '$n_duracion', sinopsis = '$n_sinopsis', imagen_poster = '$n_poster';");
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

		//$rs = $mysqli->query("SELECT * FROM pelicula p WHERE p.nombre_pelicula = '$n_titulo';");

		//$rs = $rs->fetch_assoc();

		$id = $rs['id_pelicula'];

		$sql = "INSERT INTO tiene SET id_pelicula = :id, id_genero = :genero";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
			$sql->bindParam(":genero",$n_genero,PDO::PARAM_STR);
			$sql->execute();

		//$rs = $mysqli->query("INSERT INTO tiene SET id_pelicula = '$id', id_genero = '$n_genero';");
	}

	//EDITAR
	if(isset($_POST['action']) && $_POST['action'] == 'Editar'){
		$id = $_POST['id_pelicula'];
		$n_titulo = $_POST['inputTitulo'];
		$n_fecha = $_POST['inputFecha'];
		$n_duracion = $_POST['inputDuracion'];
		$n_sinopsis = $_POST['inputSinopsis'];
		$n_poster = $_POST['inputPoster'];
		$n_genero = $_POST['inputGenero'];


		//$rs = $mysqli->query("UPDATE pelicula SET nombre_pelicula = '$n_titulo', fecha_estreno = '$n_fecha', tiempo_duracion = '$n_duracion', sinopsis = '$n_sinopsis', imagen_poster = '$n_poster' WHERE id_pelicula = '$id';");
		$sql = "UPDATE pelicula SET nombre_pelicula = :titulo, fecha_estreno = :fecest, tiempo_duracion = :durac, sinopsis = :sinopsis, imagen_poster = :poster WHERE id_pelicula = :id;";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":titulo",$n_titulo,PDO::PARAM_STR);
			$sql->bindParam(":fecest",$n_fecha,PDO::PARAM_STR);
			$sql->bindParam(":durac",$n_duracion,PDO::PARAM_STR);
			$sql->bindParam(":sinopsis",$n_sinopsis,PDO::PARAM_STR);
			$sql->bindParam(":poster",$n_poster,PDO::PARAM_STR);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
			$sql->execute();

		//$rs = $mysqli->query("UPDATE tiene SET id_genero = '$n_genero' WHERE id_pelicula = '$id';");

		$sql = "UPDATE tiene SET id_genero = :ngenero WHERE id_pelicula = :id;";

		$sql = $db->prepare($sql);
		$sql->bindParam(":ngenero",$n_genero,PDO::PARAM_STR);
		$sql->bindParam(":id",$id,PDO::PARAM_STR);
		$sql->execute();
	}

	//ELIMINAR
	else if(isset($_GET['id_pelicula']) && !isset($_GET['inputTitulo'])){
		$id = $_GET['id_pelicula'];
		
			$sql = "DELETE FROM tiene WHERE id_pelicula = :idpeli;";
			$sql = $db->prepare($sql);
			$sql->bindParam(":idpeli",$id,PDO::PARAM_STR);
			$sql->execute();

		//$rs = $mysqli->query("DELETE FROM tiene WHERE id_pelicula = '$id';");
			$sql = "DELETE FROM califica WHERE id_pelicula = :idpeli;";
			$sql = $db->prepare($sql);
			$sql->bindParam(":idpeli",$id,PDO::PARAM_STR);
			$sql->execute();
		//$rs = $mysqli->query("DELETE FROM califica WHERE id_pelicula = '$id';");
			$sql = "DELETE FROM pelicula WHERE id_pelicula = :idpeli;";
			$sql = $db->prepare($sql);
			$sql->bindParam(":idpeli",$id,PDO::PARAM_STR);
			$sql->execute();
		//$rs = $mysqli->query("DELETE FROM pelicula WHERE id_pelicula = '$id';");
	}

	else if(isset($_POST['inputIdCalif']) && isset($_POST['inputCalif'])){
		$id = $_SESSION['usuario']['userID'];
		$id_pelicula = $_POST['inputIdCalif'];
		$calif = $_POST['inputCalif'];

		$sql = "SELECT * FROM califica WHERE userID = :id AND id_pelicula = :idpelicula";
      
      	$sql = $db->prepare($sql);
      	$sql->bindParam(":id",$id,PDO::PARAM_STR);
      	$sql->bindParam(":idpelicula",$id_pelicula,PDO::PARAM_STR);
      	$sql->execute();
      	$count = $sql->rowCount();

    	if ($count<1){
    		//$rs = $mysqli->query("INSERT INTO califica SET userID = '$id', id_pelicula = '$id_pelicula', puntaje = '$calif'");
    		$sql = "INSERT INTO califica SET userID = :id, id_pelicula = :idpelicula, puntaje = :calif";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
      		$sql->bindParam(":idpelicula",$id_pelicula,PDO::PARAM_STR);
      		$sql->bindParam(":calif",$$calif,PDO::PARAM_STR);
			$sql->execute();


		} else{
			//$rs = $mysqli->query("UPDATE califica SET puntaje = '$calif' WHERE id_pelicula = '$id_pelicula' AND userID = '$id'");
			$sql = "UPDATE califica SET puntaje = :calif WHERE id_pelicula = :idpelicula AND userID = :id";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
      		$sql->bindParam(":idpelicula",$id_pelicula,PDO::PARAM_STR);
      		$sql->bindParam(":calif",$$calif,PDO::PARAM_STR);
			$sql->execute();
		}

		//$rs = $mysqli->query("SELECT * FROM califica WHERE userID = '$id' AND id_pelicula = '$id_pelicula'");
		/*if($rs->num_rows < 1){
			$rs = $mysqli->query("INSERT INTO califica SET userID = '$id', id_pelicula = '$id_pelicula', puntaje = '$calif'");
		} else{
			$rs = $mysqli->query("UPDATE califica SET puntaje = '$calif' WHERE id_pelicula = '$id_pelicula' AND userID = '$id'");
		}*/
	}


?>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<title>Sitio Trabajo Final</title>
	
	<meta name="description" content="breve descripcion del sitio">
	<meta name="keywords" content="palabraclave1,palabraclave2,palabraclave3">
	<meta name="robots" content="index,nofollow" >
	
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>

	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

	<!-- Script para mostrar un diálogo de confirmación al intentar eliminar -->
	<script language="JavaScript" type="text/javascript">
		function checkDelete(){
		    return confirm('Está seguro que desea eliminar la película?');
		}
</script>

</head>
 
<body>
	
	<div id="encab">
		<?=$menu_ppal?>
	</div>

	<div class="container pt-1">
		<div class="card card-container pt-4">

			<?php 
				// Si el usuario logueado es admin, habilito el botón para acceder a Agregar películas
				if(isset($_SESSION['usuario']) && $_SESSION['usuario'] == 'admin'){
	        		echo '<a href="./movie-form.php" class="btn btn-success mb-2">Agregar película</a>';
				} 
			?>
			
			<table class="table table-striped">
				<thead class="thead thead-dark">
					<th class="">Título</th>
					<th>Género</th>
					<th>Fecha de estreno</th>
					<th>Duración</th>
					<th>Sinopsis</th>
					<th>Calificación</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					<?php

					$sql = "SELECT * FROM pelicula p JOIN (SELECT t.id_genero Gen, g.nombre_genero, t.id_pelicula idP FROM tiene t JOIN genero g ON t.id_genero = g.id_genero ) AS Join1 ON p.id_pelicula = idP;";
			
					$sql = $db->prepare($sql);
					$sql->execute();
					$count = $sql->rowCount();
					//$rs = $sql->fetch();
					//$rs = $mysqli->query("SELECT * FROM pelicula p JOIN (SELECT t.id_genero Gen, g.nombre_genero, t.id_pelicula idP FROM tiene t JOIN genero g ON t.id_genero = g.id_genero ) AS Join1 ON p.id_pelicula = idP;");


					while($rs = $sql->fetch()) {
						// Template para la columna de acción del usuario admin
						$actionAdmin = "<a href='./movie-form.php?id_pelicula={$rs['id_pelicula']}' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> 
								<a href='./index.php?id_pelicula={$rs['id_pelicula']}'  onclick='return checkDelete()' class='btn btn-danger btn-sm'><i class='fa fa-trash'></span></i>";

						// Template para la columna de acción del usuario común
						$actionUser = "<div><form class='form-submit' method='post' action='index.php'><input type='hidden' name='inputIdCalif' value={$rs['id_pelicula']}></input><input type='number' name='inputCalif' min='1' max='5' size='1' value='3' required/><i class='fa fa-star'></span></i><button type='submit' class='btn btn-lg btn-primary btn-block btn-submit mt-2'>Calificar</button></form></div>";
						$actionToDo = "";

						if(isset($_SESSION['usuario'])){
							if($_SESSION['usuario'] == 'admin'){
	        					$actionToDo = $actionAdmin;
							} else {
								$actionToDo = $actionUser;
							}

	 					} else {
	 						// Si no hay usuario logueado, no se puede realizar ninguna acción
	 						$actionToDo = "Debes iniciar sesión<br> para realizar acciones";
	 					}

						echo "
						<tr>
							<td><h5>{$rs['nombre_pelicula']}</h5><img src='res/img/posters/{$rs['imagen_poster']}' class='img-thumbnail'></td>
							<td>{$rs['nombre_genero']}</td>
							<td>{$rs['fecha_estreno']}</td>
							<td>{$rs['tiempo_duracion']}</td>
							<td>{$rs['sinopsis']}</td>
							<td>{$rs['calificacion_promedio']} / 5</td>
							<td>".$actionToDo."</td>
						</tr>
					";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	
</body>
</html>
