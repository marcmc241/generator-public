<?PHP
	require_once("conexion_PDO.php");
	$block=0;//al principio no bloqueamos la entrada por defecto
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	$ipaddress = '';//buscamos la ip del cliente
		    if (isset($_SERVER['HTTP_CLIENT_IP']))
		        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		    else if(isset($_SERVER['HTTP_X_FORWARDED']))
		        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		    else if(isset($_SERVER['HTTP_FORWARDED']))
		        $ipaddress = $_SERVER['HTTP_FORWARDED'];
		    else if(isset($_SERVER['REMOTE_ADDR']))
		        $ipaddress = $_SERVER['REMOTE_ADDR'];
		    else
		        $ipaddress = 'UNKNOWN';

		$data = date("Y-m-d H:i:s");
		$id=session_id();//buscamos la sessionID
		$db = new Conexion();
		$dbTabla='Accesosgenerator';
		$consulta2 = "SELECT COUNT(*) FROM $dbTabla WHERE IP=:ip AND fecha>= DATE_SUB(NOW(), INTERVAL 30 minute) OR idSesion=:ids AND fecha>= DATE_SUB(NOW(), INTERVAL 30 minute)";//seleccionamos todos los registros con esa IP o esa sessionID en los últimos 30 min
		$result2 = $db->prepare($consulta2);

		if ($result2->execute(array(":ip" => $ipaddress,":ids" => $id))){
		 		$total = $result2->fetchColumn();
		 		
		 		if ($total>=3) {//si hay más de 3 registros de fallos con esa IP o session ID
		 			print("<br><br><p>Access denied for 30 minutes due to multiple failed login attempts</p>");
		 			$block=1;//bloqueamos
		 		}
				
		} else{
				$block=1;//si no funciona la consulta bloqueamos JIC
		}


		$consulta3 = "SELECT COUNT(*) FROM $dbTabla WHERE fecha>= DATE_SUB(NOW(), INTERVAL 30 minute)";
		$result3 = $db->prepare($consulta3);
		if ($result3->execute()){//seleccionamos todos los intentos de entrada fallidos en los últimos 30 min (independientemente de IP o sesionID)
			$total = $result3->fetchColumn();
			if ($total>=10) {//si hay mas de 10
				print("<br><br><p>Access denied for 30 minutes due to multiple failed login attempts from various users</p>");
		 			$block=1;//bloqueamos
			}
		}else{
			$block=1;//si no funciona la consulta bloqueamos JIC
		}
if ($block!=1) {
			//recojemos user y pass
			$user = $_POST["user"];
			$pas = $_POST["pass"];
			
			
			$dbTabla='Usuarios';
		//comparamos user y pass por si coinciden con alguno de la DB
			$consulta = "SELECT COUNT(*) FROM $dbTabla WHERE user=:u AND pass=:p  AND activo=1"; 
			$result = $db->prepare($consulta);
			$result->execute(array(":u" => $user, ":p" => md5($pas)));
			$total = $result->fetchColumn();

			if($total==1){//si hay una coincidencia

					$consulta = "SELECT * FROM $dbTabla WHERE user=:u AND pass=:p AND activo=1"; 
					$result = $db->prepare($consulta);
					$result->execute(array(":u" => $user, ":p" => md5($pas)));
					
					if (!$result) { //si no hay resultado lo devolvemos a inicio
						header("location:index.php?x=1");
					}else{//si hay resultado
						if (session_status() == PHP_SESSION_NONE) {
						    session_start();
						}
						
						$fila=$result->fetchObject();
						
						$_SESSION["idu"] = $fila->Idu;//guardamos parametros en sesion para que lo identifiquemos como logueado
						$_SESSION["user"] = $fila->user;
						$_SESSION["admin"] = $fila->admin;
						if ($fila->admin==1) {
							header("location:principal.php?x=1");//le llevamos a la pagina principal
						}else{
							header("location:principal.php?x=4");//le llevamos a la pagina principal
						}
						
					}
			}else{//si no hay coincidencia
				$dbTabla='Accesosgenerator';
				$consulta2 = "INSERT INTO $dbTabla(IP, fecha, idSesion, usuario) VALUES (:ip, :fe, :ids, :u)";
				$result2 = $db->prepare($consulta2);
				if ($result2->execute(array(":ip" => $ipaddress,":fe" => $data,":ids" => $id,":u" => $user)))//guardamos ip, fecha, sessionID y nomre de usuario introducido en la DB
				 	{ 
					header("location:index.php?x=1"); //le llevamos al inicio
				} else{
					header("location:index.php?x=1"); 
					}
			}
}

	
?>