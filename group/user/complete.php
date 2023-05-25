<?php
    session_start();
    $email = $_GET['role'];
    $_SESSION['email'] = $email;
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

        .container {
            margin: 10% 30%;
            width: 40%;
            height: 400x;
            background-color: white;
            box-shadow: 25px 30px 55px #5557;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 15px;
        }

        .container > * {
            margin: 20px 0px;
        }

        img {
            width: 250px;
            height: 200px;
        }

        p {
            font-style: italic;
        }

        button {
            width: 180px;
            border: none;
            outline: none;
            padding: 15px;
            border-radius: 25px;
            background-color: darkolivegreen;
            font-size: 20px;
            color: white;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://storage.cloud.google.com/s3904632group/party-popper.png" alt="">
        <p>Thank you for your order. Your order will be soon with you!</p>
        <button onclick="window.location.href= '/home?role=<?php echo urlencode($_SESSION['email']); ?>'">Back to Home</button>
    </div>
</body>
</html>