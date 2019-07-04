<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["year"];
$grades = $_REQUEST["grade"];



$sql = "SELECT DISTINCT exam_groups.name term FROM exam_groups\n"

    . "LEFT JOIN batches ON batches.id = exam_groups.batch_id \n"

    . "LEFT JOIN subjects ON subjects.batch_id = batches.id\n"

    . "LEFT JOIN courses ON courses.id = batches.course_id\n";

if ($years == "" && $grades == "" )
    $sql = $sql . " LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id  ";
else
    $sql = $sql . "LEFT JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE academic_years.name = '$years' AND courses.course_name = '$grades' ";

    $sql = $sql . " ORDER BY exam_groups.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['term'] . "\t";

$conn->close();

