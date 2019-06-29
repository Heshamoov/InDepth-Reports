<?php

include ('../config/dbConfig.php');



$sql = "SELECT DISTINCT name FROM subjects WHERE is_deleted = 0";
$result = $conn->query($sql);


while ($row = mysqli_fetch_array($result))
    echo $row['name'] . "\t";

$conn->close();
