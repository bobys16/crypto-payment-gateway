<?php
include_once 'vendor/autoload.php';

use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Tron;

$fullNode = new HttpProvider('https://api.trongrid.io');
$solidityNode = new HttpProvider('https://api.trongrid.io');
$eventServer = new HttpProvider('https://api.trongrid.io');
$privateKey = '624271b115415256308c26b2d4d7d6111cf496facddd059b3e8b12aa4149b5c1';
$master = "TFV5fDXAMYuVtKjjAZ861dUpMYTX8No1Fd";

//Example 1
try {
    $tron = new Tron($fullNode, $solidityNode, $eventServer);
} catch (\IEXBase\TronAPI\Exception\TronException $e) {
    die($e->getMessage());
}

	$from    = "TC4kvHgfYsvunHbWj6Ha5vjbTHcppdhZtq";
	
	$target  = "TX9BdXuK6Wmwqhnbe1s9CvaDvQNsYxrRTT";
	
	
	$tron->setPrivateKey($privateKey);
	$tron->setAddress($master);
	//var_dump($tron->registerAccount($master, "TCfbgzaM93vNhk38A2YGHfpHPYanagZbRC"));
	//print_r($tron->getAddress());
	//$transfer = $tron->send( 'TFV5fDXAMYuVtKjjAZ861dUpMYTX8No1Fd', 200);
	//var_dump($transfer);
    $contract = $tron->contract('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');  // Tether USDT https://tronscan.org/#/token20/TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t
	//var_dump($contract->approve($from, 99999999));
    //echo $contract->balanceOf();
	var_dump($contract->transferFrom($target, 0.5, $from));
/*
    // Data
    echo $contract->name();
    echo $contract->symbol();
    echo $contract->totalSupply();*/