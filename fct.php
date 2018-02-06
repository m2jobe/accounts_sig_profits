<?php
require 'vendor/autoload.php';

$email = $argv[1];
\Stripe\Stripe::setApiKey("rk_live_gPxfDcylJPdYxMR9UV24lfCY");




function getCustomerByEmailAddressAndDates($emailAddress) {
    
    
	$last_customer = NULL;
	$email = $emailAddress;
	$deadArray = [];
	while (true) {
	    $customers = \Stripe\Customer::all(array("limit" => 1, "starting_after" => $last_customer));
	    foreach ($customers->autoPagingIterator() as $customer) {
	        if ($customer->email == $email) {
	            $customerIamLookingFor = $customer;
	            return json_encode($customerIamLookingFor);

	            break 2;
	        }
	    }
	    if (!$customers->has_more) {
		return "do";
	        break;
	    }
	    $last_customer = end($customers->data);
	    //return json_encode($deadArray);
	}
	return "do"; //json_encode($deadArray);
	
}

print_r(getCustomerByEmailAddressAndDates($email))


?>
