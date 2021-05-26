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
	public function __construct($options = array()) {
		if($this->checkAPIParams()) {
			$default_options = [
				// Base URI is used with relative requests
				'base_uri' => self::$api_base_uri,
				'timeout' => 15.0,
			];

			if(defined('SOCILEN_API_VERIFY_SSL')) $default_options['verify'] = SOCILEN_API_VERIFY_SSL;

			$options = array_merge($default_options, $options);
			$this->client = new Client($options);

			$this->setToken();
		}
	}

	//</editor-fold>
	//<editor-fold desc="base functions" defaultstate="collapsed">
	private function checkAPIParams() {
		//Define empty API constants
		if(defined('SOCILEN_API_BASE_URI')) self::$api_base_uri = SOCILEN_API_BASE_URI;

		if(defined('SOCILEN_API_USER')) self::$api_user = SOCILEN_API_USER;

		if(defined('SOCILEN_API_PASSWORD')) self::$api_password = SOCILEN_API_PASSWORD;

		if(empty(self::$api_base_uri)) {
			$this->setError(400, "API base URI not set", " API base URI is not set, please set it");
		}
		else if(empty(self::$api_user)) {
			$this->setError(400, "API user not set", "API user is not set, please set it");
		}
		else if(empty(self::$api_password)) {
			$this->setError(400, "API password not set", "API password is not set, please set it");
		}
		else {
			return true;
		}
		return false;
	}

	private function safeJSONDecode($contents, string $alternative = null) {
		$response = json_decode($contents);
		if($response == null) {
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
			if($code != 200) {
				$this->setError($code, $response->getReasonPhrase(), $contents);
			}
			return $contents;
		} catch(RequestException $e) {
			$this->exceptionHandling($e);
		}
	}

	//</editor-fold>
	//<editor-fold desc="Requests" defaultstate="collapsed">
	//<editor-fold desc="User">
	public function newAddress($data) {
		return $this->getContents("users/addresses/new", ['json' => $data], 'POST');
	}

	public function newPhone($data) {
		return $this->getContents("users/phones/new", ['json' => $data], 'POST');
	}

	public function getAgreementsAll($data) {
		return $this->getContents("users/agreements/all", ['json' => $data], 'POST');
	}

	public function getAgreementsPending($data) {
		return $this->getContents("users/agreements/pending", ['json' => $data], 'POST');
	}
	//</editor-fold>


	//<editor-fold defaultstate="collapsed" desc="Borrower">
	public function getBorrower(int $code) {
		return $this->getContents("borrowers/{$code}");
	}

	public function getBorrowerProjects(int $code) {
		return $this->getContents("borrowers/{$code}/projects");
	}

	public function newNaturalBorrower($borrower) {
		return $this->getContents("borrowers/new/natural", ['json' => $borrower], 'POST');
	}

	public function newLegalBorrower($borrower) {
		return $this->getContents("borrowers/new/legal", ['json' => $borrower], 'POST');
	}

	//</editor-fold>
	//<editor-fold defaultstate="collapsed" desc="Documents">
	public function newDocument($document) {
		return $this->getContents("documents/new", ['json' => $document], 'POST');
	}

	public function getDocumentsAll($document) {
		return $this->getContents("documents/all", ['json' => $document], 'POST');
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

	public function requestAccreditedLender(int $lender_code) {
		return $this->getContents("lenders/{$lender_code}/requests/accredited");
	}

	//</editor-fold>
	//<editor-fold desc="Merchants" defaultstate="collapsed">
	public function getBorrowers() {
		return $this->getContents("merchants/borrowers");
	}

	public function getLenders() {
		return $this->getContents("merchants/lenders");
	}

	public function getLendersBasic() {
		return $this->getContents("merchants/lenders/basic");
	}

	public function getLoans() {
		return $this->getContents("merchants/loans");
	}

	public function getMerchantMovementsAll() {
		return $this->getContents("merchants/movements/all");
	}

	public function getMerchantMovementsInOut() {
		return $this->getContents("merchants/movements/inout");
	}

	public function getMerchantMovementsP2P() {
		return $this->getContents("merchants/movements/p2p");
	}

	public function getProjects() {
		return $this->getContents("merchants/projects");
	}

	public function getPublishedProjects() {
		return $this->getContents("merchants/projects/published");
	}

	// </editor-fold>
	//<editor-fold defaultstate="collapsed" desc="Payment">
	public function newPaymentTransaction($payment_transaction) {
		return $this->getContents("payments/transactions/new", ['json' => $payment_transaction], 'POST');
	}

	public function getMovementsAll($payment_movement) {
		return $this->getContents("payments/movements/all", ['json' => $payment_movement], 'POST');
	}

	public function getMovementsRetained($payment_movement) {
		return $this->getContents("payments/movements/retained", ['json' => $payment_movement], 'POST');
	}

	public function getMovementsNoRetained($payment_movement) {
		return $this->getContents("payments/movements/no-retained", ['json' => $payment_movement], 'POST');
	}

	public function newBankAccount($bank_account) {
		return $this->getContents("payments/bank-accounts/new", ['json' => $bank_account], 'POST');
	}

	public function linkBankAccount($bank_account) {
		return $this->getContents("payments/bank-accounts/link-payment-institution", ['json' => $bank_account], 'POST');
	}

	public function newBankAccountMandate($bank_account) {
		return $this->getContents("payments/bank-accounts/mandates/new", ['json' => $bank_account], 'POST');
	}

	public function signBankAccountMandate($bank_account) {
		return $this->getContents("payments/bank-accounts/mandates/sign", ['json' => $bank_account], 'POST');
	}

	public function updateBankAccountMandate($bank_account) {
		return $this->getContents("payments/bank-accounts/mandates/update", ['json' => $bank_account], 'POST');
	}

	public function getPaymentsPlan($data) {
		return $this->getContents("payments/plan", ['json' => $data], 'POST');
	}

	public function getPaymentsPlanByLender($data) {
		return $this->getContents("payments/plan/loan/{$data['loan_code']}/lender/{$data['lender_code']}");
	}

	public function newPayoutRequest($request_data) {
		return $this->getContents("payments/payouts/request", ['json' => $request_data], 'POST');
	}

	//</editor-fold>
	//<editor-fold desc="Types" defaultstate="collapsed">
	public function getTypes(string $type) {
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

	public function newProject($project) {
		return $this->getContents("projects/new", ['json' => $project], 'POST');
	}

	public function newProjectEvents($project) {
		return $this->getContents("projects/new/events", ['json' => $project], 'POST');
	}

	public function newProjectLegal($project) {
		return $this->getContents("projects/new/legal-person", ['json' => $project], 'POST');
	}

	public function newProjectNatural($project) {
		return $this->getContents("projects/new/natural-person", ['json' => $project], 'POST');
	}

	public function newProjectReduced($project) {
		return $this->getContents("projects/new/reduced", ['json' => $project], 'POST');
	}

	public function newProjectRealEstate($project) {
		return $this->getContents("projects/new/real-estate", ['json' => $project], 'POST');
	}

	public function getBid(int $project_code, int $bid_code) {
		return $this->getContents("projects/{$project_code}/bid/{$bid_code}");
	}

	public function getProjectBids(int $project_code) {
		return $this->getContents("projects/{$project_code}/bids");
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
			if($code == 200) {
				$this->authorization = "{$contents->token_type} {$contents->access_token}";
			}
			else {
				$this->setError($code, $response->getReasonPhrase(), $contents);
			}
		} catch(RequestException $e) {
			$this->exceptionHandling($e);
		}
	}

	//</editor-fold>
	//<editor-fold desc="Errors" defaultstate="collapsed">
	public function hasErrors() {
		return !is_null($this->error);
	}

	private function setError($status_code, $status_text, $response, $request = null) {
		$this->error = [
			'status_code' => $status_code,
			'status_text' => $status_text,
			'response' => $response
		];
		if($status_code == 400 || $request == null) return;

		$this->error['request'] = [
			'method' => $request->getMethod(),
			'uri' => $request->getUri()->__toString(),
			'body' => $request->getBody()->__toString()
		];
	}

	private function exceptionHandling(RequestException $e) {
		if($e->hasResponse()) {
			$status_code = $e->getResponse()->getStatusCode();
			$reason = $e->getResponse()->getReasonPhrase();
			$contents = $e->getResponse()->getBody()->getContents();
			$request = $e->getRequest();
			$this->setError($status_code, $reason, $this->safeJSONDecode($contents, $e->getMessage()), $request);
		}
		else {
			$this->setError(500, 'Internal Server error', $e->getMessage());
		}
	}

	//</editor-fold>

}