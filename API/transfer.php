<?php 
	include "api_security.php";

	$to_address = str_replace("'","",$_POST['to_address']);
	$amount = str_replace("-","",$_POST['value']);
	$network = str_replace("'","",$_POST['type']);
	$currency = str_replace("'","",$_POST['currency']);

	$query = mysqli_query($connect, "SELECT * FROM app WHERE secret = '".$key."'");
	$app_data = mysqli_fetch_array($query);
	if(is_numeric($amount)) {
		$app_config = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_config WHERE app_id='".$app_data['id']."'"));
		if($app_config['withdraw_enable'] == 0) {
			$result['msg'] = "current api doesn't have permission for transfer";
		} else {
			if($amount > $app_config['withdraw_limit']) {
				$result['msg'] = "this transaction value exceeds the limit";
			} else {
				$today = strtotime(date('Y-m-d'));
				$tomorrow = strtotime(date('Y-m-d') . " +1 days");
				$daily_limit = mysqli_fetch_array(mysqli_query($connect, "SELECT COALESCE(SUM(amount),0) as total FROM withdraw WHERE app_id = '".$app_data['id']."'AND from_api='1' AND created_at BETWEEN '".$today."' AND '".$tomorrow."'"));
				if( ($daily_limit['total'] + $amount) > $app_config['withdraw_daily']) { 
					$result['msg'] = "daily transfer limit reached";
				} else {
					if($network == "ETHER") {
						
						$app_balance = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id = '".$app_data['id']."' AND network='ETHER' AND currency='".$currency."'"));
						$ethPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd"),true);
						$platform_fee = ($amount/200) ;
						$erc20_fee = $ethPrice['ethereum']['usd'] * 0.004;
						$required = $amount + $platform_fee + $erc20_fee;
						
						if($app_balance['balance'] < $required) {
							$result['msg'] = "insufficient app balance to perform this action";
						} else {
							$insert = mysqli_query($connect, "INSERT INTO withdraw VALUES(null,'".$app_data['id']."','".$to_address."','".$amount."','1','Pending','null','".time()."','ETHER','".$currency."')");
							$transaction_id = mysqli_insert_id($connect);
							if($insert) {
								mysqli_query($connect, "UPDATE app SET balance=balance-".$required." WHERE id='".$app['id']."'");
								mysqli_query($connect, "UPDATE accumulated_fee SET amount=amount+".$platform_fee." WHERE network = 'ETHER'");
								mysqli_query($connect, "UPDATE accumulated_fee SET amount=amount+".$erc20_fee." WHERE network = 'ETH_Gas'")
								$result['status'] = "ok";
								$result['msg']	  = "transaction being processed";
								$result['data']['transaction_id'] = $transaction_id;
								$result['data']['transaction_status'] = 'Pending';
							}
						}
					} else if($network == "TRON") {
						
						$app_balance = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id = '".$app_data['id']."' AND network='TRON' AND currency='".$currency."'"));
						$trxPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=tron&vs_currencies=usd"),true);
						$platform_fee = ($amount/200) ;
						$trc20_fee = $tronPrice['tron']['usd'] * 10;
						$required = $amount + $platform_fee + $trc20_fee;
						
						if($app_balance['balance'] < $required) {
							$result['msg'] = "insufficient app balance to perform this action";
						} else {
							$insert = mysqli_query($connect, "INSERT INTO withdraw VALUES(null,'".$app_data['id']."','".$to_address."','".$amount."','1','Pending','null','".time()."','TRON','".$currency."')");
							$transaction_id = mysqli_insert_id($connect);
							if($insert) {
								mysqli_query($connect, "UPDATE app SET balance=balance-".$required." WHERE id='".$app['id']."'");
								mysqli_query($connect, "UPDATE accumulated_fee SET amount=amount+".$platform_fee." WHERE network = 'TRON'");
								mysqli_query($connect, "UPDATE accumulated_fee SET amount=amount+".$trc20_fee." WHERE network = 'TRX_Gas'")
								$result['status'] = "ok";
								$result['msg']	  = "transaction being processed";
								$result['data']['transaction_id'] = $transaction_id;
								$result['data']['transaction_status'] = 'Pending';
							}
						}
					} else if($network == "BSC") {
						
						$app_balance = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id = '".$app_data['id']."' AND network='BSC' AND currency='".$currency."'"));
						$bnbPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=binancecoin&vs_currencies=usd"),true);
						$platform_fee = ($amount/200) ;
						$bep20_fee = $bnbPrice['binancecoin']['usd'] * 0.002;
						$required = $amount + $platform_fee + $bep20_fee;
						
						if($app_balance['balance'] < $required) {
							$result['msg'] = "insufficient app balance to perform this action";
						} else {
							$insert = mysqli_query($connect, "INSERT INTO withdraw VALUES(null,'".$app_data['id']."','".$to_address."','".$amount."','1','Pending','null','".time()."','TRON','".$currency."')");
							$transaction_id = mysqli_insert_id($connect);
							if($insert) {
								mysqli_query($connect, "UPDATE app SET balance=balance-".$required." WHERE id='".$app['id']."'");
								mysqli_query($connect, "UPDATE accumulated_fee SET amount=amount+".$platform_fee." WHERE network = 'BSC'");
								mysqli_query($connect, "UPDATE accumulated_fee SET amount=amount+".$bep20_fee." WHERE network = 'BNB_Gas'")
								$result['status'] = "ok";
								$result['msg']	  = "transaction being processed";
								$result['data']['transaction_id'] = $transaction_id;
								$result['data']['transaction_status'] = 'Pending';
							}
						}
					}
					} else {
						$result['msg'] = "unknown network";
					}
				}
			}
		}
	} else {
		$result['msg'] = "non-numeric value found. transaction aborted";
	}
	echo json_encode($result);
	