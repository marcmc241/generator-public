<?PHP
	if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	require_once("protege.php");
    require_once("functions.php");
    require_once("configuration.php");
    $user=$_SESSION["user"];
    //TODO: GET HERE BY AJAX
    //add all functions of center list
    $whattodo = 0;
	if(isset($_GET['w'])){//1:publicar mensaje/2:programar oferta/3:desprogramar oferta/4:publicar oferta
        $whattodo = trim($_GET['w']);
    }
    
	/*$sil = $_POST["checkbox2"];
	if ($sil=='on') {
        $dis=true;
    }else{
     	$dis=false;
    }*/
    if ($whattodo==1) {//1:publicar mensaje
        $msg = '';
        if(isset($_POST["msg"])){
            $msg = trim($_POST["msg"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (msg)&y=r");
            exit();
        }
        $sil = false;
        $id = publicar($msg,$sil,$chat_id,$group_id,$bot_url,$user);
        if($id != false){
            header("location:principal.php?x=Publicado correctamente&y=g");
        }else{
            header("location:principal.php?x=$id&y=r");
        }

    }elseif ($whattodo==2) {//2:programar oferta
        $ido='';
        $dataprogram='';
        if(isset($_POST["ido"]) && isset($_POST["dataprogram"])){
            $ido = trim($_POST["ido"]);
            $dataprogram = $_POST["dataprogram"];
        }else{
            header("location:principal.php?x=Error en los parámetros (ido/fecha)&y=r");
            exit();
        }
        $sil=0;
        if(isset($_POST["checkboxprooflist"])){
            $sil = trim($_POST["checkboxprooflist"]);
            if ($sil=='on') {
                $sil=1;
            }else{
                $sil=0;
            }
        }
        
        $res = programoferta($ido,$dataprogram,$sil,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Programado correctamente&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==3) {//3:desprogramar oferta
        $ido='';
        if(isset($_POST["ido"])){
            $ido = trim($_POST["ido"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (ido)&y=r");
            exit();
        }

        $res = desprogramoferta($ido,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Desprogramado correctamente&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==4) {//4:publicar oferta
        $ido='';
        if(isset($_POST["ido"])){
            $ido = trim($_POST["ido"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (ido)&y=r");
            exit();
        }
        
        $sil=0;
        if(isset($_POST["checkboxpublicaofertalist"])){
            $sil = trim($_POST["checkboxpublicaofertalist"]);
            if ($sil=='on') {
                $sil=1;
            }else{
                $sil=0;
            }
        }

        if (isset($_POST['web'])) {//publish only web
            $where=0;
        } else {//all
            $where=1;
        }

        $res = publishoferta($ido,$where,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Publicado correctamente&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==5) {//5:marcar agotado
        $ido='';
        if(isset($_POST["ido"])){
            $ido = trim($_POST["ido"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (ido)&y=r");
            exit();
        }

        $res = agotado($ido,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Marcado como agotado&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==6) {//6:desmarcar agotado
        $ido='';
        if(isset($_POST["ido"])){
            $ido = trim($_POST["ido"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (ido)&y=r");
            exit();
        }

        $res = noagotado($ido,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Marcado como NO agotado&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==7) {//7:eliminar
        $ido='';
        if(isset($_POST["ido"])){
            $ido = trim($_POST["ido"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (ido)&y=r");
            exit();
        }

        $res = eliminar($ido,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Oferta eliminada&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==8) {//8:responder
        $ido='';
        $msg = '';
        if(isset($_POST["ido"]) && isset($_POST["msg"])){
            $ido = trim($_POST["ido"]);
            $msg = $_POST["msg"];
        }else{
            header("location:principal.php?x=Error en los parámetros (ido/msg)&y=r");
            exit();
        }
        
        $res = responder($ido,$msg,$chat_id,$group_id,$bot_url,$user);

        if($res == true){
            header("location:principal.php?x=Respuesta publicada&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
    }elseif ($whattodo==9) {//9:editar
        $ido='';
        if(isset($_POST["eido"])){
            $ido = trim($_POST["eido"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (ido)&y=r");
            exit();
        }
        
        $color='';
        if(isset($_POST["ecolor"])){
            $color = trim($_POST["ecolor"]);
        }
        $comentario='';
        if(isset($_POST["ecomentario"])){
            $comentario = trim($_POST["ecomentario"]);
        }
        $link='';
        if(isset($_POST["elink"]) && filter_var($_POST["elink"], FILTER_VALIDATE_URL)){
            $link = trim($_POST["elink"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (link)&y=r");
            exit();
        }

        $preciohabitual=0;
        if(isset($_POST["epreciohabitual"]) && is_numeric($_POST['epreciohabitual'])){
            $preciohabitual = trim($_POST["epreciohabitual"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (PH)&y=r");
            exit();
        }

        $preciooferta=0;
        if(isset($_POST["epreciooferta"]) && is_numeric($_POST['epreciooferta'])){
            $preciooferta = trim($_POST["epreciooferta"]);
        }else{
            header("location:principal.php?x=Error en los parámetros (PO)&y=r");
            exit();
        }

        $tipooferta=0;
        if(isset($_POST["etipooferta"]) && is_numeric($_POST['etipooferta'])){
            $tipooferta = trim($_POST["etipooferta"]);
        }

        $cupon='';
        if(isset($_POST["ecupon"])){
            $cupon = trim($_POST["ecupon"]);
        }

        $envio=0;
        if(isset($_POST["eenvio"]) && is_numeric($_POST['eenvio'])){
            $envio = trim($_POST["eenvio"]);
        }

        $garantia=0;
        if(isset($_POST["egarantia"]) && is_numeric($_POST['egarantia'])){
            $garantia = trim($_POST["egarantia"]);
        }

        $comentario2='';
        if(isset($_POST["ecomentario2"])){
            $comentario2 = trim($_POST["ecomentario2"]);
        }

        $estad=1;
        if(isset($_POST["echeckbox"])){
            $estad = trim($_POST["echeckbox"]);
            if ($estad=='on'){//change checkbox 'on' to binary
                $estad=1;
            }else{
                $estad=0;
            }
        }
        $linkoriginal=false;
        if(isset($_POST["echeckbox2"])){
            $linkoriginal = trim($_POST["echeckbox2"]);
            if ($linkoriginal=='on') {
                $linkoriginal=true;
            }else{
                $linkoriginal=false;
            }
        }

        $edit = array("col" => $color,
                    "com" => $comentario,
                    "link" => $link,
                    "ph" => $preciohabitual,
                    "po" => $preciooferta,
                    "tipoof" => $tipooferta,
                    "cup" => $cupon,
                    "env" => $envio,
                    "gar" => $garantia,
                    "com2" => $comentario2,
                    "estad" => $estad,
                    "linkoriginal" => $linkoriginal
            );

        $res = editar($ido,$chat_id,$group_id,$bot_url,$user,$edit);

        if($res == true){
            header("location:principal.php?x=Editado correctamente&y=g");
        }else{
            header("location:principal.php?x=$res&y=r");
        }
        
    }else{
        header("location:principal.php?x=Error en los parámetros&y=r");
    }
?>