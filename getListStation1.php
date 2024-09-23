<?php

$servername = "localhost";
$dbname = "u104419741_iot";
// REPLACE with Database user
$username = "u104419741_iot";
// REPLACE with Database user password
$password = "Srsssm5cd@";

// Function to establish a database connection
function connectToDatabase($server, $db, $user, $pass) {
    $conn = mysqli_connect($server, $user, $pass, $db);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

$conn = connectToDatabase($servername, $dbname, $username, $password);

$md5App = $_POST['hexApp'] ?? "";

function getListStation($connection) {
    $query = mysqli_query($connection, "SELECT DISTINCT weather_station_list.id, 
    weather_station_list.loc FROM weather_station_list INNER JOIN weather_station 
    ON weather_station_list.id = weather_station.idws 
    ORDER BY weather_station.date DESC");
    
    $arrData = [];
    while ($value = $query->fetch_assoc()) {
        $arrData[] = $value;
    }
    
    return $arrData;
}
$resultDataWs = getListStation($conn);

// Encode data to JSON
$jsonData = json_encode($resultDataWs, JSON_PRETTY_PRINT);

// Calculate MD5 checksum
$byteArray = unpack('C*', $jsonData);
$md5Checksum = md5(implode('', array_map('chr', $byteArray)));

// Response object
class Response
{
    public $success;
    public $message;
    public $md5;
    public $listData;
}

$response = new Response();
if ($md5App == $md5Checksum) {
    $response->success = 1;
    $response->message = 'DATA SUDAH TERBARU';
} else {
    $response->success = 2;
    $response->message = 'DATA BERHASIL DIPERBARUI';
    $response->md5 = $md5Checksum;
    $response->listData = $resultDataWs;
}

die(json_encode($response));
