<?php
/**
 * 
 * MassPayClient
 * 
 * A class which facilitates the interaction with Payza's 
 * MassPay API. MassPayClient class allows user to create 
 * the data to be sent to the API in the correct format and 
 * retrieve the response. 
 * 
 * 
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY
 * OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE IMPLIED WARRANTIES OF FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * @author Payza
 * @copyright 2009
 */

class Payza_Masspay
{
    /**
     * The API's response variables
     */
    private $responseArray;

    /**
     * The server address of the MassPay API
     */
    //private $server = 'sandbox.Payza.com';
    private $server = 'api.payza.com';

    /**
     * The server address of the MassPay API Sandbox
     */
    private $serverSandbox = 'api.payza.com';

    /**
     * The URL of the MassPay API
     */
    private $url = '/svc/api.svc';

    /**
     * The URL of the MassPay API Sandbox
     */
    private $urlSandbox = '/api/api.svc';

    /**
     * The URL of the method
     */
    private $method = '';

    /**
     * Your Payza user name which is your email address
     */
    private $myUserName = '';

    /**
     * Your API password that is generated from your Payza account
     */
    private $apiPassword = '';

    /**
     * The data that will be sent to the MassPay API
     */
    public $dataToSend = '';


    /**
     * MassPayClient::__construct()
     * 
     * Constructs a MassPayClient object
     */
    public function __construct()
    {
        $this->dataToSend = '';
    }

    /**
     * @param string $userName Your Payza user name.
     */
    public function setUsername($userName)
    {
        $this->myUserName = $userName;
    }

    /**
     * @param string $password Your API password.
     */
    public function setPassword($password)
    {
        $this->apiPassword = $password;
    }


    /**
     * MassPayClient::setServer()
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
     * MassPayClient::getServer()
     * 
     * Returns the server variable
     * 
     * @return string A variable containing the server's web address.
     */
    public function getServer()
    {
        if($_SERVER['HTTP_HOST'] !== 'glchub.com'):
            return $this->serverSandbox;
        else:
            return $this->server;
        endif;
    }


    /**
     * MassPayClient::setUrl()
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
     * MassPayClient::getUrl()
     * 
     * Returns the url variable
     * 
     * @return string A variable containing a URL address.
     */
    public function getUrl()
    {
        if($_SERVER['HTTP_HOST'] !== 'glchub.com'):
            return $this->urlSandbox;
        else:
            return $this->url;
        endif;
    }

    /**
     * MassPayClient::buildPostVariables()
     * 
     * Builds a URL encoded post string which contains the variables to be 
     * sent to the API in the correct format. 
     * 
     * @param int $payments Array containing the payments to be made.
     * @param string $currency 3 letter ISO-4217 currency code.
     * @param string $receiverEmail Recipient's email address.
     * @param string $senderEmail Your secondary email (optional).
     * @param int $purchaseType A valid purchase type code.
     * @param int $testMode Test mode status.
     * 
     * @return string The URL encoded post string
     */
    public function buildPostVariables($payments, $currency = 'USD', $senderEmail = '', $testMode = 0)
    {
        $iteration = count($payments);
        $payees='';
        
        //check if the received variable is an array
        if (!is_array($payments)) 
        { 
            die ("Argument is not an array!"); 
        }
        else
        {
            //create another array with proper parameter names
            $p = 0;     //variable used for the subscript of the payment number
            for ($x = 0; $x < $iteration; $x++)
            {               
                $p++;
                $payees .= "&RECEIVEREMAIL_$p=".urlencode($payments[$x]["receiver"])."&AMOUNT_$p=".urlencode($payments[$x]["amount"])."&NOTE_$p=".urlencode($payments[$x]["note"])."&MPCUSTOM_$p=".urlencode($payments[$x]["mpcustom"]);
            }               
        }
        $this->dataToSend = sprintf("USER=%s&PASSWORD=%s&CURRENCY=%s&SENDEREMAIL=%s&TESTMODE=%s",
                                    urlencode($this->myUserName),
                                    urlencode($this->apiPassword),
                                    urlencode($currency),
                                    urlencode($senderEmail),
                                    urlencode((string )$testMode));
        $this->method = '/executemasspay';
        $this->dataToSend .= $payees;
        
        return $this->dataToSend;
    }

    public function checkPayzaBalance($currency = 'USD')
    {
        $this->dataToSend = sprintf("USER=%s&PASSWORD=%s&CURRENCY=%s",
                                    urlencode($this->myUserName),
                                    urlencode($this->apiPassword),
                                    urlencode($currency));
        $this->method = '/GetBalance';
        return $this->dataToSend;
    }

    /**
     * MassPayClient::send()
     * 
     * Sends the URL encoded post string to the MassPay API 
     * using cURL and retrieves the response.
     * 
     * @return string The response from the MassPay API.
     */
    public function send()
    {
        $response = '';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->getServer() . $this->getUrl() . $this->method);
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
     * MassPayClient::parseResponse()
     * 
     * Parses the encoded response from the MassPay API
     * into an associative array.
     * 
     * @param string $input The string to be parsed by the function.
     */
    public function parseResponse($input)
    {
        parse_str($input, $this->responseArray);
    }


    /**
     * MassPayClient::getResponse()
     * 
     * Returns the responseArray 
     * 
     * @return string An array containing the response variables.
     */
    public function getResponse()
    {
        return $this->responseArray;
    }

    public function response_codes($code)
    {
        $codes = array(
            '200' => 'Payment has been successfully submitted.',
            '201' => 'Missing parameter USER in the request.',
            '202' => 'Missing parameter PASSWORD in the request.',
            '203' => 'Missing parameter RECEIVEREMAIL in the request.',
            '204' => 'Missing parameter AMOUNT in the request.',
            '205' => 'Missing parameter CURRENCY in the request.',
            '206' => 'Missing parameter PURCHASETYPE in the request.',
            '211' => 'Invalid format for parameter USER. Value must be a valid e-mail address in the following format: username@example.com',
            '212' => 'Invalid format for parameter PASSWORD. Value must be a 16 character alpha-numeric string.',
            '213' => 'Invalid format for parameter AMOUNT. Value must be numeric.',
            '214' => 'Invalid value for parameter CURRENCY. Value must be a three character string representing an ISO-4217 currency code accepted by Payza.',
            '215' => 'Invalid format for parameter RECEIVEREMAIL. Value must be a valid e-mail address in the following format: username@example.com',
            '216' => 'The format for parameter NOTE is invalid.',
            '217' => 'Invalid value for parameter TESTMODE. Value must be either 0 or 1.',
            '218' => 'Invalid value for parameter PURCHASETYPE. Value must be an integer number between 0 and 3.',
            '219' => 'Invalid format for parameter SENDEREMAIL. Value must be a valid e-mail address in the following format: username@example.com',
            '221' => 'Cannot perform the request. Invalid USER and PASSWORD combination.',
            '222' => 'Cannot perform the request. API Status is disabled for this account.',
            '223' => 'Cannot perform the request. Action cannot be performed from this IP address.',
            '224' => 'Cannot perform the request. USER account is not active.',
            '225' => 'Cannot perform the request. USER account is locked.',
            '226' => 'Cannot perform the request. Too many failed authentications. The API has been momentarily disabled for your account. Please try again later.',
            '231' => 'Incomplete transaction. Amount to be sent must be positive and greater than 1.00.',
            '232' => 'Incomplete transaction. Amount to be sent cannot be greater than the maximum amount.',
            '233' => 'Incomplete transaction. You have insufficient funds in your account.',
            '234' => 'Incomplete transaction. You are attempting to send more than your sending limit.',
            '235' => 'Incomplete transaction. You are attempting to send more than your monthly sending limit.',
            '236' => 'Incomplete transaction. You are attempting to send money to yourself.',
            '237' => 'Incomplete transaction. You are attempting to send money to an account that cannot accept payments.',
            '238' => 'Incomplete transaction. The recipient of the payment does not accept payments from unverified members.',
            '239' => 'Invalid value for parameter NOTE. The field cannot exceed 1000 characters.',
            '240' => 'Error with parameter SENDEREMAIL. The specified e-mail is not associated with your account.',
            '241' => 'Error with parameter SENDEREMAIL. The specified e-mail has not been validated.',
            '242' => 'Incomplete transaction. The recipient’s account is temporarily suspended and cannot receive money.',
            '243' => 'Incomplete transaction. The recipient only accepts funds from members in the same country.',
            '244' => 'Incomplete transaction. The recipient cannot receive funds at this time, please try again later.',
            '245' => 'Incomplete transaction. The amount you are trying to send exceeds your transaction limit as an Unverified Member.',
            '246' => 'Incomplete transaction. Your account must be Verified in order to transact money.',
            '247' => 'Unsuccessful refund. Transaction does not belong to this account.',
            '248' => 'Unsuccessful refund. Transaction does not exist in our system.',
            '249' => 'Unsuccessful refund. Transaction is no longer refundable.',
            '250' => 'Unsuccessful cancellation. Subscription does not belong to this account.',
            '251' => 'Unsuccessful cancellation. Subscription does not exist in our system.',
            '252' => 'Unsuccessful cancellation. Subscription is already canceled.',
            '260' => 'Unsuccessful query. The specified CURRENCY balance is NOT open in your account.',
            '299' => 'An unexpected error occurred.',  
        );
        return $codes[$code];
    }


    /**
     * MassPayClient::__destruct()
     * 
     * Destructor of the MassPayClient object
     */
    public function __destruct()
    {
        unset($this->responseArray);
    }
}