<?php
define('BOT_TOKEN', 'bot418691843:AAEoQjHT7UendN_AixSxhpEiykOXCKdiwS0');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');


// to set web hook https://api.telegram.org/bot418691843:AAEoQjHT7UendN_AixSxhpEiykOXCKdiwS0/setWebhook?url=https://accounts.signalprofits.com/webhooks/announcementsWebhook.php

//418691843:AAEoQjHT7UendN_AixSxhpEiykOXCKdiwS0

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successful: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}
//settingw ebook https://api.telegram.org/bot418691843:AAEoQjHT7UendN_AixSxhpEiykOXCKdiwS0/setWebhook?url=https://accounts.signalprofits.com/signaltest.php
function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $customerName = $message['from']['first_name'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];

    if (strpos($text, "/start") === 0) {
      if(strlen($text) > 6) {
      $userEmail = trim(substr($text, 6, strlen($text)));


      $servername = "127.0.0.1";
      $username = "signalprofit";
      $password = "v2}547+=SP&<0Sq";
      $dbname = "signalprofits";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      } 

      $sql = "UPDATE users SET telegramAnnouncement='".$chat_id."' WHERE subID='".$userEmail."'";

      if ($conn->query($sql) === TRUE) {
          //echo "Record updated successfully";
      } else {
          //echo "Error updating record: " . $conn->error;
      }

      //$conn->close();
      } else {
        $userEmail = "";
      }

      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "text" =>  "Hi ".($customerName).". Welcome to SignalProfits_Announcements."));

    } else if ($text === "Hello" || $text === "Hi") {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/globalMessage") === 0) {


    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}



$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}

?>