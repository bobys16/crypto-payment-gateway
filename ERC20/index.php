<?php 
require "vendor/autoload.php";

$token = new \Lessmore92\Ethereum\Token("0xFab46E002BbF0b4509813474841E0716E6730136", "https://rinkeby.infura.io/v3/c508479675d04b2eb7c9e35931a45d12");

/*
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


//the magic is here, $myapp_address send 10 tokens behalf of user and $myapp_address pay transaction fee
$transfer_tx    = $token->transferFrom($myapp_address, $owner_address, $to_address, 800);
$transfer_tx_id = $transfer_tx->sign($myapp_private)
                              ->send();
//var_dump($transfer_tx_id);
*/
echo hexdec("0x00000000000000000000000000000000000000000000002b5e3af16b18800000")/1000000000000000000;

function decodeHex($input)
{
    if (substr($input, 0, 2) == '0x') {
        $input = substr($input, 2);
    }

    if (preg_match('/[a-f0-9]+/', $input)) {
        return hexdec($input);
    }

    return $input;
}

?>