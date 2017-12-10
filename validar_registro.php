<?php 

require 'inc/conexion.php';

$usuario = $_POST['usuario']; 
$password = $_POST['password']; 

#buscar clave encriptada del usuario
  $salt = '34a@$#aA9823$'; //semilla - valor fijo
  $hash = hash('sha512', $salt.$password); //devuelve 128 caracteres
# $hash contine la clave encriptada 

#$rs = $mysqli->query("SELECT * FROM usuario u WHERE u.nombre_usuario=$usuario");
  $sql = "INSERT INTO usuario(nombre_usuario, password) VALUES (:user,:pass)";
			
			$sql = $db->prepare($sql);
			$sql->bindParam(":user",$usuario,PDO::PARAM_STR);
			$sql->bindParam(":pass",$hash,PDO::PARAM_STR);
			$sql->execute();
			$count = $sql->rowCount();
			
#DESDE ACA COMENTE
#$sql = "INSERT INTO usuario('nombre_usuario', 'password') VALUES (:usuario, :password)"

#$statement = $db->prepare($sql);

#$statement->bindParam(':usuario',$usuario);
#$statement->bindParam(':password',$hash);

	/*if(!$statement){
		echo "Error en la creacion del registro del Usuario";
		header("location:login.php");
	}
	else{*/
#		$statement->execute();
#		$rs = $statement->fetch();
# HASTA ACA

		if ($count<1){
			print_r($db->errorInfo());  #desarrollo
			header("location:signup.php");
		}
		else{
			header("location:login.php");
		}
	//}

$sql = null;
$db = null;

?> 