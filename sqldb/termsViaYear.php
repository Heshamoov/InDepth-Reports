<?php

include ('../config/dbConfig.php');

$year = $_REQUEST["year"];
// echo $year;

$sql = "SELECT DISTINCT(exam_name)
		FROM new_marks 
		WHERE acd_code = '$year'";
		

// echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['exam_name'] . "\t";

$conn->close();