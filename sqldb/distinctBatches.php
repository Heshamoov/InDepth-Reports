<?php
include ('../config/dbConfig.php');




$grade = $_REQUEST["grade"];
$sql = "SELECT DISTINCT batches.name name FROM batches\n "
        . "INNER JOIN courses\n"
        . "ON batches.course_id = courses.id\n"
        . "WHERE courses.course_name = '$grade'";
//                echo $sql;



$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row['name'] . "?";
}

$conn->close();
