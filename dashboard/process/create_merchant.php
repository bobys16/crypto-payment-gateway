<?php
if($isRouter){
    $merchant = str_replace("'","",$data['merchant']);
    $pass = str_replace("'","",$data['curr_pass']);
    $rand = hash('sha256',rand(1000000,100000000) + time(), false);
    $cip = $_SERVER['REMOTE_ADDR'];

    if($pass == $user['password']){
    	$cek = mysqli_query($connect, "SELECT * FROM app WHERE name='".$merchant."'");
    	if(mysqli_num_rows($cek) < 1){
    		$insert = mysqli_query($connect, "INSERT INTO app VALUES(null, '$uid', '$merchant', '$rand', '0', '".time()."', '".time()."')");
    			if($insert){
                    $insert_id = mysqli_insert_id($connect);
                    mysqli_query($connect, "INSERT INTO app_config VALUES(null, '$insert_id', '1', '0', '1000', '1000', '$cip', '".time()."')");
					mysqli_query($connect, "INSERT INTO app_balance VALUES(null, '".$insert_id."','BSC','LEGO','0','".time()."')");
					mysqli_query($connect, "INSERT INTO app_balance VALUES(null, '".$insert_id."','ETHER','USDT','0','".time()."')");
					mysqli_query($connect, "INSERT INTO app_balance VALUES(null, '".$insert_id."','TRON','USDT','0','".time()."')");
    				$result['success'] = 'true';
    				$result['msg'] = 'The Merchant is created succesfully!';
    				$result['next_action'] = '/dashboard';
    			}else{
    				$result['msg'] = 'There is problem processing your request, please try again later!';
    			}
    	}else{
    		$result['msg'] = 'There is another same merchant name, please use another name!';
    	}
    }else{
    	$result['msg'] = 'Your current password is not correct, please try again!';
    }
}