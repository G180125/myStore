<?php 
    session_start();

    $path = "gs://s3904632group/myStore.csv";

    $email = $_GET['role'];
    $_SESSION['email'] = $email;

    function toCart($email, $product_name, $product_price){
        $newCart = array(
            $email,
            $product_name,
            $product_price,
            1,
            $product_price,
            'Processing'
        );

        $cart_data = array();
        $cart_file = fopen("gs://s3904632group/cart.csv", 'r');
        if ($cart_file === false) {
            echo "Failed to open file: gs://s3904632group/cart.csv";
            return false;
        }

        while(($row = fgetcsv($cart_file,null,",")) != FALSE){
            array_push($cart_data, $row);
        }

        array_push($cart_data, $newCart);

        $cart_file = fopen("gs://s3904632group/cart.csv", 'w');
        if(filesize($cart_file == 0)){
            echo "Failed to open file: gs://s3904632group/cart.csv";
            return false;
        }
        
        for($i = 0; $i < count($cart_data); $i++){
            fputcsv($cart_file, $cart_data[$i]);
        }
        fclose($cart_file);

        echo "<script>alert('Successfully add the product to cart.'); </script>";
    }

    function displayUserFileCSV(){
        global $path;
        global $email;
        $data = array();

        $file = fopen($path, 'r');

        $skip = 0;
        while(($data = fgetcsv($file,null,",")) != FALSE){
            if($skip == 0){
                //skip first row
                $skip = $skip + 1;
            }else{
                /*
                <div class="product">
                    <h3>Product Name</h3> 
                    <p>Type</p> 
                    <p>Color</p> 
                    <p>Description</p>
                    <p>Material</p>  
                    <p>Price:</p>
                </div>
                */ 
                if($data[6] > 0){
                    echo '<div class="product"> <h3>'. $data[0]. '</h3> <p>Type: ' .$data[1]. '</p> <p>Color: ' .$data[2]. '</p> <p>Description: ' .$data[3]. '</p> <p>Material: ' .$data[4]. '</p> <p>Price: ' .$data[5]. '</p>';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="action" value="toCart">';
                    echo '<input type="hidden" name="email" value="' . $email . '">';
                    echo '<input type="hidden" name="productName" value="' . $data[0] . '">';
                    echo '<input type="hidden" name="price" value="' . $data[5] . '">';
                    echo '<button type="submit" name="submit" class="purchase">AddToCart</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'toCart') {
        toCart($_POST['email'], $_POST['productName'], $_POST['price']);
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

        .product {
            display: inline-block;
            float: left;
            width: 30%;
            box-sizing: border-box;
            padding: 15px;
            margin: 10px 1.5%;
            border: 1px solid gray;
            border-radius: 10px;        
        }

        .product p {
            margin-top: 20px;
        }

        .purchase{
            width: 100px;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 15px;
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

        <div class="product-list">
            <?php displayUserFileCSV(); ?>
        </div>
    </div>
</body>
</html>