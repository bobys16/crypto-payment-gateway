<?php
if($isRouter){
	$app_key = str_replace("'","",$_POST['key']);
	$currency = str_replace("'","",$_POST['currency']);
	$amount = str_replace(array("'","-"),"",$_POST['amount']);
	$type = str_replace("'","",$_POST['type']);
	$token = '';
	$result['status'] = 'failed';
	$result['msg']	  = 'Unknown Requst';
	$result['data'] = null;
	
	$getApp = mysqli_query($connect, "SELECT * FROM app WHERE secret = '".$app_key."'");
	if(mysqli_num_rows($getApp) == 0) {
		$result['msg'] = 'Unrecognized Key';
	} else {
		$app = mysqli_fetch_array($getApp);
		$sup_currency = mysqli_query($connect, "SELECT * FROM supported_currency WHERE currency_name = '" . $currency . "' AND currency_network = '".$type."'");
		if(mysqli_num_rows($sup_currency) > 0) {
			$query = mysqli_query($connect, "SELECT * FROM address_list WHERE type='".$type."' AND status='active' ORDER BY rand() LIMIT 1");
			if(mysqli_num_rows($query) > 0) {
				$fetch = mysqli_fetch_array($query);
				$created = time();
				$usd_amount = $amount;
				if($currency == "LEGO") {
					$amount = usdToLego($amount);
				}

				$insert_trx = mysqli_query($connect, "INSERT INTO transaction VALUES(null,'".$app['id']."','".$fetch['address']."','".getAmount($amount)."','".$amount."','".$usd_amount."','".$currency."','".$type."','Waiting','null','".($created+3600) ."','".$created."')");
				$tx_id = mysqli_insert_id($connect);
				if($insert_trx) {
					mysqli_query($connect, "UPDATE address_list SET status='inactive' WHERE address='".$fetch['address']."'");
					
					$result['msg'] = "Transaction has been succesfully created, please go to transacion list to view the payment detail!";
					$result['success'] = 'true';
    				$result['next_action'] = '/dashboard';
					$_COOKIE['tx_id'] = $tx_id;
				} else {
					$result['msg'] = 'Failed! Internal Server Error';
				}
			} else {
				$result['msg'] = 'System is busy';
			}
		} else {
			$result['msg'] = "Unsupported currency";
		}
		
		
	}
}

function getAmount($amount) {
	$per = $amount/1000;
	return $amount+($per*5);
}

function usdToLego($amount) {
	$legoPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=lego-coin&vs_currencies=usd"),true);
	$ret = round($amount/$legoPrice['lego-coin']['usd'],6);
	return $ret;
}