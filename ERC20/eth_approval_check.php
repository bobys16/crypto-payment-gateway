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
// checker //

	echo "Approval ERC Address Check Started...\n";
	$query = mysqli_query($connect, "SELECT * FROM address_list WHERE type='ETHER' AND approved = 0");
	//$safeGas = getSafeGasPrice();
	$i = 0;
	while($row = mysqli_fetch_assoc($query)) {
		$addr = '0x'.$row['address'];
		$priv = $row['private_key'];
		$allowance = $token->allowance($addr,$erc_energy);
		if($allowance < bcmul("100000",$decimal)) {
			if($eth->call('getBalance', [$addr])->toString() > Number::toWei('0.003', 'ether')) {
				
				$approve_tx = $token->approve($addr, $erc_energy,  bcmul("100000000000",$decimal), Number::toHex(90000), getSafeGasPrice());
				$approve_tx_id = $approve_tx->sign($priv, 1)
                            ->send();
							
				echo $addr ." going to approve by tx id ". $approve_tx_id;
							
			} else {
				
				$tx = (new TransactionBuilder())
						->setEth($eth)
						->nonce(Number::toHex($eth->getTransactionCount($erc_energy, 'pending')))
						->to($addr)
						->amount(Number::toWei(0.003, 'ether')->toHex())
						->gasPrice(getSafeGasPrice())
						->gasLimit(Number::toHex(100000))
						->data("0x")
						->build();
						$tx_id = $tx->sign($erc_energy_priv, 1)->send(); 
				echo $erc_energy ." sending 0.003 ETH to ". $addr ." for approve";
			
			}
			//echo "0x".$row['address']." allowing ".$allowance."\n";
		} else {
			mysqli_query($connect, "UPDATE address_list SET approved='1' WHERE address = '".$row['address']."'");
			echo $addr ." allowing ". $allowance ." USDT for transaction";
		}
		echo "\n";
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