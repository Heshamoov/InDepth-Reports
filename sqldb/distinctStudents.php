<?php

include ('../config/dbConfig.php');

$grade = $_REQUEST["grade"];
$batch = $_REQUEST["batch"];

$sql = "SELECT moe FROM students where grade = '$grade' and batch = '$batch'";
$result = $conn->query($sql);
//        echo $sql;

while ($row = mysqli_fetch_array($result))
    echo $row["moe"] . " ";

$conn->close();
