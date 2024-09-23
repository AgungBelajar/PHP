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

$rsVersion = "SELECT * FROM station_version";

$result    = mysqli_query($conn, $rsVersion);
if (mysqli_num_rows($result) > 0) {
    while ($value = mysqli_fetch_array($result)) {
        $id = $value['id'];
        $last_version = $value['version'];
    }
}

$psStation = $_GET['version'];

if ($psStation != $last_version) {
    $query = mysqli_query($conn, "SELECT * FROM weather_station_list WHERE flags = 1");

    if ($query->num_rows > 0) {
        $inc = 0;
        while ($value = $query->fetch_assoc()) {
            $arrId[]  = $value['id'];
            $arrLoc[] = $value['loc'];
            $inc++;
        }

        $arr['status'] = 1;
        $arr['version'] = $last_version;
        $arr['listData'] = array(
            'id' => $arrId,
            'loc' => $arrLoc,
        );
        $arr['message'] = 'Data berhasil diupdate';
    }
    echo json_encode($arr);
} else {
    $arr['status'] = 0;
    $arr['message'] = 'List station sudah paling ter-update';
    echo json_encode($arr);
}
