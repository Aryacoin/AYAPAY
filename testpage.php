<?php
//test file please run after setup a config file
include 'config.php';

print_r(get_ayapay_deposit_address("AYA", 1)); 
// after run the page you should have in (user_coin_address) table in database address and user id, if you change coin type to (LTC,BTC,DOGE) also will generete address for userid 1.
// and this address will always stay in same user and whenever the address got some amount it will make transaction on ipn file with same user id, IMPORTANT please set correct ipn url in (config file), and make sure the ipn file exist there. To test IPN just need a make deposit on one of each addresses.