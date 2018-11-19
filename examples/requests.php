<?php
// Load composer dependencies
require_once __DIR__ . '/../vendor/autoload.php'; // 
require_once __DIR__ .  '/../src/SocilenAPI.php'; // NOT necesary when lib is loaded by composer
//Define API constants
require_once __DIR__ .  '/../constants.php'; // NOT necesary when lib is loaded by composer

use Socilen\SocilenAPI;

class RequestExamples extends SocilenAPI{
	//Send a GET request to url
	public function get($url){
		$headers = [
				'Authorization' => $this->authorization, // "{grant_type} {token}" // "bearer 8bDBxauZVTA-oS_..._U6U1ggT3ahuJxmWLJIJk5tWaA8g"
				'Content-Type' => 'application/json',
		];
		
		$options = [
			'headers' => $headers
		];
		
		try {
			$response = $this->client->request('GET', $url, $options);
			$contents = json_decode($response->getBody()->getContents());
			return $contents;
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}
	
	//Send a POST request to url with json body
	public function postJSON($url, $json){
		$headers = [
				'Authorization' => $this->authorization, // "{grant_type} {token}" // "bearer 8bDBxauZVTA-oS_..._U6U1ggT3ahuJxmWLJIJk5tWaA8g"
				'Content-Type' => 'application/json',
		];
		
		$options = [
			'headers' => $headers,
			'body' => $json
		];
		
		try {
			$response = $this->client->request('POST', $url, $options);
			$contents = json_decode($response->getBody()->getContents());
			return $contents;
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}
	
	//Send a POST request to url with object body
	public function postObject($url, $object){
		$headers = [
				'Authorization' => $this->authorization, // "{grant_type} {token}" // "bearer 8bDBxauZVTA-oS_..._U6U1ggT3ahuJxmWLJIJk5tWaA8g"
				'Content-Type' => 'application/json',
		];
		
		$options = [
			'headers' => $headers,
			'body' => json_encode($object)
		];
		
		try {
			$response = $this->client->request('POST', $url, $options);
			$contents = json_decode($response->getBody()->getContents());
			return $contents;
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}
	//Send a POST request to url with object body
	public function postObject2($url, $object){
		$headers = [
				'Authorization' => $this->authorization, // "{grant_type} {token}" // "bearer 8bDBxauZVTA-oS_..._U6U1ggT3ahuJxmWLJIJk5tWaA8g"
		];
		
		$options = [
			'headers' => $headers,
			'json' => $object
		];
		
		try {
			$response = $this->client->request('POST', $url, $options);
			$contents = json_decode($response->getBody()->getContents());
			return $contents;
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}
	
	//Send a GET request to url whith query params
	public function getQuery($url, $params){
		$headers = [
				'Authorization' => $this->authorization,  // "{grant_type} {token}" // "bearer 8bDBxauZVTA-oS_..._U6U1ggT3ahuJxmWLJIJk5tWaA8g"
				'Content-Type' => 'application/json',
		];
		
		$options = [
			'headers' => $headers,
			'query' => $params
		];
		
		try {
			$response = $this->client->request('GET', $url, $options);
			$contents = json_decode($response->getBody()->getContents());
			return $contents;
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}
	
	//Send a GET request to /borrowers?borrower_code=$code
	public function getBorrowerQuery(int $code) {
		return $this->getContents("borrowers", ['query' => ['borrower_code' => $code]]);
	}
	
	//Send a GET request to /borrowers/{$code}
	public function getBorrower(int $code) {
		return $this->getContents("borrowers/{$code}");
	}
	
	//Send a POST request to /borrowers/natural sending object $borrower as json (json_encode() NOT necesary)
	public function newNaturalBorrower($borrower) {
		return $this->getContents("borrowers/natural", ['json' => $borrower], 'POST');
	}
	
	//Send a PUT request to /borrowers/natural sending object $borrower as json (json_encode() NOT necesary)
	public function updateNaturalBorrower($borrower) {
		return $this->getContents("borrowers/natural", ['json' => $borrower], 'PUT');
	}
	
	//Send a DELETE request to /borrowers/natural sending object $borrower as json (json_encode() NOT necesary)
	public function deleteNaturalBorrower($borrower) {
		return $this->getContents("borrowers/natural", ['json' => $borrower], 'DELETE');
	}
}
