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

$idws = $_POST['idws'];

$query = mysqli_query($conn, "SELECT id, idws, date, windspeedkmh, winddir, rain_rate, hum_out, temp_out FROM weather_station WHERE idws = '" . $idws . "' ORDER BY id DESC LIMIT 1");

$timezone = new DateTimeZone('Asia/Jakarta');
$currentTime = new DateTime('now', $timezone);

$endTime = $currentTime->format('Y-m-d H:i:s');
$startTime = $currentTime->sub(new DateInterval('P30D'))->format('Y-m-d H:i:s');

$queryRr = mysqli_query($conn, "SELECT * FROM weather_station WHERE idws = '" . $idws . "' AND date BETWEEN '" . $startTime . "' AND '" . $endTime . "'");

while ($row = $queryRr->fetch_assoc()) {
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

$arrRrNow = array();
$cumulativeSum = 0;
foreach ($arrRainRate as $key => $value) {
    $cumulativeSum += $value;
    $arrRrNow[$key] = round($cumulativeSum, 0);
}

$keys = array_keys($arrRrNow);
$lastKey = end($keys);
$sum_month = (!empty($arrRrNow)) ? $arrRrNow[$lastKey] : 0;

$arr = array();
if ($query->num_rows > 0) {
    while ($value = $query->fetch_assoc()) {
        $arr['id'] = $value['id'];
        $arr['idws'] = $value['idws'];
        $arr['date'] = tanggal_indo($value['date']);
        $arr['ws'] = intval($value['windspeedkmh']);
        $arr['winddir'] = intval($value['winddir']);
        $arr['rain_rate'] = intval($value['rain_rate']) * 12;
        $arr['hum'] = intval($value['hum_out']);
        $arr['temp'] = intval($value['temp_out']);
        $arr['rrMonth'] = $sum_month;
        $arr['forecastRf'] = forecastRainTomorrow($value['idws'], $value['date'], $conn);
    }
} else {
    $arr['id'] = $idws;
    $arr['idws'] = $idws;
    $arr['date'] = "-";
    $arr['ws'] = 0;
    $arr['winddir'] = 0;
    $arr['rain_rate'] = 0;
    $arr['hum'] = 0;
    $arr['temp'] = 0;
    $arr['rrMonth'] = 0;
    $arr['forecastRf'] = '-';
}

die(json_encode($arr));

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

function forecastRainTomorrow($idws, $lastDate, $connection) {
    $date = date_create($lastDate);
    $lastDate = date_format($date, "Y-m-d");
    $next_day = date('Y-m-d', strtotime($lastDate . ' +1 day'));
    
    $total_rain = 0;
    $num_rows = 0; 
    $sqlForecast = mysqli_query($connection, "SELECT * FROM db_aws_bke WHERE DATE_FORMAT(datetime, '%Y-%m-%d') = '$next_day' AND idws = '" . $idws . "' ORDER BY datetime DESC");
    if ($sqlForecast->num_rows > 0) {
        while ($value = $sqlForecast->fetch_assoc()) {
            $total_rain += $value['accu_rf_forecast'];
            $num_rows++;
        }
        
        $average_rain = $total_rain/* / $num_rows*/;
        
        if ($average_rain < 1) {
            return "Cerah";
        } elseif ($average_rain >= 1 && $average_rain <= 10) {
            return "Berawan";
        } else {
            return "Hujan";
        }
    } else {
        return "-";
    }
}
