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
	mysqli_query($connect, "INSERT INTO address_list VALUES('".$addr."','".$priv_key."','".$public_key."','BSC','0','LEGO','active','0')");
	//mysqli_query($connect, "INSERT INTO master_address VALUES(null,'".$addr."','".$priv_key."','".$public_key."','0','BSC','active','".time()."')");
	
} else {
	echo "fail";
}

?>