<?php

/*
 *
 * Méthode cheap pour limiter le nombre de requêtes par IP
 *
 */
$path = dirname(__FILE__).'/logs/mail/'.date('Y-m-d').'/'.date('H').'/'.substr(date('i'),0,1).'/';
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

require('lib/Twilio/Twilio.php');
$client = new Services_Twilio($config['twilio']['key'], $config['twilio']['secret']);

//Le xml qui sera appelé lorsque la ligne va répondre
$twiml = 'http://'.$_SERVER['HTTP_HOST'].'/etudiants/answer.php';

//Numéro de différents bureaux
$to = array('4186440664','5148734792','5143286006','8195695646','4186435321','5148733411');

$to = $to[array_rand($to)];
$call = $client->account->calls->create($config['twilio']['number'], $to, $twiml, array(
	"IfMachine"=>"Continue"//Pour être compatible avec les boites vocales
));

die('DONE');