  <!DOCTYPE html>
  <html lang="en">

   <?php 
    include_once("navbar.php"); 
    //include_once("pie.php"); 


    //generar_tit($tit);
    generar_menu($menu_ppal,1);
    generar_breadcrumbs($camino_nav,0,"Listado"); 
   ?>

  <head>
    <meta charset="utf-8">
    <title>Catálogo de películas - Registrarse</title>
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
       <h1 class="card-title text-center">Nueva cuenta</h1>
       <form class="form-submit" method="post" action="validar_registro.php" >
        <label>Usuario</label>
        <input type="text" id="inputUser" class="form-control" name="usuario" pattern="[A-Za-z0-9_-]{1,15}" required autofocus>
        <label>Ingrese su contraseña</label>
        <input type="password" id="inputPassword" class="form-control" name="password" pattern="[A-Za-z0-9_-]{1,15}" placeholder="" required>
        
        <button class="btn btn-lg btn-primary btn-block btn-submit" type="submit">Registrar</button>
    </form><!-- /form -->

    <p>Ya tienes cuenta?<a href="./login.php" class="redirect-link">
      Iniciar sesión
  </a></p>

</div><!-- /card-container -->
</div><!-- /container -->

</body>
</html>
