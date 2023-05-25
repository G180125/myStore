<?php 
    session_start();
    function displayOrder($status) {
        $data = array();
        $file = fopen("gs://s3904632group/order.csv", 'r');
        $skip = 0;
        while(($data = fgetcsv($file,null,",")) != FALSE){
            if($skip == 0){
                $skip = $skip + 1;
            }else{ 
                if($status === 'All'){
                    $orderEmail = $data[0];
                    echo '<div class="order">';
                    echo '<span>First Name: ' .$data[1]. '</span>';
                    echo '<span>Last Name: ' .$data[2]. '</span>';
                    echo '<span>Address: ' .$data[3]. '</span>';
                    echo '<span>Post Code: ' .$data[4]. '</span>';
                    echo '<span>City/Province: ' .$data[5]. '</span>';
                    echo '<span>Phone: ' .$data[6]. '</span>';
                    echo '<span>Order Status: ' .$data[7]. '</span>';
                    echo '<table>';
                    displayProductPurchase($orderEmail, $skip);
                    echo '</table>';
                    echo '<div class="total">';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="action" value="toDelivery">';
                    echo '<input type="hidden" name="orderEmail" value="' . $orderEmail . '">';
                    echo '<button type="submit" name="submit">To delivery</button>';
                    echo '</form>';
                    echo '<span>Total:' .$data[8]. '</span>';
                    echo '</div>';
                    echo '</div>';
                }else{
                    if($data[7] === $status){
                        $orderEmail = $data[0];
                        echo '<div class="order">';
                        echo '<span>First Name: ' .$data[1]. '</span>';
                        echo '<span>Last Name: ' .$data[2]. '</span>';
                        echo '<span>Address: ' .$data[3]. '</span>';
                        echo '<span>Post Code: ' .$data[4]. '</span>';
                        echo '<span>City/Province: ' .$data[5]. '</span>';
                        echo '<span>Phone: ' .$data[6]. '</span>';
                        echo '<span>Order Status: ' .$data[7]. '</span>';
                        echo '<table>';
                        displayProductPurchase($orderEmail, $skip);
                        echo '</table>';
                        echo '<div class="total">';
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="action" value="toDelivery">';
                        echo '<input type="hidden" name="orderEmail" value="' . $orderEmail . '">';
                        echo '<button type="submit" name="submit">To delivery</button>';
                        echo '</form>';
                        echo '<span>Total:' .$data[8]. '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                $skip = $skip + 1;
            }
        }
        fclose($file);
    }

    function displayProductPurchase($orderEmail, $index) {
        $product_data = array();
        $file = fopen("gs://s3904632group/cart.csv", 'r');
        $skip = 0;
        while(($product_data = fgetcsv($file,null,",")) != FALSE){
            if($skip == 0){
                echo '<tr>';
                for ($i = 0; $i < count($product_data); $i++){
                    if($i != 0 && $i != 4 && $i != 5 && $i != 6){ // Skip the email, subtotal, process and index column
                        echo '<th>'. $product_data[$i]. '</th>';
                    }
                }
                echo '</tr>';
                $skip = $skip + 1;
            }else{
                if($product_data[0] === $orderEmail && $product_data[6] == $index){
                    echo '<tr>';
                    for ($i = 0; $i < count($product_data); $i++){
                        if($i != 0 && $i != 4 && $i != 5 && $i != 6){ //skip the email, subtotal, process and index column
                            echo '<td>'. $product_data[$i]. '</td>';
                        }
                    }
                    echo '</tr>'; 
                }
            }
        }
        fclose($file);
    }

    function toDelivery($email) {
        $data = array();
        $file = fopen("gs://s3904632group/order.csv", 'r'); // Open in read and write mode
        
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/order.csv";
            return false;
        }
        
        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }
    
        for($i = 0; $i < count($data); $i++) {
            if($data[$i][0] === $email && $data[$i][7] === 'Packaging'){
                $first_name = $data[$i][1];;
                $last_name = $data[$i][2];;
                $address = $data[$i][3];;
                $post_code = $data[$i][4];;
                $city = $data[$i][5];;
                $phone = $data[$i][6];;
                $order_status = 'Delivering';
                $total = $data[$i][8];

                $updateOrder = array(
                    $email,
                    $first_name,
                    $last_name,
                    $address,
                    $post_code,
                    $city,
                    $phone,
                    $order_status,
                    $total
                );

                $data[$i] = $updateOrder;
                break;
            }
        }
    
        $file = fopen("gs://s3904632group/order.csv", 'w'); // Open in write mode
        if($file === false){
            echo "Failed to open file: gs://s3904632group/order.csv";
            return false;
        }
            
        for($i = 0; $i < count($data); $i++){
            fputcsv($file, $data[$i]);
        }
        fclose($file);

        echo "<script>alert('Order status updated successfully!');</script>";        
    }
    
    
    if (isset($_POST['action']) && $_POST['action'] == 'toDelivery') {
        toDelivery($_POST['orderEmail']);
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>myStore</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;400;700&display=swap');

        *{
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: gray;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 25px 2.5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            font: 'Poppins', sans-serif;
        }

        .navbar a {
            width: 100%;
            text-align: left;
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            margin-left: 30px;
        }

        .button-group button {
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-left: 10px;
        }

        .button-group button:hover{
            cursor: pointer;
        }

        .container {
            margin-top: 8%;
        }

        .title {
            margin-top: 8%;
            width: 100%;
            justify-content: center;
            text-align: center;
        }

        .title h2 {
            font-size: 56px;
            font-style: italic;
        }

        .sub-title {
            margin: 10px 2.5%;
        }

        .order-list {
            margin: 20px 2.5%;
        }

        .select-status {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .order {
            padding: 15px 2.5%;
            display: flex;
            flex-direction: column;
            border: 1px solid;
            border-radius: 10px;
            margin-top: 10px;
        }

        .order > * {
            margin-bottom: 5px;
        }

        table {
            margin: 0px 20px;
        } 

        table, td, th, tr {
            border-collapse: collapse;
            border: 1px solid black;
            padding: 10px;
        }

        table tr:nth-child(odd) {
            background-color: #cfcdcd;
        }

        .total {
            margin: 20px;
            font-size: 18px;
            font-weight: 800;
        }

        .total button{
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .total button:hover {
            cursor: pointer;
        }

        .total span {
            margin-left: 75%;
        }

        form button {
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-left: 10px;
        }

        form button:hover{
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/staff">Home</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/staffProduct">Product</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/user">User</a>
            <a href="#">Order</a>
        </nav>

        <div class="button-group">
            <button onclick="window.location.href= '/signin'">Logout</button>
        </div>
    </header>

    <div class="container">
        <div class="title">
            <h2>myStore</h2>
            <p>Conmmunications is at the heart of e-commerce and community</p>
        </div>

        <h2 class="sub-title">Orders:</h2>
        <div class="order-list">
            <form method="post">
                <select name="order-status" id="" class="select-status">
                    <option value="All" selected>All</option>
                    <option value="Packaging">Packaging</option>
                    <option value="Delivering">Delivery</option>
                    <option value="Received">Received</option>
                </select>

                <button type="submit" name="filter">Search</button>
            </form>
        </div>
    </div>

    <?php
    if(isset($_POST['filter'])){
        displayOrder($_POST['order-status']);
    }
    ?>
</body>
</html>