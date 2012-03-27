<?php

/*
 *
 * Méthode cheap pour limiter le nombre de requêtes par IP
 *
 */
$path = dirname(__FILE__).'/logs/fax/'.date('Y-m-d').'/'.date('H').'/'.substr(date('i'),0,1).'/';
$file = str_replace('.','_',$_SERVER['REMOTE_ADDR']);
$index = 1;
if(file_exists($path.$file)) {
	$index = (int)file_get_contents($path.$file);
	if($index >= 8) {
		die('DONE');
	} else {
		$index++;
	}
} else if(!file_exists($path)) mkdir($path,0755,true);
file_put_contents($path.$file,$index);




$config = include 'config.php';

header('Content-type: text/plain; charset="utf-8"');

$data = '<html><body>Un citoyen appuie les etudiants en greve</body></html>'; 

require_once('lib/nusoap/nusoap.php');
$client = new soapclient("http://ws.interfax.net/dfs.asmx?wsdl", true);
$soapclient->http_encoding='utf-8';
$soapclient->defencoding='utf-8';
$params[] = array(
	'Username'      => $config['interfax']['username'],
	'Password'        => $config['interfax']['password'],
	'FaxNumber'       => '+14186467551',
	'Data'            => $data,
	'FileType'        => 'HTML'
);
 
$result = $client->call("SendCharFax", $params);
 
//echo $result["SendCharFaxResult"];
die('DONE');