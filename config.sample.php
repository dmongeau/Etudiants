<?php


return array(

	/*
	 *
	 * Twilio est un service qui permet de programmer des appels téléphoniques et sms. C'est génial!
	 *
	 */
	'twilio' => array(
		'key' => 'IDENTIFIANT DE COMPTE',
		'secret' => 'CLÉ SECRÈTE',
		'number' => 'NUMÉRO DE TÉLÉPHONE SORTANT'
	),
	
	
	/*
	 *
	 * Service pour l'envoie de fax. Ils offrent la possibilité d'envoyer un nombre
	 * illimité de fax à un seul numéro pour le développement. Il suffit de mettre
	 * le numéro du fax de line beauchamp comme numéro de développement.
	 *
	 */
	'interfax' => array(
		'username' => 'VOTRE NOM DUTILISATEUR',
		'password' => 'VOTRE MOT DE PASSE'
	)

);