<?php 
    require_once 'script.php';

    if(isset($_POST['signup'])){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];
        $role = 'User';

        $data = array();
        $file = fopen("gs://s3904632group/user.csv", 'r');
        if ($file === false) {
            echo "Failed to open file: $path";
            return false;
        }

        while(($row = fgetcsv($file,null,",")) != FALSE){
            array_push($data, $row);
        }

        if($name === "" || $email === "" || $password === "") {
            echo "<script>alert('Name, email and password can not be empty.'); window.location.reload();</script>";
            return;
        }

        for($i = 0; $i < count($data); $i++){
            if($data[$i][2] === $email){
                echo "<script>alert('This email is already registered. Please use another one.'); window.location.reload();</script>";
                return;
            }
        }

        if($password != $confirm_password) {
            echo "<script>alert('Passwords do not match.'); window.location.reload();</script>";
            return;
        }

        for($i = 0; $i < count($data); $i++){
            if($email === $data[$i][2]){
                echo "<script>alert('This email is already used.'); window.location.reload();</script>";
            return;
            }
        }

        $newUser = array(
            $name,
            $phone,
            $email,
            $password,
            $role,
        );

        array_push($data, $newUser);

        $file = fopen("gs://s3904632group/user.csv", 'w');
        if(filesize($file == 0)){
            echo "Failed to open file: gs://s3904632group/user.csv";
            return false;
        }
        
        for($i = 0; $i < count($data); $i++){
            fputcsv($file, $data[$i]);
        }
        fclose($file);

        echo "<script>alert('Sign up successfully.');</script>";
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
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 75%;
        }

        form h2 {
            font-size: 38px;
            font-weight: bold;
            margin: 5% 0px;
        }

        .infield {
            position: relative;
            margin: 10px;
        }

        .sign-up-button {
            border-radius: 20px;
            border: 1px solix darkolivegreen;
            background-color: darkolivegreen;
            color: white;
            font-size: 14px;
            font-weight: bold;
            padding: 14px 45px;
            letter-spacing: 1px;
            margin-top: 20px;
        }

        .sign-in-group{
            width: 42%;
            margin: 65px 29%;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>myStore</h1>
    </header>

    <div class="container">
        <form name= "myForm"  method="post" required>
            <h2>Sign up</h2>

            <div class="infield">
                <input type="text" placeholder="Name" name="name" >
            </div>
            <div class="infield">
                <input type="text" placeholder="Phone" name="phone">
            </div>
            <div class="infield">
                <input type="text" placeholder="Email" name="email">
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password">
            </div>
            <div class="infield">
                <input type="password" placeholder="Confirm password" name="confirm-password">
            </div>
            
            <button type="submit" class="sign-up-button" name="signup">Sign up</button>
        </form>

        <div class="sign-in-group">
            <span>You already have an account?</span>
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/signin">Sign In</a>
         </div>
    </div>
</body>
</html>