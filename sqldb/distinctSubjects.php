<?php

include ('../config/dbConfig.php');

$year = $_REQUEST["year"];
$grade = $_REQUEST["grade"];

$sql = "SELECT DISTINCT subjects.name subject  \n"

    . "FROM subjects \n"

    . "JOIN batches ON subjects.batch_id = batches.id \n"

    . "JOIN courses ON batches.course_id = course_id \n";

if ($year == "" && $grade == "" ){
    $sql .= "JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE subjects.is_deleted = 0 ";}
else{
    $sql .= "JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE academic_years.name = '$year' "
. "AND courses.course_name = '$grade'  AND subjects.is_deleted = 0 ";}

$sql .="ORDER BY subjects.name ASC ;";

// echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['subject'] . "\t";

$conn->close();

