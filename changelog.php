<?PHP
	session_start();
	require_once("protege_basic.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Changelog</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Copyright 2016-17, Marc Masip-->
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="js/scripts.js"></script>
	<link href="css/estils.css?v=1" rel="stylesheet" />
<script type="text/javascript">
	var principal= 0;
</script>
</head>

<body>
<?PHP
  require_once("header.php");
  ?>
<section id="changelog">
<h2>Changelog @Ofertasxiaomifansclub Generator</h2>
<br>
<div class='card cardsize'>
	<p>Changelog v17.1:</p>
	<ul>
		<li>New - Integrado el número de clicks en la lista de ofertas publicadas.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v17:</p>
	<ul>
		<li>New - Instalado acortador de links propio (yourls) con la ayuda de F.</li><br>
		<li>New - Integrado en el generator.</li><br>
		<li>New - Ahora tendremos estadísticas más fiables de todos los clicks.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.9:</p>
	<ul>
		<li>New - Ahora la plantilla de oferta muestra el enlace a la oferta en oxmf.club.</li><br>
		<li>New - Cambiada la plantilla de oferta a la de navidad.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.8:</p>
	<ul>
		<li>Fix - Arreglados los iconos y fuentes que no se mostraban bien.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.7:</p>
	<ul>
		<li>New! - Ahora se pueden modificar los banners de promoción del nuevo oxmf.club (banner y texto).</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.6:</p>
	<ul>
		<li>New! - Ahora se pueden añadir y eliminar comentarios en settings (solo se pueden eliminar los no usados).</li><br>
		<li>New! - Añadidos nuevos botones para añadir en el comentario 2 "Global Version" y "Disponible en X colores".</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.5:</p>
	<ul>
		<li>New! - Añadido campo "tags" en el formulario de añadir producto.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.4:</p>
	<ul>
		<li>New! - Nuevo sistema de tags para mejor búsqueda de productos.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.3:</p>
	<ul>
		<li>New! - Nuevo botón para añadir el comentario: "Quita la garantía de envío para conseguir el precio".</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.2:</p>
	<ul>
		<li>New! - Ahora en configuración también se pueden editar los hashtags que se publican en twitter al final de cada oferta, ideal para promos especiales como este Black Friday.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16.1:</p>
	<ul>
		<li>New! - Añadidos comentarios: Reacondicionado,¡Pack!,Preventa,¡Pocas Unidades!,¡Liquidación!,¡Nueva Versión!,¡Últimas unidades!,¡Difícil de encontrar!</li><br>
		<li>New! - Nuevos botones para añadir links o negrita en el comentario 2</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v16:</p>
	<ul>
		<li>New! - Nueva opción de editar ofertas, ahora se pueden editar todas las ofertas estén publicadas o no. Las ofertas se actualizarán automáticamente en la web y en el canal de telegram (no se puede editar en twitter).</li><br>
		<li>New! - Nueva sección en la configuración, ahora es posible crear y eliminar categorías. Sólo se pueden eliminar si no contienen ningún producto.</li><br>
		<li>New! - Añadido círculo azul para las confirmaciones del bot en el canal (mejor para telegram X sin burbujas).</li><br>
		<li>Fix - Cambiado título de la pestaña de "Generator neon" a "Generator Neon".</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v15.4:</p>
	<ul>
		<li>New! - Añadida paginación en la pestaña de últimas ofertas creadas, ahora es posible ver, cambiar el estado y responder a ofertas más antiguas.</li><br>
		<li>Fix - Arreglado el bug que hacía que saliese múltiples veces el texto de "no hay ofertas programadas" al darle a actualizar en la lista de ofertas programadas.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v15.3:</p>
	<ul>
		<li>New! - Mejorada la integración de la API de Twitter (gestión de errores).</li><br>
		<li>New! - Ahora al eliminar una oferta también se eliminará el tweet correspondiente.</li><br>
		<li>New! - Añadido texto "no hay ofertas programadas" cuando no hay ninguna oferta programada para publicar en el listado de ofertas programadas.</li><br>
		<li>Fix - Arreglado el bug de verificación entre precio habitual y de oferta, que daba falsos positivos en algunos casos.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v15.2:</p>
	<ul>
		<li>New! - Adaptado todo el sistema para usar https.</li><br>
		<li>New! - Nueva sección "ofertas programadas" en la página principal. Muestra las ofertas programadas ordenadas por hora programada(máximo 50 ofertas a la vez).</li><br>
		<li>-New! - Integrada la API de Twitter para publicar las ofertas automáticamente en @oxmfclub.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v15.1:</p>
	<ul>
		<li>New! - Links clickables en la lista de ofertas de la parte central, que se abren en una pestaña nueva para facilitar la comprovación de agotados.</li><br>
		<li>New! - Ahora en las ofertas agotadas se cambia la diana del lado del enlace por una X.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v15:</p>
	<ul>
		<p>Nuevo Generator neon, la evolución del Generator 3000.</p><br>
		<li>New! - Nueva intefaz de usuario totalmente renovada, con elementos dinámicos que cambian según las interacciones del usuario.</li><br>
		<li>New! - Todo el diseño y colores del generator han sido renovados.</li><br>
		<li>New! - Animaciones y indicaciones que indican mejor el estado y las reacciones del sistema a las interacciones.</li><br>
		<li>New! - Nueva página de log-in.</li><br>
		<li>New! - Buscador mejorado para encontrar productos más fácilmente.</li><br>
		<li>New! - Ahora al construir la oferta también se ve la evolución de precios justo al lado.</li><br>
		<li>New! - Nuevo sistema para generar las ofertas con muchos más parámetros que se colocan automáticamente donde deben.</li><br>
		<li>New! - Visualización del resultado de la oferta a medida que se va construyendo.</li><br>
		<li>New! - Comprobación de la tienda y diferencia entre el precio habitual y de oferta.</li><br>
		<li>New! - Nueva plantilla de oferta editable con más opciones para acomodar todos los nuevos parámetros.</li><br>
		<li>New! - Mejorada la base de datos de ofertas para que guarde todos estos parámetros nuevos y los pueda mostrar en el canal y en la web.</li><br>
		<li>New! - [Admins] Nuevo sistema para editar productos, ahora desde cada producto puedes editar nombre, descripción, categoría y foto.</li><br>
		<li>New! - [Admins] Al terminar de hacer una oferta se puede publicar, publicar solo en la web o programar con un par de clicks, sin estar cambiando entre pantallas contínualemte.</li><br>
		<li>New! - Nuevo sistema de gestión de ofertas, ahora las ofertas hechas por admins o no admins se pueden guardar con estado "pendiente" a espera de publicación o programación.</li><br>
		<li>New! - Las ofertas ahora son marcadas según su estado.</li><br>
		<li>New! - En la parte central del generator se ha puesto un listado con las ofertas que se han creado, indicando producto, precio de oferta, tienda, cuanto tiempo hace que se ha creado y su estado.</li><br>
		<li>New! - Clicando en una de estas ofertas aparece la oferta construida y las opciones que tiene según su estado.</li><br>
		<li>New! - Nuevo sistema para marcar las ofertas terminadas como agotadas, uno de los estados disponibles es "agotado", y cambia la imagen del canal por una en escala de grises con la marca de agua "agotado".</li><br>
		<li>New! - Todos los usuarios pueden marcar colo agotada una oferta.</li><br>
		<li>New! - Tanto las imagenes con la marca de agua de agotado como los thumbnails de tamaño pequeño ahora los genera el sistema automáticamente al añadir o editar productos.</li><br>
		<li>New! - Nuevo estado "eliminado", si se elimina una oferta con el botón correspondiente des de la columna central, se elimina en telegram y en la web.</li><br>
		<li>New! - Todos los usuarios pueden eliminar ofertas.</li><br>
		<li>New! - [Admins] Nueva opción "responder", se puede responder a ofertas ya enviadas con el botón correspondiente en la columna central. El mensaje sólo aparecerá en telegram.</li><br>
		<li>New! - Nuevos mensajes de confirmación del bot al grupo de admins, más concretos y no tan extensos.</li><br>
		<li>New! - [Admins] Nuevo apartado de utilidades para añadir productos y publicar mensajes.</li><br>
		<li>New! - [Admins] Nuevo sistema para enviar mensajes al canal.</li><br>
		<li>New! - Nueva gráfica de ofertas creadas por día.</li><br>
		<li>New! - Nueva página de configuración de afiliados, pudiendo añadir, eliminar o marcar como inactivos (si no funcionan temporalmente).</li><br>
		<li>New! - [Superusers] Se puede editar la plantilla de oferta.</li><br>
		<li>New! - Nueva página de changelog.</li><br>
		<li>New! - Toda la pagina menos la configuración se ha hecho responsive.</li><br>
		<li>New! - [tech] Uso de ajax/json en muchas partes para que no se tenga que cambiar contínuamente entre páginas.</li><br>
		<li>New! - [tech] Nuevo sistema de generación de thumbnails y marca de agua "agotado" en php.</li><br>
		<li>New! - [tech] Incrementada la seguridad en muchas partes.</li><br>
		<li>New! - [tech] Funciones de uso común agrupadas en un script.</li><br>
		<li>New! - [tech] Reducción del número de archivos de scripts considerablemente.</li><br>
		New! - [tech] Todo el código ha sido reescrito para optimizarlo.
		<li>New! - Ahora tenemos que usar esta sintaxis para escribir código con formato: &lt;b&gt;negrita&lt;/b&gt; // &lt;i&gt;cursiva&lt;/i&gt; // &lt;a href="http: // www.example.com/"&gt;texto&lt;/a&gt; // &lt;code&gt;código&lt;/code&gt; (como el de los cupones) // &lt;pre&gt;código multilínea&lt;/pre&gt;</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v13.1:</p>
	<ul>
		<li>Fix - Arreglado bug en "modificar productos" que impedía que se viese el listado de productos.</li><br>
		<li>Fix - Arreglado bug crítico en la página "estadísticas" que rompía el código de esa parte.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v13:</p>
	<ul>
		<li>New! - Nuevo sistema de protección contra entradas por fuerza intentando adivinar user y password, bloquea el login por 30 min si hay 3 intentos fallidos des del mismo PC o 10 intentos fallidos en total durante 30 min.</li><br>
		<li>New! - [Admins] Nueva página de estadísicas que muestra nº de visitas a oxmf.club por día, nº de links pasados por oxmf.club por día, nº de ofertas generadas por día y nº de ofertas generadas por mes.</li><br>
		<li>Fix - Quitados parte de los datos del menú lateral que no terminaban de representarse bien.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v12.2:</p>
	<ul>
		<li>New! - [Admins] Nuevo sistema de publicación de ofertas directamente al canal, permite que ahora se vean todas las ofertas publicadas en mensajes.</li><br>
		<li>New! - [Admins] Ahora se pueden programar para una hora determinada ofertas y otros mensajes ya redactados previamente.</li><br>
		<li>Fix - Mejorada organización interna y eliminación de código duplicado.</li><br>
		<li>Fix - Otras pequeñas correcciones.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v12.1:</p>
	<ul>
		<li>New! - Ahora cuando se programe una oferta se enviará una confirmación al grupo con la oferta y la hora programada.</li><br>
		<li>Fix - Botones de enviar al canal y programar ya no visibles para los no admins.</li><br>
		<li>Fix - Otras pequeñas correcciones.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v12:</p>
	<ul>
		<li>New! - Se ha migrado el generator a una nueva web: oxmf.club con un servidor VPS con SSD de alta velocidad y bajo ping con los servers de telegram (2-3ms). Agradecer a F la ayuda en la configuración.</li><br>
		<li>New! - Añadida información visual en la página principal, ahora se puede saber la última fecha de publicación de un producto mirando el color del puntito de su lado. Leyenda: últimas 24h > verde; 24h a 72h > amarillo; 72h a 10 días > naranja; 10 días a 1 mes > rojo; +1mes > lila; ND > gris.</li><br>
		<li>New! - Si se hace "hover" sobre un producto en la página principal, ahora sale información sobre la última oferta.</li><br>
		<li>New! - Reforzada la seguridad del sistema de registro, ahora tiene que autorizar C cada registro para que esté activo.</li><br>
		<li>New! - Nuevas páginas de error del server.</li><br>
		<li>New! - [Admins] Ahora al terminar una oferta se puede publicar directamente al canal.</li><br>
		<li>New! - [Admins] Ahora al terminar una oferta se puede programar para que se publique a una hora determinada.</li><br>
		<li>New! - [Admins] Nueva página donde se ven los mensajes programados, y se pueden eliminar.</li><br>
		<li>New! - [Admins] Ahora se puede editar también el nombre de un producto y su categoría.</li><br>
		<li>New! - [Admins] Confirmaciones antes de enviar algo al canal.</li><br>
		<li>Fix - Arreglado el desfase horario de 2h en las ofertas guardadas.</li><br>
		<li>Fix - Changelog integrado con el resto de páginas</li><br>
		<li>Nota - Con esta update también se han abierto las puertas a nuevas funciones. Thanks to @noplanman for the help.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v11:</p>
	<ul>
		<li>Nueva opcion para enviar ofertas terminadas directamente al canal @ofertasxiaomifansclub con el bot. Usar con precaución, todo lo que se ponga ahí se publicará al canal. Opción solo disponible para Admins del Generator.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v10.3:</p>
	<ul>
		<li>Se ha desprotegido el acceso al generador de bitlinks, así si tenéis algún amigo que quiera comprar en una de nuestras tiendas afiliadas y quiere colaborar con miui.es, podéis pasarle el enlace. Para ello también se han eliminado partes de esa pagina para que no haya links que no les lleven a ninguna parte.</li><br>
		<li>Recordatorio de tiendas afiliadas: Aliexpress, Zapals, eBay, Alegrecompra, Antelife, Banggood, CooliCool, DealExtreme, Everbuying, Fastcard <br>
		cardsizetech, Focalprice, GearBest, GeekBuying, GeekVida, TomTop, LightInTheBox, Igogo, DD4, PcComponentes, Amazon, Amazon_DE, Amazon_UK, Amazon_IT, TinyDeal, FastTech.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v10.2:</p>
	<ul>
		<li>Hemos despedido al Bot, su comportamiento no era el esperado y muchas veces no hacía bien su trabajo. Si se replantea su forma de hacer las cosas le volveremos a llamar. No hace falta decir que no cobrará.</li><br>
		<li>Debido al problema del bot, volvemos al sistema anterior de copiar y pegar en Telegram (conservando el resto de ventajas del nuevo generator).</li><br>
		<li>Para los que aún no se hayan enterado, la sorpresa de la anterior update era un minijuego que se abría al clicar sobre el tick (checkmark) grande que sale al terminar de generar una oferta.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v10.1:</p>
	<ul>
		<li>Nueva opción "usar link original", ideal para casos de email price o similares que necesitan pasar los parámetros en el link para que se aplique el mejor precio.</li><br>
		<li>Nuevo apartado de "stats" en el menú que muestra las ofertas hechas durante el día en total y las hechas por el usuario, y el % relativo respecto al día anterior.</li><br>
		<li>Una sorpresa escondida, el primero que la encuentre que lo diga pero que no se chive de dónde está</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v10:</p>
	<ul>
		<li>Nueva Pantalla Principal, con buscador integrado para todos los productos.</li><br>
		<li>Nuevas categorías para tenerlo todo mucho mejor organizado.</li><br>
		<li>Nuevo sistema de generación de ofertas, mucho más seguro que el anterior.</li><br>
		<li>Presentamos el Generator3000_bot, un bot que pondrá cualquier oferta generada directamente en el grupo de admins.</li><br>
		<li>Nuevo sistema de estadísticas, para cada producto se podrá ver la evolución de precios y el precio mínimo al que ha estado (podéis verlo en funcionamiento entrando en el mi4i).</li><br>
		<li>Se pueden desactivar las estadísticas si algún producto no se desea que aparezca (p.ej. error de precio).</li><br>
		<li>Las estadísticas se deshabilitan solas si se detecta que durante una hora hay dos ofertas del mismo producto, solo se deja el segundo introducido (se considera que el primero o ha sido un error o esa oferta es irrelevante en comparación con la siguiente).</li><br>
		<li>Nuevo sistema de seguridad aplicado a todo el Generator, todos los users deben loguearse.</li><br>
		<li>El nuevo sisteme permite users con permisos de administrador y users sin ese permiso.</li><br>
		<li>Base de datos nueva, con más posibilidades, todo ha sido adaptado a la nueva base de datos.</li><br>
		<li>Arregladas las imágenes para que aparezcan bien en Telegram (con marca de agua nueva).</li><br>
		<li>Ah, que esperábais aún algo más? ;)</li><br><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v9:</p>
	<ul>
		<li>-Añadidos los afiliados de Amazon.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v8:</p>
	<ul>
		<li>-Ahora se pueden editar las descripciones de los productos sin tener que borrar y volver a introducir.</li><br>
		<li>-Nuevo apartado modificación referidos para gestionar los links referidos (añadir y eliminar).</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v6/v7:</p>
	<ul>
		<li>-Nuevo diseño en color rose gold (también es el naranja de xiaomi desaturado).</li><br>
		<li>-Nuevo menú lateral.</li><br>
		<li>-Registro/Login y páginas protegidas.</li><br>
		<li>-Nuevo apartado de Eliminación de productos.</li><br>
		<li>-Mejorada gestión enlaces afiliados.</li><br>
		<li>-Animaciones.</li><br>
		<li>-Loader.</li><br>
		<li>-Código mejorado y optimizado.</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v5:</p>
	<ul>
		<li>-arreglada descripción redmi 4 prime.</li><br>
		<li>-añadido nuevo generador de bitlinks.</li><br>
		<li>-otros cambios internos (reordenar ficheros basicamente).</li><br>
	</ul>
</div>
<br>
<div class='card cardsize'>
	<p>Changelog v4:</p>
	<ul>
		<li>-Cambiados los iconos navideños por los normales.</li><br>
		<li>-Quitada la foto de año nuevo.</li><br>
		<li>-Múltiples cambios en la interfaz, ahora iconos de "?" explican lo que hay que poner en cada sitio en caso de confusión.</li><br>
		<li>-Quitado el botón "Seleccionar Todo".</li><br>
		<li>-Cambios en los textos y en el título de la página.</li><br>
		<li>-nuevo buscador en el tipo de producto "otros", para facilitar mucho la selección en ese apartado tan lleno de gadgets.</li><br>
	</ul>
</div>
</section>
<br>
<br>
<br>
<footer>©<?PHP print(date('Y')."-".(date('y')+1));?> MMC All rights reserved. Developed by C, in collaboration with Admins Ofertasxiaomifansclub<br>
<a href="changelog.php">Changelog</a></footer>
</body>

</html>