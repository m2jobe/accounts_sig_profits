<?php
define('BOT_TOKEN', 'bot455809736:AAHnRP4PLZis3Cg8JAeYC3UWk4iipeDWBL0');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
set_time_limit(0);



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
//settingw ebook https://api.telegram.org/bot455809736:AAHnRP4PLZis3Cg8JAeYC3UWk4iipeDWBL0/setWebhook?url=https://accounts.signalprofits.com/signaltest.php
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
        $adminPass = trim(substr($text, 6, strlen($text)));


        if($adminPass === "d3kf9s4fj39sjgl30sm3f") {

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

          $sql = "INSERT INTO telegram VALUES (".$chat_id.")";

          if ($conn->query($sql) === TRUE) {
              //echo "Record updated successfully";
            apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "text" => "Hi ".($customerName).". You've been authenticated.", 'reply_markup' => array(
              'keyboard' => array(array('Great!')),
              'one_time_keyboard' => true,
              'resize_keyboard' => true)));

          } else {
            apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "text" => "Hi ".($customerName).". You've been authenticated.", 'reply_markup' => array(
              'keyboard' => array(array('Great!')),
              'one_time_keyboard' => true,
              'resize_keyboard' => true)));
          }

        }

      //$conn->close();
      } else {
        $userEmail = "";
      }

    } else if ($text === "Hello" || $text === "Hi") {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    }
// -------------------------------- Signal ------------------------------------------------
    else if (strpos($text, "/signal") === 0 || strpos($text, "/Signal") === 0) {

      $messageToSend = trim(substr($text, 7, strlen($text)));


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

      $sql = "SELECT telegramAdminID FROM telegram";
      $result = $conn->query($sql);
      $adminArray = array();
      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            array_push($adminArray, $row["telegramAdminID"]);
          }
      } else {
          echo "0 results";
      }
      //$conn->close();

      if(in_array($chat_id , $adminArray)) {

        $sql = "SELECT distinct telegramSignal FROM users where subscribed = 1 and telegramSignal is not null";
        $result = $conn->query($sql);
        $adminArray = array();
        $ch = curl_init();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

              curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot524474504:AAEq_loBZxH6bWYeHDxQGfIK6EGWniC-IQs/sendMessage?chat_id=".$row["telegramSignal"]."&text=".urlencode($messageToSend)."");

              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
              curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds
              curl_setopt($ch, CURLOPT_HEADER, 0);

              if(curl_exec($ch)){ // ?? - if request and data are completely received
                continue; // ?? - go to the next loop
              } else {
                continue;
              }

            }
        } else {
            echo "0 results";
        }

        curl_close($ch);
      } else {
        $chUnAuthor = curl_init();

        curl_setopt($chUnAuthor, CURLOPT_URL, "https://api.telegram.org/bot455809736:AAHnRP4PLZis3Cg8JAeYC3UWk4iipeDWBL0/sendMessage?chat_id=502489130&text=You are not authorized. Incident reported.");
        curl_setopt($chUnAuthor, CURLOPT_HEADER, 0);
        curl_exec($chUnAuthor);
        curl_close($chUnAuthor);
      }



    } 
//--- news --- --- news --- --- news --- --- news ------ news ------ news ------ news ------ news ------ news ------ news ---

      else if (strpos($text, "/news") === 0 || strpos($text, "/News") === 0) {

      $messageToSend = trim(substr($text, 5, strlen($text)));


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

      $sql = "SELECT telegramAdminID FROM telegram";
      $result = $conn->query($sql);
      $adminArray = array();
      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            array_push($adminArray, $row["telegramAdminID"]);
          }
      } else {
          echo "0 results";
      }
      //$conn->close();

      if(in_array($chat_id , $adminArray)) {

        $sql = "SELECT distinct telegramNews FROM users where subscribed = 1 and telegramNews is not null";
        $result = $conn->query($sql);
        $adminArray = array();
        $ch = curl_init();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

              curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot512176798:AAHoQHlQ48nw4yhDSeZPVV4qLR9aIkuXMGo/sendMessage?chat_id=".$row["telegramNews"]."&text=".urlencode($messageToSend)."");

              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
              curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds
              curl_setopt($ch, CURLOPT_HEADER, 0);

              if(curl_exec($ch)){ // ?? - if request and data are completely received
                continue; // ?? - go to the next loop
              } else {
                continue;
              }

            }
        } else {
            echo "0 results";
        }

        curl_close($ch);
      } else {
        $chUnAuthor = curl_init();

        curl_setopt($chUnAuthor, CURLOPT_URL, "https://api.telegram.org/bot455809736:AAHnRP4PLZis3Cg8JAeYC3UWk4iipeDWBL0/sendMessage?chat_id=502489130&text=You are not authorized. Incident reported.");
        curl_setopt($chUnAuthor, CURLOPT_HEADER, 0);
        curl_exec($chUnAuthor);
        curl_close($chUnAuthor);
      }



    } 
//--- news --- --- news --- --- news --- --- news ------ news ------ news ------ news ------ news ------ news ------ news ---
      else if (strpos($text, "/announcement") === 0 || strpos($text, "/Announcement") === 0) {

      $messageToSend = trim(substr($text, 13, strlen($text)));


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

      $sql = "SELECT telegramAdminID FROM telegram";
      $result = $conn->query($sql);
      $adminArray = array();
      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            array_push($adminArray, $row["telegramAdminID"]);
          }
      } else {
          echo "0 results";
      }
      //$conn->close();

      if(in_array($chat_id , $adminArray)) {

        $sql = "SELECT distinct telegramAnnouncement FROM users where subscribed = 1 and telegramAnnouncement is not null";
        $result = $conn->query($sql);
        $adminArray = array();
        $ch = curl_init();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

              curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot418691843:AAEoQjHT7UendN_AixSxhpEiykOXCKdiwS0/sendMessage?chat_id=".$row["telegramAnnouncement"]."&text=".urlencode($messageToSend)."");

              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
              curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds
              curl_setopt($ch, CURLOPT_HEADER, 0);

              if(curl_exec($ch)){ // ?? - if request and data are completely received
                continue; // ?? - go to the next loop
              } else {
                continue;
              }

            }
        } else {
            echo "0 results";
        }

        curl_close($ch);
      } else {
        $chUnAuthor = curl_init();

        curl_setopt($chUnAuthor, CURLOPT_URL, "https://api.telegram.org/bot455809736:AAHnRP4PLZis3Cg8JAeYC3UWk4iipeDWBL0/sendMessage?chat_id=502489130&text=You are not authorized. Incident reported.");
        curl_setopt($chUnAuthor, CURLOPT_HEADER, 0);
        curl_exec($chUnAuthor);
        curl_close($chUnAuthor);
      }



    } 
    else {
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