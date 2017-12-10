<?php 
$dbhost="127.0.0.1";  // localhost
$dbport =""; 
$dbname="peliculas";

$usuario="admin";
$contrasenia="admin";

$strCnx = "mysql:dbname=$dbname;host=$dbhost";  // ;charset=utf8

$db ="";

try {
	$db = new PDO($strCnx, $usuario, $contrasenia);
	
	$db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER); # para referenciar en minuscula el nombre de las columnas
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC); # Relizar el FETCH ASSOC por defecto para ahorrar memoria
	
} catch (PDOException $e) {
    echo "Surgió un error en la aplicación.<br/>Error al conectarse a la base de datos por el error: <br/>";
    print "Error: " . $e->getMessage() . "<br/>";   # cambiar por un error personalizado
    die();
}
?>