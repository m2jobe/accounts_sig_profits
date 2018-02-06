<?php 

$servername = "127.0.0.1";
$username = "signalprofit";
$password = "v2}547+=SP&<0Sq";
$dbname = "signalprofits";

$email = htmlspecialchars($_GET["email"]) ;
$discordID = htmlspecialchars($_GET["discordID"]) ;


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "UPDATE users SET discord='".$discordID."' WHERE email='".$email."'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();

?>
