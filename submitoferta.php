<?PHP
  session_start();
  require_once("protege_basic.php");
  require_once("conexion_PDO.php");
  require_once("functions.php");
  require_once("configuration.php");

$idp=0;
if(isset($_POST['idpform']) && is_numeric($_POST['idpform'])){
  $idp = trim($_POST['idpform']);
}
$color=0;
if(isset($_POST['color']) && is_numeric($_POST['color'])){
  $color = trim($_POST['color']);
}
$comentario=0;
if(isset($_POST['comentario']) && is_numeric($_POST['comentario'])){
  $comentario = trim($_POST['comentario']);
}
$link='';
if(isset($_POST['link'])&&filter_var($_POST['link'], FILTER_VALIDATE_URL)){
  $link = trim($_POST['link']);
}
$preciohabitual=0;
if(isset($_POST['preciohabitual']) && is_numeric($_POST['preciohabitual'])){
  $preciohabitual = trim($_POST['preciohabitual']);
}
$preciooferta=0;
if(isset($_POST['preciooferta']) && is_numeric($_POST['preciooferta'])){
  $preciooferta = trim($_POST['preciooferta']);
}
$tipooferta=0;
if(isset($_POST['tipooferta']) && is_numeric($_POST['tipooferta'])){
  $tipooferta = trim($_POST['tipooferta']);
}
$cupon='';
if(isset($_POST['cupon'])){
  $cupon = trim($_POST['cupon']);
}
$envio=0;
if(isset($_POST['envio']) && is_numeric($_POST['envio'])){
  $envio = trim($_POST['envio']);
}
$garantia=0;
if(isset($_POST['garantia']) && is_numeric($_POST['garantia'])){
  $garantia = trim($_POST['garantia']);
}
$comentario2='';
if(isset($_POST['comentario2'])){
  $comentario2 = trim($_POST['comentario2']);
}

if ($idp==0 || $preciohabitual==0 || $preciooferta==0) {
  header("location:principal.php?x=Algunos parámetros no son válidos&y=r");
  exit();
}


$estadisticas='on';
if(isset($_POST['checkbox'])){
  $estadisticas = $_POST["checkbox"];
}
$linkoriginal='off';
if(isset($_POST['checkbox2'])){
  $linkoriginal = $_POST["checkbox2"];
}
$silencio='off';
if(isset($_POST['checkbox3'])){
  $silencio = $_POST["checkbox3"];
}

$dataprogram=NULL;
if (isset($_POST['publicarweb'])) {//clicked btn publicar
      $pubprogmov=0;
    } else if (isset($_POST['publicarall'])){//clicked btn publicar
      $pubprogmov=1;
    } else if (isset($_POST['programar'])){//clicked btn programar
      $pubprogmov=2;
      $dataprogram = $_POST["dataprogram"];
    } else {
       //assume mover a pendientes
      $pubprogmov=3;
    }

if ($estadisticas=='on') {//change checkbox 'on' to binary
    $estadisticas=1;
}else{
    $estadisticas=0;
}
if ($linkoriginal=='on') {
    $linkoriginal=true;
}else{
    $linkoriginal=false;
}if ($silencio=='on') {
    $silencio=1;
}else{
    $silencio=0;
}
//print("$idp<br>$color<br>$comentario<br>$link<br>$preciohabitual<br>$preciooferta<br>$tipooferta<br>$cupon<br>$envio<br>$garantia<br>$comentario2<br>$estadisticas<br>$linkoriginal<br>");

$link = filter_var($link, FILTER_SANITIZE_URL);

if (filter_var($link, FILTER_VALIDATE_URL)){
  if (!$linkoriginal) {//if not true
    $link = removeparameters($link);//remove parameters after ?
  }else{
    $link = $link;
  }
  $aff = addafilliate($link);//add afilliate & get tienda id
  $url = $aff[0];
  $idt = $aff[1];//id tienda
  $erraff = $aff[2];
  $bit = make_bitly_url($url);//crear bitlink
  
  $fechap = date("Y-m-d H:i:s");//generate fechap
  $idu = $_SESSION["idu"];//get user id
  $est = 1;//estado es siempre 1 (pendiente) al principio

  //if user is admin and program is active set time & change estado

  $db = new Conexion();
  
  $dbTabla='Oferta';
  $consulta = "INSERT INTO Oferta(producto,precioH,precioO,cupon,enlace,bitlink,tienda,estadistica,fechap, usuario, color, comentario, tipooferta, envio, garantia, com2, estado, fprogram, chat, telegramid, silencio) VALUES (:prod,:ph,:po,:cu,:li,:bl,:ti,:es,:fe,:idu,:col,:com,:tip,:envi,:gara,:com2,:est,:fprog,:chat,:tid,:sil)";
  $result = $db->prepare($consulta);
  if($result->execute(array(":prod" => $idp,":ph" => $preciohabitual,":po" => $preciooferta,":cu" => $cupon,":li" => $link,":bl" => $bit,":ti" => $idt,":es" => $estadisticas,":fe" => $fechap,":idu" => $idu,":col" => $color,":com" => $comentario,":tip" => $tipooferta,":envi" => $envio,":gara" => $garantia,":com2" => $comentario2,":est" => $est,":fprog" => NULL,":chat" => $chat_id,":tid" => NULL,":sil" => $silencio))){
    
        $lastid = $db->lastInsertId();
        //saved correctly
        //PUBLISH
        if ($pubprogmov==0||$pubprogmov==1) {
          $res = publishoferta($lastid,$pubprogmov,$chat_id,$group_id,$bot_url,$_SESSION["user"]);
          if($res != true){
            header("location:principal.php?x=$res&y=r");
          }else{
            header("location:principal.php?x=Publicado correctamente&y=g");
          }
        }else if ($pubprogmov==2&&$dataprogram!=NULL) {
          $res = programoferta($lastid,$dataprogram,$silencio,$chat_id,$group_id,$bot_url,$_SESSION["user"]);
          if($res != true){
            header("location:principal.php?x=$res&y=r");
          }else{
            header("location:principal.php?x=Programado Correctamente&y=g");
          }
        }else{
          $consulta = "SELECT * FROM Producto, Oferta, Tienda WHERE Oferta.Ido=:ido AND Oferta.tienda=Tienda.Idt AND Producto.Idp=Oferta.producto";
          //send confirmation message
          $result = $db->prepare($consulta);
          if($result->execute(array(":ido" => $lastid))){
            foreach($result as $valor){
            $message = $_SESSION["user"]." ha creado una nueva oferta y está pendiente de publicar o programar:\n\n".$valor['nombrep']." a ".$valor['precioO']."€ en ".$valor['nombre']."\n\nGenial!";
             $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
             $data=file_get_contents($url);
              header("location:principal.php?x=Oferta movida a pendientes&y=g");
            }
          }
        }
    }else{
      header("location:principal.php?x=No se han podido guardar los datos&y=r");
    }
  
}else{
  header("location:principal.php?x=El link no es válido&y=r");
}
?>