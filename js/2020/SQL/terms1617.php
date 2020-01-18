<?php

include ('../../../config/dbConfig.php');

$sql = "
select distinct (exam_groups.name)
from exam_groups
         inner join batches on exam_groups.batch_id = batches.id
         inner join academic_years on batches.academic_year_id = academic_years.id

where academic_years.name = '2016 - 2017'
";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['name'] . "\t";

$conn->close();