<?php
    session_start();
    $mysqli = new mysqli("localhost","root","","customer");

    // Check connection{
    if ($mysqli -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

    $customers = $mysqli-> query("SELECT * FROM customer");

    if (!$customers) {
        echo("Error description: " . $mysqli -> error);
    }

    $customer_count = 0;
    foreach($customers as $row) {
        $customer_count = $customer_count + 1;
	}
    print($customer_count);

    $total_new_tokens = floor(0.8 * (100 * $customer_count));

    $date = date("Y-01-m");
    echo 'Distribute tokens for month: '.date("F Y",strtotime($date));

    // Calculate total points
    $points = $mysqli -> query("SELECT * FROM customer_points WHERE date = '".$date."'");
    $total_points_for_this_month = 0;
    foreach($points as $row) {
        $total_points_for_this_month = $total_points_for_this_month + $row['points'];
	}

    // Distribute points
    foreach($points as $row) {
        $current_user_email = $row['email'];
        $current_user_points = $row['points'];
        $new_token_allocation_for_this_month = floor($total_new_tokens / ($total_points_for_this_month / $current_user_points));
        $res = $mysqli -> query("UPDATE customer_points SET tokens=$new_token_allocation_for_this_month WHERE email='".$current_user_email."' AND date='".$date."'");
    
        $result = $mysqli -> query("SELECT * FROM customer WHERE email= '".$current_user_email."'");
        $customer = $result->fetch_assoc();
        $customer_new_total_tokens = $customer['tokens'] + $new_token_allocation_for_this_month;
        $res = $mysqli -> query("UPDATE customer SET tokens=$customer_new_total_tokens WHERE email='".$current_user_email."'");
    }


?>