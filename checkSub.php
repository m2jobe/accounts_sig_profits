<?php
require 'vendor/autoload.php';
$subID = htmlspecialchars($_GET["subID"]) ;

$last_customer = NULL;
\Stripe\Stripe::setApiKey("rk_live_gPxfDcylJPdYxMR9UV24lfCY");




function getSub($subID) {
    $customersResults = \Stripe\Subscription::retrieve($subID);
    $customers = $customersResults->data;

    return json_encode($customersResults);
}

print_r(getSub($subID));


?>
