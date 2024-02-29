<?php

require_once 'vendor/autoload.php';
use kornrunner\Ethereum\Address;
include "../dashboard/config.php";


$address = new Address('9e0f3a94323bfd3354139282d1293478701a637597c2ddff395796d0f93db0c0');

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