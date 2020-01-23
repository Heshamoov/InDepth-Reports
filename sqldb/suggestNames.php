<?php
include ('../config/dbConfig.php');

$student = $_REQUEST["student"];
//echo $student;
$words = explode(" ", $student);
$First = $words[0];
$Last = end($words);

$sql = "SELECT DISTINCT(student_name) FROM new_marks WHERE student_name LIKE '$First%$Last' ORDER BY 'student_name'";

//echo $sql . "\t";
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result)) {
    echo $row["student_name"] . "\t";
}