<?php
if($isRouter){
	$enable = $data['enable'];
	$wd_enable = $data['withdraw_enabled'];
	$ip = str_replace("'","",$data['registered_ip']);
	$wd_limit = str_replace("'","",$data['withdraw_limit']);
	$wd_daily = str_replace("'","",$data['withdraw_daily']);
	$pass = str_replace("'","",$data['curr_pass']);
	$call = str_replace("'", "", $data['callback_url']);


	$app_id = $_COOKIE['app_id'];

	$cek1 = mysqli_query($connect, "SELECT * FROM app WHERE id='".$app_id."' AND user_id='".$uid."'");




	if(isset($enable)){
		$v_enable = '1';
	}else{
		$v_enable = '0';
	}

	if(isset($wd_enable)){
		$v_wd = '1';
	}else{
		$v_wd = '0';
	}

	if(mysqli_num_rows($cek1) < 1){
		$result['msg'] = 'Are you trying to bypass the security? try again!';
	}else{
		if($pass == $user['password']){
			$update = mysqli_query($connect, "UPDATE app_config SET enabled='".$v_enable."', withdraw_enable='".$v_wd."', withdraw_limit='".$wd_limit."', withdraw_daily='".$wd_daily."', callback_url='".$call."', registered_ip='".$ip."', update_at='".time()."'  WHERE app_id='".$app_id."'");
			if($update){
				$result['success'] = 'true';
				$result['msg'] = 'The config is succesfully updated!';
				$result['next_view'] = 'app_config';
			}else{
				$result['msg'] = 'There is problem processing your request, please try again!';
			}
		}else{
			$result['msg'] = 'Your current account password is not correct!';
		}
	}
		
} 
?>