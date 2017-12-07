<?php
	
	if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
	# definición de las opciones de menú
	$menu1= [ 
			[1,"Películas","index.php","Lista de películas"], 
			[2, "Géneros", "genre-list.php", "Lista de géneros"]
	];
		
	
	#genera barra superior
	function generar_barra(&$barra_sup,$opcion) {			 
		$barra_sup =<<<EOT
			
EOT;
	}
	
	
	# genera el menu principal y selecciona el item indicado
	function generar_menu(&$menu_ppal,$menu) {
	global $menu1;
	
		$menu_ppal = "<nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
  				<a class='navbar-brand' href='index.php''>Trabajo Final</a>

  				<div class='collapse navbar-collapse' id='navbarSupportedContent'> <ul id='menuppal' class='navbar-nav mr-auto'>";   
		
		for ( $i=0; $i < count($menu1); $i++ )
		{
			$menutit=$menu1[$i][1];
			$clase1 = ($menu==$menu1[$i][0])? "menuactual":"";
			$ref = $menu1[$i][2];
				
			$menu_ppal .= " <li class='nav-item'>".
						"<a class='nav-link' href='$ref'>
					         {$menutit}
					      </a>";
		}   

		$menu_ppal .= "</ul>";
				     $opcion=0;

		if(isset($_SESSION['usuario'])){
	        $opcion=1;
	 	}
	

		if ($opcion==0) 
			$links = "<div class='collapse navbar-collapse flex-row-reverse'><ul class='navbar-nav'><li class='nav-item'><a href='signup.php'  title='Crear una cuenta de usuario' class='nav-link enabled'> Registrarse</a></li>".
				"<li class='nav-item'><a href='login.php'  title='Iniciar sesión' class='nav-link enabled'>Iniciar Sesión</a></li></ul></div>";
				     

		
		if ($opcion==1) 
			$links = "<div class='collapse navbar-collapse flex-row-reverse'><ul class='navbar-nav'><li class='nav-item pr-2'><span class='navbar-text'>Bienvenido, ".$_SESSION['usuario']['nombre_usuario']."!</span></li><li class='nav-item'><a href='logout.php'  title='Cerrar sesión' class='nav-link enabled'> Cerrar sesión</a></li></ul></div>";

		
		$menu_ppal .= "$links
					</div>
				</nav>";
	}


	function generar_breadcrumbs(&$camino_nav,$op,$text) {
	global $menu1;
	
		$menutit=$menu1[$op][1];
		
		$camino_nav =<<<EOT
				<ul id="camino">
					<li id="primero"><a href="index.php" title="ir a la página de inicio">Trabajo Final</a></li>
					<li><a href="{$menu1[$op][2]}" title="ir a $menutit">$menutit</a></li>
					<li>$text</li>
				</ul>	
EOT;
		
	}
	
?>