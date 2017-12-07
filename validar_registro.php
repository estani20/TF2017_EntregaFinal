<?php 
require 'inc/coneccion.php';


$usuario = $_POST['usuario']; 
$password = $_POST['password']; 


#buscar clave encriptada del usuario
$salt = '34a@$#aA9823$'; //semilla - valor fijo
$hash = hash('sha512', $salt.$password); //devuelve 128 caracteres
# $hash contine la clave encriptada 
$rs = $mysqli->query("SELECT * FROM usuario u WHERE u.nombre_usuario=$usuario");

if($rs->num_rows == 0){
	#$md5cod = md5($password); 
	$rs = $mysqli->query("INSERT INTO usuario SET nombre_usuario = '$usuario', password = '$hash'");
	header("location:index.php");
} else {
	header("location:login.php");
}



?> 