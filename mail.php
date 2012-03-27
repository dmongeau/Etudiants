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




/*
 *
 * Préparation de curl
 *
 */
header('Content-type: text/plain; charset="utf-8"');

$headers = array(
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3',
	'Accept-Encoding: gzip,deflate,sdch',
	'Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4',
	'Cache-Control: max-age=0',
	'Host: www.mels.gouv.qc.ca',
	'Origin: http://www.mels.gouv.qc.ca/ministere/nousJoindre/index.asp?page=accueil',
	'Referer: http://www.mels.gouv.qc.ca/ministere/nousJoindre/index.asp?page=accueil',
);

$ua = array('Mozilla','Opera','Microsoft Internet Explorer','Google Chrome');
$op = array('Windows','Windows XP','Linux','Windows NT','Windows 2000','OSX');
$agent = $ua[rand(0,2)].'/'.rand(1,8).'.'.rand(0,9).' ('.$op[rand(0,5)].' '.rand(1,7).'.'.rand(0,9).';)';

$options = array(
	CURLOPT_RETURNTRANSFER => true,         // return web page
	CURLOPT_ENCODING       => "",           // handle all encodings
	CURLOPT_USERAGENT      => $agent,     // who am i
	CURLOPT_AUTOREFERER    => true,         // set referer on redirect
	CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
	CURLOPT_TIMEOUT        => 120,          // timeout on response
	CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
	CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
	CURLOPT_SSL_VERIFYPEER => false,        //
	CURLOPT_VERBOSE        => 1,                //
	CURLOPT_HTTPHEADER => $headers,
	CURLOPT_HEADER => true,
	
	/*
	 *
	 * Si vous avez tor d'installé sur votre serveur
	 *
	 */
	//CURLOPT_PROXY => '127.0.0.1:9050',
	//CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5
);


/*
 *
 * On fait une première requête pour avoir un cookie de session
 *
 */
$url = 'http://www.mels.gouv.qc.ca/ministere/nousJoindre/index.asp?page=accueil';
$ch      = curl_init($url);
curl_setopt_array($ch,$options);
$content = curl_exec($ch);
$err     = curl_errno($ch);
$errmsg  = curl_error($ch) ;
$header  = curl_getinfo($ch);
curl_close($ch);

preg_match_all('/^Set-Cookie: (.*?);/m', $content, $match);
$cookies = $match[1];


/*
 *
 * Un deuxième requête pour avoir un token nécessaire à l'envoie du formulaire de contact
 *
 */
$url = 'http://www.mels.gouv.qc.ca/ministere/nousJoindre/inc/token.asp';
$ch      = curl_init($url);
curl_setopt_array($ch,$options);
curl_setopt($ch, CURLOPT_COOKIE, implode('&',$cookies));
curl_setopt($ch, CURLOPT_HEADER, false);

$token = curl_exec($ch);
curl_close($ch);


/*
 *
 * Envoie du formulaire de contact
 *
 */
$data = array();
$data[] = 'action=send';
$data[] = 'nom=Un+citoyen';
$data[] = 'quebec=oui';
$data[] = 'region=Capitale-Nationale';
$data[] = 'statut=';
$data[] = 'organisme=';
$data[] = 'adresse=';
$data[] = 'courriel='.rawurlencode('appuie.ca@gmail.com');
$data[] = 'courrielConfirm='.rawurlencode('appuie.ca@gmail.com');
$data[] = 'subject='.rawurlencode('Un citoyen appuie les étudiants en grève');
$data[] = 'demande='.rawurlencode('Un citoyen appuie les étudiants en grève');
$data[] = 'btnSubmit=';
$data[] = 'ts='.rawurlencode($token);

$url = 'http://www.mels.gouv.qc.ca/ministere/nousJoindre/index.asp?page=accueil';

$ch      = curl_init($url);
curl_setopt_array($ch,$options);
curl_setopt($ch, CURLOPT_COOKIE, implode('&',$cookies));
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&',$data)); 
$content = curl_exec($ch);
$err     = curl_errno($ch);
$errmsg  = curl_error($ch) ;
$header  = curl_getinfo($ch);
curl_close($ch);



if(preg_match('/Message envoy\&eacute\;/',$content)) {
	echo 'DONE';
} else {
	echo 'ERROR';
}
