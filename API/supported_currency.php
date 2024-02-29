<?php
include "api_security.php";
date_default_timezone_set('Etc/UTC');


if($_SERVER['REQUEST_METHOD'] == "POST") {
	$key = str_replace("'","",$_POST['key']);
	$result['status'] = 'failed';
	$result['msg']	  = 'Unknown Requst';
	$result['data'] = null;
	$check_key = mysqli_query($connect, "SELECT * FROM app WHERE key='".$key."'");
	if(mysqli_num_rows($check_key) > 0) {
		$query = mysqli_query($connect, "SELECT * FROM supported_currency");
		$result['status'] = 'success';
		$result['msg'] = "ok";
		while($row = mysqli_fetch_assoc($query)) {
			$result['data'][]= $row;
		}
	} else {
		$result['msg'] = "unknown key";
	}
}