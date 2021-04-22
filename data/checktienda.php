<?PHP
require_once("../protege_basic.php");
//Evitando caché
	//no guardar en CACHE
	header ("Cache-Control: no-cache, must-revalidate"); 
	header ("Pragma: no-cache");
	
	//ultima actualización ahora 
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	
	//la pagina expira en una fecha pasada 
	header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT");	
	require_once("../conexion_PDO.php");
	
	//Recibimos parámetro idcd
	$link=$_GET["link"];

	$err=0;
	$url=$link;
	$i=0;
	$tiendas='';
	$db = new Conexion();
	$dbTabla="Tienda";
	$consulta = "SELECT COUNT(*) FROM $dbTabla WHERE activo=1"; 
	$result = $db->prepare($consulta);
	$result->execute();
	//$total = $result->fetchColumn();
	if ($result) {

		$consulta = "SELECT * FROM $dbTabla WHERE activo=1";
		$result = $db->prepare($consulta);
		$result->execute();
		echo '{"tienda":';
		foreach ($result as $tienda){
			$pos=false;
			$pos = strpos($url, $tienda['busqueda']);
			if ($pos !== false) {
				//l'ha trobat
			     //output name of tienda
				$tiendas = array();
				
				
				$arr = array('Idt' => $tienda['Idt'], 'nombre' => $tienda['nombre'], 'main' => $tienda['main']);
				array_push($tiendas, $arr);
				
				
				
				$i++;
			} else {
			//no l'ha trobat
			}
		}
		echo json_encode($tiendas);
		echo '}';
		if ($i==0) {
			//no s'ha trobat cap coincidencia
			print("No se ha encontrado la tienda");
		}

	}else{
		print "Error en la consulta\n";
	}
	$db=NULL;


?>