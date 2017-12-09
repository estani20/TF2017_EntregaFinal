<!DOCTYPE html>
<html lang="es">
<?php

	require 'inc/conn.php';  #crea la conexión a la BD

	include_once("navbar.php"); 

	generar_menu($menu_ppal,1);
	generar_breadcrumbs($camino_nav,0,"Listado"); 



	// Inicializo vacíos los parámetros
	$id = '';
	$nuevoGenero = '';
	$error = '';

	// Seteo los valores a los atributos del género dependiendo de los parámetros recibidos
	if(isset($_POST['id_genero'])){
		$id = $_POST['id_genero'];
	}

	if(isset($_POST['new_genero'])){
		$nuevoGenero = $_POST['new_genero'];
	}

		// Dependiendo de los parámetros recibidos, realizo las distintas operaciones

		//AGREGAR
		if(($nuevoGenero != '') && ($id == '')){
	
		
		$rs = $mysqli->query("INSERT INTO genero SET nombre_genero = '".$nuevoGenero."';");
	}


	//EDITAR
	if(isset($_POST['id_genero']) && isset($_POST['new_genero'])){
		$id = $_POST['id_genero'];
		$nuevoGenero = $_POST['new_genero'];


		$rs = $mysqli->query("UPDATE genero SET nombre_genero = '$nuevoGenero' WHERE id_genero = '".$id."';");
	}

	//ELIMINAR
	else if(isset($_GET['id_genero'])){
		$id = $_GET['id_genero'];
	
		$rs = $mysqli->query("DELETE FROM genero WHERE id_genero = '".$id."';");
		if($rs==false){
   		   $error =  '<div class="alert alert-danger mt-3  mr-5 ml-5" >
						<span>No se puede eliminar un género que tenga películas asociadas</span>
					</div>';
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
		    return confirm('Está seguro que desea eliminar el género?');
		}
</script>

</head>
 
<body>

	<div id="encab">
		<?=$menu_ppal?>
	</div>
	<?php echo $error; ?>
	

	<div class="container">
		<div class="card card-container pt-4">

			<?php 
				// Si el usuario logueado es admin, habilito el botón para acceder a Agregar películas
				if(isset($_SESSION['usuario']) && $_SESSION['usuario']['nombre_usuario'] == 'admin'){
	        		echo '<a href="genre-form.php" class="btn btn-success mb-2">Agregar género</a>';
				} 
			?>

			
			<table class="table table-striped">
				<thead class="thead thead-dark">
					<th class="col md-5">Género</th>
					<th class="col md-1">Acciones</th>
				</thead>
				<tbody>
					<?php

					$rs = $mysqli->query("SELECT * FROM genero g;");

					$actionAdmin = $actionCommon = "";
					
					foreach($rs as $fila) {
						$actionAdmin = "<a href='genre-form.php?id_genero={$fila['id_genero']}' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a>".
						"<a href='genre-list.php?id_genero={$fila['id_genero']}' onclick='return 	checkDelete()' class='btn btn-danger btn-sm'><i class='fa fa-trash'></span></i>";
	
						$actionCommon = "<span>No disponible</span>";
	
						$action = $actionCommon;
	
						if(isset($_SESSION['usuario']) && $_SESSION['usuario']['nombre_usuario'] == 'admin'){
							$action = $actionAdmin;
						}
	
							echo "
							<tr class=''>
								<td class='col'>{$fila['nombre_genero']}</td>
								<td class='col'>".
									$action.
								"</td>
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



