<?php

// just db connection
function db_connection() {
    $connection = mysqli_connect("HOST", "DB_USER", "DB_PASSWORD", "DB_NAME"); //set your database info
    return $connection;
} 

// Set Basement for each call then, don't needed for each call set same default settings.
function ayapay_api_call($cmd, $req = array()) { 
    $req['version'] = 1; 
    $req['cmd'] = $cmd; 
    $req['api_key']= 'YOUR API KEY HERE'; // your API key.
    $req['format'] = 'json';
    $post_data = http_build_query($req, '', '&'); 
    $ch = curl_init('https://ayapay.aryacoin.io/apiv1');
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
    $data = curl_exec($ch);                 
    if ($data !== FALSE) { 
        if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) { 
            $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING); 
        } else { 
            $dec = json_decode($data, TRUE); 
        } 
        if ($dec !== NULL && count($dec)) { 
            return $dec; 
        } else { 
            return array('error' => 'Unable to parse JSON result ('.json_last_error().')'); 
        } 
    } else { 
        return array('error' => 'cURL error: '.curl_error($ch)); 
    } 
}

// Create deposit address 
function get_ayapay_deposit_address($coin_type, $user_id){
    // Checking if in Database user has already registred address with same coin type, then will return it, it's used to stop genereting extra address for same the user.  
    $con = db_connection();
	$find_wallet = mysqli_query($con, "SELECT * FROM user_coin_address WHERE coin_type = '".$coin_type."' AND user_id = ".$user_id);
    if ($find_wallet->num_rows > 0) {
        while ($row = $find_wallet->fetch_assoc()) {
            $final_result['address'] = $row['wallet_address'];
            $final_result['user_id'] = $row['user_id'];
            return $final_result;
        }
    }else{
        // if user has not address with same coin type, then here with userid, ipn url and coin type it will making a new address and insert it to database.
        $data = array();
        $data['ipn_url'] = 'https://yourwebsite.com/ipn.php'; // here should be set IPN url to get notification whenever this address get deposited.
        $data['currency'] = $coin_type;
        $data['label'] = $user_id;
        $address_ar = ayapay_api_call('get_callback_address',$data);
        if($address_ar['error']=='ok'){
            $final_result['address']= $address_ar['result']['address'];
            $final_result['user_id']= $user_id;
            $create_user_wallet = "INSERT INTO user_coin_address (user_id, coin_type, wallet_address) VALUES ('".$user_id."', '".$coin_type."', '".$final_result['address']."')";
            if ($con->query($create_user_wallet) === TRUE) {
               return $final_result;
            }
        }else{
            return 0;
        }
        
    }
}


// Make withdrawal
function ayapay_withdraw($coin_type, $user_id, $address, $amount){ // user id is optional if you want it save the withdrawal in database related to that user.
    $data = array();
    $data['ipn_url'] = 'https://yourwebsite.com/ipn.php';
    $data['address'] = $address;
    $data['amount'] = $amount;
    $data['currency'] = $coin_type;
    $data['auto_confirm'] = 1;
    $withdraw_ar = ayapay_api_call('create_withdrawal',$data);
    if($withdraw_ar['error']=='ok'){
        print_r ($withdraw_ar); // you can save it in database.
    }else{
        print_r ($withdraw_ar);
    }
}

?>