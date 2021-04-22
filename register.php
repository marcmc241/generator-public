<!DOCTYPE html>
<html>
<head>
	<title>Generator neon</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Copyright 2016-17, Marc Masip-->
<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
<!--<link href="css/estils.css" rel="stylesheet" />-->
<style type="text/css">
	@font-face {
	  font-family: 'Open Sans';
	  font-style: normal;
	  font-weight: 400;
	  src: local('Open Sans Regular'), local('OpenSans-Regular'), url(font/os.woff2) format('woff2');
	  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}
	body{
		text-align: center;
		color: rgba(255,255,255,.9);
		font-family:'Open Sans',"Gotham","Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,sans-serif;
		background-color: #5059d2;
		background: linear-gradient(to bottom right, #5059d2, #8d53d3);
		background: -webkit-linear-gradient(to bottom right, #5059d2, #8d53d3);
		background: -moz-linear-gradient(to bottom right, #5059d2, #8d53d3);
		background: -ms-linear-gradient(to bottom right, #5059d2, #8d53d3);
		background: -o-linear-gradient(to bottom right, #5059d2, #8d53d3);
		min-height: 100vh;
	}
	input{
	  font-size:18px;
	  padding:10px 10px 10px 5px;
	  display:block;
	  width:300px;
	  border:none;
	  border-bottom:1px solid #f6ca48;
	  background-color: rgba(0,0,0,0);
	}
	input:focus, textarea:focus, select:focus, button:focus, #searcher:focus, .button:focus{ outline:none; }
	.status{
		padding: 10px;
		background-color: #FFF;
		border-radius: 10px;
	}
	.red{
		color: #E22;
	}
	form{
		width: 320px;
		margin: auto;
	}
	::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
	  color: #AAA;
	  opacity: 1; /* Firefox */
	}
	button, input[type=submit], .button{
		height: 40px;
		display: inline-block;
		background-color: rgba(0,0,0,0);
		padding: 2px;
		cursor: pointer;
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
</style>
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
</head>

<body>
<?PHP
	/*require_once("header.php");*/
	if(isset($_GET['x'])){
		$x = $_GET['x'];
		if ($x==1){
		print "<p class='status red'>DB error</p>\n";
		}
		if ($x==2){
			print "<p class='status red'>Passwords don't match</p>\n";
		}
		if ($x==3){
			print "<p class='status red'>Username already in use</p>\n";
		}
	}
?>
<section id="sec1">
			<p>Registrate en el Generator</p>
			<form id="formlogin" action="registerin.php" method="post">
				<input type="text" placeholder="usuario" name="user" id="user" required><br>
				<input type="password" placeholder="contraseña" name="pass" id="pass" required><br>
				<input type="password" placeholder="repite contraseña" name="pass2" id="pass2" required><br>
				<input type="submit" value="Registrarse" id="loginbtn"><br><br>
			</form>
			<br><br><br>
			<p>Estos datos serán guardados en una base de datos, se guardará sólo el usuario y la contraseña, que irán vinculados a una id de usuario. Las contraseñas son encriptadas por MD5 antes de ser guardadas, no es el método más seguro que existe pero sí es sencillo y más seguro que guardarlas directamente en texto. En la Base de datos solo se puede ver la contraseña encriptada, no la original.</p>
			<br><br><br>
</section>
</body>
</html>