<?php

include ('../config/dbConfig.php');


$grade = $_REQUEST["grade"];
$subject = $_REQUEST["subject"];

$sql = "SELECT Subject FROM marks WHERE Grade = '$grade' AND Subject = '$subject'";
//    echo $sql;

$result = $conn->query($sql);
$rowcount = mysqli_num_rows($result);
echo $rowcount;

$conn->close();
