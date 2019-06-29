<?php

include ('../config/dbConfig.php');




$sql = "SELECT DISTINCT name FROM exam_groups ORDER BY name ";
$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row["name"] . "\t";

$conn->close();
