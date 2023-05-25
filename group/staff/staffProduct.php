<?php 
	session_start();
	require_once 'php/google-api-php-client/vendor/autoload.php';
    require_once 'script.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $type= $_POST['product-type'];

        $search = '%';
		$search .= $_POST['search-input'];
		$search_value = $_POST['search-input'];
		$search .= '%';
    } else {
        $type = 'All';
        $search = '%%';
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

        form {
            margin-left: 50px;
            width: 90%;
            padding: 25px 0px;
            display: flex;
            justify-content: left;
            align-items: center;
            z-index: 100;
            font: 'Poppins', sans-serif;
        }


        select {
            padding: 10px;
            border-radius: 8px;
        }

        .search-group input{
            margin-left: 50px;
            width: 200px;
            padding: 10px;
            border: 1px solid black;
            border-radius: 8px;
        }

        button {
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        button:hover{
            cursor: pointer;
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
            <a href="https://vocal-catalyst-384303.de.r.appspot.com/staff">Home</a>
            <a href="#">Product</a>
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

        <form name="filter" method="POST">
            <select name="product-type" class="product-type">
            <option value="All" <?php if ($type == "All") echo "selected"; ?>>All</option>
                <option value="Hat" <?php if ($type == "Hat") echo "selected"; ?>>Hat</option>
                <option value="Tee" <?php if ($type == "Tee") echo "selected"; ?>>Tee</option>
                <option value="Jean" <?php if ($type == "Jean") echo "selected"; ?>>Jean</option>
                <option value="Pant" <?php if ($type == "Pant") echo "selected"; ?>>Pant</option>
                <option value="Shirt" <?php if ($type == "Shirt") echo "selected"; ?>>Shirt</option>
                <option value="Short" <?php if ($type == "Short") echo "selected"; ?>>Short</option>
                <option value="Hoodie" <?php if ($type == "Hoodie") echo "selected"; ?>>Hoodie</option>
                <option value="Jacket" <?php if ($type == "Jacket") echo "selected"; ?>>Jacket</option>
            </select>  

            <div class="search-group">
                <input type="text" name="search-input" class="search-input" placeholder="Search..." value="<?php echo $search_value ?>">
            </div>

            <button type="submit">Search</button>
        </form>

        <div class="product-list">        
            <?php
                $client = new Google_Client();
                $client->useApplicationDefaultCredentials();
                $client->addScope(Google_Service_Bigquery::BIGQUERY);
                $bigquery = new Google_Service_Bigquery($client);
                $projectId = 'vocal-catalyst-384303';

                $request = new Google_Service_Bigquery_QueryRequest();
                
                if($search == '%%'){
                    if($type === 'All'){
                        $request->setQuery("SELECT * FROM [data.product]");
                    } else {
                        $request->setQuery("SELECT * FROM [data.product] WHERE Type = '$type'");
                    }
                } else {
                    if($type === 'All'){
                        $request->setQuery("SELECT * FROM [data.product] WHERE Name LIKE '$search'");
                    } else {
                        $request->setQuery("SELECT * FROM [data.product] WHERE Name LIKE '$search' AND Type = '$type'");
                    }
                }
                
                $response = $bigquery->jobs->query($projectId, $request);
                $rows = $response->getRows();

                foreach ($rows as $index => $row) {
                    $data = $row->getF();
                    $index = getRowByData($data[0]->getV());
                    $index = $index + 1;

                    echo '<div class="product">
                            <h3>'. $data[0]->getV() .'</h3>
                            <p>Type: ' .$data[1]->getV(). '</p>
                            <p>Color: ' .$data[2]->getV(). '</p>
                            <p>Description: ' .$data[3]->getV(). '</p>
                            <p>Material: ' .$data[4]->getV(). '</p>
                            <p>Price: ' .$data[5]->getV(). '</p>
                            <button type="button" onclick="window.location.href= \'/update?updaterow='.$index.'\'" class="edit">Edit</button>
                            <button type="button" onclick="window.location.href= \'/delete?deleterow='.$index.'\'" class="delete">Delete</button>
                        </div>';
                }
            ?>
	    </div>
    </div>
</body>
</html>
