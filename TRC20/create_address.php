<?php
include_once 'vendor/autoload.php';

    date_default_timezone_set('UTC');
    $his = date("d-m-Y H:i:s");
    $result = array('success' => "false", 'msg' => 'Nothing to do');
    $connect = mysqli_connect('localhost','elzgar','123Tegar','gateway');
    if (!$connect) {
        die("Internal server error!");
    }


try {
    $tron = new \IEXBase\TronAPI\Tron();

    $generateAddress = $tron->generateAddress(); // or createAddress()
    $isValid = $tron->isAddress($generateAddress->getAddress());
	$addr = $generateAddress->getAddress(true);
	$priv_key =$generateAddress->getPrivateKey();
	$public_key = $generateAddress->getPublicKey();
	
	echo json_encode(array('address' => $addr,'private_key' => $priv_key));
	
} catch (\IEXBase\TronAPI\Exception\TronException $e) {
    echo $e->getMessage();
}