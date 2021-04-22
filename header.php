<?PHP
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	require_once("protege_basic.php");
	require_once("conexion_PDO.php");
?>
<header class="">
	<div id="headercenter">
		<h2>oxmf.club</h2>
		<h1>Generator <span class="error">neon</span> <sup class="smaller">v17.1</sup></h1>
	</div>
	<div id="headerside">
		<ul>
			<?PHP
			if((!isset($_SESSION["admin"])) || ($_SESSION["admin"]==0)){
	      					print "<li><i class='material-icons'>account_circle</i><br> ".$_SESSION["user"]."</li>\n";
	      					print("<li><a href='logout.php' class='tooltiplow' data-a='Log Out'><i class='bigicon material-icons'>exit_to_app</i></a></li>\n");
						}else if(($_SESSION["admin"]>=1)){
	     					echo "<li><i class='material-icons'>account_circle</i><br> ".$_SESSION["user"]."<br>
	     							<p id='usertype'>- Admin -</p></li>\n";
	     					print("<li><a href='logout.php' class='tooltiplow' data-a='Log Out'><i class='bigicon material-icons'>exit_to_app</i></a></li>\n
	  							<li><a href='principal.php' class='tooltiplow' data-a='Home'><i class='bigicon material-icons'>home</i></a></li>\n
	  							<li><a href='settings.php' class='tooltiplow' data-a='Settings'><i class='bigicon material-icons'>settings</i></a></li>");
	  					}
	  		
	  		?>
  		</ul>
	</div>
</header>

<div class="background" id="bg1"></div>
<div class="background" id="bg2"></div>
<div class="background" id="bg3"></div>
<div class="background" id="bg4"></div>



