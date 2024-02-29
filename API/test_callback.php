<?php
$secret = $_POST['key'];
$trx_id = $_POST['trx_id'];
$detail = json_decode($_POST['detail'], true);


$file = fopen("test.txt","w");
fwrite($file, $_POST['detail']);
fclose($file);
?>