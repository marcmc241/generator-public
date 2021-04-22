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
	$productid=$_GET["producto"];

	$db = new Conexion();

	$nofertas=0;
    $dbTabla="Oferta";
        $consulta = "SELECT * FROM $dbTabla WHERE Oferta.producto=:p AND Oferta.estadistica=1 ORDER BY Oferta.fechap DESC LIMIT 0,20";
        $result = $db->prepare($consulta);
        $result->execute(array(":p" => $productid));
        $ofertas = array();
		
    foreach ($result as $estadistica){
        $esph=$estadistica['precioH'];
        $espo=$estadistica['precioO'];
        $newDate = date("d-m-Y G:i", strtotime($estadistica['fechap']));
        $nofertas++;
        $arr = array('esph' => $esph, 'espo' => $espo, 'fecha' => $newDate);
		array_push($ofertas, $arr);
    }
	echo json_encode($ofertas);
		
/*
    $consulta = "SELECT * FROM $dbTabla WHERE Oferta.producto=:p AND Oferta.estadistica=1";//
        $result = $db->prepare($consulta);
        $result->execute(array(":p" => $productid));
        $minimum=3000000;
    foreach ($result as $estadistica){
        if ($minimum>$estadistica[precioO]) {
            $minimum=$estadistica[precioO];
        }
    }
    if ($minimum==3000000) {
        $minimum='no hay datos';
    }else{
        $minimum=$minimum."€";
    }
*/

    
		
		
	$db=NULL;//Cerramos conexión
?>