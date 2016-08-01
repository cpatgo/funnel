<?php

/*
 * The RestBuilder class requires the RestRequest library class, which
 * comes bundled with this library. RestRequest can easily be used for
 * other PHP REST projects.
 */


require('RestRequest.class.php');

class RestBuilder {

  /**
   * The REST request object
   *
   * @var object
   */
  protected $request;

  /**
   * The base URL.
   * example:
   * http://rest-capable-web-site.com/
   *
   * @var string
   */
  protected $baseurl;

  /**
   * The returned data format.
   * possible values:
   * <ul>
   *  <li>simplexml -> return a SimpleXMLElement PHP object (default)</li>
   *  <li>xml -> return an XML string</li>
   * </ul>
   *
   * @var string
   */
  protected $format;

  /**
   * The API login username
   *
   * @var string
   */
  protected $username;

  /**
   * The API login password
   *
   * @var string
   */
  protected $password;

  /**
   * The body of the API request
   *
   * @var string
   */
  protected $request_body;


  /**#@-*/
  /**
   * The class constructor.
   */
  public function __construct ($baseurl,$username=null,$password=null,$format='xml') {
    $this->setBaseurl($baseurl);
    $this->setFormat($format);
    $this->setUsername($username);
    $this->setPassword($password);
    $this->setFormat($format);
  }

  /**
   * sleep every so often so that remote server
   * doesn't complain about flooding.
   *
   * @return string $username
   */
  public function sleeper()
  {
  	static $counter = 0;

  	if($counter > 2) {
  		sleep(1);
  		$counter = 0;
  	} else {
  		$counter++;
  	}
  }

  /* setters and getters */

  /**
   * get username
   *
   * @return string $username
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * set username
   *
   * @param string $username
   */
  public function setUsername($username)
  {
    if(empty($username))
      throw new InvalidArgumentException("username cannot be empty.");
    $this->username = $username;
  }

  /**
   * get format
   *
   * @return string $format
   */
  public function getFormat()
  {
    return $this->format;
  }

  /**
   * set format
   *
   * @param string $format
   */
  public function setFormat($format)
  {
    if(empty($format))
      throw new InvalidArgumentException("format cannot be empty.");
  	$format = strtolower($format);
    if(!in_array($format,array('xml','simplexml')))
      throw new InvalidArgumentException("'{$format}' is not a valid format.");
    $this->format = $format;
  }

  /**
   * get password
   *
   * @return string $password
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * set password
   *
   * @param string $password
   */
  public function setPassword($password)
  {
    if(empty($password))
      throw new InvalidArgumentException("password cannot be empty.");
    $this->password = $password;
  }


  /**
   * get request body
   *
   * @return string $request_body
   */
  public function getRequestBody()
  {
    return $this->request_body;
  }

  /**
   * set request body
   *
   * @param string $body
   */
  public function setRequestBody($body)
  {
    $this->request_body = $body;
  }

  /**
   * get baseurl
   *
   * @return string $baseurl
   */
  public function getBaseurl()
  {
    return $this->baseurl;
  }

  /**
   * set baseurl
   *
   * @param string $baseurl
   */
  public function setBaseurl($url)
  {
    if(empty($url))
      throw new InvalidArgumentException("Base URL cannot be empty.");
    // add default http protocol if absent
    if(!preg_match('!^https?://!i',$url))
      $url = 'http://' . $url;
    // add trailing slash if necessary
    if(substr($url,-1) !== '/')
      $url .= '/';
    $this->baseurl = $url;
  }

  /* private methods */

  /**
   * setup the REST request body
   *
   * @param string $body
   */
  public function setupRequestBody($body) {
    $request_body = array('request'=>$body);
    $this->setRequestBody($this->createXMLFromArray($request_body));
  }

  /**
   * process the current REST request
   *
   * @param string $url url to API request
   * @param string $type type of request (GET/PUT/POST/DELETE)
   * @param string $format format of response (xml/simplexml)
   * @return array $return response array
   */
  public function processRequest($url,$type,$format=null) {

    $this->request = new RestRequest($url,$type);
    $this->request->setUsername($this->username);
    $this->request->setPassword($this->password);

    $this->request->setRequestBody($this->request_body);

    $this->request->execute();

    $response_info = $this->request->getResponseInfo();
    $response_content = $this->request->getResponseBody();

    $return['headers'] =   substr($response_content,0,$response_info['header_size']);
    $return['body'] = substr($response_content,$response_info['header_size']);

    // grab status from headers
    if(preg_match('!^Status: (.*)$!m',$return['headers'],$match))
      $return['status'] = trim($match[1]);
    else
      $return['status'] = null;

    // grab location from headers
    if(preg_match('!^Location: (.*)$!m',$return['headers'],$match))
      $return['location'] = trim($match[1]);
    else
      $return['location'] = null;

    // set output format
    if(!isset($format))
      $format = $this->format;

    $return['body'] = trim($return['body']);
    if(!empty($return['body']) && $format == 'simplexml') {
      // return simplexml object
      $return['body'] = new SimpleXMLElement($return['body']);
    }

    // finished with request, release it
    unset($this->request);
    // clear the request body contents
    $this->request_body = null;

    return $return;
  }

  /**
   * create XML from PHP array (recursive)
   *
   * @param array $array php arrays (of arrays) of values
   * @return string $xml
   */
  public function createXMLFromArray($array,$level=0) {
    $xml = '';
    foreach($array as $key=>$val) {
        $attrs = '';
        // separate attributes if any
        if(($spos = strpos($key,' '))!==false) {
          $attrs = substr($key,$spos);
          $key = substr($key,0,$spos);
        }
        // hack to take multiple same-named keys :)
        if(($colpos = strpos($key,':'))!==false)
          $key = substr($key,0,$colpos);
        // add to xml string. if array, recurse.
        $xml .= sprintf("%s<%s>%s</%s>\n",
          str_repeat('  ',$level),
          htmlspecialchars($key).$attrs,
          is_array($val) ? "\n".$this->createXMLFromArray($val,$level+1).str_repeat('  ',$level) : htmlspecialchars($val),
          htmlspecialchars($key)
        );
    }
    return $xml;
  }

}
