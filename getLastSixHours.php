<?php

$servername = "localhost";
$dbname = "u104419741_iot";
// REPLACE with Database user
$username = "u104419741_iot";
// REPLACE with Database user password
$password = "Srsssm5cd@";

class Response
{
    public $success;
    public $message;
    public $lastSixHours;
    public $lastData;
}

// Function to establish a database connection
function connectToDatabase($server, $db, $user, $pass) {
    $conn = mysqli_connect($server, $user, $pass, $db);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

$conn = connectToDatabase($servername, $dbname, $username, $password);

$idPost = $_POST['idws'];

function filterAndLastData($connection, $idws) {
    $dataQr = mysqli_query($connection, "SELECT * FROM weather_station WHERE idws = '$idws'");
    while ($row = $dataQr->fetch_assoc()) {
        $arrDataWm[] = $row;
    }
    
    $lastData = end($arrDataWm);
    $lastDate = new DateTime($lastData['date']);
    
    $sixHoursAgo = $lastDate->sub(new DateInterval('PT5H'));
    $sixHoursAgoFormatted = $sixHoursAgo->format('Y-m-d H:i:s');
    
    $filteredDataQr = mysqli_query($connection, "SELECT * FROM weather_station WHERE date >= '$sixHoursAgoFormatted' AND idws = '$idws'");
    while ($filteredRow = $filteredDataQr->fetch_assoc()) {
        $filteredData[] = $filteredRow;
    }
    
    $dataLastWs = mysqli_query($connection, "SELECT * FROM weather_station WHERE idws = '$idws' ORDER BY id DESC LIMIT 1");
    $sendLastData = [];
    while ($row = $dataLastWs->fetch_assoc()) {
        $sendLastData['temp'] = $row['temp_out'];
        $sendLastData['rainrate'] = $row['rain_rate'];
        $sendLastData['hum'] = $row['hum_out'];
        $sendLastData['ws'] = $row['windspeedkmh'];
        $sendLastData['date'] = $row['date'];
    }
    
    return array($filteredData, $sendLastData);
}

function rawAverageHours($listData) {
    $hourlyAverages = [];
    foreach ($listData as $entry) {
        $hour = date('H', strtotime($entry['date']));
        if (!isset($hourlyAverages[$hour]['temp'])) {
            $hourlyAverages[$hour]['temp'] = $entry['temp_out'];
            $hourlyAverages[$hour]['temp_count'] = 1;
        } else {
            $hourlyAverages[$hour]['temp'] += $entry['temp_out'];
            $hourlyAverages[$hour]['temp_count']++;
        }
    
        if (!isset($hourlyAverages[$hour]['rainrate'])) {
            $hourlyAverages[$hour]['rainrate'] = $entry['rain_rate'];
            $hourlyAverages[$hour]['rainrate_count'] = 1;
        } else {
            $hourlyAverages[$hour]['rainrate'] += $entry['rain_rate'];
            $hourlyAverages[$hour]['rainrate_count']++;
        }
    
        if (!isset($hourlyAverages[$hour]['hum'])) {
            $hourlyAverages[$hour]['hum'] = $entry['hum_out'];
            $hourlyAverages[$hour]['hum_count'] = 1;
        } else {
            $hourlyAverages[$hour]['hum'] += $entry['hum_out'];
            $hourlyAverages[$hour]['hum_count']++;
        }
    
        if (!isset($hourlyAverages[$hour]['ws'])) {
            $hourlyAverages[$hour]['ws'] = $entry['windspeedkmh'];
            $hourlyAverages[$hour]['ws_count'] = 1;
        } else {
            $hourlyAverages[$hour]['ws'] += $entry['windspeedkmh'];
            $hourlyAverages[$hour]['ws_count']++;
        }
    }
    
    foreach ($hourlyAverages as &$hourlyAverage) {
        $hourlyAverage['temp'] /= $hourlyAverage['temp_count'];
        $hourlyAverage['rainrate'] /= $hourlyAverage['rainrate_count'];
        $hourlyAverage['hum'] /= $hourlyAverage['hum_count'];
        $hourlyAverage['ws'] /= $hourlyAverage['ws_count'];
    }
    
    return $hourlyAverages;
}

function finalAverageHours($listData) {
    $fixCount = 6;
    $fixHourAve = [];
    
    if (count($listData) < $fixCount) {
        $result = $fixCount - count($listData);
    
        $keys = array_keys($listData);
        $firstKey = reset($keys);
        $dateAdd = DateTime::createFromFormat('H', $firstKey);
        $hoursBefore = [];
        for ($i = 0; $i < $result; $i++) {
            $dateAdd->sub(new DateInterval('PT1H'));
            $hoursBefore[] = $dateAdd->format('H');
        }
        $hoursBefore = array_reverse($hoursBefore);
        
        foreach ($hoursBefore as $time) {
            $fixHourAve[$time . ':00']['temp'] = "0";
            $fixHourAve[$time . ':00']['rainrate'] = "0";
            $fixHourAve[$time . ':00']['hum'] = "0";
            $fixHourAve[$time . ':00']['ws'] = "0";
        }
    }
    
    foreach ($listData as $key => $value) {
        $fixHourAve[$key . ':00']['temp'] = round($value['temp'], 2);
        $fixHourAve[$key . ':00']['rainrate'] = round($value['rainrate'] * 12, 2);
        $fixHourAve[$key . ':00']['hum'] = round($value['hum'], 2);
        $fixHourAve[$key . ':00']['ws'] = round($value['ws'], 2);
    }
    
    return $fixHourAve;
}

$arrFilterData = filterAndLastData($conn, $idPost);
$filteredData = $arrFilterData[0];
$lastData = $arrFilterData[1];
$averageHours = rawAverageHours($filteredData);
$finalAveHours = finalAverageHours($averageHours);

$response = new Response();
if (!empty($finalAveHours)) {
    $response->success = 1;
    $response->message = 'DATA BERHASIL DIPERBARUI';
    $response->lastSixHours = $finalAveHours;
    if (!empty($lastData)) {
        $response->lastData = $lastData;
    }
} else {
    $response->success = 0;
    $response->message = 'DATA TIDAK TERSEDIA';
}

die(json_encode($response));
