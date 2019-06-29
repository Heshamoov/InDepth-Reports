<?php

include ('../config/dbConfig.php');

$sql = "SELECT name FROM academic_years\n"
        . "ORDER BY created_at";

//echo  $sql;
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['name'] . "\n";

$conn->close();
