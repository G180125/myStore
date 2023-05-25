<?php 
require_once 'script.php';
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
            padding: 25px 0px;
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

        .add {
            display: inline-block; 
            font-size: 16px; 
            background-color: darkolivegreen; 
            color: white; 
            text-decoration: none; 
            justify-content: center;
            margin-left: 1.5%;
            padding: 16px 34px;
            border: none;
            outline: none;
            border-radius: 10px;
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

        .edit{
            width: 60px;
            padding: 10px;
            border: none;
            outline: none;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 15px;
            background-color: darkolivegreen;
            color: white;
        }

        .delete{
            width: 60px;
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 15px;
            background-color: orangered;
            color: white;
            margin-left: 15px;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="#">Home</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/staffProduct">Product</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/user">User</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/order">Order</a>
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

        <div>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/create" class="add">Add</a>
        </div>

        <div class="product-list">
            <?php displayFileCSV(); ?>
        </div>
    </div>

</body>
</html>