<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT exam_name FROM new_marks ORDER BY exam_name;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['exam_name'] . "\t";

$conn->close();