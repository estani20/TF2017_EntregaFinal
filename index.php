<!DOCTYPE html>
<html lang="es">
<?php

require 'inc/conn.php';  #crea la conexión a la BD

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



	//ELIMINAR
	if(isset($_GET['id_pelicula']) && !isset($_GET['inputTitulo'])){
		$id = $_GET['id_pelicula'];
		$rs = $mysqli->query("DELETE FROM tiene WHERE id_pelicula = '$id';");
		$rs = $mysqli->query("DELETE FROM califica WHERE id_pelicula = '$id';");
		$rs = $mysqli->query("DELETE FROM pelicula WHERE id_pelicula = '$id';");
	}

	else if(isset($_POST['inputIdCalif']) && isset($_POST['inputCalif'])){
		$id = $_SESSION['usuario']['userID'];
		$id_pelicula = $_POST['inputIdCalif'];
		$calif = $_POST['inputCalif'];

		$rs = $mysqli->query("SELECT * FROM califica WHERE userID = '$id' AND id_pelicula = '$id_pelicula'");
		if($rs->num_rows < 1){
			$rs = $mysqli->query("INSERT INTO califica SET userID = '$id', id_pelicula = '$id_pelicula', puntaje = '$calif'");
		} else{
			$rs = $mysqli->query("UPDATE califica SET puntaje = '$calif' WHERE id_pelicula = '$id_pelicula' AND userID = '$id'");
		}
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
				if(isset($_SESSION['usuario']) && $_SESSION['usuario']['nombre_usuario'] == 'admin'){
	        		echo '<a href="movie-form.php" class="btn btn-success mb-2">Agregar película</a>';
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
					$rs = $mysqli->query("SELECT * FROM pelicula;");


					foreach($rs as $fila) {
						// Template para la columna de acción del usuario admin
						$actionAdmin = "<a href='movie-form.php?id_pelicula={$fila['id_pelicula']}' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> 
								<a href='index.php?id_pelicula={$fila['id_pelicula']}'  onclick='return 	checkDelete()' class='btn btn-danger btn-sm'><i class='fa fa-trash'></span></i>";

						// Template para la columna de acción del usuario común
						$actionUser = "<div><form class='form-submit' method='post' action='index.php'><input type='hidden' name='inputIdCalif' value={$fila['id_pelicula']}></input><input type='number' name='inputCalif' min='1' max='5' size='1' value='3' required/><i class='fa fa-star'></span></i><button type='submit' class='btn btn-lg btn-primary btn-block btn-submit mt-2'>Calificar</button></form></div>";
						$actionToDo = "";

						if(isset($_SESSION['usuario'])){
							if($_SESSION['usuario']['nombre_usuario'] == 'admin'){
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
							<td><h5>{$fila['nombre_pelicula']}</h5><img src='{$fila['imagen_poster']}' class='img-thumbnail'></td>
							<td>";
							$id = $fila['id_pelicula'];
							$rsgen = $mysqli->query("SELECT * FROM genero JOIN tiene ON genero.id_genero = tiene.id_genero WHERE id_pelicula = '$id'");
							foreach ($rsgen as $gen) {
								echo "<p>{$gen['nombre_genero']}</p>";
							}

							
							echo "</td>";
							$fecha = strtotime($fila['fecha_estreno']);
							$fechaFormateada = date("d/m/Y", $fecha);
							echo "
							<td>{$fechaFormateada}</td>
							<td>{$fila['tiempo_duracion']}</td>
							<td>{$fila['sinopsis']}</td>
							<td>{$fila['calificacion_promedio']} / 5</td>
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
