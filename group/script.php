<?php
$path = "gs://s3904632group/myStore.csv";


function displayFileCSV(){
    global $path;
    $data = array();

    $file = fopen($path, 'r');

    $skip = 0;
    $header = true;
    $index = 2;
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
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </div>
            */ 
            echo '<div class="product"> <h3>'. $data[0]. '</h3> <p>Type: ' .$data[1]. '</p> <p>Color: ' .$data[2]. '</p> <p>Description: ' .$data[3]. '</p> <p>Material: ' .$data[4]. '</p> <p>Instock: ' .$data[6]. '<p>Price: ' .$data[5]. '</p>';
            echo '<button type="button" onclick="window.location.href= \'/update?updaterow='.$index.'\'" class="edit">Edit</button>';
            echo '<button type="button" onclick="window.location.href= \'/delete?deleterow='.$index.'\'" class="delete">Delete</button>';
            $index = $index + 1;
            echo '</div>';
        }
    }
}

function getRowByIndex($index){
    $res = array();

    $data = readFileCSV();

    if(isset($data[$index])){
        $res = $data[$index];
    }
    return $res;
}

function getRowByData($name) {
    $data = readFileCSV();
    for($i = 0; $i < count($data); $i++){
        if($data[$i][0] === $name){
            return $i;
        }
    }
    return -1;
}

function readFileCSV(){
    global $path;
    $data = array();
    $file = fopen($path, 'r');
    if ($file === false) {
        echo "Failed to open file: $path";
        return false;
    }

    while(($row = fgetcsv($file,null,",")) != FALSE){
        array_push($data, $row);
    }
    fclose($file);

    return $data;
}

function writeFileCSV($data){
    global $path;
    $file = fopen($path, 'w');
    if(filesize($file == 0)){
        echo "Failed to open file: $path";
        return false;
    }
    
    for($i = 0; $i < count($data); $i++){
        fputcsv($file, $data[$i]);
    }
    fclose($file);
    return true;
}

?>