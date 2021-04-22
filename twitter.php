<?PHP
require_once('TwitterAPIExchange.php');
require_once("conexion_PDO.php");
require_once('configuration.php');

function tweet($text, $image){

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

	$text=$text." ".$hash;

	$twitter = new TwitterAPIExchange(array(
	    'oauth_access_token' => ACCESS_TOKEN,
	    'oauth_access_token_secret' => ACCESS_TOKEN_SECRET,
	    'consumer_key' => CONSUMER_KEY,
	    'consumer_secret' => CONSUMER_SECRET
	));
	
	$data = file_get_contents($image);
	$base64 = base64_encode($data);

	$url = 'https://upload.twitter.com/1.1/media/upload.json';
	$requestMethod = 'POST';
	$postData = array('media_data' => $base64);
	$json=$twitter->buildOauth($url, $requestMethod)->setPostfields($postData)->performRequest();
	$res=json_decode($json);

	$url = 'https://api.twitter.com/1.1/statuses/update.json';
	$requestMethod = 'POST';
	$postData = array('status' => $text, 'media_ids' => $res->media_id_string);
	$response=$twitter->buildOauth($url, $requestMethod)->setPostfields($postData)->performRequest();
	$response=json_decode($response);
	$twid=$response->id;
	
	return $twid;
}

function deletetweet($twid){

	$twitter = new TwitterAPIExchange(array(
	    'oauth_access_token' => ACCESS_TOKEN,
	    'oauth_access_token_secret' => ACCESS_TOKEN_SECRET,
	    'consumer_key' => CONSUMER_KEY,
	    'consumer_secret' => CONSUMER_SECRET
	));
	
	$url = 'https://api.twitter.com/1.1/statuses/destroy/'.$twid.'.json';
	$requestMethod = 'POST';
	$postData = array('id' => $twid);

	$response=$twitter->buildOauth($url, $requestMethod)->setPostfields($postData)->performRequest();
	$response=json_decode($response);
	$twid2=$response->id;
	
	if ($twid2==$twid){
		return true;
	}else{
		return false;
	}
	
}

?>