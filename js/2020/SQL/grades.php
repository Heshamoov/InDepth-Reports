<?php

include ('../../../config/dbConfig.php');

$sql = "SELECT DISTINCT course_name FROM courses WHERE course_name like 'GR%' ORDER BY course_name";

$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['course_name'] . "\t";

$conn->close();
