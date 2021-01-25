<?php
include ('../../../config/dbConfig.php');

$grade = $_REQUEST["grade"];

$sql = "SELECT DISTINCT(last_name) FROM students 
        inner join batches on students.batch_id = batches.id
        inner join courses on batches.course_id = courses.id
        WHERE courses.course_name = '$grade' ORDER BY last_name";

// echo $sql;
$result = $conn->query($sql);
$students_count = mysqli_num_rows($result);

echo "$students_count students in $grade\t";
while ($row = mysqli_fetch_array($result))
    echo $row["last_name"] . "\t";

$conn->close();