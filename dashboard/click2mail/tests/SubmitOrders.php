<?php

include_once __DIR__ . '/../Client/Click2mail_Mailingonline_Api.php';

class ClientTest extends PHPUnit_Framework_TestCase {

	private $username;
	private $password;
	private $url;
	private $doc;
	private $dataTemp;
	private $pdf;
	private $docType;

	public function setUp() {

		/*$this -> username = getenv("C2M_USERNAME");
		$this -> password = getenv("C2M_PASSWORD");
		$this -> url = getenv("C2M_URL");
		$this -> jobTemp = getenv("C2M_JOB_TEMPLATE");
		$this -> dataTemp = getenv("C2M_DATA_TEMPLATE");
		$this -> pdf = getenv("C2M_PDF");
		$this -> docType = getenv("C2M_DOC_TYPE");
		$this -> addressList = getenv("ADDRESS_LIST_LOCATION"); */
		
		$this -> username = 'chirag_zaptech';
		$this -> password = 'Chirag123#';
		$this -> url = 'https://stage-soap.click2mail.com';
		$this -> jobTemp = '';
		$this -> dataTemp = '';
		$this -> pdf = 'postcard.pdf';
		$this -> docType = "Postcard 4.25 x 6";
		$this -> addressList = "NO LIST";
	}

	public function testOrderSubmit() {
		ini_set('default_socket_timeout', 1200);

		$url = $this -> url;

		//please change the username and password here
		$username = $this -> username;
		$password = $this -> password;
		$job_template_id = $this -> jobTemp;
		// Document id from the UI page
		$data_template = $this -> dataTemp;
		// Address List Mapping id from the UI page.

		//$path_to_pdf = "fixtures/8.5x11.pdf";
		$path_to_pdf = __DIR__ . "/fixtures/" . $this -> pdf;
		
		$doList = true;
		if ($this -> addressList == "NO LIST")
		{
			$doList = false;
		}
		else
		{
			$path_to_list = __DIR__ . "/fixtures/" . $this -> addressList;
		}
		
		$document_type = $this -> docType;

		$new_document_name = "upload_doc_" . time();
		$image_file_type = array('PDF' => 1);

		$api = new Click2mail_Mailingonline_Api($url, $username, $password);

		$uploaded_file_id = $api -> UploadDocument($path_to_pdf);
		$this -> assertTrue(!empty($uploaded_file_id));

		echo "File token is ${uploaded_file_id}";

		echo "//Create Document From Template\n";

		try {

			$response = $api -> CreateDocument($new_document_name, $document_type, $image_file_type['PDF'], $uploaded_file_id);
			print_r($response);
			echo "creating document from template for " . $path_to_pdf . "\n";
			echo "document id " . $response -> document_creation_status -> id . "\n";
			echo "document creation status description " . $response -> document_creation_status -> description . "\n";
			echo "document creation status id " . $response -> document_creation_status -> status_id . "\n";
			$new_document_name = uniqid();

		} catch(Exception $e) {
			echo $e -> getMessage();

		}

		try {
			$document_id = $response -> document_creation_status -> id;

			echo "Document id {$document_id} \n";
		} catch(Exception $e) {
			echo $e -> getMessage();

		}


		// TARGET ADDRESS
		$status_id = 0;

		//Hardcoded address data
		$data = array(0 => array( array('name' => 'name', 'value' => 'Owen Zanzal'), array('name' => 'organization', 'value' => 'Click2Mail'), array('name' => 'address1', 'value' => '92-G Industrial Dr'), array('name' => 'address2', 'value' => ''), array('name' => 'address3', 'value' => ''), array('name' => 'city', 'value' => 'Troy'), array('name' => 'state', 'value' => 'VA'), array('name' => 'postalCode', 'value' => '22974'), array('name' => 'country', 'value' => 'USA')));
		
		if ($doList)
		{
			//Logic which will pull addresses from a list
			$lines = array();
			$handle = fopen($path_to_list, "r");
			if ($handle)
			{
				while (($buffer = fgets($handle)) !== false)
				{
					array_push($lines, $buffer);
				}
				fclose($handle);
			}
			$lineCount = 0;
			$address = array();
			foreach($lines as $address_line)
			{
				if ($lineCount == 0)
				{
					$headers = str_getcsv($address_line, ",");
					$lineCount++;
				}
				else {
					$address_info = str_getcsv($address_line, ",");
					
					for ($i = 0; $i < count($address_info); $i++)
					{
						$address_fields = array('name' => $headers[$i], 'value' => $address_info[$i]);
						array_push($address, $address_fields);
					}
					array_push($data, $address);
				}
			}
			//End list parsing logic
		}

		try {
			//print_r($data);
			$response = $api -> CreateAddressList($data_template, $data);
			print_r($response);
		} catch(Exception $e) {
			echo $e -> getMessage();
		}

		$list_id = $response -> list_id;
		echo "token " . $list_id . "\n";
		//just to make sure that list creation is complete in the background
		sleep(10);
		$list_status = 0;
		try {
			while ($list_status != 3) {

				$response = $api -> CheckListStatus($list_id);
				// print_r($response);
				$list_status = $response -> list_status -> status_id;
				echo "list status id " . $list_status . "\n";
				echo "list status description " . $response -> list_status -> description . "\n";
				echo "list id " . $response -> list_status -> id . "\n";
				$data_list_id = $response -> list_status -> id;
				if ($list_status == 9) {
					throw new Exception('There is an error with processing your list.');
				}

			}
		} catch(Exception $e) {
			echo $e -> getMessage();
			$data_list_id = 3364;
		}

		$return_address = array('name' => 'Fred McIntosh', 'business' => '', 'address' => '234 Main Street', 'city' => 'Des Moines', 'state' => 'Iowa', 'zip' => '55555', 'country' => ' ', 'ancillary_endorsement' => ' ');

		try {
			echo "//create a job \n";
			$response = $api -> CreateJob($document_id, $data_list_id, $job_template_id, $return_address);
			//print_r($response);
			$job_id = $response -> job_status -> id;
			echo "job id is " . $job_id . "\n";
		} catch(Excpetion $e) {
			echo $e -> getMessage();
		}

		try {
			echo "//submit a job \n";
			$billing_details = array('bill_name' => '', //Required
			'bill_address1' => '', //Required
			'bill_city' => '', //Required
			'bill_state' => '', //Required
			'bill_zip' => '', //Required
			'bill_type' => 'Invoice', //Required
			'bill_number' => '', //Pass empty string if no value is required
			'bill_exp_month' => '', //Pass empty string if no value is required
			'bill_exp_year' => '');
			//Pass empty string if no value is required
			$response = $api -> SubmitJob($job_id, $billing_details);
			// print_r($response);
			echo "job submission sucessful?? " . $response -> submit_job_result -> description . "\n";
			echo "job submission success id " . $response -> submit_job_result -> status_id . "\n";

		} catch(Excpetion $e) {
			echo $e -> getMessage();
		}

		try {
			//check job status
			$response = $api -> CheckJobStatus($job_id);
			// print_r($response);
			echo "job status for the submitted job " . $response -> CheckJobStatusResult -> description . "\n";
			echo "job status id for the submitted job " . $response -> CheckJobStatusResult -> status_id . "\n";
		} catch(Excpetion $e) {
			echo $e -> getMessage();
		}

		try {
			//view the proof
			$response = $api -> CreateProof($job_id);
			// print_r($response);
			echo "proof url for the submitted job  " . $response -> preview_id . "\n\n";
			// echo "job status id for the submitted job ".$response->CheckJobStatusResult->status_id;
		} catch(Excpetion $e) {
			echo $e -> getMessage();
		}

	}

}
