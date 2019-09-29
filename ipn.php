<?php

// AYAPAY IPN EXAMPLE

include 'config.php';
    
    $address = $_POST['address'];
    $txn_id = $_POST['txn_id']; 
    $label = intval($_POST['label']);
    $confirms = intval($_POST['confirms']);
    $currency = $_POST['currency'];
    $amount = floatval($_POST['amount']); 
    $status = intval($_POST['status']); 
    $hash = $_POST['hash'];
    $hash_compare = md5($txn_id.'YOUR API KEY'); // your api key
    if($hash_compare =! $hash){
        exit;
    }
    
$con = db_connection();
$find_transaction = mysqli_query($con, "SELECT * FROM ipn_notify WHERE coin_type = '" . $currency . "' AND status = '0' AND txid = '" . $txn_id . "' AND user_id = " . $label);
 // check database to find transaction if notify not new and try to update confirms.
if ($find_transaction->num_rows > 0) {
    while ($row = $find_transaction->fetch_assoc()) {
        if (($status >= 100 || $status == 2 )) {
            //here you can accept payment and finish the progress.
            $status_text = "Deposit confirmed";    
            $update_transaction = "UPDATE ipn_notify SET confirm = '" . $confirms . "', status = '" . $status . "', status_text = '" . $status_text . "' WHERE id = " . $row["id"];
            if ($con->query($update_transaction) === TRUE) {
                die('IPN OK');
            }
        } else if ($status < 0) {
            $update_transaction = "UPDATE ipn_notify SET confirm = '" . $confirms . "', status = '" . $status . "', status_text = '" . $status_text . "' WHERE id = " . $row["id"];
            if ($con->query($update_transaction) === TRUE) {
                die('IPN OK');
            }
        }
    }
} else {
    $find_txid = mysqli_query($con, "SELECT * FROM ipn_notify WHERE coin_type = '" . $currency . "' AND txid = '" . $txn_id . "' AND user_id = " . $label); 
    //extra check for some wallet apps they are fast so with 1 confirms may comes
    if ($find_txid->num_rows > 0) {
        die('IPN OK');
    }
    if($confirms >= 1 && $status == 0){
        $status = 100;
        $status_text = "Deposit confirmed";
        //here you can accept payment and finish the progress.
    }else{
        $status_text = "Deposit detected";
    }
    //if non of all founded so it's new transaction, here will inserted with 0 confirms to check for next confirms and waiting for acception.
    $insert_transaction = "INSERT INTO ipn_notify (user_id, address, txid, coin_type, amount, confirm, status, status_text) VALUES ('" . $label . "', '" . $address . "', '" . $txn_id . "', '" . $currency . "', '" . $amount . "', '" . $confirms . "', '" . $status . "', '" . $status_text . "')";
    if ($con->query($insert_transaction) === TRUE) {
        die('IPN OK');
    }
}


?>