<?php

require_once 'vendor/autoload.php';
use kornrunner\Ethereum\Address;



$address = new Address();

$addr = $address->get();
if($addr) {
	$priv_key = $address->getPrivateKey();
	$public_key = $address->getPublicKey();
	echo json_encode(array('address' => $addr, 'private_key' => $priv_key));
} else {
	echo "fail";
}

?>