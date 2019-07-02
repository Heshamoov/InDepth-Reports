<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$grades = $_REQUEST["grades"];

$sql = "SELECT DISTINCT batches.name batch \n"

    . "FROM batches  \n"

    . "INNER JOIN courses  ON batches.course_id = courses.id  \n";

if ($years == "" && $grades == "")
    $sql = $sql . " LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id  WHERE batches.is_deleted = 0 ";
else
    $sql = $sql . "LEFT JOIN academic_years ON batches.academic_year_id = academic_years.id WHERE $years $grades  AND batches.is_deleted = 0";

    $sql = $sql . " ORDER BY batches.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['batch'] . "\t";

$conn->close();
