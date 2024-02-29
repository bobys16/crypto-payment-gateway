<?php
if($isRouter){
    $name = str_replace("'","",$data['f_name']);
    $pw1 = str_replace("'","",$data['new_pw1']);
    $pw2 = str_replace("'","",$data['new_pw2']);
    $curr = str_replace("'","",$data['curr_pass']);

    if(isset($pw1)){
    	if($user['password'] == $curr){
    		if($pw1 == $pw2){
    			if(strlen($pw1) > 5){
    				$update = mysqli_query($connect, "UPDATE users SET name='".$name."', password='".$pw1."' WHERE id='".$_SESSION['id']."'");
    				if($update){
    					$result['success'] = 'true';
    					$result['msg'] = 'Your data has been changed succesfully!';
    				}else{
    					$result['msg'] = 'There is problem processing your request!';
    				}
    			}else{
    				$result['msg'] = 'Password must have 6-12 length in character!';
    			}
    		}else{
    			$result['msg'] = 'Your new confirmation password is not correct, please check!';
    		}
    	}else{
    		$result['msg'] = 'Your current password is not correct, please try again!';
    	}
    }else{
    	if($user['password'] == $curr){
    		$update = mysqli_query($connect, "UPDATE users SET name='".$name."' WHERE id='".$_SESSION['id']."'");
    		if($update){
    			$result['success'] = 'true';
    			$result['msg'] = 'Your name has been changed succesfully!';
    		}else{
    			$result['msg'] = 'There is problem processing your request, try again!';
    		}
    	}else{
    		$result['msg'] = 'Your current password is not correct, please try again!';
    	}
    }
}
?>