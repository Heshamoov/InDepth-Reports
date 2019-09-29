<?php
include ('../config/dbConfig.php');


$sql = "SELECT DISTINCT courses.course_name courses FROM "
        . "courses "
        . "WHERE is_deleted = 0 ORDER BY course_name";


$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['courses'] . "\t";

$conn->close();
