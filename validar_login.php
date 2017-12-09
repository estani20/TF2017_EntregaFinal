<?php
  
  require 'inc/conn.php';
  //SESIONES

  session_start();

  $u = $_POST['usuario'];
  $p = $_POST['contra']; 
  $pass = md5($p); 

  $usuarios = $mysqli->query("SELECT * FROM usuario u WHERE u.nombre_usuario='$u' AND u.password='$pass'");

  if($usuarios->num_rows == 1):
    
    $datos = $usuarios->fetch_assoc();
    $_SESSION['usuario'] = $datos;

    echo json_encode(array('error' => false, $datos['nombre_usuario']));
    header("location:index.php");

  else:
    header("location:login.php?error=1");
  endif;

  $mysqli->close();

 ?>