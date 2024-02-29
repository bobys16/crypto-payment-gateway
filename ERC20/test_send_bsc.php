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


	$get_energy = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM energy_address WHERE network = 'BSC'"));
	$bsc_energy_addr = $get_energy['address'];
	$bsc_energy_priv = $get_energy['private_key'];

	$get_master = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM master_address WHERE network = 'BSC'"));
	$bsc_master_addr = "0x".$get_master['address'];
	$bsc_master_priv = $get_master['priv_key'];

	$token = new \Lessmore92\Ethereum\Token("0x1f98bd9cb8db314ced46dc43c0a91a1f9adad4f5", "https://silent-aged-silence.bsc.quiknode.pro/c7905d41235ee123f244fd2333659c0942641216/");
$decimal = $token->decimals();
    $web3 = new Web3(new HttpProvider(new HttpRequestManager("https://silent-aged-silence.bsc.quiknode.pro/c7905d41235ee123f244fd2333659c0942641216/", 2)));
    $eth = new Eth($web3);
	$approval = $token->allowance('0x'.$bsc_master_addr, $bsc_energy_addr);
	//echo $eth->getTransactionCount($bsc_energy_addr, 'pending');
	//var_dump($eth->call('getBalance', [$bsc_energy_addr])
     //               ->toString());
	
	if($approval < 1000000) {
		/*
		$tx = (new TransactionBuilder())
			->setEth($eth)
		->nonce(Number::toHex($eth->getTransactionCount($bsc_energy_addr, 'pending')))
		->to($bsc_master_addr)
		->amount(Number::toWei(0.01, 'ether')->toHex())
		->gasPrice(getSafeGasPrice())
		->gasLimit(10000)
		->data("0x")
		->build();
		$signed = $tx->sign($bsc_energy_priv, 56); 
		var_dump($signed->send());
		*/
		
				$approve_tx = $token->approve($bsc_master_addr, $bsc_energy_addr, bcmul("100000000000",$decimal), 10000, getSafeGasPrice());
				$approve_tx_id = $approve_tx->sign($bsc_master_priv, 56)
                            ->send();
							
				echo $bsc_master_addr ." going to approve by tx id ". $approve_tx_id;
		
							
	}
	
	//echo Number::toHex(getSafeGasPrice());



	function wei2eth($wei) {
		return bcdiv($wei,'1000000000000000000',18);
	}
	function wei2gwei($wei) {
	   	return bcdiv($wei,'1000000000',9);
	}
	function getSafeGasPrice()
    {
    	
    	$getGas = json_decode(file_get_contents("https://api.bscscan.com/api?module=gastracker&action=gasoracle&apikey=F58J8HQPAXPXEYAJY4MWC1V2ET84RG15X9"),true);
    	$gasPrice = $getGas['result']['SafeGasPrice'];

        return Number::toWei($gasPrice, 'gwei') 
                     ->toHex()
            ;
    }

