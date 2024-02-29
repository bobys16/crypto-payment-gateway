<?php
include "api_security.php";


if($_SERVER['REQUEST_METHOD'] == "POST") {
	$tx_id = str_replace("'","",$_POST['trx_id']);
	$query = mysqli_query($connect, "SELECT * FROM transaction WHERE trx_id = '".$tx_id."'");
	$result = array('status' => 'fail','msg' => 'unknown request');
	if(mysqli_num_rows($query) > 0) {
		$fetch = mysqli_fetch_array($query);
		if($fetch['status'] == "Expired") {
			$result['msg'] = "expired transaction.. please do another one";
			$result['data']['tx_status'] = "Expired";
			$result['data']['hash'] = null;
			 
		} else if($fetch['status'] == "Complete") {
			$result['msg'] = "transaction completed";
			$result['data']['tx_status'] = "Complete";
			$get_hashes = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
			while($hashess = mysqli_fetch_assoc($get_hashes)) {
				$result['data']['hash'][] = $hashess['hash'];
			} 
			 
		} else {
			switch($fetch['type']) { 
				case "ETHER":
					$result = CheckTransactionERC20($tx_id);
					break;
				case "TRON":
					$result = CheckTransactionTRC20($tx_id);
					break;
				case "BSC":
					$result = CheckTransactionBEP20($tx_id);
					break;
				default:
					$result = false;
			}
		}
	} else {
		$result['msg'] = "unknown tx_id";
	}
	echo json_encode($result);
}

function CheckTransactionTRC20($tx_id) {
	global $connect;
	
	$query = mysqli_query($connect, "SELECT * FROM transaction WHERE trx_id = '".$tx_id."'");
	$query_tx_hash = mysqli_query($connect, "SELECT * FROM transaction_hash WHERE tx_id='".$tx_id."'");
	$result = array('status' => 'fail','msg' => 'unknown request');
	if(mysqli_num_rows($query) > 0) {
		$fetch = mysqli_fetch_array($query);
		$result = array('status' => 'fail','msg' => 'unknown request');
		$tx_list = json_decode(file_get_contents("https://api.trongrid.io/v1/accounts/".$fetch['address']."/transactions/trc20?limit=10&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&only_confirmed=true"),true);
		$result['status'] = 'ok';
		$result['data']['hash'] = null;
		$result['data']['tx_status'] = $fetch['status'];
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
		$result['data']['hash'] = null;
		$result['data']['tx_status'] = $fetch['status'];
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
		$result['data']['hash'] = null;
		$result['data']['tx_status'] = $fetch['status'];
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
