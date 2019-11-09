<?php

include ('../config/dbConfig.php');

$year = $_REQUEST["year"];
$student = $_REQUEST["student"];
// echo $year;
// echo $student . "sdfsdfsdfsdf<br>";

$sql = "SELECT DISTINCT(exam_name)
		FROM new_marks 
		WHERE acd_code = '$year'";

if($student != 'None' AND $student != '')
	$sql .= " AND student_name = '$student'";
		

// echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['exam_name'] . "\t";

$conn->close();