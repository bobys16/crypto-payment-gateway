<?php
if($isRouter){
    $full = str_replace("'","",$data['full_name']);
    $email = str_replace("'","",$data['email']);
    $pass = str_replace("'","",$data['pass']);
    $pass2 = str_replace("'","",$data['pass2']);
    $company = str_replace("'","",$data['company']);
    $count = strlen($pass);

    $cek = mysqli_query($connect, "SELECT * FROM users WHERE email='".$email."'");

    if(mysqli_num_rows($cek) < 1){
    	if($pass == $pass2){
            if($count > 5){
                $insert = mysqli_query($connect, "INSERT INTO users VALUES(null, '$email', '$pass', '$full', '$company', 'verified', '".time()."', '".time()."')");
                if($insert){
                    $result['success'] = 'true';
                    $result['msg'] = 'You have succesfully registered!';
                    $_SESSION['login']  = true;
                    $_SESSION['id']     = mysqli_insert_id($connect);
                    $result['next_action'] = '/dashboard/signin';
                }else{
                    $result['msg'] = 'There is problem processing your request, please try again later!';
                }
            }else{
                $result['msg'] = 'You need to enter at least 6-12 password in length, please try again!';
            }
    	}else{
    		$result['msg'] = 'Your confirmation password did not same, please try again!';
    	}
    }else{
    	$result['msg'] = 'You can use the currect inserted email, please choose another email then try again!';
    }
}
?>