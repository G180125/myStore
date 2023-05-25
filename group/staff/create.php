<?php 
    require_once 'php/google-api-php-client/vendor/autoload.php';
    require_once 'script.php';

    /*
    use Google\Cloud\BigQuery\BigQueryClient;
    use Google\Cloud\Core\ExponentialBackoff;
    */

    if(isset($_POST['create-btn'])){
        $product_name = $_POST['product-name'];
        $product_type = $_POST['product-type'];
        $product_color = $_POST['product-color'];
        $product_description = $_POST['product-description'];
        $product_material = $_POST['product-material'];
        $product_price = $_POST['product-price'];
        $product_in_stock = $_POST['product-in-stock'];

        $newProduct = array(
            $product_name,
            $product_type,
            $product_color,
            $product_description,
            $product_material,
            $product_price,
            $product_in_stock
        );

        $data = readFileCSV();
        array_push($data, $newProduct);
        
        if(writeFileCSV($data)){
            fclose($file);
            echo "<script>alert('Successfully create a new product.'); window.location.href = '/staff';</script>";
        } else {
            echo "<script>alert('An error has occur. Please try again.'); window.location.href = '/create';</script>";
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
            height: 420px;
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

        .create-button {
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
        <h2>Add new product</h2>
        <form name= "myForm" action="/create" method="post" required>
            <label for="product-name">Name*:</label>
            <input  type="text" name="product-name"required pattern=".{1,}">

            <div class="product_type">Type:
                <select name="product-type" class="product-type">
                    <option value="Hat">Hat</option>
                    <option value="Tee">Tee</option>
                    <option value="Jean">Jean</option>
                    <option value="Pant">Pant</option>
                    <option value="Shirt">Shirt</option>
                    <option value="Short">Short</option>
                    <option value="Hoodie">Hoodie</option>
                    <option value="Jacket">Jacket</option>
                </select>  
            </div>

            <label for="product-color">Color*</label>
            <input type="text" name="product-color" required pattern=".{1,}">

            <label for="product-description">Description:</label>
            <input type="number" name="product-description">

            <label for="product-material">Material:</label>
            <input  type="text" name="product-material">

            <label for="product-instock">In Stock*:</label>
            <input  type="number" name="product-in-stock"required pattern=".{1,}">

            <label for="product-price">Price*:</label>
            <input type="number" name="product-price" required pattern=".{1,}">

            <div>* = required</div>
            
            <div class="button-group">
                <button type="button" onclick="window.location.href='/staff'" class="go-back-button">Go back</button>
                <button type="submit" name="create-btn" value="create-btn"class="create-button">Create</button>
            </div>
        </form>
    </div>
</body>
</html>