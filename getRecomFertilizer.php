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

$idws = $_GET['idws'];

$timezone = new DateTimeZone('Asia/Jakarta');
$currentTime = new DateTime('now', $timezone);

$endTime = $currentTime->format('Y-m-d H:i:s');
$startTime = $currentTime->sub(new DateInterval('P30D'))->format('Y-m-d H:i:s');

$query = mysqli_query($conn, "SELECT * FROM weather_station WHERE idws = '" . $idws . "' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");

while ($row = $query->fetch_assoc()) {
  $queryResult[] = $row;
}

$queryGroup = array();
if (!empty($queryResult)) {
  foreach ($queryResult as $element) {
    $date = date_create($element['date']);
    $formattedDate = date_format($date, "d M");
    $queryGroup[$formattedDate][] = $element;
  }
}

$arrRainRate = array();
foreach ($queryGroup as $key => $value) {
    $sum_rain_rate = 0;
    foreach ($value as $key1 => $value1) {
        $sum_rain_rate += $value1['rain_rate'];
    }
    $arrRainRate[$key] = round($sum_rain_rate, 0);
}

$cumulativeSum = 0;
foreach ($arrRainRate as $key => $value) {
    $cumulativeSum += $value;
    $arrRrNow[$key] = round($cumulativeSum, 0);
}

$sum_month = 0;
foreach ($arrRrNow as $key => $value) {
    $sum_month += round($value, 0);
}

die(json_encode($sum_month));