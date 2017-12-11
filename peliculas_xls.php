<!DOCTYPE html>
<html lang="es">

<?php 
	
# con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

# indica al navegador que muestre el diálogo de descarga aún sin haber descargado todo el contenido
header("Content-type: application/octet-stream");
# indica al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=listado.xls");
header("Content-type: application/vnd.ms-excel");  

require 'inc/conexion.php';

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
		$titfiltro .= " - Duración mayor a (minutos): $duracion";
		
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

		echo "<tr><td colspan='<?=$col-1?>'><br>&nbsp;&nbsp; - No se encuentran datos para el filtro ingresado.</td><td></td></tr>";
		exit;
	}

 ?>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<title>Listado de películas</title>
</head>
<body>

	<table>
		<caption><strong><?=$titfilt?></strong></caption>
		<thead>
			<th>Género</th>
			<th>Película</th>
			<th>Duración</th>
			<th>Calificación</th>
			<th>Fecha de estreno</th>
		</thead>
		<?php 
			$genero = 0;
			foreach ($rs as $reg) {
		?>
				<tr>
					<td><?=utf8_encode($reg['nombre_genero'])?></td>
					<td><?=utf8_encode($reg['nombre_pelicula'])?></td>
					<td><?=utf8_encode($reg['tiempo_duracion'])?></td>
					<td><?=utf8_encode($reg['calificacion_promedio'])?></td>
					<td><?=utf8_encode($reg['fecha_estreno'])?></td>
				</tr>
			<?php } ?>

	</table>

</body>
</html>