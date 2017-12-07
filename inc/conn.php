<?php
$dbhost="127.0.0.1";  // localhost
$dbport =""; 
$dbname="peliculas";
$usuario="admin";
$contrasenia="admin";

$strCnx = "mysql:dbname=$dbname;host=$dbhost";  // ;charset=utf8

$mysqli = new mysqli($dbhost,$usuario,$contrasenia,$dbname);

	if ($mysqli->connect_errno): echo "Error al conectarse a la base de datos por el error ".$mysqli->connect_error; endif;

?>