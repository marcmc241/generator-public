<?php
if (session_status() == PHP_SESSION_NONE) {
	ini_set('session.gc_maxlifetime', 3600);
	session_set_cookie_params(3600);
    session_start();
}
if((!isset($_SESSION["idu"])) || ($_SESSION["idu"]<0)){
	header("location:index.php");
	exit();
}
?>