<?php
	date_default_timezone_set('Etc/UTC');
    $his = date("d-m-Y H:i:s");
    $result = array('success' => "false", 'msg' => 'Nothing to do');
    $connect = mysqli_connect('localhost','root','test','gateway');
    if (!$connect) {
        die("Internal server error!");
    }

 function getUserEntireData($id) {
        global $connect;
        $query = mysqli_query($connect, "SELECT * FROM users WHERE id='".$id."'");
        $res = mysqli_fetch_array($query);
        return $res;
    }    
?>