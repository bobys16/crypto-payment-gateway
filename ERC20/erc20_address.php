<?php

require_once 'vendor/autoload.php';
use kornrunner\Ethereum\Address;
include "../dashboard/config.php";


$address = new Address();

$addr = $address->get();
if($addr) {
	$priv_key = $address->getPrivateKey();
	$public_key = $address->getPublicKey();
	echo json_encode(array('address' => $addr, 'private_key' => $priv_key));
	mysqli_query($connect, "INSERT INTO address_list VALUES('".$addr."','".$priv_key."','".$public_key."','ETHER','0','USDT','active','0')");
} else {
	echo "fail";
}

?>