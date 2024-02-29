<?php
	include "config.php";
	$get_app = mysqli_query($connect, "SELECT * FROM app");
	while($row = mysqli_fetch_assoc($get_app)) {
		$q = mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id = '".$row['id']."'");
		if(mysqli_num_rows($q) == 0) {
			$lego = mysqli_fetch_array(mysqli_query($connect, "SELECT COALESCE(SUM(real_amount),0) as balance FROM transaction WHERE app_id='".$row['id']."' AND type='BSC' AND currency='LEGO' AND status='Complete'"));
			$erc_usdt = mysqli_fetch_array(mysqli_query($connect, "SELECT COALESCE(SUM(real_amount),0) as balance FROM transaction WHERE app_id='".$row['id']."' AND type='ETHER' AND currency='USDT' AND status='Complete'"));
			$trc_usdt = mysqli_fetch_array(mysqli_query($connect, "SELECT COALESCE(SUM(real_amount),0) as balance FROM transaction WHERE app_id='".$row['id']."' AND type='TRON' AND currency='USDT' AND status='Complete'"));
			mysqli_query($connect, "INSERT INTO app_balance VALUES(null, '".$row['id']."','BSC','LEGO','".$lego['balance']."','".time()."')");
			mysqli_query($connect, "INSERT INTO app_balance VALUES(null, '".$row['id']."','ETHER','USDT','".$erc_usdt['balance']."','".time()."')");
			mysqli_query($connect, "INSERT INTO app_balance VALUES(null, '".$row['id']."','TRON','USDT','".$trc_usdt['balance']."','".time()."')");
		}
	}