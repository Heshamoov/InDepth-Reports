<?php

include ('../config/dbConfig.php');

$student = $_REQUEST["student"];
// echo $student . "sdfsdfsdfsdf<br>";

$sql = "SELECT DISTINCT(acd_code)
		FROM new_marks ";

if($student != 'None' AND $student != '')
	$sql .= " WHERE student_name = '$student'";
		

// echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['acd_code'] . "\t";

$conn->close();