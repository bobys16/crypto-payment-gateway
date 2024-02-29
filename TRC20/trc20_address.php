<?php
include_once 'vendor/autoload.php';
try {
    $tron = new \IEXBase\TronAPI\Tron();

    $generateAddress = $tron->generateAddress(); // or createAddress()
    $isValid = $tron->isAddress($generateAddress->getAddress());
	$addr = $generateAddress->getAddress(true);
	$priv_key =$generateAddress->getPrivateKey();
	$public_key = $generateAddress->getPublicKey();
	
include "../dashboard/config.php";
	echo json_encode(array('address' => $addr,'private_key' => $priv_key));
	mysqli_query($connect, "INSERT INTO address_list VALUES('".$addr."','".$priv_key."','".$public_key."','TRON','0','USDT','active','0')");
} catch (\IEXBase\TronAPI\Exception\TronException $e) {
    echo $e->getMessage();
}