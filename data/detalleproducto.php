<?PHP
//Evitando caché
	require_once("../protege_basic.php");
	//no guardar en CACHE
	header ("Cache-Control: no-cache, must-revalidate"); 
	header ("Pragma: no-cache");
	
	//ultima actualización ahora 
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	
	//la pagina expira en una fecha pasada 
	header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT");	

	require_once("../conexion_PDO.php");
	//Recibimos parámetro idcd
	$producto=$_GET["producto"];
	
	$db = new Conexion();

	$dbTabla='Producto';
	$consulta = "SELECT * FROM $dbTabla WHERE Idp=:pr";
	$result = $db->prepare($consulta);
	$result->execute(array(":pr" => $producto));
	
	// PRINT ERROR O LISTA DATOS
	if (!$result){print "<p> Error en la consulta. </p>\n";}
	else{
		$productos = array();
		echo '{"producto":';
		foreach($result as $valor){
				$arr = array('Idp' => $valor['Idp'], 'nombrep' => $valor['nombrep'], 'descripcion' => $valor['descripcion'], 'foto' => $valor['foto'], 'categoria' => $valor['categoria'], 'activo' => $valor['activo'], 'fechap' => $valor['fechap'], 'tags' => $valor['tags']);
				array_push($productos, $arr);
		}
		echo json_encode($productos);
		echo '}';
	}
	$db=NULL;//Cerramos conexión
?>