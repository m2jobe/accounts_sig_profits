<?php 

$servername = "127.0.0.1";
$username = "signalprofit";
$password = "v2}547+=SP&<0Sq";
$dbname = "signalprofits";

$email = htmlspecialchars($_GET["email"]) ;
$feed = htmlspecialchars($_GET["feed"]) ;


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//setting it to temp value before its updating so users see change in button but actual id is set when the bot receives the message from the user 

if($feed == "signals") {
$sql = "UPDATE users SET telegramSignal='0' WHERE email='".$email."'";
} 
if($feed == "news") {
$sql = "UPDATE users SET telegramNews='0' WHERE email='".$email."'";
} 
if($feed == "announcements") {
$sql = "UPDATE users SET telegramAnnouncement='0' WHERE email='".$email."'";
}



if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();

?>
