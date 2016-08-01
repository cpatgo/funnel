<?php

require_once(dirname(dirname(__FILE__)) . '/functions/json.php');

  class RapleafApi {
    var $BASE_PATH = "https://personalize.rapleaf.com/v4/dr?api_key=";
    var $handle = null;
    var $API_KEY = "";

    /* Note that an exception is raised in the case that
     * an HTTP response code other than 200 is sent back
     * The error code and error body are displayed
     */

    function __construct() {
      $this->handle = curl_init();
      curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($this->handle, CURLOPT_TIMEOUT, 2.0);
      curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, TRUE);
      curl_setopt($this->handle, CURLOPT_USERAGENT, "ACRapleafApi/PHP4/1.0");
      if ( $GLOBALS["site"]["rapleaf_key"] ) {
        $this->API_KEY = $GLOBALS["site"]["rapleaf_key"];
      }
    }

    function query_by_email($email, $hash_email = false) {
      /* Takes an e-mail and returns a hash which maps attribute fields onto attributes
       * If the hash_email option is set, then the email will be hashed before it's sent to Rapleaf
       */
      if ($hash_email) {
        $sha1_email = sha1(strtolower($email));
        return $this->query_by_sha1($sha1_email);
      } else {
        $url = "email=" . urlencode($email);
        return $this->get_json_response($url);
      }
    }

    function query_by_md5($md5_email) {
      /* Takes an e-mail that has already been hashed by md5
       * returns a hash which maps attribute fields onto attributes
       */
      $url = "md5_email=" . urlencode($md5_email);
      return $this->get_json_response($url);
    }

    function query_by_sha1($sha1_email) {
      /* Takes an e-mail that has already been hashed by sha1
       * and returns a hash which maps attribute fields onto attributes
       */
      $url = "sha1_email=" . urlencode($sha1_email);
      return $this->get_json_response($url);
    }

    function query_by_nap($first, $last, $street, $city, $state, $email = null) {
      /* Takes first name, last name, and postal (street, city, and state acronym),
       * and returns a hash which maps attribute fields onto attributes
       * Though not necessary, adding an e-mail increases hit rate
       */
      if ($email) {
        $url = "email=" . urlencode($email) .
        "&first=" . urlencode($first) . "&last=" . urlencode($last) .
        "&street=" . urlencode($street) . "&city=" . urlencode($city) . "&state=" . urlencode($state);
      } else {
        $url = "first=" . urlencode($first) . "&last=" . urlencode($last) .
        "&street=" . urlencode($street) . "&city=" . urlencode($city) . "&state=" . urlencode($state);
      }
      return $this->get_json_response($url);
    }

    function query_by_naz($first, $last, $zip4, $email = null) {
      /* Takes first name, last name, and zip4 code (5-digit zip
       * and 4-digit extension separated by a dash as a string),
       * and returns a hash which maps attribute fields onto attributes
       * Though not necessary, adding an e-mail increases hit rate
       */
      if ($email) {
        $url = "email=" . urlencode($email) .
        "&first=" . urlencode($first) . "&last=" . urlencode($last) . "&zip4=" . $zip4;
      } else {
        $url = "zip4=" . $zip4 .
        "&first=" . urlencode($first) . "&last=" . urlencode($last);
      }
      return $this->get_json_response($url);
    }

    function bulk_query_by_email($emails) {
      $arr = array();
      foreach ( $emails as $v ) {
        $arr[] = array('email' => $v);
      }
      return $this->get_bulk_json_response($arr);
    }

    function get_bulk_json_response($arr) {
      $post = json_encode($arr);
      $url = str_replace("/dr?", "/bulk?", $this->BASE_PATH) . $this->API_KEY;
      curl_setopt($this->handle, CURLOPT_HTTPHEADERS, array('Content-Type: application/json'));
      curl_setopt($this->handle, CURLOPT_POST, true);
      curl_setopt($this->handle, CURLOPT_POSTFIELDS, $post);
      curl_setopt($this->handle, CURLOPT_URL, $url);
      $json_string = curl_exec($this->handle);
      $response_code = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
      if ($response_code < 200 || $response_code >= 300) {
        trigger_error("Error Code: " . $response_code . "\nError Body: " . $json_string);
      } else {
        $personalization = json_decode($json_string, TRUE);
        return $personalization;
      }
    }

    function build_request($params) {
      $url = $this->BASE_PATH . $this->API_KEY . "&" . $params;
      return $url;
    }


    function get_json_response($url) {
      /* Pre: Path is an extension to personalize.rapleaf.com
       * Note that an exception is raised if an HTTP response code
       * other than 200 is sent back. In this case, both the error code
       * the error code and error body are accessible from the exception raised
       */
      curl_setopt($this->handle, CURLOPT_URL, $this->build_request($url));
      //curl_setopt($this->handle, CURLOPT_HTTPHEADERS, array());
      $json_string = curl_exec($this->handle);
      $response_code = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
      if ($response_code < 200 || $response_code >= 300) {
        trigger_error("Error Code: " . $response_code . "\nError Body: " . $json_string);
      } else {
        $personalization = json_decode($json_string, TRUE);
        return $personalization;
      }
    }
  }
?>