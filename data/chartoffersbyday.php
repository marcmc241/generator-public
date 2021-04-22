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
	
	
    $days_ago = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 5, date("Y")));//5 dias antes
    //print($days_ago);
	$db = new Conexion();

	$nofertas=0;
    $dbTabla="Oferta";
        $consulta = "SELECT COUNT(*), DATE_FORMAT(Oferta.fechap,'%Y-%m-%d') as created_day FROM $dbTabla WHERE Oferta.fechap>=:fd GROUP BY created_day LIMIT 0,6";
        $result = $db->prepare($consulta);
        $result->execute(array(":fd" => $days_ago));
        $ofertas = array();
		
    foreach ($result as $estadistica){
        $arr = array('nofertas' => $estadistica['COUNT(*)'], 'day' => $estadistica['created_day']);
		array_push($ofertas, $arr);
    }
	echo json_encode($ofertas);

		
	$db=NULL;//Cerramos conexión
?>