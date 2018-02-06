<?php 

$servername = "127.0.0.1";
$username = "signalprofit";
$password = "v2}547+=SP&<0Sq";
$dbname = "signalprofits";


$customerID = htmlspecialchars($_GET["customerID"]) ;
$customerName = htmlspecialchars($_GET["customerName"]) ;
$email = htmlspecialchars($_GET["email"]) ;
$subID = htmlspecialchars($_GET["subID"]) ;
$subscribed = htmlspecialchars($_GET["subscribed"]) ;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO users (customerID, customerName, discord, email, facebook, subID, subscribed, telegramSignal,telegramNews,telegramAnnouncement)
VALUES ('".$customerID."', '".$customerName."', null, '".$email."', null, '".$subID."', ".$subscribed.", null, null, null)";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();

?>
