<?php

include ('../config/dbConfig.php');




$sql = "SELECT DISTINCT grade, batch FROM marks";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result))
        echo $row['grade'] . " - " . $row['batch'] . "\n";
} else
    echo "Sorry, make sure that you've imported marks.\nError Message: " . $conn->error . ".\n";


$conn->close();
