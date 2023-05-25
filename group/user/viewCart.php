<?php
    session_start();
    $email = $_GET['role'];
    $_SESSION['email'] = $email;

    function displayFileCSV(){
        global $email;
        $total = 0;
        $data = array();

        $file = fopen("gs://s3904632group/cart.csv", 'r');

        $skip = 0;
        while(($data = fgetcsv($file,null,",")) != FALSE){
            if($skip == 0){
                echo '<tr>';
                for ($i = 1; $i < count($data); $i++){
                    if($i != 5 && $i != 6){
                        echo '<th>'. $data[$i]. '</th>';
                    }
                }
                echo '</tr>';
                $skip = $skip + 1;
            }else{
                if($data[0] === $email && $data[5] === 'Processing'){
                    $total = $total + $data[4];
                    echo '<tr>';
                    for ($i = 1; $i < count($data); $i++){
                        $max = getInStock($data[1]);
                        if($i != 5 && $i != 6){ //skip process and index row
                            if($i == 3){ // Change the fourth column to an input type number
                                echo '<td><input type="number" name="quantity" min="1" max="'. $max. '" value="'. $data[$i]. '"></td>';
                            } else {
                                echo '<td>'. $data[$i]. '</td>';
                            }
                        }
                    }
                    echo '</tr>';
                }
            }
        }

        fclose($file);
        $_SESSION['total'] = $total;
    }

    function getInStock($product_name) {
        $data = array();
        $file = fopen("gs://s3904632group/myStore.csv", 'r');
        if ($file === false) {
            echo "Failed to open file: $path";
            return false;
        }

        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }

        for($i = 0; $i < count($data); $i++) {
            if($data[$i][0] === $product_name){
                return $data[$i][6];
            }
        }
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

        span {
            color: white;
            font-size: 24px;
            font-weight: 700;
        } 

        .header button {
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-left: 10px;
        }

        .header button:hover{
            cursor: pointer;
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

        table {
            margin: 10px 15%;
            width: 70%;
        }

        table tr:nth-child(odd) {
            background-color: #cfcdcd;
        }

        table, th, tr, td {
            border-collapse: collapse;
            border: 1px solid black;
            padding: 10px;
        }


        th:nth-child(1) {
            width: 50%;
        }

        th:nth-child(2) {
            width: 10%;
        }

        th:nth-child(3) {
            width: 10%;
        }

        table input {
            width: 40px;
        }

        th:nth-child(4) {
            width: 10%;
        }

        .cart-group h3 {
            margin-top: 30px;
            margin-bottom: 10px;
            margin-left: 65%;
        }

        .total-group {
            padding: 20px;
            width: 20%;
            margin-left: 65%;
            border: 1px solid #cfcdcd;
            background-color: #cfcdcd;
            display: flex;
            justify-content: space-between ;
            align-items: center;
        }

        .total-group span {
            font-size: 18px;
            font-weight: bold;
        }

        .check-out-button {
            margin-top: 10px;
            margin-left: 70%;
            width: 10%;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 20px;
            background-color: darkolivegreen;
            color: white;
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

        <div class="button-group">
            <span><?php echo $email ?></span>
            <button onclick="window.location.href= '/signin'">Logout</button>
        </div>
    </header>
	
    <div class="container">
	    <div class="title">
            <h2>myStore</h2>
            <p>Conmmunications is at the heart of e-commerce and community</p>
        </div>

        <table>
            <?php displayFileCSV(); ?>
        </table>

        <div class="cart-group">
            <h3>Cart Totals</h3>
            <div class="total-group">
                <span>Total</span>
                <span><?php echo $_SESSION['total']?></span>
            </div>
        </div>

        <button onclick="<?php if($_SESSION['total']>0){ ?>window.location.href= '/checkOut?role=<?php echo urlencode($_SESSION['email']); ?>&total=<?php echo $_SESSION['total'];?>'<?php } else { ?>alert('Your cart is empty!');<?php } ?>" class="check-out-button">Check Out</button>
    </div>
</body>
</html>
