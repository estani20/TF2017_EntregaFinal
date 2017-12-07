<!DOCTYPE html>
<html lang="es">
<?php

require 'inc/conn.php';  #crea la conexión a la BD

include_once("navbar.php"); 
//include_once("pie.php"); 


//generar_tit($tit);
generar_menu($menu_ppal,1);
generar_breadcrumbs($camino_nav,0,"Listado"); 

/*
	$sql =<<<EOT
		SELECT personas.*, trabaja.fechaingreso, cargo.cargo_desc
		FROM personas
		LEFT JOIN trabaja ON trabaja.pers_id=personas.pers_id
		LEFT JOIN cargo ON cargo.cargo_id=trabaja.cargo_id
		ORDER BY cargo.cargo_id, apellido, nombre
EOT;
	$rs = $db->query($sql);
	
	$lista="";
	
	if (!$rs) {
		print_r($db->errorInfo());  #CUIDADO - mensajes de error en desarrollo  - en producción se emite un mensaje generico
	} else {
		
		foreach($rs as $fila) {
			
			if (is_null($fila['cargo_desc'])) {
				$cargo="_sin cargo_ -";
				$fecingr="";
				$agregarTrabajo=" <a href='personas_cargo.php?tipo=A&pers_id={$fila['pers_id']}'> Agregar Trabajo</a> ";
			} else {
				$cargo=utf8_encode($fila['cargo_desc']);
				$fecingr="(".date('d-m-Y',strtotime($fila['fechaingreso'])).") -";
				$agregarTrabajo=" <a href='personas_cargo.php?tipo=M&pers_id={$fila['pers_id']}'> Modificar Trabajo</a> ";
			}
			
			
			$lista.="<li>".
					"  <strong>$cargo</strong> $fecingr ". 
					"  {$fila['apellido']}, {$fila['nombre']} ____ ".
					"  <a href='personas_abm.php?tipo=M&pers_id={$fila['pers_id']}'>Modifica</a> | ".
					"  <a href='#' onclick='javascript:borrar({$fila['pers_id']});'>Baja</a> | ".
					"  $agregarTrabajo ".
					"</li>";
		}
		
	}

	$rs=null;
    $db=null;
	
*/
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

	
	
		<script type="text/javascript">

	
			function ajax_conn(params,url) {
			var ajax_url; 
		
				/*
					Obtene una instancia del objeto XMLHttpRequest con el que JavaScript puede comunicarse con el servidor 
					de forma asíncrona intercambiando datos entre el cliente y el servidor sin interferir en el comportamiento actual de la página. 
				*/
				if(window.XMLHttpRequest) {
					conn = new XMLHttpRequest();
				}
				else if(window.ActiveXObject) {  // ie 6
					conn = new ActiveXObject("Microsoft.XMLHTTP");
				}

				
				conn.onreadystatechange = respuesta;   
						/*
							Preparar la funcion de respuesta
							cuando exista un cambio de estado se llama a la funcion respuesta (para que maneja la respuesta recibida)
							la URL solicitada podría devolver HTML, JavaSript, CSS, texto plano, imágenes, XML, JSON, etc.
						*/
						
				ajax_url = url+'?' + params;   	
				
				conn.open( "GET",ajax_url,true);				
						/*
						método XMLHttpRequest.open. 
						- método: el método HTTP a utilizar en la solicitud Ajax. GET o POST.
						url: dirección URL que se va a solicitar, la URL a la que se va enviar la solicitud.
						async: true (asíncrono) o false (síncrono).  -- asíncronico: no se espera la respuesta del servidor - sincronico: se espera la repuesta del servidor
						*/

				conn.send();	// Enviamos la solicitud - por metodo GET
				
				/* Metodo POST  
				conn.open('POST', url);
						// Si se utiliza el método POST, la solicitud necesita ser enviada como si fuera un formulario
				conn.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				conn.send(parametros);
				*/
			
			}


			
		function respuesta() {
					/*
						El valor de readyState 4 indica que la solicitud Ajax ha concluido 
						y la respuesta desde el servidor está disponible en XMLHttpRequest.responseText
					*/
			if(conn.readyState == 4) { 			// estado de conexión es completo - response.success
				if(conn.status == 200) {	// estado devuelto por el HTTP fue "OK" 
					 // conn.responseText - repuesta del servidor
					if ( conn.responseText==1) {
						location.reload();  // se borro un empleado - se refresca la pag
					} else {
						alert("El empleado no se pudo borrar porque tiene un trabajo asociado");
					} 
				}
			}
		}

		function borrar(id) {
			var errores=0;
		
			// validar ID
			
			// armar parametros a enviar - forma param1=valo1&param2=valor2 ...
			params="pers_id="+id;
			
			// archivo,  al que se le solcita una tarea  (URL que se va a solicitar via Ajax)
			url="personas_borrar.php";
			
			if (errores==0) {
				if (confirm('¿Está seguro que quiere borrar el empleado?')) {
					ajax_conn(params,url);
				}
			}
		}
		
</script>

</head>
 
<body>
	
	

	<div id="encab">
		<?=$menu_ppal?>
	</div>

	<div class="container">
		<div class="card card-container pt-4">
			<a href="./actor-form.php" class="btn btn-success mb-2">Agregar actor</a>
			<table class="table table-striped">
				<thead class="thead thead-dark">
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					

				</tbody>
			</table>
		</div>
	</div>
	<!--
	<div id="cuerpo">
		<?= $camino_nav?>
		
		<h3> Ejemplo de ABM de persona </h3>
			<a href="personas_abm.php?tipo=A">Ingresar una Persona</a>

			<h4>Listado de Personas</h4>
			<?php 
			if ($lista=="") {
				echo "<p> No se encuentran datos de personas</p>";
			}else{
			?>	
			<ul>
				<?=$lista?>
			</ul>
			<?php 	
			}
			?>		

	</div>

	<?=pie()?>-->

	
</body>
</html>



