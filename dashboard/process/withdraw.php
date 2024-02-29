<?php
if($isRouter){
	$receiver = str_replace("'","",$data['receiver']);
	$amount 	= str_replace(array("'","-"),"",$_POST['amount']);
	$source_app = str_replace("'","",$data['source_app']);
	$currency = str_replace("'","",$data['currency']);
	$curr_pass = str_replace("'","",$data['curr_pass']);
	

	if($curr_pass == $user['password']){
		$cek = mysqli_query($connect, "SELECT * FROM app WHERE id ='".$source_app."' AND user_id='".$uid."'");
		if(mysqli_num_rows($cek) > 0){
			$app = mysqli_fetch_array($cek);
			$platform_fee = ($amount/200) ;
			if($amount > 10){
				$get_currency = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM supported_currency WHERE id='".$currency."'"));
				$recek = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id='".$app['id']."' AND currency='".$get_currency['currency_name']."' AND network='".$get_currency['currency_network']."'"));
				
				if($get_currency['currency_network'] == "TRON") {
						$trxPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=tron&vs_currencies=usd"),true);
						$fee = $tronPrice['tron']['usd'] * 10;
				} else if($get_currency['currency_network'] == "ETHER") {
						$ethPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd"),true);
						$fee = $ethPrice['ethereum']['usd'] * 0.004;
				} else if($get_currency['currency_network'] == "BSC") {
						$bnbPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=binancecoin&vs_currencies=usd"),true);
						$fee = $bnbPrice['binancecoin']['usd'] * 0.002;
				}
				$required = $amount + $platform_fee + $fee;
				
				if($recek['balance'] > $required){
				$insert = mysqli_query($connect, "INSERT INTO withdraw VALUES(null, '$source_app', '$receiver', '$amount', '0', 'Pending', '', '".time()."', '".$get_currency['currency_network']."', '".$get_currency['currency_name']."')");
					if($insert){
						$resinsert = mysqli_query($connect, "UPDATE app_balance SET balance=balance-'".$required."' WHERE app_id='".$source_app."' AND currency='".$get_currency['currency_name']."' AND network='".$get_currency['currency_network']."'");
						if($resinsert){
							$result['success'] = 'true';
							$result['msg'] = 'Withdrawal request succesfully created!';
						}else{
							$result['msg'] = 'There is problem processing your request, please contact support or try again!';
						}
					}else{
						$result['msg'] = 'There is problem processing your request, please contact support or try again!';
					}
				}else{
					$result['msg'] = 'You dont have enough balance in selected app currency, please lower your withdrawal amount and try again!';
				}
			}else{
				$result['msg'] = 'The minimum withdrawal amount is 10$, please raise your amount and try again!';
			}
			
		}else{
			$result['msg'] = 'There is no app matching with selected app, please try again!';
		}

	}else{
		$result['msg'] = 'Your current password is not match, please try again!';
	}
}
?>