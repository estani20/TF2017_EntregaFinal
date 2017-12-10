<?php 

require 'inc/conexion.php';

$usuario = $_POST['usuario']; 
$password = $_POST['password']; 

#buscar clave encriptada del usuario
  $salt = '34a@$#aA9823$'; //semilla - valor fijo
  $hash = hash('sha512', $salt.$password); //devuelve 128 caracteres
# $hash contine la clave encriptada 

  $sql = "INSERT INTO usuario(nombre_usuario, password) VALUES (:user,:pass)";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":user",$usuario,PDO::PARAM_STR);
			$sql->bindParam(":pass",$hash,PDO::PARAM_STR);
			$sql->execute();
			$count = $sql->rowCount();

		if ($count<1){
			print_r($db->errorInfo());  #desarrollo
			header("location:signup.php");
		}
		else{
			header("location:login.php");
		}

$sql = null;
$db = null;

?> 