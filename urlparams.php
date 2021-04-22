<?PHP
require_once("protege_basic.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Generator Neon</title>
</head>
<body>
	
<form action="urlparams.php" method="POST">
	<input type="text" name="url">
	<input type="submit" value="Generar Enlace">
</form>

<?PHP
require_once("functions.php");

if (isset($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
	$url = $_POST['url'];
}else{
	print("No hay url o es inválida");
	exit();
}

$url = addafilliate($url);

$url = make_bitly_url($url[0]);



print($url);

?>

</body>
</html>