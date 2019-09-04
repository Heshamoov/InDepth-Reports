<?php

/*
 * Author: Jamsheed
 * Email: jamsheedkhalid35@gmail.com
 */

$servername = "localhost";
$username = "fedena";
$password = "fedenapw";
$DB = "alsanawabar";

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

