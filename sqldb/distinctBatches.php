<?php

include ('../config/dbConfig.php');

$year = $_REQUEST["year"];
$grade = $_REQUEST["grade"];

$sql = "SELECT DISTINCT batches.name batch \n"

    . "FROM batches  \n"

    . "INNER JOIN courses  ON batches.course_id = courses.id  \n";

if ($year == "" && $grade == "")
{
	$sql .= "JOIN academic_years ON batches.academic_year_id = academic_years.id
	 		WHERE batches.is_deleted = 0 ";
}
else
{
    $sql .= "JOIN academic_years ON batches.academic_year_id = academic_years.id 
             WHERE academic_years.name = '$year' AND courses.course_name = '$grade'  
			 AND batches.is_deleted = 0 ";
}

$sql .= "ORDER BY batches.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['batch'] . "\t";

$conn->close();
