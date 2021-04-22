<?PHP
session_start();
require_once("protege.php");
require_once("conexion_PDO.php");

if ((isset($_POST["promotxt"])) && ($_POST["promotxt"]!=NULL)) {//update
    
    $prm=strip_tags(trim($_POST["promotxt"]),"<b><i><a><code><p>");
    $prm = str_replace('"', "'", $prm);
    $prm = str_replace(array("\n", "\r"), '', $prm);
    if ((($_FILES["promoimg"]["type"] == "image/png") || ($_FILES["promoimg"]["type"] == "image/jpeg") || ($_FILES["promoimg"]["type"] == "image/pjpeg")) && ($_FILES["promoimg"]["size"] > 100) && ($_FILES["promoimg"]["size"] < 1572864)){
        if ($_FILES["promoimg"]["error"] > 0){
            header("location:principal.php?x=Imagen: error código: " . $_FILES["promoimg"]["error"]);
        }else{
            $origen=$_FILES["promoimg"]["tmp_name"];
            $path_parts = pathinfo($_FILES["promoimg"]["name"]);
            $extension=$path_parts['extension'];
            $date=date("YmdHis");
            $nom=$date.".".$extension;
            $source="images";
            $desti="../".$source."/".$nom;
            if (file_exists($desti)){
                header("location:principal.php?x=".$desti. " ya existe.");
            }else{
                
                if(move_uploaded_file($origen,$desti)){
                if(generatethumbnails($source,$nom)){

                        //insertar en BBDD
                    $db = new Conexion();
                    $dbTabla='Configuracion';
                    $consulta = "SELECT * FROM $dbTabla WHERE Idconfig=4";
                    $result = $db->prepare($consulta);
                    if ($result->execute()){
                        foreach ($result as $value) {
                            $nom_old=$value["promoimg"];
                            if(!empty($nom_old)){
                                unlink("../".$source."/".$nom_old);//Delete old image and thumbnails
                                unlink("../".$source."_s/".$nom_old);
                            }
                        }

                        $consulta = "UPDATE $dbTabla SET promotxt=:prm, promoimg=:pimg WHERE Idconfig=4";
                        $result = $db->prepare($consulta);
                        if ($result->execute(array(":prm" => $prm,":pimg" => $nom))){
                            
                            header("location:principal.php?x=Datos insertados correctamente&y=g");
                            exit();
                        }else{
                            
                            header("location:principal.php?x=Error al insertar los datos&y=r");
                            exit();
                        }
                        
                    }else{
                        header("location:principal.php?x=Los datos no pudieron ser guardados en la DB&y=r");
                        unlink($desti);//borramos la foto original para que no ocupe espacio inútil
                    }
                }else{
                    unlink($desti);//borramos la foto original para que no ocupe espacio inútil
                    header("location:principal.php?x=No se pudieron generar los thumbnails&y=r");
                }
                }else{
                header("location:principal.php?x=No se puede guardar en: ". $desti."&y=r");
                }
            }
        }
        
    }else{
    header("location:principal.php?x=La imagen no es un fichero válido&y=r");
    //echo "La foto 1 no es un fichero válido.";
	}
	$db=NULL;
			
}else if(trim($_POST["promotxt"])==""){
	$db = new Conexion();
    $dbTabla='Configuracion';
    $consulta = "UPDATE $dbTabla SET promotxt=:prm, promoimg=:pimg WHERE Idconfig=4";
    $result = $db->prepare($consulta);
    if ($result->execute(array(":prm" => "",":pimg" => ""))){
        
        header("location:principal.php?x=Banner ocultado&y=g");
        exit();
    }else{
        
        header("location:principal.php?x=Error al ocultar el banner&y=r");
        exit();
    }
}else{
	header("location:principal.php?x=Error&y=r");
    exit();
}

    function generatethumbnails($source,$file){
        //print("$source,$file");
             if(resize(800, $source.'_s/'.$file, '../'.$source.'/'.$file, '../'.$source.'/',$file)){
              return true;
             }else{
              return false;
             }
             
      }
            
      function resize($newWidth, $targetFile, $originalFile, $path, $file) {
      
                 $info = getimagesize($originalFile);
                 $mime = $info['mime'];
      
                 switch ($mime) {
                         case 'image/jpeg':
                                 $image_create_func = 'imagecreatefromjpeg';
                                 $image_save_func = 'imagejpeg';
                                 $new_image_ext = 'jpg';
                                 break;
      
                         case 'image/png':
                                 $image_create_func = 'imagecreatefrompng';
                                 $image_save_func = 'imagepng';
                                 $new_image_ext = 'png';
                                 break;
      
                         default: 
                                 //throw new Exception('Unknown image type.');
                 }
      
                 $img = $image_create_func($originalFile);
                 list($width, $height) = getimagesize($originalFile);
      
                 $newHeight = ($height / $width) * $newWidth;
                 $tmp = imagecreatetruecolor($newWidth, $newHeight);
                 imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
      
                 if (file_exists('../'.$targetFile)) {
                         unlink('../'.$targetFile);
                 }
                 if($image_save_func($tmp, "../$targetFile")){
                   
                   
                   return true;
                 }
                 
             return false;
      }
    ?>