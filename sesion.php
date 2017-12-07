<?php
	session_name("sesion_tesla");
	# iniciar o reanuda la sesi�n
	session_start();	
	
	# si un usuario se loguo con �xito, la variable de sesi�n tiene un valor
	$user= (isset($_SESSION["user"]) && !empty($_SESSION["user"]))?
	
	trim($_SESSION["user"]):"";
	if ($user=="") 
	{
		header("location:login.php");
	} 

	#cierra la session y destruye las variables de sesion creadas
	if (isset($_GET['close'])) 
	{
    	unset($_SESSION["user"]);
		unset($_SESSION["user_id"]);
		
		#Borra la cookie que almacena la sesi�n
		if(isset($_COOKIE[session_name()])) 
		{
			setcookie(session_name(), '', time() - 42000, '/');
		}
		session_destroy();
		header("location:login.php"); # redirigir a login
		exit;
	
	}	
?>
