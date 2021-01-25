<?php

include ('../../../config/dbConfig.php');

$sql = "
	SELECT DISTINCT(exam_groups.name)
	FROM exam_groups
         INNER JOIN batches ON exam_groups.batch_id = batches.id
         INNER JOIN academic_years ON batches.academic_year_id = academic_years.id

	WHERE academic_years.name = '2020 - 2021'
";

// echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['name'] . "\t";

$conn->close();