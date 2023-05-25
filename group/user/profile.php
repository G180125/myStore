<?php 
    session_start();
    $email = $_GET['role'];
    $_SESSION['email'] = $email;

    //display user infor
    $data = array();
    $file = fopen("gs://s3904632group/user.csv", 'r');
    if ($file === false) {
        echo "Failed to open file: gs://s3904632group/user.csv";
        return false;
    }

    while(($row = fgetcsv($file,null,",")) != FALSE){
        array_push($data, $row);
    }

    for($i = 0; $i < count($data); $i++){
        if($data[$i][2] === $email){
            $name = $data[$i][0];
            $phone = $data[$i][1];
            $email = $data[$i][2];
            $password = $data[$i][3];
        }
    }
    fclose($file);

    function displayOrder($email){
        global $email;
        $data = array();
        $file = fopen("gs://s3904632group/order.csv", 'r');
        $skip = 0;
        while(($data = fgetcsv($file,null,",")) != FALSE){
            if($skip == 0){
                $skip = $skip + 1;
            }else{
                if($data[0] === $email && $data[7] != 'Received'){
                    echo '<div class="order">';
                    echo '<span>First Name: ' .$data[1]. '</span>';
                    echo '<span>Last Name: ' .$data[2]. '</span>';
                    echo '<span>Address: ' .$data[3]. '</span>';
                    echo '<span>Post Code: ' .$data[4]. '</span>';
                    echo '<span>City/Province: ' .$data[5]. '</span>';
                    echo '<span>Phone: ' .$data[6]. '</span>';
                    echo '<span>Order Status: ' .$data[7]. '</span>';
                    echo '<table>';
                    displayProductPurchase($skip);
                    echo '</table>';
                    echo '<div class="total">';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="action" value="received">';
                    echo '<input type="hidden" name="index" value="' . $skip . '">';
                    echo '<input type="hidden" name="email" value="' . $email . '">';
                    echo '<button type="submit" name="submit">Received</button>';
                    echo '</form>';
                    echo '<span>Total:' .$data[8].' </span>';
                    echo '</div>';
                    echo '</div>';
                }
                $skip = $skip + 1;
            }
        }
        fclose($file);
    }

    //display product purchase 
    function displayProductPurchase($index) {
        global $email;  
        $data = array();

        $file = fopen("gs://s3904632group/cart.csv", 'r');

        $skip = 0;
        while(($data = fgetcsv($file,null,",")) != FALSE){
            if($skip == 0){
                echo '<tr>';
                for ($i = 0; $i < count($data); $i++){
                    if($i != 0 && $i != 4 && $i != 5 && $i != 6){ // Skip the email, subtotal, process and index column
                        echo '<th>'. $data[$i]. '</th>';
                    }
                }
                echo '</tr>';
                $skip = $skip + 1;
            }else{
                if($data[0] === $email && $data[6] == $index){
                    echo '<tr>';
                    for ($i = 0; $i < count($data); $i++){
                        if($i != 0 && $i != 4 && $i != 5 && $i != 6){ //skip the email, subtotal, process  and index column
                            echo '<td>'. $data[$i]. '</td>';
                        }
                    }
                    echo '</tr>'; 
                }
            }
        }

        fclose($file);
    }

    function received($email, $index) {
        $data = array();
        $file = fopen("gs://s3904632group/order.csv", 'r'); // Open in read and write mode
        
        if ($file === false) {
            echo "Failed to open file: gs://s3904632group/order.csv";
            return false;
        }
        
        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }

        if($data[$index][0] === $email && $data[$index][7] === 'Delivering'){
            $first_name = $data[$index][1];
            $last_name = $data[$iindex][2];
            $address = $data[$index][3];
            $post_code = $data[$index][4];
            $city = $data[$index][5];
            $phone = $data[$index][6];
            $order_status = 'Received';
            $total = $data[$index][8];

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

            $data[$index] = $updateOrder;
        } else {
            echo "<script>alert('Your order is repairing by us. Thank you for your patience.');</script>";
            return;
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

        echo "<script>alert('Thank you for shopping with myStore. We hope to receive a new order form you soon.');</script>";
    }

    if (isset($_POST['action']) && $_POST['action'] == 'received') {
        received($_POST['email'], $_POST['index']);
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

        .account {
            margin: 20px 2.5%;
            padding: 15px;
            width: 40%;
            font-size: 24px;
            display: flex;
            flex-direction: column;
            border: 1px solid;
            border-radius: 10px;
        }

        .account > * {
            margin-bottom: 10px;
        }

        .account button {
            margin-left: 75%;
            width: 20%;
            padding: 10px;
            border-radius: 10px;
        }

        .account button:hover {
            cursor: pointer;
        }

        .order-list {
            margin: 20px 2.5%;
        }

        .order {
            padding: 15px;
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
            <span><?php echo $email?></span>
            <button onclick="window.location.href= '/signin'">Logout</button>
        </div>
    </header>

    <div class="container">
        <div class="title">
            <h2>myStore</h2>
            <p>Conmmunications is at the heart of e-commerce and community</p>
        </div>

        <h2 class="sub-title">Account:</h2>
        <div class="account">
            <span>Name: <?php echo $name?></span> 
            <span>Phone: <?php echo $phone?></span> 
            <span>Email: <?php echo $email?></span>
            <button>Change Password</button>
        </div>

        <h2 class="sub-title">Orders:</h2>
        <div class="order-list">
            <?php displayOrder(); ?>
        </div>
    </div>
</body>
</html>