<?php 
session_start();
require_once 'script.php';

if(isset($_GET['deleterow'])){
    $deleterow = $_GET['deleterow'];
    $index = $deleterow - 1;
    
    $row = getRowByIndex($index);
}

if(isset($_POST['delete-btn'])){
    if(isset($_GET['deleterow'])){
        $deleterow = $_GET['deleterow'];
        $index = $deleterow - 1;

        $data = readFileCSV();
        $lastrow = end($data);
        if(isset($data[$index])){
            unset($data[$index]);
        }

        array_push($data, $lastrow);

        if(writeFileCSV($data)){
            fclose($file);
            echo "<script>alert('Successfully delete the product.'); window.location.href = '/staff';</script>";
        } else {
            echo "<script>alert('An error has occur. Please try again.'); window.location.href = '/delete';</script>";
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

        .delete-button {
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
        <h2>Delete product</h2>
        <form name= "myForm" method="post" required>
            <label for="product-name">Name*:</label>
            <input  type="text" name="update-product-name" value="<?php echo $row[0] ?>" required pattern=".{1,}" disabled>

            <div class="product_type">Type:
                <select name="update-product-type" class="product-type" disabled>
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
            <input type="text" name="update-product-color" value="<?php echo $row[2] ?>" required pattern=".{1,}" disabled>

            <label for="product-description">Description:</label>
            <input type="number" name="update-product-description" value="<?php echo $row[3] ?>" disabled>

            <label for="product-material">Material:</label>
            <input  type="text" name="update-product-material" value="<?php echo $row[4] ?>" disabled>

            <label for="product-instock">In Stock*:</label>
            <input  type="number" name="product-in-stock" value="<?php echo $row[6] ?>" disabled>

            <label for="product-price">Price*:</label>
            <input type="number" name="update-product-price" value="<?php echo $row[5] ?>" required pattern=".{1,}" disabled>

            <p>Are you sure to delete this project?</p>
            
            <div class="button-group">
                <button type="button" onclick="window.location.href='/staff'" class="go-back-button">Cancel</button>    
                <button type="submit" name="delete-btn" value="delete-btn" class="delete-button">Yes</button>
            </div>
        </form>
    </div>
</body>
</html>