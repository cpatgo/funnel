<?php

	class MSCrmIFD {

	  public $usr;
	  public $pwd;
	  public $org;
	  public $domain;

	  public $crmHost;
	  private $crmTicket;
	  private $cURLHandle;

	  // performs login
	  public function getAccess() {
	      $matches = array();

	      // prepare request
	      $request = '<?xml version="1.0" encoding="utf-8"?>
	          <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	              <soap:Body>
	                  <Execute xmlns="http://schemas.microsoft.com/crm/2007/CrmDiscoveryService">
	                      <Request xsi:type="RetrieveCrmTicketRequest">
	                          <OrganizationName>' . $this->org . '</OrganizationName>
	                          <UserId>' . $this->domain . '\\' . $this->usr .'</UserId>
	                          <Password>' . $this->pwd . '</Password>
	                      </Request>
	                  </Execute>
	              </soap:Body>
	          </soap:Envelope>';

	         // setup headers
	        $headers = array(
	          "POST ". '/MSCRMServices/2007/SPLA/CrmDiscoveryService.asmx' ." HTTP/1.1",
	          "Host: " . $this->crmHost,
	          'Connection: Keep-Alive',
	          "SOAPAction: \"http://schemas.microsoft.com/crm/2007/CrmDiscoveryService/Execute\"",
	          "Content-type: text/xml;charset=\"utf-8\"",
	          "Content-length: ".strlen($request),
	        );

	          // Initialize cURL, this instance will be used for passing all requests to CrmService
	          $this->cURLHandle = curl_init();

	          curl_setopt($this->cURLHandle, CURLOPT_URL, 'http://' . $this->crmHost . '/MSCRMServices/2007/SPLA/CrmDiscoveryService.asmx');
	          curl_setopt($this->cURLHandle, CURLOPT_RETURNTRANSFER, 1);
	          curl_setopt($this->cURLHandle, CURLOPT_TIMEOUT, 60);
	          curl_setopt($this->cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	          curl_setopt($this->cURLHandle, CURLOPT_HTTPHEADER, $headers);

	          //assign soap XML
	          curl_setopt($this->cURLHandle, CURLOPT_POST, 1);
	          curl_setopt($this->cURLHandle, CURLOPT_POSTFIELDS, $request);

	          $response = curl_exec($this->cURLHandle);

	          if (preg_match('/<CrmTicket>(.*)<\/CrmTicket>/', $response, $matches)) {
	              $this->crmTicket = $matches[1];
	              return true;
	          } else {
	              throw new Exception('MSCrmIFD::getAccess() IFD auth failed');
	          }
	  }

	  public function request($request, $action) {
	      $headers = array(
	          "POST /MSCrmServices/2007/MSCrmServices/2007/CrmService.asmx HTTP/1.1",
	          "Host: " . $this->crmHost,
	          'Connection: keep-alive',
	          "SOAPAction: " . $action,
	          "Content-type: text/xml;charset=utf-8",
	          "Content-length: ".strlen($request),
	      );

	      var_dump('he ');

	      curl_setopt($this->cURLHandle, CURLOPT_URL, "http://" . $this->crmHost . "/MSCrmServices/2007/CrmService.asmx");
	      curl_setopt($this->cURLHandle, CURLOPT_HTTPHEADER, $headers);
	      curl_setopt($this->cURLHandle, CURLOPT_POST, 1);
	      curl_setopt($this->cURLHandle, CURLOPT_POSTFIELDS, $request);

	      // ticket is set into cookie, with that you dont need him in soap header anymore
	      // in fact this row is most important in whole struggle with this-
	      curl_setopt($this->cURLHandle, CURLOPT_COOKIE, 'MSCRMSession=ticket=' . $this->crmTicket . ';');

	      $response = curl_exec($this->cURLHandle);
	      $responseHeaders = curl_getinfo($this->cURLHandle);

	      if ($responseHeaders['http_code'] != 200) {
	          print_r($response);
	          die('MSCrmIFD::__doRequest() failed');
	      }

	      return $response;
	  }

	  public function getAuthHeader() {
	      $header = '<soap:Header>
	                  <CrmAuthenticationToken xmlns="http://schemas.microsoft.com/crm/2007/WebServices">
	                      <AuthenticationType xmlns="http://schemas.microsoft.com/crm/2007/CoreTypes">2</AuthenticationType>
	                      <OrganizationName xmlns="http://schemas.microsoft.com/crm/2007/CoreTypes">' . $this->org . '</OrganizationName>
	                  </CrmAuthenticationToken>
	              </soap:Header>';

	      return $header;
	  }

	  public function closeConnection() {
	      curl_close($this->cURLHandle);
	  }
	}

?>