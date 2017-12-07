<!DOCTYPE html>
<?php include_once("navbar.php"); 

generar_barra($barra_sup,1);
//generar_tit($tit);
generar_menu($menu_ppal,1);
 ?> 
<html>
<head>
	<title>Agregar película</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
<title>Agregar director</title>
</head>
<body>
	<div id="encab">
		<?=$menu_ppal?>
	</div>
	<div class="container">

		<div class="card card-container">
			<h1 class="card-title pull-left">Agregar director</h1>
			<form class="form-submit">
				<label>Nombre</label>
				<input type="text" id="inputUser" class="form-control" placeholder="Nombre" required autofocus>
				<label>Apellido</label>
				<input type="text" id="inputUser" class="form-control" placeholder="Apellido" required autofocus>
				
				<br>
				<button class="btn btn-lg btn-primary btn-block btn-submit" type="submit">Agregar director</button>
			</form><!-- /form -->

		</div><!-- /card-container -->
	</div><!-- /container -->
</body>
</html>