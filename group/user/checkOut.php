<?php 
    session_start();
    $email = $_GET['role'];
    $_SESSION['email'] = $email;

    $product_cost = $_GET['total'];
    if ($product_cost > 1000000){
        $ship = 10000;
    } else {
        $ship = 60000;
    }
    $tax = $product_cost * 0.1;

    $total = $product_cost + $ship + $tax;


    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //reduce the amounts in stock of the purchased product
        purchaseProduct($email);

        //taking inputs and write to order csv file
       updateOrderFile($email);

       //change the processing of the cart
       changeCartProcess($email);
    }

    function purchaseProduct($email) {
        $data = array();
        $file = fopen("gs://s3904632group/cart.csv", 'r');
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/cart.csv";
            return false;
        }

        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }

        for($i = 0; $i < count($data); $i++) {
            if($data[$i][0] === $email){
                reduce($data[$i][1], $data[$i][3]);
            }
        }

    }

    function reduce($product_name, $quantity) {
        $data = array();
        $file = fopen("gs://s3904632group/myStore.csv", 'r');
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/myStore.csv";
            return false;
        }

        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }

        for($i = 0; $i < count($data); $i++){
            if($data[$i][0] === $product_name){
                $data[$i][6] = $data[$i][6] - $quantity;
            }
        }

        $file = fopen("gs://s3904632group/myStore.csv", 'w');
        if(filesize($file == 0)){
            echo "Failed to open file: gs://s3904632group/myStore.csv";
            return false;
        }
        
        for($i = 0; $i < count($data); $i++){
            fputcsv($file, $data[$i]);
        }
        fclose($file);
    }

    function updateOrderFile($email) {
        global $total;

        $data = array();
        $file = fopen("gs://s3904632group/order.csv", 'r');
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/order.csv";
            return false;
        }

        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }

        $first_name = $_POST['first-name'];
        $last_name = $_POST['last-name'];
        $address = $_POST['address'];
        $post_code = $_POST['post-code'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];
        $order_status = 'Packaging';

        $newOrder = array(
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

        array_push($data, $newOrder);

        $file = fopen("gs://s3904632group/order.csv", 'w');
        if(filesize($file == 0)){
            echo "Failed to open file: gs://s3904632group/order.csv";
            return false;
        }
        
        for($i = 0; $i < count($data); $i++){
            fputcsv($file, $data[$i]);
        }
        fclose($file);
    }

    function countRow() {
        $count = 0;

        $data = array();
        $file = fopen("gs://s3904632group/order.csv", 'r');
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/order.csv";
            return false;
        }

        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
            $count = $count + 1;
        }

        return $count;
    }

    function changeCartProcess($email) {
        $data = array();
        $file = fopen("gs://s3904632group/cart.csv", 'r'); 
            
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/cart.csv";
            return false;
        }
            
        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }
    
        for($i = 0; $i < count($data); $i++) {
            if($data[$i][0] === $email && $data[$i][5] != 'Done') {
                $product_name = $data[$i][1];
                $price = $data[$i][2];
                $quantity = $data[$i][3];
                $subtotal = $data[$i][4];
                $process = 'Done';
                $order_index = countRow() - 1;
    
                $updateCart = array(
                    $email,
                    $product_name,
                    $price,
                    $quantity,
                    $subtotal,
                    $process,
                    $order_index
                );
    
                $data[$i] = $updateCart;
            }
        }
        
        $file = fopen("gs://s3904632group/cart.csv", 'w'); // Open in write mode
        if($file === false){
            echo "Failed to open file: gs://s3904632group/cart.csv";
            return false;
        }
                
        for($i = 0; $i < count($data); $i++){
            fputcsv($file, $data[$i]);
        }
        fclose($file);

        header("location: https://vocal-catalyst-384303.de.r.appspot.com/complete?role=" . urlencode($_SESSION['email']));
        exit();
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

        .button-group span {
            color: white;
            font-size: 24px;
            font-weight: 700;
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

        body{
            background-color: lightgrey;
        }

        .container {
            margin-top: 8%;
            display: flex;
            flex-direction: row;
        }

        .left-panel {
            border: none;
            outline: none;
            width: 75%;
            height: 700px;
            padding: 30px;
            background-color: white;
        }

       .left-panel h4 {
            margin: 20px 0px;
        }

        form > * {
            margin-bottom: 20px;
        }

        .address,
        .phone {
            padding: 10px;
            width: 60.5%;
        }

        .first-name,
        .last-name,
        .post-code,
        .city {
            padding: 10px;
            width: 30%;
        }

        .confirm-button {
            width: 40.5%;
            margin-left: 20%;
            padding: 10px;
            background-color: darkolivegreen;
            color: white;
            letter-spacing: 1px;
        }

        .right-panel {
            margin: 2.5%;
            border: none;
            outline: none;
            width: 20%;
            height: 350px;
            padding: 15px;
            background-color: white;
        }

        .edit-group {
            margin-bottom: 20px;
        }

        .order-summary {
            font-size: 24px;
            font-weight: bold;
        }

        .right-panel a{
            margin-left: 20px;
        }

        .right-panel .cost{
            justify-content: right;
        }

        .title {
            width: 80%;
            justify-content: center;
            text-align: center;
        }

        .title h2 {
            font-size: 56px;
            font-style: italic;
        }

        .right-panel .cost {
            display: block;
            text-align: right;
        }

        hr {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/home?role=<?php echo urlencode($_SESSION['email']); ?>">Home</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/profile?role=<?php echo urlencode($_SESSION['email']); ?>">Profile</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/product?role=<?php echo urlencode($_SESSION['email']); ?>">Product</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/viewCart?role=<?php echo urlencode($_SESSION['email']); ?>">View Cart</a>
        </nav>
        </nav>

        <div class="button-group">
            <span><?php echo $email ?></span>
            <button onclick="window.location.href= '/signin'">Logout</button>
        </div>
    </header>
	
    <div class="container">
        <div class="left-panel">
            <div class="title">
                <h2>myStore</h2>
                <p>Conmmunications is at the heart of e-commerce and community</p>
            </div>

            <h4>Ship To</h4>

            <form class="my-form" method="post" required>
                <div class="name-field">
                    <input type="text" class="first-name" name="first-name" placeholder="First Name" required pattern=".{1,}">
                    <input type="text" class="last-name" name="last-name" placeholder="Last Name" required pattern=".{1,}">
                </div>
                <input type="text" class="address" name="address" placeholder="Address" required pattern=".{1,}">
                <div>
                    <input type="text" class="post-code" name="post-code" placeholder="Postal Code" required pattern=".{1,}">
                    <input type="text" class="city" name="city" placeholder="Province/City" required pattern=".{1,}">
                </div>
                <input type="text" class="phone" name="phone" placeholder="Phone" required pattern=".{1,}"> <br>

                <button type="submit" class="confirm-button">CONFIRM</button>
            </form>
        </div>

        <div class="right-panel">
            <div class="edit-group">
                <span class="order-summary">Order Summary</span>
                <a href="https://vocal-catalyst-384303.de.r.appspot.com/viewCart?role=<?php echo urlencode($_SESSION['email']); ?>">Edit Cart</a>
             </div>

            <span>Merchandise:</span>
            <span class="cost"><?php echo $product_cost ?></span> <br>
            <span>Shipping:</span>
            <span class="cost"><?php echo $ship ?></span> <br>
            <span>Tax:</span>
            <span class="cost"><?php echo $tax ?></span> <br>
            <hr>
            <span>Total:</span>
            <span class="cost"><?php echo $total ?></span> <br>
        </div>
    </div>
</body>
</html>
