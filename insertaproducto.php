<?PHP
  session_start();
  require_once("protege.php");
  require_once("conexion_PDO.php");
  require_once("configuration.php");
  $what=$_GET["w"];

if ($what==1) {//add product
  $nombre = $_POST["addprodnombre"];
  $descripcion = $_POST["addproddescripcion"];
  $categoria = $_POST["addprodcategoria"];
  $tags = $_POST["addprodtags"];

    if ((($_FILES["addprodfoto"]["type"] == "image/png") || ($_FILES["addprodfoto"]["type"] == "image/jpeg") || ($_FILES["addprodfoto"]["type"] == "image/pjpeg")) && ($_FILES["addprodfoto"]["size"] > 100) && ($_FILES["addprodfoto"]["size"] < 1572864)){
        if ($_FILES["addprodfoto"]["error"] > 0){
          header("location:principal.php?x=Imagen: error código: " . $_FILES["addprodfoto"]["error"]);
        }else{
          $origen=$_FILES["addprodfoto"]["tmp_name"];
          $path_parts = pathinfo($_FILES["addprodfoto"]["name"]);
          $extension=$path_parts['extension'];
          $date=date("YmdHis");
          $nom=$date.".".$extension;
          $source="images";
          $desti="../".$source."/".$nom;
            if (file_exists($desti)){
              header("location:principal.php?x=".$desti. " ya existe.");
            }else{
              
              if(move_uploaded_file($origen,$desti)){
                if(generatethumbnails($source,$nom, $bot_url, $group_id)){

                      //insertar en BBDD
                  $db = new Conexion();
                  $dbTabla='Producto';
                    $a=1;
                    $data = date("Y-m-d H:i:s");
                    $consulta = "INSERT INTO $dbTabla (nombrep, descripcion, foto, categoria, activo, fechap, tags) VALUES (:n, :de, :f, :t, :a, :da, :tag)";
                    $result = $db->prepare($consulta);
                    if ($result->execute(array(":n" => $nombre, ":de" => $descripcion, ":f" => $nom, ":t" => $categoria, ":a" => $a, ":da" => $data, ":tag" => $tags))){
                    $last_id = $db->lastInsertId();
                    $db=NULL;
                    //print("Datos insertados correctamente. Id: $last_id<br><br>");
                    header("location:principal.php?x=Datos insertados correctamente&y=g");
                        
                        //print("Foto 1 subida correctamente.<br><br>");
         
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


}else if ($what==2) {//edit product
  $idp = $_POST["editprodid"];
  $nombre = $_POST["editprodnombre"];
  $descripcion = $_POST["editproddescripcion"];
  $categoria = $_POST["editprodcategoria"];
  $tags = $_POST["editprodtags"];
  $dbTabla='Producto';
  $a=1;
  $db = new Conexion();
  if (!isset($_POST['editprodeliminar'])) {//edit
      
    
    if ((($_FILES["editprodfoto"]["type"] == "image/png") || ($_FILES["editprodfoto"]["type"] == "image/jpeg") || ($_FILES["editprodfoto"]["type"] == "image/pjpeg")) && ($_FILES["editprodfoto"]["size"] > 100) && ($_FILES["editprodfoto"]["size"] < 1572864)){
        if ($_FILES["editprodfoto"]["error"] > 0){
          header("location:principal.php?x=Imagen: error código: " . $_FILES["editprodfoto"]["error"]);
        }else{
          $origen=$_FILES["editprodfoto"]["tmp_name"];
          $path_parts = pathinfo($_FILES["editprodfoto"]["name"]);
          $extension=$path_parts['extension'];
          $date=date("YmdHis");
          $nom=$date.".".$extension;
          $source="images";
          $desti="../".$source."/".$nom;
            if (file_exists($desti)){
              header("location:principal.php?x=".$desti. " ya existe.");
            }else{
              $consulta = "SELECT * FROM $dbTabla WHERE Idp=:idp";
              $result = $db->prepare($consulta);
              if ($result->execute(array(":idp" => $idp))){
                foreach ($result as $value) {
                  $nom_old=$value["foto"];
                  unlink("../".$source."/".$nom_old);//Delete old image and thumbnails
                  unlink("../".$source."_s/".$nom_old);
                  unlink("../".$source."_s_ago/".$nom_old);
                  print("unlinked ../".$source."/".$nom_old."  ../".$source."_s/".$nom_old."  ../".$source."_s_ago/".$nom_old);
                }
              }

              if(move_uploaded_file($origen,$desti)){
                if(generatethumbnails($source,$nom, $bot_url, $group_id)){

                      //insertar en BBDD
                    $consulta = "UPDATE $dbTabla SET nombrep=:n, descripcion=:de, foto=:f, categoria=:t, activo=:a, tags=:tag WHERE Idp=:id";
                    $result = $db->prepare($consulta);
                    if ($result->execute(array(":n" => $nombre, ":de" => $descripcion, ":f" => $nom, ":t" => $categoria, ":a" => $a, ":id" => $idp, ":tag" => $tags))){
                    $last_id = $db->lastInsertId();
                    
                    //print("Datos insertados correctamente. Id: $last_id<br><br>");
                    header("location:principal.php?x=Datos actualizados correctamente&y=g");
                        
                        //print("Foto 1 subida correctamente.<br><br>");
         
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
        $consulta = "UPDATE $dbTabla SET nombrep=:n, descripcion=:de, categoria=:t, activo=:a, tags=:tag WHERE Idp=:id";
        $result = $db->prepare($consulta);
        if ($result->execute(array(":n" => $nombre, ":de" => $descripcion, ":t" => $categoria, ":a" => $a, ":id" => $idp, ":tag" => $tags))){
          header("location:principal.php?x=Actualizado sin cambiar imagen&y=g");
        }
      }

  }else if (isset($_POST['editprodeliminar'])) {//delete
    $a=0;
    $consulta = "UPDATE $dbTabla SET activo=:a WHERE Idp=:id";
    $result = $db->prepare($consulta);
    if ($result->execute(array(":a" => $a, ":id" => $idp))){
        header("location:principal.php?x=Producto eliminado&y=g");
    }
  }
  $db=NULL;
}


function generatethumbnails($source,$file, $bot_url, $group_id){
  //print("$source,$file");
       if(resize(600, $source.'_s/'.$file, '../'.$source.'/'.$file, '../'.$source.'/',$file, $bot_url, $group_id)&&
       resizeAndAgotado(600, $source.'_s_ago/'.$file, '../'.$source.'/'.$file, '../'.$source.'/',$file, $bot_url, $group_id)){
        return true;
       }else{
        return false;
       }
       
}
      
function resize($newWidth, $targetFile, $originalFile, $path, $file, $bot_url, $group_id) {

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
             $message = "Anda! ".$_SESSION["user"]." acaba de añadir o editar un producto<a href='http://oxmf.club/$targetFile'>!</a>";
             $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
             file_get_contents($url);
             
             return true;
           }
           
       return false;
}

function resizeAndAgotado($newWidth, $targetFile, $originalFile, $path, $file, $bot_url, $group_id) {

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

           imagefilter($img, IMG_FILTER_GRAYSCALE);//convert to grayscale
           $stamp = imagecreatefrompng('img/agotado.png');

           list($width, $height) = getimagesize($originalFile);

           $newHeight = ($height / $width) * $newWidth;
           $tmp = imagecreatetruecolor($newWidth, $newHeight);
           imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

           $marge_right = 0;
              $marge_bottom = 10;
              $sx = imagesx($stamp);
              $sy = imagesy($stamp);
              imagecopy($tmp, $stamp, imagesx($tmp) - $sx - $marge_right, imagesy($tmp) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

           if (file_exists('../'.$targetFile)) {
                   unlink('../'.$targetFile);
           }
            if($image_save_func($tmp, "../$targetFile")){
               $message = "Aquí también hay la imagen de agotado<a href='http://oxmf.club/$targetFile'>:</a>";
               $url = $bot_url."sendMessage?chat_id=".$group_id."&text=".urlencode($message)."&parse_mode=HTML";
               file_get_contents($url);
               return true;
            }

      return false;
}
?> 
