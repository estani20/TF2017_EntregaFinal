<?php
  
  require 'inc/conexion.php';
  //SESIONES

  session_start();


  $u = $_POST['usuario'];
  $p = $_POST['contra'];

#buscar clave encriptada del usuario
  $salt = '34a@$#aA9823$'; //semilla - valor fijo
  $hash = hash('sha512', $salt.$p); //devuelve 128 caracteres
# $hash contine la clave encriptada 

$sql = "SELECT userID,nombre_usuario FROM usuario u WHERE u.nombre_usuario=:user AND u.password=:pass";
      
      $sql = $db->prepare($sql);
      $sql->bindParam(":user",$u,PDO::PARAM_STR);
      $sql->bindParam(":pass",$hash,PDO::PARAM_STR);
      $sql->execute();
      $count = $sql->rowCount();

    if ($count<1){
      print_r($db->errorInfo());  #desarrollo
      header("location:login.php?error=1");
    }
    else{
      $rs = $sql->fetch();
      $_SESSION['usuario'] = $rs;
      header("location:index.php?");
    
    }
  //}

$rs = null;
$db = null;

 ?>