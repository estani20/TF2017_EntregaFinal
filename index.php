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
	
	//ELIMINAR
	if(isset($_GET['id_pelicula']) && !isset($_GET['inputTitulo'])){
		$id = $_GET['id_pelicula'];
		
			$sql = "DELETE FROM tiene WHERE id_pelicula = :idpeli;";
			$sql = $db->prepare($sql);
			$sql->bindParam(":idpeli",$id,PDO::PARAM_STR);
			$sql->execute();

			$sql = "DELETE FROM califica WHERE id_pelicula = :idpeli;";
			$sql = $db->prepare($sql);
			$sql->bindParam(":idpeli",$id,PDO::PARAM_STR);
			$sql->execute();

			$sql = "DELETE FROM pelicula WHERE id_pelicula = :idpeli;";
			$sql = $db->prepare($sql);
			$sql->bindParam(":idpeli",$id,PDO::PARAM_STR);
			$sql->execute();

	}
	else if(isset($_POST['inputIdCalif']) && isset($_POST['inputCalif'])){
		$id = $_SESSION['usuario']['userid'];
		$id_pelicula = $_POST['inputIdCalif'];
		$calif = $_POST['inputCalif'];

		$sql = "SELECT * FROM califica WHERE userID=:id AND id_pelicula=:idpelicula";
      
      	$sql = $db->prepare($sql);
      	$sql->bindParam(":id",$id,PDO::PARAM_STR);
      	$sql->bindParam(":idpelicula",$id_pelicula,PDO::PARAM_STR);
      	$sql->execute();
      	$count = $sql->rowCount();

    	if($count<1){

    		$sql = "INSERT INTO califica SET userID=:id, id_pelicula=:idpelicula, puntaje=:calif";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
      		$sql->bindParam(":idpelicula",$id_pelicula,PDO::PARAM_STR);
      		$sql->bindParam(":calif",$calif,PDO::PARAM_STR);
			$sql->execute();


		}else{

			$sql = "UPDATE califica SET puntaje=:calif WHERE id_pelicula=:idpelicula AND userID=:id";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
      		$sql->bindParam(":idpelicula",$id_pelicula,PDO::PARAM_STR);
      		$sql->bindParam(":calif",$calif,PDO::PARAM_STR);
			$sql->execute();
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

					$sql = "SELECT * FROM pelicula p";
			
					$sql = $db->prepare($sql);
					$sql->execute();
					$count = $sql->rowCount();
					
					while($rs = $sql->fetch()) {
						// Template para la columna de acción del usuario admin
						$actionAdmin = "<a href='./movie-form.php?id_pelicula={$rs['id_pelicula']}' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a> 
								<a href='./index.php?id_pelicula={$rs['id_pelicula']}'  onclick='return checkDelete()' class='btn btn-danger btn-sm'><i class='fa fa-trash'></span></i>";

						// Template para la columna de acción del usuario común
						$actionUser = "<div><form class='form-submit' method='post' action='index.php'><input type='hidden' name='inputIdCalif' value={$rs['id_pelicula']}></input><input type='number' name='inputCalif' min='1' max='5' size='1' value='3' required/><i class='fa fa-star'></span></i><button type='submit' class='btn btn-lg btn-primary btn-block btn-submit mt-2'>Calificar</button></form></div>";
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
							<td><h5>{$rs['nombre_pelicula']}</h5><img src='res/img/posters/{$rs['imagen_poster']}' class='img-thumbnail'></td>
							<td>";
							$id = $rs['id_pelicula'];
							$sqlgen = "SELECT * FROM genero JOIN tiene ON genero.id_genero = tiene.id_genero WHERE id_pelicula = :id";
							$sqlgen = $db->prepare($sqlgen);
							$sqlgen->bindParam(":id",$id,PDO::PARAM_STR);
							$sqlgen->execute();
							
							while ( $rsgen = $sqlgen->fetch()) {
								echo "<p>{$rsgen['nombre_genero']}</p>";
							}

							echo "</td>";
							$fecha = strtotime($rs['fecha_estreno']);
							$fechaFormateada = date("d/m/Y",$fecha);
							echo"
							<td>{$fechaFormateada}</td>
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
