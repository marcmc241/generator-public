<?PHP
require_once("conexion_PDO.php");
require_once('twitter.php');
//require_once("protege_basic.php");

function removeparameters($url){
	$url = strtok($url, '?');
	return $url;
}


function addafilliate($url){
	$err="";
	$iurl=$url;
	$db = new Conexion();
	$dbTabla="Tienda";
				$consulta = "SELECT COUNT(*) FROM $dbTabla"; 
				$result = $db->prepare($consulta);
				$result->execute();
				$total = $result->fetchColumn();

				if ($total>0) {

					$consulta = "SELECT * FROM $dbTabla";
					$result = $db->prepare($consulta);
					$result->execute();
					foreach ($result as $tienda){
						$pos=false;
						$pos = strpos($url, $tienda['busqueda']);
						if ($pos !== false) {
							//l'ha trobat
						     
						     	//buscar amazon
						     	$amazn=false;
								$amzn = strpos($url, "amazon");
								if ($amzn !== false) {
									//l'ha trobat
									//invertim l'ordre en els links d'amazon
								     $url=$url.$tienda['referido'];
								} else {
								    //no l'ha trobat
								    //posem l'ordre correcte
								    $url=$tienda['referido'].$url;
								}

								$tie = $tienda['Idt'];
						} else {
						    //no l'ha trobat
						}
					}
					if ($iurl==$url) {
						//no s'ha trobat cap coincidencia
						$err="No se ha encontrado la tienda (addafilliate)";
					}
	
				}else{
					$err="No se han podido obtener los datos de la bbdd (addafilliate)";
				}
				$db=NULL;
				return array($url, $tie, $err);
 }



function make_bitly_url($url)
{
	$signature = '';
	$api_url =  'https://oxmf.club/l/yourls-api.php';

	// Init the CURL session
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
	curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
	curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
	        'url' => $url,
	        'format'   => 'json',
	        'action'   => 'shorturl',
	        'signature' => $signature
	    ));

	// Fetch and return content
	$data = curl_exec($ch);
	curl_close($ch);

	$data = json_decode( $data );
	return $data->shorturl;
}

function buildoffer($ido)
{
	$templ="";
	$err="";
	$tweetcom="";
	$tweettxt="";
	$tweetimg="";
	$tweetenv="";
	$tweetgar="";
	//get template
	$db = new Conexion();
	$dbTabla="Configuracion";
	$consulta = "SELECT template FROM $dbTabla";
	$result = $db->prepare($consulta);
	$result->execute();
	if (!$result){
		$err="Error getting template (buildoffer)";
	}else{
		foreach($result as $template){
				$templ = $template['template'];
		}
	}
	//get offer values
	$dbTabla="Producto, Oferta, Tienda";
	$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
	$result = $db->prepare($consulta);
	$result->execute(array(":id" => $ido));
	if (!$result){
		$err="Error getting offer (buildoffer)";
	}else{
		foreach($result as $valor){
			$templ = str_replace("\$imagen", $valor['foto'], $templ);

			$templ = str_replace("\$nombre", $valor['nombrep'], $templ);

			if ($valor['color']==NULL||$valor['color']==0) {
				$templ = str_replace("\$color", "", $templ);
			}else{
				$dbTabla2 = "Colores";
				$consulta2 = "SELECT * FROM $dbTabla2 WHERE Idcolor=:idc";
				$result2 = $db->prepare($consulta2);
				$result2->execute(array(":idc" => $valor['color']));
				foreach($result2 as $valor2){
					$templ = str_replace("\$color", $valor2['nombre'], $templ);
				}
			}

			if ($valor['comentario']==NULL||$valor['comentario']==0) {
				$templ = str_replace("\$comentario", "", $templ);
			}else{
				$dbTabla2 = "Comentarios";
				$consulta2 = "SELECT * FROM $dbTabla2 WHERE Idcomentario=:idcm";
				$result2 = $db->prepare($consulta2);
				$result2->execute(array(":idcm" => $valor['comentario']));
				foreach($result2 as $valor2){
					$templ = str_replace("\$comentario", "\n\n      ".$valor2['texto'], $templ);
					$tweetcom="".$valor2['texto']." ";
				}
			}

			$templ = str_replace("\$descripcion", $valor['descripcion'], $templ);
			$templ = str_replace("\$tienda", $valor['nombre'], $templ);
			$templ = str_replace("\$precioh", $valor['precioH'], $templ);
			$templ = str_replace("\$precioo", $valor['precioO'], $templ);

			if ($valor['tipooferta']!=NULL) {
				$dbTabla2 = "TipoOferta";
				$consulta2 = "SELECT * FROM $dbTabla2 WHERE IdTipoOf=:idto";
				$result2 = $db->prepare($consulta2);
				$result2->execute(array(":idto" => $valor['tipooferta']));
				foreach($result2 as $valor2){
					if ($valor2['texto']!="" && $valor2['texto']!=NULL) {
						$templ = str_replace("\$tipooferta", "\n&#128203; ".$valor2['texto'], $templ);
					}
					$templ = str_replace("\$tipooferta", "", $templ);
				}
			}else{
				$templ = str_replace("\$tipooferta", "", $templ);
			}

			if ($valor['cupon']!=NULL) {
				$templ = str_replace("\$cupon", $valor['cupon'], $templ);
			}else{
				$templ = str_replace("\$cupon", "", $templ);
			}

			if ($valor['envio']==NULL||$valor['envio']==0) {
				$templ = str_replace("\$envio", "", $templ);
			}else{
				$dbTabla2 = "Envio";
				$consulta2 = "SELECT * FROM $dbTabla2 WHERE IdEnvio=:iden";
				$result2 = $db->prepare($consulta2);
				$result2->execute(array(":iden" => $valor['envio']));
				foreach($result2 as $valor2){
				$templ = str_replace("\$envio", "\n".$valor2['emoji']." Envío desde ".$valor2['nombre'], $templ);
				$tweetenv="Envío desde ".$valor2['nombre'].". ";
				}
			}

			if ($valor['garantia']==NULL||$valor['garantia']==0) {
				$templ = str_replace("\$garantia", "", $templ);
			}else{
				$dbTabla2 = "Garantia";
				$consulta2 = "SELECT * FROM $dbTabla2 WHERE IdGarantia=:idga";
				$result2 = $db->prepare($consulta2);
				$result2->execute(array(":idga" => $valor['garantia']));
				foreach($result2 as $valor2){
				$templ = str_replace("\$garantia", "\n".$valor2['emoji']." Garantía ".$valor2['nombre'], $templ);
				$tweetgar="Garantía en ".$valor2['nombre'].". ";
				}
			}

			if ($valor['com2']!=NULL) {
				$templ = str_replace("\$com2", "\n&#128196; ".$valor['com2'], $templ);
			}else{
				$templ = str_replace("\$com2", "", $templ);
			}
			$templ = str_replace("\$bitlink", $valor['bitlink'], $templ);
			$templ = str_replace("\$oxmflink", "/#/of/".$ido, $templ);

			$tweettxt=$tweetcom."#Oferta ".$valor['nombrep']." por ".$valor['precioO']."€ en ".$valor['nombre'].". ".$tweetenv.$tweetgar."https://oxmf.club/#/of/".$valor['Ido'];
			$tweetimg="https://oxmf.club/images_s/".$valor['foto'];
		}
		
	}
	$db=NULL;//Cerramos conexión
	return array($templ, $err, $valor['silencio'], $tweettxt, $tweetimg);
}

function publishoferta($ido,$where,$chat,$group_id,$bot_url,$user){//id offer/where(1=all/0=website only)
	
		
		if ($where==1) {//publish to telegram, twitter and web
			$offer=buildoffer($ido);
			$silencio=0;
			if($offer[2]==1){
				$silencio=1;
			}
			if ($offer[1]!="") {//hay error
				$err=$offer[1];
				return $err;
			}else{//no hay error
				$offermsg=$offer[0];
				$id=publicar($offermsg,$silencio,$chat,$group_id,$bot_url,$user);//publicar en telegram
				
				if($id!=false){//si es publica correctament

					$twid=tweet($offer[3],$offer[4]);//twittear
					//update status
					if($twid!=false){
						$db = new Conexion();
					    $dbTabla='Oferta';
					    $consulta = "UPDATE $dbTabla SET Oferta.estado=2, Oferta.telegramid=:tid, Oferta.twitterid=:twid WHERE Oferta.Ido=:ido";
					    $result = $db->prepare($consulta);
					    if($result->execute(array(":ido" => $ido,":tid" => $id,":twid" => $twid))){
					        //updated correctly
					        
					        return true;
					    }else{
					    	$err="No se ha podido actualizar el estado";
					      return $err;
					    }
					}else{//update state without twitter id
						$db = new Conexion();
					    $dbTabla='Oferta';
					    $consulta = "UPDATE $dbTabla SET Oferta.estado=2, Oferta.telegramid=:tid WHERE Oferta.Ido=:ido";
					    $result = $db->prepare($consulta);
					    if($result->execute(array(":ido" => $ido,":tid" => $id))){
					        //updated correctly
					    }
						$err="No se ha podido publicar en twitter / obtener id";
					      return $err;
					}
				}else{
					$err="No se ha podido publicar / obtener id";
				      return $err;
				}
			}

		}elseif ($where==0) {//publish only to web (update state only)
					$db = new Conexion();
				    $dbTabla='Oferta';
				    $consulta = "UPDATE $dbTabla SET Oferta.estado=2 WHERE Oferta.Ido=:ido";
				    $result = $db->prepare($consulta);
				    if($result->execute(array(":ido" => $ido))){
				        //updated correctly
				        $consulta = "SELECT * FROM Producto, Oferta, Tienda WHERE Oferta.Ido=:ido AND Oferta.tienda=Tienda.Idt AND Producto.Idp=Oferta.producto";
				        //send confirmation message
				        $result = $db->prepare($consulta);
				        if($result->execute(array(":ido" => $ido))){
				            foreach($result as $valor){
				            $message = "&#128309; ".$_SESSION["user"]." ha publicado una oferta en la web:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre']."\n\nGenial!";
				             $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
				             $data=file_get_contents($url);
				              header("location:principal.php?x=Oferta publicada en la web&y=g");
				            }
				          }
				        return true;
				    }else{
				    	$err="No se ha podido actualizar el estado";
				      return $err;
				    }
		}else{
			return "Faltan parametros";
		}
	
	$db=NULL;
}

function programoferta($ido,$dataprogram,$sil,$chat_id,$group_id,$bot_url,$user)//od oferta, data programación (datetime), silencio (1/0)
{
	if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0
		//no fer res
	}else{//is admin
		if (is_null($ido)||$ido==""||is_null($dataprogram)||$dataprogram==""||is_null($chat_id)||$chat_id==""||is_null($group_id)||$group_id==""||is_null($bot_url)||$bot_url==""||is_null($user)||$user=="") {
			return "Faltan parámetros";
		}//TODO COPY THIS TO ALL FUNCTIONS
		
		$db = new Conexion();
	  	$dbTabla='Oferta';
	    $consulta = "UPDATE Oferta SET estado=3, fprogram=:fe, silencio=:sil WHERE Oferta.Ido=:ido";
	    $result = $db->prepare($consulta);
	    if($result->execute(array(":ido" => $ido,":fe" => $dataprogram,":sil" => $sil))){
	    	$consulta = "SELECT * FROM Producto, Oferta, Tienda WHERE Oferta.Ido=:ido AND Oferta.tienda=Tienda.Idt AND Producto.Idp=Oferta.producto";
	    	//send confirmation message
		    $result = $db->prepare($consulta);
		    if($result->execute(array(":ido" => $ido))){
		    	foreach($result as $valor){
			       $message = "&#128309; Nueva oferta programada por ".$user."\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre']."\n\nSe publicará: ".$valor['fprogram'];
				   $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
				   $data=file_get_contents($url);
				}
				return true;
		    }else{
		    	return "Error en la consulta";
		    }
	        
	    }else{
	    	$err="No se ha podido programar";
	      return $err;
	    }
	}
	$db=NULL;
}

function desprogramoferta($ido,$chat_id,$group_id,$bot_url,$user)//od oferta, data programación (datetime), silencio (1/0)
{
	$dataprogram=null;
	$sil=null;
	if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0
		//no fer res
	}else{//is admin
		
		$db = new Conexion();
	  	$dbTabla='Oferta';
	    $consulta = "UPDATE Oferta SET estado=1, fprogram=:fe, silencio=:sil WHERE Oferta.Ido=:ido";
	    $result = $db->prepare($consulta);
	    if($result->execute(array(":ido" => $ido,":fe" => $dataprogram,":sil" => $sil))){
	    	$consulta = "SELECT * FROM Producto, Oferta, Tienda WHERE Oferta.Ido=:ido AND Oferta.tienda=Tienda.Idt AND Producto.Idp=Oferta.producto";
	    	//send confirmation message
		    $result = $db->prepare($consulta);
		    if($result->execute(array(":ido" => $ido))){
		    	foreach($result as $valor){
			       $message = "&#128309; ".$user." ha desprogramado la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre']."\n\nTendrá sus motivos &#129335;";
				   $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
				   $data=file_get_contents($url);
				}
		    }
	        return true;
	    }else{
	    	$err="No se ha podido desprogramar";
	      return $err;
	    }
	}
	$db=NULL;
}

function publicar($msg,$sil,$chat,$group_id,$bot_url,$user)//parameters: message to publish, silence (true/false)
{
	
		if ($chat!=NULL||$chat!="") {
			$chat=$chat;
		}else{
			$chat=$chat_id;
		}
		
		$msg = str_replace("\\", "", $msg);
		$message = "&#128309; Publicado por ".$user."\n\n".$msg;
	    $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
	    $data=file_get_contents($url);

		$url = "{$bot_url}sendMessage?chat_id={$chat}&text=".urlencode($msg)."&parse_mode=HTML&disable_notification=$sil";
	        $response  = json_decode(file_get_contents($url));
	        $message_id = null;
	        
		if (isset($response->result->message_id)) {
	            $message_id = $response->result->message_id;
	            return $message_id;
		}else{
	        return false;
	    }
	
}

function agotado($ido,$chat_id,$group_id,$bot_url,$user)
{
	$offer=buildoffer($ido);
	$offermsg=$offer[0];
	$offermsg = str_replace("images/", "images_s_ago/", $offermsg);
	$offermsg = str_replace("&#127919;", "&#10060;", $offermsg);
	$offermsg = str_replace("\\", "", $offermsg);
	if ($offer[1]!="") {//hay error
		$err=$offer[1];
		return $err;
	}else{//no hay error
		$db = new Conexion();
		$dbTabla="Producto, Oferta, Tienda";
		$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
		$result = $db->prepare($consulta);
		$result->execute(array(":id" => $ido));
		foreach ($result as $valor) {
			$message = "&#128309; ".$user." ha marcado como agotada la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre'];
			$url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
		    $data=file_get_contents($url);

			$url = "{$bot_url}editMessageText?chat_id={$chat_id}&message_id=".$valor['telegramid']."&text=".urlencode($offermsg)."&parse_mode=HTML";
		    $response  = json_decode(file_get_contents($url));

		    $consulta = "UPDATE Oferta SET Oferta.estado=4 WHERE Oferta.Ido=:ido";
		    $result = $db->prepare($consulta);
		    if($result->execute(array(":ido" => $ido))){
		        //updated correctly
		        return true;
		    }else{
		    	$err="No se ha podido actualizar el estado";
		      return $err;
		    }
		}
	}
	$db=NULL;
}

function noagotado($ido,$chat_id,$group_id,$bot_url,$user)
{
	$offer=buildoffer($ido);
	$offermsg=$offer[0];
	$offermsg = str_replace("\\", "", $offermsg);
	if ($offer[1]!="") {//hay error
		$err=$offer[1];
		return $err;
	}else{//no hay error
		$db = new Conexion();
		$dbTabla="Producto, Oferta, Tienda";
		$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
		$result = $db->prepare($consulta);
		$result->execute(array(":id" => $ido));
		foreach ($result as $valor) {
			$message = "&#128309; ".$user." ha marcado como NO agotada la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre'];
			$url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
		    $data=file_get_contents($url);

			$url = "{$bot_url}editMessageText?chat_id={$chat_id}&message_id=".$valor['telegramid']."&text=".urlencode($offermsg)."&parse_mode=HTML";
		    $response  = json_decode(file_get_contents($url));
		    $dbTabla='Oferta';

		    $consulta = "UPDATE Oferta SET Oferta.estado=2 WHERE Oferta.Ido=:ido";
		    $result = $db->prepare($consulta);
		    if($result->execute(array(":ido" => $ido))){
		        //updated correctly
		        return true;
		    }else{
		    	$err="No se ha podido actualizar el estado";
		      return $err;
		    }
		}
	}
	$db=NULL;
}

function editar($ido,$chat_id,$group_id,$bot_url,$user, $edit)
{
	$db = new Conexion();
	$link = $edit["link"];
    if (!$edit["linkoriginal"]) {//if not true
	    $link = removeparameters($link);//remove parameters after ?
	}else{
	    $link = $link;
	}
	$aff = addafilliate($link);//add afilliate & get tienda id
	$url = $aff[0];
	$idt = $aff[1];//id tienda
	$erraff = $aff[2];
	$bit = make_bitly_url($url);//crear bitlink

    $dbTabla="Oferta";//Update
	$consulta = "UPDATE $dbTabla SET Oferta.precioH=:ph, Oferta.precioO=:po, Oferta.cupon=:cup, Oferta.enlace=:link, Oferta.bitlink=:bitlink, Oferta.tienda=:tienda, Oferta.estadistica=:est, Oferta.color=:color, Oferta.comentario=:comentario, Oferta.tipooferta=:tipoof, Oferta.envio=:envio, Oferta.garantia=:garantia, Oferta.com2=:com2 WHERE Oferta.Ido=:id";
	$result = $db->prepare($consulta);
	if($result->execute(array(":id" => $ido, ":ph" => $edit["ph"], ":po" => $edit["po"], ":cup" => $edit["cup"], ":link" => $edit["link"], ":bitlink" => $bit, ":tienda" => $idt, ":est" => $edit["estad"], ":color" => $edit["col"], ":comentario" => $edit["com"], ":tipoof" => $edit["tipoof"], ":envio" => $edit["env"], ":garantia" => $edit["gar"], ":com2" => $edit["com2"]))){

		//volver a publicar si esta publicada o agotada
		$status;
		$dbTabla="Oferta";
		$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id";
		$result = $db->prepare($consulta);
		$result->execute(array(":id" => $ido));
		foreach ($result as $valor) {
			$status=$valor['estado'];
		}
		if ($status==2 || $status==4){//publicada o agotada: volver a publicar editada
			$offer=buildoffer($ido);
			$offermsg=$offer[0];
			$offermsg = str_replace("\\", "", $offermsg);
			if ($status==4) {//agotada
				$offermsg = str_replace("images/", "images_s_ago/", $offermsg);
				$offermsg = str_replace("&#127919;", "&#10060;", $offermsg);
			}
			if ($offer[1]!="") {//hay error
				$err=$offer[1];
				return $err;
			}else{//no hay error
				
				$dbTabla="Producto, Oferta, Tienda";
				$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
				$result = $db->prepare($consulta);
				$result->execute(array(":id" => $ido));
				foreach ($result as $valor) {
					$message = "&#128309; ".$user." ha editado la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre'];
					$url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
				    $data=file_get_contents($url);

					$url = "{$bot_url}editMessageText?chat_id={$chat_id}&message_id=".$valor['telegramid']."&text=".urlencode($offermsg)."&parse_mode=HTML";
				    $response  = json_decode(file_get_contents($url));
				    $dbTabla='Oferta';
				}
				return true;
			}
		}else{//sólo enviar confirmación
			$dbTabla="Producto, Oferta, Tienda";
			$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
			$result = $db->prepare($consulta);
			$result->execute(array(":id" => $ido));
			foreach ($result as $valor) {
				$message = "&#128309; ".$user." ha editado la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre'];
				$url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
			    $data=file_get_contents($url);
			}
			return true;
		}

	}else{
		$err="No se ha podido actualizar la oferta";
		return $err;
	}
	$db=NULL;
}

function eliminar($ido,$chat_id,$group_id,$bot_url,$user)
{
	$db = new Conexion();
	$dbTabla="Producto, Oferta, Tienda";
	$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
	$result = $db->prepare($consulta);
	$result->execute(array(":id" => $ido));
	foreach ($result as $valor) {
		$message = "&#128309; ".$user." ha eliminado la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre'];
		$url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
	    $data=file_get_contents($url);

	    if (is_numeric($valor['telegramid']) && $valor['telegramid']>0) {
	    	$url = "{$bot_url}deleteMessage?chat_id={$chat_id}&message_id=".$valor['telegramid'];
	    	$response  = json_decode(file_get_contents($url));
	    }else{
	    	$err="No se ha podido borrar en Telegram";
	    }
		

	    $deltw=deletetweet($valor['twitterid']);

	    $consulta = "UPDATE Oferta SET Oferta.estado=5, Oferta.estadistica=0, Oferta.fprogram=null WHERE Oferta.Ido=:ido";
	    $result = $db->prepare($consulta);
	    if($result->execute(array(":ido" => $ido))){
	        //updated correctly
	        if ($deltw) {
	        	return true;
	        }else{
	        	$err=$err."No se ha podido borrar en Twitter";
	        }
	        
	    }else{
	    	$err=$err."No se ha podido actualizar el estado";
	    }

	    if (isset($err)) {
	    	return $err;
	    }
	}
	$db=NULL;
}

function responder($ido,$msg,$chat_id,$group_id,$bot_url,$user)
{
	$db = new Conexion();
	$dbTabla="Producto, Oferta, Tienda";
	$consulta = "SELECT * FROM $dbTabla WHERE Oferta.Ido=:id AND Producto.Idp=Oferta.producto AND Oferta.tienda=Tienda.Idt";
	$result = $db->prepare($consulta);
	if($result->execute(array(":id" => $ido))){
		foreach ($result as $valor) {
			$message = "&#128309; ".$user." ha respondido a la oferta:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre']."\n\n".$msg;
			$url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
		    $data=file_get_contents($url);

			$url = "{$bot_url}sendMessage?chat_id={$chat_id}&reply_to_message_id=".$valor['telegramid']."&text=".urlencode($msg)."&parse_mode=HTML";
		    $response  = json_decode(file_get_contents($url));

		    return true;
		}
	}else{
    	$err="No se ha encontrado la oferta en la BBDD";
      	return $err;
	}
	
	$db=NULL;
}

?>