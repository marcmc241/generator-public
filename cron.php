<?PHP
    require_once("conexion_PDO.php");
    require_once("configuration.php");
    require_once("functions.php");
   
    $now=date('Y-m-d H:i:s');
    $db = new Conexion();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbTabla="Oferta, Usuarios";
	$consulta = "SELECT * FROM $dbTabla WHERE fprogram<=:n AND estado=3 AND Oferta.usuario=Usuarios.Idu ORDER BY Oferta.Ido ASC";
	$result = $db->prepare($consulta);
    print("hora: ".$now);
	if($result->execute(array(":n" => $now))){
        foreach ($result as $oferta){

            publishoferta($oferta["Ido"],"1",$oferta["chat"],$group_id,$bot_url,$oferta["user"]);
            
        }
    }
?>