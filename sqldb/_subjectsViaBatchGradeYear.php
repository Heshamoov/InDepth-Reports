<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];

$sql = "SELECT DISTINCT subjects.name subject  \n"

    . "FROM subjects\n"

    . "left JOIN batches ON batches.id = subjects.batch_id\n"

    . "left JOIN courses ON course_id = batches.course_id\n";

if ($years == "" && $grades == "" && $batches == "")
    $sql = $sql . " LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id WHERE subjects.is_deleted = 0 ";
else
    $sql = $sql . "LEFT JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE $years $grades $batches AND subjects.is_deleted = 0";

    $sql = $sql . " ORDER BY subjects.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['subject'] . "\t";

$conn->close();

