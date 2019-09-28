<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT name FROM batches "
        . "WHERE is_deleted = 0 "
        . "ORDER BY created_at";

$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['name'] . "\t";

$conn->close();
