<?php
class Click2mail_Mailingonline_Api{

	public $client;
	protected $upload_url;
	protected $url;

	public function Click2mail_Mailingonline_Api($url, $username, $password){
		$this->url = $url;
		$this->client_app_id = $username;
		$this->client_app_pwd = $password;
		$this->upload_url = $this->url . "/UploadDocument.aspx?clientid=" . $username . "&clientpwd=" . $password;
		$this->client = new SoapClient($this->url . "/molpro.asmx?WSDL");
	}

	//use the template_id you receive in UI
	public function CreateAddressList($template_id, $data)
	{	
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'data_template_id' => $template_id,
					'data' => $data); 

		$response = $this->client-> __soapCall('CreateAddressList',array($u));
		#print_r($response); die;
		return $response;

	}
     
	public function createJob($document_id,$data_list_id,$job_template_id,$return_address)
    {

		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'document_id' => $document_id,
					'data_list_id' => $data_list_id,
                    'job_template_id'=>$job_template_id,
                    'return_address' => $return_address); 
             
		$response = $this->client-> __soapCall('CreateJob',array($u));
		
		return $response;
	}

	public function CheckJobStatus ($job_id)
    {
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'job_id' => $job_id); 
             
		$response = $this->client-> __soapCall('CheckJobStatus',array($u));
		return $response;
	}

   	public function CreateProof ($job_id)
    {
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'jobid' => $job_id); 
             
		$response = $this->client-> __soapCall('CreateProof',array($u));
		return $response;
	}


	public function SubmitJob ($job_id,$billing_details)
    {
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'job_id' => $job_id,
					'billing_details' => $billing_details); 
             
		$response = $this->client-> __soapCall('SubmitJob',array($u));
		return $response;
	}


	public function SubmitJob3($document_id, $data_list_id, $mailing_type_code, $preferred_schedule_date, $billing_details, $return_address, $testing){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'document_id' => $document_id,
					'data_list_id' => $data_list_id,
					'mailing_type_code' => $mailing_type_code,
					'preferred_schedule_date' => $preferred_schedule_date,
					'billing_details' => $billing_details,
					'return_address' => $return_address); 
	
	    if($testing == false){
			$response = $this->client-> __soapCall('SubmitJob3',array($u));
	    }else{
			$response = $this->GenTestJobNumber();
		}
		return $response;
	}

	public function SubmitPreview($document_id, $data_list_id){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'document_id' => $document_id,
					'data_list_id' => $data_list_id); 

		$response = $this->client-> __soapCall('SubmitPreview',array($u));

		return $response;

	}

	public function CheckPreviewStatus($preview_id){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'preview_id' => $preview_id); 

		$response = $this->client-> __soapCall('CheckPreviewStatus',array($u));
		return $response;
	}

	public function GetPreviewPDFURL($preview_id){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'preview_id' => $preview_id); 

		$response = $this->client-> __soapCall('GetPreviewPDFURL',array($u));
		return $response;
	}

	
	public function CreateDocument($new_document_name, $document_type, $image_file_type, $uploaded_file_id){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'new_document_name' => $new_document_name,
					'document_type' => $document_type,
					'image_file_type' => $image_file_type,
					'uploaded_file_id' => $uploaded_file_id); 
		$response = $this->client-> __soapCall('CreateDocument',array($u));
		return $response;
	}

	public function CheckDocumentCreateStatus($document_creation_token){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'document_creation_token' => $document_creation_token);

		$response = $this->client-> __soapCall('CheckDocumentCreateStatus',array($u));
		return $response;
	}
	
	public function CheckListStatus($list_id){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'list_id' => $list_id);

		$response = $this->client-> __soapCall('CheckListStatus',array($u));
		return $response;
	}

	public function CompleteDocumentCreation($document_creation_token){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd,
					'document_creation_token' => $document_creation_token);

		$response = $this->client-> __soapCall('CompleteDocumentCreation',array($u));
		return $response;
	}

	public function CheckCreditBalance(){
		$u = array('client_app_id' => $this->client_app_id,
					'client_app_pwd' => $this->client_app_pwd
				);
		$response = $this->client-> __CheckCreditBalance('CheckCreditBalance',array($u));
		return $response;
	}
	
	public function UploadDocument($filePath) {
		$postParams["uplFile"] = "@" . $filePath;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $this->upload_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			print curl_error($ch);
			print "<br>Unable to upload file.";
			exit();
		}
		curl_close($ch);
		 
		return $response;

	}

	// Debug methods
	public function lastRequest(){
		return $this->client->__getLastRequest();
	}

	public function lastResponse(){
		return $this->client->__getLastResponse();
	}

	public function getApiMethods(){
		return $this->client->__getFunctions();
	}

	public function Debug($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}
