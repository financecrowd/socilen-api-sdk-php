<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Socilen\SocilenAPI;

//Define API constants
if (!defined('SOCILEN_API_BASE_URI'))
	define("SOCILEN_API_BASE_URI", 'https://api-sandbox.socilen.com/');
if (!defined('SOCILEN_API_USER'))
	define("SOCILEN_API_USER", 'api_user');
if (!defined('SOCILEN_API_PASSWORD'))
	define("SOCILEN_API_PASSWORD", 'api_passwd');

//SSL Verify, only nedded on dev enviorements
//if (!defined('SOCILEN_API_VERIFY_SSL'))
//	define("SOCILEN_API_VERIFY_SSL", false);

//Obtain an instance of SocilenAPI, with it you can make all calls to desired methods until the token expires
$api = new SocilenAPI();
//TODO Call the desired method of $api

//Verify if an error occurred after calling a method
if ($api->hasErrors()) {
	//TODO actions when there have been errors
	print_r($api->error);
	//If the token has expired it can be renewed with $api->renewToken();
}
else {
	//TODO actions when there have been NO errors
	print_r("Todo bien, todo correcto");
}