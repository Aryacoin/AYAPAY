# AYAPAY API V1
Ayapay , cryptocurrency payment api and ipn

This repository contains AYAPAY API v1 client side implementation.

Ayapay is an api and ipn which allows users to recieve and send payments with very low fees.


Instructions for setup using the files -
AYA PAY example setup

1- import table.sql in your database

2- set user host and password of your database in config file

3- set your api key in config file also IPN file

4- set set ipn file located url in Create deposit address founction in config file **IMPORTANT

5- for test run testpage.php

// after run the testpage you should have in (user_coin_address) table in database address and user id, if you change coin type to (LTC,BTC,DOGE) also will generete address for userid 1.
// and this address will always stay in same user and whenever the address got some amount it will make transaction on ipn file with same user id, IMPORTANT please set correct ipn url in (config file), and make sure the ipn file exist there. To test IPN just need a make deposit on one of each addresses.
