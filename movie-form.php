<!DOCTYPE html>

<?php 
require 'inc/conexion.php';  #crea la conexión a la BD

include_once("navbar.php"); 

generar_barra($barra_sup,1);
generar_menu($menu_ppal,1);

if((isset($_SESSION['usuario']) && $_SESSION['usuario']!= 'admin') || !isset($_SESSION['usuario'])){ //En la 2da condic borre ['nombre_usuario']
        header('Location: index.php');
  }

$actionTitle = 'Agregar';

if (isset($_GET['id_pelicula'])){
	$actionTitle = 'Editar';
	$id = $_GET['id_pelicula'];

	$sql = "SELECT * FROM pelicula p JOIN tiene t WHERE p.id_pelicula = :id;";
      
      	$sql = $db->prepare($sql);
      	$sql->bindParam(":id",$id,PDO::PARAM_STR);
      	$sql->execute();
      	$rs = $sql->fetch();

	$nombre = $rs['nombre_pelicula'];
	$fecha_estreno = $rs['fecha_estreno'];
	$tiempo_duracion = $rs['tiempo_duracion'];
	$sinopsis = $rs['sinopsis'];
	$imagen_poster = $rs['imagen_poster'];
	$id_genero = $rs['id_genero'];
} 


 ?> 

<html>
<head>
	<title><?php echo $actionTitle ?> película</title>

	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	
</head>
<body>
	<div id="encab">
		<?=$menu_ppal?>
	</div>

	


	<div class="container">

		<div class="card card-container">
			<h1 class="card-title pull-left"><?php echo $actionTitle ?> película</h1>
			<form class="form-submit" action="accion_pelicula.php" method="post">
				<input type="hidden" class="form-control" name="id_pelicula"  value="<?php echo $id ;?>" required autofocus>
				<input type="hidden" class="form-control" name="action"  value="<?php echo $actionTitle ;?>">
				<label>Título</label>
				<input type="text" name="inputTitulo" class="form-control" placeholder="Título" value="<?php echo (isset($nombre))?$nombre:'';?>" required autofocus>
				<label>Fecha de estreno</label>
				<input type="date" name="inputFecha" class="form-control" value="<?php echo (isset($fecha_estreno))?$fecha_estreno:'';?>" required autofocus>
				<label>Tiempo de duración</label>
				<input type="number" name="inputDuracion" class="form-control" value="<?php echo (isset($tiempo_duracion))?$tiempo_duracion: 0;?>" required autofocus>
				<label>Sinópsis</label>
				<textarea type="text" name="inputSinopsis" class="form-control pb-2" required autofocus><?php echo (isset($sinopsis))?$sinopsis:'';?></textarea>
				<label>Género</label>
				<p>(Mantener CTRL para seleccionar más de un género)</p>
				<select class="form-control" name="generos[]" multiple="multiple">
					<?php

						$sql = "SELECT * FROM genero g";
      
      					$sql = $db->prepare($sql);
      					$sql->execute();
      					//$rs = $sql->fetch();
					
						//$rs = $mysqli->query("SELECT * FROM genero g");
						while($rs = $sql->fetch()) {
							
							if($rs['id_genero'] == $id_genero){
								echo "<option value={$rs['id_genero']} selected='selected'>{$rs['nombre_genero']}</option>";
							}else

							echo "<option value={$rs['id_genero']} >{$rs['nombre_genero']}</option>";
						}

						/*
						foreach($rs as $fila) {

							if($fila['id_genero'] == $id_genero){
								echo "<option value={$fila['id_genero']} selected='selected'>{$fila['nombre_genero']}</option>";
							}else

							echo "<option value={$fila['id_genero']} >{$fila['nombre_genero']}</option>";
						}
						*/
					?>
				</select>
				<label>Póster</label>
				<input type="file" name="inputPoster" class="form-control" value="<?php echo (isset($imagen_poster))?$imagen_poster: '';?>"  autofocus>

				<br>
				<button class="btn btn-lg btn-primary btn-block btn-submit" type="submit"><?php echo $actionTitle ?> película</button>
			</form><!-- /form -->

		</div><!-- /card-container -->
	</div><!-- /container -->
</body>
</html>