<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT exam_groups.name term FROM exam_groups ORDER BY exam_groups.name ASC ;";

//echo $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['term'] . "\t";

$conn->close();

