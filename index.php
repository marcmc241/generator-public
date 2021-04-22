<?PHP
	require_once("conexion_PDO.php");
	$block=0;//al principio no bloqueamos la entrada por defecto
	if (session_status() == PHP_SESSION_NONE) {
		ini_set('session.gc_maxlifetime', 3600);
		session_set_cookie_params(3600);
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

		if((isset($_SESSION["idu"])) && ($_SESSION["idu"]>=0)){//si hi ha user
			if((isset($_SESSION["admin"])) && ($_SESSION["admin"]>=1)){//es admin
				header("location:principal.php?x=1");
			}else{
				header("location:principal.php?x=4");
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Generator 3000</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Copyright 2016-17, Marc Masip-->
<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<style type="text/css">
	*{
		margin: 0px;
		padding: 0px;
		text-align: center;
		font-family:'Open Sans',"Gotham","Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,sans-serif;
	}
	
	@font-face {
	  font-family: 'Open Sans';
	  font-style: normal;
	  font-weight: 400;
	  src: local('Open Sans Regular'), local('OpenSans-Regular'), url(font/os.woff2) format('woff2');
	  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}
	
	p{
		color: red;
	}
	body{
		background : -moz-linear-gradient(0.26% 100.26% 45deg,rgba(5, 5, 5, 1) 0%,rgba(74, 88, 164, 1) 100%);
		background : -webkit-linear-gradient(45deg, rgba(5, 5, 5, 1) 0%, rgba(74, 88, 164, 1) 100%);
		background : -webkit-gradient(linear,0.26% 100.26% ,99.74% -0.26% ,color-stop(0,rgba(5, 5, 5, 1) ),color-stop(1,rgba(74, 88, 164, 1) ));
		background : -o-linear-gradient(45deg, rgba(5, 5, 5, 1) 0%, rgba(74, 88, 164, 1) 100%);
		background : -ms-linear-gradient(45deg, rgba(5, 5, 5, 1) 0%, rgba(74, 88, 164, 1) 100%);
		background : linear-gradient(45deg, rgba(5, 5, 5, 1) 0%, rgba(74, 88, 164, 1) 100%);
		width : 100%;
		height : 98vh;
		margin: 0px;
		padding: 0px;
		text-align: center;
		
	}
	#bg{
		max-width: 650px;
		width: 100%;
		height: 90%;
		margin: auto;
		margin-top: 20px;
		position: relative;
		background-image: url("img/generator_logo_bg.png");
		background-repeat: no-repeat, no-repeat;
		background-size: contain;
		background-position: center top;
	}
	@media only screen and (max-width: 650px) {
		#bg{
			background-size: cover;
		}
	}
	h1{
		position: relative;
		top: 150px;
		color: #5059d2;
		font-size: 2.5em;
		transition: all 0.2s;
		animation-delay: .3s;
	}
	#main{
		margin: auto;
		width: 50%;
		height: 100%;
		min-width: 320px;
		
	}
	form{
		text-align: center;
		position: relative;
		top: 200px;
		margin: auto;
		width: 320px;
		min-height: 320px;
		height: auto;
		animation-delay: 1.2s;
	}
	input{
	  font-size:18px;
	  padding:10px 10px 10px 5px;
	  display:block;
	  width:300px;
	  border:none;
	  text-align: left;
	  border-bottom:1px solid #757575;
	  background-color: rgba(0,0,0,0);
	}
	input:focus, textarea:focus, select:focus, button:focus, #searcher:focus, .button:focus{ outline:none; }

	/* LABEL ======================================= */
	.label{
	  color:#444; 
	  font-size:18px;
	  font-weight:normal;
	  position:absolute;
	  pointer-events:none;
	  left:5px;
	  top:10px;
	  margin: 0px;
	  transition:0.2s ease all; 
	  -moz-transition:0.2s ease all; 
	  -webkit-transition:0.2s ease all;
	}

	/* active state */
	input:focus ~ .label, input:valid ~ .label{
	  top:-20px;
	  font-size:14px;
	  color:#5059d2;
	}

	/* BOTTOM BARS ================================= */
	.bar{ position:relative; display:block; width:300px; }
	.bar:before, .bar:after{
	  content:'';
	  height:2px; 
	  width:0;
	  bottom:1px; 
	  position:absolute;
	  background:#5059d2; 
	  transition:0.2s ease all; 
	  -moz-transition:0.2s ease all; 
	  -webkit-transition:0.2s ease all;
	}
	.bar:before{
	  left:50%;
	}
	.bar:after{
	  right:50%; 
	}

	/* active state */
	input:focus ~ .bar:before, input:focus ~ .bar:after {
	  width:50%;
	}

	/* HIGHLIGHTER ================================== */
	.highlight {
	  position:absolute;
	  height:60%; 
	  width:100px; 
	  top:25%; 
	  left:0;
	  pointer-events:none;
	  opacity:0.5;
	}

	/* active state */
	input:focus ~ .highlight {
	  -webkit-animation:inputHighlighter 0.3s ease;
	  -moz-animation:inputHighlighter 0.3s ease;
	  animation:inputHighlighter 0.3s ease;
	}

	/* ANIMATIONS ================ */
	@-webkit-keyframes inputHighlighter {
		from { background:#5059d2; }
	  to 	{ width:0; background:transparent; }
	}
	@-moz-keyframes inputHighlighter {
		from { background:#5059d2; }
	  to 	{ width:0; background:transparent; }
	}
	@keyframes inputHighlighter {
		from { background:#5059d2; }
	  to 	{ width:0; background:transparent; }
	}
	.styleinput{
		text-align: left;
		position: relative;
		margin-top: 30px;
	}
	button, input[type=submit], .button{
		height: 45px;
		display: inline-block;
		background-color: rgba(0,0,0,0);
		padding: 2px;
		cursor: pointer;
		color: #222;
		padding: 10px 10px 10px 10px;
		margin-top: 10px;
		border-style: solid;
		border-width: 1px;
		border-color: #f6ca48;
		border-radius: 2px;
		text-align: center;
		text-decoration: none;
		transition: all 0.2s;
	}

	button:hover, input[type=submit]:hover, .button:hover{
		background-color: #f6ca48;
		color: #FEFEFE;
		border-color: #f6ca48;
	}
	.status{/*Mensaje del sistema*/
		margin: 0px;
		z-index: 99;
		position: fixed;
		top: 90px;
		left: 0px;
		width: auto;
		max-width: 100%;
		height: auto;
		background-color: #5059d2;
		color: #FEFEFE;
		font-size: 1em;
		padding: 20px;
		transition: all 1s ease-in-out;
		display: inline-block;
		vertical-align: middle;
		border-radius: 5px;
	}.red{
		color: #FF5741;
	}.error{
	color: #FF5741;
	}
	@media only screen and (max-width: 600px) {
		h1{
			top: 100px;
		}
		form{
			top: 120px;
		}
	}

</style>
</head>

<body>
	<div id="bg" class="animated fadeInUp">
	<div id='main'>
	<?PHP
	if ($block!=1) {
		
	
	print("<h1 class='animated zoomInDown'>Generator <span class='error'>neon</span></h1>");
	

					
	if((!isset($_SESSION["idu"])) || ($_SESSION["idu"]<0)){
			print "<form id='formlogin' action='login.php' method='post' class='animated fadeIn'>\n
						<div class='styleinput'>      
					      <input type='text' id='user' name='user' required>
					      <span class='highlight'></span>
					      <span class='bar'></span>
					      <label class='label' for='user'>User</label><br>
					    </div>\n
					    
					    <div class='styleinput'>      
					      <input type='password' id='pass' name='pass' required>
					      <span class='highlight'></span>
					      <span class='bar'></span>
					      <label class='label' for='pass'>Password</label><br>
					    </div>\n<br>
						<input type='submit' value='Login' id='loginbtn'><br><br>\n
					</form>\n";
	}
	if(isset($_GET['x'])){
		$x = $_GET['x'];
		if ($x==1){
		print "<p class='status red'>Wrong user/pass</p>\n";
		}
		if ($x==2){
			print "<p class='status'>Logged Out Succesfully</p>\n";
		}
		if ($x==3){
			print "<p class='status'>Contact admin for approval</p>\n";
		}
	}
	
	}

?>
</div>
</div>
<script>//delete get parameters 
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
    }
</script>		
</body>
</html>