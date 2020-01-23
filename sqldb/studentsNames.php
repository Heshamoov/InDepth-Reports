<?php
include ('../config/dbConfig.php');

// $year = $_REQUEST ["year"];
$grade = $_REQUEST["grade"];

$sql = "SELECT DISTINCT(student_name) FROM new_marks WHERE grade = '$grade' ORDER BY student_name";

//// echo $sql;
$result = $conn->query($sql);
$oldStudents = array();
while ($row = mysqli_fetch_array($result)) {
//    echo $row["student_name"] . "\t";
    $oldStudents[] = $row["student_name"];
}

switch ($grade) {
    case "GR01":
        $grade = "GR 1";
        break;
    case "GR02":
        $grade = "GR 2";
        break;
    case "GR03":
        $grade = "GR 3";
        break;
    case "GR04":
        $grade = "GR 4";
        break;
    case "GR05":
        $grade = "GR 5";
        break;
    case "GR06":
        $grade = "GR 6";
        break;
    case "GR07":
        $grade = "GR 7";
        break;
    case "GR08":
        $grade = "GR 8";
        break;
    case "GR09":
        $grade = "GR 9";
        break;
}

$sql = "
    SELECT DISTINCT(last_name) FROM students 
        INNER JOIN batches ON students.batch_id = batches.id
        INNER JOIN courses ON batches.course_id = courses.id 
        WHERE course_name = '$grade' ORDER BY last_name\t";
$result = $conn->query($sql);
$newStudents = array();
while ($row = mysqli_fetch_array($result)) {
    $newStudents[] = $row["last_name"];
}

$students = array_merge($oldStudents, $newStudents);
array_unique($students);sort($students);

foreach ($students as $student)
    echo $student . "\t";


$conn->close();