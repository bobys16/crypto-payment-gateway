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


	$getTf = mysqli_query($connect, "SELECT * FROM withdraw WHERE status='Pending' AND network='BSC' AND currency='LEGO'");
	while($row = mysqli_fetch_assoc($getTf)) {
		$wd_id = $row['id'];
		$target = $row['to_address'];
		$amount = $row['amount'];
		$callback = array();
		$callback['send'] = 'false';
		try {
			$transfer_tx    = $token->transferFrom($bsc_energy, $bsc_master, $target, $amount, 50000, $safeGas);
			$transfer_tx_id = $transfer_tx->sign($bsc_energy_priv, 56)
								  ->send();
		} catch (Exception $e) {
			
			echo "Catch error: ".$e->getMessage();
		}
		
		if($transfer_tx_id) {
			mysqli_query($connect, "UPDATE withdraw SET status='Complete',tx_hash='".$transfer_tx_id."' WHERE id='".$wd_id."'");
			$callback['data']['hash'] = $transfer_tx_id;
			$callback['data']['trx_id'] = $wd_id;
			$callback['data']['tx_status'] = 'Complete';
			$callback['send'] = 'true';
			
		} else {
			echo "Error transfer";
		}
		if($callback['send'] == "true") {
			$app = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app WHERE id='".$row['app_id']."'"));
			$key = $app['secret'];
			$cb_data['transaction_type'] = 'Transfer';
			$cb_data['secret'] = $key;
			$cb_data['detail'] = $callback['data'];
			$cb_data['trx_id'] = $wd_id;
			$app_config = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_config WHERE app_id = '".$row['app_id']."'"));
			if($app_config['callback_url'] == "" || $app_config['callback_url'] == null) {
				echo "no callback found";
			} else {
				send_callback($app_config['callback_url'], $cb_data);
				echo "callback send";
			}
		}
		echo "\n";
	}
	
	
	
	function send_callback($url, $data) {
		$build_data = "key=".$data['secret']."&trx_id=".$data['trx_id']."&transaction_type=".$data['transaction_type']."&detail=".json_encode($data['detail']);
		curl($url, $build_data);
		return;
	}

	function curl($url, $data = null, $header=null) {
		$headers = array(
			'Content-type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($header !== null) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		if($data !== null) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	function getSafeGasPrice()
    {
    	
    	$getGas = json_decode(file_get_contents("https://api.bscscan.com/api?module=gastracker&action=gasoracle&apikey=F58J8HQPAXPXEYAJY4MWC1V2ET84RG15X9"),true);
    	$gasPrice = $getGas['result']['SafeGasPrice'];

        return Number::toWei($gasPrice, 'gwei') 
                     ->toHex()
            ;
    }