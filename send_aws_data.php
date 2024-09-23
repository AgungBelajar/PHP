<?php
$servername = "localhost";

// REPLACE with your Database name

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

// fetch the data sent from Python code
$idws = 66;
// $date = $_POST['date'];
// $windspeedmph = $_POST['windspeedmph'];
// $winddir = $_POST['winddir'];
// $rain_rate = $_POST['rain_rate'];
// $temp_in = $_POST['temp_in'];
// $temp_out = $_POST['temp_out'];
// $hum_in = $_POST['hum_in'];
// $hum_out = $_POST['hum_out'];
// $uv = $_POST['uv'];
// $wind_gust = $_POST['wind_gust'];
// $air_press_rel = $_POST['air_press_rel'];
// $air_pressd_abs = $_POST['air_press_abs'];
// $solar_radiation = $_POST['solar_radiation'];


//     $sql = "INSERT INTO weather_station (idws, date, windspeedmph, winddir, rain_rate , temp_in, temp_out, hum_in, hum_out, uv, wind_gust, air_press_rel, air_press_abs, solar_radiation)
//             VALUES ('" . $idws . "', 
//                     '" . $date . "', 
//                     '" . $windspeedmph . "', 
//                     '" . $winddir . "', 
//                     '" . $rain_rate . "', 
//                     '" . $temp_in . "', 
//                     '" . $temp_out . "', 
//                     '" . $hum_in . "',
//                     '" . $hum_out . "',
//                     '" . $uv . "',
//                     '" . $wind_gust . "',
//                     '" . $air_press_rel . "',
//                     '" . $air_pressd_abs . "',
//                     '" . $solar_radiation . "')";
    
    
//     if ($conn->query($sql) === TRUE) {
//         echo "New record created successfully";
//     } else {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }

// fetch the data sent from Python code
$rows = 'kosong';
$data = json_decode(file_get_contents('php://input'), true);
$rows = $data['data'];


echo $rows;
// loop through data and insert into database
// echo 'test';
foreach ($rows as $row) {
  $date = $row['date'];
  $windspeedmph = $row['windspeedmph'];
  $winddir = $row['winddir'];
  $rain_rate = $row['rain_rate'];
  $temp_in = $row['temp_in'];
  $temp_out = $row['temp_out'];
  $hum_in = $row['hum_in'];
  $hum_out = $row['hum_out'];
  $uv = $row['uv'];
  $wind_gust = $row['wind_gust'];
  $air_press_rel = $row['air_press_rel'];
  $air_press_abs = $row['air_press_abs'];
  $solar_radiation = $row['solar_radiation'];
  
  echo $date;
  
  echo 'test';
  
  $sql = "INSERT INTO weather_station (date, windspeedmph, winddir, rain_rate, temp_in, temp_out, hum_in, hum_out, uv, wind_gust, air_press_rel, air_press_abs, solar_radiation) 
          VALUES ('$date', '$windspeedmph', '$winddir', '$rain_rate', '$temp_in', '$temp_out', '$hum_in', '$hum_out', '$uv', '$wind_gust', '$air_press_rel', '$air_press_abs', '$solar_radiation')";

  if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}
?>
