<?php 
require "vendor/autoload.php";

$token = new \Lessmore92\Ethereum\StandardERC20Token("https://mainnet.infura.io/v3/3093e6030f2044c1bfd64fc07669381c");

					   ## TRANSACTION

$owner_private = '0x894e4eace2832f4e3934fe04185b065d8adb9672f71450a94f86e7c04377d828';
$owner_address = '0xAe110cEf81ac8351ee36E4421Dc0e143C3E0e30C';

$myapp_private = '0x2196d0a1889a7adc7726970600e2bb9f7ecc0c4ccbf2867ca1f9e97578dc2bde';
$myapp_address = '0x3AeF11fdebe0dC5bb3A12FEd749761a232504aA3';

$to_address = '0xFE704A4Caf57fA0d036752E573685fF0D48cfCe5';

//by this method we allow $myapp_address to send upto 99999 token behalf of $owner_address
$approve_tx    = $token->approve($owner_address, $myapp_address,  99999999);
$approve_tx_id = $approve_tx->sign($owner_private)
                            ->send();
							var_dump($approve_tx_id);
?>