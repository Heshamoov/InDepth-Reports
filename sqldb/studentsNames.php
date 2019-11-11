<?php
include ('../config/dbConfig.php');

$year = $_REQUEST ["year"];
$grade = $_REQUEST["grade"];

// echo "Students Year: $year<br>";
// echo "Students Grade: $grade<br>";

$sql = "SELECT DISTINCT(student_name) FROM new_marks WHERE $year and $grade ORDER BY student_name";

// echo $sql;
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result))
    echo $row["student_name"] . "\t";

$conn->close();