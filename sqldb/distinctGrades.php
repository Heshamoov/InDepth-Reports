<?php

include ('../config/dbConfig.php');



$term = $_REQUEST["term"];
$sql = "SELECT DISTINCT grade FROM marks WHERE Exam_Group = '$term'";
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['grade'] . " ";

$conn->close();
