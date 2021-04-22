<?php 

require_once("conexion_PDO.php");

$db = new Conexion();

$dbTabla='Usuarios'; 


$user = $_POST["user"];
$pass1 = $_POST["pass"];
$pass2 = $_POST["pass2"];

	$consulta = "SELECT COUNT(*) FROM $dbTabla WHERE user=:u"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":u" => $user));
	$total = $result->fetchColumn();

if($total==0){
	if($pass1==$pass2){
		$consulta2 = "INSERT INTO $dbTabla (user, pass) VALUES (:u, :p)";
		$result2 = $db->prepare($consulta2);
		if ($result2->execute(array(":u" => $user, ":p" => md5($pass1))))
		 	{ 
			header("location:index.php?x=3"); 
		} else{
			header("location:register.php?x=1"); 
		}
	}else{
		header("location:register.php?x=2");
	}
}else{
	header("location:register.php?x=3");
}
?>