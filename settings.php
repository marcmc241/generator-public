<?PHP
	if (session_status() == PHP_SESSION_NONE) {
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
<script src="js/scripts.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<link href="css/animate.css" rel="stylesheet" />
<link href="css/estils.css?v=4" rel="stylesheet" />
<script>//delete get parameters 
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
    }
    var principal= 0;
</script>
</head>

<body>
<?PHP
	require_once("header.php");
print("<br><h3>Configuración</h3>");
$db = new Conexion();
if ((isset($_SESSION["admin"])) && ($_SESSION["admin"]>=1)) {//es admin
	if((isset($_POST["nombre"])) && ($_POST["nombre"]!=NULL)){//add referido
		$nombre = $_POST["nombre"];
		$keyword = $_POST["keyword"];
		$link = $_POST["link"];
		$main = $_POST["main"];
		$_POST = array();//Delete all data in _post to avoid resubmitting
		$dbTabla='Tienda';
		$consulta = "INSERT INTO $dbTabla (nombre, busqueda, referido, main) VALUES (:n, :b, :r, :m)";
		$result = $db->prepare($consulta);
		if ($result->execute(array(":n" => $nombre, ":b" => $keyword, ":r" => $link, ":m" => $main))){
		    print("<p id='mensaje' class='status open good'>Datos guardados correctamente.</p>");
		}else{
			echo "<p id='mensaje' class='status open error'>Error al guardar los datos</p>"; 
		}

	}elseif ((isset($_GET["actref"])) && ($_GET["actref"]!=NULL)) {//update active
		$actref= trim($_GET["actref"]);
		$act= trim($_GET["act"]);
		if ($act==0 || $act==1){
			$dbTabla="Tienda";
			$consulta = "UPDATE $dbTabla SET activo=:act WHERE Tienda.Idt=:id";
			$result = $db->prepare($consulta);
			if($result->execute(array(":id" => $actref,":act" => $act))){
				print("<p id='mensaje' class='status open good'>Actualizado Correctamente</p>");
			}else{
				print("<p id='mensaje' class='status open error'>Error al actualizar</p>");
			}
		}else{
			print("<p id='mensaje' class='status open error'>Error al actualizar</p>");
		}
	}elseif ((isset($_GET["delref"])) && ($_GET["delref"]!=NULL)) {//delete
		/*$delref= trim($_GET["delref"]);
		if (ctype_digit($delref)&&$delref!=''&&$delref!=0){
			$dbTabla="Tienda";
			$consulta = "DELETE FROM $dbTabla WHERE Tienda.Idt=:id";
			$result = $db->prepare($consulta);
			if($result->execute(array(":id" => $delref))){
				print("<p id='mensaje' class='status open good'>Eliminado Correctamente</p>");
			}else{
				print("<p id='mensaje' class='status open error'>Error al eliminar</p>");
			}
		}else{
			print("<p id='mensaje' class='status open error'>Error al eliminar</p>");
		}*/
		print("<p id='mensaje' class='status open error'>Función deshabilitada. Cambiar a inactivo.</p>");
	}elseif ((isset($_GET["delcat"])) && ($_GET["delcat"]!=NULL)) {//delete
		$delcat= trim($_GET["delcat"]);
		if (ctype_digit($delcat)&&$delcat!=''&&$delcat!=0){

			$dbTabla='Producto';
			$consulta = "SELECT * FROM $dbTabla WHERE categoria=:c";
			$result = $db->prepare($consulta);
			$result->execute(array(":c" => $delcat));
			$prod=0;
			foreach ($result as $prod) {
				$prod++;
			}
			if ($prod===0) {
				$consulta = "DELETE FROM Categoria WHERE Categoria.Idc=:id";
				$result = $db->prepare($consulta);
				if($result->execute(array(":id" => $delcat))){
					print("<p id='mensaje' class='status open good'>Eliminado Correctamente</p>");
				}else{
					print("<p id='mensaje' class='status open error'>Error al eliminar</p>");
				}
			}else{
				print("<p id='mensaje' class='status open error'>No se puede eliminar, contiene productos</p>");
			}
			
		}else{
			print("<p id='mensaje' class='status open error'>Error al eliminar</p>");
		}
	}elseif ((isset($_POST["nombrec"])) && ($_POST["nombrec"]!=NULL)) {//add
		$nc=trim($_POST["nombrec"]);
		$dbTabla='Categoria';
		$consulta = "INSERT INTO $dbTabla (nombrec) VALUES (:n)";
		$result = $db->prepare($consulta);
		if ($result->execute(array(":n" => $nc))){
		    print("<p id='mensaje' class='status open good'>Categoría añadida</p>");
		}else{
			echo "<p id='mensaje' class='status open error'>Error al añadir categoría</p>"; 
		}
		$_POST = array();
	}elseif ((isset($_GET["delcom"])) && ($_GET["delcom"]!=NULL)) {//delete
		$delcom= trim($_GET["delcom"]);
		if (ctype_digit($delcom)&&$delcom!=''&&$delcom!=0){

			$dbTabla='Oferta';
			$consulta = "SELECT * FROM $dbTabla WHERE comentario=:c";
			$result = $db->prepare($consulta);
			$result->execute(array(":c" => $delcom));
			$ofer=0;
			foreach ($result as $off) {
				$ofer++;
			}
			if ($ofer===0) {
				$consulta = "DELETE FROM Comentarios WHERE Comentarios.Idcomentario=:id";
				$result = $db->prepare($consulta);
				if($result->execute(array(":id" => $delcom))){
					print("<p id='mensaje' class='status open good'>Eliminado Correctamente</p>");
				}else{
					print("<p id='mensaje' class='status open error'>Error al eliminar</p>");
				}
			}else{
				print("<p id='mensaje' class='status open error'>No se puede eliminar, se usa en algunas ofertas</p>");
			}
			
		}else{
			print("<p id='mensaje' class='status open error'>Error al eliminar</p>");
		}
	}elseif ((isset($_POST["textocom"])) && ($_POST["textocom"]!=NULL)) {//add
		$tcom=trim($_POST["textocom"]);
		$dbTabla='Comentarios';
		$consulta = "INSERT INTO $dbTabla (texto) VALUES (:t)";
		$result = $db->prepare($consulta);
		if ($result->execute(array(":t" => $tcom))){
		    print("<p id='mensaje' class='status open good'>Comentario añadido</p>");
		}else{
			echo "<p id='mensaje' class='status open error'>Error al añadir el comentario</p>"; 
		}
		$_POST = array();
	}elseif ((isset($_POST["hashtags"])) && ($_POST["hashtags"]!=NULL)) {//delete
		$ht=trim($_POST["hashtags"]);
		$dbTabla='Configuracion';
		$consulta = "UPDATE $dbTabla SET hashtags=:ht WHERE Idconfig=4";
		$result = $db->prepare($consulta);
		if ($result->execute(array(":ht" => $ht))){
		    print("<p id='mensaje' class='status open good'>Hashtags actualizados</p>");
		}else{
			echo "<p id='mensaje' class='status open error'>Error al actualizar</p>"; 
		}
		$_POST = array();
	}
}
if ((isset($_SESSION["admin"])) && ($_SESSION["admin"]>=2)) {//es superadmin
	if((isset($_POST["template"])) && ($_POST["template"]!=NULL)){//update datos superadmin
		$canal = $_POST["canal"];
		$grupo = $_POST["grupo"];
		$template = $_POST["template"];
		$_POST = array();//Delete all data in _POST to avoid resubmitting
		$dbTabla='Configuracion';

		$consulta = "UPDATE $dbTabla SET template=:tm, canal=:cn, grupo=:gr WHERE Idconfig=4";
		$result = $db->prepare($consulta);
		if ($result->execute(array(":tm" => $template, ":cn" => $canal, ":gr" => $grupo))){
		    print("<p id='mensaje' class='status open good'>Datos guardados correctamente.</p><br><br>");
		}else{
			echo "<p id='mensaje' class='status open error'>Error al guardar los datos</p>"; 
		}


	}
}
$db=NULL;
?>
<div id="alertbackground" onclick="cancelaroferta();">
	
</div>
<div id="alertoferta">
	<!--Div for editing offer properties when offer from offerlist clicked-->
</div>

<div class="animated fadeIn"><!--main div-->
	<?PHP  				//referidos
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if((isset($_SESSION["admin"])) && ($_SESSION["admin"]>=1)){//si esta setejat i es major o igual a 1 és admin
	print("<section id='referidos' class='setsect'><h3>Referidos</h3>");
	$db = new Conexion();
	$dbTabla="Tienda";
		$consulta = "SELECT * FROM $dbTabla ORDER BY Idt";
		$result = $db->prepare($consulta);
		$result->execute();
		$tot=0;
		print("<table cellspacing='0' cellpadding='0'>\n<tr>\n<th>ID</th>\n<th>Nombre</th>\n<th>Keyword</th>\n<th>Link</th>\n<th>Main page</th>\n<th>Activo</th>\n<th></th>\n</tr>\n");
	foreach ($result as $tienda){
		if ($tienda['activo']==1) {
			$activo="<i class='small material-icons'>check</i>";
			$act=0;
		}else{
			$activo="<i class='small material-icons'>close</i>";
			$act=1;
		}
			print("<tr>\n
	  		<td>".$tienda['Idt']."</td>\n
	  		<td>".$tienda['nombre']."</td>\n
	  		<td>".$tienda['busqueda']."</td>\n
	  		<td>".$tienda['referido']."</td>\n
	  		<td>".$tienda['main']."</td>\n
	  		<td><a href='settings.php?actref=".$tienda['Idt']."&act=$act' class='tooltip' data-a='Cambiar estado'>$activo</a></td>\n
	  		<td><a href='settings.php?delref=".$tienda['Idt']."'>Eliminar</a></td>\n
	  		</tr>\n
	  		");
		$tot++;
		}
		?>
		<tr>
			<form action="settings.php" method="post">
				<td>Nuevo:</td>
				<td><input id="nombre" type="text" name="nombre" placeholder="nombre" required></td>
				<td><input id="keyword" type="text" name="keyword" placeholder="keyword búsqueda" required></td>
				<td><input id="link" type="text" name="link" placeholder="enlace referido" required></td>
				<td><input id="main" type="text" name="main" placeholder="página principal" required></td>
				<td></td>
				<td><input id="submitref" type="submit" value="Guardar"></td>
			</form>
		</tr>
		<?PHP
		print("</table><p class='total'>Total: $tot tiendas</p></section>");




		print("<br><section id='categorias' class='setsect'><h3>Categorias</h3>");
		$dbTabla="Categoria";
		$consulta = "SELECT * FROM $dbTabla ORDER BY Idc";
		$result = $db->prepare($consulta);
		$result->execute();
		print("<table cellspacing='0' cellpadding='0'>\n<tr>\n<th>ID</th>\n<th>Nombre</th>\n<th></th>\n</tr>\n");
		$tot=0;
		foreach ($result as $cat){
			
				print("<tr>\n
		  		<td>".$cat['Idc']."</td>\n
		  		<td>".$cat['nombrec']."</td>\n
		  		<td><a href='settings.php?delcat=".$cat['Idc']."'>Eliminar</a></td>\n
		  		</tr>\n
		  		");
			$tot++;
			}
		print("<tr>
			<form action='settings.php' method='post'>
				<td>Nueva:</td>
				<td><input id='nombrec' type='text' name='nombrec' placeholder='nombre' required></td>
				<td><input id='submitcat' type='submit' value='Guardar'></td>
			</form>
		</tr>");
		print("</table><p class='total'>Total: $tot categorias</p></section>");



		print("<br><section id='comentarios' class='setsect'><h3>Comentarios</h3>");
		$dbTabla="Comentarios";
		$consulta = "SELECT * FROM $dbTabla ORDER BY Idcomentario";
		$result = $db->prepare($consulta);
		$result->execute();
		print("<table cellspacing='0' cellpadding='0'>\n<tr>\n<th>ID</th>\n<th>Texto</th>\n<th></th>\n</tr>\n");
		$tot=0;
		foreach ($result as $com){
			
				print("<tr>\n
		  		<td>".$com['Idcomentario']."</td>\n
		  		<td>".$com['texto']."</td>\n
		  		<td><a href='settings.php?delcom=".$com['Idcomentario']."'>Eliminar</a></td>\n
		  		</tr>\n
		  		");
			}
		$db=NULL;
		print("<tr>
			<form action='settings.php' method='post'>
			<td>Nuevo:</td>
			<td><input id='textocom' type='text' name='textocom' placeholder='comentario' required></td>
			<td><input id='submitcom' type='submit' value='Guardar'></td>
			</form>
		</tr>");
		print("</table></section>");
	}




if((isset($_SESSION["admin"])) && ($_SESSION["admin"]>=2)){//si esta setejat i es major o igual a 2 és SUPERadmin
	print("<section id='superuser' class='setsect'><h3>Superuser</h3><br>");
	print("<form action='settings.php' method='post'>");
	$db = new Conexion();
	$dbTabla="Configuracion";
		$consulta = "SELECT * FROM $dbTabla";
		$result = $db->prepare($consulta);
		$result->execute();
	foreach ($result as $config){
			print("<textarea id='template' name='template' placeholder='plantilla' rows='19' required>".htmlspecialchars($config['template'])."</textarea>");
			print("<p class='smaller'>Tienes que sustituir los emojis por su código. Ej. &amp;#127881; = &#127881;</p><p class='smaller'>Busca los códigos <a href='http://www.amp-what.com/'>aquí</a></p>");
			print("<div class='styleinput'>      
			      <input type='text' id='canal' name='canal' value='".$config['canal']."'>
			      <span class='highlight'></span>
			      <span class='bar'></span>
			      <label class='label' for='canal'>Canal (no implementado)</label>
			    </div>");
			print("<div class='styleinput'>      
			      <input type='text' id='grupo' name='grupo' value='".$config['grupo']."'>
			      <span class='highlight'></span>
			      <span class='bar'></span>
			      <label class='label' for='grupo'>Grupo (no implementado)</label>
			    </div>");
		}
		print("<br><input id='submitsup' type='submit' value='Guardar'></form><br>");
		print("</section>");
	$db=NULL;
}
?>
</section>
<section class='setsect'>
	<form action="settings.php" method="post">
		<label for='hashtags'>Hashtags</label>
		<?php
			$hash;
			$db = new Conexion();
		    $dbTabla='Configuracion';
		    $consulta = "SELECT * FROM $dbTabla WHERE Idconfig=4";
		    $result = $db->prepare($consulta);
		    $result->execute();
		    foreach($result as $conf){
		    	$hash=$conf['hashtags'];
		    }
		    $db=NULL;
		    print("<input id='hashtags' type='text' name='hashtags' placeholder='#mi' value='".$hash."'' required>");
		?>
	<input id="submithash" type="submit" value="Guardar">
	</form>
</section>
<section class='setsect'>
	<h3>OXMF.club Promo</h3><br>
	<p class='smaller'>Guardar vacío para ocultar el banner<br><br></p>
	<form action="updatesettings.php" method="post" enctype="multipart/form-data">
		<label for='promotxt'>Promo Text</label>
		<textarea id='promotxt' name='promotxt' placeholder='promo text' rows='19'><?php
			$promotxt;
			$db = new Conexion();
		    $dbTabla='Configuracion';
		    $consulta = "SELECT * FROM $dbTabla WHERE Idconfig=4";
		    $result = $db->prepare($consulta);
		    $result->execute();
		    foreach($result as $conf){
		    	$promotxt=$conf['promotxt'];
		    }
			$db=NULL;
			print(htmlspecialchars($promotxt)."</textarea>");
		?><p class='smaller'>Cuidado con lo que pongas ahí, puede contener tags HTML,<br>pero debe ser correcto o puedes alterar la web oxmf.club</p>
		<p>Tags disponibles: &lt;b&gt; &lt;i&gt; &lt;a&gt; &lt;code&gt; &lt;p&gt; </p>
		<p>Meter todo dentro de uno o varios párrafos (&lt;p&gt;)</p>
		<label for="promoimg">Imagen</label>
		<input type="file" name="promoimg" id="promoimg">
	<input id="submitpromo" type="submit" value="Guardar">
	</form>
	<br><br>
	<p>Texto de referencia para terminar una<br> promo general (ej. 11.11 o black friday):</p>
	<p class='smaller settingssmcard'>
		&lt;p&gt;Como siempre, publicamos todas las ofertas que encontramos en esta web, así como en nuestro &lt;a href="https://twitter.com/oxmfclub" target="_blank" rel="noreferrer"&gt;Twitter&lt;/a&gt; y también en nuestro &lt;a href="https://t.me/ofertasxiaomifansclub" target="_blank" rel="noreferrer"&gt;canal de Telegram&lt;/a&gt;, donde además de lo que publicamos aquí, algunas veces publicamos si encontramos cupones que sirven en varios artículos, por lo que te recomendamos que también estés atento al canal de Telegram si quieres enterarte de todo.&lt;/p&gt;
				
		&lt;p&gt;¡Disfruta de las ofertas!&lt;/p&gt;
	</p>
</section>

<footer>©<?PHP print(date('Y')."-".(date('y')+1));?> MMC All rights reserved. Developed by C, in collaboration with Admins Ofertasxiaomifansclub<br>
<a href="changelog.php">Changelog</a></footer>
</div>
</body>

</html>