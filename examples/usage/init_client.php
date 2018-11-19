<?php
// Load composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php'; // 
require_once __DIR__ .  '/../../src/SocilenAPI.php'; // NOT necesary when lib is loaded by composer
//Define API constants
require_once __DIR__ .  '/../../constants.php'; // NOT necesary when lib is loaded by composer

use Socilen\SocilenAPI;

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