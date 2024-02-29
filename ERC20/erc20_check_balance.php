<?php
require "vendor/autoload.php";
include "../dashboard/config.php";
use Lessmore92\Ethereum\Foundation\Transaction\TransactionBuilder;
use Lessmore92\Ethereum\Foundation\Transaction\Transaction;
use Lessmore92\Ethereum\Foundation\Transaction\SignedTransaction;
use Lessmore92\Ethereum\Foundation\Eth;
use kornrunner\Keccak;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Web3;
use Lessmore92\Ethereum\Utils\Number;


$node = "https://mainnet.infura.io/v3/3093e6030f2044c1bfd64fc07669381c";

$web3 = new Web3(new HttpProvider(new HttpRequestManager($node, 2)));
$eth = new Eth($web3);

$token = new \Lessmore92\Ethereum\Token("0xdac17f958d2ee523a2206206994597c13d831ec7", $node);
$energy_query = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM energy_address WHERE network='ETHER'"));
$erc_energy = $energy_query['address'];
$erc_energy_priv = $energy_query['private_key'];
$decimal = $token->decimals();


$master_query = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM master_address WHERE network='ETHER'"));
$erc_master = '0x'.$master_query['address'];
$safeGas = getSafeGasPrice();
$decimal = $token->decimals();

// checker //

	echo "ERC20 Address Check Started...\n";
	$query = mysqli_query($connect, "SELECT * FROM address_list WHERE type='ETHER' AND approved='1'");
	while($row = mysqli_fetch_assoc($query)) {
		
		$addr = '0x'.$row['address'];
		$priv = $row['private_key'];
		$balance = $token->balanceOf('0x'.$row['address']);
		
		if($balance > 1) {
			$transfer_tx    = $token->transferFrom($erc_energy, $addr, $erc_master, $balance, Number::toHex(100000), getSafeGasPrice());
			$transfer_tx_id = $transfer_tx->sign($erc_energy_priv, 1)
								  ->send();
		} 
		echo "0x".$row['address']." got ".$balance."\n";
		
		//echo "\n";
		sleep(10);
	}	

	function getSafeGasPrice()
    {
    	
    	$getGas = json_decode(file_get_contents("https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=5XGPQQEZMYTD999FGTC1JGZM736WFTHP5Q"),true);
    	$gasPrice = $getGas['result']['ProposeGasPrice'];

        return Number::toWei($gasPrice + 2, 'gwei') 
                     ->toHex()
            ;
    }
	/*
	echo "ERC20 Balance Check Started...\n";
	$query = mysqli_query($connect, "SELECT * FROM address_list WHERE type='ETHER'");
	while($row = mysqli_fetch_assoc($query)) {
		$balance = $token->balanceOf('0x'.$row['address']);
		mysqli_query($connect, "UPDATE address_list SET balance=".$balance." WHERE address = '".$row['address']."'");
		echo "0x".$row['address']." got ".$balance."\n";
	}	
	
	*/