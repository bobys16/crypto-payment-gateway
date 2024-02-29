<?php
include "../dashboard/config.php";
date_default_timezone_set('Etc/UTC');
while(true) {
	$query = mysqli_query($connect, "SELECT * FROM transaction WHERE status='Waiting'");
	$date = time();

	while($r = mysqli_fetch_assoc($query)){
		echo "\033[1;32m Processing request of ".$r['trx_id']." With Result : ";
		if($r['status'] == 'Waiting'){
			if($r['expired_at'] < $date){
				if($r['status'] == 'Waiting'){
					switch($r['type']) { 
						case "ETHER":
							$result = CheckTransactionERC20($r['trx_id']);
							break;
						case "TRON":
							$result = CheckTransactionTRC20($r['trx_id']);
							break;
						case "BSC":
							$result = CheckTransactionBEP20($r['trx_id']);
							break;
						default:
							$result = false;
					}
					if($result['data']['tx_status'] == 'Waiting'){
						$update = mysqli_query($connect, "UPDATE transaction SET status='Expired' WHERE trx_id='".$r['trx_id']."'");
						$result['data']['tx_status'] = 'Expired';
						$result['msg'] = 'transaction expired';
						if($update){
							mysqli_query($connect, "UPDATE address_list SET status='active' WHERE address ='".$r['address']."'");
							echo "Transaction Expired!\n";
						}else{
							echo "There is problem updating status of the transactions!\n";
						}
					}
				}else{
					echo "Transaction are Complete already or something else, so nothing to do!\n";
				}
			}else{
				switch($r['type']) { 
					case "ETHER":
						$result = CheckTransactionERC20($r['trx_id']);
						break;
					case "TRON":
						$result = CheckTransactionTRC20($r['trx_id']);
						break;
					case "BSC":
						$result = CheckTransactionBEP20($r['trx_id']);
						break;
					default:
						$result = false;
				}
				echo " Status is ". $result['msg']. "\n";
				/*
				if($result['data']['tx_status'] == "Waiting") {
					$update = mysqli_query($connect, "UPDATE transaction SET status='Expired'");
					mysqli_query($connect, "UPDATE address_list SET status='active' WHERE address ='".$r['address']."'");
					$result['data']['tx_status'] = "Expired";
					if($update){
						echo $result['msg'];
						echo " Status is Expired\n";
					}else{
						echo " Something went wrong updating the data!\n";
					}	
				}
				*/
			}
		}else if($r['status'] == 'Complete'){
			echo "Completed Already!\n";

		}else if($r['status'] == 'Expired'){
			echo " Expired Already!\n";
		}
		$get_app = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app WHERE id='".$r['app_id']."'"));
		$app_config = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_config WHERE app_id='".$r['app_id']."'"));
		$data['secret'] = $get_app['secret'];
		$data['trx_id'] = $r['trx_id'];
		$data['transaction_type'] = "Income";
		$data['detail']  = json_encode($result['data']);
		send_callback($app_config['callback_url'], $data);
		sleep(2);
	}
	echo "Sleep for 300s\n\n";
	sleep(300);
}

function CheckTransactionTRC20($tx_id) {
	global $connect;
	
	$query = mysqli_query($connect, "SELECT * FROM transaction WHERE trx_id = '".$tx_id."'");
	$result = array('status' => 'fail','msg' => 'unknown request');
	if(mysqli_num_rows($query) > 0) {
		$fetch = mysqli_fetch_array($query);
		$result = array('status' => 'fail','msg' => 'unknown request');
		$tx_list = json_decode(file_get_contents("https://api.trongrid.io/v1/accounts/".$fetch['address']."/transactions/trc20?limit=10&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&only_confirmed=true"),true);
		$result['status'] = 'ok';
		$result['msg']    = 'waiting';
		$result['data']['hash'] = null;
		$result['data']['tx_status'] = 'Waiting';
		foreach($tx_list['data'] as $key => $value) {
			if($fetch['created_at'] < ($value['block_timestamp']/1000) && ($value['block_timestamp']/1000) < $fetch['expired_at']) {
				$hash = $value['transaction_id'];
				$tx_val = ($value['value']/1000000);
				$q = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE hash='".$hash."'");
				if(mysqli_num_rows($q) < 1){
					$insert_to_hashes = mysqli_query($connect, "INSERT INTO transaction_hash VALUES(null,'".$hash."','".$tx_id."','".$fetch['address']."','".$tx_val."','".time()."')");
					if($insert_to_hashes) {
						$summ = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(value) as total FROM transaction_hash WHERE tx_id = '".$tx_id."'"));
						if($summ['total'] >= $fetch['amount']) {
							mysqli_query($connect, "UPDATE transaction SET status='Complete' WHERE trx_id='".$tx_id."'");
							mysqli_query($connect, "UPDATE app_balance SET balance=balance+".$fetch['real_amount']." WHERE app_id='".$fetch['app_id']."' AND network='TRON' AND currency='USDT'");
							$result['msg'] = "Payment received!";
							$get_hashes = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
							while($hashess = mysqli_fetch_assoc($get_hashes)) {
								$result['data']['hash'][] = $hashess['hash'];
							}
							$result['data']['timeStamp'] = ($value['block_timestamp']/1000);
							$result['data']['tx_status'] = 'Complete';
						} else {
							$result['msg'] = "Payment partially complete";
							$result['data']['amount_left'] = $fetch['amount'] - $summ['total'];
							$result['data']['tx_status'] = 'partial_complete';
						}
					}
				}
				
			}
		}
		if($result['data']['tx_status'] == "Waiting") {
			$ck = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
			if(mysqli_num_rows($ck) > 0) {
				$summ = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(value) as total FROM transaction_hash WHERE tx_id = '".$tx_id."'"));
				$result['msg'] = "Payment partially complete";
				$result['data']['amount_left'] = $fetch['amount'] - $summ['total'];
				$result['data']['tx_status'] = 'partial_complete';
				while($hashess = mysqli_fetch_assoc($ck)) {
					$result['data']['hash'][] = $hashess['hash'];
				}
			}
		}
	} else {
		$result['msg'] = "unknown tx_id";
	}	
	return $result;
}



function CheckTransactionERC20($tx_id) {
	global $connect;
	$query = mysqli_query($connect, "SELECT * FROM transaction WHERE trx_id = '".$tx_id."'");
	if(mysqli_num_rows($query) > 0) {
		$fetch = mysqli_fetch_array($query);
		$result = array('status' => 'fail','msg' => 'unknown request');
		$tx_list = json_decode(file_get_contents("https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=0xdac17f958d2ee523a2206206994597c13d831ec7&address=0x".$fetch['address']."&startblock=0&endblock=99999999&page=1&offset=5&sort=desc&apikey=5XGPQQEZMYTD999FGTC1JGZM736WFTHP5Q"),true);
		$result['status'] = 'ok';
		$result['msg']    = 'waiting';
		$result['data']['hash'] = null;
		$result['data']['tx_status'] = 'Waiting';
		foreach($tx_list['result'] as $key => $value) {
			if($fetch['created_at'] < $value['timeStamp']  && $value['timeStamp']  < $fetch['expired_at']) {				
				if((int)$value['confirmations'] > 12 && $value['to'] == "0x".$fetch['address']) {
					$hash = $value['hash'];
					$q = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE hash='".$hash."'");
					if(mysqli_num_rows($q) < 1){
						$cu = curl("https://mainnet.infura.io/v3/c508479675d04b2eb7c9e35931a45d12",'{"jsonrpc":"2.0","method":"eth_getTransactionReceipt","params": ["'.$hash.'"],"id":1}');
						$get_value= json_decode($cu,true);
						$tx_val = (hexdec($get_value['result']['logs'][0]['data'])/1000000);
						$insert_to_hashes = mysqli_query($connect, "INSERT INTO transaction_hash VALUES(null,'".$hash."','".$tx_id."','".$fetch['address']."','".$tx_val."','".time()."')");
						if($insert_to_hashes) {
							$summ = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(value) as total FROM transaction_hash WHERE tx_id = '".$tx_id."'"));
							if($summ['total'] >= $fetch['amount']) {
								mysqli_query($connect, "UPDATE transaction SET status='Complete' WHERE trx_id='".$tx_id."'");
								mysqli_query($connect, "UPDATE app_balance SET balance=balance+".$fetch['real_amount']." WHERE app_id='".$fetch['app_id']."' AND network='ETHER' AND currency='USDT'");
								$result['msg'] = "Payment received!";
								$get_hashes = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
								while($hashess = mysqli_fetch_assoc($get_hashes)) {
									$result['data']['hash'][] = $hashess['hash'];
								}
								$result['data']['timeStamp'] = $value['timeStamp'];
								$result['data']['tx_status'] = 'Complete';
							} else {
								$result['msg'] = "Payment partially complete";
								$result['data']['amount_left'] = $fetch['amount'] - $summ['total'];
								$result['data']['tx_status'] = 'partial_complete';
							}
						}
					}						
				}
			}
		}
		if($result['data']['tx_status'] == "Waiting") {
			$ck = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
			if(mysqli_num_rows($ck) > 0) {
				$summ = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(value) as total FROM transaction_hash WHERE tx_id = '".$tx_id."'"));
				$result['msg'] = "Payment partially complete";
				$result['data']['amount_left'] = $fetch['amount'] - $summ['total'];
				$result['data']['tx_status'] = 'partial_complete';
				while($hashess = mysqli_fetch_assoc($ck)) {
					$result['data']['hash'][] = $hashess['hash'];
				}
			}
		}
	} else {
		$result['msg'] = "unknown tx_id";
	}	
	return $result;
}
 // lego-coin

function CheckTransactionBEP20($tx_id) {
	global $connect;
	$query = mysqli_query($connect, "SELECT * FROM transaction WHERE trx_id = '".$tx_id."'");
	if(mysqli_num_rows($query) > 0) {
		$fetch = mysqli_fetch_array($query);
		$result = array('status' => 'ok');
		$tx_list = json_decode(file_get_contents("https://api.bscscan.com/api?module=account&action=tokentx&contractaddress=0x1F98BD9CB8Db314Ced46Dc43C0a91a1F9aDAD4F5&address=0x".$fetch['address']."&startblock=0&endblock=99999999&page=1&offset=5&sort=desc&apikey=F58J8HQPAXPXEYAJY4MWC1V2ET84RG15X9"),true);
		$result['status'] = 'ok';
		$result['msg']	  = 'waiting';
		$result['data']['hash'] = null;
		$result['data']['tx_status'] = 'Waiting';
		foreach($tx_list['result'] as $key => $value) {
			if($fetch['created_at'] < $value['timeStamp']  && $value['timeStamp']  < $fetch['expired_at']) {				
				if((int)$value['confirmations'] > 12 && $value['to'] == "0x".$fetch['address']) {
					$hash = $value['hash'];
					$q = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE hash='".$hash."'");
					if(mysqli_num_rows($q) < 1){
						$tx_val = ($value['value']/1000000000000000000);
						$insert_to_hashes = mysqli_query($connect, "INSERT INTO transaction_hash VALUES(null,'".$hash."','".$tx_id."','".$fetch['address']."','".$tx_val."','".time()."')");
						if($insert_to_hashes) {
							$summ = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(value) as total FROM transaction_hash WHERE tx_id = '".$tx_id."'"));
							if($summ['total'] >= $fetch['amount']) {
								mysqli_query($connect, "UPDATE transaction SET status='Complete' WHERE trx_id='".$tx_id."'");
								mysqli_query($connect, "UPDATE app_balance SET balance=balance+".$fetch['real_amount']." WHERE app_id='".$fetch['app_id']."' AND network='BSC' AND currency='LEGO'");
								$result['msg'] = "Payment received!";
								$get_hashes = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
								while($hashess = mysqli_fetch_assoc($get_hashes)) {
									$result['data']['hash'][] = $hashess['hash'];
								}
								$result['data']['timeStamp'] = $value['timeStamp'];
								$result['data']['tx_status'] = 'Complete';
							} else {
								$result['msg'] = "Payment partially complete";
								$result['data']['amount_left'] = $fetch['amount'] - $summ['total'];
								$result['data']['tx_status'] = 'partial_complete';
							}
						}
					}
				}
			}
		}
		if($result['data']['tx_status'] == "Waiting") {
			$ck = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
			if(mysqli_num_rows($ck) > 0) {
				$summ = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(value) as total FROM transaction_hash WHERE tx_id = '".$tx_id."'"));
				$result['msg'] = "Payment partially complete";
				$result['data']['amount_left'] = $fetch['amount'] - $summ['total'];
				$result['data']['tx_status'] = 'partial_complete';
				while($hashess = mysqli_fetch_assoc($ck)) {
					$result['data']['hash'][] = $hashess['hash'];
				}
			}
		}
	} else {
		$result['msg'] = "unknown tx_id";
	}	
	return $result;
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
function decodeHex($input)
{
    if (substr($input, 0, 2) == '0x') {
        $input = substr($input, 2);
    }

    if (preg_match('/[a-f0-9]+/', $input)) {
        return hexdec($input);
    }

    return $input;
}
?>