<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subjects"];


$sql = "SELECT DISTINCT exam_groups.name term FROM exam_groups\n"

    . "JOIN batches ON exam_groups.batch_id = batches.id \n"

    . "JOIN subjects ON batches.id = subjects.batch_id \n"

    . "JOIN courses ON batches.course_id = courses.id \n";

if ($years == "" && $grades == "" && $batches == "" && $subjects == "")
    $sql = $sql . " JOIN academic_years ON batches.academic_year_id = academic_years.id ";
else
    $sql = $sql . " JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE $years $grades $batches  $subjects ";

    $sql = $sql . " ORDER BY exam_groups.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['term'] . "\t";

$conn->close();

