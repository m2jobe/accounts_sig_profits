<?php
$email = htmlspecialchars($_GET["email"]) ;
//$email = "thejobemuhammed@gmail.com";
echo "Email sent to ". $email .". Please wait 2-5 minutes to receive the email";
#echo shell_exec('ls ') ;
echo shell_exec('/usr/bin/python addUser.py ' . $email . ' 2>&1');
#sleep(80);
#echo shell_exec('/usr/bin/pgrep chromium-browse | xargs kill -9');
?>
