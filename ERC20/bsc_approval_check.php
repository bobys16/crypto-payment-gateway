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
$decimal = $token->decimals();
// checker //

	echo "Approval BEP Address Check Started...\n";
	$query = mysqli_query($connect, "SELECT * FROM address_list WHERE type='BSC'");
	$safeGas = getSafeGasPrice();
	while($row = mysqli_fetch_assoc($query)) {
		$addr = '0x'.$row['address'];
		$priv = $row['private_key'];
		$allowance = $token->allowance($addr,$bsc_energy);
		if($allowance < bcmul("1000000000000000000",$decimal)) {
			if($eth->call('getBalance', [$addr])->toString() > Number::toWei('0.005', 'ether')) {
				
				$approve_tx = $token->approve($addr, $bsc_energy,  bcmul("1000000000000000000",$decimal), 10000, $safeGas);
				$approve_tx_id = $approve_tx->sign($priv, 56)
                            ->send();
							
				echo $addr ." going to approve by tx id ". $approve_tx_id;
							
			} else {
				
				$tx = (new TransactionBuilder())
						->setEth($eth)
						->nonce(Number::toHex($eth->getTransactionCount($bsc_energy, 'pending')))
						->to($addr)
						->amount(Number::toWei(0.008, 'ether')->toHex())
						->gasPrice($safeGas)
						->gasLimit(10000)
						->data("0x")
						->build();
						$tx_id = $tx->sign($bsc_energy_priv, 56)->send(); 
				echo $bsc_energy ." sending 0.008 BNB to ". $addr ." for approve";
				
			}
			//echo "0x".$row['address']." allowing ".$allowance."\n";
		} else {
			mysqli_query($connect, "UPDATE address_list SET approved='1' WHERE address = '".$row['address']."'");
			echo $addr ." allowing ". $allowance ." LEGO for transaction";
		}
		echo "\n";
	}	

	function getSafeGasPrice()
    {
    	
    	$getGas = json_decode(file_get_contents("https://api.bscscan.com/api?module=gastracker&action=gasoracle&apikey=F58J8HQPAXPXEYAJY4MWC1V2ET84RG15X9"),true);
    	$gasPrice = $getGas['result']['SafeGasPrice'];

        return Number::toWei($gasPrice, 'gwei') 
                     ->toHex()
            ;
    }