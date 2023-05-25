<?php 
    session_start();
    require_once 'php/google-api-php-client/vendor/autoload.php';
    require_once 'script.php';

    if(isset($_GET['updaterow'])){
        $updaterow = $_GET['updaterow'];
        $index = $updaterow - 1;
        
        $row = getRowByIndex($index);
        echo $row;
    }
    
    if(isset($_POST['update-btn'])){
        if(isset($_GET['updaterow'])){
            $updaterow = $_GET['updaterow'];
            $index = $updaterow - 1;

            $data = readFileCSV();

            if(isset($data[$index])){
                $product_name = $_POST['update-product-name'];
                $product_type = $_POST['update-product-type'];
                $product_color = $_POST['update-product-color'];
                $product_description = $_POST['update-product-description'];
                $product_material = $_POST['update-product-material'];
                $product_price = $_POST['update-product-price'];
                $produc_in_stock = $_POST['update-product-in-stock'];

                $updateProduct = array(
                    $product_name,
                    $product_type,
                    $product_color,
                    $product_description,
                    $product_material,
                    $product_price,
                    $produc_in_stock
                );

                $data[$index] = $updateProduct;
            }
            
            if(writeFileCSV($data)){
                fclose($file);
                echo "<script>alert('Successfully update the product.'); window.location.href = '/staff';</script>";
            } else {
                echo "<script>alert('An error has occur. Please try again.'); window.location.href = '/update';</script>";
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
            padding: 25px 7.5%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            font: 'Poppins', sans-serif;
        }

        .header h1 {
            font-size: 48px;
            font-style: italic;
        }

        .container {
            margin: 10% 30%;
            width: 40%;
            height: 400px;
            background-color: white;
            box-shadow: 25px 30px 55px #5557;
            border-radius: 15px;
            padding-top: 20px;
        }

        h2 {
            width: 50%;
            margin: 10px 25%;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: left;
            width: 50%;
            margin: 10px 25%;
            height: 100%;
        }

        .button-group {
            display: flex;
            flex-direction: row;
            margin-top: 20px;
        }

        .go-back-button {
            background-color: #808080; 
            color: white;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 5px;
        }

        .update-button {
            margin-left: 40px;
            background-color: #24A0ED; 
            color: white;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>myStore</h1>
    </header>

    <div class="container">
        <h2>Update product</h2>
        <form name= "myForm" method="post" required>
            <label for="product-name">Name*:</label>
            <input  type="text" name="update-product-name" value="<?php echo $row[0] ?>" required pattern=".{1,}">

            <div class="product_type">Type:
                <select name="update-product-type" class="product-type">
                    <option value="Hat" <?php if ($row[1] == "Hat") echo "selected"; ?>>Hat</option>
                    <option value="Tee" <?php if ($row[1] == "Tee") echo "selected"; ?>>Tee</option>
                    <option value="Jean" <?php if ($row[1] == "Jean") echo "selected"; ?>>Jean</option>
                    <option value="Pant" <?php if ($row[1] == "Pant") echo "selected"; ?>>Pant</option>
                    <option value="Shirt" <?php if ($row[1] == "Shirt") echo "selected"; ?>>Shirt</option>
                    <option value="Short" <?php if ($row[1] == "Short") echo "selected"; ?>>Short</option>
                    <option value="Hoodie" <?php if ($row[1] == "Hoodie") echo "selected"; ?>>Hoodie</option>
                    <option value="Jacket" <?php if ($row[1] == "Jacket") echo "selected"; ?>>Jacket</option>
                </select>
            </div>

            <label for="product-color">Color*</label>
            <input type="text" name="update-product-color" value="<?php echo $row[2] ?>" required pattern=".{1,}">

            <label for="product-description">Description:</label>
            <input type="number" name="update-product-description" value="<?php echo $row[3] ?>">

            <label for="product-material">Material:</label>
            <input  type="text" name="update-product-material" value="<?php echo $row[4] ?>">

            <label for="product-instock">In Stock*:</label>
            <input  type="number" name="update-product-in-stock" value="<?php echo $row[6] ?>">

            <label for="product-price">Price*:</label>
            <input type="number" name="update-product-price" value="<?php echo $row[5] ?>" required pattern=".{1,}">

            <div>* = required</div>
            
            <div class="button-group">
                <button type="button" onclick="window.location.href='/staff'" class="go-back-button">Go back</button>
                <button type="submit" name="update-btn" value="update-btn"class="update-button">Update</button>
            </div>
        </form>
    </div>
</body>
</html>