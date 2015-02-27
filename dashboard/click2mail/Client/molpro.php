<?php
/**
 * molpro class file
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */

/**
 * CreateDocument class
 */
require_once 'CreateDocument.php';
/**
 * CreateDocumentResponse class
 */
require_once 'CreateDocumentResponse.php';
/**
 * return_status class
 */
require_once 'return_status.php';
/**
 * CreateAddressList class
 */
require_once 'CreateAddressList.php';
/**
 * name_value class
 */
require_once 'name_value.php';
/**
 * CreateAddressListResponse class
 */
require_once 'CreateAddressListResponse.php';
/**
 * CheckListStatus class
 */
require_once 'CheckListStatus.php';
/**
 * CheckListStatusResponse class
 */
require_once 'CheckListStatusResponse.php';
/**
 * CreateJob class
 */
require_once 'CreateJob.php';
/**
 * return_address_details class
 */
require_once 'return_address_details.php';
/**
 * CreateJobResponse class
 */
require_once 'CreateJobResponse.php';
/**
 * SubmitJob class
 */
require_once 'SubmitJob.php';
/**
 * billing_details class
 */
require_once 'billing_details.php';
/**
 * SubmitJobResponse class
 */
require_once 'SubmitJobResponse.php';
/**
 * status_code class
 */
require_once 'status_code.php';
/**
 * CheckJobStatus class
 */
require_once 'CheckJobStatus.php';
/**
 * CheckJobStatusResponse class
 */
require_once 'CheckJobStatusResponse.php';
/**
 * RequestTracking class
 */
require_once 'RequestTracking.php';
/**
 * RequestTrackingResponse class
 */
require_once 'RequestTrackingResponse.php';
/**
 * tracking_info class
 */
require_once 'tracking_info.php';
/**
 * CorrectAddress class
 */
require_once 'CorrectAddress.php';
/**
 * address_details class
 */
require_once 'address_details.php';
/**
 * CorrectAddressResponse class
 */
require_once 'CorrectAddressResponse.php';
/**
 * correct_address_details class
 */
require_once 'correct_address_details.php';
/**
 * MergeDocuments class
 */
require_once 'MergeDocuments.php';
/**
 * MergeDocumentsResponse class
 */
require_once 'MergeDocumentsResponse.php';
/**
 * CreateProof class
 */
require_once 'CreateProof.php';
/**
 * CreateProofResponse class
 */
require_once 'CreateProofResponse.php';

/**
 * molpro class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class molpro extends SoapClient {

  public function molpro($wsdl = "https://stage-soap.click2mail.com/molpro.asmx?wsdl", $options = array()) {
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param CreateDocument $parameters
   * @return CreateDocumentResponse
   */
  public function CreateDocument(CreateDocument $parameters) {
    return $this->__call('CreateDocument', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreateAddressList $parameters
   * @return CreateAddressListResponse
   */
  public function CreateAddressList(CreateAddressList $parameters) {
    return $this->__call('CreateAddressList', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CheckListStatus $parameters
   * @return CheckListStatusResponse
   */
  public function CheckListStatus(CheckListStatus $parameters) {
    return $this->__call('CheckListStatus', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreateJob $parameters
   * @return CreateJobResponse
   */
  public function CreateJob(CreateJob $parameters) {
    return $this->__call('CreateJob', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SubmitJob $parameters
   * @return SubmitJobResponse
   */
  public function SubmitJob(SubmitJob $parameters) {
    return $this->__call('SubmitJob', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CheckJobStatus $parameters
   * @return CheckJobStatusResponse
   */
  public function CheckJobStatus(CheckJobStatus $parameters) {
    return $this->__call('CheckJobStatus', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RequestTracking $parameters
   * @return RequestTrackingResponse
   */
  public function RequestTracking(RequestTracking $parameters) {
    return $this->__call('RequestTracking', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CorrectAddress $parameters
   * @return CorrectAddressResponse
   */
  public function CorrectAddress(CorrectAddress $parameters) {
    return $this->__call('CorrectAddress', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param MergeDocuments $parameters
   * @return MergeDocumentsResponse
   */
  public function MergeDocuments(MergeDocuments $parameters) {
    return $this->__call('MergeDocuments', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreateProof $parameters
   * @return CreateProofResponse
   */
  public function CreateProof(CreateProof $parameters) {
    return $this->__call('CreateProof', array(
            new SoapParam($parameters, 'parameters')
      ),
      array(
            'uri' => 'http://stage-soap.click2mail.com/molpro/',
            'soapaction' => ''
           )
      );
  }

}

?>
