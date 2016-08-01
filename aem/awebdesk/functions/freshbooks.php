<?php

function freshbooks_oauth_init() {
  $site = adesk_site_get();
  $admin = adesk_admin_get();
  $r = array("success" => 1);
  $restart = 0; // are we starting over from the very beginning?
  // logout
  if ( isset($_GET["freshbooks_logout"]) && $_GET["freshbooks_logout"] == 1 ) {
    $sql = adesk_sql_delete("#subscriber_import_service", "userid = '$admin[id]' AND service = 'freshbooks'");
  }
  if ( isset($_GET["oauth_verifier"]) && isset($_GET["oauth_token"]) ) {
    // coming back to EM after authenticating/authorizing on Freshbooks
    $account = $_GET["freshbooks_account"];
    $params = array(
      "oauth_consumer_key" => "awebdesk",
      "oauth_token" => $_GET["oauth_token"],
      "oauth_verifier" => $_GET["oauth_verifier"],
      "oauth_signature" => "Tb6Bz3mCVSpAnywN5pmixJ4tBECGTVcBg&",
      "oauth_signature_method" => "PLAINTEXT",
      "oauth_version" => "1.0",
      "oauth_timestamp" => time(),
      "oauth_nonce" => md5(microtime()),
    );
    $postdata = "";
    foreach ($params as $k => $v) {
      if ($postdata) $postdata .= "&";
      $postdata .= $k . "=";
      $postdata .= urlencode($v);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$account.freshbooks.com/oauth/oauth_access.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    //dbg($result);
    curl_close($ch);
    if ( preg_match("/^oauth_token=/", $result) ) {
      // successful response
      // make the string an array first
      $tokens = array();
      $key_value_pairs = explode("&", $result);
      foreach ($key_value_pairs as $key_value_string) {
        $key_value_array = explode("=", $key_value_string);
        $tokens[ $key_value_array[0] ] = $key_value_array[1];
      }
      $tokens["account"] = $account;
      $tokens = serialize($tokens);
      // cache to database
      $exists = (int)adesk_sql_select_one("=COUNT(*)", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'freshbooks'");
      if (!$exists) {
        $insert = array(
          "userid" => $admin["id"],
          "service" => "freshbooks",
          "connection_data" => $tokens,
        );
        $sql = adesk_sql_insert("#subscriber_import_service", $insert);
      }
      else {
        adesk_sql_update_one("#subscriber_import_service", "connection_data", $tokens, "userid = '$admin[id]' AND service = 'freshbooks'");
      }
    }
  }
  else {
    $exists = adesk_sql_select_one("connection_data", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'freshbooks'");
    if (!$exists) {
      // restart oauth process - user logs in to Freshbooks and authorizes
      $restart = 1;
      if ( isset($_GET["freshbooks_account"]) && $_GET["freshbooks_account"] ) {
        $account = $_GET["freshbooks_account"];
      }
      else {
        $r["success"] = 0;
        return $r;
      }
      $params = array(
        "oauth_consumer_key" => "awebdesk",
        "oauth_callback" => $site["p_link"] . "/manage/desk.php?action=subscriber_import&freshbooks_account=$account",
        "oauth_signature" => "Tb6Bz3mCVSpAnywN5pmixJ4tBECGTVcBg&",
        "oauth_signature_method" => "PLAINTEXT",
        "oauth_version" => "1.0",
        "oauth_timestamp" => time(),
        "oauth_nonce" => md5(microtime()),
      );
      $postdata = "";
      foreach ($params as $k => $v) {
        if ($postdata) $postdata .= "&";
        $postdata .= $k . "=";
        $postdata .= urlencode($v);
      }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://$account.freshbooks.com/oauth/oauth_request.php");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      //dbg( curl_error($ch) );
      curl_close($ch);
      if (!$result) {
        $result = _a("Invalid Freshbooks account.");
      }
      // IE: $result = "oauth_token=rUqbHsEi62etzXZhNtjZHaHV5qhE5PHB9&oauth_token_secret=g6xzzx5Qyc4KmjQqhW5QPTc95N5EsdRK5&oauth_callback_confirmed=true"
    }
    else {
      // cached in database
      $result = unserialize($exists);
      foreach ($result as $k => $v) {
        $result[$k] = $k . "=" . $v;
      }
      $result = implode("&", $result);
    }
  }

  if ( preg_match("/^oauth_token=/", $result) ) {
    // successful - tokens returned
    $key_value_pairs = explode("&", $result);
    foreach ($key_value_pairs as $key_value_string) {
      $key_value_array = explode("=", $key_value_string);
      $r[ $key_value_array[0] ] = $key_value_array[1];
    }
    if ($restart) $r["user_authorize_url"] = "https://$account.freshbooks.com/oauth/oauth_authorize.php?oauth_token=" . $r["oauth_token"];
  }
  else {
    // error
    $r["success"] = 0;
    $r["message"] = $result;
  }
  return $r;
}

?>