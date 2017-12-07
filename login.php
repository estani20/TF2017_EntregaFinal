<?php 
  session_start();

  if(isset($_SESSION['usuario'])){
        header('Location: index.php');
  }
 ?>

  <!DOCTYPE html>
  <html lang="en">
  <?php 

  require 'inc/coneccion.php';

    include_once("navbar.php"); 
    //include_once("pie.php"); 


    //generar_tit($tit);
    generar_menu($menu_ppal,1);
    generar_breadcrumbs($camino_nav,0,"Listado"); 

?>

  <head>
    <meta charset="utf-8">
    <title>Catálogo de películas - Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>
<body>
  <div id="encab">
    <?=$menu_ppal?>
  </div>
  <div class="container">
    <div class="card card-container" id="logincard">
     <h1 class="card-title text-center">Iniciar sesión</h1>

     <p id="profile-name" class="profile-name-card"></p>
     <form class="form-submit" method="post" action="validar_login.php">
      <input type="text" id="inputUser" name="usuario" class="form-control" pattern="[A-Za-z0-9_-]{1,15}" placeholder="Usuario" autocomplete="off" required autofocus>
      <input type="password" id="inputPassword" name="contra" class="form-control" pattern="[A-Za-z0-9_-]{1,15}" placeholder="Contraseña" required>
      
      <button class="btn btn-lg btn-primary btn-block btn-submit" type="submit">Iniciar sesión</button>
    </form><!-- /form -->

    <p>No tienes cuenta?<a href="./signup.php" class="forgot-password">
      Crea una nueva
    </a></p>

  </div><!-- /card-container -->
</div><!-- /container -->

</body>
</html>
