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

  $usuarios = $mysqli->query("SELECT * FROM usuario u WHERE u.nombre_usuario='$u' AND u.password='$hash'");

  if($usuarios->num_rows == 1):
    
    $datos = $usuarios->fetch_assoc();
    $_SESSION['usuario'] = $datos;

    echo json_encode(array('error' => false, $datos['nombre_usuario']));
    header("location:index.php");

  else:
    header("location:login.php");
  endif;

  $mysqli->close();

 ?>