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
$startTime = $currentTime->sub(new DateInterval('P30D'))->format('Y-m-d H:i:s');

$idws1 = $_POST['idws1'];
$idws2 = $_POST['idws2'];
$idws3 = $_POST['idws3'];
$idws4 = $_POST['idws4'];

$lastDate = "-";
$arr1 = array();
$arr2 = array();
$arr3 = array();
$arr4 = array();

$queryResult1 = array();
$queryResult2 = array();
$queryResult3 = array();
$queryResult4 = array();

$query1 = mysqli_query($conn, "SELECT id, idws, uv, date, windspeedkmh, rain_rate, hum_out, temp_out FROM weather_station WHERE idws = '" . $idws1 . "' ORDER BY id DESC LIMIT 1");
$queryRr1 = mysqli_query($conn, "SELECT * FROM weather_station WHERE idws = '" . $idws1 . "' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");
if ($query1->num_rows > 0) {
    while ($row = $queryRr1->fetch_assoc()) {
      $queryResult1[] = $row;
    }
    
    $queryGroup = array();
    if (!empty($queryResult1)) {
      foreach ($queryResult1 as $element) {
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
    $keys = array_keys($arrRainRate);
    $lastKey = end($keys);
    $sum_month = (!empty($arrRainRate)) ? $arrRainRate[$lastKey] : 0;
    
    while ($value1 = $query1->fetch_assoc()) {
        $lastDate = tanggal_indo($value1['date']);
        $arr1['id'] = $value1['id'];
        $arr1['idws'] = $value1['idws'];
        $arr1['uv'] = $value1['uv'];
        $arr1['ws'] = intval($value1['windspeedkmh']);
        $arr1['rain_rate'] = intval($value1['rain_rate']) * 12;
        $arr1['hum'] = intval($value1['hum_out']);
        $arr1['temp'] = intval($value1['temp_out']);
        $arr1['rrMonth'] = $sum_month;
    }
} else {
    $arr1['id'] = $idws1;
    $arr1['idws'] = $idws1;
    $arr1['uv'] = 0;
    $arr1['ws'] = 0;
    $arr1['rain_rate'] = 0;
    $arr1['hum'] = 0;
    $arr1['temp'] = 0;
    $arr1['rrMonth'] = 0;
}

$query2 = mysqli_query($conn, "SELECT id, idws, uv, windspeedkmh, rain_rate, hum_out, temp_out FROM weather_station WHERE idws = '" . $idws2 . "' ORDER BY id DESC LIMIT 1");
$queryRr2 = mysqli_query($conn, "SELECT * FROM weather_station WHERE idws = '" . $idws2 . "' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");
if ($query2->num_rows > 0) {
    while ($row = $queryRr2->fetch_assoc()) {
      $queryResult2[] = $row;
    }
    
    $queryGroup = array();
    if (!empty($queryResult2)) {
      foreach ($queryResult2 as $element) {
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
    $keys = array_keys($arrRainRate);
    $lastKey = end($keys);
    $sum_month = (!empty($arrRainRate)) ? $arrRainRate[$lastKey] : 0;
    
    while ($value2 = $query2->fetch_assoc()) {
        $arr2['id'] = $value2['id'];
        $arr2['idws'] = $value2['idws'];
        $arr2['uv'] = $value2['uv'];
        $arr2['ws'] = intval($value2['windspeedkmh']);
        $arr2['rain_rate'] = intval($value2['rain_rate']) * 12;
        $arr2['hum'] = intval($value2['hum_out']);
        $arr2['temp'] = intval($value2['temp_out']);
        $arr2['rrMonth'] = $sum_month;
    }
} else {
    $arr2['id'] = $idws2;
    $arr2['idws'] = $idws2;
    $arr2['uv'] = 0;
    $arr2['ws'] = 0;
    $arr2['rain_rate'] = 0;
    $arr2['hum'] = 0;
    $arr2['temp'] = 0;
    $arr2['rrMonth'] = 0;
}

$query3 = mysqli_query($conn, "SELECT id, idws, uv, windspeedkmh, rain_rate, hum_out, temp_out FROM weather_station WHERE idws = '" . $idws3 . "' ORDER BY id DESC LIMIT 1");
$queryRr3 = mysqli_query($conn, "SELECT * FROM weather_station WHERE idws = '" . $idws3 . "' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");
if ($query3->num_rows > 0) {
    while ($row = $queryRr3->fetch_assoc()) {
      $queryResult3[] = $row;
    }
    
    $queryGroup = array();
    if (!empty($queryResult3)) {
      foreach ($queryResult3 as $element) {
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
    $keys = array_keys($arrRainRate);
    $lastKey = end($keys);
    $sum_month = (!empty($arrRainRate)) ? $arrRainRate[$lastKey] : 0;
    
    while ($value3 = $query3->fetch_assoc()) {
        $arr3['id'] = $value3['id'];
        $arr3['idws'] = $value3['idws'];
        $arr3['uv'] = $value3['uv'];
        $arr3['ws'] = intval($value3['windspeedkmh']);
        $arr3['rain_rate'] = intval($value3['rain_rate']) * 12;
        $arr3['hum'] = intval($value3['hum_out']);
        $arr3['temp'] = intval($value3['temp_out']);
        $arr3['rrMonth'] = $sum_month;
    }
} else {
    $arr3['id'] = $idws3;
    $arr3['idws'] = $idws3;
    $arr3['uv'] = 0;
    $arr3['ws'] = 0;
    $arr3['rain_rate'] = 0;
    $arr3['hum'] = 0;
    $arr3['temp'] = 0;
    $arr3['rrMonth'] = 0;
}

$query4 = mysqli_query($conn, "SELECT id, idws, uv, windspeedkmh, rain_rate, hum_out, temp_out FROM weather_station WHERE idws = '" . $idws4 . "' ORDER BY id DESC LIMIT 1");
$queryRr4 = mysqli_query($conn, "SELECT * FROM weather_station WHERE idws = '" . $idws4 . "' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");
if ($query4->num_rows > 0) {
    while ($row = $queryRr4->fetch_assoc()) {
      $queryResult4[] = $row;
    }
    
    $queryGroup = array();
    if (!empty($queryResult4)) {
      foreach ($queryResult4 as $element) {
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
    $keys = array_keys($arrRainRate);
    $lastKey = end($keys);
    $sum_month = (!empty($arrRainRate)) ? $arrRainRate[$lastKey] : 0;
    
    while ($value4 = $query4->fetch_assoc()) {
        $arr4['id'] = $value4['id'];
        $arr4['idws'] = $value4['idws'];
        $arr4['uv'] = $value4['uv'];
        $arr4['ws'] = intval($value4['windspeedkmh']);
        $arr4['rain_rate'] = intval($value4['rain_rate']) * 12;
        $arr4['hum'] = intval($value4['hum_out']);
        $arr4['temp'] = intval($value4['temp_out']);
        $arr4['rrMonth'] = $sum_month;
    }
} else {
    $arr4['id'] = $idws4;
    $arr4['idws'] = $idws4;
    $arr4['uv'] = 0;
    $arr4['ws'] = 0;
    $arr4['rain_rate'] = 0;
    $arr4['hum'] = 0;
    $arr4['temp'] = 0;
    $arr4['rrMonth'] = 0;
}

$result = array(
    'lastDate' => $lastDate,
    'station1' => $arr1,
    'station2' => $arr2,
    'station3' => $arr3,
    'station4' => $arr4
);

die(json_encode($result));

function tanggal_indo($tanggal)
{

    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split       = explode('-', $tanggal);

    $tgl = explode(' ', $split[2]);

    return  $tgl[0] . ' ' . $bulan[(int)$split[1]] . ', ' . substr($tgl[1], 0, -3);;
}
