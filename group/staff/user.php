<?php 
$path = "gs://s3904632group/user.csv";

function displayFileCSV(){
    global $path;
    $data = array();

    $file = fopen($path, 'r');

    $skip = 0;
    while(($data = fgetcsv($file,null,",")) != FALSE){
        if($skip == 0){
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++){
                if($i != 3){ // Skip the 4th column (password)
                    echo '<th>'. $data[$i]. '</th>';
                }
            }
            echo '</tr>';
            $skip = $skip + 1;
        }else{
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++){
                if($i != 3){ // Skip the 4th column (password)
                    echo '<td>'. $data[$i]. '</td>';
                }
            }
            echo '</tr>'; 
        }
    }

    fclose($file);
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

        table {
            margin: 10px 15%;
            width: 70%;
        }

        table, td, th, tr {
            border-collapse: collapse;
            border: 1px solid black;
            padding: 10px;
        }

        table tr:nth-child(odd) {
            background-color: #cfcdcd;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/staff">Home</a>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/staffProduct">Product</a>
            <a href="#">User</a>
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

        <table>
            <?php displayFileCSV(); ?>
        </table>
    </div>

</body>
</html>