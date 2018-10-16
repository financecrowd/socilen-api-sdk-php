<?php

namespace Socilen;

//Info about requests params available on https://app.swaggerhub.com/apis/Socilen/api-socilen/docs

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SocilenAPI {

	private static $api_base_uri;
	private static $api_user;
	private static $api_password;
	private $client;
	private $authorization;
	public $error;

	//<editor-fold desc="Constructor" defaultstate="collapsed">
	public function __construct() {
		if ($this->checkAPIParams()) {
			$options = [
				// Base URI is used with relative requests
				'base_uri' => self::$api_base_uri,
				'timeout' => 5.0,
			];

			if (defined('SOCILEN_API_VERIFY_SSL'))
				$options['verify'] = SOCILEN_API_VERIFY_SSL;

			$this->client = new Client($options);

			$this->setToken();
		}
	}

	//</editor-fold>
	//<editor-fold desc="base functions" defaultstate="collapsed">
	private function checkAPIParams() {
		//Define empty API constants
		if (defined('SOCILEN_API_BASE_URI'))
			self::$api_base_uri = SOCILEN_API_BASE_URI;

		if (defined('SOCILEN_API_USER'))
			self::$api_user = SOCILEN_API_USER;

		if (defined('SOCILEN_API_PASSWORD'))
			self::$api_password = SOCILEN_API_PASSWORD;

		if (empty(self::$api_base_uri)) {
			$this->setError(400, "API base URI not set", " API base URI is not set, please set it");
		}
		else if (empty(self::$api_user)) {
			$this->setError(400, "API user not set", "API user is not set, please set it");
		}
		else if (empty(self::$api_password)) {
			$this->setError(400, "API password not set", "API password is not set, please set it");
		}
		else {
			return true;
		}
		return false;
	}

	private function safeJSONDecode($contents, string $alternative = null) {
		$response = json_decode($contents);
		if ($response == null) {
			$response = $alternative;
		}

		return $response;
	}

	private function getBaseOptions() {
		return [
			'headers' => [
				'Authorization' => $this->authorization,
				'Content-Type' => 'application/json',
			]
		];
	}

	private function getContents($path, $options = [], $method = 'GET') {
		$this->error = null; //Vaciar posibles errores existentes
		try {
			$response = $this->client->request($method, $path, array_merge($this->getBaseOptions(), $options));
			$code = $response->getStatusCode();
			$contents = $this->safeJSONDecode($response->getBody()->getContents());
			if ($code != 200) {
				$this->setError($code, $response->getReasonPhrase(), $contents);
			}
			return $contents;
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}

	//</editor-fold>
	//<editor-fold desc="Requests" defaultstate="collapsed">
	//<editor-fold defaultstate="collapsed" desc="Borrower">
	public function getBorrower(int $code) {
		return $this->getContents("borrowers/{$code}");
	}

	public function newNaturalBorrower($borrower) {
		return $this->getContents("borrowers/new/natural", ['json' => $borrower], 'POST');
	}

	public function newLegalBorrower($borrower) {
		return $this->getContents("borrowers/new/legal", ['json' => $borrower], 'POST');
	}

	//</editor-fold>
	//<editor-fold defaultstate="collapsed" desc="Lender">
	public function getLender(int $code) {
		return $this->getContents("lenders/{$code}");
	}

	public function newNaturalLender($lender) {
		return $this->getContents("lenders/new/natural", ['json' => $lender], 'POST');
	}

	public function newLegalLender($lender) {
		return $this->getContents("lenders/new/legal", ['json' => $lender], 'POST');
	}

	public function getLenderInvestments(int $code) {
		return $this->getContents("lenders/{$code}/investments");
	}

	public function getLenderInvestmentsInProccess(int $code) {
		return $this->getContents("lenders/{$code}/investments/in_process");
	}

	//</editor-fold>
	//<editor-fold defaultstate="collapsed" desc="Payment">
	public function newPaymentTransaction($payment_transaction) {
		return $this->getContents("payments/transactions/new", ['json' => $payment_transaction], 'POST');
	}

	public function getMovements($payment_movement) {
		return $this->getContents("payments/movements", ['json' => $payment_movement], 'POST');
	}

	public function newBankAccount($bank_account) {
		return $this->getContents("payments/bank-accounts/new", ['json' => $bank_account], 'POST');
	}

	public function linkBankAccount($bank_account) {
		return $this->getContents("payments/bank-accounts/link-payment-institution", ['json' => $bank_account], 'POST');
	}

	public function getPaymentsPlan(int $loan_code, int $lender_code = null, int $investment_code = null) {
		$path = "payments/plan/loan/{$loan_code}";
		if (!is_null($lender_code))
			$path .= "/lender/{$lender_code}";
		if (!is_null($lender_code))
			$path .= "/investment/{$investment_code}";
		return $this->getContents($path);
	}

	//</editor-fold>
	//<editor-fold desc="Types" defaultstate="collapsed">
	private function getTypes(string $type) {
		return $this->getContents("types/{$type}");
	}

	public function getTypesAddress() {
		return $this->getTypes("address");
	}

	public function getTypesDocuments() {
		return $this->getTypes("documents");
	}

	public function getTypesIDDocuments() {
		return $this->getTypes("id-documents");
	}

	public function getTypesMaritalStatus() {
		return $this->getTypes("marital-status");
	}

	public function getTypesProyectPurposes() {
		return $this->getTypes("purposes");
	}

	public function getTypesLoans() {
		return $this->getTypes("loans");
	}

	public function getTypesWorkingStatus() {
		return $this->getTypes("working-status");
	}

	public function getTypesPrincipalResidences() {
		return $this->getTypes("principal-residences");
	}

	public function getTypesSecondResidencess() {
		return $this->getTypes("second-residences");
	}

	public function getTypesPhones() {
		return $this->getTypes("phones");
	}

	public function getTypesGenders() {
		return $this->getTypes("genders");
	}

	public function getTypesCompanyTypes() {
		return $this->getTypes("company/types");
	}

	public function getTypesCompanySectors() {
		return $this->getTypes("company/sectors");
	}

	public function getTypesCompanyAdministrationSystems() {
		return $this->getTypes("company/administration-systems");
	}

	public function getTypesCompanyLegalRepresentatives() {
		return $this->getTypes("company/legal-representatives");
	}

	public function getTypesPerson() {
		return $this->getTypes("person");
	}

	//</editor-fold>
	//<editor-fold desc="Project" defaultstate="collapsed">
	public function getProject(int $code) {
		return $this->getContents("projects/{$code}");
	}

	public function newNaturalProject($project) {
		return $this->getContents("projects/new/new/natural-person", ['json' => $project], 'POST');
	}

	public function newLegalProject($project) {
		return $this->getContents("projects/new/legal-person", ['json' => $project], 'POST');
	}

	public function getBid(int $project_code, int $bid_code) {
		return $this->getContents("projects/{$project_code}/bid/{$bid_code}");
	}

	public function newBid($bid) {
		return $this->getContents("projects/bid/new", ['json' => $bid], 'POST');
	}

	//</editor-fold>
	//</editor-fold>
	//<editor-fold desc="Token" defaultstate="collapsed">
	public function renewToken() {
		$this->setToken();
	}

	private function setToken() {
		try {
			$form_params = [
				'grant_type' => 'password',
				'UserName' => self::$api_user,
				'password' => self::$api_password,
			];
			$options = ['form_params' => $form_params];
			$response = $this->client->request('POST', 'token', $options);
			$code = $response->getStatusCode();
			$contents = $this->safeJSONDecode($response->getBody()->getContents());
			if ($code == 200) {
				$this->authorization = "{$contents->token_type} {$contents->access_token}";
			}
			else {
				$this->setError($code, $response->getReasonPhrase(), $contents);
			}
		} catch (RequestException $e) {
			$this->exceptionHandling($e);
		}
	}

	//</editor-fold>
	//<editor-fold desc="Errors" defaultstate="collapsed">
	public function hasErrors() {
		return !is_null($this->error);
	}

	private function setError($status_code, $status_text, $response) {
		$this->error = [
			'status_code' => $status_code,
			'status_text' => $status_text,
			'response' => $response
		];
	}

	private function exceptionHandling(RequestException $e) {
		if ($e->hasResponse()) {
			$status_code = $e->getResponse()->getStatusCode();
			$reason = $e->getResponse()->getReasonPhrase();
			$contents = $e->getResponse()->getBody()->getContents();
			$this->setError($status_code, $reason, $this->safeJSONDecode($contents, $e->getMessage()));
		}
		else {
			$this->setError(500, 'Internal Server error', $e->getMessage());
		}
	}

	//</editor-fold>
}
