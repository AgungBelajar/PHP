<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "u104419741_iot";
// REPLACE with Database user
$username = "u104419741_iot";
// REPLACE with Database user password
$password = "Srsssm5cd@";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$timezone = new DateTimeZone('Asia/Jakarta');
$currentTime = new DateTime('now', $timezone);

$endTime = $currentTime->format('Y-m-d H:i:s');
$startTime = $currentTime->sub(new DateInterval('PT24H'))->format('Y-m-d H:i:s');

$query = mysqli_query($conn, "SELECT SUM(rain_rate) as sum_rr FROM weather_station WHERE idws = '15' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");

if ($query->num_rows > 0) {
    $inc = 0;
    while ($value = $query->fetch_assoc()) {
        $sum_rr = (float) $value['sum_rr'];
    }
}

die(json_encode($sum_rr));