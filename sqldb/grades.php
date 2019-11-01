<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT grade FROM new_marks ORDER BY grade";

$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['grade'] . "\t";

$conn->close();
