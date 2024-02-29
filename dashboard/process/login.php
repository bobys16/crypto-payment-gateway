<?php

if($isRouter == "true") {
    $user = str_replace("'","",$data['email']);
    $pass = str_replace("'","",$data['password']);
    $get  = mysqli_query($connect, "SELECT * FROM users WHERE email='$user'");
	
    if(mysqli_num_rows($get) > 0) {
        $fetch = mysqli_fetch_array($get);
        if($fetch['password'] == $pass) {
			if($fetch['status'] == 'verified'){
					$result['msg']      = "Login success, redirecting..";
					$result['success']  = "true";
					$_SESSION['login']  = true;
					$result['next_action'] = 'dashboard';
					$_SESSION['id']     = $fetch['id'];
			}else{
				$result['msg']		= "Please confirm your email address first in order to start using our services !";
			}
				
        } else {
            $result['msg']      = "Login failed. Unmatched password";
        }
    } else {
        $result['msg'] = "User couldn't be found";
    }
}