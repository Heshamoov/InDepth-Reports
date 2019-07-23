<?php

/*
 * Author: Jamsheed
 * Email: jamsheedkhalid35@gmail.com
 */

$servername = "localhost";
$username = "reports2018";
$password = "Indepth2018";
$DB = "indepth_pro";

$conn = new mysqli($servername, $username, $password, $DB);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

//   -----------arabic encoding----------------
$sSQL = 'SET CHARACTER SET utf8';
mysqli_query($conn, $sSQL)
        or die('Can\'t charset in DataBase');
//    -----------arabic encoding-------------

?>

