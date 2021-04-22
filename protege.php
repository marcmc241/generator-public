<?php
if (session_status() == PHP_SESSION_NONE) {
	ini_set('session.gc_maxlifetime', 3600);
	session_set_cookie_params(3600);
    session_start();
}
if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0 no és admin
	if((!isset($_SESSION["idu"])) || ($_SESSION["idu"]<=0)){//si no hi ha idu o és inferior o igual a 0 no és usuari
	header("location:index.php");//ves al index (login)
	exit();
	}else{//si hi ha idu i és superior a 0 és usuari no admin
	header("location:principal.php?x=3");//ves a la pagina principal i mostra l'avís que no es admin
	exit();
	}
}//segueix si es admin
?>