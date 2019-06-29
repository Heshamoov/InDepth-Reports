<?php

include ('../config/dbConfig.php');




$sql = "SELECT  DISTINCT name from student_categories\n"
        . "WHERE is_deleted = 0\n"
        . "ORDER BY created_at ";



//      echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['name'] . "\t";

$conn->close();
