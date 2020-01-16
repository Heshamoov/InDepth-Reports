<?php
include ('../config/dbConfig.php');

// $year = $_REQUEST ["year"];
$grade = $_REQUEST["grade"];

// echo "Students Year: $year<br>";
// echo "Students Grade: $grade<br>";

$sql = "SELECT DISTINCT(last_name) FROM students 
		inner join batches on students.batch_id = batches.id
		inner join courses on batches.course_id = courses.id
		WHERE courses.course_name = '$grade' ORDER BY student_name";

// echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result))
    echo $row["last_name"] . "\t";

$conn->close();