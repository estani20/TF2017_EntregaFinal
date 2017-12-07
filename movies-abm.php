<!DOCTYPE html>
<html lang="es">
<?php
require 'inc/conn.inc.php';  #crea la conexión a la BD

include_once("encabezado.php"); 
include_once("pie.php"); 

generar_barra($barra_sup,1);
generar_tit($tit);
generar_menu($menu_ppal,1);

generar_breadcrumbs($camino_nav,0,"ABM"); 



	#pregunta si se hizo clic en el botón 'enviar', es decir si se enviaron los datos ingresados en el formulario al servidor
	$btn =(isset($_POST['enviar']) && !empty($_POST['enviar']))? true:false;

	
		function inicializar_variables() {
		global $nombre_pelicula, $fec_estreno, $tiempo, $sinopsis, $poster, $genero;
		
			$nombre_pelicula=$sinopsis=$poster=$genero="";
			$fec_estreno = '1900-01-01';
			$tiempo = 0;
		}

		# recupera datos enviados por el metodo post
		function recuperar_datos() {
		global $nombre_pelicula, $fec_estreno, $tiempo, $sinopsis, $poster;
		
			$nombre_pelicula =(isset($_POST['nombre_pelicula']) && !empty($_POST['nombre_pelicula']))? $_POST['nombre_pelicula']:"";

			$genero =(isset($_POST['genero']) && !empty($_POST['genero']))? $_POST['genero']:""; 	//COMBO BOX

			$fec_estreno =(isset($_POST['fec_estreno']) && !empty($_POST['fec_estreno']))? $_POST['fec_estreno']:""; 	

			$tiempo=(isset($_POST["tiempo"]) && !empty($_POST["tiempo"]))? $_POST["tiempo"]:"";

			$sinopsis=(isset($_POST["sinopsis"]) && !empty($_POST["sinopsis"]))? $_POST["sinopsis"]:"";
			
			$poster=(isset($_POST["poster"]) && !empty($_POST["poster"]))? $_POST["poster"]:"";
		}

		#funcion que valida los datos recuprados del formulario 
		function validar_datos(){
			
			//VALIDAR genero como combobox

			$nombre_pelicula = ($nombre_pelicula != "")? 1 : 0;

			$tiempoDuracion = (intval($tiempo) > 0)? 1 : 0;

			validarFecha(); //FECHA VALIDA es fec_estreno != null y queda en formato y-m-d

			$fechaValida = ($fec_estreno != null)? 1 : 0;

			if ($poster != ""){
				$patronImagen = "/^https?:\/\/(?:[a-z\-]+\.)+[a-z]{2,6}(?:\/[^\/#?]+)+\.(?:jpe?g|gif|png)$/";
				$posterValido = (preg_match($patronImagen, $poster) == 1)? 1 : 0;
			}
			
		
		}
 	
		function validarFecha(){

		if (!is_null($fec_estreno) && (trim($fec_estreno)<>"")) {  // sale Y/m/d 	
	
		$fec_estreno = str_replace(array('\'', '-', '.', ','), '/', $fec_estreno); 
		$fec_estreno = str_replace(' ','', $fec_estreno); 
		
		$patron="/^[0-2][0-9]\/[01][0-9]\/[0-9]{4}/";
	
		if (preg_match($patron,$fec_estreno)==1) {
			
			$f = explode('/', $fec_estreno);

				if (checkdate($f[1],$f[0],$f[2])) {   #checkdate(m,d,y)
					$fec_estreno = "$f[2]/$f[1]/$f[0]";  #Y-m-d
				} else {
					$fec_estreno=null;
				}
			} else {
				$fec_estreno=null;
			}
		}

		}

	
	inicializar_variables();
	
	if (!$btn) { 
				# no hizo clic en el botón 'enviar'(de tipo submit), la solicitud viene de la página peliculas.php (por medio de un enlace - utilizando metodo GET)
				# puede ser un alta o modificacion  
				# recuperar el id de la persona y tipo de operación por medio del método GET
				# 		el id y tipo deben ser cargados en los inputs ocultos del formulario para saber que operación fue la que se solicitó
				#seleccionar los datos según el identificador de la persona ingresado
			$pers_id =(isset($_GET['pers_id']) && !empty($_GET['pers_id']))? $_GET['pers_id']:"";
			$tipo =(isset($_GET['tipo']) && !empty($_GET['tipo']))? $_GET['tipo']:"A"; 

			#Valida datos ingresados - 	 $pers_id-$tipo 
			#validar_datos();

			#si es un alta inicaliza los datos en vacio, sino busca los datos de la persona	
			if ($tipo=="A") {
				inicializar_variables();
			} else {
				$sql = "SELECT * 
						FROM peliculas
						WHERE pers_id=$pers_id
					";
				$rs = $db->query($sql)->fetch();
				
				if ($rs){
					$nombre=$rs['nombre'];
					$apellido=$rs['apellido'];
					$dni=$rs['dni'];
				} else {
					print_r($db->errorInfo());  #desarrollo
				}
				$rs=null;
			}
	
	} else {
		#hizo clic en el boton submit - envio los datos del formulario a procesar en el servidor

		# grabamos los datos enviados por el método POST en el formulario en la BD
		# recuperar todos los datos (incluso los campos ocultos) por el método POST

		recuperar_datos();

		validar_datos();  #SIEMPRE VALIDAR DATOS 
	
		
		# chequear que no se ingrese un documento que ya exista
		###############################
			
		#ver si es una modificación o un alta
		if ($tipo=="M") {
			$sql="UPDATE personas SET 
					apellido=?, nombre=?, dni=?  
				  WHERE pers_id=$pers_id
				";
			$sql = $db->prepare($sql);
			$sqlvalue=[$apellido,$nombre,$dni];
			$rs = $sql->execute($sqlvalue);

			if (!$rs) {
				print_r($db->errorInfo());  #desarrollo
			} else {
				header("location:personas.php");
			}
			$rs=null;

		} else {   #es un alta 

			$sql = "INSERT INTO personas(apellido,nombre,dni) VALUES(?,?,?)"; 
			$sql = $db->prepare($sql);
			$sqlvalue=[$apellido,$nombre,$dni];
			$rs = $sql->execute($sqlvalue);

			if (!$rs) {
				print_r($db->errorInfo());  #desarrollo
			} else {
				header("location:personas.php");
				# Una vez hecho el alta o modificación volver a la página personas.php
			}
			$rs=null;
		}

	}

	
	$rs=null;
    $db=null;
?>
?>
<head>
	<title>
		
	</title>
</head>
<body>

</body>
</html>