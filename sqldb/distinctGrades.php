<?php
include ('../config/dbConfig.php');

$year = $_REQUEST["year"];

$sql = "SELECT DISTINCT courses.course_name courses FROM\n"
        . "courses \n"
        . "JOIN batches on courses.id = batches.course_id \n";

if ($year == "")
    $sql .= "JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE courses.is_deleted = 0 ";
else
    $sql .= "JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE $year  AND courses.is_deleted = 0 ";

    $sql .= "ORDER BY courses.course_name ASC;";

// echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['courses'] . "\t";

$conn->close();
