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

$idws = 15;

$query = mysqli_query($conn, "SELECT date FROM weather_station WHERE idws = '" . $idws . "' ORDER BY id DESC LIMIT 1");

$timezone = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
$timeNow = $timezone->format('Y-m-d H:i:s');

if ($query->num_rows > 0) {
    while ($value = $query->fetch_assoc()) {
        $last_data = $value['date'];
    }
}

$last_data_raw = new DateTime($last_data);
$last_data_raw->add(new DateInterval('PT1H'));
$last_data_fix = $last_data_raw->format('Y-m-d H:i:s');

setlocale(LC_TIME, 'id_ID'); // Set the locale to Indonesian
$last_data_date = new DateTime($last_data);
$formattedDate = strftime('%H:%M:%S, %d %B %Y', $last_data_date->getTimestamp());

$timezone = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
$timeNow = $timezone->format('Y-m-d H:i:s');

if ($timeNow > $last_data_fix) {
    $token   = '6190266732:AAGNJulyMe7v0CHDa010pFMztw6Vuo57ZME';
    $message = 'Data terakhir dikirim dari AWS IoT SRS adalah pada pukul ' . $formattedDate;
    $chat_id = -1001316663954;
    
    $request_params = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML',
    ];
    
    $request_url = "https://api.telegram.org/bot" . $token . '/sendMessage?' . http_build_query($request_params);
    var_dump(file_get_contents($request_url));
}

$conn->close();
