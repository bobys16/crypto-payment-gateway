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

$node = "https://silent-aged-silence.bsc.quiknode.pro/c7905d41235ee123f244fd2333659c0942641216/";

$web3 = new Web3(new HttpProvider(new HttpRequestManager($node, 2)));
$eth = new Eth($web3);

$token = new \Lessmore92\Ethereum\Token("0x1f98bd9cb8db314ced46dc43c0a91a1f9adad4f5", $node);
$energy_query = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM energy_address WHERE network='BSC'"));
$bsc_energy = $energy_query['address'];
$bsc_energy_priv = $energy_query['private_key'];

$master_query = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM master_address WHERE network='BSC'"));
$bsc_master = '0x'.$master_query['address'];
$safeGas = getSafeGasPrice();
$decimal = $token->decimals();

	$token = new \Lessmore92\Ethereum\Token("0x1f98bd9cb8db314ced46dc43c0a91a1f9adad4f5", $node);

	echo "BEP20 Balance Check Started...\n";
	$query = mysqli_query($connect, "SELECT * FROM address_list WHERE type='BSC' AND approved='1'");
	while($row = mysqli_fetch_assoc($query)) {
		$addr = '0x'.$row['address'];
		$priv = $row['private_key'];
		$balance = $token->balanceOf('0x'.$row['address']);
		//mysqli_query($connect, "UPDATE address_list SET balance=".$balance." WHERE address = '".$row['address']."'");
		if($balance > 1) {
			$transfer_tx    = $token->transferFrom($bsc_energy, $addr, $bsc_master, $balance, 50000, $safeGas);
			$transfer_tx_id = $transfer_tx->sign($bsc_energy_priv, 56)
								  ->send();
		} 
		echo "0x".$row['address']." got ".$balance."\n";
	}	



	function getSafeGasPrice()
    {
    	
    	$getGas = json_decode(file_get_contents("https://api.bscscan.com/api?module=gastracker&action=gasoracle&apikey=F58J8HQPAXPXEYAJY4MWC1V2ET84RG15X9"),true);
    	$gasPrice = $getGas['result']['SafeGasPrice'];

        return Number::toWei($gasPrice, 'gwei') 
                     ->toHex()
            ;
    }