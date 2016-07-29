<?php
/**
 * 
 * GetBalanceAPIClient
 * 
 * A class which facilitates the interaction with Payza's 
 * GetBalance API. GetBalanceAPIClient class allows user to create 
 * the data to be sent to the API in the correct format and 
 * retrieve the response. 
 * 
 * 
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY
 * OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE IMPLIED WARRANTIES OF FITNESS FOR A PARTICULAR PURPOSE.
 *  
 * @author Payza
 * @copyright 2010
 */

class GetBalanceAPIClient
{
    /**
     * The API's response variables
     */
	private $responseArray;

    /**
     * The server address of the GetBalanceAPI
     */
    private $server = 'api.payza.com';

    /**
     * The exact URL of the GetBalanceAPI
     */
    private $url = '/svc/api.svc/GetBalance';

    /**
     * Your Payza user name which is your email address
     */
    private $myUserName = '';

    /**
     * Your API password that is generated from your Payza account
     */
    private $apiPassword = '';

    /**
     * The data that will be sent to the GetBalanceAPI
     */
    public $dataToSend = '';


    /**
     * GetBalanceAPIClient::__construct()
     * 
     * Constructs a GetBalanceAPIClient object
     * 
     * @param string $userName Your Payza user name.
     * @param string $password Your API password.
     */
    public function __construct($userName, $password)
    {
        $this->myUserName = $userName;
        $this->apiPassword = $password;
        $this->dataToSend = '';
    }


    /**
     * GetBalanceAPIClient::setServer()
     * 
     * Sets the $server variable
     * 
     * @param string $newServer New web address of the server.
     */
    public function setServer($newServer = '')
    {
        $this->server = $newServer;
    }


    /**
     * GetBalanceAPIClient::getServer()
     * 
     * Returns the server variable
     * 
     * @return string A variable containing the server's web address.
     */
    public function getServer()
    {
        return $this->server;
    }


    /**
     * GetBalanceAPIClient::setUrl()
     * 
     * Sets the $url variable
     * 
     * @param string $newUrl New url address.
     */
    public function setUrl($newUrl = '')
    {
        $this->url = $newUrl;
    }


    /**
     * GetBalanceAPIClient::getUrl()
     * 
     * Returns the url variable
     * 
     * @return string A variable containing a URL address.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * GetBalanceAPIClient::buildPostVariables()
     * 
     * Builds a URL encoded post string which contains the variables to be 
     * sent to the API in the correct format. 
     * 
     * @param string $currency 3 letter ISO-4217 currency code.
     * 
     * @return string The URL encoded post string
     */
    public function buildPostVariables($currency)
    {
        $this->dataToSend = sprintf("USER=%s&PASSWORD=%s&CURRENCY=%s",
            urlencode($this->myUserName), urlencode($this->apiPassword), urlencode($currency));
        return $this->dataToSend;
    }


    /**
     * GetBalanceAPIClient::send()
     * 
     * Sends the URL encoded post string to the GetBalanceAPI 
     * using cURL and retrieves the response.
     * 
     * @return string The response from the GetBalanceAPI.
     */
    public function send()
    {
        $response = '';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->getServer() . $this->getUrl());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->dataToSend);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }


    /**
     * GetBalanceAPIClient::parseResponse()
     * 
     * Parses the encoded response from the GetBalanceAPI
     * into an associative array.
     * 
     * @param string $input The string to be parsed by the function.
     */
    public function parseResponse($input)
    {
        parse_str($input, $this->responseArray);
    }


    /**
     * GetBalanceAPIClient::getResponse()
     * 
     * Returns the responseArray 
     * 
     * @return string An array containing the response variables.
     */
    public function getResponse()
    {
        return $this->responseArray;
    }


    /**
     * GetBalanceAPIClient::__destruct()
     * 
     * Destructor of the GetBalanceAPIClient object
     */
    public function __destruct()
    {
        unset($this->responseArray);
    }
}
?>