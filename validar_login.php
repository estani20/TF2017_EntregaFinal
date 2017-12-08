<?php
  
  require 'inc/coneccion.php';
  //SESIONES

  session_start();

  $u = $_POST['usuario'];
  $p = $_POST['contra']; 
#  $pass = md5($p); 
  
#buscar clave encriptada del usuario
  $salt = '34a@$#aA9823$'; //semilla - valor fijo
  $hash = hash('sha512', $salt.$p); //devuelve 128 caracteres
# $hash contine la clave encriptada 

  $sql = "SELECT * 
          FROM usuario u 
          WHERE u.nombre_usuario='$u' AND u.password='$hash';
          ";

        $rs = $db->query($sql)->fetch();
      
      # $usuarios = $db->

  #if($usuarios->num_rows == 1):
  if($rs){
    #$datos = $usuarios->fetch_assoc();
    $datos = $rs['nombre_usuario'];
    $_SESSION['usuario'] = $datos;
    echo json_encode(array('error' => false, $datos));
    header("location:index.php");
    }
  else
    {
      header("location:login.php");
    }

  $mysqli->close();

 ?>