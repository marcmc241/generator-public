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

	//time ago human readable
	define( 'TIMEBEFORE_NOW',         'ahora' );
    define( 'TIMEBEFORE_MINUTE',      'hace {num} minuto' );
    define( 'TIMEBEFORE_MINUTES',     'hace {num} minutos' );
    define( 'TIMEBEFORE_HOUR',        'hace {num} hora' );
    define( 'TIMEBEFORE_HOURS',       'hace {num} horas' );
    define( 'TIMEBEFORE_YESTERDAY',   'ayer' );
    define( 'TIMEBEFORE_FORMAT',      '%e %b' );
    define( 'TIMEBEFORE_FORMAT_YEAR', '%e %b, %Y' );

    $numof=$_GET["numof"];

    function time_ago( $time )
    {
        $out    = ''; // what we will print out
        $now    = time(); // current time
        $diff   = $now - $time; // difference between the current and the provided dates

        if( $diff < 60 ) // it happened now
            return TIMEBEFORE_NOW;

        elseif( $diff < 3600 ) // it happened X minutes ago
            return str_replace( '{num}', ( $out = round( $diff / 60 ) ), $out == 1 ? TIMEBEFORE_MINUTE : TIMEBEFORE_MINUTES );

        elseif( $diff < 3600 * 24 ) // it happened X hours ago
            return str_replace( '{num}', ( $out = round( $diff / 3600 ) ), $out == 1 ? TIMEBEFORE_HOUR : TIMEBEFORE_HOURS );

        elseif( $diff < 3600 * 24 * 2 ) // it happened yesterday
            return TIMEBEFORE_YESTERDAY;

        else // falling back on a usual date format as it happened later than yesterday
            return strftime( date( 'Y', $time ) == date( 'Y' ) ? TIMEBEFORE_FORMAT : TIMEBEFORE_FORMAT_YEAR, $time );
    }

	require_once("../conexion_PDO.php");

	
	//Recibimos parámetro
	$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$numof=trim($_GET["numof"]);

	$fin=$numof+25;

	$params=[];

	$params[":i"] = $numof;
	$params[":m"] = 25;

	$db = new Conexion();
	$db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
	$dbTabla="Producto, Oferta, Tienda";
    $consulta = "SELECT * FROM $dbTabla WHERE Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt ORDER BY Oferta.fechap DESC LIMIT :i,:m";
    $result = $db->prepare($consulta);
	$result->execute($params);
	if (!$result){
		print "<p> Error en la consulta. </p>\n";
	}else{
		
		$ofertas = array();
		echo '{"oferta":';
		foreach($result as $valor){
			//Workaround to get estado
			$estado="";
			$dbTabla2="Oferta, Estado";
		    $consulta2 = "SELECT * FROM $dbTabla2 WHERE Oferta.estado=Estado.Idestado AND Oferta.Ido=:ido LIMIT 0,1";
		    $result2 = $db->prepare($consulta2);
			$result2->execute(array(":ido" => $valor['Ido']));
				foreach($result2 as $valor2){
					$estado=$valor2['nombreest'];
				}
			$time = DateTime::createFromFormat('Y-m-d H:i:s', $valor['fechap']);
		    $time = $time->format('U');

		    //get yourls clicks
		    if (strpos($valor['bitlink'], 'oxmf.club') !== false) {//if short url is form yourls
			
			    $short_url = array_pop(explode("/", $valor['bitlink']));//get only the last characters of url

			    $db3 = new Conexion_yourls();
				$dbTabla3="yourls_url";
			    $consulta3 = "SELECT * FROM $dbTabla3 WHERE yourls_url.keyword=:surl";
			    $result3 = $db3->prepare($consulta3);
				$result3->execute(array(":surl" => $short_url));
				foreach($result3 as $valor3){
					$clicks=$valor3['clicks'];
				}
			}else{
				$clicks=NULL;
			}
			$arr = array('Ido' => $valor['Ido'],'nombrep' => $valor['nombrep'], 'time' => $valor['fechap'],'time_ago' => time_ago( $time ),'time_title' => strftime( date( 'Y', $time ) == date( 'Y' ) ? TIMEBEFORE_FORMAT : TIMEBEFORE_FORMAT_YEAR, $time ), 'tienda' => $valor['nombre'], 'precioO' => $valor['precioO'], 'fprogram' => $valor['fprogram'], 'telegramid' => $valor['telegramid'], 'estado' => $estado, 'clicks' => $clicks);
			array_push($ofertas, $arr);
		}
		echo json_encode($ofertas);
		echo ',"range":"'.$numof.' - '.$fin.'"}';
	}
	$db=NULL;//Cerramos conexión
?>