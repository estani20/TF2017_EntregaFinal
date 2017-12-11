<!DOCTYPE html>
<html lang="es">
<?php

require 'inc/conexion.php';  #crea la conexión a la BD

include_once("navbar.php"); 

generar_menu($menu_ppal,1);


$genero = (isset($_POST["genero"]) && !empty($_POST["genero"]))? $_POST["genero"]:0;
$duracion = (isset($_POST["duracion"]) && !empty($_POST["duracion"]))? trim($_POST["duracion"]):null; 
$orden = (isset($_POST["orden"]) && !empty($_POST["orden"]))? $_POST["orden"]:0;


			# busca los generos disponibles y se presentan en una lista
function lista_generos(&$lista_c) {	
	global $db, $genero;

	$lista_c =  " <select  class='form-control mx-sm-3'  id='genero' name='genero' style='width:17%;' >". 
	"	<option value=0 selected>&laquo;Todos&raquo;</option>";

	$sql  = " SELECT * FROM genero ORDER BY nombre_genero";
	$rs = $db->query($sql);

	if (!$rs) {
					// mensaje error 
	} else {
		foreach ($rs as $row) {
			$seleccionado = ($genero == $row['id_genero'])? "selected":"";
			$lista_c .= "<option value='{$row['id_genero']}' $seleccionado>{$row['nombre_genero']}</option>"; 
		}
	}

	$lista_c .= "</select>";
	$rs=null;
}



$filtro="";
$orden_sql="";
$col="6";	

	# valido - genero sea entero 
	#		 - orden sea entero

	

	
	lista_generos ($lista_c);  #lista de generos

	#armar filtro para la consulta
	$titfilt = "Resultado "; 
	$titfiltro = "";
	$filtro = "";
	
	if ($genero <> 0) {
		$titfiltro .= " - Género: $genero ";  
		$filtro .= " genero.id_genero=$genero " ;
	}

	if ($duracion <> "") {
		$titfiltro .= " - Duración: ";
		
		if ($filtro!=="")  $filtro .= " 	AND " ;
		$filtro .= "tiempo_duracion >= $duracion";
	}
	
	if ($orden==1) 
		$orden_sql .= "nombre_pelicula ASC";

	if ($orden==2) 
		$orden_sql .= "calificacion_promedio DESC";

	
	
	if ($filtro=="") $filtro=" 0=0 ";
	if ($orden_sql=="") $orden_sql="pelicula.nombre_pelicula, pelicula.calificacion_promedio ";

	
	$sql ="
		SELECT pelicula.*, genero.* 
		FROM pelicula
		INNER JOIN tiene ON pelicula.id_pelicula=tiene.id_pelicula
		INNER JOIN genero ON genero.id_genero=tiene.id_genero
		WHERE $filtro
		ORDER BY $orden_sql 
	";
	$rs = $db->query($sql);

	if (!$rs ) { 
		print_r($db->errorInfo()); # mensaje en desarrollo

		echo "<tr><td colspan='<?=$col?>'><br>&nbsp;&nbsp; - No se encuentran datos para el filtro ingresado.</td></tr>";
		exit;
	}
	
	?>

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<title>Sitio Trabajo Final</title>

		<meta name="description" content="breve descripcion del sitio">
		<meta name="keywords" content="palabraclave1,palabraclave2,palabraclave3">
		<meta name="robots" content="index,nofollow" >

		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>

		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

		<script type="text/javascript">

			function listado() {
			// valida datos ingresados en el formulario
			// si datos ok -- return true  sino return false
			
			return true;
		}

		function excel() {
			document.getElementById("datos").method = "post";
			document.getElementById("datos").action = "personas_xls.php";
			document.getElementById("datos").submit(); 
		}		
	</script>
</head>

<body>
	<!--
	<div id="encabprint"> 
		Trabajo Final - Logo	
		<br>
		<span style="font-size:0.7em;"> Fecha de Impresión <?=date("d/m/Y H:i")?>hs. </span>
	</div>-->

	<div id="encab">
		<?=$menu_ppal?>
	</div>
	
	<div id="cuerpo" class="container no-border">

		<div class="card card-container pt-4 no-border no-print">
			<form  class="form-inline" name="datos" id="datos" method="post" action="cons.php" onsubmit="return listado();"> 
				<div class="form-group"> 
					<legend>Opciones</legend>

					<label for="genero">Género</label><?=$lista_c?>   

					<label for="duracion">  Duración mínima  </label>
					<input type="number"  class="form-control mx-sm-3" id="duracion" name="duracion" " size="6" maxlength="10">

					

					<div class="form-check form-check-inline pl-2">
						<label class="pr-3">Orden  </label>

						<label class="form-check-label pr-3" for="orden1">
							<input class="form-check-input"  type="radio" name="orden" id="orden1" value="1" <?php if ($orden==1) {?> checked="checked" <?php }?>> Alfabético
						</label>

						<label class="form-check-label pr-3" for="orden2">
							<input class="form-check-input"  type="radio" name="orden" id="orden2" value="2" <?php if ($orden==2) {?> checked="checked" <?php }?>>Calificación
						</label>

					</div>

					<input type="submit" id="Mostrar" class="btn btn-lg btn-primary btn-submit" name="Mostrar" value="Mostrar Listado">

					
				</div>	
			</form>
		</div>
		


		<div class="card card-container pt-4 no-border">
			<legend><?=$titfilt?></legend>
			<div>
				<a href="javascript:window.print();" title="Imprimir listado" class="no-print">
					<i class="fa fa-print fa-2x" aria-hidden="true"></i>
				</a>
				<a href="javascript:excel()" title='Exportar a Excel' class="no-print">
					<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" alt="Exportar a Excel"></i>
				</a>

			</div>
			<p ><?=$titfiltro?></p>


			<table width="85%" class="table table-striped"> 
				<thead class="thead thead-dark">
					<th>Género</th>
					<th>Película</th>
					<th>Duración</th>
					<th>Calificación</th>
					<th>Fecha de estreno</th>
					<th></th>
				</thead>
				<?php 		

				$genero = 0;
				$tot=$total=0;
				$subtotal="";

				if ($rs ) {      

					foreach ($rs as $reg) {  

						if ($genero <> $reg['id_genero']) {   

							$subtotal="";

							if ($tot<>0) {
								$subtotal="<td colspan=$col > Subtotal: $tot</td> ";
							}
							?>					
							<tr><?=$subtotal?></tr>
							<tr>
								<td colspan=<?=$col?>><strong> <?=$reg['nombre_genero']?></strong></td> 
							</tr>
							<?php  
							$genero = $reg['id_genero']; 
							$tot=0;
						}
						?>
						<tr >
							<td></td>
							<td><?=$reg['nombre_pelicula']?></td>
							<td><?=$reg['tiempo_duracion']?></td>
							<td><?=$reg['calificacion_promedio']?></td>
							<td><?=date("d-m-Y",strtotime($reg['fecha_estreno']))?></td>
							<td></td>
						</tr>
						<?php
						$tot++;
						$total++;
					}

					if ($tot<>0) {
						$subtotal="<tr><td colspan=$col > Subtotal: $tot</td></tr> ";
					}

					if ($total<>0) {
						$subtotal.="<tr><td colspan=$col > Total películas: $total</td></tr> ";
					} else {
						$subtotal.="<tr><td colspan=$col> No se encuentran datos para el filtro ingresado</td></tr> ";
					}

					echo $subtotal;

				}
				?>	
			</table>

		</div>

		<?php	

		$rs=null;
		$db=null;
		?>
	</body>
	</html>