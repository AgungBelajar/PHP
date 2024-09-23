<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "u104419741_iot";
// REPLACE with Database user
$username = "u104419741_iot";
// REPLACE with Database user password
$password = "Srsssm5cd@";

$ws = "";
$wd = "";
$wc = "";
$t  = "";
$h  = "";
$r  = "";
$idws  = "";

$status = '';

    $ws = weather_station($_POST["ws"]);
    $wd = weather_station($_POST["wd"]);
    $wc = weather_station($_POST["wc"]);
    $t  = weather_station($_POST["t"]);
    $h  = weather_station($_POST["h"]);
    $r  = weather_station($_POST["r"]);
    $idws  = weather_station($_POST["idws"]);
    $datetime = weather_station($_POST["d"]);

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $sql = "INSERT INTO weather_station (datetime, ws, wd, wc, t, h, r, idws)
        VALUES ('" . $datetime . "', '" . $ws . "', '" . $wd . "', '" . $wc . "', '" . $t . "', '" . $h . "', '" . $r . "', '" . $idws . "')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();

function weather_station($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
