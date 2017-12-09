<?php 
require 'inc/conn.php';
$usuario = $_POST['usuario']; 
$password = $_POST['password']; 

$rs = $mysqli->query("SELECT * FROM usuario u WHERE u.nombre_usuario=$usuario");

if($rs->num_rows == 0){
	$md5cod = md5($password); 
	$rs = $mysqli->query("INSERT INTO usuario SET nombre_usuario = '$usuario', password = '$md5cod'");
	header("location:index.php");
} else {
	header("location:login.php");
}



?> 