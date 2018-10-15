<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../src/SocilenAPI.php';

//Obtain an instance of SocilenAPI, with it you can make all calls to desired methods until the token expires
$api = new Socilen\SocilenAPI();
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