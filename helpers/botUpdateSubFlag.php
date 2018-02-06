<?php 
require '../vendor/autoload.php';

function getSub($subID) {
    $customersResults = \Stripe\Subscription::retrieve($subID);
    $customers = $customersResults;

    return $customersResults;
}

$last_customer = NULL;
\Stripe\Stripe::setApiKey("rk_live_gPxfDcylJPdYxMR9UV24lfCY");



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


$sql = "SELECT subID,email FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $subID = $row["subID"];
        $email = $row["email"];
        $customer = getSub($subID);
        if($customer->canceled_at == null) {
        	//update as subscribed
			$sql = "UPDATE users SET subscribed=1 WHERE email='".$email."'";

			if ($conn->query($sql) === TRUE) {
			    //echo "Record updated successfully";
			} else {
			    //echo "Error updating record: " . $conn->error;
			}
        } else {
        	//update as ubscribed
			$sql = "UPDATE users SET subscribed=0 WHERE email='".$email."'";

			if ($conn->query($sql) === TRUE) {
			    //echo "Record updated successfully";
			} else {
			    //echo "Error updating record: " . $conn->error;
			}
        }
    }
} else {
    echo "";
}


use RestCord\DiscordClient;

$discordClient = new DiscordClient(['token' => 'NDA3OTgyNDE1NjcyNTA4NDE2.DVJjCg.n9yESywapfUcsWeFAAPNoXQcQ0g']); // Token is required

$sql = "SELECT customerName, telegram,facebook,discord FROM users where subscribed = 0";
$result = $conn->query($sql);

$facebookUsersToDelete = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $discord = $row["discord"];
        $facebook = $row["facebook"];
        $telegram = $row["telegram"];
        $customerName = $row["customerName"];
        //remove from discord
        $discordClient->guild->removeGuildMember(['guild.id' => 397555972488429569, 'user.id' => intval($discord)]);


        //remove from telegram logic 
        
        $chBan = curl_init();

        // Unban user first just in case
        curl_setopt($chBan, CURLOPT_URL, "https://api.telegram.org/bot524474504:AAEq_loBZxH6bWYeHDxQGfIK6EGWniC-IQs/kickChatMember?chat_id=@signalprofitsgroup&user_id=".$telegram);
        curl_setopt($chBan, CURLOPT_HEADER, 0);
        curl_setopt($chBan, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chBan, CURLOPT_VERBOSE, 0);
        curl_exec($chBan);
        curl_close($chBan);

        //generate facebook users to remove csv
        array_push($facebookUsersToDelete,$customerName."-".$facebook);
    }
    
    $fp = fopen("facebook_users_to_delete/FB_USERS ".date(DATE_RFC822).".txt", 'w');
    foreach($facebookUsersToDelete as $value){
        fwrite($fp, $value.PHP_EOL);
    }
    fclose($fp);

    echo "https://accounts.signalprofits.com/helpers/facebook_users_to_delete/FB_USERS ".date(DATE_RFC822).".txt";


} else {
    echo "All users subbed! No one to remove!";
}



$conn->close();

?>
