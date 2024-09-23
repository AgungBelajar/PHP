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

$idws1 = $_GET['idws1'];
$idws2 = $_GET['idws2'];
$idws3 = $_GET['idws3'];
$idws4 = $_GET['idws4'];

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
    
    $cumulativeSum = 0;
    foreach ($arrRainRate as $key => $value) {
        $cumulativeSum += $value;
        $arrRrNow[$key] = round($cumulativeSum, 0);
    }
    $sum_month = end($arrRrNow);
    
    while ($value1 = $query1->fetch_assoc()) {
        $arr1['id1'] = $value1['id'];
        $arr1['idws1'] = $value1['idws'];
        $arr1['uv1'] = $value1['uv'];
        $arr1['date'] = tanggal_indo($value1['date']);
        $arr1['ws1'] = intval($value1['windspeedkmh']);
        $arr1['rain_rate1'] = intval($value1['rain_rate']) * 12;
        $arr1['hum1'] = intval($value1['hum_out']);
        $arr1['temp1'] = intval($value1['temp_out']);
        $arr1['rrMonth1'] = $sum_month;
    }
} else {
    $arr1['id1'] = $idws1;
    $arr1['idws1'] = $idws1;
    $arr1['uv1'] = 0;
    $arr1['date'] = "-";
    $arr1['ws1'] = 0;
    $arr1['rain_rate1'] = 0;
    $arr1['hum1'] = 0;
    $arr1['temp1'] = 0;
    $arr1['rrMonth1'] = 0;
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
    
    $cumulativeSum = 0;
    foreach ($arrRainRate as $key => $value) {
        $cumulativeSum += $value;
        $arrRrNow[$key] = round($cumulativeSum, 0);
    }
    $sum_month = end($arrRrNow);
    
    while ($value2 = $query2->fetch_assoc()) {
        $arr2['id2'] = $value2['id'];
        $arr2['idws2'] = $value2['idws'];
        $arr2['uv2'] = $value2['uv'];
        $arr2['ws2'] = intval($value2['windspeedkmh']);
        $arr2['rain_rate2'] = intval($value2['rain_rate']) * 12;
        $arr2['hum2'] = intval($value2['hum_out']);
        $arr2['temp2'] = intval($value2['temp_out']);
        $arr2['rrMonth2'] = $sum_month;
    }
} else {
    $arr2['id2'] = $idws2;
    $arr2['idws2'] = $idws2;
    $arr2['uv2'] = 0;
    $arr2['ws2'] = 0;
    $arr2['rain_rate2'] = 0;
    $arr2['hum2'] = 0;
    $arr2['temp2'] = 0;
    $arr2['rrMonth2'] = 0;
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
    
    $cumulativeSum = 0;
    foreach ($arrRainRate as $key => $value) {
        $cumulativeSum += $value;
        $arrRrNow[$key] = round($cumulativeSum, 0);
    }
    $sum_month = end($arrRrNow);
    
    while ($value3 = $query3->fetch_assoc()) {
        $arr3['id3'] = $value3['id'];
        $arr3['idws3'] = $value3['idws'];
        $arr3['uv3'] = $value3['uv'];
        $arr3['ws3'] = intval($value3['windspeedkmh']);
        $arr3['rain_rate3'] = intval($value3['rain_rate']) * 12;
        $arr3['hum3'] = intval($value3['hum_out']);
        $arr3['temp3'] = intval($value3['temp_out']);
        $arr3['rrMonth3'] = $sum_month;
    }
} else {
    $arr3['id3'] = $idws3;
    $arr3['idws3'] = $idws3;
    $arr3['uv3'] = 0;
    $arr3['ws3'] = 0;
    $arr3['rain_rate3'] = 0;
    $arr3['hum3'] = 0;
    $arr3['temp3'] = 0;
    $arr3['rrMonth3'] = 0;
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
    
    $cumulativeSum = 0;
    foreach ($arrRainRate as $key => $value) {
        $cumulativeSum += $value;
        $arrRrNow[$key] = round($cumulativeSum, 0);
    }
    $sum_month = end($arrRrNow);
    
    while ($value4 = $query4->fetch_assoc()) {
        $arr4['id4'] = $value4['id'];
        $arr4['idws4'] = $value4['idws'];
        $arr4['uv4'] = $value4['uv'];
        $arr4['ws4'] = intval($value4['windspeedkmh']);
        $arr4['rain_rate4'] = intval($value4['rain_rate']) * 12;
        $arr4['hum4'] = intval($value4['hum_out']);
        $arr4['temp4'] = intval($value4['temp_out']);
        $arr4['rrMonth4'] = $sum_month;
    }
} else {
    $arr4['id4'] = $idws4;
    $arr4['idws4'] = $idws4;
    $arr4['uv4'] = 0;
    $arr4['ws4'] = 0;
    $arr4['rain_rate4'] = 0;
    $arr4['hum4'] = 0;
    $arr4['temp4'] = 0;
    $arr4['rrMonth4'] = 0;
}

$result = array(
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
