<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$grades = $_REQUEST["grades"];

$sql = "SELECT DISTINCT subjects.name subject  \n"

    . "FROM subjects\n"

    . "JOIN batches ON subjects.batch_id = batches.id \n"

    . "JOIN courses ON batches.course_id = courses.id \n";

if ($years == "" && $grades == "")
    $sql = $sql . " JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE subjects.is_deleted = 0 ";
else
    $sql = $sql . " JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE $years $grades AND subjects.is_deleted = 0";

    $sql = $sql . " ORDER BY subjects.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['subject'] . "\t";

$conn->close();

