<?php
	date_default_timezone_set('Etc/UTC');
    $his = date("d-m-Y H:i:s");
    $result = array('success' => "false", 'msg' => 'Nothing to do');
    $connect = mysqli_connect('localhost','elzgar','123Tegar','gateway');
    if (!$connect) {
        die("Internal server error!");
    }

    $request_ip = $_SERVER['REMOTE_ADDR'];
    $result = array('status' => 'rejected','msg' => 'nothing to do..');
    $key = str_replace("'", "", $_POST['key']);
    $query = mysqli_query($connect, "SELECT a.*,b.* FROM app a INNER JOIN app_config b ON a.id = b.app_id WHERE a.secret = '".$key."'");
    if(mysqli_num_rows($query) > 0) {
        $fetch = mysqli_fetch_array($query);
        $ip_whitelist = $fetch['registered_ip'];
        $result['status'] = 'fail';
    } else {
        $result['error'] = $key;
        $result['msg'] = "Request denied!";
    }
    if($result['status'] == "rejected") {
        echo json_encode($result);
        exit;
    }