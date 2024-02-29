#!/bin/bash

echo "Hello commander!, we will check balances from available balance on ether and bsc network"
echo "Preparing to execute..."
  echo "Press <CTRL+C> to exit."
sleep 10

while :
do
  php ./erc20_check_balance.php
  echo " "
  php ./bep20_check_balance.php
  echo " "
  echo "Taking some rest for awhile sir.."
  sleep 900
done

