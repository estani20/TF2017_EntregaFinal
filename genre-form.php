<!DOCTYPE html>

<?php 
require 'inc/conn.php';  #crea la conexión a la BD


include_once("navbar.php"); 

generar_barra($barra_sup,1);
generar_menu($menu_ppal,1);
	
	if((isset($_SESSION['usuario']) && $_SESSION['usuario']['nombre_usuario'] != 'admin') || !isset($_SESSION['usuario'])){
        header('Location: genre-list.php');
  }

	$actionTitle = 'Agregar';

	//Si se cumple este condicional, estoy editando
	if (isset($_GET['id_genero'])){
		$actionTitle = 'Editar';
		$id = $_GET['id_genero'];

		$rs = $mysqli->query("SELECT * FROM genero g WHERE g.id_genero = '".$id."';");

		$rs = $rs->fetch_assoc();
    	$nombre = $rs['nombre_genero'];

	} else{
			$nombre = '';
			$id = '';
	} 



?> 
<html>
<head>
	<title><?php echo $actionTitle ?> género</title>
	<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/styles.css">
	
<title><?php echo $actionTitle ?> género</title>
</head>
<body>
	<div id="encab">
		<?=$menu_ppal?>
	</div>
	<div class="container">

		<div class="card card-container" id="logincard">
			<h1 class="card-title pull-left"><?php echo $actionTitle ?> género</h1>
			<form class="form-submit" action="genre-list.php" method="post">
				<input type="hidden" class="form-control" name="id_genero"  value="<?php echo $id ;?>" required autofocus>
				<label>Género</label>
				<input type="text" class="form-control" placeholder="Nombre" name="new_genero" value="<?php echo $nombre ?>" required autofocus>
				<br>
				<button class="btn btn-lg btn-primary btn-block btn-submit" type="submit"><?php echo $actionTitle ?> género</button>
			</form><!-- /form -->

		</div><!-- /card-container -->
	</div><!-- /container -->
</body>
</html>