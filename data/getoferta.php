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
	require_once("../functions.php");
	//Recibimos parámetro
	$ido=$_GET["ido"];

	$db = new Conexion();
	$dbTabla="Producto, Oferta, Tienda";
	$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
	$result = $db->prepare($consulta);
	$result->execute(array(":id" => $ido));
	// Print error o datos oferta
	if (!$result){
		print "<p> Error en la consulta. </p>\n";
	}else{
		$res=buildoffer($ido);
		if ($res[1]=="") {//no error
			$ofertas = array();
			echo '{"oferta":';
			foreach($result as $valor){
				//print($res[0]);
				//$res[0] = str_replace("<\\\\ a href=", "", $res[0]);
				//$url2 = '@(http(s)?)?(://oxmf.club/images/)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
				//$res[0] = preg_replace($url2, '', $res[0]);
				//$url = '@(http(s)?)?(://bit.ly)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
				//$res[0] = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $res[0]);
				$strings= explode (' Enlace: ' , $res[0]);
				//$str = substr($res[0], 0, strpos($res[0], 'Enlace'));
				$strings[1]=preg_replace('@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', '<\\\\a class="inoffer" href=\\\\"http$2:\/\/$4" target="_blank" title="$0">$0</a>',$strings[1]);
				$strings[1]=' Enlace: '.$strings[1];
				$res[0]=implode($strings);
				

				$arr = array('nombrep' => $valor['nombrep'], 'descripcion' => $valor['descripcion'], 'foto' => $valor['foto'], 'categoria' => $valor['categoria'], 'fechap' => $valor['fechap'], 'tienda' => $valor['nombre'], 'precioH' => $valor['precioH'], 'precioO' => $valor['precioO'], 'cupon' => $valor['cupon'], 'bitlink' => $valor['bitlink'], 'estadistica' => $valor['estadistica'], 'estado'=> $valor['estado'], 'fprogram' => $valor['fprogram'], 'chat' => $valor['chat'], 'telegramid' => $valor['telegramid'], 'silencio' => $valor['silencio'], 'color' => $valor['color'], 'enlace' => $valor['enlace'], 'comentario' => $valor['comentario'], 'tipooferta' => $valor['tipooferta'], 'envio' => $valor['envio'], 'garantia' => $valor['garantia'], 'com2' => $valor['com2'], 'template' => $res[0]);
				array_push($ofertas, $arr);
			}
			echo json_encode($ofertas);
			echo '}';
		}else{
			print($res[1]);
		}
	}
	$db=NULL;//Cerramos conexión

?>