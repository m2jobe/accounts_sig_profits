<?php 

$servername = "127.0.0.1";
$username = "signalprofit";
$password = "v2}547+=SP&<0Sq";
$dbname = "signalprofits";

$email = htmlspecialchars($_GET["email"]) ;


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$sql = "SELECT subID,telegramSignal,telegramNews,telegramAnnouncement,discord,facebook,subscribed FROM users where email = '".$email."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        print_r(json_encode($row));
    }
} else {
    echo "";
}


$conn->close();

?>
