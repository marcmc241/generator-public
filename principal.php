<?PHP
	if (session_status() == PHP_SESSION_NONE) {
		ini_set('session.gc_maxlifetime', 3600);
		session_set_cookie_params(3600);
	    session_start();
	}
	require_once("protege_basic.php");
	require_once("conexion_PDO.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Generator Neon</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Copyright 2016-17, Marc Masip-->
<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<?PHP
	$db = new Conexion();
	$dbTabla="Configuracion";
    $consulta = "SELECT template FROM $dbTabla";
    $result = $db->prepare($consulta);
    $result->execute();
	foreach ($result as $template){
		$proc=preg_replace( "/\r|\n/", "", nl2br($template[0]));
		print("<script>var original = '".$proc."'</script>");
	}
	$db=NULL;
	$x=0;
	if(isset($_GET['x'])){
		$x = trim($_GET['x']);
	}
	$init=0;
	if ($x==4||$x==1){$init=1;}
	if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0 no fer res
		$admina=0;
	}else{$admina=1;}
	?>
<script type="text/javascript">
	var init= <?PHP print($init); ?>;
	var a= <?PHP print($admina); ?>;
	var principal= 1;
</script>

<script src="js/scripts.js?v=15"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<link href="css/animate.css" rel="stylesheet" />

<link href="css/estils.css?v=10" rel="stylesheet" />

</head>

<body>
	<?PHP
	if((!isset($_SESSION["idu"])) || ($_SESSION["idu"]<0)){
		}else{
			print("<div id='cloud'>
      				
				</div>
      			");
		}
				$x=0;$y=0;
				if(isset($_GET['x'])){
					$x = trim($_GET['x']);
				}
				if(isset($_GET['y'])){
					$y = trim($_GET['y']);
				}
					
					if ($x==4||$x==1) {
						$timed = date("H");
					    $timezone = date("e");
					    if ($timed < "5") {
					        $saludo = "Buenas noches ";
					    }else if ($timed >= "5" && $timed < "12") {
					        $saludo = "Buenos días ";
					    } else if ($timed >= "12" && $timed < "21") {
					        $saludo = "Buenas tardes ";
					    } else if ($timed >= "21") {
					        $saludo = "Buenas noches ";
					    }
					}
					if ($x==4){
						print "<div id='mensaje' class='status open good'><p>".$saludo.$_SESSION["user"]."!</p></div>\n";
					}elseif ($x==1){
						print "<div id='mensaje' class='status open good'><p>".$saludo.$_SESSION["user"]."!</p></div>\n";
					}elseif ($x==3){
						print "<div id='mensaje' class='status open'><p>Tienes que loguearte como admin para ver esta sección</p></div>\n";
					}elseif (is_string ($x)&&($x!="")) {
						if ($y=="r") {
							print "<div id='mensaje' class='status open error'><i class='material-icons'>error</i><p>  $x</p></div>\n";
						}else if ($y=="g"){
							print "<div id='mensaje' class='status open good'><i class='material-icons'>check_circle</i><p>  $x</p></div>\n";
						}else{
							print "<div id='mensaje' class='status open'><i class='material-icons'>info</i><p>  $x</p></div>\n";
						}
						
					}else{
						print "";
					}
	?>
	<script>//delete get parameters 
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
    }
	</script>
<?PHP
	require_once("header.php");
?>
<div id="alertbackground" onclick="cancelaroferta();">
	
</div>
<div id="alertoferta" class="animated zoomIn">
	<!--Div for editing offer properties when offer from offerlist clicked-->
</div>

<div class="main animated fadeIn">
<section id="lista" class="card cardsize">
	<button id="closebtn" class="closebtn animated bounceIn" onclick="closeoferta();"><i class='material-icons'>arrow_back</i></button>
	<form id="searchform">
		<i class='material-icons searchicon'>search</i><input type="text" id="search" onkeyup="searcher()" placeholder="Search...">
	</form>

	<ul id="ul" class="ul"><!--initial product list-->
	<?PHP
	    
	    //now
	    $d0=date('Y-m-d H:i:s');
	    //this time yesterday
	    $d1=date('Y-m-d H:i:s',strtotime("-1 days"));
	    //this time 5 days ago
	    $d2=date('Y-m-d H:i:s',strtotime("-5 days"));
	    //this time 10 days ago
	    $d3=date('Y-m-d H:i:s',strtotime("-10 days"));
	    //this time 30 days ago
	    $d4=date('Y-m-d H:i:s',strtotime("-30 days"));

	    $db = new Conexion();
	    $dbTabla="Producto, Categoria";
	        $consulta = "SELECT * FROM $dbTabla WHERE Producto.categoria=Categoria.Idc AND Producto.activo=1 ORDER BY Categoria.Idc ASC";
	        $result = $db->prepare($consulta);
	        $result->execute();
	        $categoriaA="";
	    foreach ($result as $productes){

	        $dbTabla2="Oferta";
	        $consulta2 = "SELECT * FROM $dbTabla2 WHERE Oferta.producto=:idp AND Oferta.estadistica=1 ORDER BY Oferta.fechap DESC LIMIT 0,1";
	        $result2 = $db->prepare($consulta2);
	        $result2->execute(array(":idp" => $productes['Idp']));
	        //reset variables for handling correctly products that don't have any deals yet
	        $ultimaof="<div class='point' style='background-color:#AAA;' data-a=''></div>";
	        $title="data-a='No hay ofertas de este producto'";
	        $tags = filter_var( $productes['tags'], FILTER_SANITIZE_SPECIAL_CHARS);

	            if ($productes['categoria']==$categoriaA) {//no posem header ja que esta en la mateixa categoria
	                
	                foreach ($result2 as $ultimaoferta){
	                    if($ultimaoferta['fechap']<=$d0 && $ultimaoferta['fechap']>$d1){
	                        $color="#73FF63";
	                    }else if($ultimaoferta['fechap']<=$d1 && $ultimaoferta['fechap']>$d2){
	                        $color="#F2FF00";
	                    }else if($ultimaoferta['fechap']<=$d2 && $ultimaoferta['fechap']>$d3){
	                        $color="#FFC372";
	                    }else if($ultimaoferta['fechap']<=$d3 && $ultimaoferta['fechap']>$d4){
	                        $color="#E8675D";
	                    }else if($ultimaoferta['fechap']<=$d4){
	                        $color="#DA66FF";
	                    }
	                    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $ultimaoferta['fechap']);
	                    $newDateString = $myDateTime->format('j/n/y G\h');//convertim a format més llegible
	                    $ultimaof="<div class='point' style='background-color:".$color.";' title='".$newDateString."'></div>";
	                    $title="data-a='Última oferta: ".$newDateString." : ".$ultimaoferta['precioO']."€'";
	                }

	                //$tags=explode(';',$productes['tags']);
	                
	                
	                print("<li class='c".$productes['categoria']." producto' style='display: none;'><a href='#' onclick='crearoferta(".$productes['Idp'].");' data-id='".$productes['Idp']."' data-tag=' ".$tags."' ".$title.">".$ultimaof."<p>".$productes['nombrep']."</p></a></li>\n");
	                
	            }else{//canviem el header de la categoría
	                
	                foreach ($result2 as $ultimaoferta){
	                    if($ultimaoferta['fechap']<=$d0 && $ultimaoferta['fechap']>$d1){
	                        $color="#73FF63";
	                    }else if($ultimaoferta['fechap']<=$d1 && $ultimaoferta['fechap']>$d2){
	                        $color="#F2FF00";
	                    }else if($ultimaoferta['fechap']<=$d2 && $ultimaoferta['fechap']>$d3){
	                        $color="#FFC372";
	                    }else if($ultimaoferta['fechap']<=$d3 && $ultimaoferta['fechap']>$d4){
	                        $color="#E8675D";
	                    }else if($ultimaoferta['fechap']<=$d4){
	                        $color="#DA66FF";
	                    }
	                    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $ultimaoferta['fechap']);
	                    $newDateString = $myDateTime->format('j/n/y G\h');//convertim a format més llegible
	                    $ultimaof="<div class='point' style='background-color:".$color.";' title='".$newDateString."'></div>";
	                    $title="data-a='Última oferta: ".$newDateString." : ".$ultimaoferta['precioO']."€'";
	                }

	                $categoriaA=$productes['categoria'];
	                print("\n<li class='header2'><a href='#' onclick='tgle(".$productes['categoria']."); return false;' class='header' id='".$productes['nombrec']."' data-tag=' '>".$productes['nombrec']."</a></li>\n");
	                print("<li class='c".$productes['categoria']." producto' style='display: none;'><a href='#' onclick='crearoferta(".$productes['Idp'].");' data-id='".$productes['Idp']."' data-tag=' ".$tags."' ".$title.">".$ultimaof."<p>".$productes['nombrep']."</p></a></li>\n");
	            }
	    }
		$db=NULL;
	?>
	</ul>

	<div id="construiroferta">
			<?php
				if (session_status() == PHP_SESSION_NONE) {
				    session_start();
				}
				if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0
					//no mostrar botó
				}else{
					print("<button id='editprodbtn' class='animated bounceIn' onclick='editprod();'><i class='material-icons'>edit</i><p>Editar Producto</p></button>");
				}
			?>
		
		<h4>Nueva oferta</h4>
		<form id="ofertaform" action="submitoferta.php" method="post">
			<input type="hidden" id="idpform" name="idpform" value="">
		    <label for="color">Color</label>
		    <select id="color" name="color" form="ofertaform" onchange="buildpreview();">
		    	<option value="" selected></option>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="Colores";
		        $consulta = "SELECT * FROM $dbTabla ORDER BY nombre";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $color){
		    		print("<option value='".$color['Idcolor']."'>".$color['nombre']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <label for="comentario">Comentario</label>
		    <select id="comentario" name="comentario" form="ofertaform" onchange="buildpreview();">
		    	<option value="" selected></option>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="Comentarios";
		        $consulta = "SELECT * FROM $dbTabla ORDER BY texto";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $comentario){
		    		print("<option value='".$comentario['Idcomentario']."'>".$comentario['texto']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <div class="styleinput">      
		      <input type="url" id="link" name="link" onchange="checktienda();buildpreview();" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="link">Link tienda</label>
		    </div>

		    <div id="tiendacheck"><i class='small material-icons checktiendaicon'>cached</i><p>Identificando tienda...</p></div>

		    <div class="styleinput">
		      <input type="number" id="preciohabitual" name="preciohabitual" onchange="buildpreview();" step="any" min="0" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="preciohabitual">Precio Habitual</label>
		    </div>
		    
		    <div class="styleinput">      
		      <input type="number" id="preciooferta" name="preciooferta" onchange="buildpreview();" step="any" min="0" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="preciooferta">Precio Oferta</label>
		    </div>

		    <label for="tipooferta">Tipo Oferta</label>
		    <select id="tipooferta" name="tipooferta" form="ofertaform" onchange="opencupon();buildpreview();" required>
		    	<option value="" selected></option>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="TipoOferta";
		        $consulta = "SELECT * FROM $dbTabla";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $TipoOferta){
		    		print("<option value='".$TipoOferta['IdTipoOf']."' data-cupon='".$TipoOferta['cupon']."'>".$TipoOferta['texto']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <div class="styleinput" id="cupongroup">      
		      <input type="text" id="cupon" name="cupon" onchange="buildpreview();">
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="cupon">Cupón</label>
		    </div>

		    <div class="med">
		    	<label for="envio">Envio</label>
			    <select id="envio" name="envio" form="ofertaform" class="selectmed" onchange="buildpreview();">
			    	<option value="" selected></option>
			    	<?PHP
			    	$db = new Conexion();
			    	$dbTabla="Envio";
			        $consulta = "SELECT * FROM $dbTabla";
			        $result = $db->prepare($consulta);
			        $result->execute();
			    	foreach ($result as $envio){
			    		print("<option value='".$envio['IdEnvio']."' data-flag='".htmlspecialchars($envio['emoji'])."'>".$envio['nombre']."</option>");
			    	}
					$db=NULL;
			    	?>
			    </select>
			</div>

		    <div class="med">
		    	<label for="garantia">Garantía</label>
			    <select id="garantia" name="garantia" form="ofertaform" class="selectmed" onchange="buildpreview();">
			    	<option value="" selected></option>
			    	<?PHP
			    	$db = new Conexion();
			    	$dbTabla="Garantia";
			        $consulta = "SELECT * FROM $dbTabla";
			        $result = $db->prepare($consulta);
			        $result->execute();
			    	foreach ($result as $garantia){
			    		print("<option value='".$garantia['IdGarantia']."' data-flag='".htmlspecialchars($garantia['emoji'])."'>".$garantia['nombre']."</option>");
			    	}
					$db=NULL;
			    	?>
			    </select>
			</div>

			<div class="styleinput" id="comentario2group">      
		      <input type="text" id="comentario2" name="comentario2" onchange="buildpreview();">
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="comentario2">Comentario 2</label>
		    </div>
			<button class="tagbtn" type="button" onclick="comadd('a');">&#60;a&#62;</button>
			<button class="tagbtn" type="button" onclick="comadd('b');">&#60;b&#62;</button>
			<button class="tagbtn" type="button" onclick="comadd('gar');">quitar gar.env.</button>
			<button class="tagbtn" type="button" onclick="comadd('global');">Global</button>
			<button class="tagbtn" type="button" onclick="comadd('color');">X colores</button>
			<br><br>
			<input id="checkbox" class="checkbox" name="checkbox" type="checkbox" form="ofertaform" checked>
		    <label for="checkbox" id="checkbox-label" class="checkbox-label">Insertar en estadísticas</label>

		    <input id="checkbox2" class="checkbox" name="checkbox2" type="checkbox" form="ofertaform">
		    <label for="checkbox2" id="checkbox-label2" class="checkbox-label">Usar Link Original</label>
	  </form>
	</div>
	<!--EDIT PRODUCT-->
	<div id="editproddiv">
		<br>
		<h4>Editar Producto</h4>
		<form id="editprodform" action="insertaproducto.php?w=2" method="post" enctype="multipart/form-data">
			<label for="editprodcategoria">Categoría</label>
		    <select id="editprodcategoria" name="editprodcategoria" form="editprodform" required>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="Categoria";
		        $consulta = "SELECT * FROM $dbTabla ORDER BY Idc ASC";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $Categoria){
		    		print("<option value='".$Categoria['Idc']."'>".$Categoria['nombrec']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <div class="styleinput">      
		      <input type="text" id="editprodnombre" name="editprodnombre" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="editprodnombre">Nombre</label>
		    </div>
		    <label for="editproddescripcion">Descripción</label>
		    <textarea id="editproddescripcion" name="editproddescripcion" form="editprodform" required></textarea>
		    <label for="editprodfoto">Imagen</label>
		    <input type="file" name="editprodfoto" id="editprodfoto">
		    <input type="hidden" name="editprodid" id="editprodid">
		    <p>Si la imagen se deja vacía se mantiene la imagen actual, de lo contrario se actualiza.<br><br></p>
		    <input type="text" maxlength="240" name="editprodtags" id="editprodtags">
		    <p>Tags separados por ';'.<br><br></p>
		    <input type="submit" id="editprodguardar" name="editprodguardar" value="Guardar">
		    <input type="submit" id="editprodeliminar" name="editprodeliminar" class="error" value="Eliminar Producto">
		</form>
		
	</div>
</section>
<section id="ofertas" class="card cardsize2">
	<div id="ultimasofertas">
		<h4>Últimas ofertas creadas</h4>
		<button id="refreshofferlist" class="tooltip" data-a="actualizar" onclick="getofferlist();"><i class='small material-icons'>refresh</i></button>
		<button id="previousoffers" class="tooltip" data-a="más recientes" onclick="previousoffers();"><i class='small material-icons'>chevron_left</i></button>
		<p id="range"></p>
		<button id="nextoffers" class="tooltip" data-a="más antiguas" onclick="nextoffers();"><i class='small material-icons'>chevron_right</i></button>
		<br>
		<ul class="ul" id="offerlist"><!--offer list-->
		
		</ul>
	</div>

	<div id="editoffer">
		<button id="eclosebtn" class="closebtn animated bounceIn" onclick="closediteoferta();"><i class='material-icons'>arrow_back</i></button>
		<h4>Editar oferta</h4>
		<p id="edittitle"></p>
		<form id="eofertaform" action='actionform.php?w=9' method="post">
			<input type="hidden" id="eido" name="eido" value="">
		    <label for="ecolor">Color</label>
		    <select id="ecolor" name="ecolor" form="eofertaform">
		    	<option value="" selected></option>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="Colores";
		        $consulta = "SELECT * FROM $dbTabla ORDER BY nombre";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $color){
		    		print("<option value='".$color['Idcolor']."'>".$color['nombre']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <label for="ecomentario">Comentario</label>
		    <select id="ecomentario" name="ecomentario" form="eofertaform">
		    	<option value="" selected></option>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="Comentarios";
		        $consulta = "SELECT * FROM $dbTabla ORDER BY texto";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $comentario){
		    		print("<option value='".$comentario['Idcomentario']."'>".$comentario['texto']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <div class="styleinput">      
		      <input type="url" id="elink" name="elink" onchange="echecktienda();" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="elink">Link tienda</label>
		    </div>

		    <div id="etiendacheck"><i class='small material-icons checktiendaicon'>cached</i><p>Identificando tienda...</p></div>

		    <div class="styleinput">
		      <input type="number" id="epreciohabitual" name="epreciohabitual" step="any" min="0" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="epreciohabitual">Precio Habitual</label>
		    </div>
		    
		    <div class="styleinput">      
		      <input type="number" id="epreciooferta" name="epreciooferta" step="any" min="0" required>
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="epreciooferta">Precio Oferta</label>
		    </div>

		    <label for="etipooferta">Tipo Oferta</label>
		    <select id="etipooferta" name="etipooferta" form="eofertaform" onchange="eopencupon();" required>
		    	<option value="" selected></option>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="TipoOferta";
		        $consulta = "SELECT * FROM $dbTabla";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $TipoOferta){
		    		print("<option value='".$TipoOferta['IdTipoOf']."' data-cupon='".$TipoOferta['cupon']."'>".$TipoOferta['texto']."</option>");
		    	}
				$db=NULL;
		    	?>
		    </select>
		    <div class="styleinput" id="ecupongroup">      
		      <input type="text" id="ecupon" name="ecupon">
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="ecupon">Cupón</label>
		    </div>

		    <div class="med">
		    	<label for="eenvio">Envio</label>
			    <select id="eenvio" name="eenvio" form="eofertaform" class="selectmed">
			    	<option value="" selected></option>
			    	<?PHP
			    	$db = new Conexion();
			    	$dbTabla="Envio";
			        $consulta = "SELECT * FROM $dbTabla";
			        $result = $db->prepare($consulta);
			        $result->execute();
			    	foreach ($result as $envio){
			    		print("<option value='".$envio['IdEnvio']."' data-flag='".htmlspecialchars($envio['emoji'])."'>".$envio['nombre']."</option>");
			    	}
					$db=NULL;
			    	?>
			    </select>
			</div>

		    <div class="med">
		    	<label for="egarantia">Garantía</label>
			    <select id="egarantia" name="egarantia" form="eofertaform" class="selectmed">
			    	<option value="" selected></option>
			    	<?PHP
			    	$db = new Conexion();
			    	$dbTabla="Garantia";
			        $consulta = "SELECT * FROM $dbTabla";
			        $result = $db->prepare($consulta);
			        $result->execute();
			    	foreach ($result as $garantia){
			    		print("<option value='".$garantia['IdGarantia']."' data-flag='".htmlspecialchars($garantia['emoji'])."'>".$garantia['nombre']."</option>");
			    	}
					$db=NULL;
			    	?>
			    </select>
			</div>

			<div class="styleinput" id="ecomentario2group">      
		      <input type="text" id="ecomentario2" name="ecomentario2">
		      <span class="highlight"></span>
		      <span class="bar"></span>
		      <label class="label" for="ecomentario2">Comentario 2</label>
		    </div>

			<br><br>
			<input id="echeckbox" class="checkbox" name="echeckbox" type="checkbox">
		    <label for="echeckbox" id="echeckbox-label" class="checkbox-label">Insertar en estadísticas</label>

		    <input id="echeckbox2" class="checkbox" name="echeckbox2" type="checkbox">
		    <label for="echeckbox2" id="echeckbox-label2" class="checkbox-label">Usar Link Original</label>
		    <input type='submit' name='eguardar' id='eguardar' form='eofertaform' value='Guardar'>
	  </form>
	</div>
	

	<div id="chartloader" class="loader"></div>
	<div id="chart"></div>
</section>
<section id="enviar" class="card cardsize2">
	<div id="utilidades">
		
		<?php
				if (session_status() == PHP_SESSION_NONE) {
				    session_start();
				}
				if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0
					//no mostrar botó
				}else{
					print("<h4>Utilidades</h4><br><button id='btnpublicarmensaje' onclick='publicarmensaje();'>Publicar mensaje al canal</button>");
				}
		?>
		<div id="addproddiv">
			<h5>Añadir producto</h5>
			<button id='closeaddprod' class='closebtn animated bounceIn' onclick='closeaddprod();'><i class='material-icons'>arrow_back</i></button><br>

			<form id="addprodform" action="insertaproducto.php?w=1" method="post" enctype="multipart/form-data">
				<label for="addprodcategoria">Categoría</label>
		    	<select id="addprodcategoria" name="addprodcategoria" form="addprodform" required>
		    	<?PHP
		    	$db = new Conexion();
		    	$dbTabla="Categoria";
		        $consulta = "SELECT * FROM $dbTabla ORDER BY Idc ASC";
		        $result = $db->prepare($consulta);
		        $result->execute();
		    	foreach ($result as $Categoria){
		    		print("<option value='".$Categoria['Idc']."'>".$Categoria['nombrec']."</option>");
		    	}
				$db=NULL;
		    	?>
		    		
		    	</select>
		    	<br><br>
		    	<div class="styleinput">      
			      <input type="text" id="addprodnombre" name="addprodnombre" required>
			      <span class="highlight"></span>
			      <span class="bar"></span>
			      <label class="label" for="addprodnombre">Nombre</label>
			    </div>
			    <textarea id="addproddescripcion" form="addprodform" name="addproddescripcion" placeholder='Descripción' required></textarea>
			    <label for="addprodfoto">Imagen</label>
			    <input type="file" name="addprodfoto" id="addprodfoto">
			    <p class="smaller">.jpg o .png, max 1,5MB</p><br>
			    
			    
				<div class="styleinput">      
			      <input type="text" id="addprodtags" maxlength="240" name="addprodtags">
			      <span class="highlight"></span>
			      <span class="bar"></span>
			      <label class="label" for="addprodtags">Tags</label>
			    </div>
			    <p class="smaller">Tags separados por ';'</p><br>
			    <input type="submit" value="Guardar">
			    <br><br>
			</form>
		</div>
		<?php
				if (session_status() == PHP_SESSION_NONE) {
				    session_start();
				}
				if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0
					//no mostrar botó
				}else{
					print("<button id='btnanadirproducto' onclick='anadirproducto();'>Añadir producto</button>");
				}
		?>
		<div id="chartoffersbyday"></div>
		<div id="programadas">
			<h4>Ofertas Programadas</h4>
			<button id="refreshprogramadas" class="tooltip" data-a="actualizar" onclick="getprogramadas();"><i class='small material-icons'>refresh</i></button>
			
			<ul class="ul" id="programadas"><!--offer list-->
			
			</ul>
		</div>
	</div>

	<div id="ofertapreview">
		<div id="previewcontent">
			
	    </div>
	    	<img id="previewimg" src="">
	</div>
	<div id="submitbtns"><!-- form crear oferta -->
		<p id="previewmensaje" class="error"><i class='small material-icons'>error</i>Revisa los precios, el precio habitual es inferior o igual al precio de oferta</p>
		<?php
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]<=0)){//si no esta setejat o es menor o igual a 0
				//no mostrar botons
			}else{
				print("<input type='submit' name='publicar' id='publicaroferta' form='ofertaform' value='Publicar' onclick='return publicaroferta2(event);'><br>\n
						<input type='submit' name='programar' id='programaroferta' form='ofertaform' value='Programar' onclick='return programaoferta(event);'><br>");
			}
		?>
		<input type="submit" name="mover" id="moveroferta" form="ofertaform" value="Mover a Pendientes">
	</div>
</section>
<footer>©<?PHP print(date('Y')."-".(date('y')+1));?> MMC All rights reserved. Developed by C, in collaboration with Admins Ofertasxiaomifansclub<br>
<a href="changelog.php">Changelog</a></footer>
</div>
</body>

</html>