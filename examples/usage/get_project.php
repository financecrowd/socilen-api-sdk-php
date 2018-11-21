<?php
// Load composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php'; // 
require_once __DIR__ .  '/../../src/SocilenAPI.php'; // NOT necesary when lib is loaded by composer
//Define API constants
require_once __DIR__ .  '/../../constants.php';

use Socilen\SocilenAPI;

//Obtain an instance of SocilenAPI client, with it you can make all calls to desired methods until the token expires
//SocilenAPI constructor calls function renewToken() which makes a call to endpoint /token.
//Please verify for errors after get an instance of SocilenAPI client
$api = new SocilenAPI();

//Verify if an error occurred after calling a method.
if ($api->hasErrors()) {
	//TODO actions when there have been errors
	print_r($api->error);
	exit();
}

echo '<pre>';
// Get project object
$project_code = 0;
$project = $api->getProject($project_code);

//Verify if an error occurred after calling a method.
if ($api->hasErrors()) {
	//TODO actions when there have been errors
	print_r($api->error);
	exit();
}

//print it
print_r($project);

//print project amount
print_r($project->amount);

echo '</pre>';