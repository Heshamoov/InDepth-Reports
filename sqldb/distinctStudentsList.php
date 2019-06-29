<?php

include ('../config/dbConfig.php');

$sql = "SELECT moe FROM students";
$result = $conn->query($sql);
echo $sql;

while ($row = mysqli_fetch_array($result))
    echo $row["moe"] . " ";

$conn->close();
